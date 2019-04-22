<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_model extends CI_Model {
	var $column_order = array('nama'); //set column field database for datatable orderable
    var $column_search = array('nama'); //set column field database for datatable searchable s

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_data_master($id)
    {
        $this->_get_master($id);
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

        public function get_data_master_role($id)
    {
        $this->_get_master_role($id);
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_master_role($id)
    {
        if ($id=="menu") {
            $this->db->select("id_menu as id,parent_menu,nama_menu AS nama,url,icon , 'menu' as alias");
            $this->db->from('master_menu');
        }
        else if ($id=="user") {
           $this->db->select("id,role, username,nama,department ,'user' as alias");
           $this->db->from('login');
       }else if ($id=="role") {
        $this->db->select("id_role AS id, nama, 'role' as alias");
        $this->db->from('master_role'); 
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

    private function _get_master($id)
    {
        if ($id=="tgrup") {
            $this->db->select("*");
            $this->db->from('(select group1.id,group1.nama, 0 as jml,sub_section.nama as induk,"grp" as alias from group1
                LEFT JOIN sub_section on sub_section.id = group1.id_sub) a');
        }
        else if ($id=="tsub") {
           $this->db->select("*");
           $this->db->from('(select sub_section.id,sub_section.nama, count(sub_section.id) as jml, section.nama as induk,"sub" as alias FROM sub_section 
            left join
            section on section.id = sub_section.id_sec
            left join 
            group1 on sub_section.id = group1.id_sub group by sub_section.nama order by sub_section.nama asc) a');
       }

       else if ($id =="tsec") {
           $this->db->select("*");
           $this->db->from('(
            select section.id,section.nama, count(section.id) as jml, departemen.nama as induk,"sec" as alias FROM section 
            LEFT JOIN
            departemen on departemen.id = section.id_departemen
            left join             
            sub_section on section.id = sub_section.id_sec group by section.nama order by section.nama asc) a');
       }

       else if ($id=="tdep") {
        $this->db->select("*");
        $this->db->from('(select departemen.id,departemen.nama, count(departemen.id) as jml, devisi.nama as induk,"dep" as alias FROM departemen 
            LEFT JOIN 
            devisi on devisi.id = departemen.id_devisi
            left join 
            section on departemen.id = section.id_departemen group by departemen.nama order by departemen.nama asc) a');
    }else{
        $this->db->select("*");
        $this->db->from('(select "-" as induk,devisi.id,devisi.nama, count(departemen.id) as jml,"dev" as alias FROM devisi left join departemen on devisi.id = departemen.id_devisi group by devisi.nama order by devisi.nama asc) a'); 
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

    function count_filtered($id)
    {
        $this->_get_master($id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($id)
    {
        $this->_get_master($id);
        return $this->db->count_all_results();
    }

    function count_filtered_role($id)
    {
        $this->_get_master_role($id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_role($id)
    {
        $this->_get_master_role($id);
        return $this->db->count_all_results();
    }

    public function get_devisi($id,$nama,$dbtabel,$induk,$tb)
    {
       $this->db->Where('id',$id);
       $this->db->set('nama',$nama);
       $this->db->set($tb,$induk);
       $this->db->update($dbtabel);
   }

   public function get_select($nama)
   {
        $this->db->select("id,nama");
        $this->db->from($nama);
        $query = $this->db->get();
        return $query->result();
    
   }
}
?>