<?php namespace ss\cart\controllers;

class App extends \Controller
{
    public function recalculate()
    {
        cart($this->_instance())->recalculate();
    }
}
