<?php
namespace Veni\CartRulesReport\Model\ResourceModel\CartRules;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Psr\Log\LoggerInterface;
use Veni\CartRulesReport\Model\CartRules;

/**
 * Class Collection
 * @package Veni\CartRulesReport\Model\ResourceModel\CartRules
 */
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

    /**
     * @var string
     */
    protected $_idFieldName = CartRules::CART_RULES_ID;

    /**
     * Collection constructor.
     * @param EntityFactoryInterface $entityFactory
     * @param LoggerInterface $logger
     * @param FetchStrategyInterface $fetchStrategy
     * @param ManagerInterface $eventManager
     * @param AdapterInterface|null $connection
     * @param AbstractDb|null $resource
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        AdapterInterface $connection = null,
        AbstractDb $resource = null)
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
     * @param array|string $field
     * @param null $condition
     * @return $this
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if (in_array($field, ['entity_id', 'name', 'description', 'customer_id', 'order_id'], true)) {
            $field = 'main_table.' . $field;
        }

        return parent::addFieldToFilter($field, $condition);
    }

}
