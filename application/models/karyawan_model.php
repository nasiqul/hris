<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Karyawan_model extends CI_Model {
	var $column_order = array('k.nik','namaKaryawan','dep/subSec','sec/Group','tanggalMasuk','statusKaryawan'); //set column field database for datatable orderable
    var $column_search = array('k.nik','namaKaryawan','dep/subSec','sec/Group','DATE_FORMAT(tanggalMasuk, "%d %M %Y")','statusKaryawan','status'); //set column field database for datatable searchable 
    var $order = array('tanggalMasuk' => 'asc', 'nik' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_data_karyawan()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_datatables_query()
    {
        $this->db->select("pin, k.nik, costCenter, foto, namaKaryawan, dep/subSec as dep, sec/Group as group, kode, tanggalMasuk, jk, statusKaryawan, grade, namaGrade, jabatan, statusKeluarga, tanggalLahir, tempatLahir, alamat, hp, ktp, rekening, bpjstk, jp, bpjskes, npwp, status");
        $this->db->from('karyawan k');

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

    public function get_data_karyawan_coba()
    {
        $this->_get_datatables_query2();
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_datatables_query2()
    {
        $this->db->select("k.nik, k.namaKaryawan, dv.nama as namadev, dp.nama as namadep, k.tanggalMasuk, k.statusKaryawan, k.status");
        $this->db->from('karyawan k');
        $this->db->join('posisi p','k.nik = p.nik', 'left');
        $this->db->join('devisi dv','p.id_devisi = dv.id', 'left');
        $this->db->join('departemen dp','p.id_dep = dp.id', 'left');
        $this->db->join('section sec','p.id_sec = sec.id', 'left');
        $this->db->join('sub_section ssec','p.id_sub_sec = ssec.id', 'left');
        $this->db->join('group1 gr','p.id_group = gr.id', 'left');

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
        $this->db->from('karyawan');
        return $this->db->count_all_results();
    }

    public function get_data_karyawan_by_nik($nik)
    {
        $this->db->select("pin, k.nik, costCenter, foto, namaKaryawan, dep/subSec as dep, sec/Group as group, kode, tanggalMasuk, jk, statusKaryawan, grade, namaGrade, jabatan, statusKeluarga, tanggalLahir, tempatLahir, alamat, hp, ktp, rekening, bpjstk, jp, bpjskes, npwp, status");
        $this->db->from('karyawan k');
        $this->db->like('k.nik',$nik);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_data_karyawan_by_nik2($nik)
    {
        $this->db->select("pin, k.nik, costCenter, foto, namaKaryawan, d.nama as dev, dp.nama as dep, sc.nama as sec,kode, tanggalMasuk, jk, statusKaryawan, grade, namaGrade, jabatan, statusKeluarga, tanggalLahir, tempatLahir, alamat, hp, ktp, rekening, bpjstk, jp, bpjskes, npwp, status, p.id_devisi, p.id_dep, p.id_sec, p.id_sub_sec");
        $this->db->from('karyawan k');
        $this->db->join('posisi p', 'p.nik = k.nik', 'left');
        $this->db->join('devisi d', 'p.id_devisi = d.id', 'left');
        $this->db->join('departemen dp', 'p.id_dep = dp.id', 'left');
        $this->db->join('section sc', 'p.id_sec = sc.id', 'left');
        $this->db->join('sub_section sub_sec', 'p.id_sub_sec = sub_sec.id', 'left');
        $this->db->join('group1 gr', 'p.id_group = gr.id', 'left');
        $this->db->where('k.nik',$nik);
        $query = $this->db->get();
        return $query->result();
    }

    public function by_status(){
        $q = "SELECT statusKaryawan, COUNT(*) AS jml from karyawan group by statusKaryawan ORDER BY FIELD(statusKaryawan,'Tetap','Percobaan','Kontrak 2','Kontrak 1')";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }

    public function by_gender(){
        $q = "SELECT jk, COUNT(nik) AS jml from karyawan group by jk";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }

    public function by_grade(){
        $q = "SELECT grade, COUNT(*) AS jml from karyawan where grade <> '-' group by grade ORDER BY FIELD(grade, 'E0','E1','E2','E3','E4','E5','E6','E7','E8','L1','L2','L3','L4','M1','M2','M3','M4','M5','D3') ASC";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }

    public function by_kode(){
        $q = "SELECT dp.nama as dep, COUNT(k.nik) as jml from karyawan k
                LEFT JOIN posisi p ON p.nik = k.nik 
                JOIN departemen dp ON dp.id = p.id_dep
                GROUP BY dp.id
                ORDER BY jml ASC";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }

    public function by_posisi(){
        $q = "SELECT jabatan, COUNT(*) AS jml from karyawan where jabatan <> '-' and jabatan <> '' group by jabatan order by jml ASC";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }

    public function tot(){
        $q = "SELECT COUNT(*) AS jml from karyawan";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }

    public function getKeluarga()
    {
        $q = "SELECT statusKeluarga from karyawan group by statusKeluarga";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }    
    }

    public function getSection($id_dep)
    {
        $q = "SELECT * from section where id_departemen = ".$id_dep."";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }    
    }

    public function get_dev()
    {
        $q = "SELECT * from devisi";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }    
    }

    public function get_grade()
    {
        $q = "SELECT * from grade group by kode_grade";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }    
    }

    public function get_jabatan()
    {
        $q = "SELECT * from jabatan";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }    
    }

    public function get_kode()
    {
        $q = "SELECT * from kode";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }    
    }

    public function get_nama_grade($id)
    {
        $q = "SELECT * from grade where kode_grade = '".$id."'";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }    
    }

    public function get_dep($id)
    {
        $q = "SELECT * from departemen where id_devisi = ".$id."";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }    
    }

    public function get_sec($id)
    {
        $q = "SELECT * from section where id_departemen = ".$id."";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }    
    }

    public function get_subsec($id)
    {
        $q = "SELECT * from sub_section where id_sec = ".$id."";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }    
    }

    public function get_group($id)
    {
        $q = "SELECT * from group1 where id_sub = ".$id."";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }    
    }

    public function tambah($nik, $nama, $tmptL, $tglL, $jk, $ktp, $alamat, $statusK, $dev, $dep, $sec, $subsec, $group, $grade, $ngrade, $jab, $kode, $statusKar, $pin, $tglM, $cs, $hp, $bpjstk, $bpjskes, $no_rek, $npwp, $jp){

        $data = array(
            'pin' => $pin,
            'nik' => $nik,
            'costCenter' => $cs,
            'namaKaryawan' => $nama,
            'kode' => $kode,
            'tanggalMasuk' => date('Y-m-d', strtotime($tglM)),
            'jk' => $jk,
            'statusKaryawan' => $statusKar,
            'grade' => $grade,
            'namaGrade' => $ngrade,
            'jabatan' => $jab,
            'statusKeluarga' => $statusK,
            'tempatLahir' => $tmptL,
            'tanggalLahir' => date('Y-m-d', strtotime($tglL)),
            'alamat' => $alamat,
            'hp' => $hp,
            'ktp' => $ktp,
            'rekening' => $no_rek,
            'bpjstk' => $bpjstk,
            'jp' => $jp,
            'bpjskes' => $bpjskes,
            'npwp' => $npwp,
            'status' => 'Aktif'
        );

        $this->db->insert('karyawan', $data);

        $data2 = array(
            'nik' => $nik,
            'id_devisi' => $dev,
            'id_dep' => $dep,
            'id_sec' => $sec,
            'id_sub_sec' => $subsec,
            'id_group' => $group
        );

        $this->db->insert('posisi', $data2);
    }
}
?>