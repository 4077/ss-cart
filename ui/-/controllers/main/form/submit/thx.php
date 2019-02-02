<?php namespace ss\cart\ui\controllers\main\form\submit;

class Thx extends \Controller
{
    public function view()
    {
        $v = $this->v();

        $v->assign('TEXT', cart($this->_instance())->settings('ui/cart/thx'));

        $this->css();

        return $v;
    }
}
