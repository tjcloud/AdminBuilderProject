<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Home_model extends CI_Model {
	
	function userLogin($data)
	{
		$email = $data['email'];
		$password = $data['password'];
		/******admin login******/
		$return = '';
		
		$fecthkemail = $this->db->query("SELECT * FROM admin WHERE email = '$email'");
		
		if($fecthkemail->num_rows() > 0)
		{
			$check_pass = "SELECT * FROM admin WHERE email = '$email' and password = '$password'";
			$fetch_pass = $this->db->query($check_pass);
			
			if($fetch_pass->num_rows() > 0)
			{	
				$fetch_data = $fetch_pass->row();
				
				if($fetch_data->status == 0){
					$return = "NA"; // Not Activate
				} else if($fetch_data->status == 1)
				{
					$userdata = array(
						'login_id'=>$fetch_data->id,
						'username' => $fetch_data->first_name.' '.$fetch_data->last_name,
						'user_type' => $fetch_data->user_type,
						'login_email'=>$fetch_data->email);
					$this->session->set_userdata($userdata);
					$return = "success";
					
					$upd_last = $this->db->query("update admin set lastlogin_date = '".date("Y-m-d H;i:s")."' WHERE id = '".$fetch_data->id."'");
					
				} else{
					$return = "BL"; // Block
				}	
			} else{
				$return = "WP"; // wrong password
			}	
		} else{
			$return = "WE"; // wrong email
		}	
		
		return $return;
	}
	
	public function forgotPassword($data)
	{
		$email = $data['email'];
		
		$return = '';
		
		$fecthkemail = $this->db->query("SELECT id,first_name,last_name,email,status FROM admin WHERE email = '$email'");
		
		if($fecthkemail->num_rows() > 0)
		{
			$fetch_data = $fecthkemail->row();
			
			if($fetch_data->status == 0)
			{
				$return = "NA"; // Not Activate
			} 
			else if($fetch_data->status == 1)
			{
				$upd_last = $this->db->query("update admin set password = '".$data['password']."', updated_at='".date("Y-m-d H:i:s")."' WHERE id = '".$fetch_data->id."'");
				$return = array("name" => $fetch_data->first_name.$fetch_data->last_name, "email" => $fetch_data->email);
			} 
			else
			{
				$return = "BL"; // Block
			}	
		} else{
			$return = "WE"; // wrong email
		}	
		
		return $return;
	}
}
?>