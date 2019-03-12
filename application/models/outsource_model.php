<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Outsource_model extends CI_Model {
	var $column_order = array('bulan','masuk','keluar','total'); //set column field database for datatable orderable
    var $column_search = array('DATE_FORMAT(bulan, "%b %Y")','masuk','keluar','total'); //set column field database for datatable searchable 
    var $order = array('bulan' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function outsource_data()
    {
        $this->_get_otsource();
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_otsource()
    {
        $this->db->select('DATE_FORMAT(bulan, "%b %Y") bulan2, masuk, keluar, total');

        $this->db->from('outsourcing');

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
        $this->_get_otsource();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from('outsourcing');
        return $this->db->count_all_results();
    }

    public function tambah($periode, $masuk, $keluar, $tot)
    {
        $this->db->set('bulan', date('Y-m-d', strtotime('10-'.$periode)));
        $this->db->set('masuk', $masuk);
        $this->db->set('keluar', $keluar);
        $this->db->set('total', $tot);

        $this->db->insert('outsourcing');
    }

}
?>