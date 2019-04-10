<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Over_user_model extends CI_Model {
	var $column_order = array('','over_time.id','tanggal','sc.nama','ssc.nama','gr.nama'); //set column field database for datatable orderable
    var $column_search = array('over_time.id','DATE_FORMAT(tanggal, "%a, %d %b %Y")'); //set column field database for datatable searchable 
    var $order = array('over_time.id' => 'asc'); // default order 

    public function __construct()
    {
    	parent::__construct();
    	$this->load->database();
    }

    public function get_ot_user($tgl,$sub,$subsec,$group,$user,$role)
    {
    	$this->_get_datatables_query($tgl,$sub,$subsec,$group,$user,$role);
    	if($_GET['length'] != -1){
    		$this->db->limit($_GET['length'], $_GET['start']);
    	}
    	$query = $this->db->get();
    	return $query->result();
    }

    private function _get_datatables_query($tgl,$sub,$subsec,$group,$user,$role)
    {
    	$this->db->select("over_time.id, DATE_FORMAT(tanggal, '%a, %d %b %Y') as tanggal, over_time_member.status, sc.nama as section, ssc.nama as subsection, gr.nama as grup, count(nik) as jml");
    	$this->db->from("over_time");
        $this->db->join("over_time_member",'over_time_member.id_ot = over_time.id','left');
        $this->db->join("section sc",'sc.id = over_time.departemen','left');
        $this->db->join("sub_section ssc",'ssc.id = over_time.section','left');
        $this->db->join("group1 gr",'gr.id = over_time.sub_sec','left');
        $this->db->join("departemen",'departemen.id = sc.id_departemen','left');
        $this->db->where("tanggal = '".$tgl."'");
        $this->db->where("deleted_at IS NULL");
        
        if ($role != "1") {
        $this->db->where("departemen.nama = (select department from login where username = '".$user."')");
        }

        if ($sub !="" && $sub && $sub != "0" ) {
            $this->db->where("departemen = '".$sub."'");
        }
        if ($subsec != "" && $subsec && $subsec != "0") {
            $this->db->where("section = '".$subsec."'");
        }
        if ($group != "" && $group && $group != "0") {
            $this->db->where("sub_sec = '".$group."'");
        }
        $this->db->group_by('over_time.id');

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


    function count_filtered($tgl,$sub,$subsec,$group,$user,$role)
    {
        $this->_get_datatables_query($tgl,$sub,$subsec,$group,$user,$role);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($tgl,$sub,$subsec,$group,$user,$role)
    {
        $this->_get_datatables_query($tgl,$sub,$subsec,$group,$user,$role);
        return $this->db->count_all_results();
    }
}

?>
