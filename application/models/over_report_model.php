<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Over_report_model extends CI_Model {

    var $column_order = array('period','k.nik','k.namaKaryawan','bagian','total_jam','satuan'); //set column field database for datatable orderable
    var $column_search = array('k.nik','k.namaKaryawan','dp.nama','k.kode'); //set column field database for datatable searchable 
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
        $this->db->select('DATE_FORMAT(o.tanggal, "%M %Y") as period, k.nik, k.namaKaryawan, concat(dp.nama,"-",IF(sc.nama,sc.nama,""),"/", k.kode) as bagian, sum(final) as total_jam, sum(satuan) as satuan');
        $this->db->from("over_time o");
        $this->db->join("over_time_member om","o.id = om.id_ot");
        $this->db->join("karyawan k","om.nik = k.nik");
        $this->db->join("posisi p","p.nik = k.nik");
        $this->db->join("departemen dp","dp.id = p.id_dep", "left");
        $this->db->join("section sc","sc.id = p.id_sec", "left");
        $this->db->group_by(array("date_format(o.tanggal, '%m-%Y')", "k.nik"));


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
        $this->_get_datatables_query();
        return $this->db->count_all_results();
    }


    // ---------------------- [ 2 ] --------------

    public function get_ot_report2()
    {
        $this->_get_datatables_query2();
        if($_GET['length'] != -1){
            $this->db->limit($_GET['length'], $_GET['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_datatables_query2()
    {
        $this->db->select('ovr.period, ovr.nik, emp.`name`, department, section, ovr.final_jam');
        $this->db->from("(select DATE_FORMAT(tanggal, '%Y-%m') period, nik, SUM(IF(status = 1, final, jam)) as final_jam from over_time_member 
            join over_time on over_time.id = over_time_member.id_ot
            where deleted_at is null
            and jam_aktual = 0
            group by nik, DATE_FORMAT(tanggal, '%Y-%m')
        ) ovr");
        $this->db->join("ympimis.employees emp","emp.employee_id = ovr.nik","left");
        $this->db->join("(select employee_id, department, section from ympimis.mutation_logs where valid_to is null) mutation","mutation.employee_id = ovr.nik","left");
        $this->db->order_by('period desc, final_jam desc'); 

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

    function count_filtered2()
    {
        $this->_get_datatables_query2();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all2()
    {
        $this->_get_datatables_query2();
        return $this->db->count_all_results();
    }
}
?>