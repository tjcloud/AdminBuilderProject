<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH . 'libraries/ImplementJWT.php');

class project_management extends MX_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
         * 
	 */
    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
		
		if (!isset($this->session->userdata['login_id'])){
			redirect(base_url());
		}
		
		$this->load->model('common_model');
		$this->load->model('project_management_model');
		$this->load->helper('directory');
		$this->load->helper('file');
    }
	
	public function index()
	{
		$this->template->build('project_view');      
	}
	public function ajaxAddProject()
	{
		$active_group = "";
		$query_builder = "";
		$project_name = $this->input->post('project_name');
		$database_name = $this->input->post('database_name');
		$url = $this->input->post('url');

		$project_exists = $this->project_management_model->PorjectExists($project_name);
		if(empty($project_exists))
		{
			if (!is_dir('uploads/Projects/'.$project_name)) 
			{

				$status = $this->project_management_model->InsertProjectData($project_name,$database_name,$url);

			    mkdir('./uploads/Projects/' . $project_name, 0777, TRUE);
			    $srcdir = './uploads/BaseStructure/';
				$dstdir = './uploads/Projects/'.$project_name.'/';
				$last_insert_id = $this->common_model->directory_copy($srcdir,$dstdir);

				
				$database = '<?php 
				defined("BASEPATH") OR exit("No direct script access allowed");
				$active_group = "default";
				$query_builder = TRUE;
				$db["default"] = array("dsn" => "",
					"hostname" => "localhost",
					"username" => "root",
					"password" => "",
					"database" => "'.$database_name.'",
					"dbdriver" => "mysqli",
					"dbprefix" => "",
					"pconnect" => FALSE,
					"db_debug" => (ENVIRONMENT !== "production"),
					"cache_on" => FALSE,
					"cachedir" => "",
					"char_set" => "utf8",
					"dbcollat" => "utf8_general_ci",
					"swap_pre" => "",
					"encrypt" => FALSE,
					"compress" => FALSE,
					"stricton" => FALSE,
					"failover" => array(),
					"save_queries" => TRUE);';

				$config = '<?php defined("BASEPATH") OR exit("No direct script access allowed");
				$config["base_url"] = \''.$url.'\';
				$config["index_page"] = "index.php";
				$config["uri_protocol"]	= "REQUEST_URI";
				$config["url_suffix"] = "";
				$config["language"]	= "english";
				$config["charset"] = "UTF-8";
				$config["enable_hooks"] = FALSE;
				$config["subclass_prefix"] = "MY_";
				$config["composer_autoload"] = FALSE;
				$config["permitted_uri_chars"] = "a-z 0-9~%.:_\-";
				$config["enable_query_strings"] = FALSE;
				$config["controller_trigger"] = "c";
				$config["function_trigger"] = "m";
				$config["directory_trigger"] = "d";
				$config["allow_get_array"] = TRUE;
				$config["log_threshold"] = 0;
				$config["log_path"] = "";
				$config["log_file_extension"] = "";
				$config["log_file_permissions"] = 0644;
				$config["log_date_format"] = "Y-m-d H:i:s";
				$config["error_views_path"] = "";
				$config["cache_path"] = "";
				$config["cache_query_string"] = FALSE;
				$config["encryption_key"] = "";
				$config["sess_driver"] = "files";
				$config["sess_cookie_name"] = "ci_session";
				$config["sess_expiration"] = 7200;
				$config["sess_save_path"] = NULL;
				$config["sess_match_ip"] = FALSE;
				$config["sess_time_to_update"] = 300;
				$config["sess_regenerate_destroy"] = FALSE;
				$config["cookie_prefix"]	= "";
				$config["cookie_domain"]	= "";
				$config["cookie_path"]		= "/";
				$config["cookie_secure"]	= FALSE;
				$config["cookie_httponly"] 	= FALSE;
				$config["standardize_newlines"] = FALSE;
				$config["global_xss_filtering"] = FALSE;
				$config["csrf_protection"] = FALSE;
				$config["csrf_token_name"] = "csrf_test_name";
				$config["csrf_cookie_name"] = "csrf_cookie_name";
				$config["csrf_expire"] = 7200;
				$config["csrf_regenerate"] = TRUE;
				$config["csrf_exclude_uris"] = array();
				$config["compress_output"] = FALSE;
				$config["time_reference"] = "local";
				$config["rewrite_short_tags"] = FALSE;
				$config["proxy_ips"] = "";';

				$sqlfile = 'CREATE DATABASE '.'IF NOT EXISTS '.$database_name;

				$databasepath = 'uploads/Projects/'.$project_name.'/application/config/database.php';
				$configpath = 'uploads/Projects/'.$project_name.'/application/config/config.php';
				$sqlpath = 'uploads/Projects/'.$project_name.'/'.$database_name.'.sql';

				$databasefp=fopen($databasepath,'w');
				$configfp=fopen($configpath,'w');
				$sqlfp=fopen($sqlpath,'w');

				fwrite($databasefp,$database);
				fwrite($configfp,$config);
				fwrite($sqlfp,$sqlfile);

				fclose($databasefp);
				fclose($configfp);
				fclose($sqlfp);

				if($status)
				{
					$this->session->set_userdata('project_id',$status);
					$message['status'] = "true";
			    	$message['message'] = "Congratulation! Project Created Successfully";
			    	$message['redirect'] = base_url()."main_management";
				}
				else
				{
					$message['status'] = "false";
			    	$message['message'] = "Sorry! Something went wrong";
				}
			    $this->call_Function($message);
			}
			else
			{
				$message['status'] = "false";
			    $message['message'] = "Sorry! Project ".$project_name." Is Already Exists";
			    $this->call_Function($message);	
			}
		}
		else
		{
			$message['status'] = "false";
		    $message['message'] = "Sorry! Project ".$project_name." Is Already Exists";
		    $this->call_Function($message);
		}
	}
	function call_Function($data)
	{
		header("Content-type:text/json");
		echo json_encode($data);
		exit();
	}
}