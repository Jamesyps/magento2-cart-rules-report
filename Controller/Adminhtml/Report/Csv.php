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

        $promotionsByOrder = $this->getPromotionsByOrder();
        $linkedPromotions = $this->getLinkedPromotions($promotionsByOrder);

        $cartRulesCollection
            ->getSelect()
            ->columns("COUNT(customer_email) AS num_of_usage")
            ->group('main_table.name');
        $cartRulesCollection->setOrder('num_of_usage');
        $collectionItems = $cartRulesCollection->getItems();
        foreach ($collectionItems as $collectionItem) {
            $row = [];
            $row[] = $collectionItem->getData('name');
            if(isset($linkedPromotions[$collectionItem->getData('name')])) {
                $row[] = $collectionItem->getData('num_of_usage') - $linkedPromotions[$collectionItem->getData('name')]['num'];
                $row[] = $linkedPromotions[$collectionItem->getData('name')]['num'];
                $row[] = $linkedPromotions[$collectionItem->getData('name')]['combinations'];
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

    private function getPromotionsByOrder()
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

    private function getLinkedPromotions($promotionsByOrder)
    {
        $linkedPromotions = [];
        $cartPromotions = $this->getPromotionNames();

        foreach ($cartPromotions as $cartPromotion) {
            $linkedPromotions[$cartPromotion]['num'] = 0;
            $linkedPromotions[$cartPromotion]['combinations'] = '';
            foreach ($promotionsByOrder as $orderNum => $promotions) {
                if(count($promotions) > 1 && in_array($cartPromotion,$promotions)) {
                    $linkedPromotions[$cartPromotion]['num']++;
                    $linkedPromotions[$cartPromotion]['combinations'] .= implode(',', $promotions) . '; ';
                }
            }
        }

        return $linkedPromotions;
    }

    private function getPromotionNames()
    {
        $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $select = $connection->select();
        $select->from('veni_cart_rule_qualifier', 'name')->distinct(true);

        return $connection->fetchCol($select);
    }

}
