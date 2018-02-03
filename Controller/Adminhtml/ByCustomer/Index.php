<?php
namespace Veni\CartPriceRulesQualifier\Controller\Adminhtml\ByCustomer;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action;

class Index extends Action
{
    const ADMIN_RESOURCE = 'Veni_CartPriceRulesQualifier::by_customer';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var CartRuleQualifier
     */
    protected $modelCartRuleQualifierFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Veni\CartPriceRulesQualifier\Model\CartRuleQualifierFactory $modelCartRuleQualifierFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->modelCartRuleQualifierFactory = $modelCartRuleQualifierFactory;
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Veni_CartPriceRulesQualifier::by_customer');
        $resultPage->addBreadcrumb(__('Jobs'), __('Jobs'));
        $resultPage->addBreadcrumb(__('Manage Jobs'), __('Manage Jobs'));
        $resultPage->getConfig()->getTitle()->prepend(__('Cart Price Rules Qualifier By Customer'));
        //$test = $this->modelCartRuleQualifierFactory->create();
        //$test->getCollection()->load();
        //echo $test->getCollection()->getSelect();
        //die;
        //echo $test->getCollection()->getSelect();
        //var_dump($test->getCollection()->getSelect());
        //die('collection');
        return $resultPage;
    }
}