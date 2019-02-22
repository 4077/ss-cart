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
        //        'mailer'
    ];

    /**
     * @var \ss\cart\Svc\Stage
     */
    public $stage = \ss\cart\Svc\Stage::class;
//
//    /**
//     * @var \ss\cart\Svc\Mailer
//     */
//    public $mailer = \ss\cart\Svc\Mailer::class;

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
            'products'    => [],
            'client_info' => [],
            'cache'       => [
                'total_cost' => 0
            ]
        ]);

        appc()->se('ss/cats/ui/select_warehouses_group')->rebind('\ss\cart app:recalculate|' . $this->instance);
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

    private function &sProduct(\ss\models\Product $product)
    {
        $s = &ap($this->s, 'products/' . $product->id);

        return $s;
    }

    public function getProducts()
    {
        return $this->s['products'];
    }

    public function contain(\ss\models\Product $product)
    {
        return null !== $this->sProduct($product);
    }

    public function update($path, $value)
    {
        ap($this->s, $path, $value);
    }

    public function recalculate()
    {
        $products = $this->getProducts();

        foreach ($products as $productId => $productData) {
            if ($product = \ss\models\Product::find($productId)) {
                if ($pivotPack = $productData['pivot'] ?? false) {
                    if ($pivot = unpack_model($pivotPack)) {
                        $tile = \ss\components\products\tile($product, $pivot);

                        $sProduct = &$this->sProduct($product);

                        $sProduct['name'] = $tile->name;
                        $sProduct['price'] = $tile->price;
                        $sProduct['price_without_discount'] = $tile->priceWithoutDiscount;
                        $sProduct['discount'] = $tile->discount;
                        $sProduct['units'] = $tile->units;
                    }
                }
            }
        }
    }

    public function incQuantity(\ss\models\Product $product)
    {
        $s = &$this->sProduct($product);

        $s['quantity'] += 1;

        pusher()->trigger('ss/cart/update_product', [
            'productId' => $product->id
        ]);
    }

    public function decQuantity(\ss\models\Product $product)
    {
        $s = &$this->sProduct($product);

        $s['quantity'] -= 1;

        if ($s['quantity'] < 1) {
            $s['quantity'] = 1;
        }

        pusher()->trigger('ss/cart/update_product', [
            'productId' => $product->id
        ]);
    }

    public function setQuantity(\ss\models\Product $product, $value)
    {
        $s = &$this->sProduct($product);

        $value = \ewma\Data\Formats\Numeric::parseDecimal($value);

        if ($value < 1) {
            $value = 1;
        }

        $s['quantity'] = $value;

        pusher()->trigger('ss/cart/update_product', [
            'productId' => $product->id
        ]);
    }

    public function getQuantity(\ss\models\Product $product)
    {
        return $this->s['products'][$product->id]['quantity'] ?? 0;
    }

    public function add(\ss\models\Product $product, $productData)
    {
        $sProduct = &$this->sProduct($product);

        $quantity = $this->stage->getQuantity($product);

        if (null !== $sProduct) {
            $sProduct['quantity'] += $quantity;
        } else {
            ra($productData, ['quantity' => $quantity]);

            $sProduct = $productData;
        }

        $this->stage->setQuantity($product, 1);

        pusher()->trigger('ss/cart/add_product', [
            'instance'      => $this->instance,
            'productsCount' => count($this->s['products']),
            'productId'     => $product->id
        ]);

        sstm()->events->trigger('cats/ui/addProductToCart', [
            'product_id' => $product->id,
            'cart'       => [
                'instance' => $this->instance,
                'data'     => $this->s
            ]
        ]);
    }

    public function delete(\ss\models\Product $product)
    {
        if (isset($this->s['products'][$product->id])) {
            # 1

            // todo проверить что там
            sstm()->events->trigger('cats/ui/deleteProductFromCart', [
                'product_id' => $product->id,
                'cart'       => [
                    'instance' => $this->instance,
                    'data'     => $this->s
                ]
            ]);

            # 2
            unset($this->s['products'][$product->id]);

            pusher()->trigger('ss/cart/delete_product', [
                'instance'      => $this->instance,
                'productsCount' => count($this->s['products']),
                'productId'     => $product->id,
            ]);
        }
    }

    public function reset()
    {
        $cartProductsIds = array_keys($this->s['products']);
        $stageProductsIds = array_keys(ap($this->s, 'stage'));

        merge($productsIds, $cartProductsIds);
        merge($productsIds, $stageProductsIds);

        ra($this->s, [
            'products' => [],
            'stage'    => []
        ]);

        foreach ($productsIds as $productId) {
            pusher()->trigger('ss/cart/delete_product', [
                'instance'      => $this->instance,
                'productsCount' => 0,
                'productId'     => $productId
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
