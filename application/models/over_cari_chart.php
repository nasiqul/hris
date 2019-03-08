<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Over_cari_chart extends CI_Model {
	var $column_order = array('karyawan.nik','karyawan.namaKaryawan', 'dp.nama', 'sc.nama', 'karyawan.kode','avg(over_time_member.final)'); //set column field database for datatable orderable
    var $column_search = array('karyawan.nik','karyawan.namaKaryawan', 'dp.nama', 'sc.nama', 'karyawan.kode'); //set column field database for datatable searchable 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_data($tgl,$cat)
    {
        $this->_get_over_cari($tgl,$cat);
        if($_GET['length'] != -1)
            $this->db->limit($_GET['length'], $_GET['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_over_cari($tgl, $cat)
    {
        $this->db->select('date_format(over_time.tanggal, "%m-%Y") as month_name, karyawan.nik, karyawan.namaKaryawan, dp.nama as departemen, sc.nama as section, karyawan.kode, avg(over_time_member.final) as avg');
        $this->db->from('over_time_member');
        $this->db->join('over_time','over_time.id = over_time_member.id_ot', 'right');
        $this->db->join('karyawan','karyawan.nik = over_time_member.nik', 'left');
        $this->db->join('posisi p','p.nik = karyawan.nik');
        $this->db->join('departemen dp','dp.id = p.id_dep');
        $this->db->join('section sc','sc.id = p.id_sec');
        $this->db->where('over_time_member.final > 3');
        $this->db->where('over_time.hari','N');
        $this->db->where('MONTH(over_time.tanggal) = MONTH(STR_TO_DATE("'.$tgl.'","%d-%m-%Y"))');
        $this->db->where('YEAR(over_time.tanggal) = YEAR(STR_TO_DATE("'.$tgl.'","%d-%m-%Y"))');
        $this->db->where('karyawan.kode', $cat);
        $this->db->group_by(array("date_format(over_time.tanggal, '%m-%Y')", "over_time_member.nik"));

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
        $this->db->select('date_format(over_time.tanggal, "%m-%Y") as month_name, karyawan.nik, karyawan.namaKaryawan, dp.nama as departemen, sc.nama as section, karyawan.kode, avg(over_time_member.final) as avg');
        $this->db->from('over_time_member');
        $this->db->join('over_time','over_time.id = over_time_member.id_ot', 'right');
        $this->db->join('karyawan','karyawan.nik = over_time_member.nik', 'left');
        $this->db->join('posisi p','p.nik = karyawan.nik');
        $this->db->join('departemen dp','dp.id = p.id_dep');
        $this->db->join('section sc','sc.id = p.id_sec');
        $this->db->where('over_time_member.final > 3');
        $this->db->where('over_time.hari','N');
        $this->db->where('MONTH(over_time.tanggal) = MONTH(STR_TO_DATE("'.$tgl.'","%d-%m-%Y"))');
        $this->db->where('YEAR(over_time.tanggal) = YEAR(STR_TO_DATE("'.$tgl.'","%d-%m-%Y"))');
        $this->db->where('karyawan.kode', $cat);
        $this->db->group_by(array("date_format(over_time.tanggal, '%m-%Y')", "week(over_time.tanggal)", "over_time_member.nik", "karyawan.nik"));
        $this->db->having("sum(over_time_member.final) > 14");

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
        $this->db->select('u.month_name as month_name, karyawan.nik, karyawan.namaKaryawan, dp.nama as departemen, sc.nama as section, karyawan.kode, l.avg');
        $this->db->from('
            (
                select date_format(over_time.tanggal, "%m-%Y") as month_name, over_time_member.nik, karyawan.namaKaryawan, karyawan.kode, sum(over_time_member.final) as sum from over_time_member 
    right join over_time on over_time.id = over_time_member.id_ot 
    left join karyawan on karyawan.nik = over_time_member.nik 
    where over_time_member.final > 3 and over_time.hari = "N" 
    AND MONTH(over_time.tanggal) = MONTH(STR_TO_DATE("'.$tgl.'","%d-%m-%Y")) AND YEAR(STR_TO_DATE("'.$tgl.'","%d-%m-%Y"))
    group by date_format(over_time.tanggal, "%m-%Y"), over_time_member.nik
        ) as u
        ');
        $this->db->join('(
            select date_format(over_time.tanggal, "%m-%Y") as month_name, karyawan.nik, karyawan.namaKaryawan, karyawan.kode, avg(over_time_member.final) as avg, sum(over_time_member.final) as jml from over_time_member 
        right join over_time on over_time.id = over_time_member.id_ot 
        left join karyawan on karyawan.nik = over_time_member.nik 
        where over_time.hari = "N"
        AND MONTH(over_time.tanggal) = MONTH(STR_TO_DATE("'.$tgl.'","%d-%m-%Y")) AND YEAR(STR_TO_DATE("'.$tgl.'","%d-%m-%Y"))
        group by date_format(over_time.tanggal, "%m-%Y"), week(over_time.tanggal), karyawan.nik 
        having jml > 14
        ) as l
        ','u.nik = l.nik');
        $this->db->join('karyawan','karyawan.nik = u.nik');
        $this->db->join('posisi p','p.nik = u.nik');
        $this->db->join('departemen dp','dp.id = p.id_dep');
        $this->db->join('section sc','sc.id = p.id_sec');
        $this->db->where('u.kode', $cat);
        $this->db->group_by("u.nik");

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
        $this->db->select('date_format(over_time.tanggal, "%m-%Y") as month_name, karyawan.nik, karyawan.namaKaryawan, dp.nama as departemen, sc.nama as section, karyawan.kode, avg(over_time_member.final) as avg');
        $this->db->from('over_time_member');
        $this->db->join('over_time','over_time.id = over_time_member.id_ot');
        $this->db->join('karyawan','karyawan.nik = over_time_member.nik');
        $this->db->join('posisi p','p.nik = karyawan.nik');
        $this->db->join('departemen dp','dp.id = p.id_dep');
        $this->db->join('section sc','sc.id = p.id_sec');
        
        $this->db->where('MONTH(over_time.tanggal) = MONTH(STR_TO_DATE("'.$tgl.'","%d-%m-%Y"))');
        $this->db->where('YEAR(over_time.tanggal) = YEAR(STR_TO_DATE("'.$tgl.'","%d-%m-%Y"))');
        $this->db->where('karyawan.kode', $cat);
        $this->db->group_by(array("karyawan.kode", "karyawan.nik", "date_format(over_time.tanggal, '%m-%Y')"));
        $this->db->having("sum(over_time_member.final) > 56");

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