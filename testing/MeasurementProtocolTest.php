<?php

namespace Test;

class MeasurementProtocolTest extends \PHPUnit_Framework_TestCase
{
	public function testCurlExists()
	{
		$this->assertTrue(
			function_exists('curl_init'), 
			"Curl Required to use CSTruter\Analytics"
		);
	}
	
	/**
     * @depends testCurlExists
     */
	public function testSendEvent()
	{
		$analytics = new \CSTruter\Analytics\MeasurementProtocol(ANALYTICS_UA);
		$analytics->request->url = $analytics->request->url.'/debug';
		$response = $analytics->sendEvent('Debugger', 'Debug', 'Test');
	
		if ($analytics->request->exception != null) {
			throw $analytics->request->exception;
		}
	
		if ($json = json_decode($response)) {
			$result = $json->hitParsingResult[0];
			if (!$result->valid) {
				$message = '';
				foreach($result->parserMessage as $issue) {
					$message.= $issue->description.' ';
				}
				throw new \Exception($message);
			}
		}
	}
}

?>