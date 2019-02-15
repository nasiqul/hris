<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cari_over extends CI_Model {
	var $column_order = array('o.id','tanggal','d.nama','s.nama','keperluan','catatan'); //set column field database for datatable orderable
    var $column_search = array('o.id','tanggal','d.nama','s.nama','keperluan','catatan'); //set column field database for datatable searchable 
    var $order = array('tanggal' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_data_ot_cari($tgl, $bagian)
    {
        $this->_get_datatables_query($tgl, $bagian);
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_datatables_query($tgl, $bagian)
    {
        $this->db->select('o.id, tanggal, d.nama as namaDep, s.nama as namaSec, keperluan, catatan, SUM(om.jam) as jam');
        $this->db->from("over_time o");
        $this->db->join("departemen d","o.departemen = d.id",'left');
        $this->db->join("section s","o.section = s.id",'left');
        $this->db->join("over_time_member om","o.id = om.id_ot");
        $this->db->where("MONTH(tanggal) = MONTH(STR_TO_DATE('".$tgl."', '%d-%m-%Y'))");
        $this->db->where("d.nama",$bagian);
        $this->db->group_by("o.id");


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
        $this->db->from('over_time');
        return $this->db->count_all_results();
    }
}
?>