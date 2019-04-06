<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function login($nik, $pass)
    {
        $this->db->select("username, password, role");
        $this->db->from('login2');
        $this->db->where('username',$nik);
        $this->db->where('password',$pass);

        $query = $this->db->get();
        return $query->num_rows();
    }

    public function login_data($nik, $pass)
    {
        $this->db->select("username, password, role, karyawan.namaKaryawan");
        $this->db->from('login2');
        $this->db->join('karyawan','login2.username = karyawan.nik','left');
        $this->db->where('username',$nik);
        $this->db->where('password',$pass);

        $query = $this->db->get();
        return $query->result();
    }
}
?>