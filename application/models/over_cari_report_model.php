<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Over_cari_report_model extends CI_Model {
	var $column_order = array('o.tanggal','om.jam_aktual','om.satuan'); //set column field database for datatable orderable
    var $column_search = array('o.tanggal'); //set column field database for datatable searchable 
    var $order = array('o.tanggal' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_data_report_cari($tgl, $nik)
    {
        $this->_get_report_cari($tgl, $nik);
        if($_GET['length'] != -1)
            $this->db->limit($_GET['length'], $_GET['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_report_cari($tgl, $nik)
    {
        $this->db->select('k.nik, k.namaKaryawan, o.tanggal, om.jam_aktual, om.satuan');
        $this->db->from('over_time_member om');
        $this->db->join('karyawan k','k.nik = om.nik');
        $this->db->join('over_time o','om.id_ot = o.id');
        $this->db->where('k.nik',$nik);
        $this->db->where('MONTH(o.tanggal) = MONTH("'.$tgl.'")');

        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            if($_GET['search']['value']) // if datatable send GET for search
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

    function count_filtered($tgl, $nik)
    {
        $this->_get_report_cari($tgl, $nik);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($tgl, $nik)
    {
        $this->_get_report_cari($tgl, $nik);
        return $this->db->count_all_results();
    }
}
?>