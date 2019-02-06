<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
    }

    public function get_data_client($id)
    {
        $this->db->where("nik",$id);
        $query = $this->db->get("karyawan");

        return $query->result();  
    }
}
?>