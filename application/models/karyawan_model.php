<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Karyawan_model extends CI_Model {
	var $column_order = array('nik','foto','namaKaryawan','dep/subSec','sec/Group','tanggalMasuk','jk','statusKaryawan','namaGrade','jabatan'); //set column field database for datatable orderable
    var $column_search = array('nik','foto','namaKaryawan','dep/subSec','sec/Group','tanggalMasuk','jk','statusKaryawan','namaGrade','jabatan'); //set column field database for datatable searchable 
    var $order = array('nik' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_data_karyawan()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

	private function _get_datatables_query()
    {
        $this->db->select("pin, nik, costCenter, foto, namaKaryawan, dep/subSec as dep, sec/Group as group, kode, tanggalMasuk, jk, statusKaryawan, grade, namaGrade, jabatan, statusKeluarga, tanggalLahir, tempatLahir, alamat, hp, ktp, rekening, bpjstk, jp, bpjskes, npwp");
        $this->db->from('karyawan');
 
        $i = 0;
     
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from('karyawan');
        return $this->db->count_all_results();
    }

    public function get_data_karyawan_by_nik($nik)
    {
        $this->db->select("pin, nik, costCenter, foto, namaKaryawan, dep/subSec as dep, sec/Group as group, kode, tanggalMasuk, jk, statusKaryawan, grade, namaGrade, jabatan, statusKeluarga, tanggalLahir, tempatLahir, alamat, hp, ktp, rekening, bpjstk, jp, bpjskes, npwp");
        $this->db->from('karyawan');
        $this->db->where('nik',$nik);
        $query = $this->db->get();
        return $query->result();
    }
}
?>