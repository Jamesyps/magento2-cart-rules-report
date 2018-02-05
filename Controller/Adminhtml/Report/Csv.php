<?php
namespace Veni\CartPriceRulesQualifier\Controller\Adminhtml\Report;

use Magento\Framework\App\Action\Context;

class Csv extends \Magento\Framework\App\Action\Action
{

    public function __construct(
        Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory)
    {
        parent::__construct($context);
    }

    public function execute()
    {
        $heading = [
            __('Id'),
            __('SKU'),
            __('Name')
        ];
        $outputFile = "ListProducts". date('Ymd_His').".csv";
        $handle = fopen($outputFile, 'w');
        fputcsv($handle, $heading);

        //foreach ($products as $product) {
            $row = [
                '23213',
                '23123123',
                'VenZy'
            ];
            fputcsv($handle, $row);
       // }
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
}