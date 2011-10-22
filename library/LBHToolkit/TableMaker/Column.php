<?php
/**
 * Header.php
 * LBHToolkit_TableMaker_Header
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
 * @subpackage  TableMaker
 * @copyright   Little Black Hat, 2011
 * @license     http://www.littleblackhat.com/lbhtoolkit    New BSD License
 */

class LBHToolkit_TableMaker_Column extends LBHToolkit_TableMaker_Abstract
{
	protected $_decorators = array();
	
	/**
	 * Takes an array of parameters and validates them. Called from the constructor
	 *
	 * @param string $params 
	 * @return void
	 * 
	 * @author Kevin Hallmark
	 */
	public function validateParams($params)
	{
		// Check that the column_id field is set (it's required)
		if (!$this->column_id)
		{
			throw new LBHToolkit_TableMaker_Exception('No column_id provided');
		}
		
		// Make sure the label is set, if it isn't, use an inflection of the id
		if ($this->label === NULL)
		{
			$this->label = ucwords(str_replace('_', ' ', $this->column_id));
		}
		
		if ($this->decorators)
		{
			foreach ($this->decorators AS $name => $decorator)
			{
				$this->addDecorator($name, $decorator);
			}
		}
	}
	
	public function addDecorator($alias, $decorator)
	{
		if (is_object($decorator))
		{
			if (!($decorator instanceof LBHToolkit_TableMaker_Decorator_Interface))
			{
				throw new Memberfuse_Rest_Doctrine_Exception("The object you added for $alias doesn't conform to the adapter interface.");
			}
		}
		else
		{
			if (!isset($decorator['type']))
			{
				throw new LBHToolkit_TableMaker_Exception(sprintf('No decorator type specified for %s', $alias));
			}
			
			switch ($decorator['type'])
			{
				case 'ViewHelper':
					$class_name = 'LBHToolkit_TableMaker_Decorator_ViewHelper';
					break;
				case 'Template':
					$class_name = 'LBHToolkit_TableMaker_Decorator_Template';
					break;
				case 'Function':
					$class_name = 'LBHToolkit_TableMaker_Decorator_Function';
					break;
				default:
					$class_name = $decorator['type'];
					break;
			}
			
			if (!class_exists($class_name))
			{
				throw new LBHToolkit_TableMaker_Exception("The decorator $class_name could not be found.");
			}
			
			$decorator = new $class_name($decorator);
		}
		
		$decorator->identifier = $alias;
		
		$this->_decorators[$aliabrbs] = $decorator;
	}
	
	/**
	 * Render the header for this data set
	 *
	 * @param array|object $data 
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function renderHeader(&$data, LBHToolkit_TableMaker_Paging $pagingInfo)
	{
		// Set the label
		$label = $this->label;
		
		// If there is a sort available, add a link
		if ($this->sort)
		{
			$label = sprintf('<a href="%s">%s</a>', $pagingInfo->renderHeader($this->sort, $pagingInfo), $label);
		}
		
		// Get the header attributes
		$attribs = $this->getHeaderAttributes();
		
		// Allow a custom function to process the header information
		if($this->header_function && method_exists($data, $this->header_function))
		{
			$function = $this->header_function;
			$data->$function($this, $attribs);
		}
		
		// Parse the attributes in to an HTML string
		$attribs = $this->_parseAttribs($attribs);
		
		// Format it into a header
		$header = sprintf('<th%s>%s</th>', $attribs, $label);
		
		// Return the HTML
		return $header;
	}
	
	/**
	 * Render the main row for this data set
	 *
	 * @param string $data 
	 * @param LBHToolkit_TableMaker_PagingInfo $pagingInfo 
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function render(&$data, LBHToolkit_TableMaker_Paging $pagingInfo)
	{
		// Get the column id
		$column = $this->column_id;
		
		// Init html
		$html = '';
		
		// Get the data value
		if (is_object($data) && isset($data->$column))
		{
			$html = (string)$data->$column;
		}
		else if (is_array($data) && isset($data[$column]))
		{
			$html = (string)$data[$column];
		}
		
		// Default arguments passed to function/view_helper/template
		$default_arguments = array(
			'row' => $data, 
			'row_value' => $html, 
			'html' => &$html
		);
		
		// Get the html attributes to add to this column
		$attribs = $this->getBodyAttributes();
		
		if (count($this->getDecorators()))
		{
			foreach ($this->getDecorators() AS $decorator)
			{
				$decorator->view = $this->view;
				$html = $decorator->format($html, $default_arguments);
			}
		}

		/*
		// Render the body function
		if ($this->body_function)
		{
			// Get the body function
			$function = $this->body_function['name'];
			
			// If it's an array...
			if (is_array($function))
			{
				// Check to make sure one of our special keys isn't used
				// in the callable array
				$function = $this->_parseParams($function, $default_arguments);
			}
			
			$body_function_params = array($default_arguments);
			if (isset($this->body_function['params']))
			{
				// Use them
				$body_function_params = $this->_parseParams($this->body_function['params'], $default_arguments);
			}
			
			if (!is_callable($function))
			{
				$message = sprintf('The body function on %s could not be called.', $column);
				throw new LBHToolkit_TableMaker_Exception($message);
			}
			
			$html = call_user_func_array($function, $body_function_params);
			
		}*/

		$html = sprintf('<td%s>%s</td>', $this->_parseAttribs($attribs), $html);
		
		return $html;
	}
	
	/**
	 * This calculates any custom attributes you want on the header columns
	 *
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function getHeaderAttributes()
	{
		// Add the default col scope
		$attribs = array('scope' => 'col');
		
		// If there is a header class set, add it to the attributes
		if ($this->header_class)
		{
			$attribs['class'] = $this->header_class;
		}
		
		// Return the default
		return $attribs;
	}
	
	/**
	 * This calculates any custom attributes you want on the body columns
	 *
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function getBodyAttributes()
	{
		$attribs = array();
		
		// If there is a body class, set it
		if ($this->body_class)
		{
			$attribs['class'] = $this->body_class;
		}
		
		return $attribs;
	}
	
	public function getDecorators()
	{
		return $this->_decorators;
	}
	
	/**
	 * Parse Attribtues arrays into a string
	 *
	 * @param string $attribs 
	 * @return void
	 * @author Kevin Hallmark
	 */
	protected function _parseAttribs($attribs)
	{
		$attrib_str = '';
		
		if (count($attribs))
		{
			foreach($attribs AS $key => $value)
			{
				$attrib_str = $attrib_str . sprintf(' %s="%s"', $key, $value);
			}
		}
		
		return $attrib_str;
	}
	
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