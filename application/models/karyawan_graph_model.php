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
}
?>