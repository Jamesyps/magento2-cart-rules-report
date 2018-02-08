<?php
namespace Veni\CartRulesReport\Model\ResourceModel;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Cart Rules post mysql resource
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