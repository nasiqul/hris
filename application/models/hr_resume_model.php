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
        $this->db->from('(
            select karyawan.nik, namaKaryawan, CONCAT(departemen.nama," - ",section.nama," - ",sub_section.nama," - ",group1.nama ) bagian, COALESCE(jam,0) act, aktual.satuan from karyawan
            left join posisi on posisi.nik = karyawan.nik
            join departemen on departemen.id = posisi.id_dep
            join section on section.id = posisi.id_sec
            join sub_section on sub_section.id = posisi.id_sub_sec
            join group1 on group1.id = posisi.id_group
            left join
            ( select d.nik, sum(d.jam) as jam, sum(satuan) as satuan from
                        (
                        select m.*, satuan_lembur.satuan from (
                            select tanggal ,nik, sum(jam) as jam, status from over where date_format(tanggal, "%Y-%m") = "'.$tgl.'" and status_final = 1
            group by tanggal, nik ) as m 
                        left join satuan_lembur on satuan_lembur.jam = m.jam and satuan_lembur.hari = m.status
                        ) d group by nik) as aktual on aktual.nik = karyawan.nik
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