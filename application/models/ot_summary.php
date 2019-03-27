<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ot_summary extends CI_Model {
	var $column_order = array('mon','name','karyawan','aktual','avg','min','max'); //set column field database for datatable orderable
    var $column_search = array('mon','name','karyawan','aktual'); //set column field database for datatable searchable 
    var $order = array('name' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function ot_summary_m($tgl1)
    {
        $this->_get_data_summary($tgl1);
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_data_summary($tgl1)
    {
        $this->db->select("mon, p.id_cc, name, karyawan, aktual, round((aktual/karyawan),1) as avg, coalesce(min, 0) as min_final, coalesce(max, 0) as max_final");

        $this->db->from("
            (

        select mon, master_cc.id_cc, kode, name, sum(tot_karyawan) as karyawan from (
            select mon, costCenter, sum(if(if(date_format(a.tanggalMasuk, '%Y-%m') < mon, 1, 0 ) - if(date_format(a.tanggalKeluar, '%Y-%m') < mon, 1, 0 ) = 0, 0, 1)) as tot_karyawan from
            (
            select distinct fiskal, date_format(tanggal, '%Y-%m') as mon
            from kalender_fy
            ) as b
            join
            (
                select '195' as fy, karyawan.kode, tanggalKeluar, tanggalMasuk, nik, costCenter
                from karyawan
            ) as a
            on a.fy = b.fiskal
            group by mon, costCenter
            having mon = '".$tgl1."'
    ) as b 
    left join master_cc on master_cc.id_cc = b.costCenter
    GROUP BY mon, kode, master_cc.id_cc
        ) as p
            ");

        $this->db->join("(
--              INI
        select tanggal, costCenter, ROUND(sum(jam),1) as aktual from over
                left join karyawan on karyawan.nik = over.nik
                where DATE_FORMAT(tanggal,'%Y-%m') = '".$tgl1."'
                group by costCenter
        ) as n","p.id_cc = n.costCenter",'left');
        $this->db->join("
           (
        select costCenter, max(jam) as max, min(jam) as min from (
                    select s.*, karyawan.costCenter
                         from (
                         SELECT * from (
                         select nik, round(sum(jam),1) as jam from over 
                         where DATE_FORMAT(tanggal,'%Y-%m') = '".$tgl1."'
                         GROUP BY nik
                         union
                         select nik, 0 jam from karyawan
                         ) a
                        GROUP BY a.nik
                        ) s
                        left join karyawan on karyawan.nik = s.nik
                ) as d 
                GROUP BY costCenter
        ) as m
        ","m.costCenter = n.costCenter","left");

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

    function count_filtered($tgl1)
    {
        $this->_get_data_summary($tgl1);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($tgl1)
    {
        $this->_get_data_summary($tgl1);
        return $this->db->count_all_results();
    }
}
?>