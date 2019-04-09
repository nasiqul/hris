<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
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
		$this->session->sess_destroy();
		$this->load->view('login');
	}

	public function submit_login()
	{
		$newdata = array(
			'id'  => $_POST['nik']
		);

		$this->session->set_userdata($newdata);

		redirect('client');
	}


	public function proses_login()
	{
		$nik = $_POST['nik'];
		$pass = $_POST['pass'];

		$list = $this->user_model->login($nik,$pass);
		$isi = $this->user_model->login_data2($nik,$pass);

		if ($list == 1) {
			$newdata = array(
				'nik'  => $nik,
				'role'  => $isi[0]->role,
				'nama'  => $isi[0]->nama
			);

			$this->session->set_userdata($newdata);

			$newdata2 = array(
				'rows'  => 1,
				'role'  => $isi[0]->role,
				'nama'  => $isi[0]->nama
			);

			echo json_encode($newdata2);
		}
		else {

			echo json_encode(0);
		}
	}
}
?>