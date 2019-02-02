<?php namespace ss\cart;

class Svc extends \ewma\Service\Service
{
    public static $instances = [];

    public $instance;

    public $sessionKey;

    /**
     * @return \ss\cart\Svc
     */
    public static function getInstance($instance, $sessionKey)
    {
        if (!isset(static::$instances[$sessionKey . ':' . $instance])) {
            $svc = new self;

            $svc->instance = $instance;

            $svc->sessionKey = $sessionKey or
            $svc->sessionKey = app()->session->getKey();

            static::$instances[$sessionKey . ':' . $instance] = $svc;
            static::$instances[$sessionKey . ':' . $instance]->__register__();
        }

        return static::$instances[$sessionKey . ':' . $instance];
    }

    protected $services = [
        'stage',
        'mailer'
    ];

    /**
     * @var \ss\cart\Svc\Stage
     */
    public $stage = \ss\cart\Svc\Stage::class;

    /**
     * @var \ss\cart\Svc\Mailer
     */
    public $mailer = \ss\cart\Svc\Mailer::class;

    //
    //
    //

    /**
     * @var \ss\cart\controllers\Main
     */
    public $rootController;

    public $s;

    protected function boot()
    {
        $this->rootController = appc('\ss\cart~|' . $this->instance);

        $this->s = &$this->rootController->s('|' . $this->instance, [
            'stage'       => [],
            'items'       => [],
            'client_info' => [],
            'cache'       => [
                'total_cost' => 0
            ]
        ]);
    }

    public function settings($path = false)
    {
        return ap($this->rootController->data, path('settings', $path));
    }

    public function &s($path = false, $AA = [])
    {
        $s = &ap($this->s, $path);

        if ($AA) {
            aa($s, $AA);
        }

        return $s;
    }

    private function &sItem($key)
    {
        $s = &ap($this->s, 'items/' . $key);

        return $s;
    }

    public function getItems()
    {
        return $this->s['items'];
    }

    public function contain($itemKey)
    {
        return null !== $this->sItem($itemKey);
    }

    public function update($path, $value)
    {
        ap($this->s, $path, $value);
    }

    public function incQuantity($itemKey)
    {
        $s = &$this->sItem($itemKey);

        $s['quantity'] += 1;

        pusher()->trigger('ss/cart/update_item', [
            'itemKey' => $itemKey
        ]);
    }

    public function decQuantity($itemKey)
    {
        $s = &$this->sItem($itemKey);

        $s['quantity'] -= 1;

        if ($s['quantity'] < 1) {
            $s['quantity'] = 1;
        }

        pusher()->trigger('ss/cart/update_item', [
            'itemKey' => $itemKey
        ]);
    }

    public function setQuantity($itemKey, $value)
    {
        $s = &$this->sItem($itemKey);

        $value = \ewma\Data\Formats\Numeric::parseDecimal($value);

        if ($value < 1) {
            $value = 1;
        }

        $s['quantity'] = $value;

        pusher()->trigger('ss/cart/update_item', [
            'itemKey' => $itemKey
        ]);
    }

    public function getQuantity($itemKey)
    {
        return $this->s['items'][$itemKey]['quantity'] ?? 0;
    }

    public function add($itemKey, $itemData)
    {
        $sItem = &$this->sItem($itemKey);

        $quantity = $this->stage->getQuantity($itemKey);

        if (null !== $sItem) {
            $sItem['quantity'] += $quantity;
        } else {
            ra($itemData, ['quantity' => $quantity]);

            $sItem = $itemData;
        }

        pusher()->trigger('ss/cart/add_item', [
            'instance'   => $this->instance,
            'itemsCount' => count($this->s['items']),
            'itemKey'    => $itemKey
        ]);

        sstm()->events->trigger('cats/ui/addProductToCart', [
            'item_key' => $itemKey,
            'cart'     => [
                'instance' => $this->instance,
                'data'     => $this->s
            ]
        ]);
    }

    public function delete($itemKey)
    {
        if (isset($this->s['items'][$itemKey])) {
            # 1
            sstm()->events->trigger('cats/ui/deleteProductFromCart', [
                'item_key' => $itemKey,
                'cart'     => [
                    'instance' => $this->instance,
                    'data'     => $this->s
                ]
            ]);

            # 2
            unset($this->s['items'][$itemKey]);

            pusher()->trigger('ss/cart/delete_item', [
                'instance'   => $this->instance,
                'itemsCount' => count($this->s['items']),
                'itemKey'    => $itemKey
            ]);
        }
    }

    public function reset()
    {
        $cartItemsKeys = array_keys($this->s['items']);
        $stageItemsKeys = array_keys(ap($this->s, 'stage'));

        merge($itemsKeys, $cartItemsKeys);
        merge($itemsKeys, $stageItemsKeys);

        ra($this->s, [
            'items' => [],
            'stage' => []
        ]);

        foreach ($itemsKeys as $itemKey) {
            pusher()->trigger('ss/cart/delete_item', [
                'instance'   => $this->instance,
                'itemsCount' => 0,
                'itemKey'    => $itemKey
            ]);
        }
    }

    /**
     * @return \ewma\Controllers\Controller
     */
    public function c()
    {
        $args = func_get_args();

        if ($args) {
            $output = call_user_func_array([$this->rootController, 'c'], $args);
        } else {
            $output = $this->rootController;
        }

        return $output;
    }
}
