<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cari_karyawan_model extends CI_Model {
	var $column_order = array('k.nik','namaKaryawan','dep/subSec','sec/Group','tanggalMasuk','statusKaryawan','status'); //set column field database for datatable orderable
    var $column_search = array('k.nik','namaKaryawan','dep/subSec','sec/Group','DATE_FORMAT(tanggalMasuk, "%d %b %Y")','statusKaryawan','status'); //set column field database for datatable searchable 
    var $order = array('tanggalMasuk' => 'asc', 'nik' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_data_karyawan_cari($status, $grade,$dep,$pos)
    {
        $this->_get_datatables_query($status, $grade,$dep,$pos);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

	private function _get_datatables_query($status, $grade, $dep, $pos)
    {
        $this->db->select("k.nik, k.namaKaryawan, dv.nama as namadev, dp.nama as namadep, DATE_FORMAT(k.tanggalMasuk, '%d %b %Y') as tanggalMasuk, k.statusKaryawan, k.status");
        $this->db->from('karyawan k');
        $this->db->join('posisi p','k.nik = p.nik', 'left');
        $this->db->join('devisi dv','p.id_devisi = dv.id', 'left');
        $this->db->join('departemen dp','p.id_dep = dp.id', 'left');
        $this->db->join('section sec','p.id_sec = sec.id', 'left');
        $this->db->join('sub_section ssec','p.id_sub_sec = ssec.id', 'left');
        $this->db->join('group1 gr','p.id_group = gr.id', 'left');
        $this->db->where('tanggalKeluar IS NULL');

        if ($status) {
            $this->db->where("statusKaryawan", $status);
        }

        if ($grade) {
            $this->db->where("k.grade", $grade);
        }

        if ($dep) {
            $this->db->where("dp.nama", $dep);
        }

        if ($pos) {
            $this->db->where("k.jabatan", $pos);
        }
 
        $i = 0;
     
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function count_filtered($status, $grade, $dep, $pos)
    {
        $this->_get_datatables_query($status, $grade, $dep, $pos);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from('karyawan');
        return $this->db->count_all_results();
    }
}
?>