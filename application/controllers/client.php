<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('client_model');
	}

	public function view()
	{
		$id = $this->session->userdata("id");
		$data['client'] = $this->client_model->get_data_client($id);
		$this->load->view('client', $data);
	}
}
?>