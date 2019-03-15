<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Budget_model extends CI_Model {
	var $column_order = array('id_cc','period','budget'); //set column field database for datatable orderable
    var $column_search = array('id_cc','DATE_FORMAT(period, "%M")','budget'); //set column field database for datatable searchable 
    var $order = array('period' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_data_budget()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_datatables_query()
    {
        $this->db->select('id_cc, DATE_FORMAT(period, "%M") as period2, budget');
        $this->db->from('cost_center_budget');

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
        $this->db->from('cost_center_budget');
        return $this->db->count_all_results();
    }

    public function get_chart_data($tgl)
    {
        $q = "select COUNT(C.NIK) as jumlah, c.costCenter, b.name, a.budget, a.aktual from karyawan as c
        LEFT JOIN cost_center_budget as a on a.id_cc = c.costCenter
        LEFT JOIN master_cc as b on a.id_cc = b.id_cc        
        where a.period = '".$tgl."'
        GROUP BY c.costCenter";
        $query = $this->db->query($q);
        return $query->result();
    }

    public function get_chart_data_mp()
    {
        $q = "select id_cc, budget, aktual from cost_center_budget
        where period = '2019-03-01'";
        $query = $this->db->query($q);
        return $query->result();
    }
}
?>