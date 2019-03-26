<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Over_model_new extends CI_Model {

	var $column_order = array('id','tanggal','nik','nama','masuk','keluar','jam','aktual','diff','final2'); //set column field database for datatable orderable
    var $column_search = array('id','DATE_FORMAT(tanggal, "%d-%m-%Y")','nik','nama','masuk','keluar','jam','aktual','diff','final2'); //set column field database for datatable searchable 
    var $order = array('id' => 'desc'); // default order 

    public function __construct()
    {
    	parent::__construct();
    	$this->load->database();
    }

    // NO WITH PARAMETER
    public function get_data_ot($tgl)
    {
        $this->_get_datatables_query($tgl);
        if($_GET['length'] != -1)
            $this->db->limit($_GET['length'], $_GET['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_datatables_query($tgl)
    {
        $this->db->select("IFNULL(d.id,'-') as id, over.tanggal, over.nik, karyawan.namaKaryawan, presensi.masuk, presensi.keluar, over.jam as aktual, IFNULL(d.jam,0) as jam_plan, IFNULL((over.jam - d.jam),0) as diff, IF(final = 0 OR final IS NULL, d.jam, 0) as final2, final, d.status");
        $this->db->from("over");
        $this->db->join('(
            select over_time_member.id_ot, over_time.id, tanggal, nik, jam, final, over_time_member.status from over_time
            left join over_time_member on over_time_member.id_ot = over_time.id
        ) d','d.tanggal = over.tanggal and d.nik = over.nik','left');
        $this->db->join("karyawan","karyawan.nik = over.nik","left");
        $this->db->join("presensi","presensi.nik = over.nik","left");
        $this->db->join("presensi p2","p2.tanggal = over.tanggal","left");
        $this->db->where("over.tanggal = '".$tgl."'");
        $this->db->group_by(array("over.tanggal", "over.nik", "d.id_ot"));


        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            if($_GET['search']['value']) // if datatable send POST for search
            {

                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_GET['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_GET['search']['value']);
                }

                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
                }
                $i++;
            }

        if(isset($_GET['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    // NO PARAMETER
    public function get_data_ot_defaeult()
    {
        $this->_get_datatables_query_defaeult();
        if($_GET['length'] != -1)
            $this->db->limit($_GET['length'], $_GET['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_datatables_query_defaeult()
    {
        $this->db->select("tanggal, nik,nama,masuk,keluar,id,shift,status,jam,final,id_jam,jam_lembur, IFNULL(aktual, 0) as aktual, IFNULL(diff, 0) as diff, IFNULL(final2, 0) as final2");
        $this->db->from("
            (select tanggal,c.nik1 as nik, d.namaKaryawan as nama, masuk, keluar, id, shift, c.status, jam, final, c.id_jam, c.jam_lembur,
            (IF(hari = 'L',
            floor((TIME_TO_SEC(TIMEDIFF(concat('2010-08-20 ',keluar), concat('2010-08-20 ',masuk)))) / 60 / 60 * 2) / 2, 
            IF(shift = 1,
            floor((

            IF(c.jam_lembur = 'Awal',0,
            IF(DATE_FORMAT(tanggal,'%a') = 'Fri',
            (TIME_TO_SEC(TIMEDIFF(concat('2010-08-20 ',keluar), '2010-08-20 16:30:00'))),
            (TIME_TO_SEC(TIMEDIFF(concat('2010-08-20 ',keluar), '2010-08-20 16:00:00'))))
            )
            + 

            IF(c.jam_lembur = 'Awal',(TIME_TO_SEC(TIMEDIFF('2010-08-20 07:00:00' , concat('2010-08-20 ',masuk)))),0)
            )/ 60 / 60 * 2) / 2
            , IF(shift = 2,
            floor((IF(c.jam_lembur = 'Awal',0,
            IF(DATE_FORMAT(tanggal,'%a') = 'Fri'
            ,(TIME_TO_SEC(TIMEDIFF(concat('2010-08-20 ',keluar), '2010-08-20 00:45:00')))
            ,(TIME_TO_SEC(TIMEDIFF(concat('2010-08-20 ',keluar), '2010-08-20 00:15:00'))))
            )

            + 

            IF(c.jam_lembur = 'Awal',
            IF(DATE_FORMAT(tanggal,'%a') = 'Fri'
            ,(TIME_TO_SEC(TIMEDIFF('2010-08-20 16:30:00' , concat('2010-08-20 ',masuk))))
            ,(TIME_TO_SEC(TIMEDIFF('2010-08-20 16:00:00' , concat('2010-08-20 ',masuk)))))                          
            ,0)

            )/ 60 / 60 * 2) / 2
            , IF(shift = 3,
            floor((IF(c.jam_lembur = 'Awal',0,
            IF(DATE_FORMAT(tanggal,'%a') = 'Fri'
            ,(TIME_TO_SEC(TIMEDIFF(concat('2010-08-20 ',keluar), '2010-08-20 07:40:00')))
            ,(TIME_TO_SEC(TIMEDIFF(concat('2010-08-20 ',keluar), '2010-08-20 07:10:00'))))
            )

            + 

            IF(c.jam_lembur = 'Awal',(TIME_TO_SEC(TIMEDIFF('2010-08-20 00:00:00' , concat('2010-08-20 ',masuk)))),0)
            )/ 60 / 60 * 2) / 2
            , 0)))
            ) - (SELECT istirahat from master_lembur where id = c.id_jam))

            as aktual, 
            ((SELECT aktual) - jam) as diff,
            IF((SELECT aktual) > jam , 
            IF(final <> 0, ROUND((SELECT final), 1) , ROUND(jam, 1))
            , ROUND((SELECT aktual), 1))
            as final2

            from (SELECT * from (
            SELECT o.tanggal, o.id, b.jam, b.nik as nik1,b.id_jam as id_jam,b.jam_lembur as jam_lembur, b.status as status, final, hari from over_time as o
            LEFT JOIN over_time_member as b
            on o.id = b.id_ot
            ) a

            left join (
            SELECT presensi.nik,presensi.masuk,presensi.keluar,presensi.tanggal as tanggalpresensi, shift from presensi where presensi.nik in (SELECT over_time_member.nik from over_time_member) and presensi.tanggal in (SELECT over_time.tanggal from over_time)
            ) b on a.tanggal = b.tanggalpresensi and a.nik1 = b.nik) c
            left join karyawan d on c.nik = d.nik ) tmp
            ");
        $this->db->where("tanggal = curdate()");
        $this->db->where("status = '0'");


        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            if($_GET['search']['value']) // if datatable send POST for search
            {

                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_GET['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_GET['search']['value']);
                }

                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
                }
                $i++;
            }

        if(isset($_GET['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function count_filtered2($tgl)
    {
        $this->_get_datatables_query($tgl);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all2($tgl)
    {
        $this->_get_datatables_query($tgl);
        return $this->db->count_all_results();
    }


    public function get_over_by_id($id)
    {
        $this->db->select("o.id as id_over, tanggal, s.nama as section, sc.nama as sub_sec, gr.nama as grup, keperluan, catatan, hari");
        $this->db->from('over_time o');
        $this->db->join('section s','o.departemen = s.id','left');
        $this->db->join('sub_section sc','o.section = sc.id','left');
        $this->db->join('group1 gr','o.sub_sec = gr.id','left');
        $this->db->where("o.id",$id);
        $query = $this->db->get();
        return $query->result();
    }

    // GET DATA EMPLOYEE OVERTIME
    public function get_over_by_id_member($id)
    {
        $this->_get_datatables_query2($id);
        if($_GET['length'] != -1)
            $this->db->limit($_GET['length'], $_GET['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_member_id($id,$tgl)
    {
        $this->db->select("o.id as id_over, tanggal, departemen, section, keperluan, catatan, om.*, k.namaKaryawan, cc.budget, cc.id_cc, cc.aktual as aktual");
        $this->db->from('over_time o');
        $this->db->join("over_time_member om","o.id = om.id_ot");
        $this->db->join("karyawan k","om.nik = k.nik");
        $this->db->join("cost_center_budget cc","cc.id_cc = k.costCenter");
        $this->db->where("o.id",$id);
        $this->db->where("MONTH(cc.period) = MONTH(STR_TO_DATE('".$tgl."', '%d-%m-%Y'))");
        $this->db->where("YEAR(cc.period) = YEAR(STR_TO_DATE('".$tgl."', '%d-%m-%Y'))");
        $this->db->group_by('k.nik');
        $query = $this->db->get();
        return $query->result();
    }

    public function costCenter($id)
    {
        $this->db->select("costCenter, COUNT(nik) as jml");
        $this->db->from("karyawan");
        $this->db->where("costCenter", $id);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_datatables_query2($id)
    {
        $this->db->select("o.id as id_over, tanggal, departemen, section, keperluan, catatan, om.*, k.namaKaryawan");
        $this->db->from('over_time o');
        $this->db->join("over_time_member om","o.id = om.id_ot");
        $this->db->join("karyawan k","om.nik = k.nik");
        $this->db->where("o.id",$id);


        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            if($_GET['search']['value']) // if datatable send POST for search
            {

                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_GET['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_GET['search']['value']);
                }

                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
                }
                $i++;
            }

        if(isset($_GET['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_id_doc()
    {
        $this->db->select("id");
        $this->db->where("YEAR(tanggal) = YEAR(CURRENT_DATE())");
        $this->db->where("MONTH(tanggal) = MONTH(CURRENT_DATE())");
        $this->db->from("over_time");
        $this->db->order_by('id','DESC');

        $query = $this->db->get();
        return $query->result();
    }

    public function by_bagian(){
        $q = "SELECT nama, section, tanggal, SUM(om.jam) AS jml from over_time o 
        JOIN over_time_member om ON o.id = om.id_ot
        LEFT JOIN departemen d ON o.departemen = d.id
        where MONTH(o.tanggal) = MONTH(CURRENT_DATE()) GROUP BY o.departemen ORDER BY jml DESC";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }

    public function by_bagian_cari($tgl){
        $q = "SELECT nama, section, tanggal, SUM(om.jam) AS jml from over_time o 
        LEFT JOIN departemen d ON o.departemen = d.id
        JOIN over_time_member om ON o.id = om.id_ot
        where MONTH(o.tanggal) = MONTH( STR_TO_DATE('".$tgl."', '%d-%m-%Y')) GROUP BY o.departemen ORDER BY jml DESC";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }


    public function get_over_by_bagian($tgl, $dep)
    {
        $this->_get_datatables_query3($tgl, $dep);
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    private function _get_datatables_query3($tgl, $dep)
    {
        $this->db->select("nama, tanggal, k.namaKaryawan, om.jam, o.keperluan");
        $this->db->from('over_time o');
        $this->db->join("departemen d","o.departemen = d.id", 'LEFT');
        $this->db->join("over_time_member om","o.id = om.id_ot");
        $this->db->join("karyawan k","om.nik = k.nik");
        $this->db->where("d.nama",$dep);
        $this->db->where("MONTH(o.tanggal) = MONTH(STR_TO_DATE('".$tgl."', '%d-%m-%Y'))");


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

}
?>
