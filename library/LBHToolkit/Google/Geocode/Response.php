<?php
/**
 * Response.php
 * LBHToolkit_Google_Geocode_Response
 * 
 * A simple wrapper around Geocode Responses. Allows a programatic interface for
 * accessing these results.
 * 
 * LICENSE
 * 
 * This file is subject to the New BSD License that is bundled with this package.
 * It is available in the LICENSE file. 
 * 
 * It is also available online at http://www.littleblackhat.com/lbhtoolkit
 * 
 * @author      Kevin Hallmark <kevin.hallmark@littleblackhat.com>
 * @since       2011-10-07
 * @package     LBHToolkit
 * @subpackage  LBHToolkit_Google_Geocode
 * @copyright   Little Black Hat, 2011
 * @license     http://www.littleblackhat.com/lbhtoolkit    New BSD License
 */

class LBHToolkit_Google_Geocode_Response extends LBHToolkit_Google_Response
{
	const STATUS_OK = 'OK';
	const STATUS_ZERO_RESULTS = 'ZERO_RESULTS';
	const STATUS_OVER_QUERY_LIMIT = 'OVER_QUERY_LIMIT';
	const STATUS_REQUEST_DENIED = 'REQUEST_DENIED';
	const STATUS_INVALID_REQUEST = 'INVALID_REQUEST';
	
	public function getStatus()
	{
		if (!$this->getResponse())
		{
			return FALSE;
		}
		
		$status = $this->getResponse()->status;
		
		$result = false;
		switch ($status)
		{
			case self::STATUS_OK:
				$result = true;
				break;
			case self::STATUS_ZERO_RESULTS:
			case self::STATUS_OVER_QUERY_LIMIT:
			case self::STATUS_REQUEST_DENIED:
			case self::STATUS_INVALID_REQUEST:
			default:
				$result = false;
				break;
		}
		
		return $result;
	}
	
	public function getResults()
	{
		$response = $this->getResponse();
		
		if (!$response)
		{
			return NULL;
		}
		
		return $response->results;
	}
	
	public function count()
	{
		return count($this->getResults());
	}
	
	public function getResult($result_number)
	{
		$result_number = $result_number - 1;
		if ($result_number < 0)
		{
			$result_number = 0;
		}
		
		$results = $this->getResults();
		
		if (!$results)
		{
			return NULL;
		}
		
		if (!is_numeric($result_number))
		{
			throw new LBHToolkit_Google_Geocode_Exception('Must request a numeric result');
		}
		
		if (!isset($results[$result_number]))
		{
			return NULL;
		}
		
		return new LBHToolkit_Google_Geocode_Result($results[$result_number]);
	}
}