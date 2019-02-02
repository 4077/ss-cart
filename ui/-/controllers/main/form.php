<?php namespace ss\cart\ui\controllers\main;

class Form extends \Controller
{
    public function reload()
    {
        $this->jquery()->replace($this->view());
    }

    public function view()
    {
        $v = $this->v();

        // 1
        $this->widget(':', [
            'paths' => [
                'submit' => $this->_p('>xhr:submit|')
            ]
        ]);

        // 2
        $v = $this->assignFields($v);

        $v->assign([
                       'SUBMIT_BUTTON_LABEL' => cart($this->_instance())->settings('ui/cart/submit_button_label')
                   ]);

        $this->css();

        return $v;
    }

    private function assignFields(\ewma\Views\View $v)
    {
        $clientInfo = cart($this->_instance())->s('client_info', $this->getDefaultClientInfo());

        foreach ($this->getFields() as $field) {
            if ($field['alias'] == 'address') {
                continue;
            }

            $controlCall = $this->_call($field['control']);

            $controlCall->aa([
                                 'field'       => $field['alias'],
                                 'value'       => $clientInfo[$field['alias']] ?? '',
                                 'update_path' => $this->_p('>xhr:update|'),
                                 'update_data' => [
                                     'field' => $field['alias']
                                 ]
                             ]);

            $v->assign('field', [
                'ALIAS'   => $field['alias'],
                'LABEL'   => $field['label'],
                'CONTROL' => $controlCall->perform()
            ]);

            if ($field['necessary']) {
                $v->assign('field/necessary');
            }
        }

        $delivery = ap($clientInfo, 'delivery');

        $v->assign([
                       'DELIVERY_TOGGLE_BUTTON' => $this->c('\std\ui button:view', [
                           'path'  => '>xhr:deliveryToggle|',
                           'data'  => [

                           ],
                           'class' => 'delivery_toggle_button ' . ($delivery ? 'checked' : ''),
                           'icon'  => 'fa fa-check',
                           'label' => 'Нужна доставка'
                       ])
                   ]);

        if ($delivery) {
            $v->assign('delivery', [
                'CONTROL' => $this->_call('>controls/textarea:view')
                    ->ra([
                             'field'       => 'address',
                             'value'       => ap($clientInfo, 'address'),
                             'update_path' => $this->_p('>xhr:update|'),
                             'update_data' => [
                                 'field' => 'address'
                             ]
                         ])->perform()
            ]);
        }

        return $v;
    }

    public function getFields()
    {
        return [
            [
                'alias'     => 'fio',
                'label'     => 'Имя',
                'control'   => '>controls/input:view',
                'necessary' => false
            ],
            [
                'alias'     => 'email',
                'label'     => 'e-mail',
                'control'   => '>controls/input:view',
                'necessary' => false
            ],
            [
                'alias'     => 'phone',
                'label'     => 'Телефон',
                'control'   => [
                    '>controls/input:view',
                    [
                        'mask'        => '+7? (999) 999-99-99',
                        'placeholder' => '+7 (___) ___-__-__'
                    ]
                ],
                'necessary' => true
            ],
            [
                'alias'     => 'address',
                'label'     => 'Адрес доставки',
                'control'   => '>controls/textarea:view',
                'necessary' => false
            ],
            [
                'alias'     => 'comment',
                'label'     => 'Примечание',
                'control'   => '>controls/textarea:view',
                'necessary' => false
            ]
        ];
    }

    public function getFieldsAliases()
    {
        return \ewma\Data\Table\Transformer::getColumn($this->getFields(), 'alias');
    }

    public function getNecessaryFieldsAliases()
    {
        $aliases = [];

        foreach ($this->getFields() as $field) {
            if ($field['necessary']) {
                $aliases[] = $field['alias'];
            }
        }

        return $aliases;
    }

    private function getDefaultClientInfo()
    {
        $clientInfo = [];
        foreach ($this->getFields() as $field) {
            $clientInfo[$field['alias']] = '';
        }

        aa($clientInfo, [
            'delivery' => false,
            'address'  => ''
        ]);

        return $clientInfo;
    }
}
