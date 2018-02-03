<?php

namespace Veni\CartPriceRulesQualifier\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class SalesOrderSaveAfter implements ObserverInterface
{

    /**
     * @var \Veni\CartPriceRulesQualifier\Model\CartRuleQualifierFactory
     */
    private $cartRuleQualifierFactory;
    /**
     * @var \Magento\SalesRule\Model\RuleFactory
     */
    private $ruleFactory;

    public function __construct(\Veni\CartPriceRulesQualifier\Model\CartRuleQualifierFactory $cartRuleQualifierFactory,
                                \Magento\SalesRule\Model\RuleFactory $ruleFactory)
    {

        $this->cartRuleQualifierFactory = $cartRuleQualifierFactory;
        $this->ruleFactory = $ruleFactory;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        /** @var \Veni\CartPriceRulesQualifier\Model\CartRuleQualifier $cartRuleQualifierModel */
        $cartRuleQualifierModel = $this->cartRuleQualifierFactory->create();

        $modelData = [
            'customer_id' => $order->getCustomerId(),
            'order_id' => $order->getId()
        ];
        $ruleIds = explode(',', $order->getAppliedRuleIds());
        foreach ($ruleIds as $ruleId) {
            /** @var \Magento\SalesRule\Model\Rule $rule */
            $rule = $this->ruleFactory->create();
            $rule->load($ruleId);
            $modelData['name'] = $rule->getName();
            $cartRuleQualifierModel->setData($modelData);
            $cartRuleQualifierModel->save();
        }
    }

}