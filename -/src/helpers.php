<?php

function cart($instance = false, $sessionKey = false)
{
    return \ss\cart\Svc::getInstance($instance, $sessionKey);
}

/**
 * @param bool $instance
 *
 * @return mixed|\ss\cart\controllers\Main
 */
function cartc($instance = false)
{
    $args = func_get_args();

    if ($args) {
        return call_user_func_array([cart($instance)->rootController, 'c'], $args);
    } else {
        return cart($instance)->rootController;
    }
}
