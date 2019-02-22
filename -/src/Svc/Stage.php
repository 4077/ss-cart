<?php namespace ss\cart\Svc;

class Stage extends \ewma\Service\Service
{
    protected $services = ['svc'];

    /**
     * @var \ss\cart\Svc
     */
    public $svc = \ss\cart\Svc::class;

    //
    //
    //

    private $instance;

    private $s;

    public function boot()
    {
        $this->instance = $this->svc->instance;

        $this->s = &cart($this->instance)->s('stage');
    }

    private function &sProduct(\ss\models\Product $product)
    {
        $s = &ap($this->s, $product->id);

        if (null === $s) {
            $s['quantity'] = 1;
        }

        return $s;
    }

    public function getProducts()
    {
        return $this->s['products'];
    }

    public function getQuantity(\ss\models\Product $product)
    {
        return $this->s[$product->id]['quantity'] ?? 1;
    }

//    public function incQuantity(\ss\models\Product $product)
//    {
//        $s = &$this->sProduct($product);
//
//        $s['quantity'] += 1;
//
//        pusher()->trigger('ss/cart/stage/update_product', [
//            'productId' => $product->id
//        ]);
//    }
//
//    public function decQuantity(\ss\models\Product $product)
//    {
//        $s = &$this->sProduct($product);
//
//        $s['quantity'] -= 1;
//
//        if ($s['quantity'] < 1) {
//            $s['quantity'] = 1;
//        }
//
//        pusher()->trigger('ss/cart/stage/update_product', [
//            'productId' => $product->id
//        ]);
//    }

    public function setQuantity(\ss\models\Product $product, $value)
    {
        $s = &$this->sProduct($product);

        $value = \ewma\Data\Formats\Numeric::parseDecimal($value);

        if ($value < 1) {
            $value = 1;
        }

        $s['quantity'] = $value;

        pusher()->triggerOthers('ss/cart/stage/update_product', [
            'productId' => $product->id
        ]);
    }
}
