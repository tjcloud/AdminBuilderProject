<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Dashboard_model extends CI_Model 
{
	
	function getPlanExpiryRecords()
	{
		$returnData = array();
		
		$reqorder = "SELECT contacts.*,DATEDIFF(`end_date`,'".date("Y-m-d")."') AS DateDiff from contacts where payment_type = '1' AND is_deleted='0' AND (pending_issue = '' OR pending_issue IS NULL) having DateDiff <= 5 order by DateDiff asc";
		
		$query = $this->db->query($reqorder);
		if($query->num_rows()){
			$returnData = $query->result_array();
		}
		return $returnData;
	}
	
	function getPlanExpiryRecordsFilter($WHERE = '')
	{
		$returnData = array();
		
		$reqorder = "SELECT contacts.*,DATEDIFF(`end_date`,'".date("Y-m-d")."') AS DateDiff from contacts where 1=1 ".$WHERE." having DateDiff <= 5 order by DateDiff asc";
		
		$query = $this->db->query($reqorder);
		if($query->num_rows()){
			$returnData = $query->result_array();
		}
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
	
	public static function array_orderby()
	{
		$args = func_get_args();
		$data = array_shift($args);
		foreach ($args as $n => $field) {
			if (is_string($field)) {
				$tmp = array();
				foreach ($data as $key => $row)
					$tmp[$key] = $row[$field];
				$args[$n] = $tmp;
				}
		}
		$args[] = &$data;
		call_user_func_array('array_multisort', $args);
		return array_pop($args);
	}
	public function InsertProjectData($project_name,$database_name,$url)
	{
		return $this->db->insert('projects',['project_name'=>$project_name,'database_name'=>$database_name,'site_url'=>$url]);
	}
	public function PorjectExists($project_name)
	{
		$q = $this->db->select('*')
					->from('projects')
					->where('project_name',$project_name)
					->get();
		return $q->result_array();
	}
}
?>