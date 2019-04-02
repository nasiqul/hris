<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ot extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('over_model');
		$this->load->model('cari_over');
		$this->load->model('over_user_model');
		$this->load->model('home_model');
		$this->load->model('over_report_model');
		$this->load->model('over_cari_report_model');
		$this->load->model('over_cari_report_model_2');
		$this->load->model('over_cari_chart');
		$this->load->model('cari_over_dep');
		$this->load->model('over_cari_chart2');
		$this->load->model('over_model_new');
		$this->load->model('ot_summary');
		$this->load->model('over_detail_mControl');
		$this->load->library('ciqrcode');
		$this->load->helper('file');
		//$this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
	}

	public function ot_submit()
	{
		$no_doc = $_POST['no'];
		$tgl = date('Y-m-d',strtotime($_POST['tgl']));
		$dep = $_POST['dep'];
		$sec = $_POST['sec'];
		$kep = $_POST['kep'];
		$cat = $_POST['cat'];
		$subsec = $_POST['subsec'];
		$grup = $_POST['grup'];
		$hari = $_POST['hari'];
		$shift = $_POST['shift'];

		$params['data'] = $no_doc;
		$params['level'] = 'H';
		$params['size'] = 10;
		$params['savename'] = 'app/qr_lembur/'.$no_doc.'.png';
		$this->ciqrcode->generate($params);

		$this->over_model->save_master($no_doc, $tgl, $dep, $sec, $subsec, $kep, $cat, $hari, $grup, 
			$shift);
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

	public function deleteSPL()
	{
		$no_doc = $_POST['nodoc2'];

		$this->over_model->deleteSPL($no_doc);
	}

	public function ajax_ot()
	{
		if(isset($_GET["tgl"]) && $_GET['tgl']){
			$list = $this->over_model->get_data_ot(date("Y-m-d",strtotime($_GET["tgl"])));
			$tot = $this->over_model->count_all2(date("Y-m-d",strtotime($_GET["tgl"])));
			$filter = $this->over_model->count_filtered2(date("Y-m-d",strtotime($_GET["tgl"])));
		}else{
			$list = $this->over_model->get_data_ot(date("Y-m-d"));	
			$tot = $this->over_model->count_all2(date("Y-m-d"));
			$filter = $this->over_model->count_filtered2(date("Y-m-d"));
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
			"recordsFiltered" => $filter,
			"recordsTotal" => $tot,
			"data" => $data
		);
            //output to json format
		echo json_encode($output);
	}

	// OT NEW
	public function ajax_ot2()
	{
		if(isset($_GET["tgl"]) && $_GET['tgl']){
			$list = $this->over_model_new->get_data_ot(date("Y-m-d",strtotime($_GET["tgl"])));
			$tot = $this->over_model_new->count_all2(date("Y-m-d",strtotime($_GET["tgl"])));
			$filter = $this->over_model_new->count_filtered2(date("Y-m-d",strtotime($_GET["tgl"])));
		}else{
			$list = $this->over_model_new->get_data_ot(date("Y-m-d"));	
			$tot = $this->over_model_new->count_all2(date("Y-m-d"));
			$filter = $this->over_model_new->count_filtered2(date("Y-m-d"));
		}
		

		$data = array();
		foreach ($list as $key) {
			$row = array();
			$tg = date("d-m-Y",strtotime($key->tanggal));
			$row[] = $key->id;
			$row[] = $tg;
			$row[] = $key->nik;
			$row[] = $key->namaKaryawan;
			$row[] = $key->masuk;
			$row[] = $key->keluar;
			$row[] = $key->jam_plan;

			if ($key->aktual > $key->jam_plan)
				$row[] = "<button class='btn btn-danger btn-xs' id=d".$key->nik.$key->id." onclick='applyJam(".$key->id.",\"".$key->nik."\",".$key->jam_plan.",\"".$tg."\")'><i class='fa fa-close'></i></button>  &nbsp <b>"
			.$key->aktual."</b> 
			&nbsp<button class='btn btn-success btn-xs' id=c".$key->nik.$key->id." onclick='applyJam(".$key->id.",\"".$key->nik."\",".$key->aktual.",\"".$tg."\")'><i class='fa fa-check'></i></button>";
			else
				$row[] = $key->aktual;
			$row[] = $key->diff;
			$row[] = "<p id='f".$key->nik.$key->id."'>".$key->final."<p>";

			$row[] = "<button class='btn btn-primary btn-xs' onclick='detail_spl(".$key->id.")'>Detail</button>
			<button class='btn btn-success btn-xs' id='conf".$key->nik.$key->id."' onclick='modalOpen(\"".$key->nik."\",".$key->final.",\"".$tg."\",\"".$key->id."\")'><i class='fa fa-thumbs-up'></i> OK</button>";

			$data[] = $row;
		}

		$output = array(
			"draw" => $_GET['draw'],
			"recordsFiltered" => $filter,
			"recordsTotal" => $tot,
			"data" => $data
		);
            //output to json format
		echo json_encode($output);
	}

	public function updatedataover()
	{
		if($_GET["tgl"] != ""){
			$list = $this->over_model_new->tabel_konfirmasi(date("Y-m-d",strtotime($_GET["tgl"])) );
		}
		
		$data = array();
		foreach ($list as $key) {
			$cost = 0;	
			$budget = 0;
			if ($key->jml_nik == "1") {

				if ($key->diff == "0") {				

					if ($key->jam_plan != "0") {
						$where = array(
							'nik' => $key->nik,
							'id_ot' =>$key->id
						);

						$jam = $key->jam_plan;

						$this->over_model->update_data_over($where,'over_time_member');
						$this->over_model->update_data_final($where,'over_time_member',$key->final2);

					}
					else {
						$jam = "0";
					}

					$nik = $key->nik;
					$tgl = $key->tanggal;

					$this->over_model->change_over($nik, $tgl, $jam);

					$nikkar = "";
					$nikkar = $this->db->query("SELECT costCenter FROM karyawan WHERE NIK='".$key->nik."'");
					
					foreach ($nikkar->result() as $row) //Iterate through results
					{
						$cost = $row->costCenter;
						$aktual = "";
						$aktual = $this->db->query("SELECT aktual FROM cost_center_budget WHERE id_cc='".$cost."' and DATE_FORMAT(period,'%Y-%m') =  DATE_FORMAT('".$key->tanggal."','%Y-%m')");
						foreach ($aktual->result() as $row) //Iterate through results
						{
							$act = $row->aktual;
							$total =  (float) $act + (float) $key->final2;
							$tgl = date('Y-m',strtotime($key->tanggal))."-01";

							$where2 = array(
								'id_cc' => $cost,
								'period' => $tgl
							);
							$this->over_model->update_data_budget($where2,'cost_center_budget',$total);
						}

					}
				}
			}

			// echo json_encode($s);
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
			$row[] = $key->section;
			$row[] = $key->sub_sec;
			$row[] = $key->keperluan;
			$row[] = $key->catatan;
			$row[] = $key->grup;

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
		$tgl2 = date('Y-m-d',strtotime($tgl));

		$cc = $this->over_model->get_cc($nik, $jml, $tgl, $id);

		$this->over_model->change_over($nik, $tgl2, $jml);

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
				// $row[] = "<button class='btn btn-primary btn-xs' onclick='
				// detail2(\"".$key->nik."\",\"".$key->period."\",\"".$key->namaKaryawan."\")'>Detail</button>";

				$row[] = "<a class='btn btn-primary btn-sm' href='".base_url('home/detailSPL/'.$key->nik."/".$key->period)."'> Detail </a>";

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
		if (isset($_GET['tanggal'])) {
			$tgl = $_GET['tanggal'];
			
		} else {
			$tgl = date('Y-m-d');

		}

		$sub = $_GET['sub'];
		$subsec = $_GET['subsec'];
		$group = $_GET['group'];

		$list = $this->over_user_model->get_ot_user($tgl,$sub,$subsec,$group);

		$tot = $this->over_user_model->count_all($tgl,$sub,$subsec,$group);
		$filter = $this->over_user_model->count_filtered($tgl,$sub,$subsec,$group);
		$data = array();
		if(!empty($list)) {
			$no = 1;
			foreach ($list as $key) {
				$row = array();
				$row[] = $no;
				$row[] = $key->id;
				$row[] = $key->tanggal;
				if ($key->status != '0') 
					$row[] = "<button class='btn btn-primary btn-xs' onclick='detail_spl(".$key->id."); exporta(".$key->id.")'>Detail</button>";
				else
					$row[] = "<button class='btn btn-primary btn-xs' onclick='detail_spl(".$key->id."); exporta(".$key->id.")'>Detail</button>";

				// <button class='btn btn-danger btn-xs' onclick='modal_open(".$key->id.");'><i class='fa fa-trash'></i></button>
				

				$data[] = $row;

				$no++;
			}

		}

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
		$time = strtotime('01 '.$tgl);

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

		}
		else {

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

	public function ga_by_tgl_food()
	{
		$tgl = $_POST['tgl'];

		$list = $this->over_model->getGA2($tgl);
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

	public function makan1()
	{
		$tgl = $_POST['tgl'];
		$id = $_POST['id'];

		$list = $this->over_model->makan1db($tgl,$id);
		$data = array();

		if (!empty($list)) {
			foreach ($list as $key) {
				$row = array();
				$row[] = $key->nik;
				$row[] = $key->namaKaryawan;
				$row[] = $key->dev;
				$row[] = $key->dept;
				$row[] = $key->sec;
				$row[] = $key->sub;
				$row[] = $key->gruop1;

				$data[] = $row;
			}
			echo json_encode($data);

		} else {
			
			$data[] = "-";

			echo json_encode($data);
		}
	}

	public function extrafood1()
	{
		$tgl = $_POST['tgl'];
		$id = $_POST['id'];

		$list = $this->over_model->extrafood2($tgl,$id);
		$data = array();

		if (!empty($list)) {
			foreach ($list as $key) {
				$row = array();
				$row[] = $key->nik;
				$row[] = $key->namaKaryawan;
				$row[] = $key->dev;
				$row[] = $key->dept;
				$row[] = $key->sec;
				$row[] = $key->sub;
				$row[] = $key->gruop1;

				$data[] = $row;
			}
			echo json_encode($data);

		} else {
			
			$data[] = "-";

			echo json_encode($data);
		}
	}

	public function trans1()
	{
		$tgl = $_POST['tgl'];
		$id = $_POST['id'];
		$sampai = $_POST['sampai'];
		$dari = $_POST['dari'];

		$list = $this->over_model->transdb($id,$tgl,$dari,$sampai);
		$data = array();

		if (!empty($list)) {
			foreach ($list as $key) {
				$row = array();
				$row[] = $key->nik;
				$row[] = $key->namaKaryawan;
				$row[] = $key->dev;
				$row[] = $key->dept;
				$row[] = $key->sec;
				$row[] = $key->sub;
				$row[] = $key->gruop1;

				$data[] = $row;
			}
			echo json_encode($data);

		} else {
			
			$data[] = "-";

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
			$row[] = $key->dari;
			$row[] = $key->sampai;
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
			$time = strtotime('01-'.$key->month_name);

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
		$data3 = array();

		foreach ($list as $key) {
			$data2 = array();

			$nik = $key->nik;

			$data2["name"] = $key->namaKaryawan;
			$data3["name"] = $key->target;

			$list2 = $this->over_model->jam_by_nik($nik,$tahun);

			$row2 = array();
			$d = array();

			foreach ($list2 as $key2) {
				$row = array();
				$row[] = (float) $key2->tot;

				array_push($row2, $row);
				array_push($d, (float) $key2->budget);
			}

			$data2["data"] =  $row2;
			$data3["data"] =  $d;
			$data3["dashStyle"] =  'Dash';

			array_push($data, $data2);
			array_push($data, $data3);
		}

            //output to json format
		echo json_encode($data);
	}

	public function overtime_chart_per_dep()
	{
		if (isset($_POST['bulan'])) {
            //$n = date('m-Y', strtotime($_POST['date2']));
			$tgl = $_POST['bulan'];
			$tgl2 = date('Y-m',strtotime("01-".$_POST['bulan']));
		}
		else {
			$tgl = date('m-Y');
			$tgl2 = date('Y-m');
		}

		if (isset($_POST['bagian'])) {
            //$n = date('m-Y', strtotime($_POST['date2']));
			$cc = $_POST['bagian'];
		}
		else {
			$cc = "0";
		}

		$data4 = array();

		$fiskal = $this->home_model->getFiskal($tgl2);

		$list2 = $this->over_model->get_cc5($tgl,$cc);
		$list3 = $this->over_model->get_budget($tgl2,$cc,$fiskal[0]->fiskal);

		$data3 = array();

		foreach ($list3 as $key3) {
			$row2 = array();
			$row2[] = $key3->period; 
			$row2[] = $key3->departemen;
			$row2[] = (float) $key3->budget;

			$data3[] = $row2;
		}

		$data2 = array();

		foreach ($list2 as $key2) {
			$row = array();
			$row[] = date('d',strtotime($key2->tanggal)); 
			$row[] = $key2->departemen;
			$row[] = (float) $key2->jam;

			$data2[] = $row;
		}

		array_push($data4, $data2);
		array_push($data4, $data3);

            //output to json format
		echo json_encode($data4);

	}


	public function createXLS($id) {
		// create file name
		$fileName = 'data-'.time().'.xlsx';  
		// load excel library
		$this->load->library('excel');
		$empInfo = $this->over_model->get_over_by_id_member($id);
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
        // set Header
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'id_over');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'nik');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'nama');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'dari');
		$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'sampai');
		$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'transport');  
		$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'makan');         
		$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'efood');  
		$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'jam');  
        // set Row
		$rowCount = 2;
		foreach ($empInfo as $element) {
			$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $element['id_over']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element['nik']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $element['namaKaryawan']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $element['dari']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $element['sampai']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $element['transport']);
			$objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $element['makan']);
			$objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $element['efood']);
			$objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $element['jam']);
			$rowCount++;
		}
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save(ROOT_UPLOAD_IMPORT_PATH.$fileName);
		// download file
		header("Content-Type: application/vnd.ms-excel");
		redirect(HTTP_UPLOAD_IMPORT_PATH.$fileName);        
	}

	public function ajax_summary()
	{
		if (isset($_POST['tgl'])) {
			$tgl1 = date('Y-m',strtotime('01-'.$_POST['tgl']));
		}
		else{
			$tgl1 = date('Y-m');
		}

		$list = $this->ot_summary->ot_summary_m($tgl1);
		$tot = $this->ot_summary->count_all($tgl1);
		$filter = $this->ot_summary->count_filtered($tgl1);

		$data = array();

		foreach ($list as $key2) {
			$row = array();
			$row[] = $key2->mon; 
			$row[] = $key2->name;
			$row[] = (float) $key2->aktual;
			$row[] = $key2->karyawan;
			$row[] = (float) $key2->avg;
			$row[] = (float) $key2->min_final;
			$row[] = (float) $key2->max_final;

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsFiltered" => $filter,
			"recordsTotal" => $tot,
			"data" => $data,
		);
            //output to json format
		echo json_encode($output);
	}

	public function ajax_mountly()
	{
		$tgl = date("Y-m");

		$fiskal = $this->home_model->getFiskal($tgl);

		$list = $this->over_model->get_data_ot_month($fiskal[0]->fiskal);
		$data = array();

		foreach ($list as $key2) {
			$row = array();
			$row[] = $key2->mon; 
			$row[] = (float) $key2->budget_tot;
			$row[] = (float) $key2->act_tot;
			$row[] = (float) $key2->forecast_tot;
			$row[] = $key2->bagian;

			$data[] = $row;
		}

		echo json_encode($data);
	}

	public function report_g($nik,$tgl)
	{
		$list = $this->over_model->get_jam_by_nik($nik, $tgl);
		$data = array();
		foreach ($list as $key) {
			$row = array();
			$row[] = $key->nik;
			$row[] = date('F Y',strtotime($key->tgl));
			$row[] = (float) $key->jam;
			$row[] = $key->namaKaryawan;

			$data[] = $row;
		}

            //output to json format
		echo json_encode($data);
	}

	public function presentase_g()
	{
		$tgl = $_POST['bulan'];
		$bagian = $_POST['bagian'];
		$n1 = date('Y-m', strtotime("01-".$tgl));

		$fiskal = $this->home_model->getFiskal($n1);

		$list = $this->over_model->get_presentase($n1,$bagian,$fiskal[0]->fiskal);
		$data = array();
		foreach ($list as $key) {
			$row = array();
			$row["budget"] = (int) $key->budget_tot2;
			$row["aktual"] = (int) $key->aktual;
			$row["kode"] = $key->kode;

			$data[] = $row;
		}

            //output to json format
		echo json_encode($data);
	}

	public function ajax_ot_monthly_c()
	{
		$kode = $_GET['kode'];
		$tgl = date('Y-m-d',strtotime($_GET['tgl']));

		$list = $this->over_detail_mControl->get_data_chart_detail($kode,$tgl);
		$tot = $this->over_detail_mControl->count_all($kode,$tgl);
		$filter = $this->over_detail_mControl->count_filtered($kode,$tgl);
		$data = array();
		$no = 1;
		foreach ($list as $key) {
			$row = array();
			$row[] = $no;
			$row[] = $key->tanggal;
			$row[] = $key->nik;
			$row[] = $key->namaKaryawan;
			$row[] = $key->name;
			$row[] = $key->masuk;
			$row[] = $key->keluar;
			$row[] = $key->jam;

			$data[] = $row;
			$no++;
		}

		$output = array(
			"draw" => $_GET['draw'],
			"recordsFiltered" => $filter,
			"recordsTotal" => $tot,
			"data" => $data,
		);
            //output to json format
		echo json_encode($output);
	}


	public function ajax_ot_hr()
	{
		if (isset($_GET['tanggal'])) {
			$tgl = $_GET['tanggal'];
			
		} else {
			$tgl = date('Y-m-d');

		}

		$sub = $_GET['sub'];
		$subsec = $_GET['subsec'];
		$group = $_GET['group'];

		$list = $this->over_user_model->get_ot_user($tgl,$sub,$subsec,$group);

		$tot = $this->over_user_model->count_all($tgl,$sub,$subsec,$group);
		$filter = $this->over_user_model->count_filtered($tgl,$sub,$subsec,$group);
		$data = array();
		if(!empty($list)) {
			$no = 1;
			foreach ($list as $key) {
				$row = array();
				$row[] = $no;
				$row[] = $key->id;
				$row[] = $key->tanggal;
				$row[] = "
				<a class='btn btn-primary btn-xs' href='".base_url("home/overtime_edit/".$key->id)."'><i class='fa fa-pencil'></i></a> &nbsp;
				<button class='btn btn-danger btn-xs' onclick='modal_open(".$key->id.");'><i class='fa fa-trash'></i></button>";
				

				$data[] = $row;

				$no++;
			}

		}

		$output = array(
			"draw" => $_GET['draw'],
			"recordsTotal" => $tot,
			"recordsFiltered" => $filter,
			"data" => $data,
		);
            //output to json format
		echo json_encode($output);
	}

	public function delete_ot()
	{
		$id_ot = $_POST['id'];
		$this->over_model->hapus($id_ot);
	}

	public function get_break()
	{
		$hari = date('w',strtotime($_GET['tgl']));
		$dari = $_GET['dari'];
		$sampai = $_GET['sampai'];
		$shift = $_GET['shift'];

		$list = $this->over_model->get_break($hari, $dari, $sampai, $shift);

		foreach ($list as $key) {
			$row = array();
			$row[] = $key->istirahat;

			$data[] = $row;
		}

		echo json_encode($data);
	}

	public function exportexcel($id){

		// $tgl = $_POST['tgl'];

		// Load plugin PHPExcel nya
		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		
		// Panggil class PHPExcel nya
		$excel = new PHPExcel();

		// Settingan awal fil excel
		$excel->getProperties()->setCreator('MIRAI')
		->setLastModifiedBy('MIRAI')
		->setTitle("Data MIRAI")
		->setSubject("MIRAI")
		->setDescription("Laporan MIRAI")
		->setKeywords("Data Lembur");

		// Buat sebuah variabel untuk menampung pengaturan style dari header tabel
		$style_col = array(
			'font' => array('bold' => true), // Set font nya jadi bold
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
			),
			'borders' => array(
				'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
				'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
				'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
				'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
			)
		);

		// Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
		$style_row = array(
			'alignment' => array(
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
			),
			'borders' => array(
				'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
				'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
				'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
				'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
			)
		);

		$excel->setActiveSheetIndex(0)->setCellValue('A1', "DATA LEMBUR"); // Set kolom A1 dengan tulisan "DATA SISWA"
		$excel->getActiveSheet()->mergeCells('A1:K1'); // Set Merge Cell pada kolom A1 sampai E1
		$excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
		$excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
		$excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A3', "NO"); // Set kolom A3 dengan tulisan "NO"
		$excel->setActiveSheetIndex(0)->setCellValue('B3', "ID OVER TIME"); // Set kolom B3 dengan tulisan "NIS"
		$excel->setActiveSheetIndex(0)->setCellValue('C3', "NIK"); // Set kolom C3 dengan tulisan "NAMA"
		$excel->setActiveSheetIndex(0)->setCellValue('D3', "DARI"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
		$excel->setActiveSheetIndex(0)->setCellValue('E3', "SAMPAI"); // Set kolom E3 dengan tulisan "ALAMAT"
		$excel->setActiveSheetIndex(0)->setCellValue('F3', "JAM"); 
		$excel->setActiveSheetIndex(0)->setCellValue('G3', "TRANSPORT"); 
		$excel->setActiveSheetIndex(0)->setCellValue('H3', "MAKAN"); 
		$excel->setActiveSheetIndex(0)->setCellValue('I3', "EXTRA FOOD"); 
		$excel->setActiveSheetIndex(0)->setCellValue('J3', "FINAL"); 
		$excel->setActiveSheetIndex(0)->setCellValue('K3', "SATUAN"); 
		// Apply style header yang telah kita buat tadi ke masing-masing kolom header
		$excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('G3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('H3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('I3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('J3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('K3')->applyFromArray($style_col);

		// Panggil function view yang ada di SiswaModel untuk menampilkan semua data siswanya
		$siswa = $this->over_model->exportdata($id);

		$no = 1; // Untuk penomoran tabel, di awal set dengan 1
		$numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
		foreach($siswa as $data){ // Lakukan looping pada variabel siswa
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $data->id_ot);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $data->nik);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $data->dari);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $data->sampai);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $data->jam);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $data->transport);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $data->makan);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $data->ext_food);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $data->final);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $data->satuan);
			
			
			// Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
			$excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('K'.$numrow)->applyFromArray($style_row);
			
			$no++; // Tambah 1 setiap kali looping
			$numrow++; // Tambah 1 setiap kali looping
		}

		
		// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Data Lembur");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename="DataLembur.xlsx"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		ob_end_clean();
		$write->save('php://output');

	}


	public function exportexcelhr($id){

		// $tgl = $_POST['tgl'];

		// Load plugin PHPExcel nya
		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		
		// Panggil class PHPExcel nya
		$excel = new PHPExcel();

		// Settingan awal fil excel
		$excel->getProperties()->setCreator('MIRAI')
		->setLastModifiedBy('MIRAI')
		->setTitle("Data MIRAI")
		->setSubject("MIRAI")
		->setDescription("Laporan MIRAI")
		->setKeywords("Data Lembur");

		// Buat sebuah variabel untuk menampung pengaturan style dari header tabel
		$style_col = array(
			'font' => array('bold' => true), // Set font nya jadi bold
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
			),
			'borders' => array(
				'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
				'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
				'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
				'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
			)
		);

		// Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
		$style_row = array(
			'alignment' => array(
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
			),
			'borders' => array(
				'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
				'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
				'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
				'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
			)
		);

		$excel->setActiveSheetIndex(0)->setCellValue('A1', "DATA LEMBUR"); // Set kolom A1 dengan tulisan "DATA SISWA"
		$excel->getActiveSheet()->mergeCells('A1:K1'); // Set Merge Cell pada kolom A1 sampai E1
		$excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
		$excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
		$excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

		// Buat header tabel nya pada baris ke 3
		$excel->setActiveSheetIndex(0)->setCellValue('A3', "NO"); // Set kolom A3 dengan tulisan "NO"
		$excel->setActiveSheetIndex(0)->setCellValue('B3', "ID OVER TIME"); // Set kolom B3 dengan tulisan "NIS"
		$excel->setActiveSheetIndex(0)->setCellValue('C3', "NIK"); // Set kolom C3 dengan tulisan "NAMA"
		$excel->setActiveSheetIndex(0)->setCellValue('D3', "TANGGAL"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
		$excel->setActiveSheetIndex(0)->setCellValue('E3', "NAMA KARYAWAN"); // Set kolom E3 dengan tulisan "ALAMAT"
		$excel->setActiveSheetIndex(0)->setCellValue('F3', "DARI"); 
		$excel->setActiveSheetIndex(0)->setCellValue('G3', "SAMPAI"); 
		$excel->setActiveSheetIndex(0)->setCellValue('H3', "JAM"); 
		$excel->setActiveSheetIndex(0)->setCellValue('I3', "TRANSPORT"); 
		$excel->setActiveSheetIndex(0)->setCellValue('J3', "MAKAN"); 
		$excel->setActiveSheetIndex(0)->setCellValue('K3', "EXTRA FOOD"); 
		$excel->setActiveSheetIndex(0)->setCellValue('L3', "FINAL");
		// Apply style header yang telah kita buat tadi ke masing-masing kolom header
		$excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('G3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('H3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('I3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('J3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('K3')->applyFromArray($style_col);
		$excel->getActiveSheet()->getStyle('L3')->applyFromArray($style_col);

		// Panggil function view yang ada di SiswaModel untuk menampilkan semua data siswanya
		$siswa = $this->over_model->exportdatahr($id);

		$no = 1; // Untuk penomoran tabel, di awal set dengan 1
		$numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
		foreach($siswa as $data){ // Lakukan looping pada variabel siswa
			$excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $no);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $data->id_ot);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $data->nik);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $data->tanggal);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $data->namaKaryawan);
			$excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $data->dari);
			$excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $data->sampai);
			$excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $data->jam);
			$excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $data->transport);
			$excel->setActiveSheetIndex(0)->setCellValue('J'.$numrow, $data->makan);
			$excel->setActiveSheetIndex(0)->setCellValue('K'.$numrow, $data->ext_food);
			$excel->setActiveSheetIndex(0)->setCellValue('L'.$numrow, $data->final);
			
			
			// Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
			$excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('H'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('I'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('J'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('K'.$numrow)->applyFromArray($style_row);
			$excel->getActiveSheet()->getStyle('L'.$numrow)->applyFromArray($style_row);
			
			$no++; // Tambah 1 setiap kali looping
			$numrow++; // Tambah 1 setiap kali looping
		}

		
		// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		// Set orientasi kertas jadi LANDSCAPE
		$excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

		// Set judul file excel nya
		$excel->getActiveSheet(0)->setTitle("Data Lembur");
		$excel->setActiveSheetIndex(0);

		// Proses file excel
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename="DataLembur.xlsx"'); // Set nama file excel nya
		header('Cache-Control: max-age=0');

		$write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		ob_end_clean();
		$write->save('php://output');

	}
}
?>


