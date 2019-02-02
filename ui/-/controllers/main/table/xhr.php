<?php namespace ss\cart\ui\controllers\main\table;

class Xhr extends \Controller
{
    public $allow = self::XHR;

    public function reload()
    {
        $this->c('<:reload|');
    }

    private function getCartInstance()
    {
        return $this->_instance();
    }

    public function delete()
    {
        $itemKey = $this->data('item_key');

        cart($this->getCartInstance())->delete($itemKey);
    }

    public function decQuantity()
    {
        $itemKey = $this->data('item_key');
        $cartInstance = $this->getCartInstance();

        if ($itemKey && null !== $cartInstance) {
            cart($cartInstance)->decQuantity($itemKey);
        }
    }

    public function incQuantity()
    {
        $itemKey = $this->data('item_key');
        $cartInstance = $this->getCartInstance();

        if ($itemKey && null !== $cartInstance) {
            cart($cartInstance)->incQuantity($itemKey);
        }
    }

    public function setQuantity()
    {
        $itemKey = $this->data('item_key');
        $cartInstance = $this->getCartInstance();

        if ($itemKey && null !== $cartInstance) {
            cart($cartInstance)->setQuantity($itemKey, $this->data('value'));
        }
    }
}
