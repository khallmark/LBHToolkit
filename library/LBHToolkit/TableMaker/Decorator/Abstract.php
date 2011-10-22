<?php
/**
 * Abstract.php
 * LBHToolkit_TableMaker_Decorator_Abstract
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
 * @since       2011-10-19
 * @package     LBHToolkit
 * @subpackage  TableMaker
 * @copyright   Little Black Hat, 2011
 * @license     http://www.littleblackhat.com/lbhtoolkit    New BSD License
 */

abstract class LBHToolkit_TableMaker_Decorator_Abstract
	extends LBHToolkit_Base 
	implements LBHToolkit_TableMaker_Decorator_Interface
{
	protected function _parseParams($params, $replacements)
	{
		if (count($params))
		{
			foreach ($params AS &$param)
			{
				if ($param == '%%row%%')
				{
					$param = $replacements['row'];
				}
				else if ($param == '%%row_value%%')
				{
					$param = $replacements['row_value'];
				}
			}
		}
		
		return $params;
	}
}