<?php namespace ss\cart\ui\controllers\main\form\controls;

class Textarea extends \Controller
{
    public function view()
    {
        $this->instance_(k(8));

        $v = $this->v('|');

        $v->assign([
                       'CLASS' => 'control ' . ($this->data('class') ? $this->data['class'] : 'textarea_control'),
                       'NAME'  => $this->data('field'),
                       'VALUE' => $this->data('value'),
                   ]);

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
