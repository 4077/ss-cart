<?php namespace ss\cart\ui\controllers;

class Main extends \Controller
{
    public function view()
    {
        pusher()->subscribe();

        $v = $this->v('|');

        $cart = cart($this->_instance());

        $v->assign([
                       'HEADER' => $cart->settings('ui/cart/header'),
                       'CLASS'  => $cart->settings('ui/cart/class'),
                       'TABLE'  => $this->c('>table:view|'),
                       'FORM'   => $this->c('>form:view|')
                   ]);

        $this->css();

        sstm()->events->trigger('cats/ui/cartPageOpen', [
            'cart' => [
                'instance' => $cart->instance,
                'data'     => $cart->s
            ]
        ]);

        if ($user = $this->_user()) {
            $clientName = $user->model->login;
        } else {
            $clientName = $this->app->session->getKey();
        }

        $this->log($clientName . ' CART OPEN', 'test');

        return $v;
    }
}
