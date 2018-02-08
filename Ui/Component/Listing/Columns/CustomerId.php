<?php
namespace Veni\CartRulesReport\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

/**
 * Class CustomerId
 * @package Veni\CartRulesReport\Ui\Component\Listing\Columns
 */
class CustomerId extends \Magento\Ui\Component\Listing\Columns\Column
{

    /**
     * Column name
     */
    const NAME = 'customer_id';

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ){
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {

        $dataSource = parent::prepareDataSource($dataSource);

        if (empty($dataSource['data']['items'])) {
            return $dataSource;
        }

        $fieldName = $this->getData('name');

        foreach ($dataSource['data']['items'] as &$item) {
            if (empty($item[static::NAME])) {
                $item[$fieldName] = __('Not registered');
            }
        }

        return $dataSource;
    }

}
