<?php
namespace Veni\CartPriceRulesQualifier\Model\ResourceModel;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Cart Rule Qualifier post mysql resource
 */
class CartRuleQualifier extends AbstractDb
{

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        // Table Name and Primary Key column
        $this->_init('veni_cart_rule_qualifier', 'entity_id');
    }

}