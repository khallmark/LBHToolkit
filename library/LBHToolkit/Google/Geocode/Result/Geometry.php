<?php
/**
 * Geometry.php
 * LBHToolkit_Google_Geocode_Geometry
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

class LBHToolkit_Google_Geocode_Result_Geometry extends LBHToolkit_Google_Response
{
	const LOCATION_TYPE_ROOFTOP = 'ROOFTOP';
	const LOCATION_TYPE_INTERPOLATED = 'INTERPOLATED';
	const LOCATION_TYPE_GEOMETRIC_CENTER = 'GEOMETRIC_CENTER';
	const LOCATION_TYPE_APPROXIMATE = 'APPROXIMATE';
	
	public function isApproximate()
	{
		$location_type = $this->getResponse()->location_type;
		
		$approximate = TRUE;
		
		if ($location_type == self::LOCATION_TYPE_ROOFTOP)
		{
			$approximate = FALSE;
		}
		
		return $approximate;
	}
	
	public function getCoordinates()
	{
		$location = $this->getResponse()->location;
		
		return $location;
	}
	
	public function getCoordinatesString()
	{
		$location = $this->getCoordinates();
		
		return $location->lat . ',' . $location->lng;
	}
}