<?php namespace ss\cart\sessionEvents\triggers\controllers\notify;

class OrderDataLetter extends \Controller
{
    public function view()
    {
        $v = $this->v();

        $cartInstance = $this->data('cart/instance');
        $cartData = $this->data('cart/data');

        $totalCost = 0;

        if ($products = ap($cartData, 'products')) {
            foreach ($products as $productId => $productData) {
                $cost = $productData['price'] * $productData['quantity'];

                $totalCost += $cost;

                $priceString = '';

                if ($productData['discount']) {
                    $priceString .= number_format__($productData['price_without_discount']) . ' -' . $productData['discount'] . '% ';
                }

                $priceString .= number_format__($productData['price']);

                $v->assign('product', [
                    'NAME'     => $productData['name'],
                    'PRICE'    => $priceString,
                    'QUANTITY' => trim_zeros($productData['quantity']),
                    'UNITS'    => $productData['units'],
                    'COST'     => number_format__($cost)
                ]);
            }

            $v->assign([
                           'TOTAL_COST_LABEL' => 'Итого',
                           'TOTAL_COST'       => number_format__($totalCost)
                       ]);

            if ($globalDiscount = cart($cartInstance)->settings('global_discount')) {
                $v->assign('global_discount', [
                    'TOTAL_COST_LABEL' => 'Итого со скидкой',
                    'TOTAL_COST'       => number_format__($totalCost / 100 * (100 - $globalDiscount))
                ]);
            }
        }

        return $v;
    }
}
