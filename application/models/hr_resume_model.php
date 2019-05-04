<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HR_resume_model extends CI_Model {
	var $column_order = array('nik','bagian'); //set column field database for datatable orderable
    var $column_search = array('nik','namaKaryawan','bagian'); //set column field database for datatable searchable 
    // var $order = array('masuk' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function ot_get_resume_data($tgl)
    {
        $this->_get_datatables_query($tgl);
        if($_GET['length'] != -1)
            $this->db->limit($_GET['length'], $_GET['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_datatables_query($tgl)
    {
        $this->db->select('*');
        $this->db->from('(select ovr.nik, karyawan.namaKaryawan, CONCAT(dept.nama," - ",dev.nama," - ",sec.nama," - ",sub.nama," - ",gr.nama) bagian, ovr.final as jam, SUM(satuan) as satuan from
                (
                select over_time.tanggal, over_time_member.nik, sum(final) final, over_time.hari from over_time left join over_time_member on over_time.id = over_time_member.id_ot where DATE_FORMAT(tanggal,"%Y-%m") = "'.$tgl.'" and deleted_at IS NULL and nik IS NOT NULL and over_time_member.status = 1
                group by nik,tanggal
                ) ovr
                left join karyawan on karyawan.nik = ovr.nik
                left join posisi p on p.nik = karyawan.nik
                left join departemen as dept on p.id_dep = dept.id
                left join devisi as dev on p.id_devisi = dev.id
                LEFT JOIN section as sec on p.id_sec = sec.id
                LEFT JOIN sub_section as sub on p.id_sub_sec = sub.id
                LEFT JOIN group1    as gr on p.id_group = gr.id  
                left join satuan_lembur on satuan_lembur.jam = ovr.final and satuan_lembur.hari = ovr.hari
                where ovr.final <> 0
                group by nik
                ORDER BY tanggal asc

        ) as tabel');

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

    function count_filtered($tgl)
    {
        $this->_get_datatables_query($tgl);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($tgl)
    {
        $this->_get_datatables_query($tgl);
        return $this->db->count_all_results();
    }

}
?>