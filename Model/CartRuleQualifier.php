<?php
namespace Veni\CartPriceRulesQualifier\Model;

use \Magento\Framework\Model\AbstractModel;

class CartRuleQualifier extends AbstractModel
{
    const CART_RULE_QUALIFIER_ID = 'entity_id'; // We define the id fieldname

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'cart_rule_qualifiers';

    /**
     * Name of the event object
     *
     * @var string
     */
    protected $_eventObject = 'veni_cart_rule_qualifier';

    /**
     * Name of object id field
     *
     * @var string
     */
    protected $_idFieldName = self::CART_RULE_QUALIFIER_ID;

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Veni\CartPriceRulesQualifier\Model\ResourceModel\CartRuleQualifier');
    }
}