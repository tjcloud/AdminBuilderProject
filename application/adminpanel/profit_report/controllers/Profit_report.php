<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH . 'libraries/ImplementJWT.php');
class Profit_report extends MX_Controller {

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
		$this->load->model('profit_report_model');
		$this->objOfJwt = new ImplementJwt;
    }
	
	public function index()
	{
		$data['company_lot'] = $this->common_model->get_sql_select_data('company_lot');
		$this->template->build('index',$data);      
	}
	
	private function clean($string) {
	   //$string = str_replace(' ','-', $string); // Replaces all spaces with hyphens.
	   $string = preg_replace('/[^A-Za-z0-9 \-]/', '', $string); // Removes special chars.

	   return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
	}
	
	public function ajaxAddCSV(){
		
		$delete_lot_month = date("m",strtotime('-2 month'));
		$month_report = $this->input->post('profit_month');
		
		$fileName = $_FILES["CSV_file"]["tmp_name"];
		$file_type = $_FILES["CSV_file"]["name"];
		
		$FNAME = explode(".",$file_type);
		
		if($FNAME[1] != 'csv' && $FNAME[1] != 'CSV'){
			$status = false;
			$message = "Please upload valid csv file!";
			$this->common_model->call_Function($status,$message);
		}
		
		$Clients_Code_CSV = array();
		$isSaved = 0;
		
		if ($_FILES["CSV_file"]["size"] > 0) {
			
			$old_company_lot = $this->common_model->get_sql_select_data('company_lot',array('month_report' => $delete_lot_month));
			
			if(!empty($old_company_lot)) {
				$res = $this->common_model->DELETEDATA('company_lot',array('month_report' => $delete_lot_month));
			}
			
			$file = fopen($fileName, "r");
			$i = 0;
			while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
				$CompanyName = "";
				$LotSize = "";
				
				if($i > 0){
					if($column[0] != "" && $column[1] != ""){
						$CompanyName = $this->clean($column[0]);
						$LotSize = $this->clean($column[1]);
						
						$insertLot['company_name'] = $CompanyName;
						$insertLot['lot_size'] = $LotSize;
						$insertLot['created_date'] = date("Y-m-d H:i:s");
						$insertLot['month_report'] = $month_report;
						$isSaved = $this->common_model->INSERTDATA('company_lot',$insertLot);
					}	
				}
				$i++;
			}
		}
		
		if($isSaved){	
			$status = true;
			$message = "Company LOT imported succesfully.";
			$redirect = base_url().'profit_report';
			$this->common_model->call_Function_redirect($status,$message,$redirect);		
		}else{
			$status = false;
			$message = "There is something wrong!";
			$this->common_model->call_Function($status,$message);
		}	   
	}
	
	public function ajaxEditCompanyLot()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('company_name','Company Name','required');
		$this->form_validation->set_rules('lot_size','Lot Size','required');
		
		if($this->form_validation->run() == FALSE)
		{
			$status = false;
			$message = validation_errors();
			$this->common_model->call_Function($status,$message);
		} 
		else 
		{
			$company_name = $this->input->post('company_name');
			
			$old_company_lot = $this->common_model->get_sql_select_data('company_lot',array('id' => $company_name));
			
			if(!empty($old_company_lot)){
				$updateData['lot_size'] = ($this->input->post('lot_size')) ? $this->input->post('lot_size') : "0";
				$updateData['updated_date'] = date("Y-m-d H:i:s");
				
				$res = $this->common_model->UPDATEDATA('company_lot', array('id' => $company_name), $updateData);	
				
				if($res){					
					$status = true;
					$message = "Company lot updated succesfully.";
					$redirect = base_url().'profit_report';
					$this->common_model->call_Function_redirect($status,$message,$redirect);		
				} else {
					$status = false;
					$message = "There is something wrong!";
					$this->common_model->call_Function($status,$message);
				}
			} else {
				$status = false;
				$message = "Company name not found!";
				$this->common_model->call_Function($status,$message);
			}
		}    
	}
	
	public function ajaxSearchCompany($query = ''){
		if($query != ""){
			$query = urldecode($query);
			$search_query = $query;	
			
			$company_lots = $this->common_model->get_sql_select_data('company_lot','company_name like "%'.$search_query.'%"');
			
			$returnArray = array();
			
			foreach($company_lots as $company_lots_data){
				$month_selected = "";
				$month_selected = date("M",strtotime(date("d-".$company_lots_data['month_report'].'-Y')));
				
				$tempreturnArray['month'] = $month_selected;
				$tempreturnArray['company_name_text'] = $company_lots_data['company_name'];
				$tempreturnArray['company_name'] = $company_lots_data['company_name'].' - '.$month_selected;
				$tempreturnArray['lot_size'] = $company_lots_data['lot_size'];
				
				array_push($returnArray,$tempreturnArray);
			}
			
			header('Content-Type:text/json');
			echo json_encode($returnArray);
			exit;
		}
	}
	
	public function ajaxCalculateProfit()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('report_date','Report Date','required');
		$this->form_validation->set_rules('overall_total','Grand Total','required');
		
		if($this->form_validation->run() == FALSE)
		{
			$status = false;
			$message = validation_errors();
			$this->common_model->call_Function($status,$message);
		} 
		else 
		{
			$insertProfitReport['common_lot'] = time();
			$insertProfitReport['report_date'] = date("Y-m-d H:i:s",strtotime($this->input->post('report_date')));
			$insertProfitReport['grand_total'] = $this->input->post('overall_total');
			$insertProfitReport['created_date'] = date("Y-m-d H:i:s");
			$insertProfitReport['report_from'] = $this->input->post('report_from');
			$insertProfitReport['report_segment'] = $this->input->post('report_segment');
			
			$checkPreviousLog = $this->common_model->get_sql_select_data('profit_report',array('report_date' => $insertProfitReport['report_date'],'report_segment' => $insertProfitReport['report_segment']),array('id'));
			
			if(!empty($checkPreviousLog)){
				$res = $this->common_model->DELETEDATA('profit_report',array('report_date'=> $insertProfitReport['report_date'],'report_segment' => $insertProfitReport['report_segment']));
			}
			
			for($i = 0;$i<$this->input->post('total_row_added');$i++) {
				
				if(isset($this->input->post('company_name_report')[$i]) && $this->input->post('company_name_report')[$i] != "") {
					
					$insertProfitReport['company_name'] = ($this->input->post('company_name_report')[$i] != "") ? $this->input->post('company_name_report')[$i] : "";
					$insertProfitReport['buy_sell'] = ($this->input->post('buy_sell')[$i] != "") ? $this->input->post('buy_sell')[$i] : "";
					$insertProfitReport['entry_price'] = ($this->input->post('entry_price')[$i] != "") ? $this->input->post('entry_price')[$i] : "";
					$insertProfitReport['entry_start_date'] = ($this->input->post('entry_start_date')[$i] != "") ? date("Y-m-d",strtotime($this->input->post('entry_start_date')[$i])) : "";
					$insertProfitReport['exit_price'] = ($this->input->post('exit_price')[$i] != "") ? $this->input->post('exit_price')[$i] : "";
					$insertProfitReport['exit_end_date'] = ($this->input->post('exit_end_date')[$i] != "") ? date("Y-m-d",strtotime($this->input->post('exit_end_date')[$i])) : "";
					$insertProfitReport['sl_price'] = ($this->input->post('sl_price')[$i] != "") ? $this->input->post('sl_price')[$i] : "";
					$insertProfitReport['target_price'] = ($this->input->post('target_price')[$i] != "") ? $this->input->post('target_price')[$i] : "";
					$insertProfitReport['lot_size'] = ($this->input->post('lot_size')[$i] != "") ? $this->input->post('lot_size')[$i] : "";
					$insertProfitReport['final_amount'] = ($this->input->post('final_amount')[$i] != "") ? $this->input->post('final_amount')[$i] : "";
					$res = $this->common_model->INSERTDATA('profit_report',$insertProfitReport);		
				}	
			}
			
			if($res){					
				$status = true;
				$message = "Profit Loss data inserted succesfully.";
				$redirect = base_url().'profit_report';
				$this->common_model->call_Function_redirect($status,$message,$redirect);		
			} else {
				$status = false;
				$message = "There is something wrong!";
				$this->common_model->call_Function($status,$message);
			}
		}    
	}
	
	public function ajaxEditExitPrice(){
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('record_id','ID','required');
		$this->form_validation->set_rules('exit_price_edit','Exit Price','required');
		$this->form_validation->set_rules('exit_end_date_edit','End Date','required');
		
		if($this->form_validation->run() == FALSE)
		{
			$status = false;
			$message = validation_errors();
			$this->common_model->call_Function($status,$message);
		} 
		else 
		{
			$checkPreviousData = $this->common_model->get_sql_select_data('profit_report',array('id' => $this->input->post('record_id')));
			
			if(!empty($checkPreviousData)){
				
				$BuySell = $checkPreviousData[0]['buy_sell'];
				$EntryPrice = $checkPreviousData[0]['entry_price'];
				$ExitPrice = $this->input->post('exit_price_edit');
				$lotSize = $checkPreviousData[0]['lot_size'];
				$common_lot_id = $checkPreviousData[0]['common_lot'];
				
				if($BuySell == 'Buy') {
					$final_amount1 = $ExitPrice - $EntryPrice;
					$final_amount = round($final_amount1 * $lotSize,2);
				}else if($BuySell == 'Sell') {
					$final_amount1 = $EntryPrice - $ExitPrice;
					$final_amount = round($final_amount1 * $lotSize,2);
				}
				
				$updateExitPrice['final_amount'] = (float)$final_amount;
				$updateExitPrice['exit_end_date'] = date("Y-m-d H:i:s",strtotime($this->input->post('exit_end_date_edit')));
				$updateExitPrice['exit_price'] = $this->input->post('exit_price_edit');
				$updateExitPrice['updated_date'] = date("Y-m-d H:i:s");
				
				$res = $this->common_model->UPDATEDATA('profit_report',array('id' => $this->input->post('record_id')),$updateExitPrice);		
				
				if($res){		
					$updateGrandTotal = $this->common_model->get_sql_select_data('profit_report',array('common_lot' => $common_lot_id),array('SUM(final_amount) as final_total'));
					$GrandTotal = 0;
					if(!empty($updateGrandTotal)){
						$GrandTotal = $updateGrandTotal[0]['final_total'];
					} 
					$updateGrandTotalData['grand_total'] = $GrandTotal;
					$res2 = $this->common_model->UPDATEDATA('profit_report',array('common_lot' => $common_lot_id),$updateGrandTotalData);			
					
					if($res2){
						$status = true;
						$message = "Exit Price updated succesfully.";
						$this->common_model->call_Function($status,$message);			
					}else{
						$status = false;
						$message = "There is something wrong!";
						$this->common_model->call_Function($status,$message);
					}					
				} else {
					$status = false;
					$message = "There is something wrong!";
					$this->common_model->call_Function($status,$message);
				}	
			}else{
				$status = false;
				$message = "There is something wrong!";
				$this->common_model->call_Function($status,$message);
			}
		}  
	}
	
	public function ajaxGenerateReport() {
		
		$Type = $this->input->post('type');
		$WHERE = '1=1';
		if($Type == 'Monthly') {
			$start_date = date("Y-m-01");
			$end_date = date("Y-m-d");
			$WHERE .= ' AND report_date between "'.$start_date.'" AND "'.$end_date.'"';
		} else if($Type == 'Weekly') {
			$start_date = date("Y-m-d",strtotime('monday this week'));
			$end_date = date("Y-m-d");
			$WHERE .= ' AND report_date between "'.$start_date.'" AND "'.$end_date.'"';
		}
		
		if($this->input->post('report_segment_filter') != "") {
			$WHERE .= ' AND report_segment="'.$this->input->post('report_segment_filter').'"';
		}
		
		if($this->input->post('report_se_date') != "") {
			
			$NormalDate = $this->input->post('report_se_date');
			
			if(!strpos($NormalDate,"to")){
				$EndDate  = date("Y-m-d",strtotime($NormalDate));
				$StartDate = date("Y-m-d",strtotime($NormalDate));
			}else{
				$date=explode(" to ",$NormalDate);
				$EndDate  = date("Y-m-d",strtotime($date[1]));
				$StartDate = date("Y-m-d",strtotime($date[0]));
			}
			
			$WHERE .= ' AND entry_start_date != "" AND entry_start_date between "'.$StartDate.'" AND "'.$EndDate.'"';
		}
		
		$GetProfitData = $this->common_model->get_sql_select_data('profit_report',$WHERE,'','','report_date asc');
		
		$html = "";
		
		if($this->input->post('report_segment_filter') != "" && $this->input->post('report_segment_filter') == 'Positional'){
			
			$open_close = $this->input->post('status_val');
			
			if(!empty($GetProfitData)){		
				foreach($GetProfitData as $GetProfitDataDetails) {
					if($open_close != "" && $open_close == 'Open' && $GetProfitDataDetails['exit_end_date'] == '0000-00-00') {
						$html .= '<tr class="give_color">';
							$html .= '<td class="text-center">'.date("d-m-Y",strtotime($GetProfitDataDetails['report_date'])).'</td>';
							
							if($GetProfitDataDetails['exit_end_date'] == '0000-00-00'){
								$html .= '<td class="text-center">Open</td>';
							} else {
								$html .= '<td class="text-center">Close</td>';	
							}
							
							$html .= '<td class="text-center">'.$GetProfitDataDetails['company_name'].'</td>';
							$html .= '<td class="text-center">'.$GetProfitDataDetails['buy_sell'].'</td>';
							$html .= '<td class="text-center">'.$GetProfitDataDetails['entry_price'].' <br>('.date("d-m-Y",strtotime($GetProfitDataDetails['entry_start_date'])).')</td>';
							
							if($GetProfitDataDetails['exit_end_date'] == '0000-00-00') {
								if($GetProfitDataDetails['exit_price'] == 0){
									$html .= '<td class="text-center"><a href="javascript:void(0);" class="waves-effect waves-light change_in_image" onclick="edit_exit_price(\''.$GetProfitDataDetails['id'].'\',\''.$GetProfitDataDetails['sl_price'].'\',\''.$GetProfitDataDetails['target_price'].'\');"><i class="fa fa-pencil"></i></a>';	
								} else {
									$html .= '<td class="text-center">'.$GetProfitDataDetails['exit_price'].' <br> <a href="javascript:void(0);" class="waves-effect waves-light change_in_image" onclick="edit_exit_price(\''.$GetProfitDataDetails['id'].'\',\''.$GetProfitDataDetails['sl_price'].'\',\''.$GetProfitDataDetails['target_price'].'\');"><i class="fa fa-pencil"></i></a>';	
								} 	 
							} else {
								$html .= '<td class="text-center">'.$GetProfitDataDetails['exit_price'].' <br>('.date("d-m-Y",strtotime($GetProfitDataDetails['exit_end_date'])).')</td>';	
							}
							
							$html .= '<td class="text-center">'.$GetProfitDataDetails['lot_size'].'</td>';
							
							if($GetProfitDataDetails['final_amount'] == 0){
								$html .= '<td class="text-center change_in_image_finaltotal">'.$GetProfitDataDetails['final_amount'].'</td>';
							} else {
								$html .= '<td class="text-center">'.$GetProfitDataDetails['final_amount'].'</td>';
							}
							
							$html .= '<td class="text-center">'.$GetProfitDataDetails['grand_total'].'</td>';
						$html .= '</tr>';
					} else if($open_close != "" && $open_close == 'Close' && $GetProfitDataDetails['exit_end_date'] != '0000-00-00') {
						$html .= '<tr class="give_color">';
							$html .= '<td class="text-center">'.date("d-m-Y",strtotime($GetProfitDataDetails['report_date'])).'</td>';
							
							if($GetProfitDataDetails['exit_end_date'] == '0000-00-00'){
								$html .= '<td class="text-center">Open</td>';
							} else {
								$html .= '<td class="text-center">Close</td>';	
							}
							
							$html .= '<td class="text-center">'.$GetProfitDataDetails['company_name'].'</td>';
							$html .= '<td class="text-center">'.$GetProfitDataDetails['buy_sell'].'</td>';
							$html .= '<td class="text-center">'.$GetProfitDataDetails['entry_price'].' <br>('.date("d-m-Y",strtotime($GetProfitDataDetails['entry_start_date'])).')</td>';
							
							if($GetProfitDataDetails['exit_end_date'] == '0000-00-00') {
								if($GetProfitDataDetails['exit_price'] == 0){
									$html .= '<td class="text-center"><a href="javascript:void(0);" class="waves-effect waves-light change_in_image" onclick="edit_exit_price(\''.$GetProfitDataDetails['id'].'\',\''.$GetProfitDataDetails['sl_price'].'\',\''.$GetProfitDataDetails['target_price'].'\');"><i class="fa fa-pencil"></i></a>';	
								} else {
									$html .= '<td class="text-center">'.$GetProfitDataDetails['exit_price'].' <br> <a href="javascript:void(0);" class="waves-effect waves-light change_in_image" onclick="edit_exit_price(\''.$GetProfitDataDetails['id'].'\',\''.$GetProfitDataDetails['sl_price'].'\',\''.$GetProfitDataDetails['target_price'].'\');"><i class="fa fa-pencil"></i></a>';	
								} 	 
							} else {
								$html .= '<td class="text-center">'.$GetProfitDataDetails['exit_price'].' <br>('.date("d-m-Y",strtotime($GetProfitDataDetails['exit_end_date'])).')</td>';	
							}
							
							$html .= '<td class="text-center">'.$GetProfitDataDetails['lot_size'].'</td>';
							
							if($GetProfitDataDetails['final_amount'] == 0){
								$html .= '<td class="text-center change_in_image_finaltotal">'.$GetProfitDataDetails['final_amount'].'</td>';
							} else {
								$html .= '<td class="text-center">'.$GetProfitDataDetails['final_amount'].'</td>';
							}
							
							$html .= '<td class="text-center">'.$GetProfitDataDetails['grand_total'].'</td>';
						$html .= '</tr>';	
					} else if($open_close == ""){
						$html .= '<tr class="give_color">';
							$html .= '<td class="text-center">'.date("d-m-Y",strtotime($GetProfitDataDetails['report_date'])).'</td>';
							
							if($GetProfitDataDetails['exit_end_date'] == '0000-00-00'){
								$html .= '<td class="text-center">Open</td>';
							} else {
								$html .= '<td class="text-center">Close</td>';	
							}
							
							$html .= '<td class="text-center">'.$GetProfitDataDetails['company_name'].'</td>';
							$html .= '<td class="text-center">'.$GetProfitDataDetails['buy_sell'].'</td>';
							$html .= '<td class="text-center">'.$GetProfitDataDetails['entry_price'].' <br>('.date("d-m-Y",strtotime($GetProfitDataDetails['entry_start_date'])).')</td>';
							
							if($GetProfitDataDetails['exit_end_date'] == '0000-00-00') {
								if($GetProfitDataDetails['exit_price'] == 0){
									$html .= '<td class="text-center"><a href="javascript:void(0);" class="waves-effect waves-light change_in_image" onclick="edit_exit_price(\''.$GetProfitDataDetails['id'].'\',\''.$GetProfitDataDetails['sl_price'].'\',\''.$GetProfitDataDetails['target_price'].'\');"><i class="fa fa-pencil"></i></a>';	
								} else {
									$html .= '<td class="text-center">'.$GetProfitDataDetails['exit_price'].' <br> <a href="javascript:void(0);" class="waves-effect waves-light change_in_image" onclick="edit_exit_price(\''.$GetProfitDataDetails['id'].'\',\''.$GetProfitDataDetails['sl_price'].'\',\''.$GetProfitDataDetails['target_price'].'\');"><i class="fa fa-pencil"></i></a>';	
								} 	 
							} else {
								$html .= '<td class="text-center">'.$GetProfitDataDetails['exit_price'].' <br>('.date("d-m-Y",strtotime($GetProfitDataDetails['exit_end_date'])).')</td>';	
							}
							
							$html .= '<td class="text-center">'.$GetProfitDataDetails['lot_size'].'</td>';
							
							if($GetProfitDataDetails['final_amount'] == 0){
								$html .= '<td class="text-center change_in_image_finaltotal">'.$GetProfitDataDetails['final_amount'].'</td>';
							} else {
								$html .= '<td class="text-center">'.$GetProfitDataDetails['final_amount'].'</td>';
							}
							
							$html .= '<td class="text-center">'.$GetProfitDataDetails['grand_total'].'</td>';
						$html .= '</tr>';	
					}
				}
			}else{
				$html .= '<tr>';
					$html .= '<td class="text-center" colspan="9">No data found.</td>';
				$html .= '</tr>';
			}
		} else {
			if(!empty($GetProfitData)){		
				foreach($GetProfitData as $GetProfitDataDetails) {
					$html .= '<tr class="give_color">';
						$html .= '<td class="text-center">'.date("d-m-Y",strtotime($GetProfitDataDetails['report_date'])).'</td>';
						$html .= '<td class="text-center">'.$GetProfitDataDetails['company_name'].'</td>';
						$html .= '<td class="text-center">'.$GetProfitDataDetails['buy_sell'].'</td>';
						$html .= '<td class="text-center">'.$GetProfitDataDetails['entry_price'].'</td>';
						$html .= '<td class="text-center">'.$GetProfitDataDetails['exit_price'].'</td>';
						$html .= '<td class="text-center">'.$GetProfitDataDetails['lot_size'].'</td>';
						$html .= '<td class="text-center">'.$GetProfitDataDetails['final_amount'].'</td>';
						$html .= '<td class="text-center">'.$GetProfitDataDetails['grand_total'].'</td>';
					$html .= '</tr>';
				}
			}else{
				$html .= '<tr>';
					$html .= '<td class="text-center" colspan="8">No data found.</td>';
				$html .= '</tr>';
			}	
		}
		
		echo $html;
		exit;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */