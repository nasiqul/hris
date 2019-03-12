<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Karyawan_form extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('karyawan_model');
		$this->load->model('outsource_model');
	}

	public function ajax_dep()
	{
		$id = $_POST['id'];
		$list = $this->karyawan_model->get_dep($id);

		$data = array();

		$row1 = array();
		$row1[] = "";
		$row1[] = "Select Departemen";

		$data[] = $row1;

		foreach ($list as $key) {
			$row = array();

			$row[] = $key->id;
			$row[] = $key->nama;

			array_push($data, $row);
		}

        //output to json format
		echo json_encode($data);
	}

	public function ajax_sec()
	{
		$id = $_POST['id'];
		$list = $this->karyawan_model->get_sec($id);

		$data = array();

		$row1 = array();
		$row1[] = "";
		$row1[] = "Select Section";

		$data[] = $row1;
		if ($list) {
			foreach ($list as $key) {
				$row = array();

				$row[] = $key->id;
				$row[] = $key->nama;

				array_push($data, $row);
			}
		}

        //output to json format
		echo json_encode($data);
	}

	public function ajax_subsec()
	{
		$id = $_POST['id'];
		$list = $this->karyawan_model->get_subsec($id);

		$data = array();

		$row1 = array();
		$row1[] = "";
		$row1[] = "Select Sub-Section";

		$data[] = $row1;
		if ($list) {
			foreach ($list as $key) {
				$row = array();

				$row[] = $key->id;
				$row[] = $key->nama;

				array_push($data, $row);
			}
		}

        //output to json format
		echo json_encode($data);
	}

	public function ajax_group()
	{
		$id = $_POST['id'];
		$list = $this->karyawan_model->get_group($id);

		$data = array();

		$row1 = array();
		$row1[] = "";
		$row1[] = "Select Group";

		$data[] = $row1;
		if ($list) {
			foreach ($list as $key) {
				$row = array();

				$row[] = $key->id;
				$row[] = $key->nama;

				array_push($data, $row);
			}
		}

        //output to json format
		echo json_encode($data);
	}

	public function ajax_grade()
	{
		$id = $_POST['id'];
		$list = $this->karyawan_model->get_nama_grade($id);

		$data = array();

		$row1 = array();
		$row1[] = "";
		$row1[] = "Select Nama Grade";

		$data[] = $row1;
		if ($list) {
			foreach ($list as $key) {
				$row = array();

				$row[] = $key->id;
				$row[] = $key->nama_grade;

				array_push($data, $row);
			}
		}

        //output to json format
		echo json_encode($data);
	}

	public function add()
	{
		$config['upload_path']="./app/foto";
		$config['allowed_types']='jpg|png';
		$config['file_name'] = $_POST['nik'];

		$this->load->library('upload',$config);
		if($this->upload->do_upload("foto")){
			$data = array('upload_data' => $this->upload->data());

			$nik = $_POST['nik'];
			$nama = $_POST['nama'];
			$tmptL = $_POST['tmptL'];
			$tglL = $_POST['tglL'];
			$jk = $_POST['jk'];
			$ktp = $_POST['ktp'];
			$alamat = $_POST['alamat'];
			$statusK = $_POST['statusK'];
			$dev = $_POST['devisi'];
			$dep = $_POST['departemen'];
			$sec = $_POST['section'];
			$subsec = $_POST['subsection'];
			$group = $_POST['group'];
			$grade = $_POST['grade'];			
			$jab = $_POST['jabatan'];
			$kode = $_POST['kode'];
			$statusKar = $_POST['statusKar'];
			$pin = $_POST['pin'];
			$tglM = $_POST['tglM'];
			$cs = $_POST['cs'];
			$hp = $_POST['hp'];
			$bpjstk = $_POST['bpjstk'];
			$bpjskes = $_POST['bpjskes'];
			$no_rek = $_POST['no_rek'];
			$npwp = $_POST['npwp'];
			$jp = $_POST['jp'];
			$image = $data['upload_data']['file_name']; 

			$result= $this->karyawan_model->tambah($image, $nik, $nama, $tmptL, $tglL, $jk, $ktp, $alamat, $statusK, $dev, $dep, $sec, $subsec, $group, $grade, $jab, $kode, $statusKar, $pin, $tglM, $cs, $hp, $bpjstk, $bpjskes, $no_rek, $npwp, $jp);
			echo json_decode($result);
		}

	}

	public function outsource_data()
	{
		$list = $this->outsource_model->outsource_data();
		$tot = $this->outsource_model->count_all();
		$filter = $this->outsource_model->count_filtered();

		$data = array();

		if ($list) {
			foreach ($list as $key) {
				$row = array();

				$row[] = $key->bulan2;
				$row[] = $key->masuk;
				$row[] = $key->keluar;
				$row[] = $key->total;

				$data[] = $row;
			}
		}

		$output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $tot,
            "recordsFiltered" => $filter,
            "data" => $data,
        );
            //output to json format
        echo json_encode($output);
	}


	public function add_outsource()
	{
		$periode = $_POST['periode'];
		$masuk = $_POST['masuk'];
		$keluar = $_POST['keluar'];
		$tot = $_POST['tot'];

		$result = $this->outsource_model->tambah($periode, $masuk, $keluar, $tot);
		echo json_decode($result);
	}

}
?>