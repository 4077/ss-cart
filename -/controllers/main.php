<?php namespace ss\cart\controllers;

class Main extends \Controller
{
    public function __create()
    {
        $this->dmap('|', 'settings');
    }

    public function view()
    {
        $mode = $this->data('mode');

        if ($mode == 'button') {
            return $this->c('button~:view|');
        }

        if ($mode == 'bar') {
//            return $this->c('bar~:view|'); todo
        }
    }
}
