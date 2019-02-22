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

        $products = $cart->getProducts();

        /**
         * @var $carouselSvc \ss\components\products\ui\controllers\Carousel
         */
        $carouselSvc = $this->c('\ss\components\products\ui carousel');

        $hasOnceWithHiddenPrice = false;

        $totalCost = 0;

        foreach ($products as $productId => $productData) {
            $product = \ss\models\Product::find($productId);

            $discount = $productData['discount'];
            $price = $productData['price'];

            $cost = $price * $productData['quantity'];

            if ($productData['price_display']) {
                $totalCost += $cost;
            } else {
                $hasOnceWithHiddenPrice = true;
            }

            $requestData = ['product' => xpack_model($product)];

            $v->assign('product', [
                'ID'               => $productId,
                'NUMBER'           => $carouselSvc->n,
                'NAME'             => $productData['name'],
                'QUANTIFY'         => $this->c('\std\ui\quantify~:view|' . $cartInstance . '/cart/' . $productId, [
                    'inc_call'       => ['>xhr:incQuantity|', $requestData],
                    'dec_call'       => ['>xhr:decQuantity|', $requestData],
                    'update_call'    => ['>xhr:setQuantity|', $requestData],
                    'update_timeout' => 800,
                    'quantity'       => (float)$productData['quantity']
                ]),
                'UNITS'            => $productData['units'],
                'PRICE'            => $productData['price_display'] ? number_format__($productData['price']) : '—',
                'QUANTITY'         => $productData['quantity'],
                'COST'             => $productData['price_display'] ? number_format__($cost) : '—',
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

            if ($productData['price_display']) {
                $v->assign('price_display');

                if ($discount) {
                    $v->assign('product/price_without_discount', [
                        'VALUE'    => number_format__($productData['price_without_discount']),
                        'DISCOUNT' => $discount
                    ]);
                }
            }

            if ($product) {
                $image = $this->c('\std\images~:first', [
                    'model'       => $product,
                    'query'       => '100 100',
                    'cache_field' => 'images_cache'
                ]);

                if ($image) {
                    $v->assign('product/image', [
                        'CONTENT' => $image->view
                    ]);
                }

                if ($pivotPack = $productData['pivot'] ?? false) {
                    if ($pivot = unpack_model($pivotPack)) {
                        $tile = \ss\components\products\tile($product, $pivot);

                        $carouselSvc->addTile($tile);
                    }
                }
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
        $v->assign('CAROUSEL', $carouselSvc->render(['back_route' => $cart->settings('ui/cart/route')]));

        $this->css(':\css\std~');

        $widgetData = [
            '.w' => [
                'carousel' => $this->_w('\ss\components\products\ui carousel:')
            ],
            '.r' => [
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
