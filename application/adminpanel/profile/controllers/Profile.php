<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH . 'libraries/ImplementJWT.php');

class Profile extends MX_Controller {

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
		$this->load->model('profile_model');
		$this->objOfJwt = new ImplementJwt;
    }
	
	public function edit()
	{
		$admin_id = $this->session->userdata['login_id'];
		 
		$data['admin_details'] = $this->profile_model->getAdminDetails($admin_id);
		$this->template->build('edit',$data);      
	}
	
	public function ajaxEditAdminEmail()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('email','Email','required');
		
		if($this->form_validation->run() == FALSE)
		{
			$status = false;
			$message = validation_errors();
			$this->common_model->call_Function($status,$message);
		} 
		else 
		{
			$updateData['id'] = $this->session->userdata['login_id'];
			$updateData['email'] = ($this->input->post('email')) ? $this->input->post('email') : "";
			$updateData['updated_at'] = date("Y-m-d H:i:s");
			
			$res = $this->profile_model->editProfile($updateData);	
			
			if(is_array($res) && $res['status'] == true)
			{					
				$status = true;
				$message = "Profile email updated successfully";
				$redirect = base_url().'profile/edit';
				$this->common_model->call_Function_redirect($status,$message,$redirect);		
			}
			else
			{
				$status = false;
				$message = "There is something wrong!";
				$this->common_model->call_Function($status,$message);
			}
		}    
	}
	
	public function ajaxChangePassword()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('password','Password','required');
		
		if($this->form_validation->run() == FALSE)
		{
			$status = false;
			$message = validation_errors();
			$this->common_model->call_Function($status,$message);
		} 
		else 
		{
			$updateData['id'] = $this->session->userdata['login_id'];
			$updateData['password'] = ($this->input->post('password')) ? $this->objOfJwt->GenerateToken($this->input->post('password')) : "";
			$updateData['updated_at'] = date("Y-m-d H:i:s");
			
			$res = $this->profile_model->editPassword($updateData);	
			
			if(is_array($res) && $res['status'] == true)
			{					
				$status = true;
				$message = "Profile password updated successfully";
				$redirect = base_url().'profile/edit';
				$this->common_model->call_Function_redirect($status,$message,$redirect);		
			}
			else
			{
				$status = false;
				$message = "There is something wrong!";
				$this->common_model->call_Function($status,$message);
			}
		}    
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */