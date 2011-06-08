<?php
/**
 * Generator.php
 * LBHToolkit_vCard_Generator
 * 
 * A simple php5 class for generating vCard files
 * 
 * LICENSE
 * 
 * This file is subject to the New BSD License that is bundled with this package.
 * It is available in the LICENSE file. 
 * 
 * It is also available online at http://www.littleblackhat.com/lbhtoolkit
 * 
 * @author      Kevin Hallmark <kevin.hallamrk@littleblackhat.com>
 * @since       2010-05-17
 * @package     LBHToolkit
 * @copyright   Little Black Hat, 2010
 * @license     http://www.littleblackhat.com/lbhtoolkit    New BSD License
 * 
 * The list of possible fields can be found at the bottom of the file.
 */


class LBHToolkit_vCard_Generator {
	/**
	 * The array of values to add to the vcard
	 *
	 * @var array
	 */
	protected $options = array();
	
	/**
	 * The creator code for this vcard class
	 */
	const CREATOR = '-// http://www.littleblackhat.com/lbhtoolkit // LBHToolkit // LBHToolkit vCard Generator 1.0';
	
	/**
	 * undocumented function
	 *
	 * @param array $vCard_data 
	 * @return void
	 * @author Kevin Hallmark
	 */
	public function __construct ($vCard_data = null) 
	{
		if($vCard_data !== null && is_array($vCard_data)) 
		{
			$this->options = $vCard_data;
		}
	}

	public function create() 
	{
		if($this->name === null && $this->formatted_name === null && $this->company === null) {
			throw new Exception("You didn't set name and formatted name. These properties are required");
		}
		
		$vcard = "BEGIN:VCARD\r\n";
		
		$this->addAttribute($vcard, "VERSION", '3.0');
		$this->addAttribute($vcard, "CLASS", 'PUBLIC');
		$this->addAttribute($vcard, "PRODID", self::CREATOR);
		$this->addAttribute($vcard, "REV", date("c"));
		$this->addAttribute($vcard, "TZ", date("P"));
		
		if($this->formatted_name === null && $this->name === null && $this->company !== null) {
			$this->addAttribute($vcard, "FN", $this->company);
			$this->addAttribute($vcard, "N", ';;;;');
			
		} else if($this->name === null) {
			$this->addAttribute($vcard, "FN", $this->formatted_name);
			$this->addAttribute($vcard, "N", str_replace(' ', ';', $this->formatted_name.';;'));
		} else if($this->formatted_name === null) {
			$this->addAttribute($vcard, "FN", trim(str_replace(';', ' ', $this->name)));
			$this->addAttribute($vcard, "N", $this->name);
		} else {
			$this->addAttribute($vcard, "FN", $this->formatted_name);
			$this->addAttribute($vcard, "N", $this->name);
			
		}
		
		
		
		$this->addAttribute($vcard, "NICKNAME", $this->nickname);
		$this->addAttribute($vcard, "ORG", $this->company);

		$this->addAttribute($vcard, "BIRTHDAY", $this->birthday);

		$this->addAttribute($vcard, "ROLE", $this->role);
		$this->addAttribute($vcard, "TITLE", $this->title);
		$this->addAttribute($vcard, "NOTE", $this->note);

		if($this->work_email) {
			$email_work_options = "TYPE=INTERNET,PREF";
			$email_home_options = 'TYPE=INTERNET';
		} else {
			$email_work_options = 'TYPE=INTERNET';
			$email_home_options = 'TYPE=INTERNET,PREF';
		}
		
		$this->addAttribute($vcard, 'EMAIL', $this->work_email, $email_work_options);
		$this->addAttribute($vcard, 'EMAIL', $this->home_email, $email_home_options);
		
		$this->addAttribute($vcard, "TEL", $this->cell_phone, "TYPE=VOICE,CELL");

		$this->addAttribute($vcard, "TEL", $this->home_fax, "TYPE=FAX,HOME");
		$this->addAttribute($vcard, "TEL", $this->work_fax, "TYPE=FAX,WORK");
		$this->addAttribute($vcard, "TEL", $this->home_phone, "TYPE=VOICE,HOME");
		$this->addAttribute($vcard, "TEL", $this->work_phone, "TYPE=VOICE,WORK");

		$this->addAttribute($vcard, 'URL', $this->uri);
		
		$this->addAddress($vcard, 'home');
		$this->addAddress($vcard, 'work');
		
		$vcard.= "END:VCARD\n";

		return $vcard;
	}
	
	/**
	 * undocumented function
	 *
	 * @param string $vcard 
	 * @param string $type 
	 * @return void
	 * @author Kevin Hallmark
	 */
	protected function addAddress(&$vcard, $type) {
		$address = ';;';
		$label = '';
		
		$keys = array($type. '_address',$type.'_city',$type.'_state',$type.'_zip',$type.'_country');
		
		foreach($keys AS $key) {
			if($this->$key) {
				$address .= $this->$key.';';
				$label .= $this->$key."\\n";
			} else {
				$address .= ';';
			}
		}
		
		$address = substr($address, 0, -1);
		$label = substr($label, 0,-2);
		
		$address_options = "TYPE=". strtoupper($type) .",POSTAL,PARCEL";
		$label_options = "TYPE=DOM,". strtoupper($type) .",POSTAL,PARCEL";
		
		if($label != '') {
			$this->addAttribute($vcard, "ADR", $address, $address_options);
			$this->addAttribute($vcard, "LABEL", $label, $label_options);
		}
	}
	
	public function __get($key) 
	{
		if(isset($this->options[$key])) {
			return $this->options[$key];
		}
		
		return null;
	}
	
	public function __set($key, $value) 
	{
		$this->options[$key] = $value;
	}
	
	protected function addAttribute(&$vcard, $element_name, $value, $options = null) {
		if($value !== null) {
			$vcard .= $element_name;
			
			if($options !== null) {
				$vcard .= ';'.$options;
			}
			$vcard .= ":" . $value . "\r\n";
		}
	}
}

/*
name
formatted_name
company
nickname
birthday
role
title
note

work_email
home_email


home_fax
work_fax
home_phone
cell_phone
work_phone

uri

home_address
home_city
home_state
home_zip
home_country

work_address
work_city
work_state
work_zip
work_country
*/
