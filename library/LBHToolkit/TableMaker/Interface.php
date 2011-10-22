<?php
/**
 * Interface.php
 * LBHToolkit_TableMaker_Interface
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

interface LBHToolkit_TableMaker_Interface
{
	/**
	 * Takes an array of parameters and validates them. Called from the constructor
	 *
	 * @param string $params 
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function validateParams($params);
	
	
	/**
	 * Render the header for this data set
	 *
	 * @param array|object $data 
	 * @param LBHToolkit_TableMaker_PagingInfo $pagingInfo 
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function renderHeader(&$data, LBHToolkit_TableMaker_Paging $pagingInfo);
	
	
	/**
	 * Render the main row for this data set
	 *
	 * @param string $data 
	 * @param LBHToolkit_TableMaker_PagingInfo $pagingInfo 
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function render(&$data, LBHToolkit_TableMaker_Paging $pagingInfo);
}