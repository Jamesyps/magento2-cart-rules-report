<?php
namespace Veni\CartPriceRulesQualifier\Controller\Adminhtml\Report;

use Magento\Framework\App\Action\Context;

class Csv extends \Magento\Framework\App\Action\Action
{

    /**
     * @var \Veni\CartPriceRulesQualifier\Model\CartRuleQualifierFactory $cartRuleQualifierFactory
     */
    private $cartRuleQualifierFactory;

    public function __construct(
        Context $context,
        \Veni\CartPriceRulesQualifier\Model\CartRuleQualifierFactory $cartRuleQualifierFactory)
    {
        parent::__construct($context);
        $this->cartRuleQualifierFactory = $cartRuleQualifierFactory;
    }

    public function execute()
    {
        $heading = $this->getHeading();
        $outputFile = "PromotionsByCustomers". date('Ymd').".csv";
        $handle = fopen($outputFile, 'w');
        fputcsv($handle, $heading);

        $cartRuleQualifierModel = $this->cartRuleQualifierFactory->create();
        $cartRulesCollection = $cartRuleQualifierModel->getCollection();
        $cartRulesCollection
            ->getSelect()
            ->columns("COUNT(customer_email) AS num_of_usage")
            ->group('main_table.name');
        $cartRulesCollection->setOrder('num_of_usage');

        $collectionItems = $cartRulesCollection->getItems();
        foreach ($collectionItems as $collectionItem) {
            $row = [
                $collectionItem->getData('name'),
                $collectionItem->getData('num_of_usage'),
                '...'
            ];
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
            __('Number of usage'),
            __('....')
        ];
    }
}
