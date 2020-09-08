<?php

class VS7_SalesRuleUsesPerGuest_Model_Observer
{
    public function addField($observer)
    {
        $form = $observer->getForm();
        if (empty($form)) return;
        $fieldset = $form->getElement('base_fieldset');
        if (empty($fieldset)) return;
        $model = Mage::registry('current_promo_quote_rule');
        if (empty($model)) return;
        $fieldset->addField('vs7_uses_per_guest', 'text', array(
            'name' => 'vs7_uses_per_guest',
            'label' => Mage::helper('salesrule')->__('Uses per Guest'),
            'note' => Mage::helper('salesrule')->__('Usage limit enforced for guest customers only'),
        ));
        $form->addValues(array('vs7_uses_per_guest' => $model->getData('vs7_uses_per_guest')));
    }
}