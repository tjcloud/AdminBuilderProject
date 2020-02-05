<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Profit_report_model extends CI_Model {
	
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
	
	function getReportData()
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
	
}
?>