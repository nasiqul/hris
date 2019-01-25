<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends CI_Model {

	var $column_order = array('tanggal','nik','namaKaryawan','masuk','keluar','shift'); //set column field database for datatable orderable
    var $column_search = array('tanggal','nik','namaKaryawan','masuk','keluar','shift'); //set column field database for datatable searchable 
    var $order = array('masuk' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


  //   public function viewIndex()
  //   {

		// // $query = "select emp.nik, CONCAT(emp.first_name, last_name) AS nama, att_log.scan_date from emp JOIN att_log ON emp.pin = att_log.pin Where att_log.scan_date = '2019-01-07' ORDER BY att_log.scan_date DESC";


  //     $this->db->select('emp.nik, CONCAT(emp.first_name," ", last_name) AS nama, att_log.scan_date, emp.pin,att_log.att_id,  GROUP_CONCAT(time(att_log.scan_date) ORDER BY att_log.scan_date separator "_") AS date');
		// // $this->db->select('emp.nik, CONCAT(emp.first_name," ", last_name) AS nama, att_log.scan_date, att_log.att_id');
  //     $this->db->from('emp');
  //     $this->db->join('att_log', 'emp.pin = att_log.pin', 'left');
  //     $this->db->where('DATE(att_log.scan_date)','2019-01-08')->or_where('DATE(att_log.scan_date)',NULL);
  //     $this->db->order_by("att_log.scan_date", "desc");
  //     $this->db->group_by("emp.pin");
		// // $this->db->limit(10);

  //     $query = $this->db->get();
  //     return $query->result_array($query);
  // }

    public function get_data_persensi()
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
        $this->db->where('presensi.tanggal','2019-01-08');
        $this->db->where('presensi.shift !=','0');

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

    public function report1(){
        $q = "SELECT tanggal, COUNT(*) AS jml from presensi where shift = '1' group by tanggal";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }

    public function report2(){
        $q = "SELECT tanggal, COUNT(*) AS jml from presensi where shift = '2' group by tanggal";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }

    public function report3(){
        $q = "SELECT tanggal, COUNT(*) AS jml from presensi where shift = '3' group by tanggal";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }
}