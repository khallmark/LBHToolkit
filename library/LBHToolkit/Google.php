<?php
/**
 * Google.php
 * LBHToolkit_Google
 * 
 * <description>
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
 * @subpackage  LBHToolkit
 * @copyright   Little Black Hat, 2011
 * @license     http://www.littleblackhat.com/lbhtoolkit    New BSD License
 */

class LBHToolkit_Google
{
	protected $_secure = TRUE;
	
	protected $_api_version = NULL;
	protected $_service_url = NULL;
	
	protected $_output_format = 'json';
	
	protected $_allowed_parameters = array();
	
	protected $_response_class = 'LBHToolkit_Google_Response';
	
	public function addParameter($key, $value)
	{
		$allowed = $this->_allowed_parameters;
		
		if (!isset($allowed[$key]))
		{
			$message = sprintf("The field %s is not supported by the Google Geocode API v3", $key);
			throw new LBHToolkit_Google_Exception($message);
		}
		
		if ((count($allowed[$key]) > 0) && !in_array($value, $allowed[$key], TRUE))
		{
			$message = sprintf("The field %s does not allow the value %s", $key, $value);
			throw new LBHToolkit_Google_Exception($message);
		}
		
		$this->_parameters[$key] = sprintf('%s=%s', $key, urlencode($value));
	}
	
	protected function _parseParameters($parameters = NULL)
	{
		if ($parameters === NULL)
		{
			$parameters = $this->_parameters;
		}
		
		return implode('&', $parameters);
		
		$sensor = $parameters['sensor'];
		unset($parameters['sensor']);
		
		$query = $sensor;
		if (count($parameters) > 0)
		{
			$query = sprintf('%s&%s', implode('&', $parameters), $sensor);
		}
		
		return $query;
	}
	
	public function getApiVersion()
	{
		return $this->_api_version;
	}
	
	public function setApiVersion($version)
	{
		$this->_api_version = $version;
	}
	
	public function getServiceUrl()
	{
		$protocol = $this->isSecure() ? 'http://' : 'https://';
		
		return sprintf('%s%s%s', $protocol, $this->_service_url, $this->getOutputFormat());
	}
	
	public function setServiceUrl($service_url)
	{
		if (substr($service_url, -1, 1) != '/')
		{
			$service_url = $service_url . '/';
		}
		
		if (strpos($service_url, 'http') !== FALSE)
		{
			$service_url = str_replace(array('http://', 'https://'), '', $service_url);
		}
		
		$this->_service_url = $service_url;
	}
	
	public function getOutputFormat()
	{
		return $this->_output_format;
	}
	
	public function setOutputFormat($output_format)
	{
		$output_format = strtolower($output_format);
		
		if ($output_format != 'json' && $output_format != 'xml')
		{
			throw new LBHToolkit_Google_Exception("Invalid output format $output_format. Google supports json or xml");
		}
		
		$this->_output_format = $output_format;
	}
	
	public function isSecure()
	{
		return $this->_secure;
	}
	
	public function setSecure(bool $secure)
	{
		$this->_secure = $secure;
	}
	
	public function getResponseClass()
	{
		return $this->_response_class;
	}
	
	public function setResponseClass($response_class)
	{
		$this->_response_class = $response_class;
	}
	
	public function execute()
	{
		$query = $this->_parseParameters();
		
		$geocode_url = sprintf('%s?%s', $this->getServiceUrl(), $query);
		
		$ch = curl_init($geocode_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);
		
		if(!$response)
		{
		    return FALSE;
		}
		
		if ($this->getOutputFormat() == 'json')
		{
			$response = json_decode($response);
		}
		
		$response_class = $this->getResponseClass();
		
		return new $response_class($response);
	}
}
