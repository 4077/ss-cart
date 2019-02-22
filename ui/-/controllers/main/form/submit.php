<?php namespace ss\cart\ui\controllers\main\form;

class Submit extends \Controller
{
    public function run()
    {
        $notFilledFields = $this->getNotFilledNecessaryFields();

        if ($notFilledFields) {
            $this->widget('<:', 'necessaryFieldsError', [
                'fields' => $notFilledFields
            ]);
        } else {
            $clientInfoUpdate = [];
            foreach ($this->c('<:getFieldsAliases') + ['address'] as $field) { // hardcode
                $clientInfoUpdate[$field] = $this->data('fields/' . $field);
            }

            $clientInfo = &cart($this->_instance())->s('client_info');

            if (empty($clientInfo['delivery'])) {
                unset($clientInfo['address']);
            }

            ra($clientInfo, $clientInfoUpdate);

            $order = $this->createOrder();

            $this->sendClientLetter($order);
            $this->sendOwnerLetter($order);

            $cart = cart($this->_instance());

            sstm()->events->trigger('cats/ui/createOrder', [
                'cart' => [
                    'instance' => $cart->instance,
                    'data'     => $cart->s()
                ]
            ]);

            $this->showThxView();

            cart($this->_instance())->reset();
        }
    }

    private function showThxView()
    {
        $this->c('~|')->jquery()->replace($this->c('>thx:view|'));
    }

    private function createOrder()
    {
        $clientInfo = unmap(cart($this->_instance())->s('client_info'), 'delivery'); // hardcode

        $order = \ss\models\Order::create([
                                              'items' => $this->getOrderItemsData()
                                          ]);

        $order->client()->create($clientInfo);

        return $order;
    }

    private function getOrderItemsData()
    {
        $output = [];

        $products = cart($this->_instance())->getProducts();

        foreach ($products as $productId => $productData) {
            $outputItem = $productData;

            if ($product = \ss\models\Product::find($productId)) {
                aa($outputItem, [
                    'props' => _j($product->props)
                ]);
            }

            $output[] = $outputItem;
        }

        return j_($output);
    }

    private function sendClientLetter($order)
    {
        $cart = cart($this->_instance());

        $clientInfo = $cart->s('client_info');

        if ($clientInfo['email']) {
            $notifySettings = $cart->settings('notify');
            $mailerHandlerPath = $cart->settings('mailer');

            $mailer = mailer($mailerHandlerPath);

            $mailer->AddAddress($clientInfo['email']);

            $mailer->Subject = $this->tokenize(ap($notifySettings, 'client_letter/subject'), $order);
            $mailer->Body = $this->getClientLetterBody($order);

            $mailer->queue();
        }
    }

    private function sendOwnerLetter($order)
    {
        $cart = cart($this->_instance());

        $notifySettings = $cart->settings('notify');
        $recipients = ap($notifySettings, 'recipients');

        foreach ($recipients as $recipient) {
            $mailerHandlerPath = $cart->settings('mailer');

            $mailer = mailer($mailerHandlerPath);

            $mailer->AddAddress($recipient);

            $mailer->Subject = $this->tokenize(ap($notifySettings, 'owner_letter/subject'), $order);
            $mailer->Body = $this->getOwnerLetterBody($order);

            $mailer->queue();
        }
    }

    private function tokenize($string, $order)
    {
        return str_replace(['%order_id'], [$order->id], $string);
    }

    private function getNotFilledNecessaryFields()
    {
        $notFilledFields = [];

        $necessaryFields = $this->c('<:getNecessaryFieldsAliases');

        foreach ($necessaryFields as $field) {
            if (!$this->data('fields/' . $field)) {
                $notFilledFields[] = $field;
            }
        }

        return $notFilledFields;
    }

    private function getClientLetterBody($order)
    {
        $notifySettings = cart($this->_instance())->settings('notify');

        $v = $this->v('>letters/clientLetter');

        $v->assign([
                       'STORE_URL'   => abs_url(),
                       'STORE_NAME'  => ap($notifySettings, 'store_name'),
                       'CLIENT_DATA' => $this->getLetterClientDataView(),
                       'ORDER_DATA'  => $this->getLetterOrderDataView(),
                       'ORDER_ID'    => $order->id
                   ]);

        return $v->render();
    }

    private function getOwnerLetterBody($order)
    {
        $v = $this->v('>letters/ownerLetter');

        $v->assign([
                       'ORDER_ID'    => $order->id,
                       'ORDER_HREF'  => abs_url('/cp/orders', $order->id),
                       'CLIENT_DATA' => $this->getLetterClientDataView(),
                       'ORDER_DATA'  => $this->getLetterOrderDataView()
                   ]);

        return $v->render();
    }

    private $letterClientInfoView;

    private function getLetterClientDataView()
    {
        if (!$this->letterClientInfoView) {
            $v = $this->v('>letters/blocks/clientData');

            $clientInfo = cart($this->_instance())->s('client_info');

            foreach ($this->c('<:getFields') as $field) { // hardcode
                if (!empty($clientInfo[$field['alias']])) {
                    $v->assign('row', [
                        'CAPTION' => $field['label'],
                        'VALUE'   => $clientInfo[$field['alias']]
                    ]);
                }
            }

            $this->letterClientInfoView = $v;
        }

        return $this->letterClientInfoView;
    }

    private function getLetterOrderDataView()
    {
        $v = $this->v('>letters/blocks/orderData');

        $cart = cart($this->_instance());

        $products = $cart->getProducts();

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

            $v->assign('item', [
                'NAME'     => $productData['name'],
                'UNITS'    => $productData['units'],
                'PRICE'    => $productData['price_display'] ? number_format__($productData['price']) : '—',
                'QUANTITY' => $productData['quantity'],
                'COST'     => $productData['price_display'] ? number_format__($cost) : '—',
            ]);

            if ($productData['price_display']) {
                if ($discount) {
                    $v->assign('item/price_without_discount', [
                        'VALUE'    => number_format__($productData['price_without_discount']),
                        'DISCOUNT' => $discount
                    ]);
                }
            }

            if ($product) {
                $imageSmall = $this->c('\std\images~:first', [
                    'model'       => $product,
                    'query'       => '100 100 fit',
                    'cache_field' => 'images_cache'
                ]);

                $imageBig = $this->c('\std\images~:first', [
                    'model'       => $product,
                    'cache_field' => 'images_cache'
                ]);

                if ($imageSmall && $imageBig) {
                    $imageAbsPath = public_path($imageSmall->versionModel->file_path);
                    $imageAbsUrl = abs_url($imageBig->versionModel->file_path);

                    if (file_exists($imageAbsPath)) {
                        $imgBinary = fread(
                            fopen($imageAbsPath, 'r'),
                            filesize($imageAbsPath)
                        );

                        $imgStr = base64_encode($imgBinary);

                        $v->assign('item/image', [
                            'SRC'       => $imageAbsUrl,
                            'THUMB_SRC' => 'data:image/jpg;base64,' . $imgStr
                        ]);
                    }
                }
            }
        }

        $v->assign([
                       'TOTAL_COST_LABEL' => 'Итого',
                       'TOTAL_COST'       => number_format__($totalCost)
                   ]);

        if ($globalDiscount = $cart->settings('global_discount')) {
            $v->assign('global_discount', [
                'TOTAL_COST_LABEL' => 'Итого со скидкой',
                'TOTAL_COST'       => number_format__($totalCost / 100 * (100 - $globalDiscount))
            ]);
        }

        return $v;
    }
}
