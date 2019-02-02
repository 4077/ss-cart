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

    private function &sItem($key)
    {
        $s = &ap($this->s, $key);

        if (null === $s) {
            $s['quantity'] = 1;
        }

        return $s;
    }

    public function getItems()
    {
        return $this->s['items'];
    }

    public function getQuantity($itemKey)
    {
        return $this->s[$itemKey]['quantity'] ?? 1;
    }

    public function incQuantity($itemKey)
    {
        $s = &$this->sItem($itemKey);

        $s['quantity'] += 1;

        pusher()->trigger('ss/cart/stage/update_item', [
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

        pusher()->trigger('ss/cart/stage/update_item', [
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

        pusher()->trigger('ss/cart/stage/update_item', [
            'itemKey' => $itemKey
        ]);
    }
}
