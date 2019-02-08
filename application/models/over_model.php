<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Over_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function get_dep()
	{
		$query = $this->db->get('departemen');
		return $query->result();
	}

	public function save_master($no_doc, $tgl, $dep, $sec, $kep, $cat)
	{
		$data = array(
			'id' => $no_doc,
			'tanggal' => $tgl,
			'departemen' => $dep,
			'section' => $sec,
			'keperluan' => $kep,
			'catatan' => $cat
		);

		$this->db->insert('over_time', $data);
	}

	public function save_member($no_doc, $nik1, $dari1, $sampai1, $jam1, $trans1, $makan1, $exfood1)
	{
		$data = array(
			'id_ot' => $no_doc,
			'nik' => $nik1,
			'dari' => $dari1,
			'sampai' => $sampai1,
			'jam' => $jam1,
			'transport' => $trans1,
			'makan' => $makan1,
			'ext_food' => $exfood1
		);

		$this->db->insert('over_time_member', $data);	}
	}
	?>