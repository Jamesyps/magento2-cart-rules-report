<?php namespace Veni\CartRulesReport\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        /**
         * Create table 'veni_cart_rules'
         */

        $tableName = $installer->getTable('veni_cart_rules');
        $tableComment = 'Veni cart rules';
        $columns = array(
            'entity_id' => array(
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => array('identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true),
                'comment' => 'Entity Id',
            ),
            'name' => array(
                'type' => Table::TYPE_TEXT,
                'size' => 255,
                'options' => array('nullable' => false, 'default' => ''),
                'comment' => 'Cart rule name',
            ),
            'description' => array(
                'type' => Table::TYPE_TEXT,
                'size' => 2048,
                'options' => array('nullable' => false, 'default' => ''),
                'comment' => 'Cart rule description',
            ),
            'customer_id' => array(
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => array('nullable' => true, 'default' => null),
                'comment' => 'Customer Id',
            ),
            'order_id' => array(
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'options' => array('unsigned' => true, 'nullable' => false),
                'comment' => 'Order id',
            ),
        );

        $foreignKeys = array(
            'order_id' => array(
                'ref_table' => 'sales_order',
                'ref_column' => 'entity_id',
                'on_delete' => Table::ACTION_CASCADE,
            ),
        );

        /**
         *  We can use the parameters above to create our table
         */

        // Table creation
        $table = $installer->getConnection()->newTable($tableName);

        // Columns creation
        foreach($columns AS $name => $values){
            $table->addColumn(
                $name,
                $values['type'],
                $values['size'],
                $values['options'],
                $values['comment']
            );
        }


        // Foreign keys creation
        foreach($foreignKeys AS $column => $foreignKey){
            $table->addForeignKey(
                $installer->getFkName($tableName, $column, $foreignKey['ref_table'], $foreignKey['ref_column']),
                $column,
                $foreignKey['ref_table'],
                $foreignKey['ref_column'],
                $foreignKey['on_delete']
            );
        }

        // Table comment
        $table->setComment($tableComment);

        // Execute SQL to create the table
        $installer->getConnection()->createTable($table);

        // End Setup
        $installer->endSetup();
    }

}
