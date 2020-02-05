<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Payment_report_model extends CI_Model {
	
	public function __construct(){
        parent::__construct();
    }
	
	function getPaymentLogs($WHERE = null)
	{
		$returnData = array();
		
		$reqorder = "select payment_logs.*,contacts.contact_name,contacts.contact_no,contacts.trade_company_text,contacts.client_id,contacts.segments from payment_logs left join contacts on (contacts.id = payment_logs.user_id) where ".$WHERE." order by payment_logs.created_date desc";
		
		$query = $this->db->query($reqorder);
		if($query->num_rows()){
			$returnData = $query->result_array();
		}
		
		return $returnData;
	}
	
}
?>