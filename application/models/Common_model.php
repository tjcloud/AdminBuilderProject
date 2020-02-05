<?php
class Common_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
	
	public function get_sql_select_data($tablename, $where = '', $feild = '', $limit = '', $order_by = '',$group_by = '', $like = '', $orwhere = '') {
  
        if (!empty($feild))
            $this->db->select($feild);
        if (empty($feild))
            $this->db->select();
        if (!empty($where))
            $this->db->where($where);
        if (!empty($orwhere))
            $this->db->or_where($orwhere);
        if (!empty($limit))
            $this->db->limit($limit);
        if (!empty($like))
            $this->db->like($like);
        if (!empty($order_by))
            $this->db->order_by($order_by);

        if (!empty($group_by))
            $this->db->group_by($group_by );
        $this->db->from($tablename);
        $query = $this->db->get();

        return $query->result_array();
    }
	
	public function DELETEDATA($tablename = '', $where = '') {
        if (!empty($tablename) || !empty($where)):
            $this->db->where($where);
            $this->db->delete($tablename);
        else: return "Invalid Input Provided";
        endif;
    }
	
	public function UPDATEDATA($tablename, $where = '', $feild = '') {
        if (!empty($tablename) || !empty($feild)):
            $this->db->where($where);
            $this->db->update($tablename, $feild);
	    return $this->db->affected_rows();;
        else: return "Invalid Input Provided";
        endif;
    }
	public function INSERTDATA($tablename, $feild = '') {
        #echo '<pre>';print_r($feild);
        #die;
        if (!empty($tablename) || !empty($feild)):
            $this->db->set($feild);
            $insert = $this->db->insert($tablename);
            if ($insert):
                return $this->db->insert_id();

            endif;
        else: return "Invalid Input Provided";
        endif;
    }
	
	function call_Function($s,$m)
	{
		$dataResponse = array(	
								'status' => $s,
								'message' => $m,
							);	
		header('Content-Type:text/json');
		echo json_encode($dataResponse);
		exit;
	}
	
	function call_Function_redirect($s,$m,$redirect)
	{
		$dataResponse = array(	
								'status' => $s,
								'message' => $m,
								'redirect' => $redirect,
							);	
		header('Content-Type:text/json');
		echo json_encode($dataResponse);
		exit;
	}
    function directory_copy($srcdir, $dstdir)
    {
        //preparing the paths
        $srcdir=rtrim($srcdir,'/');
        $dstdir=rtrim($dstdir,'/');

        //creating the destination directory
        if(!is_dir($dstdir))mkdir($dstdir, 0777, true);
        
        //Mapping the directory
        $dir_map=directory_map($srcdir);

        foreach($dir_map as $object_key=>$object_value)
        {
            if(is_numeric($object_key))
                copy($srcdir.'/'.$object_value,$dstdir.'/'.$object_value);//This is a File not a directory
            else
                $this->directory_copy($srcdir.'/'.$object_key,$dstdir.'/'.$object_key);//this is a directory
        }
    }
}
