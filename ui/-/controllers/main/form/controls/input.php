<?php namespace ss\cart\ui\controllers\main\form\controls;

class Input extends \Controller
{
    public function view()
    {
        $this->instance_(k(8));

        $v = $this->v('|');

        $v->assign([
                       'CLASS' => 'control ' . ($this->data('class') ? $this->data['class'] : 'input_control'),
                       'NAME'  => $this->data('field'),
                       'VALUE' => $this->data('value'),
                   ]);

        if ($mask = $this->data('mask')) {
            $this->c('\plugins\maskedinput~:bind', [
                'selector'    => $this->_selector('|') . ' input',
                'mask'        => $mask,
                'placeholder' => $this->data('placeholder')
            ]);
        }

        $this->widget(':|', [
            'field'        => $this->data('field'),
            'formSelector' => $this->_selector('<<:'),
            'formNodeNs'   => $this->_nodeNs('<<'),
            'formNodeId'   => $this->_nodeId('<<'),
            'updatePath'   => $this->data['update_path'],
            'updateData'   => $this->data['update_data']
        ]);

        return $v;
    }
}
