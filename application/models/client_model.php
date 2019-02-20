<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client_model extends CI_Model {
    var $column_order = array('tanggal','nik'); //set column field database for datatable orderable
    var $column_search = array('tanggal','nik'); //set column field

    var $order = array('tanggal' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
    }

    public function get_data_client($id)
    {
        $this->_get_datatables_query($id);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_datatables_query($id)
    {
        $this->db->select('karyawan.nik, namaKaryawan, tanggal,
            COUNT(if(shift = "Km" , shift, null)) Km,
            COUNT(if(shift = "Sn" , shift, null)) Sn,
            COUNT(if(shift = "M" , shift, null)) M,
            COUNT(if(shift = "CK" , shift, null)) CK,
            COUNT(if(shift = "I" , shift, null)) I,
            COUNT(if(shift = "SD" , shift, null)) SD,
            COUNT(if(shift = "A" , shift, null)) A,
            COUNT(if(shift = "CT" , shift, null)) CT,
            COUNT(if(shift = "DL" , shift, null)) DL');
        $this->db->from('presensi');
        $this->db->join('karyawan','karyawan.nik = presensi.nik');
        $this->db->where('presensi.nik',$id);
        $this->db->group_by('DATE_FORMAT(presensi.tanggal, "%Y-%m-01")');
 
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