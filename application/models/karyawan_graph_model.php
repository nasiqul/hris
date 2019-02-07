<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Karyawan_graph_model extends CI_Model {

	public function select_all(){
		$i = 1;
		$hasil = array();

		$q = "SHOW TABLES Like '%2019'";
		$q1 = $this->db->query($q);

		if($q1->num_rows() > 0){
			foreach($q1->result() as $d){
				$str = $i."-2019";

				$this->db->select('karyawan.nik, karyawan.namaKaryawan, COUNT(`'.$str.'`.nik) as jml, "'.$str.'" as tablename');
				$this->db->from('`'.$str.'`');
				$this->db->join('karyawan','karyawan.nik = `'.$str.'`.nik');
				$query = $this->db->get();

				if($query->num_rows() > 0){
					foreach($query->result() as $data){
						array_push($hasil, $data);
					}
				}
				$i++;
			}
			return $hasil;
		}
	}

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

    public function by_total_kehadiran_date($tgl){
        $q = "SELECT * from (SELECT p.tanggal, p.shift, COUNT(*) AS jml from presensi as p where p.shift REGEXP '^[1-9]+$' group by p.tanggal,p.shift)  AS tbl WHERE DATE_FORMAT(tanggal, '%d-%m-%Y') = '".$tgl."'";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }
}
?>