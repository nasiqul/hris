<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ot extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('over_model');
		$this->load->model('cari_over');
		$this->load->library('ciqrcode');
	}

	public function ot_submit()
	{
		$no_doc = $_POST['no'];
		$tgl = date('Y-m-d',strtotime($_POST['tgl']));
		$dep = $_POST['dep'];
		$sec = $_POST['sec'];
		$kep = $_POST['kep'];
		$cat = $_POST['cat'];

		$params['data'] = $no_doc;
		$params['level'] = 'H';
		$params['size'] = 10;
		$params['savename'] = 'app/qr_lembur/'.$no_doc.'.png';
		$this->ciqrcode->generate($params);

		$kalender = $this->over_model->get_calendar($tgl);

		if ($kalender == 0)
			$this->over_model->save_master($no_doc, $tgl, $dep, $sec, $kep, $cat, 'B');
		else
			$this->over_model->save_master($no_doc, $tgl, $dep, $sec, $kep, $cat, 'L');
	}

	public function ot_member_submit()
	{
		$no_doc = $_POST['nodoc2'];

		$nik = $_POST['nik'.$i];
		$dari = $_POST['dari'.$i];
		$sampai = $_POST['sampai'.$i];
		$jam = $_POST['jam'.$i];
		$trans = $_POST['trans'.$i];
		$makan = $_POST['makan'.$i];
		$exfood = $_POST['exfood'.$i];

		$this->over_model->save_member($no_doc, $nik, $dari, $sampai, $jam, $trans, $makan, $exfood);
		
	}

	public function ajax_ot()
	{
		
		$list = $this->over_model->get_data_ot();
		$aktual = 0;

		$data = array();
		foreach ($list as $key) {
			$row = array();
			$tg = date("d-m-Y",strtotime($key->tanggal));
			$row[] = $key->id;
			$row[] = $tg;
			$row[] = $key->nik;
			$row[] = $key->namaKaryawan;
			$row[] = $key->masuk;
			$row[] = $key->keluar.$key->shift;
			$row[] = $key->jam;

			if ($key->shift == 1) {
				$date = '2007-05-14';

				$time = strtotime($date." ".$key->masuk);
				$datang = strtotime($date.' 07:00:00');

				$time2 = strtotime($date." ".$key->keluar);
				$pulang = strtotime($date.' 16:00:00');

				$jam = ($time2 - $pulang) + ($datang - $time);

				$aktual =  floor((($jam / 60) / 60) * 2) / 2;
				$row[] = $aktual;
			}
			else if ($key->shift == 2) {
				$date = '2007-05-14';

				$time = strtotime($date." ".$key->masuk);
				$datang = strtotime($date.' 16:00:00');

				$time2 = strtotime($date." ".$key->keluar);
				$pulang = strtotime($date.' 00:00:00');

				$jam = ($time2 - $pulang) + ($datang - $time);

				$aktual =  floor((($jam / 60) / 60) * 2) / 2;
				$row[] = $aktual;
			}
			else
				$row[] = "";

			$row[] = $aktual - $key->jam;
			
			if ($key->jam > $aktual) {
				$lembur = $aktual;
				$row[] = $lembur;
			}
			else {
				$lembur = $key->jam;
				$row[] = $lembur;
			}

			if ($key->status == 0) {
				$row[] = "<button class='btn btn-primary btn-xs' onclick='detail_spl(".$key->id.")'>Detail</button>
				<button class='btn btn-success btn-xs' onclick='modalOpen(\"".$key->nik."\",".$lembur.",\"".$tg."\")'><i class='fa fa-thumbs-up'></i> Confirm</button>";
			}
			else {
				$row[] = "<button class='btn btn-primary btn-xs' onclick='detail_spl(".$key->id.")'>Detail</button>";
			}

			$data[] = $row;
		}

		$tot = $this->over_model->count_all();
		$filter = $this->over_model->count_filtered();

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $tot,
			"recordsFiltered" => $filter,
			"data" => $data
		);
            //output to json format
		echo json_encode($output);
	}

	public function ajax_spl_data()
	{
		$id = $_POST['id'];
		$list = $this->over_model->get_over_by_id($id);
		$data = array();
		foreach ($list as $key) {
			$row = array();
			$row[] = $key->id_over;
			$row[] = date("l",strtotime($key->tanggal));
			$row[] = date("d-m-Y",strtotime($key->tanggal));
			$row[] = $key->departemen;
			$row[] = $key->section;
			$row[] = $key->keperluan;
			$row[] = $key->catatan;

			$data[] = $row;
		}

            //output to json format
		echo json_encode($data);
	}

	public function ajax_spl_data2()
	{
		$id = $_POST['id'];
		$list = $this->over_model->get_over_by_id_member($id);
		$no = 1;
		$data = array();
		foreach ($list as $key) {
			$row = array();
			$row[] = $no;
			$row[] = $key->id_over;
			$row[] = $key->tanggal;
			$row[] = $key->nik;
			$row[] = $key->namaKaryawan;
			$row[] = $key->dari;
			$row[] = $key->sampai;
			$row[] = $key->jam;
			$row[] = $key->transport;
			$row[] = $key->makan;
			$row[] = $key->ext_food;

			$data[] = $row;
			$no++;
		}

		$tot = $this->over_model->count_all();
		$filter = $this->over_model->count_filtered();

		$output = array(
			"draw" => $_POST['draw'],
			"data" => $data
		);

            //output to json format
		echo json_encode($output);
	}

	public function ajax_ot_graph()
	{
		if (isset($_POST['sortBulan'])) 
		{
			$tgl = $_POST['sortBulan'];

			$over = $this->over_model->by_bagian_cari('01-'.$tgl);
		}
		else {
			$over = $this->over_model->by_bagian();
		}

		$arr = array();
		$result = array();
		if(!empty($over)) {
			foreach($over as $r2){
				$tgl = date('M Y', strtotime($r2->tanggal));

				$arr['name'] = $r2->nama;
				$arr['y'] = (float) $r2->jml;
				$arr['tgl'] = $tgl;

				$result[] = $arr;
			}
		}
		else
			$result[] = json_decode("{}");

		echo json_encode($result);
	}

	public function ajax_ot_graph_bulan()
	{
		$over = $this->over_model->by_bagian_bulan();

		$arr = array();
		$result = array();
		if(!empty($over)) {
			foreach($over as $r2){
				$tgl = date('M Y', strtotime($r2->tanggal));

				$arr['name'] = $r2->nama;
				$arr['y'] = (float) $r2->jml;
				$arr['tgl'] = $tgl;

				$result[] = $arr;
			}
		}
		else
			$result[] = json_decode("{}");

		echo json_encode($result);
	}


	public function ajax_modal_g()
	{
		$tgl = $_POST['tanggal'];
		$time = strtotime('01 '.$tgl);
		$newformat = date('d-m-Y',$time);
		
		$dep = $_POST['departemen'];

		$list = $this->over_model->get_over_by_bagian($newformat, $dep);
		$data = array();
		foreach ($list as $key) {
			$row = array();
			$row[] = $key->nama;
			$row[] = date("d-m-Y",strtotime($key->tanggal));
			$row[] = $key->namaKaryawan;
			$row[] = $key->jam;
			$row[] = $key->keperluan;

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"data" => $data
		);

            //output to json format
		echo json_encode($output);
	}


	public function print_preview($id)
	{
		$data['list'] = $this->over_model->get_over_by_id($id);
		$data['list_anggota'] = $this->over_model->get_member_id($id);
		$cc = $this->over_model->get_member_id($id);
		$data['cc_member'] = $this->over_model->costCenter($cc[sizeof($cc)-1]->id_cc);

		$this->load->view('print_ot',$data);
	}

	public function cek_nik()
	{
		$nik = $_POST['nik'];
		$dep = $_POST['dep'];

		$list = $this->over_model->cek_id($nik, $dep);

		echo json_encode($list);
	}

	public function acc()
	{
		$nik = $_POST['nik'];
		$tgl = $_POST['tgl'];
		$jml = $_POST['tot'];

		$cc = $this->over_model->get_cc($nik, $jml, $tgl);

		$this->over_model->tambah_aktual($cc[0]->costCenter, $jml, $tgl);

	}
}
?>
