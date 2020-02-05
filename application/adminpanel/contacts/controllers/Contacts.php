<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'libraries/ImplementJWT.php');
class Contacts extends MX_Controller {

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
		$this->load->model('contacts_model');
		$this->objOfJwt = new ImplementJwt;
    }
	
	public function index()
	{
		$WHERE = " is_deleted = '0'";
		
		if(!empty($this->input->post())){
			
			if($this->input->post('company_name') != ""){
				$WHERE .= " AND trade_company = '".$this->input->post('company_name')."'";
			}
			if($this->input->post('segment') != ""){
				$WHERE .= " AND FIND_IN_SET('".$this->input->post('segment')."',segments)";
			}
			if($this->input->post('poa_status') != ""){
				$WHERE .= " AND poa_status='".$this->input->post('poa_status')."'";
			}
			if($this->input->post('paid_status') != ""){
				$WHERE .= " AND payment_type='".$this->input->post('paid_status')."'";
			}
		}
		
		$data['company_name'] = ($this->input->post('company_name') != "") ? $this->input->post('company_name') : "";
		$data['segment'] = ($this->input->post('segment') != "") ? $this->input->post('segment') : "";
		$data['poa_status'] = ($this->input->post('poa_status') != "") ? $this->input->post('poa_status') : "";
		$data['paid_status'] = ($this->input->post('paid_status') != "") ? $this->input->post('paid_status') : "";
		
		$CONTACTS = array();
		$CONTACT_DATA = $this->common_model->get_sql_select_data('contacts',$WHERE,'','','id desc');
		
		foreach($CONTACT_DATA as $CONTACT_D){
			
			$Temp_Contacts['payment_type'] = $CONTACT_D['payment_type'];
			$Temp_Contacts['start_date'] = $CONTACT_D['start_date'];
			$Temp_Contacts['end_date'] = $CONTACT_D['end_date'];
			$Temp_Contacts['client_id'] = $CONTACT_D['client_id'];
			$Temp_Contacts['contact_name'] = $CONTACT_D['contact_name'];
			$Temp_Contacts['contact_no'] = $CONTACT_D['contact_no'];
			$Temp_Contacts['payment_type'] = $CONTACT_D['payment_type'];
			$Temp_Contacts['id'] = $CONTACT_D['id'];
			$Temp_Contacts['is_active'] = $CONTACT_D['is_active'];
			$Temp_Contacts['whatsapp_no'] = $CONTACT_D['whatsapp_no'];
			
			$LAST_PLAN_LOG = $this->common_model->get_sql_select_data('plan_log',array('user_id' => $CONTACT_D['id']),'','1','id desc');
			
			if(!empty($LAST_PLAN_LOG)) {
				
				if($LAST_PLAN_LOG[0]['plan_type'] == 'Paid'){
					$CONTACT_ID	= $LAST_PLAN_LOG[0]['user_id'];
					$START_DATE = date("Y-m-d",strtotime($LAST_PLAN_LOG[0]['start_date']));
					$END_DATE = date("Y-m-d",strtotime($LAST_PLAN_LOG[0]['end_date']));
					
					$Total_Treding_Days = $this->contacts_model->getTotalTredingDays($START_DATE,$END_DATE);
					$Total_Trade_Days = $this->contacts_model->getTotalTradeDays($CONTACT_ID,$START_DATE,$END_DATE);
					
					if($Total_Treding_Days != 0) {
						$Percentage = round(($Total_Trade_Days * 100) / $Total_Treding_Days);	
					} else {
						$Percentage = 0;
					}
				
					$Temp_Contacts['PaidFree_Percentage'] = "Paid : ".$Percentage."%";;	
					
				} else if($LAST_PLAN_LOG[0]['plan_type'] == 'Free') {
					$CONTACT_ID	= $LAST_PLAN_LOG[0]['user_id'];
					$START_DATE = date("Y-m-d",strtotime($LAST_PLAN_LOG[0]['start_date']));
					$END_DATE = date("Y-m-d");
					
					$Total_Treding_Days = $this->contacts_model->getTotalTredingDays($START_DATE,$END_DATE);
					$Total_Trade_Days = $this->contacts_model->getTotalTradeDays($CONTACT_ID,$START_DATE,$END_DATE);
					
					if($Total_Treding_Days != 0) {
						$Percentage = round(($Total_Trade_Days * 100) / $Total_Treding_Days);	
					} else {
						$Percentage = 0;
					}
				
					$Temp_Contacts['PaidFree_Percentage'] = "Free : ".$Percentage."%";;	
				}
			} 
			
			$Per = $this->getPercentageTrade($CONTACT_D['id']);
			
			$Temp_Contacts['Overall_Percentage'] = $Per['Overall_Percentage'];
			$Temp_Contacts['NotActive_Percentage'] = $Per['NotActive_Percentage'];
			
			$Avg_Per = 0;
			$Total_Per = $Per['PaidFree_Percentage'] + $Per['NotActive_Percentage'];
			if($Total_Per != 0){
				$Avg_Per = round($Total_Per / 2);
			}
			$Temp_Contacts['Avg_Percentage'] = $Avg_Per;
			
			array_push($CONTACTS,$Temp_Contacts);		
		}
		
		$data['contacts'] = $CONTACTS;
		
		$this->template->build('index',$data);      
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
			
			$Total_Treding_Days = $this->contacts_model->getTotalTredingDays($SDATE,$EDATE);
			$Total_Trade_Days = $this->contacts_model->getTotalTradeDays($contact_id,$SDATE,$EDATE);
			
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
		
		$percentage_arr['Overall_Percentage'] = $Average_Percentage;
		$percentage_arr['PaidFree_Percentage'] = $Average_PaidFree_Percentage;
		$percentage_arr['NotActive_Percentage'] = $Average_NotActive_Percentage;
		
		return $percentage_arr;
	}
	
	public function create()
	{
		$this->template->build('add');
	}
	
	public function ajaxAddContact()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('contact_name','Contact Name','required');
		$this->form_validation->set_rules('contact_no','Contact No.','required');
		$this->form_validation->set_rules('whatsapp_no','Whatsapp No.','required');
		$this->form_validation->set_rules('trade_company','Trade Company','required');
		$this->form_validation->set_rules('payment_type','Payment Type','required');
		
		if($this->input->post('trade_company') == 1 || $this->input->post('trade_company') == 2){
			$this->form_validation->set_rules('client_id','Client ID','required');
			$this->form_validation->set_rules('poa_status','POA Status','required');
		}
		
		if($this->input->post('payment_type') == 1){
			$this->form_validation->set_rules('current_amount','Amount','required');
			$this->form_validation->set_rules('duration_type','Duration Type','required');
			$this->form_validation->set_rules('duration_value','Duration Value','required');
			$this->form_validation->set_rules('start_date','Plan Start Date','required');
			$this->form_validation->set_rules('payment_for_segment','Payment for which segment','required');
		}
		
		if($this->form_validation->run() == FALSE)
		{
			$status = false;
			$message = validation_errors();
			$this->common_model->call_Function($status,$message);
		} 
		else 
		{
			$WHERE_DUP = '1=1 AND contact_no="'.$this->input->post('contact_no').'"';
			$WHERE_DUP .= ' AND trade_company="'.$this->input->post('trade_company').'"';
			$WHERE_DUP .= ' AND payment_type="'.$this->input->post('payment_type').'"';
			if($this->input->post('payment_type') == 1){
				$WHERE_DUP .= ' AND payment_for_segment="'.$this->input->post('payment_for_segment').'"';
			}
			
			$contact = $this->common_model->get_sql_select_data('contacts' ,$WHERE_DUP,array('id','is_deleted'));
			
			if(!empty($contact) && $contact[0]['is_deleted'] == 1) {
				
				$Contact_Id = $contact[0]['id'];
				
				$updateData['contact_name'] = ($this->input->post('contact_name') != "") ? $this->input->post('contact_name') : "";
				$updateData['whatsapp_no'] = ($this->input->post('whatsapp_no') != "") ? $this->input->post('whatsapp_no') : "";
				$updateData['trade_company'] = ($this->input->post('trade_company') != "") ? $this->input->post('trade_company') : "";
				$updateData['trade_company_text'] = ($this->input->post('trade_company_text') != "") ? $this->input->post('trade_company_text') : "";
				
				if($this->input->post('trade_company') == 1 || $this->input->post('trade_company') == 2){
					$updateData['client_id'] = ($this->input->post('client_id')) ? $this->input->post('client_id') : "";
					$updateData['poa_status'] = ($this->input->post('poa_status')) ? strval($this->input->post('poa_status')) : "";	
					$updateData['poa_text_status'] = ($this->input->post('poa_text_status') != "") ? $this->input->post('poa_text_status') : "";
				}else{
					$updateData['client_id'] = "";
					$updateData['poa_status'] = "";
					$updateData['poa_text_status'] = "";
				}
				
				$updateData['segments'] = (!empty($this->input->post('segments'))) ? implode(",",$this->input->post('segments')) : "";
				$updateData['pending_issue'] = (!empty($this->input->post('pending_issue'))) ? implode(",",$this->input->post('pending_issue')) : "";
				$updateData['whatsapp_broadcast_list'] = (!empty($this->input->post('whatsapp_broadcast_list'))) ? implode(",",$this->input->post('whatsapp_broadcast_list')) : "";
				$updateData['payment_type'] = ($this->input->post('payment_type') != "") ? $this->input->post('payment_type') : "";
				
				if($this->input->post('payment_type') == 1){
					$updateData['start_date'] = ($this->input->post('start_date') != "") ? date("Y-m-d",strtotime($this->input->post('start_date'))) : "";
					$updateData['end_date'] = ($this->input->post('end_date') != "") ? date("Y-m-d",strtotime($this->input->post('end_date'))) : "";
					$updateData['current_amount'] = ($this->input->post('current_amount')) ? $this->input->post('current_amount') : "";
					$updateData['duration_type'] = ($this->input->post('duration_type')) ? $this->input->post('duration_type') : "";
					$updateData['duration_value'] = ($this->input->post('duration_value')) ? $this->input->post('duration_value') : "";
					$updateData['payment_for_segment'] = ($this->input->post('payment_for_segment')) ? $this->input->post('payment_for_segment') : "";
				}else{
					$updateData['start_date'] = "0000-00-00 00:00:00";
					$updateData['end_date'] = "0000-00-00 00:00:00";	
					$updateData['current_amount'] = "";
					$updateData['duration_type'] = "";
					$updateData['duration_value'] = "";
					$updateData['payment_for_segment'] = "";
				}
				
				$updateData['note'] = ($this->input->post('note') != "") ? $this->input->post('note') : "";
				$updateData['updated_date'] = date("Y-m-d H:i:s");
				$updateData['is_deleted'] = '0';
				$updateData['deleted_date'] = '0000-00-00 00:00:00';
				
				if($this->input->post('payment_type') == 1){	
					$updateData['last_payment_date'] = date("Y-m-d H:i:s");	
				}
				
				$res = $this->common_model->UPDATEDATA('contacts', array('id' => $Contact_Id), $updateData);	
				
				if($res){
					
					if($this->input->post('payment_type') == 1){	
						$insertPaymentLog = array();
					
						$insertPaymentLog['user_id'] = $Contact_Id;
						$insertPaymentLog['amount'] = ($this->input->post('current_amount')) ? $this->input->post('current_amount') : "";
						$insertPaymentLog['duration_type'] = ($this->input->post('duration_type')) ? $this->input->post('duration_type') : "";
						$insertPaymentLog['duration_value'] = ($this->input->post('duration_value')) ? $this->input->post('duration_value') : "";
						$insertPaymentLog['start_date'] = ($this->input->post('start_date') != "") ? date("Y-m-d",strtotime($this->input->post('start_date'))) : "";
						$insertPaymentLog['end_date'] = ($this->input->post('end_date') != "") ? date("Y-m-d",strtotime($this->input->post('end_date'))) : "";
						$insertPaymentLog['created_date'] = date("Y-m-d H:i:s");	
						
						$res = $this->common_model->INSERTDATA('payment_logs',$insertPaymentLog);	
					}
					
					$status = true;
					$message = "Contact has been restored successfully.";
					$redirect = base_url().'contacts';
					$this->common_model->call_Function_redirect($status,$message,$redirect);		
				}else{
					$data['status'] = false;
					$data['message'] = 'There is something wrong while restoring contact.';
					header('Content-Type:text/json');
					echo json_encode($data);
					exit;
				}
			} else if(!empty($contact)) {
				$data['status'] = false;
				$data['message'] = 'Contact with provided details is already exist!';
				header('Content-Type:text/json');
				echo json_encode($data);
				exit;
			}
			
			$insertData['contact_name'] = ($this->input->post('contact_name')) ? $this->input->post('contact_name') : "";
			$insertData['contact_no'] = ($this->input->post('contact_no')) ? $this->input->post('contact_no') : "";
			$insertData['whatsapp_no'] = ($this->input->post('whatsapp_no')) ? $this->input->post('whatsapp_no') : "";
			$insertData['trade_company'] = ($this->input->post('trade_company')) ? $this->input->post('trade_company') : "";
			$insertData['trade_company_text'] = ($this->input->post('trade_company_text')) ? $this->input->post('trade_company_text') : "";
			
			if($this->input->post('trade_company') == 1 || $this->input->post('trade_company') == 2){
				$insertData['client_id'] = ($this->input->post('client_id')) ? $this->input->post('client_id') : "";
				$insertData['poa_status'] = ($this->input->post('poa_status')) ? strval($this->input->post('poa_status')) : "";	
				$insertData['poa_text_status'] = ($this->input->post('poa_text_status')) ? $this->input->post('poa_text_status') : "";
			}else{
				$insertData['client_id'] = "";
				$insertData['poa_status'] = "";
				$insertData['poa_text_status'] = "";
			}
			
			$insertData['segments'] = ($this->input->post('segments')) ? implode(",",$this->input->post('segments')) : "";
			$insertData['pending_issue'] = (!empty($this->input->post('pending_issue'))) ? implode(",",$this->input->post('pending_issue')) : "";
			$insertData['whatsapp_broadcast_list'] = (!empty($this->input->post('whatsapp_broadcast_list'))) ? implode(",",$this->input->post('whatsapp_broadcast_list')) : "";
			$insertData['payment_type'] = ($this->input->post('payment_type')) ? $this->input->post('payment_type') : "";
			
			if($this->input->post('payment_type') == 1) {
				$insertData['current_amount'] = ($this->input->post('current_amount')) ? $this->input->post('current_amount') : "";
				$insertData['duration_type'] = ($this->input->post('duration_type')) ? $this->input->post('duration_type') : "";
				$insertData['duration_value'] = ($this->input->post('duration_value')) ? $this->input->post('duration_value') : "";
				$insertData['start_date'] = ($this->input->post('start_date')) ? date("Y-m-d",strtotime($this->input->post('start_date'))) : "";
				$insertData['end_date'] = ($this->input->post('end_date')) ? date("Y-m-d",strtotime($this->input->post('end_date'))) : "";
				$insertData['payment_for_segment'] = ($this->input->post('payment_for_segment')) ? $this->input->post('payment_for_segment') : "";
			} else { 
				$insertData['start_date'] = "0000-00-00 00:00:00";
				$insertData['end_date'] = "0000-00-00 00:00:00";	
				$insertData['current_amount'] = "";
				$insertData['duration_type'] = "";
				$insertData['duration_value'] = "";
				$insertData['payment_for_segment'] = "";
			}
			
			$insertData['note'] = ($this->input->post('note')) ? $this->input->post('note') : "";
			$insertData['is_active'] = '1';
			$insertData['created_date'] = date("Y-m-d H:i:s");
			
			if($this->input->post('payment_type') == 1){
				$insertData['last_payment_date'] = date("Y-m-d H:i:s");
			}
			
			$resInsert = $this->common_model->INSERTDATA('contacts',$insertData);	
			
			if($resInsert){	

				if($this->input->post('payment_type') == 1){	
					$insertPaymentLog = array();
				
					$insertPaymentLog['user_id'] = $resInsert;
					$insertPaymentLog['amount'] = ($this->input->post('current_amount')) ? $this->input->post('current_amount') : "";
					$insertPaymentLog['duration_type'] = ($this->input->post('duration_type')) ? $this->input->post('duration_type') : "";
					$insertPaymentLog['duration_value'] = ($this->input->post('duration_value')) ? $this->input->post('duration_value') : "";
					$insertPaymentLog['start_date'] = ($this->input->post('start_date') != "") ? date("Y-m-d",strtotime($this->input->post('start_date'))) : "";
					$insertPaymentLog['end_date'] = ($this->input->post('end_date') != "") ? date("Y-m-d",strtotime($this->input->post('end_date'))) : "";
					$insertPaymentLog['created_date'] = date("Y-m-d H:i:s");	
					
					$res = $this->common_model->INSERTDATA('payment_logs',$insertPaymentLog);	
					
					$insertPlanLog = array();
				
					$insertPlanLog['user_id'] = $resInsert;
					$insertPlanLog['start_date'] = ($this->input->post('start_date') != "") ? date("Y-m-d",strtotime($this->input->post('start_date'))) : "";
					$insertPlanLog['end_date'] = ($this->input->post('end_date') != "") ? date("Y-m-d",strtotime($this->input->post('end_date'))) : "";
					$insertPlanLog['plan_type'] = "Paid";
					$insertPlanLog['created_date'] = date("Y-m-d H:i:s");	
					
					$res = $this->common_model->INSERTDATA('plan_log',$insertPlanLog);	
					
				} else {
					
					$insertPlanLog = array();
				
					$insertPlanLog['user_id'] = $resInsert;
					$insertPlanLog['start_date'] = date("Y-m-d");
					$insertPlanLog['end_date'] = "";
					$insertPlanLog['plan_type'] = "Free";
					$insertPlanLog['created_date'] = date("Y-m-d H:i:s");	
					
					$res = $this->common_model->INSERTDATA('plan_log',$insertPlanLog);	
					
				}
			
				$status = true;
				$message = "Contact added successfully.";
				$redirect = base_url().'contacts';
				$this->common_model->call_Function_redirect($status,$message,$redirect);		
			}else{
				$status = false;
				$message = "There is something wrong!";
				$this->common_model->call_Function($status,$message);
			}
		}    
	}
	
	public function edit()
	{
		if ($this->uri->segment(3) === FALSE){
			$contact_id = 0;
		}else{
			$contact_id = (is_numeric($this->uri->segment(3))) ? $this->uri->segment(3) : 0;
		}
		
		$data['contact_details'] = $this->common_model->get_sql_select_data('contacts',array('id' => $contact_id,'is_deleted' => '0'));
		
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
			
			$Total_Treding_Days = $this->contacts_model->getTotalTredingDays($SDATE,$EDATE);
			$Total_Trade_Days = $this->contacts_model->getTotalTradeDays($contact_id,$SDATE,$EDATE);
			
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
		
		$Final_array = $this->contacts_model->array_orderby($Final_array,'start_date',SORT_ASC);
		
		$data['attandace_report'] = $Final_array;
		$data['average_percentage'] = $Average_Percentage;
		
		$this->template->build('edit',$data);      
	}
	
	public function ajaxEditContact()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('contact_id','Contact ID','required');
		$this->form_validation->set_rules('contact_name','Contact Name','required');
		$this->form_validation->set_rules('contact_no','Contact No.','required');
		$this->form_validation->set_rules('whatsapp_no','Whatsapp No.','required');
		$this->form_validation->set_rules('trade_company','Trade Company','required');
		$this->form_validation->set_rules('payment_type','Payment Type','required');
		
		if($this->input->post('trade_company') == 1 || $this->input->post('trade_company') == 2){
			$this->form_validation->set_rules('client_id','Client ID','required');
			$this->form_validation->set_rules('poa_status','POA Status','required');
		}
		
		if($this->input->post('payment_type') == 1){
			$this->form_validation->set_rules('current_amount','Amount','required');
			$this->form_validation->set_rules('duration_type','Duration Type','required');
			$this->form_validation->set_rules('duration_value','Duration Value','required');
			$this->form_validation->set_rules('start_date','Plan Start Date','required');
			$this->form_validation->set_rules('payment_for_segment','Payment for which segment','required');
		}
		
		if($this->form_validation->run() == FALSE)
		{
			$status = false;
			$message = validation_errors();
			$this->common_model->call_Function($status,$message);
		} 
		else 
		{
			$IsContactExist = $this->common_model->get_sql_select_data('contacts' ,array('id' => $this->input->post('contact_id')),array('id','contact_name','start_date','end_date','payment_type'));
			
			if(empty($IsContactExist)){
				$data['status'] = false;
				$data['message'] = 'Contact with provided Contact ID does not exist!';
				
				header('Content-Type:text/json');
				echo json_encode($data);
				exit;
			}
			
			$WHERE_DUP = '1=1 AND contact_no="'.$this->input->post('contact_no').'" AND id != "'.$this->input->post('contact_id').'"';
			$WHERE_DUP .= ' AND trade_company="'.$this->input->post('trade_company').'"';
			$WHERE_DUP .= ' AND payment_type="'.$this->input->post('payment_type').'"';
			if($this->input->post('payment_type') == 1){
				$WHERE_DUP .= ' AND payment_for_segment="'.$this->input->post('payment_for_segment').'"';
			}
			
			$contact = $this->common_model->get_sql_select_data('contacts',$WHERE_DUP,array('id'));
			
			if(!empty($contact)){
				$data['status'] = false;
				$data['message'] = 'Contact with provided contact number is already exist!';
				
				header('Content-Type:text/json');
				echo json_encode($data);
				exit;
			}
			
			$updateData['contact_name'] = ($this->input->post('contact_name') != "") ? $this->input->post('contact_name') : "";
			$updateData['contact_no'] = ($this->input->post('contact_no') != "") ? $this->input->post('contact_no') : "";
			$updateData['whatsapp_no'] = ($this->input->post('whatsapp_no') != "") ? $this->input->post('whatsapp_no') : "";
			$updateData['trade_company'] = ($this->input->post('trade_company') != "") ? $this->input->post('trade_company') : "";
			$updateData['trade_company_text'] = ($this->input->post('trade_company_text') != "") ? $this->input->post('trade_company_text') : "";
			
			if($this->input->post('trade_company') == 1 || $this->input->post('trade_company') == 2){
				$updateData['client_id'] = ($this->input->post('client_id') != "") ? $this->input->post('client_id') : "";
				$updateData['poa_status'] = ($this->input->post('poa_status') != "") ? strval($this->input->post('poa_status')) : "";	
			}else{
				$updateData['client_id'] = "";
				$updateData['poa_status'] = "";	
			}
			
			$updateData['poa_text_status'] = ($this->input->post('poa_text_status') != "") ? $this->input->post('poa_text_status') : "";
			$updateData['segments'] = (!empty($this->input->post('segments'))) ? implode(",",$this->input->post('segments')) : "";
			$updateData['pending_issue'] = (!empty($this->input->post('pending_issue'))) ? implode(",",$this->input->post('pending_issue')) : "";
			$updateData['whatsapp_broadcast_list'] = (!empty($this->input->post('whatsapp_broadcast_list'))) ? implode(",",$this->input->post('whatsapp_broadcast_list')) : "";
			$updateData['payment_type'] = ($this->input->post('payment_type') != "") ? $this->input->post('payment_type') : "";
			
			if($this->input->post('payment_type') == 1){
				$updateData['start_date'] = ($this->input->post('start_date') != "") ? date("Y-m-d",strtotime($this->input->post('start_date'))) : "";
				$updateData['end_date'] = ($this->input->post('end_date') != "") ? date("Y-m-d",strtotime($this->input->post('end_date'))) : "";
				$updateData['current_amount'] = ($this->input->post('current_amount')) ? $this->input->post('current_amount') : "";
				$updateData['duration_type'] = ($this->input->post('duration_type')) ? $this->input->post('duration_type') : "";
				$updateData['duration_value'] = ($this->input->post('duration_value')) ? $this->input->post('duration_value') : "";
				$updateData['payment_for_segment'] = ($this->input->post('payment_for_segment')) ? $this->input->post('payment_for_segment') : "";
			}else{
				$updateData['start_date'] = "0000-00-00 00:00:00";
				$updateData['end_date'] = "0000-00-00 00:00:00";	
				$updateData['current_amount'] = "";
				$updateData['duration_type'] = "";
				$updateData['duration_value'] = "";
				$updateData['payment_for_segment'] = "";
			}
			
			$updateData['note'] = ($this->input->post('note') != "") ? $this->input->post('note') : "";
			$updateData['updated_date'] = date("Y-m-d H:i:s");
			
			if($this->input->post('is_new_payment_entry') == 1) {
				$updateData['last_payment_date'] = date("Y-m-d H:i:s");	
			}
			
			$res = $this->common_model->UPDATEDATA('contacts',array('id' => $this->input->post('contact_id')),$updateData);	
			
			if($res){
				
				if($this->input->post('payment_type') == 1) {
					if($this->input->post('is_new_payment_entry') == 1) {
						
						$insertPaymentLog = array();
						$insertPaymentLog['user_id'] = $this->input->post('contact_id');
						$insertPaymentLog['amount'] = ($this->input->post('current_amount')) ? $this->input->post('current_amount') : "";
						$insertPaymentLog['duration_type'] = ($this->input->post('duration_type')) ? $this->input->post('duration_type') : "";
						$insertPaymentLog['duration_value'] = ($this->input->post('duration_value')) ? $this->input->post('duration_value') : "";
						$insertPaymentLog['start_date'] = ($this->input->post('start_date') != "") ? date("Y-m-d",strtotime($this->input->post('start_date'))) : "";
						$insertPaymentLog['end_date'] = ($this->input->post('end_date') != "") ? date("Y-m-d",strtotime($this->input->post('end_date'))) : "";
						$insertPaymentLog['created_date'] = date("Y-m-d H:i:s");	
						$res = $this->common_model->INSERTDATA('payment_logs',$insertPaymentLog);
						
						$LastPlanLogCheck = $this->common_model->get_sql_select_data('plan_log' ,array('user_id' => $this->input->post('contact_id')),array('id','end_date','plan_type','start_date'),'1','id desc');
						
						if(!empty($LastPlanLogCheck) && $LastPlanLogCheck[0]['end_date'] == '0000-00-00' && $LastPlanLogCheck[0]['plan_type'] == 'Free') {
							
							$START_DATE = ($this->input->post('start_date') != "") ? date("Y-m-d",strtotime($this->input->post('start_date'))) : "";
							$PLAN_STARTDATE = date("Y-m-d",strtotime($LastPlanLogCheck[0]['start_date']));
							
							if($START_DATE <= $PLAN_STARTDATE) {
								$PLAN_ENDDATE = $START_DATE;	
							} else{
								$PLAN_ENDDATE = date("Y-m-d",strtotime('-1 day',strtotime($START_DATE)));	
							}
							
							$updatePlanLog = array();
				
							$updatePlanLog['end_date'] = $PLAN_ENDDATE;
							$res = $this->common_model->UPDATEDATA('plan_log',array('id' => $LastPlanLogCheck[0]['id']),$updatePlanLog);	
						} 
						
						$insertPlanLog = array();
				
						$insertPlanLog['user_id'] = $this->input->post('contact_id');
						$insertPlanLog['start_date'] = ($this->input->post('start_date') != "") ? date("Y-m-d",strtotime($this->input->post('start_date'))) : "";
						$insertPlanLog['end_date'] = ($this->input->post('end_date') != "") ? date("Y-m-d",strtotime($this->input->post('end_date'))) : "";
						$insertPlanLog['plan_type'] = "Paid";
						$insertPlanLog['created_date'] = date("Y-m-d H:i:s");	
						
						$res = $this->common_model->INSERTDATA('plan_log',$insertPlanLog);		
						
					} else {
						$LastPaymentLog = $this->common_model->get_sql_select_data('payment_logs' ,array('user_id' => $this->input->post('contact_id')),array('id'),'1','id desc');
						
						if(!empty($LastPaymentLog)){
							
							$updatePaymentLog = array();
						
							$updatePaymentLog['user_id'] = $this->input->post('contact_id');
							$updatePaymentLog['amount'] = ($this->input->post('current_amount')) ? $this->input->post('current_amount') : "";
							$updatePaymentLog['duration_type'] = ($this->input->post('duration_type')) ? $this->input->post('duration_type') : "";
							$updatePaymentLog['duration_value'] = ($this->input->post('duration_value')) ? $this->input->post('duration_value') : "";
							$updatePaymentLog['start_date'] = ($this->input->post('start_date') != "") ? date("Y-m-d",strtotime($this->input->post('start_date'))) : "";
							$updatePaymentLog['end_date'] = ($this->input->post('end_date') != "") ? date("Y-m-d",strtotime($this->input->post('end_date'))) : "";
							$updatePaymentLog['updated_date'] = date("Y-m-d H:i:s");	
							
							$res = $this->common_model->UPDATEDATA('payment_logs',array('id' => $LastPaymentLog[0]['id']),$updatePaymentLog);	
							
						} 
					}
				}
				
				if($IsContactExist[0]['payment_type'] != $this->input->post('payment_type')){
					if($IsContactExist[0]['payment_type'] == 1 && $this->input->post('payment_type') == 0){
						
						$LastPlanLogCheck = $this->common_model->get_sql_select_data('plan_log' ,array('user_id' => $this->input->post('contact_id')),array('id','end_date','plan_type','start_date'),'1','id desc');
						
						if(!empty($LastPlanLogCheck)) {
							
							$LAST_ID = $LastPlanLogCheck[0]['id'];
							$EDATE = date("Y-m-d",strtotime($LastPlanLogCheck[0]['end_date']));
							
							if($LastPlanLogCheck[0]['end_date'] != '0000-00-00' && date("Y-m-d") >= $EDATE){
								
								$insertPlanLog = array();
								$insertPlanLog['user_id'] = $this->input->post('contact_id');
								$insertPlanLog['start_date'] = date("Y-m-d");
								$insertPlanLog['end_date'] = "";
								$insertPlanLog['plan_type'] = "Free";
								$insertPlanLog['created_date'] = date("Y-m-d H:i:s");	
								$res = $this->common_model->INSERTDATA('plan_log',$insertPlanLog);			
								
							} else {
								
								$updatePlanLog = array();
								$updatePlanLog['end_date'] = date("Y-m-d");
								$res = $this->common_model->UPDATEDATA('plan_log',array('id' => $LAST_ID),$updatePlanLog);		
								
								$insertPlanLog = array();
								$insertPlanLog['user_id'] = $this->input->post('contact_id');
								$insertPlanLog['start_date'] = date("Y-m-d");
								$insertPlanLog['end_date'] = "";
								$insertPlanLog['plan_type'] = "Free";
								$insertPlanLog['created_date'] = date("Y-m-d H:i:s");	
								$res = $this->common_model->INSERTDATA('plan_log',$insertPlanLog);			
							} 
						}
					}
				}
				
				$status = true;
				$message = "Contact updated successfully.";
				$redirect = base_url().'contacts';
				$this->common_model->call_Function_redirect($status,$message,$redirect);		
			}else{
				$status = false;
				$message = "There is something wrong!";
				$this->common_model->call_Function($status,$message);
			}
		}    
	}
	
	public function ajaxChangeContactStatus(){
		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('userid','User Id','required');
		$this->form_validation->set_rules('is_active','Status','required');
		
		if($this->form_validation->run() == FALSE){
			$status = false;
			$message = validation_errors();
			$this->common_model->call_Function($status,$message);
		} 
		else 
		{
			$updateStatus['is_active'] = ($this->input->post('is_active')) ? $this->input->post('is_active') : '0';
			if($this->input->post('is_active') == 0) {
				$updateStatus['deactivated_date'] = date("Y-m-d H:i:s");	
			} else {
				$updateStatus['deactivated_date'] = '0000-00-00 00:00:00';	
			}
			
			$isSaved = $this->common_model->UPDATEDATA('contacts', array('id' => $this->input->post('userid')), $updateStatus);	
			
			if($isSaved){
				$status = true;
				$message = "Contact Status has been changed successfully.";
				$this->common_model->call_Function($status,$message);
			}else{
				$status = false;
				$message = "There is something wrong!";
				$this->common_model->call_Function($status,$message);
			}
		}			
	}
	
	public function ajaxDeleteContact()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('user_id','User Id','required');
		
		if($this->form_validation->run() == FALSE)
		{
			$status = false;
			$message = validation_errors();
			$this->common_model->call_Function($status,$message);
		} 
		else 
		{
			$updateData['updated_date'] = date("Y-m-d H:i:s");
			$updateData['is_deleted'] = '1';
			$updateData['deleted_date'] = date("Y-m-d H:i:s");
			
			$res = $this->common_model->UPDATEDATA('contacts', array('id' => $this->input->post('user_id')), $updateData);	
			
			if($res)
			{					
				$status = true;
				$message = "Contact has been deleted successfully";
				$redirect = base_url().'contacts';
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
	
	public function ajaxGetContactDetails()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('userid','User Id','required');
		
		if($this->form_validation->run() == FALSE)
		{
			$status = false;
			$message = validation_errors();
			$this->common_model->call_Function($status,$message);
		} 
		else 
		{
			$res = $this->common_model->get_sql_select_data('contacts', array('id' => $this->input->post('userid')));	
			
			header('Content-Type:text/json');
			echo json_encode($res[0]);
			exit;
		}    
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */