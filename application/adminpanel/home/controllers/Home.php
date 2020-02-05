<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH . 'libraries/ImplementJWT.php');

class Home extends MX_Controller {

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
		
		if (isset($this->session->userdata['login_id'])){
			redirect(base_url().'dashboard');
		}
		
		$this->load->model('Home_model');
		$this->objOfJwt = new ImplementJwt;
    }
	
	public function index()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('email', 'Email', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		
		if($this->form_validation->run() == FALSE)
		{
			$error['error'] = validation_errors();
			$this->load->view('index',$error);
		} 
		else 
		{	
			$logindata['email'] = $this->input->post('email');
			//load library for encryption
			$logindata['password'] = $this->objOfJwt->GenerateToken($this->input->post('password'));
			
			$isLoggedIn = $this->Home_model->userLogin($logindata);
			
			if($isLoggedIn == "WE"){
				$data['status'] = false;
				$data['message'] = 'Sorry, provided email does not exists in system.';
				
				header('Content-Type:text/json');
				echo json_encode($data);
				exit;
			} elseif($isLoggedIn == "WP"){
				$data['status'] = false;
				$data['message'] = 'Sorry, Provided password is wrong.';
				
				header('Content-Type:text/json');
				echo json_encode($data);
				exit;
			} elseif($isLoggedIn == "NA"){
				$data['status'] = false;
				$data['message'] = 'Sorry, Your account has been not activate, Please active your account.';
				
				header('Content-Type:text/json');
				echo json_encode($data);
				exit;
			} elseif($isLoggedIn == "BL"){
				$data['status'] = false;
				$data['message'] = 'Sorry, Your account has been blocked by us, Please contact admin to active your account.';
				
				header('Content-Type:text/json');
				echo json_encode($data);
				exit;
			} elseif($isLoggedIn == "success"){
				$data['status'] = true;
				$data['redirect'] = base_url()."dashboard";
				$data['message'] = 'Congratulation, Login successfully, Please wait.';
				
				header('Content-Type:text/json');
				echo json_encode($data);
				exit;
			}elseif($isLoggedIn == FALSE){
				$data['status'] = false;
				$data['message'] = 'There is an error, Please try again.';
				
				header('Content-Type:text/json');
				echo json_encode($data);
				exit;
			}	
		}
	}
	
	public function forgot_password()
	{
		$this->template->build('forgot_password');      
	}
	
	public function randomPassword() 
	{
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < 8; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	} 
	
	public function ajaxForogotPassword()
	{
	    $this->load->library('form_validation');
		
		$this->form_validation->set_rules('forgot_email', 'Email-Address', 'required');
		
		if($this->form_validation->run() == FALSE)
		{
			$data['status'] = false;
			$data['message'] = validation_errors();
			header('Content-Type:text/json');
			echo json_encode($data);	
		} 
		else 
		{ 
			$userdata['email'] = $this->input->post('forgot_email');
			$password = $this->randomPassword();
			
			$userdata['password'] = $this->objOfJwt->GenerateToken($password);
			
			$isSuccess = $this->Home_model->forgotPassword($userdata);
			
			if(is_string($isSuccess) && $isSuccess == "WE"){
				$data['status'] = false;
				$data['message'] = 'Sorry, provided email does not exists in system.';
				
				header('Content-Type:text/json');
				echo json_encode($data);
				exit;
			} elseif(is_string($isSuccess) && $isSuccess == "NA"){
				$data['status'] = false;
				$data['message'] = 'Provided email is not verified, Please verify your email and try again.';
				
				header('Content-Type:text/json');
				echo json_encode($data);
				exit;
			} elseif(is_string($isSuccess) && $isSuccess == "BL"){
				$data['status'] = false;
				$data['message'] = 'Sorry, Your account has been blocked by administrator, Please contact admin to active your account.';
				
				header('Content-Type:text/json');
				echo json_encode($data);
				exit;
			} elseif(is_array($isSuccess)){
				
				$body = '';
        
				$body .='
					<table style="border-collapse:collapse;width:700px;margin:auto;background:#fff;padding:0;border:0;max-width:100%;font-family:Arial,sans-serif">
						<thead style="background:#313131;color:#fff">
							<tr>
								<td style="padding:10px 15px"><a href="'.SITE_URL.'" title="" target="_blank"><img src="'.base_url().ADMIN_THEME.'assets/images/rocks-logo.png" alt="" style="width: 25%;"></a></td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="2" style="padding:55px">
									<p style="color:#000;font-size:18px;font-family:sans-serif;margin-top:0;margin-bottom:10px">
										<strong style="color:#b61b1b;font-size:28px;font-family:sans-serif;display:inline-block;margin-bottom:10px">Hi <span style="color:#000">'.$isSuccess['name'].',</span></strong>
									</p>
									
									<p style="color:#000;font-size:16px;font-family:sans-serif;line-height:22px">Please find your new password as below.</p>
									
									<p style="color:#000;font-size:16px;font-family:sans-serif;line-height:22px">
									Email : '.$isSuccess['email'].'<br>
									Password : '.$password.'<br></p>
									
									<p style="color:#000;font-size:16px;font-family:sans-serif;line-height:22px">Feel free to contact us know if you are facing any issue with login.</p>
									
									<p style="color:#000;font-size:18px;line-height:22px">Thanks.</p>
									
								</td>
							</tr>
						</tbody>
					</table>
				';
				
				$to_email = $isSuccess['email'];
				
				$config['smtp_host'] = 'localhost';
				$config['smtp_user'] = SMTP_AUTH_USER;
				$config['smtp_pass'] = SMTP_USER_PASS;
				$config['smtp_port'] = 25;
				$config['charset']  = 'iso-8859-1';
				$config['wordwrap'] = TRUE;
				$config['mailtype'] = 'html';
				$config['newline']  = "\r\n";
				
				$this->load->library('email', $config);
				$this->email->from(FROM_EMAIL);
				$this->email->to($to_email);
				$this->email->subject("Mobile Cover - Forgot Password.");
				$this->email->message($body);
				
				if ($this->email->send()) {
					$data['status'] = true;
					$data['message'] = 'Please check your inbox we have sent you new password.';
					$data['redirect'] = base_url();
					
					header('Content-Type:text/json');
					echo json_encode($data);
					exit;
				}
				else
				{
					$data['status'] = false;
					$data['message'] = 'There is an error, Please try again.';
					
					header('Content-Type:text/json');
					echo json_encode($data);
					exit;
				}
				
				
			}else{
				$data['status'] = false;
				$data['message'] = 'There is an error, Please try again.';
				
				header('Content-Type:text/json');
				echo json_encode($data);
				exit;
			}
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */