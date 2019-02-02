<?php namespace ss\cart\sessionEvents\triggers\controllers\notify;

class OrderDataLetter extends \Controller
{
    public function view()
    {
        $v = $this->v();

        $cartInstance = $this->data('cart/instance');
        $cartData = $this->data('cart/data');

        $totalCost = 0;

        if ($items = ap($cartData, 'items')) {
            foreach ($items as $key => $item) {
                $cost = $item['price'] * $item['quantity'];

                if ($product = unpack_model($item['model'] ?? null)) {
                    $totalCost += $cost;

                    $v->assign('item', [
                        'NAME'     => $item['name'],
                        'PRICE'    => number_format__($item['price']),
                        'QUANTITY' => trim_zeros($item['quantity']),
                        'COST'     => number_format__($cost)
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

        return $v;
    }
}
