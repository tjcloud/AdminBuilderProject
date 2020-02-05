<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH . 'libraries/ImplementJWT.php');

class Dashboard extends MX_Controller {

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
		$this->load->model('dashboard_model');
		$this->load->helper('directory');
		$this->load->helper('file');
    }
	
	public function index()
	{
		$WHERE = " AND payment_type = '1' AND is_deleted='0' AND (pending_issue = '' OR pending_issue IS NULL)";
		
		if(!empty($this->input->post())){
			if($this->input->post('company_name') != ""){
				$WHERE .= " AND trade_company = '".$this->input->post('company_name')."'";
			}
			if($this->input->post('segment') != ""){
				$WHERE .= " AND FIND_IN_SET('".$this->input->post('segment')."',segments)";
			}
			
			$plan_expiry = $this->dashboard_model->getPlanExpiryRecordsFilter($WHERE);
		
			$data['company_name'] = ($this->input->post('company_name') != "") ? $this->input->post('company_name') : "";
			$data['segment'] = ($this->input->post('segment') != "") ? $this->input->post('segment') : "";
			$data['plan_expiry'] = $plan_expiry;
		} else {
			$data['company_name'] = ($this->input->post('company_name') != "") ? $this->input->post('company_name') : "";
			$data['segment'] = ($this->input->post('segment') != "") ? $this->input->post('segment') : "";
			$data['plan_expiry'] = $this->dashboard_model->getPlanExpiryRecords();	
		}
		
		$WHERE_1 = "(poa_status = '0' OR poa_status = '1') AND trade_company='1' AND payment_type = '1' AND is_deleted='0' AND (pending_issue = '' OR pending_issue IS NULL)";
		$WHERE_2 = "(poa_status = '0' OR poa_status = '1') AND trade_company='2' AND payment_type = '1' AND is_deleted='0' AND (pending_issue = '' OR pending_issue IS NULL)";
		
		$data['no_poa_angel'] = $this->common_model->get_sql_select_data('contacts',$WHERE_1,'','','id desc');
		$data['no_poa_upstock'] = $this->common_model->get_sql_select_data('contacts',$WHERE_2,'','','id desc');
		
		$WHERE_3 = "trade_company = '1' AND (pending_issue = '' OR pending_issue IS NULL)";
		
		$CONTACTS_Angels = array();
		$CONTACT_DATA_ANGEL = $this->common_model->get_sql_select_data('contacts',$WHERE_3,'','','id desc');
		
		foreach($CONTACT_DATA_ANGEL as $CONTACT_D){
			
			$Temp_Contacts['contact_name'] = $CONTACT_D['contact_name'];
			$Temp_Contacts['contact_no'] = $CONTACT_D['contact_no'];
			$Temp_Contacts['segments'] = $CONTACT_D['segments'];
			$Temp_Contacts['poa_text_status'] = $CONTACT_D['poa_text_status'];
			$Temp_Contacts['id'] = $CONTACT_D['id'];
			$Temp_Contacts['whatsapp_no'] = $CONTACT_D['whatsapp_no'];
			
			$Per = $this->getPercentageTrade($CONTACT_D['id']);
			
			$Temp_Contacts['PaidFree_Percentage'] = $Per['PaidFree_Percentage'];
			$Temp_Contacts['NotActive_Percentage'] = $Per['NotActive_Percentage'];
			
			$Avg_Per = 0;
			$Total_Per = $Per['PaidFree_Percentage'] + $Per['NotActive_Percentage'];
			if($Total_Per != 0){
				$Avg_Per = round($Total_Per / 2);
			}
			$Temp_Contacts['Avg_Percentage'] = $Avg_Per;
			
			$Snooze_fromdate = $CONTACT_D['snooze_from'];
			$Snooze_todate = $CONTACT_D['snooze_to'];
			
			if($Snooze_fromdate != "" && $Snooze_todate != ""){
				$current_date = date("Y-m-d");
				if($current_date > $Snooze_todate) {
					
					$DAYS = 0;
					$datediff = strtotime($Snooze_todate) - strtotime($current_date);
					$DAYS = round($datediff / (60 * 60 * 24));		
					$Temp_Contacts['snooze_days'] = $DAYS.' day';
					
					if($Temp_Contacts['PaidFree_Percentage'] <= 30){	
						array_push($CONTACTS_Angels,$Temp_Contacts);			
					}		
				}
			} else {
				$Temp_Contacts['snooze_days'] = 'Not yet';
				if($Temp_Contacts['PaidFree_Percentage'] <= 30){	
					array_push($CONTACTS_Angels,$Temp_Contacts);			
				}	
			}
		}
		
		$data['not_trader_angle'] = $CONTACTS_Angels;
		
		$WHERE_4 = "trade_company = '2' AND (pending_issue = '' OR pending_issue IS NULL)";
		
		$CONTACTS_UPSTOCK = array();
		$CONTACT_DATA_UPSTOCK = $this->common_model->get_sql_select_data('contacts',$WHERE_4,'','','id desc');
		
		foreach($CONTACT_DATA_UPSTOCK as $CONTACT_DA){
			
			$Temp_Contacts['contact_name'] = $CONTACT_DA['contact_name'];
			$Temp_Contacts['contact_no'] = $CONTACT_DA['contact_no'];
			$Temp_Contacts['segments'] = $CONTACT_DA['segments'];
			$Temp_Contacts['poa_text_status'] = $CONTACT_DA['poa_text_status'];
			$Temp_Contacts['id'] = $CONTACT_DA['id'];
			$Temp_Contacts['whatsapp_no'] = $CONTACT_DA['whatsapp_no'];
			
			$Per = $this->getPercentageTrade($CONTACT_DA['id']);
			
			$Temp_Contacts['PaidFree_Percentage'] = $Per['PaidFree_Percentage'];
			$Temp_Contacts['NotActive_Percentage'] = $Per['NotActive_Percentage'];
			
			$Avg_Per = 0;
			$Total_Per = $Per['PaidFree_Percentage'] + $Per['NotActive_Percentage'];
			if($Total_Per != 0){
				$Avg_Per = round($Total_Per / 2);
			}
			$Temp_Contacts['Avg_Percentage'] = $Avg_Per;
			
			$Snooze_fromdate = $CONTACT_DA['snooze_from'];
			$Snooze_todate = $CONTACT_DA['snooze_to'];
			
			$DAYS = 0;
			
			if($Snooze_fromdate != "" && $Snooze_todate != ""){
				$current_date = date("Y-m-d");
				
				if($current_date > $Snooze_todate) {
					
					$datediff = strtotime($Snooze_todate) - strtotime($current_date);
					$DAYS = round($datediff / (60 * 60 * 24));		
					$Temp_Contacts['snooze_days'] = $DAYS.' day';
					
					if($Temp_Contacts['PaidFree_Percentage'] <= 30) {	
						array_push($CONTACTS_UPSTOCK,$Temp_Contacts);			
					} 		
				} 
			} else {
				if($Temp_Contacts['PaidFree_Percentage'] <= 30){
					$Temp_Contacts['snooze_days'] = 'Not yet';
					array_push($CONTACTS_UPSTOCK,$Temp_Contacts);			
				}
			}
		}
		
		$data['not_trader_upstock'] = $CONTACTS_UPSTOCK;
		
		$WHERE_8 = "pending_issue != '' AND is_deleted='0'";
		$WHERE_9 = "whatsapp_broadcast_list != '' AND is_deleted='0' AND (pending_issue = '' OR pending_issue IS NULL)";
		
		$data['pending_issue'] = $this->common_model->get_sql_select_data('contacts',$WHERE_8,'','','id desc');
		
		if(!empty($this->input->post())){
			if($this->input->post('wp_broadcast_segment') != ""){
				$data['wp_broadcast_segment'] = ($this->input->post('wp_broadcast_segment') != "") ? $this->input->post('wp_broadcast_segment') : "";
				$WHERE_9 .= " AND FIND_IN_SET('".$this->input->post('wp_broadcast_segment')."',whatsapp_broadcast_list)";
			}	
		} else{ 
			$data['wp_broadcast_segment'] = "";
		}
		
		$data['whatsapp_broadcast_list'] = $this->common_model->get_sql_select_data('contacts',$WHERE_9,'','','id desc');
		
		$this->template->build('dashboard',$data);      
	}
	public function ajaxAddProject()
	{
		$project_name = $this->input->post('project_name');
		$database_name = $this->input->post('database_name');
		$url = $this->input->post('url');

		$project_exists = $this->dashboard_model->PorjectExists($project_name);
		if(empty($project_exists))
		{
			if (!is_dir('uploads/Projects/'.$project_name)) 
			{
				$status = $this->dashboard_model->InsertProjectData($project_name,$database_name,$url);

			    mkdir('./uploads/Projects/' . $project_name, 0777, TRUE);
			    $srcdir = './uploads/BaseStructure/';
				$dstdir = './uploads/Projects/'.$project_name.'/';
				$this->common_model->directory_copy($srcdir,$dstdir);
			    $message['status'] = "true";
			    $message['message'] = "Congratulation! Project Created Successfully";
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
	private function getPercentageTrade($contact_id = ''){
		$PLAN_LOG = $this->common_model->get_sql_select_data('plan_log',array('user_id' => $contact_id));
		
		$DATES_ARRAY = array();
		$PREVIOUS_MONTH = "";
		
		$PREVIOUS_STARTDATE = "";
		$PREVIOUS_ENDDATE = "";
		$PREVIOUS_PLANTYPE = "";
		
		if(!empty($PLAN_LOG)){
			
			$TOTAL_RECORDS = count($PLAN_LOG);
			
			for($i = 0;$i<$TOTAL_RECORDS;$i++){
				
				$Month_Year = date("M-Y",strtotime($PLAN_LOG[$i]['start_date']));
				
				$START_DATE = "";
				$END_DATE = "";
				
				$START_DATE = date("Y-m-d",strtotime($PLAN_LOG[$i]['start_date']));
				$END_DATE = ($PLAN_LOG[$i]['end_date'] != '0000-00-00') ? date("Y-m-d",strtotime($PLAN_LOG[$i]['end_date'])) : date("Y-m-d");
				$PLAN_TYPE = $PLAN_LOG[$i]['plan_type'];
				
				if($i > 0) {
					
					$datediff = strtotime($START_DATE) - strtotime($PREVIOUS_ENDDATE);
					$DAYS = round($datediff / (60 * 60 * 24));		
					
					if($DAYS >= 2) {
						
						$New_START_DATE = date("Y-m-d",strtotime('+1 day',strtotime($PREVIOUS_ENDDATE)));
						$New_END_DATE = date("Y-m-d",strtotime('-1 day',strtotime($START_DATE)));
						
						$LAST_DAY2 = date("Y-m-t", strtotime($New_START_DATE));
						$NEXT_DAY2 = date("Y-m-d",strtotime('+1 day',strtotime($LAST_DAY2)));
						
						if($LAST_DAY2 <= $New_END_DATE){
							
							$TEMP_DATES_ARRAY['start_date'] = $New_START_DATE;
							$TEMP_DATES_ARRAY['end_date'] = $LAST_DAY2;	
							$TEMP_DATES_ARRAY['plan_type'] = 'Inactive';	
							
							array_push($DATES_ARRAY,$TEMP_DATES_ARRAY);
							
							$TEMP_DATES_ARRAY['start_date'] = $NEXT_DAY2;
							$TEMP_DATES_ARRAY['end_date'] = $New_END_DATE;	
							$TEMP_DATES_ARRAY['plan_type'] = 'Inactive';	
							
							$PREVIOUS_STARTDATE = $NEXT_DAY2;
							$PREVIOUS_ENDDATE = $New_END_DATE;
							
						} else {
							$TEMP_DATES_ARRAY['start_date'] = $New_START_DATE;
							$TEMP_DATES_ARRAY['end_date'] = $New_END_DATE;		
							$TEMP_DATES_ARRAY['plan_type'] = 'Inactive';	
							
							$PREVIOUS_ENDDATE = $New_END_DATE;
							$PREVIOUS_STARTDATE = $New_START_DATE;
						}
						
						$i--;	
						
					} else {
						
						$LAST_DAY = date("Y-m-t", strtotime($START_DATE));
						$NEXT_DAY = date("Y-m-d",strtotime('+1 day',strtotime($LAST_DAY)));
						
						if($LAST_DAY <= $END_DATE){
							
							$TEMP_DATES_ARRAY['start_date'] = $START_DATE;
							$TEMP_DATES_ARRAY['end_date'] = $LAST_DAY;	
							$TEMP_DATES_ARRAY['plan_type'] = $PLAN_TYPE;	
							
							if($END_DATE > $LAST_DAY) {							
							
								array_push($DATES_ARRAY,$TEMP_DATES_ARRAY);
							
								$TEMP_DATES_ARRAY['start_date'] = $NEXT_DAY;
								$TEMP_DATES_ARRAY['end_date'] = $END_DATE;	
								$TEMP_DATES_ARRAY['plan_type'] = $PLAN_TYPE;
								
								$PREVIOUS_STARTDATE = $NEXT_DAY;
								$PREVIOUS_ENDDATE = $END_DATE;
							} else { 
								$PREVIOUS_STARTDATE = $START_DATE;
								$PREVIOUS_ENDDATE = $LAST_DAY;
							}
							
						} else {
							$TEMP_DATES_ARRAY['start_date'] = $START_DATE;
							$TEMP_DATES_ARRAY['end_date'] = $END_DATE;	
							$TEMP_DATES_ARRAY['plan_type'] = $PLAN_TYPE;	
							
							$PREVIOUS_STARTDATE = $START_DATE;
							$PREVIOUS_ENDDATE = $END_DATE;	
						}
					}
					
				} else {
					
					$LAST_DAY = date("Y-m-t", strtotime($START_DATE));
					$NEXT_DAY = date("Y-m-d",strtotime('+1 day',strtotime($LAST_DAY)));
					
					if($LAST_DAY <= $END_DATE){
						
						$TEMP_DATES_ARRAY['start_date'] = $START_DATE;
						$TEMP_DATES_ARRAY['end_date'] = $LAST_DAY;	
						$TEMP_DATES_ARRAY['plan_type'] = $PLAN_TYPE;	
						
						if($END_DATE > $LAST_DAY) {							
						
							array_push($DATES_ARRAY,$TEMP_DATES_ARRAY);
						
							$TEMP_DATES_ARRAY['start_date'] = $NEXT_DAY;
							$TEMP_DATES_ARRAY['end_date'] = $END_DATE;	
							$TEMP_DATES_ARRAY['plan_type'] = $PLAN_TYPE;
							
							$PREVIOUS_STARTDATE = $NEXT_DAY;
							$PREVIOUS_ENDDATE = $END_DATE;
						} else { 
							$PREVIOUS_STARTDATE = $START_DATE;
							$PREVIOUS_ENDDATE = $LAST_DAY;
						}
						
					} else {
						$TEMP_DATES_ARRAY['start_date'] = $START_DATE;
						$TEMP_DATES_ARRAY['end_date'] = $END_DATE;	
						$TEMP_DATES_ARRAY['plan_type'] = $PLAN_TYPE;	
						
						$PREVIOUS_STARTDATE = $START_DATE;
						$PREVIOUS_ENDDATE = $END_DATE;	
					}
				}
				
				array_push($DATES_ARRAY,$TEMP_DATES_ARRAY);
			}
		}
		
		$Final_array = array();
		$TOTAL_LOGS = (!empty($DATES_ARRAY)) ? count($DATES_ARRAY) : 0;
		
		$Total_Percentage = 0;
		$Average_Percentage = 0;
		$Average_PaidFree_Percentage =0;
		$Average_NotActive_Percentage =0;
		
		$PaidFree_Percentage = 0;
		$NotActive_Percentage = 0;
		
		$Total_Trade_Logs = 0;
		$Total_Trade_PaidFree_Logs = 0;
		$Total_Trade_Inactive_Logs = 0;
		
		for($j=0;$j<count($DATES_ARRAY);$j++){
			
			$SDATE = "";
			$EDATE = "";
			
			$SDATE = date("Y-m-d",strtotime($DATES_ARRAY[$j]['start_date']));
			$EDATE = date("Y-m-d",strtotime($DATES_ARRAY[$j]['end_date']));
			
			$Total_Treding_Days = $this->dashboard_model->getTotalTredingDays($SDATE,$EDATE);
			$Total_Trade_Days = $this->dashboard_model->getTotalTradeDays($contact_id,$SDATE,$EDATE);
			
			if($Total_Treding_Days != 0) {
				$Percentage = round(($Total_Trade_Days * 100) / $Total_Treding_Days);	
			} else {
				$Percentage = 0;
			}
			
			$Temp_Final_Array['Month'] = date("M-Y",strtotime($DATES_ARRAY[$j]['start_date']));
			$Temp_Final_Array['start_date'] = $SDATE;
			$Temp_Final_Array['end_date'] = $EDATE;
			$Temp_Final_Array['total_treding_days'] = $Total_Treding_Days;
			$Temp_Final_Array['total_trade_days'] = $Total_Trade_Days;
			$Temp_Final_Array['percentage'] = $Percentage;
			$Temp_Final_Array['Status'] = $DATES_ARRAY[$j]['plan_type'];
			
			array_push($Final_array,$Temp_Final_Array);
			
			$Total_Percentage = $Total_Percentage + $Percentage;
			
			if($Total_Treding_Days > 0){
				$Total_Trade_Logs = $Total_Trade_Logs + 1;
			}
			
			if($DATES_ARRAY[$j]['plan_type'] == 'Free' || $DATES_ARRAY[$j]['plan_type'] == 'Paid'){
				$PaidFree_Percentage = $PaidFree_Percentage + $Percentage;
				
				if($Total_Treding_Days > 0){
					$Total_Trade_PaidFree_Logs = $Total_Trade_PaidFree_Logs + 1;
				}
			} else if($DATES_ARRAY[$j]['plan_type'] == 'Inactive'){
				$NotActive_Percentage = $NotActive_Percentage + $Percentage;
				
				if($Total_Treding_Days > 0){
					$Total_Trade_Inactive_Logs = $Total_Trade_Inactive_Logs + 1;
				}
			}
		}
		
		if($Total_Trade_Logs != 0 && $Total_Percentage != 0){
			$Average_Percentage = round($Total_Percentage / $Total_Trade_Logs);
		}
		if($PaidFree_Percentage != 0 && $Total_Trade_PaidFree_Logs != 0){
			$Average_PaidFree_Percentage = round($PaidFree_Percentage / $Total_Trade_PaidFree_Logs);
		}
		if($NotActive_Percentage != 0 && $Total_Trade_Inactive_Logs != 0){
			$Average_NotActive_Percentage = round($NotActive_Percentage / $Total_Trade_Inactive_Logs);
		}
		
		$percentage_arr = array();
		
		$percentage_arr['PaidFree_Percentage'] = $Average_PaidFree_Percentage;
		$percentage_arr['NotActive_Percentage'] = $Average_NotActive_Percentage;
		
		return $percentage_arr;
	}
	
	public function ajaxSnoozeUser()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('cid','User Id','required');
		$this->form_validation->set_rules('snooze_date','Snooze Date','required');
		
		if($this->form_validation->run() == FALSE)
		{
			$status = false;
			$message = validation_errors();
			$this->common_model->call_Function($status,$message);
		} 
		else 
		{
			$Contact_Id = $this->input->post('cid');
			$Snooze_Date = explode("to",$this->input->post('snooze_date'));
			$start_date = date("Y-m-d",strtotime($Snooze_Date[0]));
			$end_date = date("Y-m-d",strtotime($Snooze_Date[1]));
			
			$res = $this->common_model->get_sql_select_data('contacts', array('id' => $Contact_Id));	
			
			if(!empty($res)){
				
				$updateData['snooze_from'] = $start_date;
				$updateData['snooze_to'] = $end_date;
				$updateData['updated_date'] = date("Y-m-d H:i:s");
				
				$res2 = $this->common_model->UPDATEDATA('contacts', array('id' => $Contact_Id), $updateData);	
				
				if($res2){
					$status = true;
					$message = "User has been snoozed from '".$start_date."' to '".$end_date."'";
					$this->common_model->call_Function($status,$message);	
				} else {
					$status = false;
					$message = "There is something wrong!";
					$this->common_model->call_Function($status,$message);
				}
			} else {
				$status = false;
				$message = "There is something wrong!";
				$this->common_model->call_Function($status,$message);
			}
		}    
	}
	
	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url(),'refresh');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */