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

		$data['z'] = 'ok';
		$data['status'] = $this->karyawan_model->by_status();
        $data['gender'] = $this->karyawan_model->by_gender();
        $data['grade'] = $this->karyawan_model->by_grade();
        $data['kode'] = $this->karyawan_model->by_kode();
        $data['posisi'] = $this->karyawan_model->by_posisi();
        $data['menu'] = 'empG1';
        $data['chart'] = 'status';
        $this->load->view("karyawan_graph", $data);
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


	public function attendance()
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
		$this->load->view('report_manaj', $data);
	}


	public function karyawan_graph_gender()
	{
		$data['z'] = 'ok';
		$data['status'] = $this->karyawan_model->by_status();
        $data['gender'] = $this->karyawan_model->by_gender();
        $data['grade'] = $this->karyawan_model->by_grade();
        $data['kode'] = $this->karyawan_model->by_kode();
        $data['posisi'] = $this->karyawan_model->by_posisi();
        $data['menu'] = 'empG2';
        $data['chart'] = 'gender';
        $this->load->view("karyawan_graph", $data);
	}

	public function karyawan_graph_grade()
	{
		$data['z'] = 'ok';
		$data['status'] = $this->karyawan_model->by_status();
        $data['gender'] = $this->karyawan_model->by_gender();
        $data['grade'] = $this->karyawan_model->by_grade();
        $data['kode'] = $this->karyawan_model->by_kode();
        $data['posisi'] = $this->karyawan_model->by_posisi();
        $data['menu'] = 'empG3';
        $data['chart'] = 'grade';
        $this->load->view("karyawan_graph", $data);
	}


	public function karyawan_graph_dept()
	{
		$data['z'] = 'ok';
		$data['status'] = $this->karyawan_model->by_status();
        $data['gender'] = $this->karyawan_model->by_gender();
        $data['grade'] = $this->karyawan_model->by_grade();
        $data['kode'] = $this->karyawan_model->by_kode();
        $data['posisi'] = $this->karyawan_model->by_posisi();
        $data['menu'] = 'empG4';
        $data['chart'] = 'dept';
        $this->load->view("karyawan_graph", $data);
	}

	public function karyawan_graph_jabatan()
	{
		$data['z'] = 'ok';
		$data['status'] = $this->karyawan_model->by_status();
        $data['gender'] = $this->karyawan_model->by_gender();
        $data['grade'] = $this->karyawan_model->by_grade();
        $data['kode'] = $this->karyawan_model->by_kode();
        $data['posisi'] = $this->karyawan_model->by_posisi();
        $data['menu'] = 'empG5';
        $data['chart'] = 'jabatan';
        $this->load->view("karyawan_graph", $data);
	}

	

}
?>