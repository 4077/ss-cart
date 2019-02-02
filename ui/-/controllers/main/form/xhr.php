<?php namespace ss\cart\ui\controllers\main\form;

class Xhr extends \Controller
{
    public $allow = self::XHR;

    public function update()
    {
        if ($this->dataHas('field string, value')) {
            if (in($this->data['field'], $this->c('<:getFieldsAliases')) || $this->data['field'] == 'address') { // hardcode
                $clientInfo = &cart($this->_instance())->s('client_info');

                $field = $this->data['field'];
                $value = $this->data['value'];

                if ($field == 'phone') {
                    $value = \ss\support\Support::integerPhone($value, '7');
                }

                $clientInfo[$field] = $value;

                $this->widget('<:', 'removeErrorHighlighting', [
                    'field' => $this->data['field']
                ]);
            }
        }
    }

    public function deliveryToggle()
    {
        $clientInfo = &cart($this->_instance())->s('client_info');

        $delivery = &ap($clientInfo, 'delivery');

        invert($delivery);

        $this->c('<:reload|');
    }

    public function submit()
    {
        $this->c('@submit:run|', [], true);
    }
}
