<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Over_model extends CI_Model {

	var $column_order = array('o.id','tanggal','d.nama','s.nama','keperluan','catatan'); //set column field database for datatable orderable
    var $column_search = array('o.id','tanggal','d.nama','s.nama','keperluan','catatan'); //set column field database for datatable searchable 
    var $order = array('tanggal' => 'asc'); // default order 

    public function __construct()
    {
    	parent::__construct();
    	$this->load->database();
    }

    public function get_data_ot()
    {
    	$this->_get_datatables_query();
    	if($_POST['length'] != -1)
    		$this->db->limit($_POST['length'], $_POST['start']);
    	$query = $this->db->get();
    	return $query->result();
    }

    private function _get_datatables_query()
    {
    	// $this->db->select("tanggal,c.nik,d.namaKaryawan,masuk,keluar,jam,id");
    	// $this->db->from("(SELECT * from (
    	// 	SELECT o.tanggal,o.id,b.jam,b.nik as nik1 from over_time as o
    	// 	LEFT JOIN over_time_member as b
    	// 	on o.id = b.id_ot
    	// 	) a

    	// 	left join (
    	// 	SELECT presensi.nik,presensi.masuk,presensi.keluar,presensi.tanggal as tanggalpresensi from presensi where presensi.nik in (SELECT over_time_member.nik from over_time_member) and presensi.tanggal in (SELECT over_time.tanggal from over_time)
    	// ) b on a.tanggal = b.tanggalpresensi and a.nik1 = b.nik) c");
    	// $this->db->join("karyawan d","c.nik = d.nik","left");

        $this->db->select('o.id, tanggal, d.nama as namaDep, s.nama as namaSec, keperluan, catatan');
        $this->db->from("over_time o");
        $this->db->join("departemen d","o.departemen = d.id",'left');
        $this->db->join("section s","o.section = s.id",'left');


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

    public function get_dep()
    {
    	$query = $this->db->get('departemen');
    	return $query->result();
    }

    public function save_master($no_doc, $tgl, $dep, $sec, $kep, $cat)
    {
    	$data = array(
    		'id' => $no_doc,
    		'tanggal' => $tgl,
    		'departemen' => $dep,
    		'section' => $sec,
    		'keperluan' => $kep,
    		'catatan' => $cat
    	);

    	$this->db->insert('over_time', $data);
    }

    public function save_member($no_doc, $nik1, $dari1, $sampai1, $jam1, $trans1, $makan1, $exfood1)
    {
    	$data = array(
    		'id_ot' => $no_doc,
    		'nik' => $nik1,
    		'dari' => $dari1,
    		'sampai' => $sampai1,
    		'jam' => $jam1,
    		'transport' => $trans1,
    		'makan' => $makan1,
    		'ext_food' => $exfood1
    	);

    	$this->db->insert('over_time_member', $data);	
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from('over_time_member');
        return $this->db->count_all_results();
    }


    public function get_over_by_id($id)
    {
        $this->db->select("o.id as id_over, tanggal, departemen, section, keperluan, catatan");
        $this->db->from('over_time o');
        $this->db->where("o.id",$id);
        $query = $this->db->get();
        return $query->result();

    }


    public function get_over_by_id_member($id)
    {
        $this->_get_datatables_query2($id);
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_datatables_query2($id)
    {
        $this->db->select("o.id as id_over, tanggal, departemen, section, keperluan, catatan, om.*, k.namaKaryawan");
        $this->db->from('over_time o');
        $this->db->join("over_time_member om","o.id = om.id_ot");
        $this->db->join("karyawan k","om.nik = k.nik");
        $this->db->where("o.id",$id);


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

    public function get_id_doc()
    {
        $this->db->select("id");
        $this->db->where("YEAR(tanggal) = YEAR(CURRENT_DATE())");
        $this->db->where("MONTH(tanggal) = MONTH(CURRENT_DATE())");
        $this->db->from("over_time");
        $this->db->order_by('id','DESC');

        $query = $this->db->get();
        return $query->result();
    }
}
?>