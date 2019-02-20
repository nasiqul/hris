<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Over_model extends CI_Model {

	var $column_order = array('id','tanggal','c.nik','d.namaKaryawan','masuk','keluar','jam','shift','','',''); //set column field database for datatable orderable
    var $column_search = array('id','DATE_FORMAT(tanggal, "%d-%m-%Y")','c.nik','d.namaKaryawan','masuk','keluar','jam'); //set column field database for datatable searchable 
    var $order = array('tanggal' => 'desc'); // default order 

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
    	$this->db->select("tanggal,c.nik,d.namaKaryawan, masuk, keluar, jam, id, shift, c.status");
    	$this->db->from("(SELECT * from (
    		SELECT o.tanggal, o.id, b.jam, b.nik as nik1, b.status as status from over_time as o
    		LEFT JOIN over_time_member as b
    		on o.id = b.id_ot
    		) a

    		left join (
    		SELECT presensi.nik,presensi.masuk,presensi.keluar,presensi.tanggal as tanggalpresensi, shift from presensi where presensi.nik in (SELECT over_time_member.nik from over_time_member) and presensi.tanggal in (SELECT over_time.tanggal from over_time)
    	) b on a.tanggal = b.tanggalpresensi and a.nik1 = b.nik) c");
    	$this->db->join("karyawan d","c.nik = d.nik","left");

        // $this->db->select('o.id, tanggal, d.nama as namaDep, s.nama as namaSec, keperluan, catatan, SUM(om.jam) as jam');
        // $this->db->from("over_time o");
        // $this->db->join("departemen d","o.departemen = d.id",'left');
        // $this->db->join("section s","o.section = s.id",'left');
        // $this->db->join("over_time_member om","o.id = om.id_ot");
        // $this->db->group_by("o.id");


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

    public function save_master($no_doc, $tgl, $dep, $sec, $kep, $cat, $hari)
    {
    	$data = array(
    		'id' => $no_doc,
    		'tanggal' => $tgl,
    		'departemen' => $dep,
    		'section' => $sec,
    		'keperluan' => $kep,
    		'catatan' => $cat,
            'hari' => $hari,
            'status' => 0
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
        $this->db->from('over_time');
        return $this->db->count_all_results();
    }


    public function get_over_by_id($id)
    {
        $this->db->select("o.id as id_over, tanggal, d.nama as departemen, s.nama as section, keperluan, catatan");
        $this->db->from('over_time o');
        $this->db->join('departemen d','o.departemen = d.id','left');
        $this->db->join('section s','o.section = s.id','left');
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

    public function get_member_id($id)
    {
        $this->db->select("o.id as id_over, tanggal, departemen, section, keperluan, catatan, om.*, k.namaKaryawan, cc.budget, cc.id_cc, cc.aktual");
        $this->db->from('over_time o');
        $this->db->join("over_time_member om","o.id = om.id_ot");
        $this->db->join("karyawan k","om.nik = k.nik");
        $this->db->join("cost_center_budget cc","cc.id_cc = k.costCenter");
        $this->db->where("o.id",$id);
        $query = $this->db->get();
        return $query->result();
    }

    public function costCenter($id)
    {
        $this->db->select("COUNT(nik) as jml");
        $this->db->from("karyawan");
        $this->db->where("costCenter", $id);
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

    public function by_bagian(){
        $q = "SELECT nama, section, tanggal, SUM(om.jam) AS jml from over_time o 
        JOIN over_time_member om ON o.id = om.id_ot
        LEFT JOIN departemen d ON o.departemen = d.id
        where MONTH(o.tanggal) = MONTH(CURRENT_DATE()) GROUP BY o.departemen ORDER BY jml DESC";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }

    public function by_bagian_cari($tgl){
        $q = "SELECT nama, section, tanggal, SUM(om.jam) AS jml from over_time o 
        LEFT JOIN departemen d ON o.departemen = d.id
        JOIN over_time_member om ON o.id = om.id_ot
        where MONTH(o.tanggal) = MONTH( STR_TO_DATE('".$tgl."', '%d-%m-%Y')) GROUP BY o.departemen ORDER BY jml DESC";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }


    public function get_over_by_bagian($tgl, $dep)
    {
        $this->_get_datatables_query3($tgl, $dep);
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_datatables_query3($tgl, $dep)
    {
        $this->db->select("nama, tanggal, k.namaKaryawan, om.jam, o.keperluan");
        $this->db->from('over_time o');
        $this->db->join("departemen d","o.departemen = d.id", 'LEFT');
        $this->db->join("over_time_member om","o.id = om.id_ot");
        $this->db->join("karyawan k","om.nik = k.nik");
        $this->db->where("d.nama",$dep);
        $this->db->where("MONTH(o.tanggal) = MONTH(STR_TO_DATE('".$tgl."', '%d-%m-%Y'))");


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

    public function cek_id($nik, $dep)
    {
        $this->db->select("k.nik");
        $this->db->from('karyawan k');
        $this->db->join("posisi p","p.nik = k.nik");
        $this->db->join("departemen d","p.id_dep = d.id");
        $this->db->where("k.nik",$nik);
        $this->db->where("p.id_dep",$dep);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function by_bagian_bulan(){
        $q = "";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }


    public function get_cc($nik,$val,$tgl)
    {
        $sql = "UPDATE over_time_member AS om JOIN over_time AS o ON om.id_ot = o.id SET om.status = 1, om.jam_aktual = ".(float)$val." WHERE o.tanggal = STR_TO_DATE('".$tgl."', '%d-%m-%Y') AND om.nik = '".$nik."'";
        $this->db->query($sql);

        $this->db->select("k.costCenter");
        $this->db->from("karyawan k");
        $this->db->where("k.nik",$nik);
        $query = $this->db->get();
        return $query->result();
    }

    public function tambah_aktual($id_cc,$val,$tgl)
    {

        $query2 = "UPDATE `cost_center_budget` SET `aktual` = `aktual` + ".(float)$val." WHERE `id_cc` = '".$id_cc."'";
        $this->db->query($query2);
    }

    public function get_calendar($tgl)
    {
        $this->db->select("tanggal");
        $this->db->from("kalender");
        $this->db->where("tanggal = STR_TO_DATE('".$tgl."', '%Y-%m-%d')");

        $query = $this->db->get();
        return $query->num_rows();
    }
}
?>