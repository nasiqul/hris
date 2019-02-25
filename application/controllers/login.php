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
		$this->load->view('login');
	}

	public function submit_login()
	{
		$newdata = array(
			'id'  => $_POST['nik']
		);

		$this->session->set_userdata($newdata);

		redirect('client/view');
	}


	public function proses_login()
	{
		$nik = $_POST['nik'];
		$pass = $_POST['pass'];

		$list = $this->user_model->login($nik,$pass);
		$isi = $this->user_model->login_data($nik,$pass);

		if ($list == 1) {
			$newdata = array(
				'nik'  => $nik,
				'role'  => $isi[0]->role
			);

			$this->session->set_userdata($newdata);

			echo json_encode(1);
		}
		else {

			echo json_encode(0);
		}
	}
}
?>