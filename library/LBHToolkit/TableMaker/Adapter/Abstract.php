<?php
/**
 * Abstract.php
 * LBHToolkit_TableMaker_Adapter_Abstract
 * 
 * An abstract data adapter class for the TableMaker. Does a lot of simple things
 * for you.
 * 
 * LICENSE
 * 
 * This file is subject to the New BSD License that is bundled with this package.
 * It is available in the LICENSE file. 
 * 
 * It is also available online at http://www.littleblackhat.com/lbhtoolkit
 * 
 * @author      Kevin Hallmark <kevin.hallmark@littleblackhat.com>
 * @since       2011-09-21
 * @package     LBHToolkit
 * @subpackage  LBHToolkit_TableMaker_Adapter
 * @copyright   Little Black Hat, 2011
 * @license     http://www.littleblackhat.com/lbhtoolkit    New BSD License
 */

abstract class LBHToolkit_TableMaker_Adapter_Abstract implements Serializable, LBHToolkit_TableMaker_Adapter_Interface
{
	protected $_params = array();

	/**
	 * The defaut page size for a tablemaker result set
	 */
	const PAGE_SIZE = 20;
	const PAGE_SIZE_MAX = 100;

	/**
	 * Constructor
	 *
	 * @param string $object 
	 * @param string $params 
	 * @author Kevin Hallmark
	 */
	public function __construct($params = NULL)
	{
		if ($params !== NULL)
		{
			$this->setParams($params);
		}
		
		$this->validateParams($params);
	}
	
	
	/**
	 * Get from the params array. Used for serialization
	 *
	 * @param string $key 
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function __get($key)
	{
		if (array_key_exists($key, $this->_params))
		{
			return $this->_params[$key];
		}
		
		return NULL;
	}
	
	/**
	 * Set to params
	 *
	 * @param string $key 
	 * @param string $value 
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function __set($key, $value)
	{
		$this->_params[$key] = $value;
	}
	
	/**
	 * PHP Magic Method::__isset()
	 *
	 * @param string $key 
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function __isset($key)
	{
		return isset($this->_params[$key]);
	}
	
	
	
	
	/**
	 * Get the parameters in mass
	 *
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function getParams()
	{
		return $this->_params;
	}
	
	/**
	 * Set the parameters in mass
	 *
	 * @param string $new_params 
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function setParams($new_params)
	{
		if (!is_array($new_params))
		{
			throw new Memberfuse_Rest_Exception('Params must be an array.');
		}
		$this->_params = $new_params;
	}
	
	
	
	
	
	
	/**
	 * Lets you return custom fields to serialize
	 *
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function customSerializeFields()
	{
		return NULL;
	}
	
	/**
	 * Lets you add custom field processing on unserialize
	 *
	 * @param string $params 
	 * @return array Modified Parameters array
	 * @author Kevin Hallmark
	 */
	public function customUnserializeFields($params)
	{
		return $params;
	}
	
	/**
	 * Serialize 
	 *
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function serialize()
	{
		$params              = $this->_params;
		$params['className'] = $this->_className;
		
		// Make sure we don't cache our Request Parameters
		$request_params = array('action', 'relationship', 'page', 'count');
		foreach($request_params AS $key_value)
		{
			unset($request_params);
		}
		
		if($custom_fields = $this->customSerializeFields())
		{
			array_merge($params, $custom_fields);
		}
		
        return serialize($params);
    }

	/**
	 * Custom Unserialize
	 *
	 * @param string $data 
	 * @return void
	 * @author Kevin Hallmark
	 */
    public function unserialize($data)
	{
		$data = unserialize($data);
		$this->setClassName($data['className']);
		unset($data['className']);
		
		$data = $this->customUnserializeFields($data);
		
		$this->setParams($data);
	}
}