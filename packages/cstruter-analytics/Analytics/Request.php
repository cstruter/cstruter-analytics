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
 * Analytics Request Class
 *
 * Web Request class used for the Measurement Protocol 
 */
class Request
{
	/** @var string $url Base URL used by the Measurement Protocol */
	public $url = 'https://www.google-analytics.com';
	
	/** @var \Exception $exception	Possible exception thrown during operation */
	public $exception;
	
	/**
	 * Get a response for the current request
	 *
	 * @param array $args query string arguments sent to the server 
	 * @return string returned from this request
	 */
	public function getResponse(array $args)
	{
		$request = curl_init();
		
		curl_setopt_array($request, [
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_URL => $this->url.'/collect?'.http_build_query($args)
		]);

		$this->exception = null;
		if (($response = curl_exec($request))=== false) {
			$error = curl_error($request);
			$this->exception = new \Exception($error);
			error_log($error);
		}
		curl_close($request);
		
		return $response;
	}	
}

?>