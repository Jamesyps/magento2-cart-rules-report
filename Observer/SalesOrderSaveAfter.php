<?php
namespace Veni\CartRulesReport\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\SalesRule\Model\RuleFactory;
use Veni\CartRulesReport\Model\CartRulesFactory;

class SalesOrderSaveAfter implements ObserverInterface
{

    /**
     * @var CartRulesFactory
     */
    protected $cartRulesFactory;

    /**
     * @var RuleFactory
     */
    protected $ruleFactory;

    public function __construct(
        CartRulesFactory $cartRulesFactory,
        RuleFactory $ruleFactory
    ) {
        $this->ruleFactory = $ruleFactory;
        $this->cartRulesFactory = $cartRulesFactory;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        /** @var \Veni\CartRulesReport\Model\CartRules $cartRulesModel */
        $cartRulesModel = $this->cartRulesFactory->create();

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
            $cartRulesModel->setData($modelData);
            $cartRulesModel->save();
        }
    }

}