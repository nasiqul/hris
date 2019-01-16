<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Over_report_model extends CI_Model {

    var $column_order = array('period','k.nik','k.namaKaryawan','bagian','total_jam','satuan'); //set column field database for datatable orderable
    var $column_search = array('DATE_FORMAT(o.tanggal, "%M %Y")','k.nik','k.namaKaryawan','dp.nama'); //set column field database for datatable searchable 
    var $order = array('period' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }    

    public function get_ot_report()
    {
        $this->_get_datatables_query();
        if($_GET['length'] != -1){
            $this->db->limit($_GET['length'], $_GET['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_datatables_query()
    {
        $this->db->select('DATE_FORMAT(o.tanggal, "%M %Y") as period, k.nik, k.namaKaryawan,  concat(dp.nama,"-",IF(sc.nama,sc.nama,""),"/", k.kode) as bagian, sum(jam_aktual) as total_jam, sum(satuan) as satuan');
        $this->db->from("over_time o");
        $this->db->join("over_time_member om","o.id = om.id_ot");
        $this->db->join("karyawan k","om.nik = k.nik");
        $this->db->join("posisi p","p.nik = k.nik");
        $this->db->join("departemen dp","dp.id = p.id_dep", "left");
        $this->db->join("section sc","sc.id = p.id_sec", "left");
        $this->db->group_by(array("MONTH(o.tanggal)", "k.nik"));


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