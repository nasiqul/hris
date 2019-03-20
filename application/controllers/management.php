<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Management extends CI_Controller {
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
		$data['i'] = 'ok';
		$data['menu'] = 'ovrMoC';
		$this->load->view('ot_monthly_control', $data);
	}

	public function monthlyMon()
	{
		$data['i'] = 'ok';
		$data['menu'] = 'ovrMon';
		$this->load->view('ot_monthly_monitor', $data);
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