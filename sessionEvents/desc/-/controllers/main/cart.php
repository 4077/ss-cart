<?php namespace ss\cart\sessionEvents\desc\controllers\main;

class Cart extends \Controller
{
    public function view()
    {
        $v = $this->v();

        $changeProductId = $this->data('product_id');

        $cartInstance = $this->data('cart/instance');
        $cartData = $this->data('cart/data');

        $highlightClass = $this->data('highlight_class');
        $skipInTotalCost = $this->data('skip_in_total_cost');

        $totalCost = 0;

        if ($products = ap($cartData, 'products')) {
            foreach ($products as $productId => $productData) {
//                $product = \ss\models\Product::find($productId);

                $cost = $productData['price'] * $productData['quantity'];

                if ($productId != $changeProductId || !$skipInTotalCost) {
                    $totalCost += $cost;
                }

                $priceString = '';

                if ($productData['discount']) {
                    $priceString .= number_format__($productData['price_without_discount']) . ' -' . $productData['discount'] . '% ';
                }

                $priceString .= number_format__($productData['price']);

                $v->assign('product', [
                    'HIGHLIGHT_CLASS' => $changeProductId == $productId ? $highlightClass : '',
                    'NAME'            => $productData['name'],
                    'PRICE'           => $priceString,
                    'QUANTITY'        => trim_zeros($productData['quantity']),
                    'UNITS'           => $productData['units'],
                    'COST'            => number_format__($cost)
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

        $this->css();

        return $v;
    }
}
