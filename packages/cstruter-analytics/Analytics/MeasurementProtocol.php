<?php

/**
 * Measurement Protocol Implementation
 *
 * @package CSTruter\Analytics
 * @author Christoff Trüter
 * @version 0.1.0
 */

namespace CSTruter\Analytics;

/**
 * Measurement Protocol Class
 *
 * Class for sending data for analysis to the Google Analytics server
 * @link https://developers.google.com/analytics/devguides/collection/protocol/v1 Measurement Protocol
 */
class MeasurementProtocol
{
	/** @var string $clientId An ID unique to a particular user */
	private $clientId;
	
	/** @var int $version The protocol version */
	private $version = 1;
	
	/** @var string $trackingId The ID that distinguishes to which Google Analytics property to send data */
	private $trackingId;
	
	/** @var Request $request HTTP Request made to the Google Analytics server */
	public $request;
	
	/**
	 * Instantiate a new Measurement Protocol Class
	 * 
	 * If the $clientId is left blank, the class will attempt to extract it from 
	 * the google analytics cookie set via the analytics JavaScript, else use the 
	 * user's session if that fails.
	 *
	 * @param string $trackingId	Format UA-XXXX-Y
	 * @param string $clientId
	 */
	public function __construct($trackingId, $clientId = NULL) {
		$this->trackingId = $trackingId;
		$this->request = new Request();
		if ($clientId == NULL) {
			if (isset($_COOKIE["_ga"])) {
				list($version, $domainDepth, $cid1, $cid2) = explode('.', $_COOKIE["_ga"], 4);
				$clientId = $cid1.'.'.$cid2;
			} else {
				$clientId = session_id();
			}
		}
		$this->clientId = $clientId;
	}
	
	/**
	 * Event Tracking
	 *
	 * @param string $category		Specifies the event category. Must not be empty.
	 * @param string $action		Specifies the event action. Must not be empty.
	 * @param string $label			Specifies the event label (Optional).
	 * @return string 				Transparent 1x1 pixel gif aka tracking pixel
	 */
	public function sendEvent($category, $action, $label = NULL) {
		$args = [
			'v' => $this->version,	// Protocol Version
			'tid' => $this->trackingId, // Tracking Id
			'cid' => $this->clientId, // Client Id
			't' => 'event',	// Hit Type
			'ec' => $category, // Event Category
			'ea' => $action, // Event Action
			'el' => $label, // Event Label
			'dr' => filter_input(INPUT_SERVER, 'HTTP_REFERER'), // Document Referrer (Optional)
			'uip' => filter_input(INPUT_SERVER, 'REMOTE_ADDR') // IP Override (Optional)
		];
		return $this->request->getResponse($args);
	}
}

?>