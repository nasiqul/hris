<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Over_detail_mControl extends CI_Model {
	var $column_order = array('tanggal_tanya','nik_penanya','pertanyaan','jawaban','nik_penjawab','tanggal_jawab'); //set column field database for datatable orderable
    var $column_search = array('tanggal_tanya','nik_penanya','pertanyaan','jawaban','nik_penjawab','tanggal_jawab'); //set column field database for datatable searchable 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_data_chart_detail($kode,$tgl)
    {
        $this->_get_data_chart_detail($kode,$tgl);
        if($_GET['length'] != -1)
            $this->db->limit($_GET['length'], $_GET['start']);
        $query = $this->db->get();  
        return $query->result();
    }

    private function _get_data_chart_detail($kode,$tgl)
    {
        $this->db->select("d.tanggal, d.nik, d.namaKaryawan, d.name, presensi.masuk, presensi.keluar, jam");
        $this->db->from("(
            select o.nik,tanggal, jam, karyawan.namaKaryawan, karyawan.kode, costCenter, name from over o
            left join karyawan on o.nik = karyawan.nik
            left join master_cc on master_cc.id_cc = karyawan.costCenter
            where tanggal = '".$tgl."' and master_cc.departemen = '".$kode."' and jam <> 0
        ) d ");
        $this->db->join('presensi','(presensi.tanggal = d.tanggal) and (presensi.nik = d.nik)','left');

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

    function count_filtered($kode,$tgl)
    {
        $this->_get_data_chart_detail($kode,$tgl);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($kode,$tgl)
    {
        $this->_get_data_chart_detail($kode,$tgl);
        return $this->db->count_all_results();
    }
}
?>