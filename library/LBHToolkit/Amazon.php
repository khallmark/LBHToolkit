<?php
/**
 * Amazon.php
 * LBHToolkit_Amazon
 * 
 * Base service class for the LBHToolkit amazon system
 * 
 * LICENSE
 * 
 * This file is subject to the New BSD License that is bundled with this package.
 * It is available in the LICENSE file. 
 * 
 * It is also available online at http://www.littleblackhat.com/lbhtoolkit
 * 
 * @author      Kevin Hallmark <khallmark@littleblackhat.com>
 * @since       2011-08-24
 * @package     LBHToolkit
 * @subpackage  Amazon
 * @copyright   Little Black Hat, 2011
 * @license     http://www.littleblackhat.com/lbhtoolkit    New BSD License
 */

class LBHToolkit_Amazon
{
	protected static $_amazon = NULL;
	
	/**
	 * Returns the Zend_Service_Amazon
	 *
	 * @return void
	 * @author Kevin Hallmark
	 */
	public static function getService()
	{
		if (LBHToolkit_Amazon::$_amazon == NULL)
		{
			LBHToolkit_Amazon::$_amazon = new LBHToolkit_Service_Amazon('', '', '');
		}
		
		return LBHToolkit_Amazon::$_amazon;
	}
}