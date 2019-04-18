<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('home_model');
		$this->load->model('master_model');
	}

	public function index()
	{
		$data['menu2'] = 'Master';
		$data['menu'] = 'Master Bagian';
		$this->load->view("master/master_bagian",$data);
	}

	public function ajax_client()
	{
		$id = $this->session->userdata("nik");
		$list = $this->client_model->get_data_client($id);

		$data = array();
		foreach ($list as $key) {
			$row = array();
			$row[] = date_format(date_create($key->tanggal),"M Y");
			
			if ($key->A != 0) 
				$row[] = $key->A;
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
			$row[] = "-";
			$row[] = "-";
			$row[] = "-";
			$jml = $key->A + $key->I + $key->SD;
			
			if ($jml != 0 )
				$row[] = "<i class='fa fa-close'></i>";
			else
				$row[] = "<i class='fa fa-check'></i>";
			$row[] = "-";
			$row[] = "<button class='btn btn-info btn-xs' id='detail'>Detail</button>";

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
		$id = $this->session->userdata("nik");
		$list = $this->karyawan_model->get_data_karyawan_by_nik2($id);

		$data = array();
		foreach ($list as $key) {
			$row = array();

			$row[] = $key->nik;
			$row[] = $key->namaKaryawan;
			$row[] = date_format(date_create($key->tanggalMasuk),"d M Y");
			$row[] = $key->statusKaryawan;
			$row[] = $key->namaGrade." / ". $key->jabatan;
			$row[] = $key->dev;
			$row[] = $key->dep;
			$row[] = $key->sec;
			$row[] = $key->foto;

			$data[] = $row;
		}

        //output to json format
		echo json_encode($data);
		
	}

	public function ajax_devisi($id)
	{
		$list = $this->master_model->get_data_master($id);
		$tot = $this->master_model->count_all($id);
		$filter = $this->master_model->count_filtered($id);

		$data = array();
		foreach ($list as $key) {
			$row = array();
			
			$row[] = "<p id='$key->alias$key->id'>$key->nama</p>";
			if ($id !="tgrup") {
				$row[] = $key->jml;
			}
			
			$row[] = "<p id='$key->id$key->alias'>$key->induk</p>";
			$row[] = "<button class='btn btn-info btn-xs' id='detail' name='devisi' onclick='devisi(\"$key->alias$key->id\"); selek(\"$key->id$key->alias\")'>Detail</button>";
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"data" => $data,
			"recordsTotal" => $tot,
			"recordsFiltered" => $filter,
		);
        //output to json format
		echo json_encode($output);
	}

	public function get_devisi()
	{
		$id = $_POST['id'];
		$nama = $_POST['nama'];
		$dbtabel = $_POST['dbtabel'];
		$induk = $_POST['induk'];
		$tb = $_POST['tb'];
		$this->master_model->get_devisi($id,$nama,$dbtabel,$induk,$tb);
		
	}

	public function get_select()
	{
		$nama = $_GET['nama'];
		$list = $this->master_model->get_select($nama);

		$data = array();
		foreach ($list as $key) {
			$row = array();

			$row[] = $key->id;
			$row[] = $key->nama;

			$data[] = $row;
		}

        //output to json format
		echo json_encode($data);

	}

	
}
?>