<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class main_management_model extends CI_Model 
{
	// public function InsertProjectData($project_name,$database_name,$url)
	// {
	// 	$this->db->insert('projects',['project_name'=>$project_name,'database_name'=>$database_name,'site_url'=>$url]);
	// 	return  $insert_id;
	// }
	public function Porjects($project_name)
	{
		$q = $this->db->select('*')
					->from('projects')
					->where('id',$project_name)
					->get();
		return $q->result_array();
	}
}
?>