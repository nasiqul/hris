<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Export_model extends CI_Model {
	public function export_presensi($data2)
	{

		$qcekJam = "SELECT jam_kerja FROM shift where shift = '".$data2['shift']."'";
		$cekJam = $this->db->query($qcekJam);

		// HITUNG JAM KERJA
		if($cekJam->num_rows() > 0){
			foreach($cekJam->result() as $data4){
				$jam_kerja = $data4->jam_kerja;
				$jam_masuk = $data4->jam_masuk;
			}
		}
		else {
			$jam_kerja = 0;
			$jam_masuk = 0;
		}


		$tgl = date('Y-m-d',strtotime($data2['tgl']));
		$qcekTgl = "SELECT * FROM kalender where tanggal = '".$tgl."'";
		$cekTgl = $this->db->query($qcekTgl);


		//CEK LIBUR
		if($cekTgl->num_rows() > 0)
		{
			// HARI NORMAL
			$hari = 'L';

			if ($data2['shift'] == '3' || $data2['shift'] == '2') {
				$time1 = new DateTime(date('Y-m-d H:i:s' ,strtotime('2019-01-01 '.$data2['masuk'])));
				$time2 = new DateTime(date('Y-m-d H:i:s' ,strtotime('2019-01-02 '.$data2['keluar'])));
			}
			else {
				$time1 = new DateTime(date('Y-m-d H:i:s' ,strtotime('2019-01-01 '.$data2['masuk'])));
				$time2 = new DateTime(date('Y-m-d H:i:s' ,strtotime('2019-01-01 '.$data2['keluar'])));	
			}


			$interval2 = $time1->diff($time2);
			$s2 = $interval2->format('%H:%i:%s');
			$str_time2 = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $s2);

			sscanf($str_time2, "%d:%d:%d", $hours2, $minutes2, $seconds2);

			$time_seconds2 = $hours2 * 3600 + $minutes2 * 60 + $seconds2;

			$jam = $time_seconds2;
			$jamK = $jam - 7200;

			if ($jamK > 0) 
				$d = $jam - 1800;
			else
				$d = $jam;

		}		
		else
		{
			// HARI NORMAL
			$hari = 'N';

			if ($data2['shift'] == '2') {
				$time1 = new DateTime(date('Y-m-d H:i:s' ,strtotime('2019-01-01 '.$data2['masuk'])));
				$time2 = new DateTime(date('Y-m-d H:i:s' ,strtotime('2019-01-01 '.$jam_masuk)));

				$interval = $time1->diff($time2);
				$s = $interval->format('%H:%i:%s');
				$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $s);

				sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

				$time_seconds = $hours * 3600 + $minutes * 60 + $seconds;

				if($time_seconds >= 1800)
					$time_masuk = $data2['masuk'];
				else 
					$time_masuk = $jam_masuk;

				$time3 = new DateTime(date('Y-m-d H:i:s' ,strtotime('2019-01-01 '.$time_masuk)));
				$time4 = new DateTime(date('Y-m-d H:i:s' ,strtotime('2019-01-02 '.$data2['keluar'])));
			}
			//HITUNG JAM AWAL
			else if ($data2['shift'] == '3') {
				$time1 = new DateTime(date('Y-m-d H:i:s' ,strtotime('2019-01-01 '.$data2['masuk'])));
				$time2 = new DateTime(date('Y-m-d H:i:s' ,strtotime('2019-01-02 '.$jam_masuk)));

				$interval = $time1->diff($time2);
				$s = $interval->format('%H:%i:%s');
				$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $s);

				sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

				$time_seconds = $hours * 3600 + $minutes * 60 + $seconds;

				if($time_seconds >= 1800)
					$time_masuk = $data2['masuk'];
				else 
					$time_masuk = $jam_masuk;

				$time3 = new DateTime(date('Y-m-d H:i:s' ,strtotime('2019-01-01 '.$time_masuk)));
				$time4 = new DateTime(date('Y-m-d H:i:s' ,strtotime('2019-01-01 '.$data2['keluar'])));
			}
			else {
				$time1 = new DateTime(date('Y-m-d H:i:s' ,strtotime('2019-01-01 '.$data2['masuk'])));
				$time2 = new DateTime(date('Y-m-d H:i:s' ,strtotime('2019-01-01 '.$jam_masuk)));

				$interval = $time1->diff($time2);
				$s = $interval->format('%H:%i:%s');
				$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $s);

				sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

				$time_seconds = $hours * 3600 + $minutes * 60 + $seconds;

				if($time_seconds >= 1800)
					$time_masuk = $data2['masuk'];
				else 
					$time_masuk = $jam_masuk;

				$time3 = new DateTime(date('Y-m-d H:i:s' ,strtotime('2019-01-01 '.$time_masuk)));
				$time4 = new DateTime(date('Y-m-d H:i:s' ,strtotime('2019-01-01 '.$data2['keluar'])));
			}

			$interval2 = $time3->diff($time4);
			$s2 = $interval2->format('%H:%i:%s');
			$str_time2 = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $s2);

			sscanf($str_time2, "%d:%d:%d", $hours2, $minutes2, $seconds2);

			$time_seconds2 = $hours2 * 3600 + $minutes2 * 60 + $seconds2;

			$jam = $time_seconds2 - $jam_kerja;
			$jamK = $jam - 7200;

			if ($jamK > 0) 
				$d = $jam - 1800;
			else
				$d = $jam;

		}


		$jam_lembur = ceil($d/1800)*1800;

		$query = "CALL masukData2 ('','".$data2['nik']."','".$data2['tgl']."', '".$data2['masuk']."','".$data2['keluar']."','".$data2['shift']."', '".$hari."' ,'".$jam_lembur."')";
		$result = $this->db->query($query);

		if($result){
			return true;
		}else{
			return $this->db->_error_message(); 
		}
	}
}
?>
