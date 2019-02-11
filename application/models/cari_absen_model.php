<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cari_absen_model extends CI_Model {
	var $column_order = array('tanggal','karyawan.nik','namaKaryawan','masuk','keluar','shift'); //set column field database for datatable orderable
    var $column_search = array('tanggal','karyawan.nik','namaKaryawan','masuk','keluar','shift'); //set column field database for datatable searchable 
    var $order = array('masuk' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_data_absensi_cari($tgl, $nik, $nama, $shift)
    {
        $this->_get_absensi_cari($tgl, $nik, $nama, $shift);
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }


    private function _get_absensi_cari($tgl, $nik, $nama, $shift)
    {
        $this->db->select('karyawan.nik, karyawan.namaKaryawan, presensi.tanggal, presensi.masuk, presensi.keluar, presensi.shift, CONCAT(`karyawan`.`dep/subSec`," ",`karyawan`.`sec/Group`," (",`karyawan`.`kode`,")") as bagian');
        $this->db->from('presensi');
        $this->db->join('karyawan','karyawan.nik = presensi.nik');
        $this->db->where('presensi.shift REGEXP','^[a-zA-Z]+$');
        $this->db->where('presensi.shift !=','0');

       if ($tgl) {
            $this->db->where("DATE_FORMAT(tanggal, '%d/%m/%Y') =",$tgl);
        }

        if ($nik) {
            $this->db->where('karyawan.nik',$nik);
        }

        if ($nama) {
            $this->db->like('namaKaryawan',$nama);
        }

        if ($shift) {
            $this->db->where('shift', $shift);
        }


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

    function count_filtered($tgl, $nik, $nama, $shift)
    {
        $this->_get_absensi_cari($tgl, $nik, $nama, $shift);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from('presensi');
        $this->db->where('presensi.shift REGEXP','^[a-zA-Z]+$');
        $this->db->where('presensi.shift !=','0');
        return $this->db->count_all_results();
    }
}
?>