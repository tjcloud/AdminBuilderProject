<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Attendance_model extends CI_Model {
	
	public function __construct(){
        parent::__construct();
    }
	
	function getCustomerList()
	{
		$returnData = array();
		
		$model_array = array();
		
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('users.is_deleted','0');
		$this->db->order_by('user_id','desc');
		
		$query = $this->db->get();
		$returnData = $query->result();
			
		return $returnData;
	}
	
	function getTotalTredingDays($Sdate,$Edate){
		$returnData = 0;
		$reqorder = "select id from attandance_report where attandance_date between '".$Sdate."' AND '".$Edate."' group by attandance_date";
		$query = $this->db->query($reqorder);
		if($query->num_rows()){
			$returnData = $query->num_rows();
		}
		return $returnData;
	}
	
	function getTotalTradeDays($contactId,$Sdate,$Edate){
		$returnData = 0;
		$reqorder = "select id from attandance_report where user_id = '".$contactId."' AND is_attend='1' AND attandance_date between '".$Sdate."' AND '".$Edate."' group by attandance_date";
		$query = $this->db->query($reqorder);
		if($query->num_rows()){
			$returnData = $query->num_rows();
		}
		return $returnData;
	}
}
?>