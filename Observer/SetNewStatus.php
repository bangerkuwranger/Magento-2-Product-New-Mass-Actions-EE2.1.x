<?php

namespace Bangerkuwranger\Productnewmassactions\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

class SetNewStatus implements ObserverInterface {


    /**
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
	protected $_timezone;
	/**
     * @var LoggerInterface
     */
    protected $_loggerInterface;
    
    public function __construct(
		\Magento\Framework\Stdlib\DateTime\TimezoneInterface $_timezone,
		\Magento\Framework\App\RequestInterface $_request,
		LoggerInterface $_loggerInterface
	) {
    
		$this->_timezone = $_timezone;
		$this->_request = $_request;
		$this->_loggerInterface = $_loggerInterface;

    }
    
    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute( \Magento\Framework\Event\Observer $_observer ) {
    
        $request = $_observer->getEvent()->getRequest();
        $this->_loggerInterface->debug(print_r($request,true));
		$isNew = $this->_request->getPost('product[is_new]');
        $product = $_observer->getEvent()->getProduct();
        $product->setData( 'news_from_date', '' );
        $product->setData( 'news_to_date', '' );
        if( !$isNew ) {
        
            $tz = new \DateTimeZone( $this->_timezone->getConfigTimezone() );
            $now = new \DateTime( null, $tz );
            $product->setData( 'news_from_date', $now->format( "Y-m-d h:i:s" ) );
        
        }
    
    }

}
