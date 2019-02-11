<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('client_model');
		$this->load->model('karyawan_model');
	}

	public function view()
	{
		$this->load->view('client');
	}

	public function ajax_client()
	{
		$id = $this->session->userdata("id");
		$list = $this->client_model->get_data_client($id);

		$data = array();
		foreach ($list as $key) {
			$row = array();
			$row[] = date_format(date_create($key->tanggal),"M Y");
			
			if ($key->DL != 0) 
				$row[] = $key->DL;
			else
				$row[] = "-";
			
			if ($key->I != 0) 
				$row[] = $key->I;
			else
				$row[] = "-";

			if ($key->SD != 0) 
				$row[] = $key->SD;
			else
				$row[] = "-";
			
			$row[] = $key->CT;
			$jml = $key->DL + $key->I + $key->SD;
			
			if ($jml != 0 )
				$row[] = "<i class='fa fa-close'></i>";
			else
				$row[] = "<i class='fa fa-check'></i>";
			$row[] = "-";

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"data" => $data,
		);
        //output to json format
		echo json_encode($output);
	}

	public function ajax_client_data()
	{
		$id = $this->session->userdata("id");
		$list = $this->karyawan_model->get_data_karyawan_by_nik($id);

		$data = array();
		foreach ($list as $key) {
			$row = array();

			$row[] = $key->nik;
			$row[] = $key->namaKaryawan;
			$row[] = date_format(date_create($key->tanggalMasuk),"d M Y");
			$row[] = $key->statusKaryawan;
			$row[] = $key->namaGrade." / ". $key->jabatan;

			$data[] = $row;
		}

        //output to json format
		echo json_encode($data);
		
	}
}
?>