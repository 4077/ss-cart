<?php namespace ss\cart\ui\controllers\main;

class Table extends \Controller
{
    public function reload()
    {
        $this->jquery('|')->replace($this->view());
    }

    public function view()
    {
        $v = $this->v('|');

        $cartInstance = $this->_instance();

        $cart = cart($cartInstance);

        $noContainer = $cart->settings('ui/cart/no_container');

        $items = $cart->getItems();

        if (empty($noContainer)) { // костыль
            /**
             * @var $carouselSvc \ss\components\products\ui\controllers\Carousel
             */
            $carouselSvc = $this->c('\ss\components\products\ui carousel');

            /**
             * @var $cTileApp \ss\components\products\ui\controllers\tile\App
             */
            $cTileApp = $this->c('\ss\components\products\ui tile/app');
        }

        $hasOnceWithHiddenPrice = false;

        $totalCost = 0;

        $number = 0;
        foreach ($items as $itemKey => $item) {
            $cost = $item['price'] * $item['quantity'];

            $requestData = [
                'item_key' => $itemKey
            ];

            $model = unpack_model($item['model'] ?? null);

            $itemData = [
                'price_display'     => true,
                'sell_by_alt_units' => false
            ];

            if (empty($noContainer)) { // костыль
                if ($model instanceof \ss\models\Product) {
                    if ($container = $model->cat) {
                        $pivot = ss()->cats->getFirstEnabledComponentPivot($container);

                        $tileData = $cTileApp->renderTileData($model, $pivot);

                        remap($itemData, $tileData, 'price_display, sell_by_alt_units');
                    }
                }
            }

            if ($itemData['price_display']) {
                $totalCost += $cost;
            } else {
                $hasOnceWithHiddenPrice = true;
            }

            $v->assign('item', [
                'KEY'              => $itemKey,
                'NUMBER'           => $number,
                'NAME'             => $item['name'],
                'QUANTIFY'         => $this->c('\std\ui\quantify~:view|' . $cartInstance . '/cart/' . $itemKey, [
                    'inc_call'       => ['>xhr:incQuantity|', $requestData],
                    'dec_call'       => ['>xhr:decQuantity|', $requestData],
                    'update_call'    => ['>xhr:setQuantity|', $requestData],
                    'update_timeout' => 800,
                    'quantity'       => (float)$item['quantity']
                ]),
                'PRICE'            => $itemData['price_display'] ? number_format__($item['price']) : '—',
                'QUANTITY'         => $item['quantity'],
                'COST'             => $itemData['price_display'] ? number_format__($cost) : '—',
                'DELETE_BUTTON_TD' => $this->c('\std\ui button:view:td', [
                    'path'  => '>xhr:delete|',
                    'data'  => $requestData,
                    'class' => 'delete',
                    'attrs' => [
                        'width' => '20'
                    ],
                    'icon'  => 'fa fa-close'
                ]),
                'DELETE_BUTTON'    => $this->c('\std\ui button:view', [
                    'path'  => '>xhr:delete|',
                    'data'  => $requestData,
                    'class' => 'delete_button',
                    'icon'  => 'fa fa-close'
                ])
            ]);

            if ($itemData['price_display']) {
                $v->assign('price_display');
            }

            if ($model) {
                $image = $this->c('\std\images~:first', [
                    'model'       => $model,
                    'query'       => '100 100',
                    'cache_field' => 'images_cache'
                ]);

                if ($image) {
                    $v->assign('item/image', [
                        'CONTENT' => $image->view
                    ]);
                }

                if ($model instanceof \ss\models\Product) {
                    if ($propsString = $this->getPropsString($model)) {
                        $v->assign('item/props', [
                            'CONTENT' => $propsString
                        ]);
                    }

                    if (empty($noContainer)) { // костыль
                        if ($pivot = ss()->cats->getFirstEnabledComponentPivot($model->cat)) {
                            $tileData = $cTileApp->renderTileData($model, $pivot, true);

                            // отключил в корзине это всё принудительно, так как изменение колва или добавление в корзину перезагружает таблицу и карусель пропадает
                            ra($tileData, [
                                'quantify'                        => false,
                                'cartbutton/display'              => false,
                                'stock_info/in_stock/display'     => false,
                                'stock_info/not_in_stock/display' => false
                            ]);

                            $carouselSvc->addProduct($model->id, $this->c('\ss\components\products\ui product:view', $tileData));
                        }
                    }
                }

                $v->append('item', [
                    'UNITS' => $itemData['sell_by_alt_units'] ? $model->alt_units : $model->units
                ]);

                $number++;
            }
        }

        if ($totalCost) {
            $v->assign('total_cost');

            if ($globalDiscount = $cart->settings('global_discount')) {
                $v->assign('global_discount', [
                    'TOTAL_COST' => number_format__($totalCost / 100 * (100 - $globalDiscount))
                ]);
            }

            if ($hasOnceWithHiddenPrice) {
                $v->assign('total_cost_info', [
                    'CONTENT' => 'Указана не полная сумма заказа, так как для некоторых товаров цена не обозначена. Полную сумму вы сможете уточнить, связавшись с менеджером.'
                ]);
            }
        } else {
            if ($hasOnceWithHiddenPrice) {
                $v->assign('total_cost_info', [
                    'CONTENT' => 'Общую сумму заказа Вы сможете уточнить, связавшись с менеджером.'
                ]);
            }
        }

        $v->assign('TOTAL_COST', number_format__($totalCost));

        if (empty($noContainer)) { // костыль
            $v->assign('CAROUSEL', $carouselSvc->render([
                                                            'back_route' => $cart->settings('ui/cart/route')
                                                        ]));
        }

        $this->css(':\css\std~');

        $widgetData = [
            'noContainer' => $noContainer, // костыль
            '.w'          => [
                'carousel' => $this->_w('\ss\components\products\ui carousel:')
            ],
            '.r'          => [
                'reload' => $this->_p('>xhr:reload|')
            ]
        ];

        $this->widget(':', $widgetData);

        return $v;
    }

    private function getPropsString($product)
    {
        $props = _j($product->props);

        $list = [];

        foreach ((array)$props as $prop) {
            if ($prop['label']) {
                $list[] = $prop['label'] . ': ' . $prop['value'];
            } else {
                $list[] = $prop['value'];
            }
        }

        return implode('; ', $list);
    }
}
