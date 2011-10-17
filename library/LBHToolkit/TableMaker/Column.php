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
 * @author      Kevin Hallmark <khallmark@avectra.com>
 * @since       2011-08-24
 * @package     LBHToolkit
 * @subpackage  TableMaker
 * @copyright   Little Black Hat, 2011
 * @license     http://www.littleblackhat.com/lbhtoolkit    New BSD License
 */

class LBHToolkit_TableMaker_Column extends LBHToolkit_TableMaker_Abstract
{
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
		if (!$this->label)
		{
			$this->label = ucwords(str_replace('_', ' ', $this->column_id));
		}
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
		
		$column = $this->column_id;
		
		$data_str = $data->$column;
		
		$attribs = $this->getBodyAttributes();
		
		// 
		if ($this->body_function && method_exists($data, $this->body_function))
		{
			$function = $this->body_function;
			$data_str = $data->$function($this, $attribs);
		}
		
		// Render a template file
		if ($this->template)
		{
			$config['row'] = $data;
			$config['data_str'] = $data_str;
			$config['debug'] = $this->view->debug;
			
			$data_str = $this->view->partial($this->template, $config);
		}
		
		$attribs = $this->_parseAttribs($attribs);
		
		$body = sprintf('<td%s>%s</td>', $attribs, $data_str);
		
		return $body;
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
}