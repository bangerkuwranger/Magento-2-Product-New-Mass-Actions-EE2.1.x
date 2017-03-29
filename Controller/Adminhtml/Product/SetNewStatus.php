<?php
namespace Bangerkuwranger\Productnewmassactions\Controller\Adminhtml\Product;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Controller\Adminhtml\Product\Builder;
use Psr\Log\LoggerInterface;

class SetNewStatus extends \Magento\Catalog\Controller\Adminhtml\Product {

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
    /**
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
	protected $_timezone;
	/**
	* @param \Magento\Framework\Stdlib\DateTime\DateTime
	*/
	protected $_datetime;

     
    public function __construct(
        Context $_context,
        Builder $_productBuilder,
        Filter $_filter,
        CollectionFactory $_collectionFactory,
        LoggerInterface $_loggerInterface,
		\Magento\Framework\Stdlib\DateTime\TimezoneInterface $_timezone
    ) {
    	
        $this->_collectionFactory = $_collectionFactory;
        $this->_filter = $_filter;
        $this->_loggerInterface = $_loggerInterface;
        $this->_context = $_context;
        $this->_messageManager = $this->_context->getMessageManager();
        $this->_resultRedirectFactory = $this->_context->getResultRedirectFactory();
        $this->_timezone = $_timezone;
        parent::__construct( $this->_context, $_productBuilder );
    
    }
    
    public function execute() {
	
		$new = 'Not New';
		$newStatus = (int) $this->getRequest()->getParam('newstatus');
		if( $newStatus ) {
		
			$new = 'New';
		
		}
		$storeId = (int) $this->getRequest()->getParam('store', 0);
		$tz = new \DateTimeZone( $this->_timezone->getConfigTimezone() );
        $now = new \DateTime( null, $tz );
		$collection = $this->_filter->getCollection( $this->_collectionFactory->create() );
		$collection->addAttributeToSelect( 'news_from_date' )->addAttributeToSelect( 'news_to_date' );
		$productIds = $collection->getAllIds();
		if( 0 !== count( $productIds ) ) {
		
			try {
			
				if( $newStatus ) {
					
					$this->_objectManager->get( 'Magento\Catalog\Model\Product\Action' )->updateAttributes( $productIds, ['news_from_date' => $now->format( "Y-m-d H:i:s" )], $storeId )->updateAttributes( $productIds, ['news_to_date' => ''], $storeId );
				
				}
				else {
				
					$this->_objectManager->get( 'Magento\Catalog\Model\Product\Action' )->updateAttributes( $productIds, ['news_from_date' => ''], $storeId )->updateAttributes( $productIds, ['news_to_date' => ''], $storeId );
				
				}
				$this->_messageManager->addSuccessMessage( __( '%1 product(s) have been set to ' . $new . '.', count( $productIds ) ) );
			
			} catch( \Magento\Framework\Exception\LocalizedException $e ) {
			
				$this->_loggerInterface->critical( $e );
				$this->_messageManager->addError( $e->getMessage() );
			
			}
			catch (\Exception $e) {
			
				$this->_loggerInterface->critical( $e );
				$this->_getSession()->addException( $e, __('Something went wrong while updating the product(s) New status.' ));
			
			}
		
		}
		else {
		
			$this->_messageManager->addErrorMessage( __( 'No Products Selected' ) );
		
		}
		$resultRedirect = $this->_resultRedirectFactory->create();
        return $resultRedirect->setPath( $this->getUrl( 'catalog/product/index' ) );
	
	}


}
