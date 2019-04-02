<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Management extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('home_model');
		$this->load->model('absensi_model');
		$this->load->model('karyawan_model');
		$this->load->model('over_report_model');
		$this->load->model('budget_model');
	}

	public function index()
	{
		$data['i'] = 'ok';
		$data['menu'] = 'ovrMoC';
		$data['section'] = $this->budget_model->get_name_cc2();
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
		$data['section'] = $this->budget_model->get_name_cc();
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

	public function detailSPL2($nik,$tgl)
    {
    	$data['menu2'] = 'Overtime';
        $data['menu'] = 'OT Management By NIK';
        $data['nik'] = $nik;
        $data['tgl'] = $tgl;
        $data['i'] = 'ok';
        $this->load->view("graph_report",$data);
    }


    public function ajax_ot_report_d()
	{
		$list = $this->over_report_model->get_ot_report2();

		$tot = $this->over_report_model->count_all2();
		$filter = $this->over_report_model->count_filtered2();

		$data = array();
		if(!empty($list)) {
			foreach ($list as $key) {
				$row = array();
				$row[] = $key->period;
				$row[] = $key->nik;
				$row[] = $key->namaKaryawan;
				$row[] = $key->bagian;
				$row[] = $key->total_jam;
				// $row[] = "<button class='btn btn-primary btn-xs' onclick='
				// detail2(\"".$key->nik."\",\"".$key->period."\",\"".$key->namaKaryawan."\")'>Detail</button>";

				$row[] = "<a class='btn btn-primary btn-sm' href='".base_url('management/detailSPL2/'.$key->nik."/".$key->period)."'> Detail </a>";

				$data[] = $row;
			}

		}else
		$data[] = json_decode("{}");

            //output to json format
		$output = array(
			"draw" => $_GET['draw'],
			"recordsFiltered" => $filter,
			"recordsTotal" => $tot,
			"data" => $data,
		);
            //output to json format
		echo json_encode($output);
	}

}
?>
