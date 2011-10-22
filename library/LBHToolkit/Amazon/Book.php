<?php
/**
 * Book.php
 * LBHToolkit_Amazon_Book
 * 
 * Encapsulates interactions with the Amazon Product Avertising API
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
 * @subpackage  Amazon
 * @copyright   Little Black Hat, 2011
 * @license     http://www.littleblackhat.com/lbhtoolkit    New BSD License
 */

class LBHToolkit_Amazon_Book
{
	/**
	 * Finds all books in the amazon system by ISBN 13. Returns an array of results.
	 *
	 * @param string $isbn13 
	 * @return array
	 * @author Kevin Hallmark
	 */
	public static function findByIsbn13($isbn13, $sections = 'ItemAttributes')
	{
		// Get the Amazon service singleton
		$amazon = LBHToolkit_Model_Amazon::getService();
		
		// Find the book, searching the keywords for the ISBN
		$results = $amazon->itemSearch(
			array(
				'SearchIndex' => 'Books',
				'Keywords' => $isbn13,
				'ResponseGroup' => $sections,
			)
		);
		
		// Initialize the matches
		$matches = array();
		
		// Loop through the results
		foreach ($results as $result)
		{
			// Check the EAN (Amazon's name for ISBN13)
			if ($result->EAN == $isbn13)
			{
				// Add it to the matches
				$matches[] = $result;
			}
		}
		
		return $matches;
	}
	
}
