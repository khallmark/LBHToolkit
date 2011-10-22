<?php
/**
 * Abstract.php
 * LBHToolkit_TableMaker_Abstract
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
 * @since       2011-08-24
 * @package     LBHToolkit
 * @subpackage  LBHToolkit_TableMaker
 * @copyright   Little Black Hat, 2011
 * @license     http://www.littleblackhat.com/lbhtoolkit    New BSD License
 */

abstract class LBHToolkit_TableMaker_Abstract extends LBHToolkit_Base implements LBHToolkit_TableMaker_Interface
{
	/**
	 * The defaut page size for a tablemaker result set
	 */
	const PAGE_SIZE = 20;
	const PAGE_SIZE_MAX = 100;
	
	/**
	 * Serialize 
	 *
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function toArray()
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
			$params = array_merge($params, $custom_fields);
		}
		
		return $params;
	}
}