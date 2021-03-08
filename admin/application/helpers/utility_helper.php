<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');



// --------------------------------------------------------------------

/**
 * Returns the global CI object
 *
 * @return 	object
 */
if (!function_exists('CI'))
{
	function CI() {
	    if (!function_exists('get_instance')) return FALSE;

	    $CI =& get_instance();
	    return $CI;
	}
}


	

// --------------------------------------------------------------------

/**
 * Capture content via an output buffer
 *
 * @param	boolean	turn on output buffering
 * @param	string	if set to 'all', will clear end the buffer and clean it
 * @return 	string	return buffered content
 */
if (!function_exists('capture'))
{
	function capture($on = TRUE, $clean = 'all')
	{
		$str = '';
		if ($on)
		{
			ob_start();
		}
		else
		{
			$str = ob_get_contents();
			if (!empty($str))
			{
				if ($clean == 'all')
				{
					ob_end_clean();
				}
				else if ($clean)
				{
					ob_clean();
				}
			}
			return $str;
		}
	}
}

// --------------------------------------------------------------------

/**
 * Format true value
 *
 * @param	mixed	possible true value
 * @return 	string	formatted true value
 */
if (!function_exists('is_true_val'))
{
	function is_true_val($val)
	{
		$val = strtolower($val);
		return ($val == 'y' || $val == 'yes' || $val === 1  || $val == '1' || $val== 'true' || $val == 't');
	}
}

// --------------------------------------------------------------------

/**
 * Boolean check to determine string content is serialized
 *
 * @param	mixed	possible serialized string
 * @return 	boolean
 */
if (!function_exists('is_serialized_str'))
{
	function is_serialized_str($data)
	{
		if ( !is_string($data))
			return false;
		$data = trim($data);
		if ( 'N;' == $data )
			return true;
		if ( !preg_match('/^([adObis]):/', $data, $badions))
			return false;
		switch ( $badions[1] ) :
		case 'a' :
		case 'O' :
		case 's' :
			if ( preg_match("/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data))
				return true;
			break;
		case 'b' :
		case 'i' :
		case 'd' :
			if ( preg_match("/^{$badions[1]}:[0-9.E-]+;\$/", $data))
				return true;
			break;
		endswitch;
		return false;
	}
}

// --------------------------------------------------------------------

/**
 * Boolean check to determine string content is a JSON object string
 *
 * @param	mixed	possible serialized string
 * @return 	boolean
 */
if (!function_exists('is_json_str'))
{
	function is_json_str($data)
	{
		if (is_string($data))
		{
			$json = json_decode($data, TRUE);
			return ($json !== NULL AND $data != $json);
		}
		return NULL;
	}
}

// --------------------------------------------------------------------

/**
 * Print object in human-readible format
 * 
 * @param	mixed	The variable to dump
 * @param	boolean	Return string
 * @return 	string
 */
if (!function_exists('print_obj'))
{
	function print_obj($obj, $return = FALSE)
	{
		$str = "<pre>";
		if (is_array($obj))
		{
			// to prevent circular references
			if (is_a(current($obj), 'Data_record'))
			{
				foreach($obj as $key => $val)
				{
					$str .= '['.$key.']';
					$str .= $val;
				}
			}
			else
			{
				$str .= print_r($obj, TRUE);
			}
		}
		else
		{
			if (is_a($obj, 'Data_record'))
			{
				$str .= $obj;
			}
			else
			{
				$str .= print_r($obj, TRUE);
			}
		}
		$str .= "</pre>";
		if ($return) return $str;
		echo $str;
	}
}

// --------------------------------------------------------------------

/**
 * Logs an error message to logs file
 *
 * @param	string	Error message
 * @return 	void
 */
if (!function_exists('log_error'))
{
	function log_error($error) 
	{
		log_message('error', $error);
	}
}

// --------------------------------------------------------------------

/**
 * Returns whether the current environment is set for development
 *
 * @return 	boolean
 */
if (!function_exists('is_dev_mode'))
{
	function is_dev_mode()
	{
		return (ENVIRONMENT != 'production');
	}
}

// --------------------------------------------------------------------

/**
 * Returns whether the current environment is equal to the passed environment
 *
 * @return 	boolean
 */
if (!function_exists('is_environment'))
{
	function is_environment($environment)
	{
		return (strtolower(ENVIRONMENT) == strtolower($environment));
	}
}

if (!function_exists('json_headers'))
{
	function json_headers($no_cache = TRUE)
	{
		if ($no_cache)
		{
			header('Cache-Control: no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		}
		header('Content-type: application/json');
	}
}

if(!function_exists('char_separated')){

	function char_separated($array,$char=','){
		$char_separated = implode($char, $array);
		return $char_separated;
	}
}


if(!function_exists('char_separated_to_array')){

	function char_separated_to_array($string,$char=','){
		$char_separated_to_array = explode($char, $string);
		return $char_separated_to_array;
	}
}

if(!function_exists('ordinal')){
	function ordinal($number) {
	    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
	    if ((($number % 100) >= 11) && (($number%100) <= 13))
	        return $number. 'th';
	    else
	        return $number. $ends[$number % 10];
	}
}
	


function uniqidReal($lenght = 13) {
    // uniqid gives 13 chars, but you could adjust it to your needs.
    if (function_exists("random_bytes")) {
        $bytes = random_bytes(ceil($lenght / 2));
    } elseif (function_exists("openssl_random_pseudo_bytes")) {
        $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
    } else {
        throw new Exception("no cryptographically secure random function available");
    }
    return substr(bin2hex($bytes), 0, $lenght);
}



//------------------------------------------------------------------

/*=================================================================
=            			EMAIL HELPER           	 				=
=================================================================*/

if( ! function_exists('sendmail')){


	function sendmail($mail_data=array(),$mail_type='html'){

		CI()->load->library('email');

		CI()->email->from($mail_data['from'], $mail_data['from_name']);
		CI()->email->to($mail_data['to']);

		if(isset($mail_data['cc'])){
			CI()->email->cc($mail_data['cc']);
		}

		if(isset($mail_data['bcc'])){
			CI()->email->bcc($mail_data['bcc']);
		}

		CI()->email->subject($mail_data['subject']);

		if(isset($mail_data['has_attachment']) && $mail_data['has_attachment']==FALSE){
			CI()->email->attach($mail_data['attachment']);
		}

		if($mail_type=='text'){	
			$message=$mail_data['data'];
		}else if($mail_type=='html'){
			$message=CI()->load->view($mail_data['view'],$mail_data['data']);
		}

		CI()->email->message($mail_data['data']);

		if(!CI()->email->send()){
			CI()->email->print_debugger(array('headers'));
		}
	}
}



/*=================================================================
=            			ASSETS COMMON           	 				=
=================================================================*/


if( ! function_exists('assets_url')){

	function assets_url(){
		return base_url().'common/assets/';
	}
}

if(!function_exists('delete_files')){

	function delete_files($target) {
	    if(is_dir($target)){
	        $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned

	        foreach( $files as $file ){
	            delete_files( $file );      
	        }
	        if(isset($target) && is_dir($target)){
	        	rmdir( $target );
	        }
	       
	    } elseif(is_file($target)) {
	        unlink( $target );  
	    }
	}	
}

if(!function_exists('isHomogenous')){

	function isHomogenous($arr) {
	    $firstValue = current($arr);
	    foreach ($arr as $val) {
	        if ($firstValue !== $val) {
	            return false;
	        }
	    }
	    return true;
	}

}


if(!function_exists('post_data')){
	function post_data($post_var){
		return xss_clean(strip_javascript(strip_whitespace(encode_php_tags(CI()->input->post($post_var)))));
	}
}

if(!function_exists('get_data')){
	function get_data($get_var){
		return xss_clean(strip_javascript(strip_whitespace(encode_php_tags(CI()->input->get($post_var)))));
	}
}


if(!function_exists('get_routes')){

	function get_routes(){

		$routes=CI()->router->routes;

		//print_obj($routes);die;

		// foreach ($routes as $key => $value) {
		// 	// echo $key;
		// 	// echo '<br>';
		// 	if($key=='companies'){
		// 		$r='All Companies';
		// 	}else if($key=='companies/oltemplates'){
		// 		$r='All Offerletter Templates';
		// 	}else if($key=='companies/oltemplates/add'){
		// 		$r='Add Offerletter Template';
		// 	}else if($key=='companies/oltemplates/(:num)/(:num)'){
		// 		$r='Update Offerletter Template';
		// 	}else if($key=='companies/deleteoletter'){
		// 		$r='Delete Offetletter Template';
		// 	}else if($key='companies/save'){
		// 		$r='Add Companies';
		// 	}else if($key=='companies/delete'){
		// 		$r='Delete Companies';
		// 	}else if($key=='companies/changestatus'){
		// 		$r='Change Companies Status';
		// 	}else if($key=='companies/designations'){
		// 		$r='Company Designations';
		// 	}

		// 	echo $r;
		// 	echo '<br>';
		// }

		// die;

		$removeKeys = array('404_override', 'account_logout','search_candidates','check_data_exists','search_users','search_employees','account_login','candidates/upload_data_excel','candidate/expdelete','candidates/load_medias','companies/get_loan_masters','account','updateprofile','upload_files');

		foreach($removeKeys as $key) {
		   unset($routes[$key]);
		}

		foreach ($routes as $key => $value) {

			if($key=='users/(:num)'){
				$r='User Data Edit View';
			}else if($key=='users'){
				$r='User Data View';
			}else if($key=='users/update'){
				$r='User Data Edit';
			}else if($key=='users/groups'){
				$r='User Groups Data View';
			}else if($key=='users/groups/(:num)'){
				$r='User Groups Data Edit View';
			}else if($key=='users/group_edit'){
				$r='User Groups Data Edit';
			}else if($key=='candidates'){
				$r='Candidates Data View';
			}else if($key=='candidates/create'){
				$r='Candidate Data Add View';
			}else if($key=='candidates/add'){
				$r='Candidate Data Add';
			}else if($key=='candidates/delete'){
				$r='Candidate Data Full Delete';
			}else if($key=='candidates/edit/(:num)'){
				$r='Candidate Data Edit';
			}else if($key=='candidates/edudelete'){
				$r='Candidate Educational Data Delete';
			}else if($key=='candidates/family_other_details_delete'){
				$r='Candidate Family Data Delete';
			}else if($key=='candidates/epf_nominee_delete'){
				$r='Candidate EPF Data Delete';
			}else if($key=='candidates/gratuity_nominee_delete'){
				$r='Candidate Gratuity Data Delete';
			}else if($key=='candidates/verifykyc'){
				$r='Candidate KYC Verification';
			}else if($key=='candidates/approvekyc'){
				$r='Candidate KYC Approve';
			}else if($key=='candidates/delete_files'){
				$r='Candidate KYC Files Delete';
			}else if($key=='candidates/generate_offer_letter'){
				$r='Candidate Generate Offer Letter';
			}else if($key=='update_my_data'){
				$r='Candidate Can Update Data';
			}else if($key=='upload_candidate_files'){
				$r='Candidate KYC Files Upload';
			}else if($key=='upload_my_files'){
				$r='Candidate Can Upload KYC Files';
			}else if($key=='candidates/upload_excel_to_db'){
				$r='Upload Candidate Data Using Excel';
			}else if($key=='delete_my_file'){
				$r='Candidate Can Delete Files';
			}
			else if($key=='dashboard'){
				$r='Dashboard';
			}else if($key=='settings'){
				$r='System Settings View';
			}else if($key=='settings/edit'){
				$r='System Settings Modification';
			}else if($key=='leave/changestatus'){
				$r='Change Applied Leaves Status';
			}else if($key=='leave/delete'){
				$r='Delete Applied Leaves';
			}else if($key=='leave/apply'){
				$r='Apply for Leaves';
			}else if($key=='attendance/leaves'){
				$r='All Leaves Data';
			}else if($key=='attendance/leaves/categories'){
				$r='All Leaves Categories';
			}else if($key=='leave/category_add'){
				$r='Add/Edit Leaves Category';
			}else if($key=='leave/category_delete'){
				$r='Delete Leaves Category';
			}

			//Employees
			else if($key=='employees'){
				$r='View All Employee\'s Data';
			}else if($key=='change_status'){
				$r='Change Employee\'s Status';
			}else if($key=='employees/add'){
				$r='Access Add Employee Form';
			}else if($key=='createnewemployee'){
				$r='Add Employee';
			}
			else if($key=='employees/update_salary_struct'){
				$r='Update Employee Salary Structure';
			}else if($key=='employees/update_loandata'){
				$r='Update Employee Loan Data';
			}else if($key=='employees/update_tdsdata'){
				$r='Update Employee TDS Data';
			}else if($key=='employees/update_othderdeductionsdata'){
				$r='Update Employee Other Deductions Data';
			}else if($key=='employees/search_employee_tds_data'){
				$r='View Employee TDS Data';
			}
			//Employees
			//Companies
			else if($key=='companies'){
				$r='View All Companies';
			}
			else if($key=='companies/save'){
				$r='Add Companies';
			}else if($key=='companies/delete'){
				$r='Delete Companies';
			}else if($key=='companies/changestatus'){
				$r='Change Companies Status';
			}else if($key=='companies/designations'){
				$r='View Company Designations';
			}else if($key=='companies/designations/add'){
				$r='Add Company Designations';
			}else if($key=='companies/designations_delete'){
				$r='Delete Company Designations';
			}else if($key=='companies/search_designations'){
				$r='Search Company Designations';
			}
			else if($key=='companies/departments'){
				$r='View Company Departments';
			}else if($key=='companies/departments/add'){
				$r='Add Company Departments';
			}else if($key=='companies/departments_delete'){
				$r='Delete Company Departments';
			}else if($key=='companies/search_departments'){
				$r='Search Company Departments';
			}

			else if($key=='companies/company_emp_grade_search'){
				$r='Search Company Salary Grades';
			}else if($key=='companies/company_emp_grade_add'){
				$r='Add Company Salary Grades';
			}else if($key=='companies/company_emp_grade_delete'){
				$r='Delete Company Salary Grades';
			}else if($key=='companies/company_emp_garde_sal_struct_add'){
				$r='Search Company Salary Grades Salary Structure';
			}

			else if($key=='companies/add_loan_master'){
				$r='Add Company Loan Data';
			}else if($key=='companies/search_loan_masters'){
				$r='Search Company Loan Data';
			}else if($key=='companies/delete_loan_masters'){
				$r='Delete Company Loan Data';
			}

			else if($key=='companies/company_taxes_search'){
				$r='Search Company P-Tax Data';
			}else if($key=='companies/add_tax'){
				$r='Add Tax Data';
			}

			else if($key=='companies/updatepfesic'){
				$r='Update Company PF-ESIC Data';
			}

			else if($key=='companies/search_invoices'){
				$r='View Company Invoices Data';
			}else if($key=='companies/generate_invoices'){
				$r='Generate Company Invoices Data';
			}

			else if($key=='companies/salarygrades'){
				$r='View Salary Company Grades';
			}

			else if($key=='attendance/upload'){
				$r='Upload Attendance';
			}else if($key=='companies/generate_attendance_sheet'){
				$r='Generate Attendance Excel Formats';
			}

			else if($key=='companies/oltemplates'){
				$r='All Offerletter Templates';
			}else if($key=='companies/oltemplates/add'){
				$r='Add Offerletter Template';
			}else if($key=='companies/oltemplates/(:num)/(:num)'){
				$r='Update Offerletter Template';
			}else if($key=='companies/deleteoletter'){
				$r='Delete Offerletter Template';
			}else if($key='settings/upload_files'){
				$r='Upload Files in Media Explorer';
			}
			//Problem
			
			
			else{
				$r=$key;
			}

			$route[$key]=$r;
		}

		//print_obj($route);die;

		return $route;
	}
	
}




if(!function_exists('menu_active')){

	function menu_active(){

		$routes=CI()->uri->uri_string();

		if($routes=='candidates/create' || $routes=='candidates'){
			$a='if';
		}else{
			$a='else';
		}

		return $a;
	}
}


if(!function_exists('get_percentage')){

	function get_percentage($m,$v){
		return (($m*$v)/100);
	}
}

// my functions

function encrypt_it($str_to_enc){
	$enc_string = $str_to_enc;

	// Store the cipher method
	$ciphering = "AES-256-CBC";
	// Use OpenSSl Encryption method
	$iv_length = openssl_cipher_iv_length($ciphering);
	$options = 0;
	// Non-NULL Initialization Vector for encryption
	$encryption_iv = '5104459805871797';
	//$encryption_iv = openssl_random_pseudo_bytes($iv_length);
	// Store the encryption key
	$encryption_key = "KaPdSgVkYp3s5v8y/B?E(H+MbQeThWmZ";

	$encrypted_str = openssl_encrypt($enc_string, $ciphering,
		$encryption_key, $options, $encryption_iv);
	//echo "Encrypted String: " . $encrypted_str . "\n";
	return($encrypted_str);

}

function decrypt_it($str_to_dec){
	// Store the cipher method
	$ciphering = "AES-256-CBC";

	// Use OpenSSl Encryption method
	$iv_length = openssl_cipher_iv_length($ciphering);
	$options = 0;
	// Non-NULL Initialization Vector for decryption
	$decryption_iv = '5104459805871797';
	//$decryption_iv = openssl_random_pseudo_bytes($iv_length);

	// Store the decryption key
	$decryption_key = "KaPdSgVkYp3s5v8y/B?E(H+MbQeThWmZ";
	$decrypted_str=openssl_decrypt ($str_to_dec, $ciphering,
		$decryption_key, $options, $decryption_iv);
	return($decrypted_str);
	//echo "Decrypted String: " . $decryption;

}

function random_strings($length_of_string) {

	// String of all alphanumeric character
	$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

	// Shufle the $str_result and returns substring
	// of specified length
	return substr( str_shuffle( $str_result ),
		0, $length_of_string );
}

function random_numbs($length_of_string) {

	// String of all alphanumeric character
	$str_result = '0123456789';

	// Shufle the $str_result and returns substring
	// of specified length
	return substr( str_shuffle( $str_result ),
		0, $length_of_string );
}

if(!function_exists('isUrlExists')){
    function isUrlExists($tblName, $col_name, $urlSlug){
        if(!empty($tblName) && !empty($urlSlug)){
            $ci = & get_instance();
            $ci->db->from($tblName);
            $ci->db->where($col_name,$urlSlug);
            $rowNum = $ci->db->count_all_results();
            return ($rowNum>0)?true:false;
        }else{
            return true;
        }
    }
}

if(!function_exists('getiplocation')){
    function getiplocation($ip){
        if(!empty($ip)){
        	//get location
			//$ip = '185.220.101.136';
		    //$location = file_get_contents('http://freegeoip.net/json/'.$_SERVER['REMOTE_ADDR']);
		    $location = file_get_contents('http://ip-api.com/json/'.$ip);
		    //you can also use ipinfo.io or any other ip location provider API
		    $data_loc = json_decode($location);
		    return $data_loc;
        }else{
            //return true;
        }
    }
}


/* End of file utility_helper.php */
/* Location: ./helpers/utility_helper.php */
