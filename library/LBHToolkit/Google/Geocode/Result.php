<?php
/**
 * Result.php
 * LBHToolkit_Google_Geocode_Result
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
 * @since       2011-10-07
 * @package     LBHToolkit
 * @subpackage  LBHToolkit_Google_Geocode
 * @copyright   Little Black Hat, 2011
 * @license     http://www.littleblackhat.com/lbhtoolkit    New BSD License
 */

class LBHToolkit_Google_Geocode_Result extends LBHToolkit_Google_Response
{
	public function getGeometry()
	{
		$geometry = $this->getResponse()->geometry;
		
		return new LBHToolkit_Google_Geocode_Result_Geometry($geometry);
	}
}