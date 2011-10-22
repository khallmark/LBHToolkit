<?php
/**
 * Paging.php
 * LBHToolkit_TableMaker_Paging
 * 
 * This component handles paging for the TableMaker
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

class LBHToolkit_TableMaker_Paging extends LBHToolkit_TableMaker_Abstract 
{
	// How many total elements should the paginator have
	const PAGINATOR_SIZE = 9;

	/**
	 * Takes an array of parameters and validates them. Called from the constructor
	 *
	 * @param string $params 
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function validateParams($params)
	{
		// Set a default action
		if (!$this->action)
		{
			$this->action = 'index';
		}
		
		// Set the default page to 1
		if (!$this->page)
		{
			$this->page = 1;
		}
		
		// Set hte count to the page size by default
		if (!$this->count)
		{
			$this->count = LBHToolkit_TableMaker_Abstract::PAGE_SIZE;
		}
		
		// If there is more than the maximum number of items a page, cap it
		if ($this->count > LBHToolkit_TableMaker_Abstract::PAGE_SIZE_MAX)
		{
			$this->count = LBHToolkit_TableMaker_Abstract::PAGE_SIZE_MAX;
		}
		
		if (!$this->show_summary)
		{
			$this->show_summary = TRUE;
		}
	}
	
	/**
	 * Renders the sorting link for a header
	 *
	 * @param array|object $data 
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function renderHeader(&$data, LBHToolkit_TableMaker_Paging $pagingInfo)
	{
		// Get the sort from the data parameter
		$sort = $data;
		
		// Render the link
		$link = $this->renderLink($sort, $this->page);
		
		// Return the link
		return $link;
	}
	
	/**
	 * Renders a paginator
	 *
	 * @param string $data 
	 * @param LBHToolkit_TableMaker_Paging $pagingInfo 
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function render(&$data, LBHToolkit_TableMaker_Paging $pagingInfo)
	{
		// Get the current page
		$current_page = $this->page;
		
		// Get the total number of pages
		$total_pages = $this->total_pages;
		
		// This is used to make sure there are the correct number of items, and 
		// that they are properly balanced
		$paginator_size = floor(self::PAGINATOR_SIZE/2);
		
		// Get the minimum page for the paginator
		$min_page = $current_page - $paginator_size;
		
		// If the page is less than 1, set it to 1
		if($min_page < 1)
		{
			$min_page = 1;
		}
		
		// Recalculate how many items should be to the right of the current element
		$paginator_size = ceil(self::PAGINATOR_SIZE/2) + ($paginator_size - ($this->page - $min_page));
		
		// Get the max page
		$max_page = $current_page + $paginator_size;
		
		// If it's more than the total, set it back to the total
		if($max_page > $total_pages)
		{
			$max_page = $total_pages;
		}
		
		// If min_page == max_page, the only page is 1
		if ($min_page == $max_page)
		{
			$min_page = $max_page = 1;
		}
		
		// Declare our data variable
		$html = '';
		
		// If we aren't on page 1, show a 'Prev' link
		if($this->page > 1)
		{
			$html = $html . sprintf('<li><a href="%s">First</a></li>', $this->renderLink($this->sort, (1)));
			$html = $html . sprintf('<li><a href="%s">Prev</a></li>', $this->renderLink($this->sort, ($this->page - 1)));
		}
		
		// Generate the middle links for the paginator
		for ($i = $min_page; $i <= $max_page; $i++)
		{
			// Handle the current page and other pages differently
			if ($i == $this->page)
			{
				// If this is hte current page, don't show a link and add a class
				$html = $html . sprintf('<li class="current">%s</li>', $i);
			}
			else
			{
				// If this is not the next page, show a link and don't add a class
				$link = $this->renderLink($this->sort, $i);
				$html = $html . sprintf('<li><a href="%s">%s</a></li>', $link, $i);
			}
		}
		
		// If we aren't on the last page, show a 'Next' link
		if ($this->page < $total_pages)
		{
			$html = $html . sprintf('<li><a href="%s">Next</a></li>', $this->renderLink($this->sort, ($this->page + 1)));
			$html = $html . sprintf('<li><a href="%s">Last</a></li>', $this->renderLink($this->sort, ($total_pages)));
		}
		
		$result_string = '';
		if ($this->show_summary)
		{
			$coeff = (($current_page - 1) * $this->count);
			$first_result = $coeff + 1;
			$last_result = $coeff + $this->count;
			
			if ($last_result > $this->total_count)
			{
				$last_result = $this->total_count;
			}
			
			$result_string = sprintf(
				'<div class="results">Showing results %s - %s of %s total results. Page %s of %s.</div>',
				$first_result,
				$last_result,
				$this->total_count,
				$current_page,
				$total_pages
			);
		}
		
		
		// Put hte items in the list and return the html
		return sprintf('<div class="pagination"><ul>%s</ul>%s</div>', $html, $result_string);
	}
	
	/**
	 * Render a link based on the provided parameters. Used to generate links everywhere
	 *
	 * @param string $sort 
	 * @param string $page 
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function renderLink($sort = NULL, $page = 1)
	{
		// If there is no period in the sort string, assemble it
		if (strpos($sort, '.') === FALSE)
		{
			$sort = sprintf('%s.%s', $sort, $this->getOrder($sort));
		}
		
		$order = $this->getOrder($sort);
		
		// Get the page number
		$page = $this->getPage($sort, $page);
		
		// Get the action
		$link = $this->action;
		
		// Add the sort parameters to the link
		$link = $link . "/sort/$sort/order/$order";
		
		// For everything except page 1 (the default) show the page number
		if ($page != 1)
		{
			$link = $link . "/page/$page";
		}
		
		// Append the query
		if ($this->query)
		{
			$query = '?';
			foreach($this->query AS $key => $value)
			{
				$query = $query . sprintf('%s=%s&', $key, $value);
			}
			$link = $link . $query;
		}
		
		// Return the link
		return $link;
	}
	
	/**
	 * Get's the correct page based on the supplied information
	 *
	 * @param string $sort 
	 * @param string $page 
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function getPage($sort, $page = 1)
	{
		if ($this->sort != $sort)
		{
			return 1;
		}
		
		return $page;
	}
	
	/**
	 * Gets the order for linking purposes
	 *
	 * @param string $sort 
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function getOrder($sort)
	{
		// If the sorts match, and the order is desc, then reorder to asc
		if($this->sort == $sort && $this->order == 'desc')
		{
			return 'asc';
		}
		
		// Return the default
		return 'desc';
	}
	
	/**
	 * Set the total number of items for paging information
	 *
	 * @param string $total_count 
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function setTotalCount($total_count)
	{
		// Set the total count
		$this->total_count = $total_count;
		
		// Defaults to only one page
		$total_pages = 1;
		
		// But if there is a count greater than 0, calculate a new max page
		if ($total_count != 0)
		{
			$total_pages = ceil(($this->total_count)/($this->count));
		}
		
		// Set the total number of pages
		$this->total_pages = $total_pages;
	}
}