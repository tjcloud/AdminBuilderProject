<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Form Validation Library
 *
 * Provides additional form validation functionality beyond CIs default.
 *
 * @package		CodeIgniter
 * @subpackage	Library
 * @category	Library
 * @author		Chris Monnat
 */

 
class APP_Form_validation extends CI_Form_validation 
{

	public function __construct() 
	{
		parent::CI_Form_Validation();
	}
	
	
	/** 
	 * Function for manually adding an error to errors array for display 
	 * with other form errors.
	 * 
	 * @access public 
	 * @param string
	 * @param string
	 * @return void
	 */ 
	public function manual_error($field, $message)
	{
		$this->_error_array[$field] = $message;
	}
	
	
	/** 
	 * Validates format of dates (MM/DD/YYYY).
	 * 
	 * @access public 
	 * @param string
	 * @return bool
	 */ 
	public function valid_date($str)
	{
		return ( ! ereg("^[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}$", $str)) ? FALSE : TRUE;
	}
	
	
	/** 
	 * Validates format of phone number (XXX-XXX-XXXX).
	 * 
	 * @access public 
	 * @param string
	 * @return bool
	 */ 
	public function valid_phone($str)
	{
		return ( ! ereg("^[0-9]{3}-[0-9]{3}-[0-9]{4}$", $str)) ? FALSE : TRUE;
	}
		
	
	/** 
	 * Prep entered value as a valid subdomain value.
	 * 
	 * @access public 
	 * @param string
	 * @return string
	 */ 
	public function url_title($str)
	{
		$str = str_replace('.', '-', $str); // remove periods in URL string
		return url_title($str, 'dash', TRUE);
	}
	
	
	/** 
	 * Validates format of credit card number and checks against Mod 10 algorithm.
	 * 
	 * @access public 
	 * @param string
	 * @return string
	 */ 
	public function valid_cc_number($number)
	{
		// is the number in the correct format?
		$valid_format = FALSE;
		if(ereg("^5[1-5][0-9]{14}$", $number)) // mastercard
		{
			$valid_format = TRUE;
		}
		elseif(ereg("^4[0-9]{12}([0-9]{3})?$", $number)) // visa
		{
			$valid_format = TRUE;
		}
		// will add others as needed
	
		// is the number valid?
		$card_number = strrev($number);
		$num_sum = 0;
		
		for($i=0; $i < strlen($card_number); $i++)
		{
			$current_num = substr($card_number, $i, 1);
			
			// double every second digit
			if($i%2 == 1)
			{
				$current_num *= 2;
			}
			
			// add digits of 2-digit numbers together
			if($current_num > 9)
			{
				$first_num = $current_num % 10;
				$second_num = ($current_num - $first_num) / 10;
				$current_num = $first_num + $second_num;
			}
			
			$num_sum += $current_num;
		}
		
		// if the total has no remainder it's OK
		$pass_check = ($num_sum % 10 == 0);
		
		if($valid_format && $pass_check)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
}

/* End of file MY_validation.php */ 
/* Location: ./application/libraries/MY_validation.php */ 