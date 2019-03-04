<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Karyawan_form extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('karyawan_model');
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
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload',$config);
		if($this->upload->do_upload("file")){
			$data = array('upload_data' => $this->upload->data());

			$nik = $_POST['nik'];
			$nama = $_POST['nama'];
			$tmptL = $_POST['tmptL'];
			$tglL = $_POST['tglL'];
			$jk = $_POST['jk'];
			$ktp = $_POST['ktp'];
			$alamat = $_POST['alamat'];
			$statusK = $_POST['statusK'];
			$dev = $_POST['dev'];
			$dep = $_POST['dep'];
			$sec = $_POST['sec'];
			$subsec = $_POST['subsec'];
			$group = $_POST['group'];
			$grade = $_POST['grade'];
			$ngrade = $_POST['ngrade'];
			$jab = $_POST['jab'];
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

			$result= $this->karyawan_model->tambah($image, $nik, $nama, $tmptL, $tglL, $jk, $ktp, $alamat, $statusK, $dev, $dep, $sec, $subsec, $group, $grade, $ngrade, $jab, $kode, $statusKar, $pin, $tglM, $cs, $hp, $bpjstk, $bpjskes, $no_rek, $npwp, $jp);
			echo json_decode($result);
		}

	}
}
?>