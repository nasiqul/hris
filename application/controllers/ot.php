<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ot extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('over_model');
		$this->load->model('cari_over');
		$this->load->model('over_user_model');
		$this->load->model('over_report_model');
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
		$list = $this->over_model->get_data_ot();

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
			if ($key->aktual > $key->jam && $key->final == 0)
				$row[] = "<button class='btn btn-danger btn-xs' id=d".$key->nik.$key->id." onclick='applyJam(".$key->id.",".$key->nik.",".$key->jam.")'><i class='fa fa-close'></i></button>  &nbsp <b>"
			.$key->jam."</b> 
			&nbsp<button class='btn btn-success btn-xs' id=c".$key->nik.$key->id." onclick='applyJam(".$key->id.",".$key->nik.",".$key->aktual.")'><i class='fa fa-check'></i></button>";
			else
				$row[] = $key->jam;
			$row[] = ROUND($key->aktual,2);
			$row[] = $key->diff;
			$row[] = "<p id='f".$key->nik.$key->id."'>".ROUND($key->final2,2)."<p>";

			if ($key->status == 0) {
				$row[] = "<button class='btn btn-primary btn-xs' onclick='detail_spl(".$key->id.")'>Detail</button>
				<button class='btn btn-success btn-xs' id='conf".$key->nik.$key->id."' onclick='modalOpen(\"".$key->nik."\",".$key->final2.",\"".$tg."\",\"".$key->id."\")'><i class='fa fa-thumbs-up'></i> Confirm</button>";
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
				detail(".$key->nik.",\"".$key->period."\")'>Detail</button>";

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
		
		if ($list > 0) {
			echo json_encode("L");
		} else {
			echo json_encode("N");
		}
	}
}
?>

