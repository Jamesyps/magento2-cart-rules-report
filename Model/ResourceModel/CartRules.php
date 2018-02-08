<?php
namespace Veni\CartRulesReport\Model\ResourceModel;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class CartRules post mysql resource
 * @package Veni\CartRulesReport\Model\ResourceModel
 */
class CartRules extends AbstractDb
{

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        // Table Name and Primary Key column
        $this->_init('veni_cart_rules', 'entity_id');
    }

}
