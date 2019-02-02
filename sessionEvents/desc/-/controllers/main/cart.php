<?php namespace ss\cart\sessionEvents\desc\controllers\main;

class Cart extends \Controller
{
    public function view()
    {
        $v = $this->v();

        $itemKey = $this->data('item_key');

        $cartInstance = $this->data('cart/instance');
        $cartData = $this->data('cart/data');

        $highlightClass = $this->data('highlight_class');
        $skipInTotalCost = $this->data('skip_in_total_cost');

        $totalCost = 0;

        if ($items = ap($cartData, 'items')) {
            foreach ($items as $key => $item) {
                $cost = $item['price'] * $item['quantity'];

                if ($product = unpack_model($item['model'] ?? null)) {
                    if ($key != $itemKey || !$skipInTotalCost) {
                        $totalCost += $cost;
                    }

                    $v->assign('item', [
                        'HIGHLIGHT_CLASS' => $key == $itemKey ? $highlightClass : '',
                        'NAME'            => $item['name'],
                        'PRICE'           => number_format__($item['price']),
                        'QUANTITY'        => trim_zeros($item['quantity']),
                        'COST'            => number_format__($cost)
                    ]);
                }
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
