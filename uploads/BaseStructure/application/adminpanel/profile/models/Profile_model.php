<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Profile_model extends CI_Model {
	
	public function __construct(){
        parent::__construct();
    }
	
	function getAdminDetails($product_color_id)
	{
		$result = array();
		 
		$q = $this->db->select("*")
					->where('id',$product_color_id)
					->get('admin');
					
		if($q->num_rows() > 0){
			$result = $q->result();
			return $result;		
		}else{
			return $result;		
		}
	}
	
	function editProfile($modelData)
	{
		$returnData = array();
		
		$q = $this->db->select("id")
					->where('id',$modelData['id'])
					->get('admin');
		
		if($q->num_rows() > 0)			
		{
			$where = array("id" => $modelData['id']);
			
			$updateData['email'] = $modelData['email'];
			$updateData['updated_at'] = $modelData['updated_at'];
			
			$userdata = array(
				'login_email'=>$updateData['email']);
			$this->session->set_userdata($userdata);
			
			$response = $this->db->update('admin',$updateData,$where);
			
			if($response)
			{
				$returnData['status'] = true;
				return $returnData;
			}
			else
			{
				return false;
			}	
		}
		else
		{
			return false;
		}
	}

	function editPassword($modelData)
	{
		$returnData = array();
		
		$q = $this->db->select("id")
					->where('id',$modelData['id'])
					->get('admin');
		
		if($q->num_rows() > 0)			
		{
			$where = array("id" => $modelData['id']);
			
			$updateData['password'] = $modelData['password'];
			$updateData['updated_at'] = $modelData['updated_at'];
			
			$response = $this->db->update('admin',$updateData,$where);
			
			if($response)
			{
				$returnData['status'] = true;
				return $returnData;
			}
			else
			{
				return false;
			}	
		}
		else
		{
			return false;
		}
	}	
}
?>