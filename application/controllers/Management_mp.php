<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Management_mp extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('home_model');
		$this->load->model('absensi_model');
		$this->load->model('karyawan_model');
		$this->load->model('cari_model');
		$this->load->model('user_model');
	}

	public function index()
	{
		if (isset($_POST['bulan'])) {
			$newdata = array(
				'bulan'  => $_POST['bulan']
			);

			$this->session->set_userdata($newdata);
		}

		if ($this->session->userdata("bulan")) {

			$bln = $this->session->userdata("bulan");

			$data['report1'] = $this->home_model->report1_1_by_tgl($bln);
			$data['report2'] = $this->home_model->report2_2_by_tgl($bln);
		}
		else {
			$data['report1'] = $this->home_model->report1_1();
			$data['report2'] = $this->home_model->report2_2();
		}
		
		$data['menu'] = 'home';
		$data['z'] = 'ok';
		$this->load->view('report', $data);
	}

	public function absen()
	{
		$data['z'] = 'ok';
		$data['menu'] = 'absG';
		$this->load->view("absensi_graph",$data);
	}

	public function presensi()
	{
		$data['z'] = 'ok';
		$data['persentase'] = $this->home_model->by_persentase();
		$data['persentase_tidakMasuk'] = $this->home_model->persentase_tidakMasuk();
		$data['kary'] = $this->karyawan_model->tot();
		$data['menu'] = 'prG';
		$this->load->view("presensi_graph", $data);
	}

	public function ot_m()
	{
		$data['i'] = 'ok';
		$data['menu'] = 'OT-m';
		$this->load->view('ot_management', $data);
	}

	public function ot_report2()
	{
		$data['i'] = 'ok';
		$data['menu'] = 'ovrR2';
		$this->load->view('overtime_report2', $data);
	}

	public function monthly()
	{
		$data['i'] = 'ok';
		$data['menu'] = 'ovrMo';
		$this->load->view('ot_summary', $data);
	}

}
?>