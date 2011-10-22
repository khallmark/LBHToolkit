<?php
/**
 * Template.php
 * LBHToolkit_TableMaker_Decorator_Template
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

class LBHToolkit_TableMaker_Decorator_Template extends LBHToolkit_TableMaker_Decorator_Abstract
{
	public function validateParams($params)
	{
		if (!$this->name)
		{
			throw new LBHToolkit_TableMaker_Exception('No template file name provided for ' . $this->identifier);
		}
		
		if (!$this->arguments)
		{
			$this->arguments = array('%%row%%');
		}
		
		if (!is_array($this->arguments))
		{
			throw new LBHToolkit_TableMaker_Exception('Arguments must be an array for ' . $this->identifier);
		}
	}
	
	/**
	 * The format function is used by TableMaker decorators to process the resulting
	 * string.
	 *
	 * @param string $output
	 * @param array $parameters 
	 * @return string The HTML string
	 * @author Kevin Hallmark
	 */
	public function format($output, array $parameters = array())
	{
		$template_params = $parameters;
		
		// If template variables were passed in
		if ($this->arguments && is_array($this->arguments))
		{
			$template_params = $this->_parseParams(array_merge($this->arguments, $parameters), $parameters);
		}
		
		$output = $this->view->partial($this->name, $template_params);
		
		return $output;
	}
}