<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_detail_m extends CI_Model {
	var $column_order = array('presensi.tanggal','presensi.nik','karyawan.namaKaryawan','section.nama','presensi.masuk','presensi.keluar', 'presensi.shift'); //set column field database for datatable orderable
    var $column_search = array('presensi.tanggal','presensi.nik','karyawan.namaKaryawan','section.nama','presensi.masuk','presensi.keluar', 'presensi.shift'); //set column field database for datatable searchable 
    var $order = array('presensi.shift' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_data($tgl)
    {
        $this->_get_data2($tgl);
        if($_GET['length'] != -1)
            $this->db->limit($_GET['length'], $_GET['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_data2($tgl)
    {
        $this->db->select('presensi.tanggal, karyawan.nik, karyawan.namaKaryawan, presensi.masuk, section.nama as section, presensi.keluar, presensi.shift');
        $this->db->from('presensi');
        $this->db->join('karyawan','karyawan.nik = presensi.nik');
        $this->db->join('posisi','posisi.nik = karyawan.nik');
        $this->db->join('section','section.id = posisi.id_sec');
        $this->db->where('date(presensi.tanggal) = STR_TO_DATE("'.$tgl.'", "%Y-%m-%d")');
        $this->db->where('presensi.shift REGEXP "^[1-9]+$"');

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
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function count_filtered($tgl)
    {
        $this->_get_data2($tgl);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($tgl)
    {
        $this->_get_data2($tgl);
        return $this->db->count_all_results();
    }
}
?>