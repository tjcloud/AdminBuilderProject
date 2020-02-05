<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'libraries/ImplementJWT.php');
class Attendance extends MX_Controller {

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
		$this->load->model('attendance_model');
		$this->objOfJwt = new ImplementJwt;
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
			
			$Total_Treding_Days = $this->attendance_model->getTotalTredingDays($SDATE,$EDATE);
			$Total_Trade_Days = $this->attendance_model->getTotalTradeDays($contact_id,$SDATE,$EDATE);
			
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
		}
		
		if($Total_Trade_Logs != 0 && $Total_Percentage != 0){
			$Average_Percentage = round($Total_Percentage / $Total_Trade_Logs);
		}
		
		$percentage_arr = array();
		$percentage_arr['Overall_Percentage'] = $Average_Percentage;
		
		return $percentage_arr;
	}
	
	public function index()
	{
		$WHERE = " 1=1";
		
		if(!empty($this->input->post())){
			
			if($this->input->post('company_name') != ""){
				$WHERE .= " AND trade_company = '".$this->input->post('company_name')."'";
			}
			if($this->input->post('segment') != ""){
				$WHERE .= " AND FIND_IN_SET('".$this->input->post('segment')."',segments)";
			}
			if($this->input->post('paid_status') != ""){
				$WHERE .= " AND is_paid='".$this->input->post('paid_status')."'";
			}
		}
		
		$data['company_name'] = ($this->input->post('company_name') != "") ? $this->input->post('company_name') : "";
		$data['segment'] = ($this->input->post('segment') != "") ? $this->input->post('segment') : "";
		$data['paid_status'] = ($this->input->post('paid_status') != "") ? $this->input->post('paid_status') : "";
		$data['overall_percentage'] = ($this->input->post('overall_percentage') != "") ? $this->input->post('overall_percentage') : "";
		
		$attandance = $this->common_model->get_sql_select_data('attandance_report',$WHERE,'','','attandance_date desc','user_id');
		
		$ATTANDANCE_DATA = array();
		foreach($attandance as $value) {
			$temp_attandance['client_id'] = $value['client_id'];
			$temp_attandance['client_name'] = $value['client_name'];
			$temp_attandance['contact_no'] = $value['contact_no'];
			$temp_attandance['trade_company_text'] = $value['trade_company_text'];
			$temp_attandance['segments'] = $value['segments'];
			$temp_attandance['is_paid'] = $value['is_paid'];
			
			$user_id = $value['user_id'];
			$percentage = $this->getPercentageTrade($user_id);
			
			$temp_attandance['Overall_Percentage'] = $percentage['Overall_Percentage'];
			
			if($data['overall_percentage'] != "" && $data['overall_percentage'] == '1'){
				if($percentage['Overall_Percentage'] >= 0 && $percentage['Overall_Percentage'] <= 25){
					array_push($ATTANDANCE_DATA,$temp_attandance);	
				} 
			} else if($data['overall_percentage'] != "" && $data['overall_percentage'] == '2'){	
				if($percentage['Overall_Percentage'] >= 26 && $percentage['Overall_Percentage'] <= 50){
					array_push($ATTANDANCE_DATA,$temp_attandance);	
				} 
			} else if($data['overall_percentage'] != "" && $data['overall_percentage'] == '3'){	
				if($percentage['Overall_Percentage'] >= 51 && $percentage['Overall_Percentage'] <= 75){
					array_push($ATTANDANCE_DATA,$temp_attandance);	
				} 
			} else if($data['overall_percentage'] != "" && $data['overall_percentage'] == '4'){	
				if($percentage['Overall_Percentage'] >= 76 && $percentage['Overall_Percentage'] <= 100){
					array_push($ATTANDANCE_DATA,$temp_attandance);	
				} 
			} else if($data['overall_percentage'] == "") {
				array_push($ATTANDANCE_DATA,$temp_attandance);
			}
		}
		
		$data['attandance'] = $ATTANDANCE_DATA;
		
		$this->template->build('index',$data);      
	}
	
	private function clean($string) {
	   $string = str_replace(' ', '', $string); // Replaces all spaces with hyphens.
	   $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

	   return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
	}
	
	public function ajaxAddAttendance(){
		
		$Get_ALL_Contacts = $this->common_model->get_sql_select_data('contacts' ,array('is_deleted' => '0','client_id !=' => ""));
		
		$selected_date = ($this->input->post('attend_date') != "") ? date("Y-m-d",strtotime($this->input->post('attend_date'))) : date("Y-m-d");
		$fileName = $_FILES["CSV_file"]["tmp_name"];
		$file_type = $_FILES["CSV_file"]["name"];
		
		$FNAME = explode(".",$file_type);
		
		if($FNAME[1] != 'csv' && $FNAME[1] != 'CSV'){
			$status = false;
			$message = "Please upload valid csv file!";
			$this->common_model->call_Function($status,$message);
		}
		
		
		$Clients_Code_CSV = array();
		
		if ($_FILES["CSV_file"]["size"] > 0) {
			$file = fopen($fileName, "r");
			$i = 0;
			while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
				if($i > 0){
					if($column[0] != ""){
						$Clients_Code_CSV[] = $this->clean($column[0]);
					}	
				}
				$i++;
			}
		}
		
		if(!empty($Get_ALL_Contacts) && !empty($Clients_Code_CSV)){
			
			foreach($Get_ALL_Contacts as $Get_ALL_Contacts_Data){
				
				$is_attend = '0';
				$User_Id = $Get_ALL_Contacts_Data['id'];
				$CLIENT_ID = $Get_ALL_Contacts_Data['client_id'];
				
				if(!in_array($CLIENT_ID,$Clients_Code_CSV)){
					$is_attend = '0';
				} else {
					$is_attend = '1';
				}
				
				$CheckSelectedDateAttandnce = $this->common_model->get_sql_select_data('attandance_report' ,array('attandance_date' => $selected_date,'client_id' => $CLIENT_ID),array('id'));
				
				if(!empty($CheckSelectedDateAttandnce)){
					
					$updateAttandance['client_name'] = $Get_ALL_Contacts_Data['contact_name'];
					
					$updateAttandance['contact_no'] = $Get_ALL_Contacts_Data['contact_no'];
					$updateAttandance['whatsapp_no'] = $Get_ALL_Contacts_Data['whatsapp_no'];
					$updateAttandance['trade_company'] = $Get_ALL_Contacts_Data['trade_company'];
					$updateAttandance['trade_company_text'] = $Get_ALL_Contacts_Data['trade_company_text'];
					$updateAttandance['segments'] = $Get_ALL_Contacts_Data['segments'];
					
					$updateAttandance['is_attend'] = $is_attend;
					$updateAttandance['is_paid'] = $Get_ALL_Contacts_Data['payment_type'];
					$updateAttandance['amount'] = $Get_ALL_Contacts_Data['current_amount'];
					$updateAttandance['duration_type'] = $Get_ALL_Contacts_Data['duration_type'];
					$updateAttandance['duration_value'] = $Get_ALL_Contacts_Data['duration_value'];
					$updateAttandance['start_date'] = ($Get_ALL_Contacts_Data['start_date'] != "" && $Get_ALL_Contacts_Data['start_date'] != '0000-00-00 00:00:00') ? date("Y-m-d H:i:s",strtotime($Get_ALL_Contacts_Data['start_date'])) : "";
					$updateAttandance['end_date'] = ($Get_ALL_Contacts_Data['end_date'] != "" && $Get_ALL_Contacts_Data['end_date'] != '0000-00-00 00:00:00') ? date("Y-m-d H:i:s",strtotime($Get_ALL_Contacts_Data['end_date'])) : "";
					$updateAttandance['updated_date'] = date("Y-m-d H:i:s");
					
					$res = $this->common_model->UPDATEDATA('attandance_report',array('id' => $CheckSelectedDateAttandnce[0]['id']),$updateAttandance);	
				}else{
					
					$insertAttandance['client_id'] = $CLIENT_ID;
					$insertAttandance['client_name'] = $Get_ALL_Contacts_Data['contact_name'];
					
					$insertAttandance['contact_no'] = $Get_ALL_Contacts_Data['contact_no'];
					$insertAttandance['whatsapp_no'] = $Get_ALL_Contacts_Data['whatsapp_no'];
					$insertAttandance['trade_company'] = $Get_ALL_Contacts_Data['trade_company'];
					$insertAttandance['trade_company_text'] = $Get_ALL_Contacts_Data['trade_company_text'];
					$insertAttandance['segments'] = $Get_ALL_Contacts_Data['segments'];
					
					$insertAttandance['is_attend'] = $is_attend;
					$insertAttandance['attandance_date'] = $selected_date;
					$insertAttandance['user_id'] = $User_Id;
					$insertAttandance['is_paid'] = $Get_ALL_Contacts_Data['payment_type'];
					$insertAttandance['amount'] = $Get_ALL_Contacts_Data['current_amount'];
					$insertAttandance['duration_type'] = $Get_ALL_Contacts_Data['duration_type'];
					$insertAttandance['duration_value'] = $Get_ALL_Contacts_Data['duration_value'];
					$insertAttandance['start_date'] = ($Get_ALL_Contacts_Data['start_date'] != "" && $Get_ALL_Contacts_Data['start_date'] != '0000-00-00 00:00:00') ? date("Y-m-d H:i:s",strtotime($Get_ALL_Contacts_Data['start_date'])) : "";
					$insertAttandance['end_date'] = ($Get_ALL_Contacts_Data['end_date'] != "" && $Get_ALL_Contacts_Data['end_date'] != '0000-00-00 00:00:00') ? date("Y-m-d H:i:s",strtotime($Get_ALL_Contacts_Data['end_date'])) : "";
					$insertAttandance['created_date'] = date("Y-m-d H:i:s");
					
					$res = $this->common_model->INSERTDATA('attandance_report',$insertAttandance);	
				}
			}
			
			if($res){	
				$status = true;
				$message = "Clients attandance has been imported succesfully.";
				$redirect = base_url().'attendance';
				$this->common_model->call_Function_redirect($status,$message,$redirect);		
			}else{
				$status = false;
				$message = "There is something wrong!";
				$this->common_model->call_Function($status,$message);
			}	
		}else{
			$status = false;
			$message = "Users are missing!";
			$this->common_model->call_Function($status,$message);
		}
		   
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */