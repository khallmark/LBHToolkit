<?php
/**
 * Array.php
 * LBHToolkit_TableMaker_Adapter_Array
 * 
 * The TableMaker Array Adapter can use an of objects to generate a table.
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

class LBHToolkit_TableMaker_Adapter_Array extends LBHToolkit_TableMaker_Adapter_Abstract
{
	protected $_id = 0;
	
	/**
	 * Takes an array of parameters and validates them. Called from the constructor
	 *
	 * @param string $params 
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function validateParams($params)
	{
		
	}
	
	
	/**
	 * Set the data into this adapter
	 *
	 * @param string $data 
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function setData($data)
	{
		if (!is_array($data))
		{
			throw new LBHToolkit_TableMaker_Exception("The provided data is not a valid array.");
		}
		
		$this->data = $data;
	}
	
	/**
	 * Use the paging info from the TableMaker to get the specific result page.
	 *
	 * @param LBHToolkit_TableMaker_Paging $pagingInfo
	 * @return mixed Any interable collection of objects
	 * 
	 * @author Kevin Hallmark
	 */
	public function getData(LBHToolkit_TableMaker_Paging $pagingInfo)
	{
		return array_slice($this->data, $pagingInfo->count * ($pagingInfo->page - 1), $pagingInfo->count);
	}
	
	/**
	 * Get the total number of results for this adapter
	 *
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function getTotalCount()
	{
		$count = count($this->data);
		return $count;
	}
	
	/**
	 * This function should return a unique/primary key for the passed in row.
	 *
	 * @param string $row 
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function getPrimaryKey($row)
	{
		return (++$this->_id);
	}
}