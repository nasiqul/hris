<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Over_cari_report_model_2 extends CI_Model {
	var $column_order = array('tanggal','jam'); //set column field database for datatable orderable
    var $column_search = array('tanggal'); //set column field database for datatable searchable 
    var $order = array('tanggal' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_data_report_cari_2($tgl, $nik)
    {
        $this->_get_report_cari_2($tgl, $nik);
        if($_GET['length'] != -1)
            $this->db->limit($_GET['length'], $_GET['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_report_cari_2($tgl, $nik)
    {
        $this->db->select('date_format(tanggal, "%d-%m-%Y") as tgl, jam');
        $this->db->from('over');
        $this->db->where('nik',$nik);
        $this->db->where('jam <> 0');
        $this->db->where('date_format(tanggal, "%m-%Y") = "'.$tgl.'"');

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

    function count_filtered_2($tgl, $nik)
    {
        $this->_get_report_cari_2($tgl, $nik);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_2($tgl, $nik)
    {
        $this->_get_report_cari_2($tgl, $nik);
        return $this->db->count_all_results();
    }
}
?>