<?php
namespace Veni\CartRulesReport\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action;
use Veni\CartRulesReport\Model\CartRulesFactory;

/**
 * Class Index
 * @package Veni\CartRulesReport\Controller\Adminhtml\Index
 */
class Index extends Action
{

    const ADMIN_RESOURCE = 'Veni_CartRulesReport::cart_rules_report';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var CartRulesFactory
     */
    protected $cartRulesFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        CartRulesFactory $cartRulesFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->cartRulesFactory = $cartRulesFactory;
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
        $resultPage->setActiveMenu('Veni_CartRulesReport::index');
        $resultPage->addBreadcrumb(__('Cart Rules'), __('Cart Rules'));
        $resultPage->addBreadcrumb(__('By Rule name'), __('By Rule name'));
        $resultPage->getConfig()->getTitle()->prepend(__('Cart Rules Report'));

        return $resultPage;
    }

}
