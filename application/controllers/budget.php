<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Budget extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('budget_model');
	}

	public function index()
	{
		$data['menu'] = 'cc';
		$this->load->view('budget',$data);
	}

	public function ajax_budget()
	{
		$list = $this->budget_model->get_data_budget();
		$tot = $this->budget_model->count_all();
		$filter = $this->budget_model->count_filtered();

		$data = array();
		foreach ($list as $key) {
			$row = array();
			$row[] = $key->id_cc;
			$row[] = $key->period2;
			$row[] = $key->budget;
			
			$data[] = $row;
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
}
?>