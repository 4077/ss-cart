<?php namespace ss\cart\button\controllers\main;

class Xhr extends \Controller
{
    public $allow = self::XHR;

    public function openCart()
    {
//        $this->c('\std\ui\layer~:open|cart', [
//            'content_call' => $this->_abs('\ss\cart\ui~:view|')
//        ]);
//
//        appc()->se('cart/empty')->rebind('\std\ui\layer~:close|cart');

        $cart = cart($this->_instance());

        $route = $cart->settings('ui/cart/route');

        app()->response->href(force_slashes($route));
    }
}
