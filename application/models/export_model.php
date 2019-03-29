<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Export_model extends CI_Model {
	public function export_presensi($data2)
	{
		$qcekJam = "SELECT jam_kerja, jam_masuk FROM shift where shift = '".$data2['shift']."'";
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
			// HARI LIBUR
			$hari = 'L';

			if ($data2['shift'] == '3' || $data2['shift'] == '2') {
				$time1 = new DateTime(date('Y-m-d H:i:s' ,strtotime('2019-01-01 '.$data2['masuk'])));
				$time2 = new DateTime(date('Y-m-d H:i:s' ,strtotime('2019-01-02 '.$data2['keluar'])));
			}
			else {
				$time1 = new DateTime(date('Y-m-d H:i:s' ,strtotime('2019-01-01 '.$data2['masuk'])));
				$time2 = new DateTime(date('Y-m-d H:i:s' ,strtotime('2019-01-01 '.$data2['keluar'])));	
			}

			$haric = date('w' ,strtotime($data2['tgl']));

			$quer = 'select IFNULL(sum(TIME_TO_SEC(duration)),0) as istirahat from breaktime where day =  "'.$haric.'" and breaktime.start >= "'.$data2['masuk'].'" and breaktime.end <= "'.$data2['keluar'].'"';
			$query8 = $this->db->query($quer);

			foreach($query8->result() as $data3){
				$istirahat = $data3->istirahat;
			}

			$interval2 = $time1->diff($time2);
			$s2 = $interval2->format('%H:%i:%s');
			$str_time2 = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $s2);

			sscanf($str_time2, "%d:%d:%d", $hours2, $minutes2, $seconds2);

			$time_seconds2 = $hours2 * 3600 + $minutes2 * 60 + $seconds2;

			$jam = $time_seconds2;

			$d = $jam - (int) $istirahat;

		}		
		else
		{
			// HARI NORMAL
			$hari = 'N';

			if ($data2['shift'] == '2') {
				//HITUNG JAM AWAL
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
			
			else if ($data2['shift'] == '3') {
				//HITUNG JAM AWAL
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
			else if ($data2['shift'] == '1') {
				//HITUNG JAM AWAL
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


			if ($data2['shift'] == '1' || $data2['shift'] == '2' || $data2['shift'] == '3') {
				$interval2 = $time3->diff($time4);
				$s2 = $interval2->format('%H:%i:%s');
				$str_time2 = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $s2);

				sscanf($str_time2, "%d:%d:%d", $hours2, $minutes2, $seconds2);

				$time_seconds2 = $hours2 * 3600 + $minutes2 * 60 + $seconds2;

				$jam = $time_seconds2 - $jam_kerja;

				if ($jam > 7200) 
					$d = $jam - 1800;
				else
					$d = $jam;
			}
			else {
				$d = 0;
				$jam_lembur = 0;

			}

		}
		
		$c = round($d/3600,1);
        $jam_lembur = round($c*2)/2;

		$query = "CALL masukData2 ('".$data2['pin']."','".$data2['nik']."','".$data2['tgl']."', '".$data2['masuk']."','".$data2['keluar']."','".$data2['shift']."', '".$hari."' ,'".$jam_lembur."')";
		$result = $this->db->query($query);

		if($result){
			return true;
		}else{
			return $this->db->_error_message(); 
		}
	}
}
?>
