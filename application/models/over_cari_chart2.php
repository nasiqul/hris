<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Over_cari_chart2 extends CI_Model {
	var $column_order = array('karyawan.nik','karyawan.namaKaryawan', 'dp.nama', 'sc.nama', 'karyawan.kode','avg(over.jam)'); //set column field database for datatable orderable
    var $column_search = array('karyawan.nik','karyawan.namaKaryawan', 'dp.nama', 'sc.nama', 'karyawan.kode'); //set column field database for datatable searchable 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_data($tgl,$cat)
    {
        $this->_get_over_cari($tgl, $cat);
        if($_GET['length'] != -1)
            $this->db->limit($_GET['length'], $_GET['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_over_cari($tgl, $cat)
    {
        if ($cat == "3jam_t") {
            $where = "";
        } else {
            $where = 'where kode = "'.$cat.'"';
        }

        $this->db->select('*');
        $this->db->from('(
            SELECT s.*, karyawan.kode, karyawan.namaKaryawan, section.nama as section, departemen.nama as departemen from
(select d.nik, round(avg(jam),2) as avg from
        (select over_time.id, tanggal, nik, sum(final) as jam, status, over_time.hari from over_time
        left join over_time_member on over_time_member.id_ot = over_time.id
        where deleted_at IS NULL and date_format(over_time.tanggal, "%Y-%m") = "'.$tgl.'" and nik IS NOT NULL and over_time_member.status = 1 and hari = "N"
        group by nik, tanggal) d 
        where jam > 3
        group by d.nik ) s
        left join karyawan on karyawan.nik = s.nik 
        left join posisi on posisi.nik = karyawan.nik
        left join departemen on departemen.id = posisi.id_dep
        left join section on section.id = posisi.id_sec
        '.$where.'
        ) a');


        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            if($_GET['search']['value']) // if datatable send GET for search
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

    function count_filtered_3($tgl, $cat)
    {
        $this->_get_over_cari($tgl, $cat);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_3($tgl, $cat)
    {
        $this->_get_over_cari($tgl, $cat);
        return $this->db->count_all_results();
    }

    // ----------------------------------- [    14 JAM   ] ------------------------------

    public function get_data_14($tgl,$cat)
    {
        $this->_get_over_cari_14($tgl,$cat);
        if($_GET['length'] != -1)
            $this->db->limit($_GET['length'], $_GET['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_over_cari_14($tgl, $cat)
    {
        if ($cat == "14jam_t") {
            $where = "";
        } else {
            $where = 'and kode = "'.$cat.'"';
        }

        $this->db->select('*');
        $this->db->from('(select s.nik, avg(jam) as avg, kode, karyawan.namaKaryawan, section.nama as section, departemen.nama as departemen  from
        (select nik, sum(jam) jam, week_name from
        (select over_time.id, tanggal, nik, sum(final) as jam, status, over_time.hari, week(over_time.tanggal) as week_name from over_time
        left join over_time_member on over_time_member.id_ot = over_time.id
        where deleted_at IS NULL and date_format(over_time.tanggal, "%Y-%m") = "'.$tgl.'" and nik IS NOT NULL and over_time_member.status = 1 and hari = "N"
        group by nik, tanggal) m
        group by nik, week_name) s
        left join karyawan on karyawan.nik  = s.nik
        left join posisi on posisi.nik = karyawan.nik
        left join departemen on departemen.id = posisi.id_dep
        left join section on section.id = posisi.id_sec
        where jam > 14
        group by s.nik '.$where.'
        ) b');

        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            if($_GET['search']['value']) // if datatable send GET for search
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

    function count_filtered_14($tgl, $cat)
    {
        $this->_get_over_cari_14($tgl, $cat);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_14($tgl, $cat)
    {
        $this->_get_over_cari_14($tgl, $cat);
        return $this->db->count_all_results();
    }


    // ----------------------------------- [   3 JAM & 14 JAM   ] ------------------------------

    public function get_data_3_14($tgl,$cat)
    {
        $this->_get_over_cari_3_14($tgl,$cat);
        if($_GET['length'] != -1)
            $this->db->limit($_GET['length'], $_GET['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_over_cari_3_14($tgl, $cat)
    {
        if ($cat == "3_14jam_t") {
            $where = '';
        } else {
            $where = 'where kode = "'.$cat.'"';
        }
       
        $this->db->select('b.*');
        $this->db->from('(
             select c.nik, karyawan.namaKaryawan ,karyawan.kode, dp.nama as departemen, sc.nama as section, c.avg from 
 ( select z.nik, x.avg from 
 ( select d.nik, round(avg(jam),2) as avg from
        (select over_time.id, tanggal, nik, sum(final) as jam, status, over_time.hari from over_time
        left join over_time_member on over_time_member.id_ot = over_time.id
        where deleted_at IS NULL and date_format(over_time.tanggal, "%Y-%m") = "'.$tgl.'" and nik IS NOT NULL and over_time_member.status = 1 and hari = "N"
        group by nik, tanggal) d 
        where jam > 3
        group by d.nik ) z
        
        INNER JOIN
        
        ( select s.nik, avg(jam) as avg from
        (select nik, sum(jam) jam, week_name from
        (select over_time.id, tanggal, nik, sum(final) as jam, status, over_time.hari, week(over_time.tanggal) as week_name from over_time
        left join over_time_member on over_time_member.id_ot = over_time.id
        where deleted_at IS NULL and date_format(over_time.tanggal, "%Y-%m") = "'.$tgl.'" and nik IS NOT NULL and over_time_member.status = 1 and hari = "N"
        group by nik, tanggal) m
        group by nik, week_name) s
        where jam > 14
        group by s.nik) x on z.nik = x.nik
        ) c
        LEFT JOIN karyawan on karyawan.nik = c.nik
    LEFT JOIN posisi p on p.nik = karyawan.nik
    LEFT JOIN departemen dp on dp.id = p.id_dep
    LEFT JOIN section sc on sc.id = p.id_sec
    '.$where.') b ');


        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            if($_GET['search']['value']) // if datatable send GET for search
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

    function count_filtered_3_14($tgl, $cat)
    {
        $this->_get_over_cari_3_14($tgl, $cat);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_3_14($tgl, $cat)
    {
        $this->_get_over_cari_3_14($tgl, $cat);
        return $this->db->count_all_results();
    }


    // ----------------------------------- [   56 JAM   ] ------------------------------

    public function get_data_56($tgl,$cat)
    {
        $this->_get_over_cari_56($tgl,$cat);
        if($_GET['length'] != -1)
            $this->db->limit($_GET['length'], $_GET['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_over_cari_56($tgl, $cat)
    {
        if ($cat == "56jam_t") {
            $where = "";
        } else {
            $where = 'and c.kode = "'.$cat.'"';
        }

        $this->db->select('*');
        $this->db->from('
            (
            select c.kode, c.nik, c.jam as avg, karyawan.namaKaryawan, dp.nama as departemen, sc.nama as section from
( select d.nik, sum(jam) as jam, karyawan.kode from
(select over_time.id, tanggal, nik, sum(final) as jam, status, over_time.hari from over_time
        left join over_time_member on over_time_member.id_ot = over_time.id
        where deleted_at IS NULL and date_format(over_time.tanggal, "%Y-%m") = "'.$tgl.'" and nik IS NOT NULL and over_time_member.status = 1 and hari = "N"
        group by nik, tanggal) d
        left join karyawan on karyawan.nik = d.nik
        group by d.nik ) c
        LEFT JOIN karyawan on karyawan.nik = c.nik
    LEFT JOIN posisi p on p.nik = karyawan.nik
    LEFT JOIN departemen dp on dp.id = p.id_dep
    LEFT join section sc on sc.id = p.id_sec
        where jam > 56 '.$where.'
        ) a
            ');
        

        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            if($_GET['search']['value']) // if datatable send GET for search
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

    function count_filtered_56($tgl, $cat)
    {
        $this->_get_over_cari_56($tgl, $cat);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_56($tgl, $cat)
    {
        $this->_get_over_cari_56($tgl, $cat);
        return $this->db->count_all_results();
    }
}
?>