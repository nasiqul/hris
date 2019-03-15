<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ot extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('over_model');
		$this->load->model('cari_over');
		$this->load->model('over_user_model');
		$this->load->model('over_report_model');
		$this->load->model('over_cari_report_model');
		$this->load->model('over_cari_report_model_2');
		$this->load->model('over_cari_chart');
		$this->load->model('cari_over_dep');
		$this->load->library('ciqrcode');
		$this->load->model('over_cari_chart2');
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
			$this->over_model->save_master($no_doc, $tgl, $dep, $sec, $kep, $cat, 'N');
		else
			$this->over_model->save_master($no_doc, $tgl, $dep, $sec, $kep, $cat, 'L');
	}

	public function ot_member_submit()
	{
		$no_doc = $_POST['nodoc2'];

		$nik = $_POST['nik'];
		$dari = $_POST['dari'];
		$sampai = $_POST['sampai'];
		$jam = $_POST['jam'];
		$trans = $_POST['trans'];
		$makan = $_POST['makan'];
		$exfood = $_POST['exfood'];
		$idJam = $_POST['id_jam'];	

		$this->over_model->save_member($no_doc, $nik, $dari, $sampai, $jam, $trans, $makan, $exfood, $idJam);
		
	}

	public function ajax_ot()
	{
		if($_GET["tgl"] != ""){
			$list = $this->over_model->get_data_ot(date("Y-m-d",strtotime($_GET["tgl"])));
		}else{
			$list = $this->over_model->get_data_ot_defaeult();	
		}
		

		$data = array();
		foreach ($list as $key) {
			$row = array();
			$tg = date("d-m-Y",strtotime($key->tanggal));
			$row[] = $key->id;
			$row[] = $tg;
			$row[] = $key->nik;
			$row[] = $key->nama;
			$row[] = $key->masuk;
			$row[] = $key->keluar;
			$row[] = $key->jam;

			if ($key->aktual > $key->jam && $key->final == 0 && $key->status == 0)
				$row[] = "<button class='btn btn-danger btn-xs' id=d".$key->nik.$key->id." onclick='applyJam(".$key->id.",\"".$key->nik."\",".$key->jam.",\"".$tg."\")'><i class='fa fa-close'></i></button>  &nbsp <b>"
			.ROUND($key->aktual,2)."</b> 
			&nbsp<button class='btn btn-success btn-xs' id=c".$key->nik.$key->id." onclick='applyJam(".$key->id.",\"".$key->nik."\",".$key->aktual.",\"".$tg."\")'><i class='fa fa-check'></i></button>";
			else
				$row[] = ROUND($key->aktual,2);
			$row[] = $key->diff;
			$row[] = "<p id='f".$key->nik.$key->id."'>".ROUND($key->final2,2)."<p>";

			if ($key->status == 0) {
				$row[] = "<button class='btn btn-primary btn-xs' onclick='detail_spl(".$key->id.")'>Detail</button>
				<button class='btn btn-success btn-xs' id='conf".$key->nik.$key->id."' onclick='modalOpen(\"".$key->nik."\",".$key->final2.",\"".$tg."\",\"".$key->id."\")'><i class='fa fa-thumbs-up'></i> OK</button>";
			}
			else {
				$row[] = "<button class='btn btn-primary btn-xs' onclick='detail_spl(".$key->id.")'>Detail</button>";
			}

			$data[] = $row;
		}

		$output = array(
			"draw" => $_GET['draw'],
			"data" => $data
		);
            //output to json format
		echo json_encode($output);
	}

	public function updatedataover()
	{

		if($_GET["tgl"] != ""){
			$list = $this->over_model->caobaaa(date("Y-m-d",strtotime($_GET["tgl"])) );
		}else{
			$list = $this->over_model->caobaaa_default();
		}
		
		$data = array();
		foreach ($list as $key) {
			$cost = 0;	
			$budget = 0;
			

			if ($key->diff =="0") {				
				$where = array(
					'nik' => $key->nik,
					'id_ot' =>$key->id
				);

				$this->over_model->update_data_over($where,'over_time_member');
				$this->over_model->update_data_final($where,'over_time_member',$key->final2);

				$nikkar = "";
				$nikkar = $this->db->query("SELECT costCenter FROM karyawan WHERE NIK='".$key->nik."'");
				
				foreach ($nikkar->result() as $row) //Iterate through results
				{
					$cost=$row->costCenter;
					$aktual = "";
					$aktual = $this->db->query("SELECT aktual FROM cost_center_budget WHERE id_cc='".$cost."'");
					foreach ($aktual->result() as $row) //Iterate through results
					{
						$budget=$row->aktual;
						$total =  (float) $budget + (float) $key->final2;
						$tgl = date('Y-m',strtotime($key->tanggal))."-01";

						$where2 = array(
							'id_cc' => $cost,
							'period' => $tgl
						);
						$this->over_model->update_data_budget($where2,'cost_center_budget',$total);
					}

				}
				


			}

			echo json_encode($budget);
		}

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
		$id = $_GET['id'];
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
			"draw" => $_GET['draw'],
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

	public function ajax_spl_g()
	{
		$tgl = $_POST['tanggal'];
		$cc = $_POST['cc'];
		$target = $_POST['target'];

		$list = $this->over_model->get_data_chart($tgl, $cc, $target);
		$data = array();
		if(!empty($list)) {
			foreach ($list as $key) {
				$row = array();
				$row[] = $key->jam;
				$row[] = date("d/m",strtotime($key->tanggal));
				$row[] = $key->target;

				$data[] = $row;
			}

		}else
		$data[] = json_decode("{}");

            //output to json format
		echo json_encode($data);
	}


	public function print_preview($id,$tgl)
	{
		$data['list'] = $this->over_model->get_over_by_id($id);
		$data['list_anggota'] = $this->over_model->get_member_id($id,$tgl);
		$cc = $this->over_model->get_member_id($id,$tgl);
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
		$id = $_POST['id'];

		$cc = $this->over_model->get_cc($nik, $jml, $tgl, $id);

		$this->over_model->tambah_aktual($cc[0]->costCenter, $jml, $tgl);

	}

	public function changeJam()
	{
		$nik = $_POST['nik'];
		$id = $_POST['id'];
		$jam = $_POST['jam'];

		$this->over_model->set_jam($id, $nik, $jam);
	}

	public function ajax_ot_report()
	{
		$list = $this->over_report_model->get_ot_report();

		$tot = $this->over_report_model->count_all();
		$filter = $this->over_report_model->count_filtered();

		$data = array();
		if(!empty($list)) {
			foreach ($list as $key) {
				$row = array();
				$row[] = $key->period;
				$row[] = $key->nik;
				$row[] = $key->namaKaryawan;
				$row[] = $key->bagian;
				$row[] = $key->total_jam;
				$row[] = $key->satuan;
				$row[] = "<button class='btn btn-primary btn-xs' onclick='
				detail(\"".$key->nik."\",\"".$key->period."\",\"".$key->namaKaryawan."\")'>Detail</button>";

				$data[] = $row;
			}

		}else
		$data[] = json_decode("{}");

            //output to json format
		$output = array(
			"draw" => $_GET['draw'],
			"recordsFiltered" => $filter,
			"recordsTotal" => $tot,
			"data" => $data,
		);
            //output to json format
		echo json_encode($output);
	}

	public function ajax_ot_report_d()
	{
		$list = $this->over_report_model->get_ot_report2();

		$tot = $this->over_report_model->count_all2();
		$filter = $this->over_report_model->count_filtered2();

		$data = array();
		if(!empty($list)) {
			foreach ($list as $key) {
				$row = array();
				$row[] = $key->period;
				$row[] = $key->nik;
				$row[] = $key->namaKaryawan;
				$row[] = $key->bagian;
				$row[] = $key->total_jam;
				$row[] = "<button class='btn btn-primary btn-xs' onclick='
				detail2(\"".$key->nik."\",\"".$key->period."\",\"".$key->namaKaryawan."\")'>Detail</button>";

				$data[] = $row;
			}

		}else
		$data[] = json_decode("{}");

            //output to json format
		$output = array(
			"draw" => $_GET['draw'],
			"recordsFiltered" => $filter,
			"recordsTotal" => $tot,
			"data" => $data,
		);
            //output to json format
		echo json_encode($output);
	}

	public function get_id()
	{
		$tgl = $_POST['tgl'];
		$list = $this->over_model->get_id($tgl);
		if ($list) {
			echo json_encode($list[0]->id);
		}
		else {
			$time = strtotime($tgl);

			$newformat = date('ym',$time);

			$id = $newformat."0000";
			echo json_encode($id);
		}
	}

	public function ajax_ot_user()
	{
		$list = $this->over_user_model->get_ot_user();

		$tot = $this->over_user_model->count_all();
		$filter = $this->over_user_model->count_filtered();
		$data = array();
		if(!empty($list)) {
			$no = 1;
			foreach ($list as $key) {
				$row = array();
				$row[] = $no;
				$row[] = $key->id;
				$row[] = $key->tanggal;
				$row[] = "<button class='btn btn-primary btn-xs' onclick='detail_spl(".$key->id.")'>Detail</button>";

				$data[] = $row;

				$no++;
			}

		}else
		$data[] = json_decode("{}");


		$output = array(
			"draw" => $_GET['draw'],
			"recordsTotal" => $tot,
			"recordsFiltered" => $filter,
			"data" => $data,
		);
            //output to json format
		echo json_encode($output);
	}

	public function ajax_over_jam()
	{
		$id = $_POST['id'];
		$hari = $_POST['hari'];
		$list = $this->over_model->getJam($id,$hari);
		$data = array();

		$row1 = array();
		$row1[] = "";
		$row1[] = "Select Jam";
		$row1[] = "";

		$data[] = $row1;
		if ($list) {
			foreach ($list as $key) {
				$row = array();
				$row[] = $key->id;
				$row[] = $key->jam_awal;
				$row[] = $key->jam_akhir;
				$row[] = $key->jam;

				array_push($data, $row);
			}
		}

            //output to json format
		echo json_encode($data);
	}


	public function ajax_hitung_jam()
	{
		$id = $_POST['id'];
		$list = $this->over_model->getJam_act($id);
		$data = array();

		$data[] = $list[0]->jam;

        //output to json format
		echo json_encode($data);
	}

	public function ajax_hari()
	{
		$tgl = $_POST['tgl'];
		$list = $this->over_model->getHari($tgl);

		$time = strtotime($tgl);

		$newformat = date('D',$time);

		
		if ($list > 0) {
			if ($newformat == 'Fri') {
				echo json_encode("JL");
			}
			else{
				echo json_encode("L");
			}
		} else {
			if ($newformat == 'Fri') {
				echo json_encode("JN");
			}
			else{
				echo json_encode("N");
			}
		}
	}

	public function ajax_ot_report_details()
	{
		$nik = $_GET['nik'];
		$tgl = $_GET['period'];
		$time = strtotime('10 '.$tgl);

		$newformat = date('Y-m-d',$time);

		$list = $this->over_cari_report_model->get_data_report_cari($newformat, $nik);

		$tot = $this->over_cari_report_model->count_all($newformat, $nik);
		$filter = $this->over_cari_report_model->count_filtered($newformat, $nik);

		$data = array();
		if(!empty($list)) {
			foreach ($list as $key) {
				$row = array();
				$row[] = $key->tanggal;
				$row[] = $key->final;
				$row[] = $key->satuan;

				$data[] = $row;
			}

		}else
		$data[] = json_decode("{}");

            //output to json format
		$output = array(
			"draw" => $_GET['draw'],
			"recordsFiltered" => $filter,
			"data" => $data,
		);
            //output to json format
		echo json_encode($output);
	}

	public function ajax_ot_report_details_2()
	{
		$nik = $_GET['nik'];
		$tgl = $_GET['period'];
		$time = strtotime('10 '.$tgl);

		$newformat = date('m-Y',$time);

		$list = $this->over_cari_report_model_2->get_data_report_cari_2($newformat, $nik);

		$tot = $this->over_cari_report_model_2->count_all_2($newformat, $nik);
		$filter = $this->over_cari_report_model_2->count_filtered_2($newformat, $nik);

		$data = array();
		if(!empty($list)) {
			foreach ($list as $key) {
				$row = array();
				$row[] = $key->tgl;
				$row[] = $key->jam;

				$data[] = $row;
			}

		}else
		$data[] = json_decode("{}");

            //output to json format
		$output = array(
			"draw" => $_GET['draw'],
			"recordsFiltered" => $filter,
			"data" => $data,
		);
            //output to json format
		echo json_encode($output);
	}


	public function ga_by_tgl()
	{
		$tgl = $_POST['tgl'];

		$list = $this->over_model->getGA($tgl);
		$data = array();

		if (!empty($list)) {
			foreach ($list as $key) {
				$row = array();
				$row[] = $key->tanggal;
				$row[] = $key->makan1;
				$row[] = $key->makan2;
				$row[] = $key->makan3;

				$data[] = $row;
			}
			echo json_encode($data);

		} else {
			$data[] = "gagal";

			echo json_encode($data);

		}
	}

	public function ga_by_tgl_trans()
	{
		$tgl = $_POST['tgl'];

		$list = $this->over_model->getGA_trans($tgl);
		$data = array();

		foreach ($list as $key) {
			$row = array();
			$row[] = $key->tanggal;
			$row[] = $key->jam_awal;
			$row[] = $key->jam_akhir;
			$row[] = $key->B;
			$row[] = $key->P;

			$data[] = $row;
		}

            //output to json format
		echo json_encode($data);
	}

	public function overtime_chart()
	{
		$list = $this->over_model->chart();
		$data = array();

		foreach ($list as $key) {
			$time = strtotime('10-'.$key->month_name);

			$newformat = date('F-Y',$time);

			$row = array();
			$row[] = $key->nama;
			$row[] = $key->tiga_jam;
			$row[] = $key->blas_jam;
			$row[] = $key->tiga_blas_jam;
			$row[] = $key->manam_jam;
			$row[] = $newformat;

			$data[] = $row;
		}

            //output to json format
		echo json_encode($data);
	}

	public function overtime_chart2()
	{
		if (isset($_POST['tgl'])) {
			$tgl = date('Y-m-d', strtotime('10-'.$_POST['tgl']));
		} else {
			$tgl = date('Y-m-d');
		}

		$list = $this->over_model->chart2($tgl);
		$data = array();

		foreach ($list as $key) {
			$time = strtotime($key->month_name);

			$newformat = date('F-Y',$time);

			$row = array();
			$row[] = $key->nama;
			$row[] = $key->tiga_jam;
			$row[] = $key->blas_jam;
			$row[] = $key->tiga_blas_jam;
			$row[] = $key->manam_jam;
			$row[] = $newformat;

			$data[] = $row;
		}

            //output to json format
		echo json_encode($data);
	}

	public function ajax_ot_g_detail()
	{
		$tgl = date('d-m-Y' ,strtotime('10-'.$_GET['tgl']));
		$kode = $_GET['kode'];
		$cat = $_GET['cat'];

		if ($kode == 'OT > 3 JAM / HARI') {
			$list = $this->over_cari_chart->get_data($tgl,$cat);
			$tot = $this->over_cari_chart->count_all_3($tgl,$cat);
			$filter = $this->over_cari_chart->count_filtered_3($tgl,$cat);
		}
		else if ($kode == 'OT > 14 JAM / MGG') {
			$list = $this->over_cari_chart->get_data_14($tgl,$cat);
			$tot = $this->over_cari_chart->count_all_14($tgl,$cat);
			$filter = $this->over_cari_chart->count_filtered_14($tgl,$cat);
		}
		else if ($kode == 'OT > 3 dan > 14 Jam') {
			$list = $this->over_cari_chart->get_data_3_14($tgl,$cat);
			$tot = $this->over_cari_chart->count_all_3_14($tgl,$cat);
			$filter = $this->over_cari_chart->count_filtered_3_14($tgl,$cat);
		}
		else if ($kode == 'OT > 56 JAM / BLN') {
			$list = $this->over_cari_chart->get_data_56($tgl,$cat);
			$tot = $this->over_cari_chart->count_all_56($tgl,$cat);
			$filter = $this->over_cari_chart->count_filtered_56($tgl,$cat);
		}

		$data = array();

		foreach ($list as $key) {
			$row = array();
			$row[] = $key->nik;
			$row[] = $key->namaKaryawan;
			$row[] = $key->departemen;
			$row[] = $key->section;
			$row[] = $key->kode;
			$row[] = $key->avg;

			$data[] = $row;
		}

        //output to json format
		$output = array(
			"draw" => $_GET['draw'],
			"recordsFiltered" => $filter,
			"recordsTotal" => $tot,
			"data" => $data,
		);
            //output to json format
		echo json_encode($output);
	}

	public function ajax_ot_g_detail2()
	{
		$tgl = date('d-m-Y' ,strtotime('10-'.$_GET['tgl']));
		$kode = $_GET['kode'];
		$cat = $_GET['cat'];

		if ($kode == 'OT>3 JAM / HARI') {
			$list = $this->over_cari_chart2->get_data($tgl,$cat);
			$tot = $this->over_cari_chart2->count_all_3($tgl,$cat);
			$filter = $this->over_cari_chart2->count_filtered_3($tgl,$cat);
		}
		else if ($kode == 'OT>14 JAM / MGG') {
			$list = $this->over_cari_chart2->get_data_14($tgl,$cat);
			$tot = $this->over_cari_chart2->count_all_14($tgl,$cat);
			$filter = $this->over_cari_chart2->count_filtered_14($tgl,$cat);
		}
		else if ($kode == 'OT>3 & >14 JAM') {
			$list = $this->over_cari_chart2->get_data_3_14($tgl,$cat);
			$tot = $this->over_cari_chart2->count_all_3_14($tgl,$cat);
			$filter = $this->over_cari_chart2->count_filtered_3_14($tgl,$cat);
		}
		else if ($kode == 'OT>56 JAM / BLN') {
			$list = $this->over_cari_chart2->get_data_56($tgl,$cat);
			$tot = $this->over_cari_chart2->count_all_56($tgl,$cat);
			$filter = $this->over_cari_chart2->count_filtered_56($tgl,$cat);
		}

		$data = array();

		foreach ($list as $key) {
			$row = array();
			$row[] = $key->nik;
			$row[] = $key->namaKaryawan;
			$row[] = $key->departemen;
			$row[] = $key->section;
			$row[] = $key->kode;
			$row[] = $key->avg;

			$data[] = $row;
		}

        //output to json format
		$output = array(
			"draw" => $_GET['draw'],
			"recordsFiltered" => $filter,
			"recordsTotal" => $tot,
			"data" => $data,
		);
            //output to json format
		echo json_encode($output);
	}

	public function ajax_ot_jam()
	{
		$tgl = $_GET['tgl2'];
		$cat = $_GET['cat'];
		
		if ($cat == "3jam") {
			$list = $this->over_cari_chart2->get_data_3_t($tgl);
			$tot = $this->over_cari_chart2->count_all_3_t($tgl);
			$filter = $this->over_cari_chart2->count_filtered_3_t($tgl);
		}
		else if ($cat == "14jam") {
			$list = $this->over_cari_chart2->get_data_14_t($tgl);
			$tot = $this->over_cari_chart2->count_all_14_t($tgl);
			$filter = $this->over_cari_chart2->count_filtered_14_t($tgl);
		}
		else if ($cat == "3_14jam") {
			$list = $this->over_cari_chart2->get_data_3_14_t($tgl);
			$tot = $this->over_cari_chart2->count_all_3_14_t($tgl);
			$filter = $this->over_cari_chart2->count_filtered_3_14_t($tgl);
		}
		else if ($cat == "56jam") {
			$list = $this->over_cari_chart2->get_data_56_t($tgl);
			$tot = $this->over_cari_chart2->count_all_56_t($tgl);
			$filter = $this->over_cari_chart2->count_filtered_56_t($tgl);
		}
		
		
		$data = array();
		foreach ($list as $key) {
			$row = array();
			$row[] = $key->nik;
			$row[] = $key->namaKaryawan;
			$row[] = $key->departemen;
			$row[] = $key->section;
			$row[] = $key->kode;
			$row[] = $key->avg;

			$data[] = $row;
		}


            //output to json format
		$output = array(
			"draw" => $_GET['draw'],
			"recordsFiltered" => $filter,
			"data" => $data,
		);
            //output to json format
		echo json_encode($output);
	}


	public function ajax_kar2()
	{
		$d = date('n');
		for ($i = $d; $i > 0; $i--) {
			$s = date('d-m-Y',strtotime('2019-'.$d.'-01'));

			$list = $this->over_model->coba1($s);

			$data = array();

			foreach ($list as $key) {
				$row = array();
				$row[] = $key->bulan;
				$row[] = $key->tot;

				array_push($data, $row);
			}
		}
            //output to json format
		echo json_encode($data);
	}


	public function ajax_dp_g_detail()
	{
		$tgl = $_GET['tgl'];
		$bagian = $_GET['bagian'];

		$list = $this->cari_over_dep->get_data_ot_g2($tgl,$bagian);
		$tot = $this->cari_over_dep->count_all($tgl,$bagian);
		$filter = $this->cari_over_dep->count_filtered($tgl,$bagian);

		$data = array();

		foreach ($list as $key) {
			$row = array();
			$row[] = $key->nik;
			$row[] = $key->namaKaryawan;
			$row[] = $key->dep;
			$row[] = $key->sec;
			$row[] = $key->jam;

			$data[] = $row;
		}

        //output to json format
		$output = array(
			"draw" => $_GET['draw'],
			"recordsFiltered" => $filter,
			"recordsTotal" => $tot,
			"data" => $data,
		);
            //output to json format
		echo json_encode($output);


	}

	public function ajax_ot_manaj($tahun,$section)
	{

		$list = $this->over_model->nik_by_cc($section);
		$data = array();

		foreach ($list as $key) {
			$data2 = array();

			$nik = $key->nik;

			$data2["name"] = $key->namaKaryawan;

			$list2 = $this->over_model->jam_by_nik($nik,$tahun);

			$row2 = array();

			foreach ($list2 as $key2) {
				$row = array();
				$row[] = (float) $key2->tot;

				array_push($row2, $row);
			}

			$data2["data"] =  $row2;
			array_push($data, $data2);
		}

            //output to json format
		echo json_encode($data);
	}

	public function overtime_chart_per_dep()
	{
		$list = $this->over_model->get_tgl();
		$data = array();

		foreach ($list as $key) {
			$data2 = array();

			$tgl = $key->tanggal;

			$data2["name"] = $tgl;

			$list2 = $this->over_model->get_o_data($tgl);

			$row2 = array();

			foreach ($list2 as $key2) {
				$row = array();
				$row[] = (float) $key2->jam;

				array_push($row2, $row);
			}

			$data2["data"] =  $row2;
			array_push($data, $data2);
		}

            //output to json format
		echo json_encode($data);

	}
}
?>

