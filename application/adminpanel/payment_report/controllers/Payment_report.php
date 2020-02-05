<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'libraries/ImplementJWT.php');
class Payment_report extends MX_Controller {

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
		$this->load->model('payment_report_model');
		$this->objOfJwt = new ImplementJwt;
    }
	
	public function index()
	{
		$WHERE = " 1=1 AND (pending_issue = '' OR pending_issue IS NULL)";
		
		if(!empty($this->input->post())){
			if($this->input->post('company_name') != ""){
				$WHERE .= " AND contacts.trade_company = '".$this->input->post('company_name')."'";
			}
			if($this->input->post('segment') != ""){
				$WHERE .= " AND contacts.payment_for_segment = '".$this->input->post('segment')."'";
			}
			if($this->input->post('date_range') != ""){
				$Rdate = explode("to",$this->input->post('date_range'));
				$sdate = date("Y-m-d",strtotime($Rdate[0]));
				$edate = date("Y-m-d",strtotime($Rdate[1]));
				
				if($sdate == $edate){
					$WHERE .= " AND Date(payment_logs.created_date) = '".$sdate."'";
				}else{
					$WHERE .= " AND Date(payment_logs.created_date) between '".$sdate."' AND '".$edate."'";	
				}
			}
		}
		
		$data['company_name'] = ($this->input->post('company_name') != "") ? $this->input->post('company_name') : "";
		$data['segment'] = ($this->input->post('segment') != "") ? $this->input->post('segment') : "";
		$data['date_range'] = ($this->input->post('date_range') != "") ? $this->input->post('date_range') : "";
		
		$data['payments_log'] = $this->payment_report_model->getPaymentLogs($WHERE);
		$this->template->build('index',$data);      
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */