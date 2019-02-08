<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ot extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('over_model');
	}

	public function ot_submit()
	{
		$no_doc = $_POST['no'];
		$tgl = date('Y-m-d',strtotime($_POST['tgl']));
		$dep = $_POST['dep'];
		$sec = $_POST['sec'];
		$kep = $_POST['kep'];
		$cat = $_POST['cat'];
		$this->over_model->save_master($no_doc, $tgl, $dep, $sec, $kep, $cat);
	}

	public function ot_member_submit()
	{
		$no_doc = $_POST['nodoc2'];
		$no = $_POST['nomor'];

		for ($i=1; $i <= $no; $i++) { 
			$nik = $_POST['nik'.$i];
			$dari = $_POST['dari'.$i];
			$sampai = $_POST['sampai'.$i];
			$jam = $_POST['jam'.$i];
			$trans = $_POST['trans'.$i];

			if (isset($_POST['makan'.$i])) {
				$makan = 1;
			}
			else
				$makan = 0;

			if (isset($_POST['exfood'.$i])) {
				$exfood = 1;
			}
			else
				$exfood = 0;

			$this->over_model->save_member($no_doc, $nik, $dari, $sampai, $jam, $trans, $makan, $exfood);
		}
	}
}
?>
