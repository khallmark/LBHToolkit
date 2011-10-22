<?php
/**
 * TableMaker.php
 * LBHToolkit_TableMaker
 * 
 * The TableMaker is used to generate an HTML table from a set of array accessible
 * data. It is a Zend_Controller_Action_Helper that works in conjunction with the
 * Zend request object in order to drive it's parameters.
 * 
 * LICENSE
 * 
 * This file is subject to the New BSD License that is bundled with this package.
 * It is available in the LICENSE file. 
 * 
 * It is also available online at http://www.littleblackhat.com/lbhtoolkit
 * 
 * @author		Kevin Hallmark <kevin.hallmark@littleblackhat.com>
 * @since		2011-08-24
 * @package		LBHToolkit
 * @subpackage	TableMaker
 * @copyright	Little Black Hat, 2011
 * @license		http://www.littleblackhat.com/lbhtoolkit	New BSD License
 */

class LBHToolkit_TableMaker extends Zend_Controller_Action_Helper_Abstract implements LBHToolkit_TableMaker_Interface
{
	/**
	 * Standard Parameters
	 *
	 * @var array
	 */
	protected $_params = array();

	/**
	 * The defaut page size for a tablemaker result set
	 */
	const PAGE_SIZE = 20;
	const PAGE_SIZE_MAX = 100;
	
	/**
	 * The headers for the table
	 * 
	 * @var array
	 */
	protected $_columns;
	
	
	protected $_adapter = NULL;
	
	
	protected $_template_vars = array();
	
	/**
	 * Allows this plugin to be instantiated more easily from the HelperBroker
	 *
	 * @param string $params 
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function direct($params)
	{
		if ($params !== NULL)
		{
			$this->setParams($params);
		}
		
		$this->validateParams($params);
		
		return $this;
	}
	
	/**
	 * Setup and validate the parameters
	 *
	 * @param string $params 
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function validateParams($params)
	{
		$this->data_count = 0;
		
		if (!$this->adapter)
		{
			throw new LBHToolkit_TableMaker_Exception("No Adapter Specified");
		}
		
		$this->_adapter = new $this->adapter;
		
		if (!$this->count)
		{
			$this->count = LBHToolkit_TableMaker::PAGE_SIZE;
		}
		
	}
	
	/**
	 * Adds a new column to the table
	 * 
	 * @param $column The column object or an initialization array
	 */
	public function hasColumn($column)
	{
		if (is_object($column))
		{
			if (!is_a($column, 'LBHToolkit_TableMaker_Column'))
			{
				throw new Memberfuse_Rest_Exception("The object you added is not a valid column object");
			}
		}
		else
		{
			$column = new LBHToolkit_TableMaker_Column($column);
			$column->view = $this->getActionController()->view;
		}
		
		$this->_columns[$column->column_id] = $column;
		
		return $column;
	}
	
	/**
	 * The Template Variables are passed on rendering to an partials rendered by
	 * the TableMaker.
	 *
	 * @param string $key 
	 * @param string $value 
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function addTemplateVar($key, $value)
	{
		$this->_template_vars[$key] = $value;
	}
	
	/**
	 * This handles setting the paging info object
	 *
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function getPagingInfo()
	{
		$paging = array();
		
		$default_sort = 'id';
		if($this->default_sort)
		{
			$default_sort = $this->default_sort;
		}
		
		$default_order = 'asc';
		if ($this->default_order)
		{
			$default_order = $this->default_order;
		}
		
		$paging['default_sort'] = $default_sort;
		$paging['default_order'] = $default_order;
		
		$paging['page'] = $this->getRequest()->getParam('page', 1);
		$paging['count'] = $this->getRequest()->getParam('count', $this->count);
		
		
		$paging['sort'] = $this->getRequest()->getParam('sort', $default_sort);
		$paging['order'] = $this->getRequest()->getParam('order', $default_order);
		
		$paging['sort_order'] = sprintf('%s.%s', $paging['sort'], $paging['order']);
		
		$paging['action'] = $this->getActionName();
		
		
		$paging['query'] = $this->getRequest()->getQuery();
		
		$pagingInfo = new LBHToolkit_TableMaker_Paging($paging);
		
		return $pagingInfo;
	}
	
	public function getActionName()
	{
		$moduleName = $this->getRequest()->getModuleName();
		$controllerName = $this->getRequest()->getControllerName();
		$actionName = $this->getRequest()->getActionName();
		
		
		return '/' . $moduleName . '/' . $controllerName . '/' .$actionName;
	}

	public function setData($data)
	{
		$this->getAdapter()->setData($data);
	}
	
	public function renderTable()
	{
		$pagingInfo = $this->getPagingInfo();
		
		$total_count = $this->getAdapter()->getTotalCount();//$this->total_count;
		
		$data = $this->getAdapter()->getData($pagingInfo);
		
		if (count($data) == 0 || $total_count == 0)
		{
			return $this->renderEmpty();
		}
		
		$pagingInfo->setTotalCount($total_count);
		
		$html = sprintf(
			'<table class="%s"><thead>%s</thead><tbody>%s</tbody></table>%s', 
			$this->class, 
			$this->renderHeader($data, $pagingInfo), 
			$this->render($data, $pagingInfo), 
			$pagingInfo->render($data, $pagingInfo)
		);
		
		return $html;
	}
	
	public function renderEmpty()
	{
		if($this->empty_text)
		{
			return sprintf("<p>%s</p>", $this->empty_text);
		}
		
		return "<p>No results were returned for your request.</p>";
	}
	
	public function renderHeader(&$data, LBHToolkit_TableMaker_Paging $pagingInfo)
	{
		$columns = $this->_columns;
		
		if(count($columns) == 0)
		{
			throw new LBHToolkit_TableMaker_Exception("No columns provided for the table.");
		}
		
		foreach ($columns AS $column)
		{
			$html = $html . $column->renderHeader($data, $pagingInfo);
		}
		
		$html = sprintf('<tr>%s</tr>', $html);
		
		return $html;
	}
	
	/**
	 * Generates a 'table' element with the data in the object
	 *
	 * @access public
	 * 
	 */
	public function render(&$data, LBHToolkit_TableMaker_Paging $pagingInfo)
	{
		
		$columns = $this->_columns;
		foreach ($data AS $row)
		{
			if (is_array($row))
			{
				$row = (object) $row;
			}
			
			$row_html = '';
			foreach ($columns AS $column)
			{
				$column->template_vars = $this->_template_vars;
				
				$column_id = $column->column_id;
				$row_html  = $row_html . $column->render($row, $pagingInfo);
			}
			
			$html = $html . sprintf('<tr id="tr_%s">%s</tr>', $row->id, $row_html);
		}
		
		return $html;
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
	
	public function getAdapter()
	{
		return $this->_adapter;
	}
}