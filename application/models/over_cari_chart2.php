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
        $this->_get_over_cari($tgl,$cat);
        if($_GET['length'] != -1)
            $this->db->limit($_GET['length'], $_GET['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_over_cari($tgl, $cat)
    {
        $this->db->select('*');
        $this->db->from('(
            select a.nik, ROUND( sum(jam / org),2) as avg,karyawan.namaKaryawan, dp.nama as departemen, sc.nama as section, karyawan.kode from (
            select nik, count(nik) as org, sum(jam) as jam from (
            select nik,tanggal, jam from over where DATE_FORMAT(tanggal,"%Y-%m")="'.$tgl.'" and Status = "N" and jam > 3 ORDER BY nik asc
            ) d group by nik
            ) a  
            LEFT JOIN karyawan on karyawan.nik = a.nik
            JOIN posisi p on p.nik = karyawan.nik
            JOIN departemen dp on dp.id = p.id_dep
            join section sc on sc.id = p.id_sec
            WHERE karyawan.kode="'.$cat.'"
            group by a.nik
        )a');


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
        $this->db->select('*');
        $this->db->from(' (
            select a.nik,a.avg,karyawan.namaKaryawan, dp.nama as departemen, sc.nama as section, karyawan.kode  from (
            select nik, ROUND( sum(jam/jml),2) as avg from (
            select nik, sum(jam) as jam, sum(org) as jml from (
            select *,COUNT(nik) as org from (
            select nik,SUM(jam) as jam, WEEK(tanggal) as wek from over WHERE DATE_FORMAT(tanggal,"%Y-%m")="'.$tgl.'" and status = "n" GROUP BY WEEK(tanggal),nik ORDER BY nik 
            )a WHERE a.jam > 14 GROUP BY nik, wek
            ) a GROUP BY a.nik
            ) a GROUP BY a.nik
            ) a

            LEFT JOIN karyawan on karyawan.nik = a.nik
            JOIN posisi p on p.nik = karyawan.nik
            JOIN departemen dp on dp.id = p.id_dep
            join section sc on sc.id = p.id_sec
            WHERE karyawan.kode="'.$cat.'"
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
       
        $this->db->select('c.*');
        $this->db->from('
            (
            select a.nik, ROUND( sum(jam / org),2) as avg,karyawan.namaKaryawan, dp.nama as departemen, sc.nama as section, karyawan.kode from (
            select nik, count(nik) as org, sum(jam) as jam from (
            select nik,tanggal, jam from over where DATE_FORMAT(tanggal,"%Y-%m")="'.$tgl.'" and Status = "N" and jam > 3 ORDER BY nik asc
            ) d group by nik
            ) a  
            LEFT JOIN karyawan on karyawan.nik = a.nik
            JOIN posisi p on p.nik = karyawan.nik
            JOIN departemen dp on dp.id = p.id_dep
            join section sc on sc.id = p.id_sec
            WHERE karyawan.kode="'.$cat.'"
            group by a.nik
            )a
            INNER JOIN (
            select * from (
            select a.nik,a.avg,karyawan.namaKaryawan, dp.nama as departemen, sc.nama as section, karyawan.kode  from (
            select nik, ROUND( sum(jam/jml),2) as avg from (
            select nik, sum(jam) as jam, sum(org) as jml from (
            select *,COUNT(nik) as org from (
            select nik,SUM(jam) as jam, WEEK(tanggal) as wek from over WHERE DATE_FORMAT(tanggal,"%Y-%m")="'.$tgl.'" and status = "n" GROUP BY WEEK(tanggal),nik ORDER BY nik 
            )a WHERE a.jam > 14 GROUP BY nik, wek
            ) a GROUP BY a.nik
            ) a GROUP BY a.nik
            ) a

            LEFT JOIN karyawan on karyawan.nik = a.nik
            JOIN posisi p on p.nik = karyawan.nik
            JOIN departemen dp on dp.id = p.id_dep
            join section sc on sc.id = p.id_sec
            WHERE karyawan.kode="'.$cat.'"
            ) b ) c
            on a.nik = c.nik
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
        $this->db->select('*');
        $this->db->from('
            (
            select a.nik, ROUND( sum(jam),2) as avg,karyawan.namaKaryawan, dp.nama as departemen, sc.nama as section, karyawan.kode from (
            select nik, count(nik) as org, sum(jam) as jam from (
            select nik,tanggal, jam from over where DATE_FORMAT(tanggal,"%Y-%m")="'.$tgl.'" and Status = "N" ORDER BY nik asc
            ) d group by nik 
            ) a 
            LEFT JOIN karyawan on karyawan.nik = a.nik
            JOIN posisi p on p.nik = karyawan.nik
            JOIN departemen dp on dp.id = p.id_dep
            join section sc on sc.id = p.id_sec
            WHERE karyawan.kode="'.$cat.'" 
            group by a.nik
        )a where a.avg > 56
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


    // --------------------------- 3 JAM TABEL ------------------

    public function get_data_3_t($tgl)
    {
        $this->_get_over_cari_t($tgl);
        if($_GET['length'] != -1)
            $this->db->limit($_GET['length'], $_GET['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_over_cari_t($tgl)
    {
        $this->db->select('*');
        $this->db->from('(
            select a.nik, ROUND( sum(jam / org),2) as avg,karyawan.namaKaryawan, dp.nama as departemen, sc.nama as section, karyawan.kode from (
            select nik, count(nik) as org, sum(jam) as jam from (
            select nik,tanggal, jam from over where DATE_FORMAT(tanggal,"%Y-%m")="'.$tgl.'" and Status = "N" and jam > 3 ORDER BY nik asc
            ) d group by nik
            ) a  
            LEFT JOIN karyawan on karyawan.nik = a.nik
            JOIN posisi p on p.nik = karyawan.nik
            JOIN departemen dp on dp.id = p.id_dep
            join section sc on sc.id = p.id_sec
           
            group by a.nik
        )a');

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

    function count_filtered_3_t($tgl)
    {
        $this->_get_over_cari_t($tgl);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_3_t($tgl)
    {
        $this->_get_over_cari_t($tgl);
        return $this->db->count_all_results();
    }

    // ----------------------------------- [    14 JAM TABEL  ] ------------------------------

    public function get_data_14_t($tgl)
    {
        $this->_get_over_cari_14_t($tgl);
        if($_GET['length'] != -1)
            $this->db->limit($_GET['length'], $_GET['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_over_cari_14_t($tgl)
    {
        $this->db->select('*');
        $this->db->from(' (
            select a.nik,a.avg,karyawan.namaKaryawan, dp.nama as departemen, sc.nama as section, karyawan.kode  from (
            select nik, ROUND( sum(jam/jml),2) as avg from (
            select nik, sum(jam) as jam, sum(org) as jml from (
            select *,COUNT(nik) as org from (
            select nik,SUM(jam) as jam, WEEK(tanggal) as wek from over WHERE DATE_FORMAT(tanggal,"%Y-%m")="'.$tgl.'" and status = "n" GROUP BY WEEK(tanggal),nik ORDER BY nik 
            )a WHERE a.jam > 14 GROUP BY nik, wek
            ) a GROUP BY a.nik
            ) a GROUP BY a.nik
            ) a

            LEFT JOIN karyawan on karyawan.nik = a.nik
            JOIN posisi p on p.nik = karyawan.nik
            JOIN departemen dp on dp.id = p.id_dep
            join section sc on sc.id = p.id_sec
            
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

    function count_filtered_14_t($tgl)
    {
        $this->_get_over_cari_14_t($tgl);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_14_t($tgl)
    {
        $this->_get_over_cari_14_t($tgl);
        return $this->db->count_all_results();
    }


    // ----------------------------------- [   3 JAM & 14 JAM TABEL  ] ------------------------------

    public function get_data_3_14_t($tgl)
    {
        $this->_get_over_cari_3_14_t($tgl);
        if($_GET['length'] != -1)
            $this->db->limit($_GET['length'], $_GET['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_over_cari_3_14_t($tgl)
    {
         $this->db->select('c.*');
        $this->db->from('
            (
            select a.nik, ROUND( sum(jam / org),2) as avg,karyawan.namaKaryawan, dp.nama as departemen, sc.nama as section, karyawan.kode from (
            select nik, count(nik) as org, sum(jam) as jam from (
            select nik,tanggal, jam from over where DATE_FORMAT(tanggal,"%Y-%m")="'.$tgl.'" and Status = "N" and jam > 3 ORDER BY nik asc
            ) d group by nik
            ) a  
            LEFT JOIN karyawan on karyawan.nik = a.nik
            JOIN posisi p on p.nik = karyawan.nik
            JOIN departemen dp on dp.id = p.id_dep
            join section sc on sc.id = p.id_sec
           
            group by a.nik
            )a
            INNER JOIN (
            select * from (
            select a.nik,a.avg,karyawan.namaKaryawan, dp.nama as departemen, sc.nama as section, karyawan.kode  from (
            select nik, ROUND( sum(jam/jml),2) as avg from (
            select nik, sum(jam) as jam, sum(org) as jml from (
            select *,COUNT(nik) as org from (
            select nik,SUM(jam) as jam, WEEK(tanggal) as wek from over WHERE DATE_FORMAT(tanggal,"%Y-%m")="'.$tgl.'" and status = "n" GROUP BY WEEK(tanggal),nik ORDER BY nik 
            )a WHERE a.jam > 14 GROUP BY nik, wek
            ) a GROUP BY a.nik
            ) a GROUP BY a.nik
            ) a

            LEFT JOIN karyawan on karyawan.nik = a.nik
            JOIN posisi p on p.nik = karyawan.nik
            JOIN departemen dp on dp.id = p.id_dep
            join section sc on sc.id = p.id_sec
           
            ) b ) c
            on a.nik = c.nik
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

    function count_filtered_3_14_t($tgl)
    {
        $this->_get_over_cari_3_14_t($tgl);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_3_14_t($tgl)
    {
        $this->_get_over_cari_3_14_t($tgl);
        return $this->db->count_all_results();
    }


    // ----------------------------------- [   56 JAM  TABEL ] ------------------------------

    public function get_data_56_t($tgl)
    {
        $this->_get_over_cari_56_t($tgl);
        if($_GET['length'] != -1)
            $this->db->limit($_GET['length'], $_GET['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_over_cari_56_t($tgl)
    {
        $this->db->select('*');
        $this->db->from('
            (
            select a.nik, ROUND( sum(jam),2) as avg,karyawan.namaKaryawan, dp.nama as departemen, sc.nama as section, karyawan.kode from (
            select nik, count(nik) as org, sum(jam) as jam from (
            select nik,tanggal, jam from over where DATE_FORMAT(tanggal,"%Y-%m")="'.$tgl.'" and Status = "N" ORDER BY nik asc
            ) d group by nik 
            ) a 
            LEFT JOIN karyawan on karyawan.nik = a.nik
            JOIN posisi p on p.nik = karyawan.nik
            JOIN departemen dp on dp.id = p.id_dep
            join section sc on sc.id = p.id_sec
           
            group by a.nik
        )a where a.avg > 56
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

    function count_filtered_56_t($tgl)
    {
        $this->_get_over_cari_56_t($tgl);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_56_t($tgl)
    {
        $this->_get_over_cari_56_t($tgl);
        return $this->db->count_all_results();
    }
}
?>