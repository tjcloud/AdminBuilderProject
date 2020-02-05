<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class project_management_model extends CI_Model 
{
	public function InsertProjectData($project_name,$database_name,$url)
	{
		$date = date("Y:m:d h:i:s");
		$this->db->insert('projects',['project_name'=>$project_name,'database_name'=>$database_name,'site_url'=>$url,'created_at'=>$date]);
		return  $this->db->insert_id();
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