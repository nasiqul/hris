<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('home_model');
		$this->load->model('absensi_model');
		$this->load->model('karyawan_model');
		$this->load->model('cari_model');
        //$this->load->model('user_model');
	}

	public function index()
	{
		$this->load->view('login');
	}

	public function submit_login()
	{
		$newdata = array(
			'id'  => $_POST['id']
		);

		$this->session->set_userdata($newdata);

		$this->load->view('client');
	}
}
?>