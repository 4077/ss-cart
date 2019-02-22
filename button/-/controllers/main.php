<?php namespace ss\cart\button\controllers;

class Main extends \Controller
{
    public function reload()
    {
        $this->jquery('|')->replace($this->view());
    }

    public function hide()
    {
        $this->widget(':|', 'hide');
    }

    public function view()
    {
        $v = $this->v('|');

        $cart = cart($this->_instance());

        $products = $cart->getProducts();

        $productsCount = count($products);

        if ($productsCount) {
            $v->assign('PRODUCTS_COUNT', $productsCount);
        } else {
            $v->assign('HIDDEN_CLASS', 'hidden');
        }

        $v->assign('CLASS', $cart->settings('ui/button/class'));

        $this->css();

        $this->widget(':|', [
            '.r'           => [
                'openCart' => $this->_p('>xhr:openCart|')
            ],
            'instance'     => $this->_instance(),
            'hideOnScroll' => $cart->settings('ui/button/hide_on_scroll'),
            'hideMaxWw'    => $cart->settings('ui/button/hide_max_ww')
        ]);

        return $v;
    }
}
