<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi_column extends CI_Model {
	//database for datatable searchable 
    var $order = array('shift' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_data_absensi_column($tgl, $shift)
    {
        $this->_get_absensi_column($tgl, $shift);
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }


    private function _get_absensi_column($tgl, $shift)
    {
        $this->db->select('karyawan.namaKaryawan, presensi.tanggal, presensi.shift');
        $this->db->from('presensi');
        $this->db->join('karyawan','karyawan.nik = presensi.nik');
        $this->db->where('presensi.shift',$shift);
        $this->db->where('date(presensi.tanggal)','date("'.$tgl.'"")');

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
}
?>