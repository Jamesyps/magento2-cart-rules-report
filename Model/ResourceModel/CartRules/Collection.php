<?php
namespace Veni\CartRulesReport\Model\ResourceModel\CartRules;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * Mapping for fields
     *
     * @var array
     */
    protected $_map = [
        'fields' => [
            'customer_email' => 'ce.email',
            'sales_order_num' => 'so.increment_id',
            'sales_order_created_at' => 'so.created_at'
        ],
    ];

    protected $_idFieldName = \Veni\CartRulesReport\Model\CartRules::CART_RULES_ID;

    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null)
    {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
    }


    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Veni\CartRulesReport\Model\CartRules',
            'Veni\CartRulesReport\Model\ResourceModel\CartRules');
    }

    public function _initSelect()
    {
        parent::_initSelect();
        $this->getSelect()->joinLeft(
            ['ce' => $this->getTable('customer_entity')],
            'ce.entity_id = main_table.customer_id',
            ['customer_email'=> 'email']
        )->joinLeft(
            ['so' => $this->getTable('sales_order')],
            'so.entity_id = main_table.order_id',
            ['sales_order_num' => 'increment_id', 'sales_order_created_at' => 'created_at', 'store_id' => 'store_id']
        );

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if (in_array($field, ['entity_id', 'name', 'description', 'customer_id', 'order_id'], true)) {
            $field = 'main_table.' . $field;
        }

        return parent::addFieldToFilter($field, $condition);
    }

}

