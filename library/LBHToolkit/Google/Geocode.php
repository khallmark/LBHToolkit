<?php
/**
 * Geocode.php
 * LBHToolkit_Google_Geocode
 * 
 * Interacts with the Google Geocode service to return the latitude and longitude
 * of an address.
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
 * @subpackage  LBHToolkit_Google
 * @copyright   Little Black Hat, 2011
 * @license     http://www.littleblackhat.com/lbhtoolkit    New BSD License
 */

class LBHToolkit_Google_Geocode extends LBHToolkit_Google
{
	protected $_service_url = 'maps.googleapis.com/maps/api/geocode/';
	
	protected $_parameters = array('sensor' => 'sensor=false');
	
	protected $_allowed_parameters = array(
		'address' => array(), 
		'latlng' => array(),
		'bounds' => array(),
		'language' => array(),
		'sensor' => array('true', 'false'), 
	);
	
	protected $_response_class = 'LBHToolkit_Google_Geocode_Response';
	
	public function addParameter($key, $value)
	{
		if ($key == 'sensor')
		{
			if ($value === 1 || $value === TRUE || $value === 'TRUE')
			{
				$value = 'true';
			}
			
			if ($value === 0 || $value === FALSE || $value === 'FALSE')
			{
				$value = 'false';
			}
		}
		
		parent::addParameter($key, $value);
	}
	
	protected function _parseParameters($parameters = NULL)
	{
		$parameters = $this->_parameters;
		
		$sensor = $parameters['sensor'];
		unset($parameters['sensor']);
		
		$query = $sensor;
		if (count($parameters) > 0)
		{
			$query = sprintf('%s&%s', parent::_parseParameters($parameters), $query);
		}
		
		return $query;
	}
	
	public static function geocodeForAddress($address, $parameters = array())
	{
		$geocoder = new LBHToolkit_Google_Geocode();
		
		$geocoder->addParameter('address', $address);
		
		foreach ($parameters AS $key => $value)
		{
			$geocoder->addParameter($key, $value);
		}
		
		$response = $geocoder->execute();
		
		if (!$response || !$response->getStatus() || !$response->count())
		{
			return NULL;
		}
		
		$result = $response->getResult(1);
		
		return $result;
	}
	
	public static function geocodeForLatLng($lat, $lng)
	{
		
	}
}