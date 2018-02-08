<?php
namespace Veni\CartRulesReport\Model;

use \Magento\Framework\Model\AbstractModel;

class CartRules extends AbstractModel
{
    const CART_RULES_ID = 'entity_id'; // We define the id fieldname

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'cart_rules_report';

    /**
     * Name of the event object
     *
     * @var string
     */
    protected $_eventObject = 'veni_cart_rules';

    /**
     * Name of object id field
     *
     * @var string
     */
    protected $_idFieldName = self::CART_RULES_ID;

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Veni\CartRulesReport\Model\ResourceModel\CartRules');
    }
}