<?php
/**
 * ViewHelper.php
 * LBHToolkit_TableMaker_Decorator_ViewHelper
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

class LBHToolkit_TableMaker_Decorator_ViewHelper extends LBHToolkit_TableMaker_Decorator_Abstract
{
	
	public function validateParams($params)
	{
		if (!$this->name)
		{
			throw new LBHToolkit_TableMaker_Exception('No decorator name provided.');
		}
		
		if (!$this->arguments)
		{
			$this->arguments = array('%%row%%');
		}
	}
	
	/**
	 * The format function is used by TableMaker decorators to process the resulting
	 * string.
	 *
	 * @param array $parameters 
	 * @return string The HTML string
	 * @author Kevin Hallmark
	 */
	public function format($output, array $parameters = array())
	{
		$column      = $this->column;
		$view_helper = $this->name;
		
		$function = array($this->view, $view_helper);
		
		$options = $this->_parseParams($this->arguments, $parameters);
		
		if (!is_callable($function))
		{
			$message = sprintf('The view helper %s on %s could not be called.', $view_helper, $column);
			throw new LBHToolkit_TableMaker_Exception($message);
		}
		
		$output = call_user_func_array($function, $options);
		
		return $output;
	}
}