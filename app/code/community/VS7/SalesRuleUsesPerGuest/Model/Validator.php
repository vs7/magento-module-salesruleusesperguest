<?php

class VS7_SalesRuleUsesPerGuest_Model_Validator extends Mage_SalesRule_Model_Validator
{
    private $_usedPerGuest = array();

    protected function _canProcessRule($rule, $address)
    {
        $result = parent::_canProcessRule($rule, $address);
        if (empty($result)) return $result;
        if(Mage::app()->getStore()->isAdmin()) return $result;
        if(Mage::getDesign()->getArea() == 'adminhtml') return $result;

        $loggedIn = Mage::getSingleton('customer/session')->isLoggedIn();
        $quote = Mage::getSingleton('checkout/session')->getQuote();
        $customerEmail = $quote->getCustomerEmail();
        $usesPerGuest = $rule->getData('vs7_uses_per_guest');
        $customerGroup = $quote->getCustomerGroupId();

        if (!$loggedIn && !empty($customerEmail) && $usesPerGuest > 0 && $customerGroup == 0) {
            if (isset($this->_usedPerGuest[$rule->getId()])) {
                if ($this->_usedPerGuest[$rule->getId()] >= $usesPerGuest) {
                    return false;
                } else {
                    return $result;
                }
            }
            $orders = Mage::getModel('sales/order')->getCollection();
            $orders->getSelect()->where('FIND_IN_SET (' . $rule->getId() . ', applied_rule_ids) AND customer_email = "' . $customerEmail . '" AND customer_id IS NULL');
            $this->_usedPerGuest[$rule->getId()] = $orders->getSize();
            if ($this->_usedPerGuest[$rule->getId()] >= $usesPerGuest) {
                return false;
            }
        }

        return $result;
    }
}