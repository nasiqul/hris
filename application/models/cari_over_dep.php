<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cari_over_dep extends CI_Model {
	var $column_order = array('over.nik', 'karyawan.namaKaryawan','over.tanggal','departemen.nama','jam'); //set column field database for datatable orderable
    var $column_search = array('over.nik', 'karyawan.namaKaryawan','over.tanggal','departemen.nama'); //set column field database for datatable searchable 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_data_ot_g2($tgl, $bagian)
    {
        $this->_get_datatables_query($tgl, $bagian);
        if($_GET['length'] != -1)
            $this->db->limit($_GET['length'], $_GET['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_datatables_query($tgl, $bagian)
    {
        $this->db->select('over.nik, karyawan.namaKaryawan, over.tanggal, departemen.nama as dep, section.nama as sec, ROUND(sum(over.jam),1) as jam');
        $this->db->from("over");
        $this->db->join("posisi","over.nik = posisi.nik");
        $this->db->join("karyawan","over.nik = karyawan.nik");
        $this->db->join("departemen","posisi.id_dep = departemen.id");
        $this->db->join("section","posisi.id_sec = section.id");
        $this->db->where("departemen.nama",$bagian);
        $this->db->where("DATE_FORMAT(over.tanggal,'%m-%Y') = DATE_FORMAT('".$tgl."','%m-%Y')");
        $this->db->group_by("over.nik");
        $this->db->order_by("jam","DESC");

        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            if($_GET['search']['value']) // if datatable send POST for search
            {

                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_GET['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_GET['search']['value']);
                }

                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
                }
                $i++;
            }

        if(isset($_GET['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
        }
    }

    function count_filtered($tgl, $bagian)
    {
        $this->_get_datatables_query($tgl, $bagian);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($tgl, $bagian)
    {
        $this->_get_datatables_query($tgl, $bagian);
        return $this->db->count_all_results();
    }
}
?>