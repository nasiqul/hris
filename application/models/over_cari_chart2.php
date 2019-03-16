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
        $this->db->select('date_format(over.tanggal, "%m-%Y") as month_name, karyawan.nik, karyawan.namaKaryawan, dp.nama as departemen, sc.nama as section, karyawan.kode, avg(over.jam) as avg');
        $this->db->from('over');
        $this->db->join('karyawan','karyawan.nik = over.nik', 'left');
        $this->db->join('posisi p','p.nik = karyawan.nik');
        $this->db->join('departemen dp','dp.id = p.id_dep');
        $this->db->join('section sc','sc.id = p.id_sec');
        $this->db->where('over.jam > 3');
        $this->db->where('over.status','N');
        $this->db->where('MONTH(over.tanggal) = MONTH(STR_TO_DATE("'.$tgl.'","%d-%m-%Y"))');
        $this->db->where('YEAR(over.tanggal) = YEAR(STR_TO_DATE("'.$tgl.'","%d-%m-%Y"))');
        $this->db->where('karyawan.kode', $cat);
        $this->db->group_by(array("date_format(over.tanggal, '%m-%Y')", "over.nik"));

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
        $this->db->select('b.month_name, karyawan.nik, karyawan.namaKaryawan, dp.nama as departemen, sc.nama as section, karyawan.kode, avg(b.jam) as avg');
        $this->db->from('(
            select date_format(over.tanggal, "%m-%Y") as month_name, week(over.tanggal) as week_name, karyawan.nik, karyawan.kode, sum(over.jam) as jam from over
            left join karyawan on karyawan.nik = over.nik 
            where over.status = "N" AND MONTH(over.tanggal) = MONTH(STR_TO_DATE("'.$tgl.'","%d-%m-%Y"))
            AND YEAR(over.tanggal) = YEAR(STR_TO_DATE("'.$tgl.'","%d-%m-%Y"))
            group by date_format(over.tanggal, "%m-%Y"), week(over.tanggal), karyawan.nik, karyawan.kode
            having jam > 14
        ) b');
        $this->db->join('karyawan','karyawan.nik = b.nik', 'left');
        $this->db->join('posisi p','p.nik = karyawan.nik');
        $this->db->join('departemen dp','dp.id = p.id_dep');
        $this->db->join('section sc','sc.id = p.id_sec');
        $this->db->where('karyawan.kode', $cat);
        $this->db->group_by("b.nik");

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
                select date_format(over.tanggal, "%m-%Y") as month_name, over.nik, karyawan.namaKaryawan, karyawan.kode, sum(over.jam) as sum from over 
    left join karyawan on karyawan.nik = over.nik 
    where over.jam > 3 and over.status = "N" 
    AND MONTH(over.tanggal) = MONTH(STR_TO_DATE("'.$tgl.'","%d-%m-%Y")) AND YEAR(STR_TO_DATE("'.$tgl.'","%d-%m-%Y"))
    group by date_format(over.tanggal, "%m-%Y"), over.nik
        ) as u
        ');
        $this->db->join('(
            select date_format(over.tanggal, "%m-%Y") as month_name, karyawan.nik, karyawan.namaKaryawan, karyawan.kode, avg(over.jam) as avg, sum(over.jam) as jml from over
        left join karyawan on karyawan.nik = over.nik 
        where over.status = "N"
        AND MONTH(over.tanggal) = MONTH(STR_TO_DATE("'.$tgl.'","%d-%m-%Y")) AND YEAR(STR_TO_DATE("'.$tgl.'","%d-%m-%Y"))
        group by date_format(over.tanggal, "%m-%Y"), week(over.tanggal), karyawan.nik 
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
        $this->db->select('date_format(over.tanggal, "%m-%Y") as month_name, karyawan.nik, karyawan.namaKaryawan, dp.nama as departemen, sc.nama as section, karyawan.kode, avg(over.jam) as avg');
        $this->db->from('over');
        $this->db->join('karyawan','karyawan.nik = over.nik');
        $this->db->join('posisi p','p.nik = karyawan.nik');
        $this->db->join('departemen dp','dp.id = p.id_dep');
        $this->db->join('section sc','sc.id = p.id_sec');
        
        $this->db->where('MONTH(over.tanggal) = MONTH(STR_TO_DATE("'.$tgl.'","%d-%m-%Y"))');
        $this->db->where('YEAR(over.tanggal) = YEAR(STR_TO_DATE("'.$tgl.'","%d-%m-%Y"))');
        $this->db->where('karyawan.kode', $cat);
        $this->db->group_by(array("karyawan.kode", "karyawan.nik", "date_format(over.tanggal, '%m-%Y')"));
        $this->db->having("sum(over.jam) > 56");

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
        $this->db->select('date_format(over.tanggal, "%m-%Y") as month_name, karyawan.nik, karyawan.namaKaryawan, dp.nama as departemen, sc.nama as section, karyawan.kode, avg(over.jam) as avg');
        $this->db->from('over');
        $this->db->join('karyawan','karyawan.nik = over.nik', 'left');
        $this->db->join('posisi p','p.nik = karyawan.nik');
        $this->db->join('departemen dp','dp.id = p.id_dep');
        $this->db->join('section sc','sc.id = p.id_sec');
        $this->db->where('over.jam > 3');
        $this->db->where('over.status','N');
        $this->db->where('MONTH(over.tanggal) = MONTH(STR_TO_DATE("'.$tgl.'","%d-%m-%Y"))');
        $this->db->where('YEAR(over.tanggal) = YEAR(STR_TO_DATE("'.$tgl.'","%d-%m-%Y"))');
        $this->db->group_by(array("date_format(over.tanggal, '%m-%Y')", "over.nik"));

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
        $this->db->select('b.month_name, karyawan.nik, karyawan.namaKaryawan, dp.nama as departemen, sc.nama as section, karyawan.kode, avg(b.jam) as avg');
        $this->db->from('(
                select date_format(over.tanggal, "%m-%Y") as month_name, week(over.tanggal) as week_name, karyawan.nik, karyawan.kode, sum(over.jam) as jam from over
                left join karyawan on karyawan.nik = over.nik 
                where over.status = "N" AND MONTH(over.tanggal) = MONTH(STR_TO_DATE("'.$tgl.'","%d-%m-%Y"))
                 AND YEAR(over.tanggal) = YEAR(STR_TO_DATE("'.$tgl.'","%d-%m-%Y"))
                group by date_format(over.tanggal, "%m-%Y"), week(over.tanggal), karyawan.nik, karyawan.kode
                having jam > 14
            ) as b');
        $this->db->join('karyawan','karyawan.nik = b.nik', 'left');
        $this->db->join('posisi p','p.nik = karyawan.nik');
        $this->db->join('departemen dp','dp.id = p.id_dep');
        $this->db->join('section sc','sc.id = p.id_sec');
        $this->db->group_by("b.nik");

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
        $this->db->select('u.month_name as month_name, karyawan.nik, karyawan.namaKaryawan, dp.nama as departemen, sc.nama as section, karyawan.kode, l.avg');
        $this->db->from('
            (
                select date_format(over.tanggal, "%m-%Y") as month_name, over.nik, karyawan.namaKaryawan, karyawan.kode, sum(over.jam) as sum from over 
    left join karyawan on karyawan.nik = over.nik 
    where over.jam > 3 and over.status = "N" 
    AND MONTH(over.tanggal) = MONTH(STR_TO_DATE("'.$tgl.'","%d-%m-%Y")) AND YEAR(STR_TO_DATE("'.$tgl.'","%d-%m-%Y"))
    group by date_format(over.tanggal, "%m-%Y"), over.nik
        ) as u
        ');
        $this->db->join('(
            select date_format(over.tanggal, "%m-%Y") as month_name, karyawan.nik, karyawan.namaKaryawan, karyawan.kode, avg(over.jam) as avg, sum(over.jam) as jml from over
        left join karyawan on karyawan.nik = over.nik 
        where over.status = "N"
        AND MONTH(over.tanggal) = MONTH(STR_TO_DATE("'.$tgl.'","%d-%m-%Y")) AND YEAR(STR_TO_DATE("'.$tgl.'","%d-%m-%Y"))
        group by date_format(over.tanggal, "%m-%Y"), week(over.tanggal), karyawan.nik 
        having jml > 14
        ) as l
        ','u.nik = l.nik');
        $this->db->join('karyawan','karyawan.nik = u.nik');
        $this->db->join('posisi p','p.nik = u.nik');
        $this->db->join('departemen dp','dp.id = p.id_dep');
        $this->db->join('section sc','sc.id = p.id_sec');
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
        $this->db->select('date_format(over.tanggal, "%m-%Y") as month_name, karyawan.nik, karyawan.namaKaryawan, dp.nama as departemen, sc.nama as section, karyawan.kode, avg(over.jam) as avg');
        $this->db->from('over');
        $this->db->join('karyawan','karyawan.nik = over.nik');
        $this->db->join('posisi p','p.nik = karyawan.nik');
        $this->db->join('departemen dp','dp.id = p.id_dep');
        $this->db->join('section sc','sc.id = p.id_sec');
        
        $this->db->where('MONTH(over.tanggal) = MONTH(STR_TO_DATE("'.$tgl.'","%d-%m-%Y"))');
        $this->db->where('YEAR(over.tanggal) = YEAR(STR_TO_DATE("'.$tgl.'","%d-%m-%Y"))');
        $this->db->group_by(array("karyawan.kode", "karyawan.nik", "date_format(over.tanggal, '%m-%Y')"));
        $this->db->having("sum(over.jam) > 56");

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