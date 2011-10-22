<?php
/**
 * Response.php
 * LBHToolkit_Google_Response
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
 * @subpackage  LBHToolkit_Google
 * @copyright   Little Black Hat, 2011
 * @license     http://www.littleblackhat.com/lbhtoolkit    New BSD License
 */

abstract class LBHToolkit_Google_Response
{
	protected $_response = NULL;
	
	public function __construct($response)
	{
		$this->_response = $response;
	}

	public function getResponse()
	{
		if (!$this->_response === NULL)
		{
			return FALSE;
		}
		
		return $this->_response;
	}	
}