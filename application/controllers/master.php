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
		$data['parentMenu'] = $this->home_model->getParentMenu();
		$this->load->view("master/master_bagian",$data);
	}

	public function role()
	{
		$data['menu2'] = 'Master';
		$data['menu'] = 'Master Role';
		$data['parentMenu'] = $this->home_model->getParentMenu();
		$this->load->view("master/master_login",$data);
	}

	public function keperluan()
	{
		$data['menu2'] = 'Master';
		$data['menu'] = 'Master Keperluan';
		$data['parentMenu'] = $this->home_model->getParentMenu();
		$data['kep'] = $this->master_model->getKeperluan();
		$this->load->view("master/master_keperluan",$data);
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
//---------------------- BAGIAN 
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

	public function edit_keperluan()
	{
		$id = $_POST['id'];
		$isi = $_POST['isi'];
		$this->master_model->edit_keperluan($id, $isi);
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

	//----------------- ROLE

	public function ajax_role($id)
	{
		$list = $this->master_model->get_data_master_role($id);
		$tot = $this->master_model->count_all_role($id);
		$filter = $this->master_model->count_filtered_role($id);

		$data = array();
		foreach ($list as $key) {
			$row = array();
			if ($id =="role") {
			$row[] = "<p id='$key->alias$key->id'>$key->id</p>";				
			$row[] = "<p id='$key->id$key->alias'>$key->nama</p>";
			$row[] = "<button class='btn btn-info btn-xs' id='detail' name='devisi' onclick='devisi(\"$key->alias$key->id\"); parent_menu()'>Detail</button> 
			<button class='btn btn-primary btn-xs' >Edit</button>
			<button class='btn btn-danger btn-xs' >Edit</button>
			";
		}

		if ($id =="menu") {
			$row[] = "<p id='$key->alias$key->id'>$key->parent_menu</p>";				
			$row[] = "<p id='$key->id$key->alias'>$key->nama</p>";
			$row[] = "<p id='$key->id$key->alias'>$key->url</p>";
			$row[] = "<p id='$key->id$key->alias'>$key->icon</p>";
			$row[] = "<button class='btn btn-info btn-xs' id='detail' name='devisi' onclick='devisi(\"$key->alias$key->id\"); selek(\"$key->id$key->alias\")'>Detail</button>";
		}

		if ($id =="user") {
			$row[] = "<p id='$key->alias$key->id'>$key->role</p>";				
			$row[] = "<p id='$key->id$key->alias'>$key->username</p>";
			$row[] = "<p id='$key->id$key->alias'>$key->nama</p>";
			$row[] = "<p id='$key->id$key->alias'>$key->department</p>";
			$row[] = "<button class='btn btn-info btn-xs' id='detail' name='devisi' onclick='devisi(\"$key->alias$key->id\"); selek(\"$key->id$key->alias\")'>Detail</button>";
		}
			
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

	public function parent_menu()
	{
		
		$list = $this->master_model->parent_menu();

		$data = array();
		foreach ($list as $key) {
			$row = array();

			$row[] = $key->parent_menu;		

			$data[] = $row;
		}

        //output to json format
		echo json_encode($data);

	}

	public function sub_menu()
	{
		$nama = $_GET['nama'];
		$list = $this->master_model->sub_menu($nama);

		$data = array();
		foreach ($list as $key) {
			$row = array();
			$row[] = $key->id_menu;
			$row[] = $key->nama_menu;		

			$data[] = $row;
		}

        //output to json format
		echo json_encode($data);

	}

	
}
?>