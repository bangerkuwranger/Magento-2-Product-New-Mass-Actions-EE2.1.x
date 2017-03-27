<?php
namespace Bangerkuwranger\Productnewmassactions\Controller\Adminhtml;
use Magento\Backend\App\Action as BackendAction;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Psr\Log\LoggerInterface;

class SetToNewAction extends \Magento\Backend\App\Action {

	/**
     * @var Filter
     */
    protected $_filter;
    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;
    /**
     * @var LoggerInterface
     */
    protected $_loggerInterface;
    /**
     * @param Context
     */
    protected $_context;
     /**
     * @param MessageManager
     */
    protected $_messageManager;
    /**
     * @var RedirectFactory
     */
    protected $_resultRedirectFactory;
     
    public function __construct(
        Filter $_filter,
        CollectionFactory $_collectionFactory,
        LoggerInterface $_loggerInterface,
        Context $_context
    ) {
    	
    	parent::__construct($this->_context);
        $this->_collectionFactory = $_collectionFactory;
        $this->_filter = $_filter;
        $this->_loggerInterface = $_loggerInterface;
        $this->_context = $_context;
        $this->_messageManager = $this->_context->getMessageManager();
        $this->_resultRedirectFactory = $this->_context->getResultRedirectFactory();
    
    }

	public function execute() {
	
		$now = date( "Y-m-d h:i:s" );
		$collection = $this->_filter->getCollection( $this->_collectionFactory->create() );
		$collection>addAttributeToSelect( 'news_from_date' )->addAttributeToSelect( 'news_to_date' );
		$products = $collection->getItems();
		if( !empty( $products ) ) {
		
			foreach( $products as $product ) {
		
				$endDate = $product->getData( 'news_to_date' );
				try {
				
					if( !empty( $endDate ) ) {
				
						$product->setData( 'news_to_date', null );
						$product->getResource()->saveAttribute( $product, 'news_to_date' );
				
					}
					$product->setData( 'news_from_date', $now );
					$product->getResource()->saveAttribute( $product, 'news_from_date' );
				
				}
				catch( \Exception $e ) {
				
					$this->_loggerInterface->critical( $e );
					$this->_messageManager->addErrorMessage( $e->getMessage() );
				
				}
		
			}
			$this->_messageManager->addSuccessMessage( __( '%1 product(s) have been set to new', count( $products ) ) );
		
		}
		else {
		
			$this->_messageManager->addErrorMessage( __( 'No Products Selected' ) );
		
		}
		$resultRedirect = $this->_resultRedirectFactory->create();
        return $resultRedirect->setPath( $this->getUrl( 'catalog/product/index' ) );
	
	}

}
