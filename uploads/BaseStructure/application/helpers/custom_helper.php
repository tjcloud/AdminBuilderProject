<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');



// random number
if (!function_exists('rand_number')) {

    function rand_number() {
        return rand(0, 999999999);
    }

}



if (!function_exists('rand_string')) {

    function rand_string() {
        // ci method
        return random_string('unique');
    }

}


if (!function_exists('getNewFileName')) {

    function getNewFileName($origFileName) {
        $randnumber = randomString(10);
        $fileExt = array_pop(explode(".", $origFileName));
        return $randnumber . time() . "." . $fileExt;
    }

}

if (!function_exists('doctorFileName')) {

    function doctorFileName($origFileName, $name) {
        $fileExt = array_pop(explode(".", $origFileName));
        return $name. "." . $fileExt;
    }

}

if (!function_exists('testhelper')) {

    function testhelper() {
        $fileExt = 'test helper';
        return $fileExt;
    }

}
if (!function_exists('randomString')) {

    function randomString($length) {
        return $randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

}

if (!function_exists('capital')) {

 function capital($str) {
        // ci method
        return ucfirst($str);
    }

}


if (!function_exists('capitalWords')) {

    function capitalWords($str) {
        // ci method
        return ucwords($str);
    }

}

if (!function_exists('ismobile')) {
		function isMobile() {
		return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	}
}

// For Blog Section


/**
 * Check language
 * 
 * This function will return true if provided language/parameter is the same as currently active language
 */
function is_lang($lang = '') {
	$CI =& get_instance();

	if($lang == $CI->config->item('language_abbr')) {
		return true;
	}
}


/**
 * Homepage link/url
 * 
 * This function will return the homepage URL based on the currently active language
 * Usage: <?php echo homepage_link(); ?>
 */
function homepage_link() {
	$CI =& get_instance();
	// Active language
	$lang = $CI->config->item('language_abbr');
	
	// Checking if current language is set as default language
	$url = ($lang == $CI->config->item('default_language')) ? base_url().'/' : base_url().'/' . $lang;
	
	return $url;
}


/**
 * List available languages
 * 
 * Parameters
 * layout: type of output (li, img, a). By default, list is set to "a". Clean href links
 * title: If set to "code", language titles will be shorten to abbrevation (en, de, fr...)
 * 
 * Usage: <?php echo list_languages(array('layout' => 'li', 'title' => 'code')); ?>
 */
function list_languages($options = array()) {
	$CI =& get_instance();
	$default_language = $CI->config->item('default_language');
	$current_language = $CI->config->item('language_abbr');

	foreach ($CI->config->item('lang_desc') as $value=>$key) {
		// If language is the same as default language, don't show the abbrevation
		$url = ($value == $default_language) ? '' : $value;
		$title = (isset($options['title']) AND $options['title'] == 'code') ? $value : $key;

		// Set active class on active language
		$active = ($value == $current_language) ? ' active' : '';
		
		if (isset($options['layout']) AND $options['layout'] == 'option') { 
			if ($active) {
				$active = ' selected="selected"';
			}
			echo '<option value="'.base_url() .'/'. $url.'"'.@$active.'class="'.$value.'">'.$title.'</option>';
		} elseif (isset($options['layout']) AND $options['layout'] == 'li') {
			echo '<li class="'.$value.$active.'"><a href="'.base_url() .'/'. $url.'" title="'.$key.'">'.$title.'</a></li>';
		} elseif (isset($options['layout']) AND $options['layout'] == 'img') {
			$image_path = (isset($options['image_path'])) ? $options['image_path'] : 'images';
			echo '<a href="'.base_url() .'/'. $url.'" class="'.$value.$active.'" title="'.$key.'"><img src="'.base_url() .'/'. $image_path . "/" . $value.'.png" alt="'.$key.'" /></a>';
		} else {
			echo '<a href="'.base_url() .'/'. $url.'" class="'.$value.$active.'" title="'.$key.'">'.$title.'</a>';
		}
	}
}

/**
 * Breadcrumbs
 * 
 * Parameters
 * delimiter: can be whatever. By default it is set to arrow ( &gt; )
 * title: title to show
 * 
 * Usage: <?php echo breadcrumbs(array('delimiter' => ' > ', 'title' => $bc)); ?>
 */
function breadcrumbs($options = array()) {
	$CI =& get_instance();
	$output = '';
	$current_language = $CI->config->item('language_abbr');
	$options['delimiter'] = (isset($options['delimiter'])) ? $options['delimiter'] : ' &gt; ';

	// Homepage title
	$homepage_title = $CI->db->get_where(POSTS_DB_TABLE, array('slug' => '/', 'lang' => $current_language, 'module' => 1))->row()->title;	
	$output .= ($CI->uri->total_segments() > 0) ? '<a href="'.homepage_link().'">'.$homepage_title.'</a>' . $options['delimiter'] : $homepage_title;

	// Append current page title
	if(isset($options['title']) AND $options['title']) {
		$total_items = sizeof($options['title'])-1;
		for($i=0; $i<=$total_items; $i++) {
			$output .= $options['title'][$i];
			if($i != $total_items) {
				$output .= $options['delimiter'];
			}
		}
	}

	return $output;
}

/**
 * Returns configured media path
 */
function media_path($options = array()) {
	$CI =& get_instance();

	$options = array_merge(array(
		'base_url' => TRUE,
		'uploads_folder' => $CI->config->item('upload_folder'),
		'file' => '',
	), $options);

	$base_url = ( $options['base_url'] == TRUE ) ? $CI->config->item('base_url').'/' : $options['base_url'];

	return $base_url . $options['uploads_folder'] . $options['file'];
}

/**
 * Takes you to current page or post editing
 */
function edit_post_link($options = array()) {
	$CI =& get_instance();

	//if($CI->session->userdata('logged_in') AND $CI->session->userdata('group_id') >= 3) {
		$label = ( isset($options['label']) AND $options['label'] ) ? $options['label'] : 'Edit';
		if( isset($options['module']) AND $options['module'] == PUBLISH_M ) {
			$url = 'blog/posts';
		} else if( isset($options['module']) AND $options['module'] == CATEGORIES_M ) {
			$url = 'blog/category';
		} else {
			$url = 'pages';
		}
		return '<p class="edit-post-link"><a target="_blank" href="'.$CI->config->item('base_url') .'/'. ADMIN_URL . '/' . $url . '/edit/' . $options['id'].'">'.$label.'</a></p>';
   //}
}

// function for display sort descriptin
function shortDescription($fullDescription=""){
		$shortDescription = "";
		$fullDescription =  trim(strip_tags($fullDescription));

		if ($fullDescription) {
			$initialCount = 400;
			if (strlen($fullDescription) > $initialCount) {
				$shortDescription = substr($fullDescription,0,$initialCount)."......";
			}
			else {
				return $fullDescription;
			}
		}

		return $shortDescription;
}//


/**
 * Blocks
 */
function block($identifier = '', $default = FALSE) {
	$CI =& get_instance();
	$lang = ($default == FALSE) ? $CI->config->item('language_abbr') : $CI->config->item('default_language');
	$slug = ($CI->uri->segment(1) == '') ? "/" : $CI->uri->segment(1);
	$data = $CI->db->get_where(POST_META_DB_TABLE, array('identifier' => $identifier, 'lang' => $lang, 'module' => 0));

	if ($data->num_rows() > 0) {
		return $data->row()->content;
	}
}

/**
 * Custom Blocks / Post Meta
 */
function custom_block($identifier = '', $post_id) {
	$CI =& get_instance();
	$lang = $CI->config->item('language_abbr');
	$data = $CI->db->get_where(POST_META_DB_TABLE, array('identifier' => $identifier, 'lang' => $lang, 'post_id' => $post_id));

	if ($data->num_rows() > 0) {
		return $data->row()->content;
	}
}


if (!function_exists('GetuserType')) {

 function GetuserType() {
	$ci=& get_instance();
	$ci->load->database(); 
	
	$sql = "SELECT * 
	FROM sales_user_type"; 
	$query = $ci->db->query($sql);
	$row = $query->result();
	return $row;		

    }

}

if (!function_exists('GetNameById')) {

 function GetNameById($id='') {
	$ci=& get_instance();
	$ci->load->database(); 
	
	$sql = "SELECT first_name,last_name 
	FROM sales_user_master where user_id='$id'"; 
	$query = $ci->db->query($sql);
	$row = $query->result();
	if(isset($row[0]->first_name) && isset($row[0]->last_name)){
		$name=$row[0]->first_name.' '.$row[0]->last_name;
		return $name;		
	}else{
		return "NA";
		}
    }

}
if (!function_exists('StatusArray')) {

 function StatusArray() {
   $status=array('0'=>'Inactive','1'=>'Active');
	return $status;		

    }

}
if (!function_exists('CompanyLogo')) {

 function CompanyLogo($id='') {
	$ci=& get_instance();
	$ci->load->database(); 
	
	$sql = "SELECT company_logo
	FROM sales_company_master where company_id='$id'"; 
	$query = $ci->db->query($sql);
	$row = $query->result();
	$logo=explode('.',$row[0]->company_logo);
	return '<img src="'.base_url().'../media/thumb/'.$logo[0].'_thumb.'.$logo[1].'">';		

    }

}

if (!function_exists('CompanyColor')) {

 function CompanyColor($id='') {
	$ci=& get_instance();
	$ci->load->database(); 
	
	$sql = "SELECT color
	FROM sales_company_master where company_id='$id'"; 
	$query = $ci->db->query($sql);
	$row = $query->result();
	if(isset($row[0]->color)){
	$color=$row[0]->color;
	return $color;	
     }
	
   return false;
    }

}
if (!function_exists('Companyinfo')) {

 function Companyinfo($id='',$data='') {
	$ci=& get_instance();
	$ci->load->database(); 
	
	$sql = "SELECT $data
	FROM sales_company_master where company_id='$id'"; 
	$query = $ci->db->query($sql);
	$row = $query->result();	
     return $row;
    }

}

if (!function_exists('GetAllPermission')) {

 function GetAllPermission() {
	$ci=& get_instance();
	$ci->load->database(); 
	
	$sql = "SELECT * FROM sales_user_permission order by permission_id ASC"; 
	$query = $ci->db->query($sql);
	$row = $query->result();
	return $row;		

    }

}
if (!function_exists('GetUserPermission')) {

 function GetUserPermission($user_id='') {
	$ci=& get_instance();
	$ci->load->database(); 
	
	$sql = "SELECT * 
	FROM rel_user_permisstion where user_id='$user_id'"; 
	$query = $ci->db->query($sql);
	$row = $query->result();
	return $row;		

    }

}
if (!function_exists('GetCompanyUser')) {

 function GetCompanyUser($company_id='',$user_id='') {
	$ci=& get_instance();
	$ci->load->database(); 
	
	$sql = "SELECT * 
	FROM sales_user_master where company_id='$company_id' AND user_id!='$user_id' "; 

	$query = $ci->db->query($sql);
	$row = $query->result();
	return $row;		

    }

}
if (!function_exists('GetAccounting')) {

 function GetAccounting($user_id='') {
	$ci=& get_instance();
	$ci->load->database(); 
	
	$sql = "SELECT * 
	FROM sales_user_master where  user_id ='$user_id' and find_in_set('5',rep_type)"; 
	//print_r($sql);die;
	$query = $ci->db->query($sql);
	$row = $query->result();
	return $row;		

    }

}

if (!function_exists('GetInventory')) {

 function GetInventory($user_id='') {
	$ci=& get_instance();
	$ci->load->database(); 
	
	$sql = "SELECT * 
	FROM sales_user_master where  user_id ='$user_id' and find_in_set('6',rep_type)"; 
	//print_r($sql);die;
	$query = $ci->db->query($sql);
	$row = $query->result();
	return $row;		

    }

}

if (!function_exists('CountcompanyUser')) {

 function CountcompanyUser($user_id='') {
	$ci=& get_instance();
	$ci->load->database(); 
	
	$sql = "SELECT count(*) as count FROM `sales_user_master` WHERE company_id = '$user_id'"; 
	//print_r($sql);die;
	$query = $ci->db->query($sql);
	$row = $query->result();
	return $row;		

    }

}
if (!function_exists('totalCompanyUsers')) {

 function totalCompanyUsers($user_id='') {
	$ci=& get_instance();
	$ci->load->database(); 
	
	$sql = "SELECT * FROM `sales_company_master` WHERE company_id= '$user_id'"; 
	//print_r($sql);die;
	$query = $ci->db->query($sql);
	$row = $query->result();
	return $row;		

    }

}
if (!function_exists('dispatcherProfile')) {

 function dispatcherProfile($id='') {
	$ci=& get_instance();
	$ci->load->database(); 
	
	$sql = "SELECT profile_pic
	FROM sales_user_master where user_id='$id'"; 
	$query = $ci->db->query($sql);
	$row = $query->result();
	if($row[0]->profile_pic){
	$logo=explode('.',$row[0]->profile_pic);
	return'<img src="'.base_url().'../media/users/thumb/'.$logo[0].'_thumb.'.$logo[1].'" style="max-width: 60px; width: 100%; ">';
	// return '<img src="'.base_url().'../media/thumb/'.$logo[0].'_thumb.'.$logo[1].'">';
	}else{
		return '<img src="'.base_url().'../media/user_default.png" style="max-width: 60px; width: 100%; ">';
	}
			

    }

}