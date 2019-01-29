<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi_model extends CI_Model {
	var $column_order = array('tanggal','nik','namaKaryawan','masuk','keluar'); //set column field database for datatable orderable
    var $column_search = array('tanggal','nik','namaKaryawan','masuk','keluar'); //set column field database for datatable searchable 
    var $order = array('masuk' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_data_absensi()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

	private function _get_datatables_query()
    {
        $this->db->select('karyawan.nik, karyawan.namaKaryawan, presensi.tanggal, presensi.masuk, presensi.keluar, presensi.shift');
        $this->db->from('presensi');
        $this->db->join('karyawan','karyawan.nik = presensi.nik');
        $this->db->where('date(presensi.tanggal)','CURDATE()');
        $this->db->where('presensi.shift REGEXP','^[a-zA-Z]+$');
        $this->db->where('presensi.shift !=','OFF');
        $this->db->where('presensi.shift !=','X');
 
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
        $this->db->from('presensi');
        return $this->db->count_all_results();
    }

    public function by_absensi(){
        $q = "SELECT shift,tanggal, COUNT(*) AS jml from presensi where date(tanggal) = CURRENT_DATE() and shift <> 'off' AND shift <> 'X' and shift REGEXP '^[a-zA-Z]+$' group by shift order by jml ASC ";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }
}
?>