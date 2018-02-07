<?php
namespace Veni\CartPriceRulesQualifier\Controller\Adminhtml\Report;

use Magento\Framework\App\Action\Context;

class Csv extends \Magento\Framework\App\Action\Action
{

    const OUTPUT_FILE_NAME = 'PromotionsByCustomers';

    /**
     * @var \Veni\CartPriceRulesQualifier\Model\CartRuleQualifierFactory $cartRuleQualifierFactory
     */
    protected $cartRuleQualifierFactory;
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resource;

    public function __construct(
        Context $context,
        \Veni\CartPriceRulesQualifier\Model\CartRuleQualifierFactory $cartRuleQualifierFactory,
        \Magento\Framework\App\ResourceConnection $resource)
    {
        parent::__construct($context);
        $this->cartRuleQualifierFactory = $cartRuleQualifierFactory;
        $this->resource = $resource;
    }

    public function execute()
    {
        $heading = $this->getHeading();
        $outputFile = self::OUTPUT_FILE_NAME . date('Ymd').".csv";
        $handle = fopen($outputFile, 'w');
        fputcsv($handle, $heading);

        $cartRuleQualifierModel = $this->cartRuleQualifierFactory->create();
        $cartRulesCollection = $cartRuleQualifierModel->getCollection();

        $promotionsByOrder = $this->getLinkedPromotions();
        $combinationsByPromotion = $this->getCombinationsByPromotion($promotionsByOrder);

        $cartRulesCollection
            ->getSelect()
            ->columns("COUNT(customer_email) AS num_of_usage")
            ->group('main_table.name');
        $cartRulesCollection->setOrder('num_of_usage');
        $collectionItems = $cartRulesCollection->getItems();
        foreach ($collectionItems as $collectionItem) {
            $row = [];
            $row[] = $collectionItem->getData('name');
            if(isset($combinationsByPromotion[$collectionItem->getData('name')])) {
                $row[] = $collectionItem->getData('num_of_usage') - $combinationsByPromotion[$collectionItem->getData('name')]['num'];
                $row[] = $combinationsByPromotion[$collectionItem->getData('name')]['num'];
                $row[] = $combinationsByPromotion[$collectionItem->getData('name')]['combinations'];
            }
            $row[] = $collectionItem->getData('num_of_usage');
            fputcsv($handle, $row);
        }

        $this->downloadCsv($outputFile);
    }

    public function downloadCsv($file)
    {
        if (file_exists($file)) {
            //set appropriate headers
            header('Content-Description: File Transfer');
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename='.basename($file));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();flush();
            readfile($file);
        }
    }

    private function getHeading()
    {
        return [
            __('Promotion'),
            __('Num of standalone promotions usage'),
            __('Num of linked promotions usage'),
            __('Linked promotions'),
            __('Sum of usage')
        ];
    }

    private function getLinkedPromotions()
    {
        $cartRuleQualifierModel = $this->cartRuleQualifierFactory->create();
        $cartRulesCollection = $cartRuleQualifierModel->getCollection();
        $collectionItems = $cartRulesCollection->getItems();

        $promotionsByOrder = [];
        foreach ($collectionItems as $collectionItem) {
            $promotionsByOrder[$collectionItem->getData('sales_order_num')][] = $collectionItem->getData('name');
        }

        return $promotionsByOrder;
    }

    private function getCombinationsByPromotion($promotionsByOrder)
    {
        $combinations = [];
        $cartPromotions = $this->getCartPromotions();

        foreach ($cartPromotions as $cartPromotion) {
            $combinations[$cartPromotion]['num'] = 0;
            $combinations[$cartPromotion]['combinations'] = '';
            foreach ($promotionsByOrder as $orderNum => $promotions) {
                if(count($promotions) > 1 && in_array($cartPromotion,$promotions)) {
                    $combinations[$cartPromotion]['num']++;
                    $combinations[$cartPromotion]['combinations'] .= implode(',', $promotions) . '; ';
                }
            }
        }

        return $combinations;
    }

    private function getCartPromotions()
    {
        $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $select = $connection->select();
        $select->from('veni_cart_rule_qualifier', 'name')->distinct(true);

        return $connection->fetchCol($select);
    }

}
