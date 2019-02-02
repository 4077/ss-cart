<?php namespace ss\cart\sessionEvents\desc\controllers;

class Main extends \Controller
{
    public function addProductToCart()
    {
        return $this->c_('>cart:view', [
            'highlight_class' => 'added'
        ]);
    }

    public function deleteProductFromCart()
    {
        return $this->c_('>cart:view', [
            'skip_in_total_cost' => true,
            'highlight_class'    => 'deleted'
        ]);
    }

    public function cartPageOpen()
    {
        return $this->c_('>cart:view');
    }

    public function createOrder()
    {
        return $this->c_('>cart:view');
    }
}
