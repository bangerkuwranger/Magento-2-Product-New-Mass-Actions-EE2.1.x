<?php

namespace Bangerkuwranger\Productnewmassactions\Observer;
use Magento\Framework\Event\ObserverInterface;
use Bangerkuwranger\Productnewmassactions\Logger\Logger;

class SetNewStatus implements ObserverInterface {


    /**
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
	protected $_timezone;
	/**
     * @var Logger
     */
    protected $_logger;
    
    public function __construct(
		\Magento\Framework\Stdlib\DateTime\TimezoneInterface $_timezone,
		\Magento\Framework\App\RequestInterface $_request,
		Logger $_logger
	) {
    
		$this->_timezone = $_timezone;
		$this->_request = $_request;
		$this->_logger = $_logger;

    }
    
    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute( \Magento\Framework\Event\Observer $_observer ) {
    
//     	$request =  $_observer->getRequest();
		$postData =  $this->_request->getPostValue();
// 		$this->_logger->info( 'observer req: ' /* . print_r( $request, true )*/ );
		
        $product = $_observer->getEvent()->getProduct();
//         $this->_logger->info( 'observer req: '  . print_r( $product, true ) );
//         $newChange = $product->dataHasChangedFor( 'is_new' );
//         $this->_logger->info( 'observer req: '  . print_r( $postData, true ) );
//         return;
// 		return $this;
		$isNew = $postData['product']['is_new'];
// 		print_r( $isNew );
// 		exit();
// 		$this->_logger->info( "observer is_new changed: " . $isNew . "\n" );
		$product->setData( 'news_from_date', '' );
		$product->setData( 'news_to_date', '' );
		$product->setData( 'is_new', 0 );
		$this->_request->setPostValue( 'news_from_date', '' );
		$this->_request->setPostValue( 'news_to_date', '' );
		if( 1 === $isNew ) {
	
			$tz = new \DateTimeZone( $this->_timezone->getConfigTimezone() );
			$now = new \DateTime( null, $tz );
			$product->setData( 'news_from_date', $now->format( "Y-m-d H:i:s" ) );
			$this->_request->setPostValue( 'news_from_date', $now->format( "Y-m-d H:i:s" ) );
	
		}
// 		$product->save();
    
    }

}
