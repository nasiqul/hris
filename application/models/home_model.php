<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends CI_Model {

	var $column_order = array('tanggal','karyawan.nik','namaKaryawan','masuk','keluar','shift'); //set column field database for datatable orderable
    var $column_search = array('tanggal','karyawan.nik','namaKaryawan','masuk','keluar','shift'); //set column field database for datatable searchable 
    var $order = array('masuk' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

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
        $this->db->select('karyawan.nik, karyawan.namaKaryawan, presensi.tanggal, presensi.masuk, presensi.keluar, presensi.shift, sec.nama as sec, ssc.nama as subsec, group1.nama as grup');
        $this->db->from('presensi');
        $this->db->join('karyawan','karyawan.nik = presensi.nik', 'left');
        $this->db->join('posisi','posisi.nik = karyawan.nik', 'left');
        $this->db->join('section sec','sec.id = posisi.id_sec', 'left');
        $this->db->join('sub_section ssc','ssc.id = posisi.id_sub_sec', 'left');
        $this->db->join('group1','group1.id = posisi.id_group', 'left');
        $this->db->where('date(presensi.tanggal) = CURRENT_DATE()');
        $this->db->where('presensi.shift !=','0');
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

    public function report1(){
        $q = "SELECT tanggal, COUNT(*) AS jml from presensi where shift = '1' 
        AND MONTH(tanggal) = MONTH(CURRENT_DATE())
        AND YEAR(tanggal) = YEAR(CURRENT_DATE()) and tanggal not in (select tanggal from kalender) group by tanggal";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }

    public function report2(){
        $q = "SELECT tanggal, COUNT(*) AS jml from presensi where shift = '2' AND MONTH(tanggal) = MONTH(CURRENT_DATE()) AND YEAR(tanggal) = YEAR(CURRENT_DATE()) and tanggal not in (select tanggal from kalender)  group by tanggal";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }

    public function report3(){
        $q = "SELECT tanggal, COUNT(*) AS jml from presensi where shift = '3' AND MONTH(tanggal) = MONTH(CURRENT_DATE()) AND YEAR(tanggal) = YEAR(CURRENT_DATE()) and tanggal not in (select tanggal from kalender)  group by tanggal";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }

    public function report1_by_tgl($tgl){
        $q = "SELECT tanggal, COUNT(*) AS jml from presensi where shift = '1' AND MONTH(tanggal) = MONTH(STR_TO_DATE('".$tgl."', '%d-%m-%Y')) AND YEAR(tanggal) = YEAR(STR_TO_DATE('".$tgl."', '%d-%m-%Y')) and tanggal not in (select tanggal from kalender) group by tanggal";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }

    public function report2_by_tgl($tgl){
        $q = "SELECT tanggal, COUNT(*) AS jml from presensi where shift = '2' AND MONTH(tanggal) = MONTH(STR_TO_DATE('".$tgl."', '%d-%m-%Y')) AND YEAR(tanggal) = YEAR(STR_TO_DATE('".$tgl."', '%d-%m-%Y')) and tanggal not in (select tanggal from kalender)  group by tanggal";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }

    public function report3_by_tgl($tgl){
        $q = "SELECT tanggal, COUNT(*) AS jml from presensi where shift = '3' AND MONTH(tanggal) = MONTH(STR_TO_DATE('".$tgl."', '%d-%m-%Y')) AND YEAR(tanggal) = YEAR(STR_TO_DATE('".$tgl."', '%d-%m-%Y')) and tanggal not in (select tanggal from kalender)  group by tanggal";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }

    // -----------------------       HADIR / TIDAK HADIR --------------------------

    public function report1_1_by_tgl($tgl){
        $q = "SELECT tanggal, COUNT(*) AS jml, (Select count(nik) from presensi WHERE MONTH(tanggal) = MONTH(CURRENT_DATE()) AND YEAR(tanggal) = YEAR(CURRENT_DATE())) as total from presensi where shift REGEXP '^[1-9]+$' AND MONTH(tanggal) = MONTH(STR_TO_DATE('".$tgl."', '%d-%m-%Y')) AND YEAR(tanggal) = YEAR(STR_TO_DATE('".$tgl."', '%d-%m-%Y')) and tanggal not in (select tanggal from kalender)  group by tanggal";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }

    public function report2_2_by_tgl($tgl){
        $q = "SELECT tanggal, COUNT(*) AS jml from presensi where shift NOT REGEXP '^[1-9]+$' AND MONTH(tanggal) = MONTH(STR_TO_DATE('".$tgl."', '%d-%m-%Y')) AND YEAR(tanggal) = YEAR(STR_TO_DATE('".$tgl."', '%d-%m-%Y')) and tanggal not in (select tanggal from kalender)  group by tanggal";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }

    public function report1_1(){
        $q = "SELECT tanggal, COUNT(*) AS jml ,(Select count(nik) from presensi WHERE MONTH(tanggal) = MONTH(CURRENT_DATE()) AND YEAR(tanggal) = YEAR(CURRENT_DATE())) as total from presensi where shift  IN (1,2,3,'T','PC','DL') AND MONTH(tanggal) = MONTH(CURRENT_DATE()) AND YEAR(tanggal) = YEAR(CURRENT_DATE()) and tanggal not in (select tanggal from kalender)  group by tanggal";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }

    public function report2_2(){
        $q = "SELECT tanggal, COUNT(*) AS jml from presensi where shift NOT IN (1,2,3,'T','PC','DL') AND MONTH(tanggal) = MONTH(CURRENT_DATE()) AND YEAR(tanggal) = YEAR(CURRENT_DATE()) and tanggal not in (select tanggal from kalender)  group by tanggal";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }

    //---------------------------------------------------------------------------------

    public function by_total_kehadiran(){
        $q = "SELECT * from (SELECT p.tanggal, p.shift, COUNT(*) AS jml from presensi as p where p.shift REGEXP '^[1-9]+$' group by p.tanggal,p.shift)  AS tbl WHERE date(tanggal) = CURRENT_DATE()";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }

    public function by_persentase(){
        $q = "SELECT * from (SELECT p.tanggal, COUNT(*) AS jml from presensi as p where p.shift REGEXP '^[1-9]+$' group by p.tanggal) AS tbl WHERE date(tanggal) = CURRENT_DATE()";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }

    public function persentase_tidakMasuk(){
        $q = "SELECT * from (SELECT p.tanggal, COUNT(*) AS jml from presensi as p where p.shift <> '0' and p.shift <> '1' and p.shift <> '2' and p.shift <> '3' and p.shift <> 'OFF' and p.shift <> 'X' group by p.tanggal) AS tbl WHERE date(tanggal) = CURRENT_DATE()";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }


    public function laporan(){
        $q = "SELECT * FROM (SELECT tanggal,shift, COUNT(*) AS jml FROM presensi where shift = '1' GROUP BY tanggal,shift UNION ALL SELECT tanggal,shift, COUNT(*) AS jml FROM presensi where shift = '2' GROUP BY tanggal,shift ) AS U WHERE MONTH(U.tanggal) = MONTH(CURRENT_DATE()) AND YEAR(U.tanggal) = YEAR(CURRENT_DATE())";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }

    public function getMenu2($sess, $menu)
    {
     $q = "select nama_menu, url, parent_menu, icon from master_menu 
     left join role on master_menu.id_menu = role.id_menu
     LEFT JOIN login as l on role.user_id = l.role
     where l.username ='".$sess."' and master_menu.parent_menu ='".$menu."'";

     $query = $this->db->query($q);
     return $query->result();
 }

 public function getFiskal($tgl)
 {
    $q = "select fiskal from kalender_fy where DATE_FORMAT(tanggal,'%Y-%m') = '".$tgl."' limit 1";
    $query = $this->db->query($q);
    return $query->result();   
}

public function getFiskalAll()
{
    $q = "select fiskal, tanggal from kalender_fy group by fiskal";
    $query = $this->db->query($q);
    return $query->result();   
}

public function get_dep_all()
{
    $query = $this->db->get('section');
    return $query->result();
}

public function get_jabatan($nik)
{
    $q = "select jabatan from karyawan where nik = '".$nik."'";
    $query = $this->db->query($q);
    return $query->result();
}

public function get_report_absensi($tgl)
{
    $q = "select pin, nik, namaKaryawan, tanggalMasuk, jk from namaKaryawan";
    $query = $this->db->query($q);
    return $query->result();
}

public function getParentMenu()
{
    $q = "select parent_menu from master_menu group by parent_menu order by id_menu asc";
    $query = $this->db->query($q);
    return $query->result();
}

public function get_kep_all()
{
    $query = $this->db->get('master_keperluan');
    return $query->result();
}
}