<?php namespace ss\cart\ui\controllers\main\table;

class Xhr extends \Controller
{
    public $allow = self::XHR;

    public function reload()
    {
        $this->c('<:reload|');
    }

    private function getCart()
    {
        if ($instance = $this->_instance()) {
            return cart($instance);
        }
    }

    public function delete()
    {
        $product = $this->unxpackModel('product');
        $cart = $this->getCart();

        if ($product && $cart) {
            $cart->delete($product);
        }
    }

    public function decQuantity()
    {
        $product = $this->unxpackModel('product');
        $cart = $this->getCart();

        if ($product && $cart) {
            $cart->decQuantity($product);
        }
    }

    public function incQuantity()
    {
        $product = $this->unxpackModel('product');
        $cart = $this->getCart();

        if ($product && $cart) {
            $cart->incQuantity($product);
        }
    }

    public function setQuantity()
    {
        $product = $this->unxpackModel('product');
        $cart = $this->getCart();

        if ($product && $cart) {
            $cart->setQuantity($product, $this->data('value'));
        }
    }
}
