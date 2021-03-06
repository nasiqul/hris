<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Over_model extends CI_Model {

	var $column_order = array('id','tanggal','nik','nama','masuk','keluar','jam','aktual','diff','final2'); //set column field database for datatable orderable
    var $column_search = array('id','DATE_FORMAT(tanggal, "%d-%m-%Y")','nik','nama','masuk','keluar','jam','aktual','diff','final2'); //set column field database for datatable searchable 
    var $order = array('id' => 'desc'); // default order 

    public function __construct()
    {
    	parent::__construct();
    	$this->load->database();
    }

    public function get_data_ot_defaeult()
    {
        $this->_get_datatables_query_defaeult();
        if($_GET['length'] != -1)
            $this->db->limit($_GET['length'], $_GET['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function caobaaa_default()
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

        $query = $this->db->get();
        return $query->result();

    }

    public function get_data_ot($tgl)
    {
        $this->_get_datatables_query($tgl);
        if($_GET['length'] != -1)
            $this->db->limit($_GET['length'], $_GET['start']);
        $query = $this->db->get();
        return $query->result();
    }


    public function caobaaa($tgl)
    {
        $this->db->select("b.*, count(b.nik) as jml");
        $this->db->from("
           (

           select tanggal, nik,nama,masuk,keluar,id,shift,status,jam,final,id_jam,jam_lembur, IFNULL(aktual, 0) as aktual, IFNULL(diff, 0) as diff, IFNULL(final2, 0) as final2, hari from 


           (select tanggal,c.nik1 as nik, d.namaKaryawan as nama, masuk, keluar, id, shift, c.status, jam, final, c.id_jam, c.jam_lembur, hari, 
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
           ) as b
           ");

        $this->db->where("tanggal = '".$tgl."'");
        $this->db->where("masuk IS NOT NULL");
        $this->db->where("keluar IS NOT NULL");
        $this->db->group_by('nik');

        $query = $this->db->get();
        return $query->result();

    }
    private function _get_datatables_query($tgl)
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
        $this->db->where("tanggal = '".$tgl."'");
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

    // public function update_data_over($where,$table){
    //     $this->db->set('status', "1");
    //     $this->db->where($where);
    //     $this->db->update($table);
    // }   


    public function update_data_final($where,$table,$data){
        $this->db->set('final', $data);
        $this->db->set('status', "1");
        $this->db->where($where);
        $this->db->update($table);
    }  

    public function update_data_budget($where,$table,$data){
        $this->db->set('aktual', $data);
        $this->db->where($where);
        $this->db->update($table);
    }  

    public function get_dep($id)
    {
        $q = "select * from section where id_departemen = (select departemen.id from login join departemen on login.department = departemen.nama where username = '".$id."')";
        $query = $this->db->query($q);

        if ($query->num_rows() == 0) {
            $q = "select * from section";
            $query = $this->db->query($q);
        }
        return $query->result();
    }

    public function get_sub_sec($id)
    {
        $this->db->where("id_sec",$id);
        $query = $this->db->get('sub_section');
        return $query->result();
    }

    public function get_grup($id)
    {
        $this->db->where("id_sub",$id);
        $query = $this->db->get('group1');
        return $query->result();
    }   

    public function save_master($no_doc, $tgl, $dep, $sec, $subsec, $kep, $cat, $hari, $grup, $shift, $nik)
    {
    	$data = array(
    		'id' => $no_doc,
    		'tanggal' => $tgl,
    		'departemen' => $dep,
    		'section' => $sec,
    		'keperluan' => $kep,
    		'catatan' => $cat,
            'hari' => $hari,
            'sub_sec' => $subsec,
            'status_shift' => $grup,
            'shift' => $shift,
            'nik_create' => $nik,
            'created_at' => date('Y-m-d'),
            'last_edited' => date('Y-m-d')
        );

    	$this->db->insert('over_time', $data);
    }

    public function save_member($no_doc, $nik1, $dari1, $sampai1, $jam1, $trans1, $makan1, $exfood1, $idJam1)
    {
        $this->db->select("lembur");
        $this->db->from("master_lembur");
        $this->db->where("id",$idJam1);
        $this->db->where("lembur","AW");

        $q = $this->db->get()->num_rows();
        if ($q > 0) {
            $jamL = 'Awal';
        }
        else
        {
            $jamL = 0;
        }

        $data = array(
          'id_ot' => $no_doc,
          'nik' => $nik1,
          'dari' => $dari1,
          'sampai' => $sampai1,
          'jam' => $jam1,
          'transport' => $trans1,
          'makan' => $makan1,
          'ext_food' => $exfood1,
          'id_jam' => $idJam1,
          'jam_lembur' => $jamL
      );

        $this->db->insert('over_time_member', $data);	
    }

    public function update_member($id_user, $nik1, $dari1, $sampai1, $jam1, $trans1, $makan1, $exfood1, $idJam1, $stat1)
    {
        $this->db->set('dari', $dari1);
        $this->db->set('sampai', $sampai1);
        $this->db->set('jam', $jam1);
        $this->db->set('transport', $trans1);
        $this->db->set('makan', $makan1);
        $this->db->set('ext_food', $exfood1);
        $this->db->set('id_jam', $idJam1);
        $this->db->set('jam_aktual', $stat1);

        $this->db->where('id', $id_user);
        $this->db->update('over_time_member'); 
    }

    public function deleteSPL($id_ot)
    {
        $this->db->where('id_ot', $id_ot);
        $this->db->delete('over_time_member');
    }

    public function editSPL_status($id_ot)
    {
        $nik = $this->session->userdata('nikLogin');
        $this->db->set('last_edited', date('Y-m-d'));
        $this->db->set('nik_create', $nik);
        $this->db->where('id', $id_ot);
        $this->db->update('over_time');
    }

    function count_filtered()
    {
        $this->_get_datatables_query_defaeult();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from('over_time');
        return $this->db->count_all_results();
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
        $this->db->select("o.id as id_over, tanggal, s.nama as section, sc.nama as sub_sec, gr.nama as grup, keperluan, catatan, hari, departemen.nama as dp");
        $this->db->from('over_time o');
        $this->db->join('section s','o.departemen = s.id','left');
        $this->db->join('sub_section sc','o.section = sc.id','left');
        $this->db->join('group1 gr','o.sub_sec = gr.id','left');
        $this->db->join("departemen",'departemen.id = s.id_departemen','left');
        $this->db->where("o.id",$id);
        $query = $this->db->get();
        return $query->result();
    }


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
        // $q = "select om.nik, karyawan.namaKaryawan, costCenter, dari, sampai, transport, makan, ext_food, jam from over_time o 
        // join over_time_member om on o.id = om.id_ot
        // left join karyawan on karyawan.nik = om.nik
        // where o.id = '".$id."' and jam_aktual = 0";

        $tgl2 = date('Y-m-d',strtotime($tgl));
        $first = date('Y-m-01',strtotime($tgl));

        $q = "select ovr.*, if(mut.position = '-', mut.grade_name, mut.position) grade, IFNULL(ovr_all.jam,0) as jam_all from 
        (select om.nik, karyawan.namaKaryawan, costCenter, dari, sampai, transport, makan, ext_food, jam from over_time o 
        join over_time_member om on o.id = om.id_ot
        left join karyawan on karyawan.nik = om.nik
        where o.id = '".$id."' and jam_aktual = 0) as ovr left join
        (select employee_id, grade_name, position from ympimis.promotion_logs where valid_to is null) as mut on ovr.nik = mut.employee_id
        left join
        (
        select nik, SUM(IF(`status` = 1,final,jam)) as jam from over_time left join over_time_member on over_time.id = over_time_member.id_ot
        where over_time.tanggal >= '".$first."' and over_time.tanggal < '".$tgl2."' and jam_aktual = 0 and deleted_at is null
        group by nik
    ) as ovr_all on ovr_all.nik = ovr.nik";
    $query = $this->db->query($q);
    return $query->result();
}

public function costCenter($id, $tgl, $fy)
{
    $q = "select n.*, z.act from 
    (
    select d.id_cc, d.period, (d.budget * m.karyawan) as tot_budget from (
    select cost_center as id_cc, period, budget from ympimis.budgets where date_format(period, '%Y-%m') = '".$tgl."'
    ) d
    left join
    (
    select mon, master_cc.id_cc, sum(tot_karyawan) as karyawan from (
    select mon, costCenter, count(if(if(date_format(a.tanggalMasuk, '%Y-%m') < mon, 1, 0 ) - if(date_format(a.tanggalKeluar, '%Y-%m') < mon, 1, 0 ) = 0, null, 1)) as tot_karyawan from
    (
    select distinct fiskal, date_format(tanggal, '%Y-%m') as mon
    from kalender_fy
    ) as b
    join
    (
    select '".$fy."' as fy, karyawan.kode, tanggalKeluar, tanggalMasuk, nik, costCenter
    from karyawan
    ) as a
    on a.fy = b.fiskal
    group by mon, costCenter
    having mon = '".$tgl."'
    ) as b 
    left join master_cc on master_cc.id_cc = b.costCenter
    GROUP BY mon, master_cc.id_cc
    ) m on m.id_cc = d.id_cc
    where d.id_cc = '".$id."'
    ) n left join (
    select tanggal, sum(jam) act, costCenter from over
    left join karyawan on karyawan.nik = over.nik
    where date_format(tanggal, '%Y-%m') = '".$tgl."' and costCenter='".$id."'
    group by costCenter
    ) z on n.id_cc = z.costCenter
    ";
    $query = $this->db->query($q);
    return $query->result();
}

private function _get_datatables_query2($id)
{
    $this->db->select("o.id as id_over, tanggal, departemen, section, keperluan, catatan, om.*, k.namaKaryawan");
    $this->db->from('over_time o');
    $this->db->join("over_time_member om","o.id = om.id_ot");
    $this->db->join("karyawan k","om.nik = k.nik");
    $this->db->where("o.id",$id);
    $this->db->where("om.jam_aktual",0);


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

    public function cek_id($nik, $dep)
    {
        $this->db->select("k.nik");
        $this->db->from('karyawan k');
        $this->db->join("posisi p","p.nik = k.nik");
        // $this->db->join("departemen d","p.id_dep = d.id");
        $this->db->like('k.nik', $nik, 'before');
        // $this->db->where("p.id_dep",$dep);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function by_bagian_bulan(){
        $q = "";
        $query = $this->db->query($q);

        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
    }


    public function get_cc($nik,$val,$tgl,$id)
    {
        $sql = "UPDATE over_time_member AS om JOIN over_time AS o ON om.id_ot = o.id SET om.status = 1, om.jam_aktual = ".(float)$val." WHERE o.tanggal = STR_TO_DATE('".$tgl."', '%d-%m-%Y') AND om.nik = '".$nik."'";
        $this->db->query($sql);

        $sql = "UPDATE over_time_member AS om 
        JOIN over_time AS o ON om.id_ot = o.id 
        JOIN satuan_lembur sl ON sl.jam = om.jam_aktual 
        SET om.satuan = sl.satuan
        WHERE o.tanggal = STR_TO_DATE('".$tgl."', '%d-%m-%Y') AND om.nik = '".$nik."' AND sl.hari = (
        SELECT hari from over_time where id = '".$id."'
    )";
    $this->db->query($sql);

    $this->db->select("k.costCenter");
    $this->db->from("karyawan k");
    $this->db->where("k.nik",$nik);
    $query = $this->db->get();
    return $query->result();
}

// public function tambah_aktual($id_cc,$val,$tgl)
// {
//     $query2 = "UPDATE `cost_center_budget` SET `aktual` = `aktual` + ".(float)$val." WHERE `id_cc` = '".$id_cc."' AND MONTH(period) = MONTH(STR_TO_DATE('".$tgl."', '%d-%m-%Y')) 
//     AND YEAR(period) = YEAR(STR_TO_DATE('".$tgl."', '%d-%m-%Y'))";
//     $this->db->query($query2);
// }

public function get_calendar($tgl)
{
    $this->db->select("tanggal");
    $this->db->from("kalender");
    $this->db->where("tanggal = STR_TO_DATE('".$tgl."', '%Y-%m-%d')");

    $query = $this->db->get();
    return $query->num_rows();
}

public function set_jam($id, $nik, $jam)
{
    $sql = "UPDATE over_time_member SET 
    final = ".$jam."
    WHERE id_ot = ".$id." AND nik = '".$nik."'";
    $this->db->query($sql);
}

public function get_data_chart($tgl,$cc,$tgl2)
{
    $q = "
    select week_date, COALESCE(jam,0) act, ".$cc." cost_center, round((select (budget / DATE_FORMAT(LAST_DAY('".$tgl."'),'%d')) budget_tot from ympimis.budgets where DATE_FORMAT(period,'%Y-%m') = '".$tgl2."' and cost_center = ".$cc." ),2) as budget_total from
    (select ovr.tanggal, sum(ovr.jam) as jam, cost_center from 
    (select nik, tanggal, sum(IF(status = 0, jam, final)) as jam from over_time_member left join over_time on over_time.id = over_time_member.id_ot where DATE_FORMAT(tanggal,'%Y-%m') = '".$tgl2."' and tanggal <= '".$tgl."' and deleted_at is null and jam_aktual = 0
    group by nik, tanggal) ovr
    left join (select employee_id, cost_center from ympimis.mutation_logs where valid_to is null) cc on cc.employee_id = ovr.nik
    where jam > 0 and cost_center = ".$cc."
    group by tanggal) as overtime right join 
    (select week_date from ympimis.weekly_calendars where DATE_FORMAT(week_date,'%Y-%m') = '".$tgl2."' and week_date <= '".$tgl."') cal on cal.week_date = overtime.tanggal";
    return $this->db->query($q)->result();
}

public function get_budget_g($tanggal, $tanggal2, $cc)
{
    $q = "select (budget / DATE_FORMAT(LAST_DAY('".$tanggal."'),'%d')) budget_total from ympimis.budgets where DATE_FORMAT(period,'%Y-%m') = '".$tanggal2."' and cost_center = ".$cc;
    return $this->db->query($q)->result();
}

public function get_id($tgl)
{
    $this->db->select("id");
    $this->db->where("MONTH(tanggal) = MONTH(STR_TO_DATE('".$tgl."', '%d-%m-%Y'))");
    $this->db->order_by("id", "DESC");

    $query = $this->db->get("over_time", 1);
    return $query->result();
}

public function getJam($shift, $hari)
{
    $this->db->select("*");
    $this->db->where("shift",$shift);
    $this->db->where("hari",$hari);
    $this->db->order_by("id", "ASC");

    $query = $this->db->get("master_lembur");
    return $query->result();
}

public function getJam_act($id)
{
    $this->db->select("jam");
    $this->db->where("id",$id);

    $query = $this->db->get("master_lembur");
    return $query->result();
}

public function getHari($tgl)
{
    $this->db->select("tanggal");
    $this->db->where("tanggal = STR_TO_DATE('".$tgl."','%d-%m-%Y')");

    $query = $this->db->get("kalender");
    return $query->num_rows();
}

public function getGA($tgl)
{
    $this->db->select("tanggal,  
        (SELECT IFNULL(SUM(makan), 0) from over_time_member 
        JOIN over_time ON over_time.id = over_time_member.id_ot
        WHERE shift = 1 AND over_time.tanggal = STR_TO_DATE('".$tgl."', '%d-%m-%Y')
        and deleted_at is null) as makan1 ,

        (SELECT IFNULL(SUM(makan), 0) from over_time_member 
        JOIN over_time ON over_time.id = over_time_member.id_ot
        WHERE shift = 2 AND over_time.tanggal = STR_TO_DATE('".$tgl."', '%d-%m-%Y')
        and deleted_at is null) as makan2 , 

        (SELECT IFNULL(SUM(makan), 0) from over_time_member 
        JOIN over_time ON over_time.id = over_time_member.id_ot
        WHERE shift = 3 AND over_time.tanggal = STR_TO_DATE('".$tgl."', '%d-%m-%Y')
        and deleted_at is null) as makan3");

    $this->db->from("over_time o");
    $this->db->join("over_time_member om","o.id = om.id_ot");
    $this->db->where("o.tanggal = STR_TO_DATE('".$tgl."', '%d-%m-%Y')");
    $this->db->group_by("o.tanggal");

    $query = $this->db->get();
    return $query->result();
}

public function getGA2($tgl)
{
    $this->db->select("tanggal,  
        (SELECT IFNULL(SUM(ext_food), 0) from over_time_member 
        JOIN over_time ON over_time.id = over_time_member.id_ot
        WHERE shift = 1 AND over_time.tanggal = STR_TO_DATE('".$tgl."', '%d-%m-%Y')
        and deleted_at is null) as makan1 ,

        (SELECT IFNULL(SUM(ext_food), 0) from over_time_member 
        JOIN over_time ON over_time.id = over_time_member.id_ot
        WHERE shift = 2 AND over_time.tanggal = STR_TO_DATE('".$tgl."', '%d-%m-%Y')
        and deleted_at is null) as makan2 , 

        (SELECT IFNULL(SUM(ext_food), 0) from over_time_member 
        JOIN over_time ON over_time.id = over_time_member.id_ot
        WHERE shift = 3 AND over_time.tanggal = STR_TO_DATE('".$tgl."', '%d-%m-%Y')
        and deleted_at is null) as makan3");
    $this->db->from("over_time o");
    $this->db->join("over_time_member om","o.id = om.id_ot");
    $this->db->where("o.tanggal = STR_TO_DATE('".$tgl."', '%d-%m-%Y')");
    $this->db->group_by("o.tanggal");

    $query = $this->db->get();
    return $query->result();
}

public function makan1db($tgl,$id)
{
    $this->db->select("*");
    $this->db->from("(
        SELECT a.nik,k.namaKaryawan,dept.nama as dept,dev.nama as dev,sec.nama as sec,sub.nama as sub,gr.nama as gruop1 from over_time_member as a
        JOIN over_time ON over_time.id = a.id_ot
        left join karyawan as k on a.nik = k.nik
        LEFT JOIN posisi as p on a.nik = p.nik
        left join departemen as dept on p.id_dep = dept.id
        left join devisi as dev on p.id_devisi = dev.id
        LEFT JOIN section as sec on p.id_sec = sec.id
        LEFT JOIN sub_section as sub on p.id_sub_sec = sub.id
        LEFT JOIN group1    as gr on p.id_group = gr.id         
        WHERE over_time.shift = ".$id." AND over_time.tanggal = STR_TO_DATE('".$tgl."', '%d-%m-%Y') and a.makan ='1' and deleted_at is null
    ) a");

    $query = $this->db->get();
    return $query->result();
}

public function extrafood2($tgl,$id)
{
    $this->db->select("*");
    $this->db->from("(
        SELECT a.nik,k.namaKaryawan,dept.nama as dept,dev.nama as dev,sec.nama as sec,sub.nama as sub,gr.nama as gruop1 from over_time_member as a
        JOIN over_time ON over_time.id = a.id_ot
        left join karyawan as k on a.nik = k.nik
        LEFT JOIN posisi as p on a.nik = p.nik
        left join departemen as dept on p.id_dep = dept.id
        left join devisi as dev on p.id_devisi = dev.id
        LEFT JOIN section as sec on p.id_sec = sec.id
        LEFT JOIN sub_section as sub on p.id_sub_sec = sub.id
        LEFT JOIN group1    as gr on p.id_group = gr.id         
        WHERE over_time.shift = ".$id." AND over_time.tanggal = STR_TO_DATE('".$tgl."', '%d-%m-%Y') and a.ext_food ='1' and deleted_at is null
    ) a");

    $query = $this->db->get();
    return $query->result();
}

public function multiot2($id)
{
    $q = "select tanggal, over_time.id, section.nama as sec,sub_section.nama as sub,group1.nama as grup, count(over_time_member.nik) as jml_org, sum(over_time_member.jam) as jml_jam, karyawan.costCenter, over_time.keperluan from over_time
    join section on over_time.departemen = section.id
    join sub_section on over_time.section = sub_section.id
    left join group1 on over_time.sub_sec = group1.id
    join over_time_member on over_time_member.id_ot = over_time.id
    left join karyawan on karyawan.nik = over_time_member.nik
    where over_time.id IN (".$id.") and jam_aktual = 0
    group by id";

    $query = $this->db->query($q);
    return $query->result();
}

public function multiot_cc($id,$tgl,$tgl2)
{
    $q = "
    select n.id_cc, master_cc.name, sum(n.act) as act, sum(budget_tot) as tot from 
    (
    select l.id_cc, d.tanggal, COALESCE(act,0) act, l.budget_tot from
    (select id_cc, ROUND((budget_total / DATE_FORMAT(LAST_DAY('".$tgl."'),'%d')),1) budget_tot from cost_center_budget where DATE_FORMAT(period,'%Y-%m') = '".$tgl2."' and id_cc IN (".$id.")) as l
    cross join 
    (
    select tanggal from over
    where DATE_FORMAT(tanggal,'%Y-%m') = '".$tgl2."'
    group by tanggal
    ) as d
    left join 
    (
    select d.tanggal, sum(jam) as act, karyawan.costCenter from
    (select nik, tanggal, if(status = 1, final, jam) jam from over_time left join over_time_member on over_time.id = over_time_member.id_ot where deleted_at is null and jam_aktual = 0 and DATE_FORMAT(tanggal,'%Y-%m') = '".$tgl2."') d
    left join karyawan on karyawan.nik = d.nik
    where karyawan.costCenter IN (".$id.")
    group by tanggal, costCenter
    ) x on x.costCenter = l.id_cc and x.tanggal = d.tanggal
    where d.tanggal <= '".$tgl."'
    ) as n
    left join master_cc on master_cc.id_cc = n.id_cc
    group by id_cc";

    $query = $this->db->query($q);
    return $query->result();
}

public function transdb($id,$tgl,$dari,$sampai)
{
    $this->db->select("*");
    $this->db->from("(
     SELECT a.nik,k.namaKaryawan,dept.nama as dept,dev.nama as dev,sec.nama as sec,sub.nama as sub,gr.nama as gruop1 from over_time_member as a
     JOIN over_time ON over_time.id = a.id_ot
     left join karyawan as k on a.nik = k.nik
     LEFT JOIN posisi as p on a.nik = p.nik
     left join departemen as dept on p.id_dep = dept.id
     left join devisi as dev on p.id_devisi = dev.id
     LEFT JOIN section as sec on p.id_sec = sec.id
     LEFT JOIN sub_section as sub on p.id_sub_sec = sub.id
     LEFT JOIN group1    as gr on p.id_group = gr.id              
     where dari='".$dari."' and sampai='".$sampai."' and transport='".$id."' 
     and over_time.tanggal = STR_TO_DATE('".$tgl."', '%d-%m-%Y') and deleted_at is null )a");
    $query = $this->db->get();
    return $query->result();
}

public function getGA_trans($tgl)
{
    $this->db->select("*");
    $this->db->from("(
        SELECT o.tanggal, om.dari, om.sampai, transport, COUNT(if(transport = 'B' , transport, null)) B, COUNT(if(transport = 'P' , transport, null)) P from over_time o 
        left JOIN over_time_member om ON o.id = om.id_ot
        WHERE o.tanggal = STR_TO_DATE('".$tgl."', '%d-%m-%Y')
        and deleted_at is null
        group by dari, sampai
    ) as b");
    $this->db->where("b.B <> 0");
    $this->db->or_where("b.P <> 0");
    

    $query = $this->db->get();
    return $query->result();
}

public function chart()
{
    $tgl = date("Y-m-d");
    $q = "
    select kode.nama, '03-2019' month_name, IFNULL(SUM(3_jam),0) tiga_jam, IFNULL(14_jam,0) blas_jam, IFNULL(3_14_jam,0) tiga_blas_jam, IFNULL(56_jam,0) manam_jam from kode left join
    (
    select date_format(over_time.tanggal, '%m-%Y') as month_name, over_time_member.nik, karyawan.kode, if(count(over_time_member.nik) > 1, 1, count(over_time_member.nik)) as 3_jam from over_time_member 
    right join over_time on over_time.id = over_time_member.id_ot 
    left join karyawan on karyawan.nik = over_time_member.nik 
    where over_time_member.final > 3 and over_time.hari = 'N' AND MONTH(over_time.tanggal) = MONTH('".$tgl."') AND YEAR(over_time.tanggal) = YEAR('".$tgl."')
    group by date_format(over_time.tanggal, '%m-%Y'), karyawan.kode, over_time_member.nik
    ) as a on a.kode = kode.nama
    left join
    (
    select b.month_name, b.kode, count(b.nik) as 14_jam from 
    (
    select date_format(over_time.tanggal, '%m-%Y') as month_name, week(over_time.tanggal) as week_name, karyawan.nik, karyawan.kode, sum(over_time_member.final) as jam from over_time_member right join over_time on over_time.id = over_time_member.id_ot left join karyawan on karyawan.nik = over_time_member         .nik 
    where over_time.hari = 'N' AND MONTH(over_time.tanggal) = MONTH('".$tgl."') AND YEAR(over_time.tanggal) = YEAR('".$tgl."')
    group by date_format(over_time.tanggal, '%m-%Y'), week(over_time.tanggal), karyawan.kode, karyawan.nik having jam > 14
    ) as b GROUP BY b.kode
    ) as c on c.kode = kode.nama
    left join
    (
    SELECT u.month_name as bulan, u.kode, COUNT(u.nik) as 3_14_jam from 
    (
    SELECT date_format(over_time.tanggal, '%m-%Y') as month_name, over_time_member.nik, karyawan.kode, if(count(over_time_member.nik) > 1, 1, count(over_time_member.nik)) as 3_jam from over_time_member 
    right join over_time on over_time.id = over_time_member.id_ot 
    left join karyawan on karyawan.nik = over_time_member.nik 
    where over_time_member.final > 3 and over_time.hari = 'N'  AND MONTH(over_time.tanggal) = MONTH('".$tgl."') AND YEAR(over_time.tanggal) = YEAR('".$tgl."')
    group by date_format(over_time.tanggal, '%m-%Y'), karyawan.kode, over_time_member.nik
    ) as u

    JOIN

    (select b.month_name, b.kode, count(b.nik) as 14_jam, b.nik as nik from 
    (
    select date_format(over_time.tanggal, '%m-%Y') as month_name, week(over_time.tanggal) as week_name, karyawan.nik, karyawan.kode, sum(                               over_time_member.final) as jam from over_time_member right join over_time on over_time.id = over_time_member.id_ot left join karyawan on                            karyawan.nik = over_time_member.nik 
    where over_time.hari = 'N'  AND MONTH(over_time.tanggal) = MONTH('".$tgl."') AND YEAR(over_time.tanggal) = YEAR('".$tgl."')
    group by date_format(over_time.tanggal, '%m-%Y'), week(over_time.tanggal), karyawan.kode, karyawan.nik having jam > 14
    ) as b 
    GROUP BY b.nik) as l
    on u.nik = l.nik
    GROUP BY u.kode

    ) as z on z.kode = kode.nama
    left join
    (
    select month_name, kode, sum( nik) as 56_jam from (
    select month_name, kode, COUNT(nik) as nik, final from (
    select date_format(over_time.tanggal, '%m-%Y') as month_name, sum(over_time_member.final) as final, k.nik, k.kode from over_time_member
    join over_time on over_time.id = over_time_member.id_ot
    join karyawan k on k.nik = over_time_member.nik
    WHERE MONTH(over_time.tanggal) = MONTH('".$tgl."') AND YEAR(over_time.tanggal) = YEAR('".$tgl."')
    AND over_time.hari = 'N'
    GROUP BY k.kode, k.nik, date_format(over_time.tanggal, '%m-%Y')
    ) as d
    GROUP BY kode, nik
    HAVING final > 56
    ) a

    GROUP BY kode
    ) t on t.kode = kode.nama
    GROUP by kode.nama
    ";
    $query = $this->db->query($q);
    return $query->result();
}


public function chart2($tgl)
{
    $q = "select kd.kode,'".$tgl."' month_name, COALESCE(tiga.tiga_jam,0) as tiga_jam, COALESCE(patblas.emptblas_jam,0) as emptblas_jam, COALESCE(tiga_patblas.tiga_patblas_jam,0) as tiga_patblas_jam, COALESCE(lima_nam.limanam_jam,0) as limanam_jam from
    (select kode from karyawan where kode <> '' GROUP BY kode) kd
    left join
    ( select kode, count(nik) tiga_jam from (
    select d.nik, round(avg(jam),2) as avg, karyawan.kode from
    (select over_time.id, tanggal, nik, sum(final) as jam, status, over_time.hari from over_time
    left join over_time_member on over_time_member.id_ot = over_time.id
    where deleted_at IS NULL and date_format(over_time.tanggal, '%Y-%m') = '".$tgl."' and nik IS NOT NULL and over_time_member.status = 1 and hari = 'N'
    group by nik, tanggal) d 
    left join karyawan on karyawan.nik  = d.nik
    where jam > 3
    group by d.nik
    ) tiga_jam
    group by kode
    ) as tiga on kd.kode = tiga.kode
    left join (
    select kode, count(nik) as emptblas_jam from
    (select s.nik, avg(jam) as avg, kode from
    (select nik, sum(jam) jam, week_name from
    (select over_time.id, tanggal, nik, sum(final) as jam, status, over_time.hari, week(over_time.tanggal) as week_name from over_time
    left join over_time_member on over_time_member.id_ot = over_time.id
    where deleted_at IS NULL and date_format(over_time.tanggal, '%Y-%m') = '".$tgl."' and nik IS NOT NULL and over_time_member.status = 1 and hari = 'N'
    group by nik, tanggal) m
    group by nik, week_name) s
    left join karyawan on karyawan.nik  = s.nik
    where jam > 14
    group by s.nik) l
    group by kode
    ) patblas on kd.kode = patblas.kode
    left join (
    select karyawan.kode, count(c.nik) as tiga_patblas_jam from 
    ( select z.nik, x.avg from 
    ( select d.nik, round(avg(jam),2) as avg from
    (select over_time.id, tanggal, nik, sum(final) as jam, status, over_time.hari from over_time
    left join over_time_member on over_time_member.id_ot = over_time.id
    where deleted_at IS NULL and date_format(over_time.tanggal, '%Y-%m') = '".$tgl."' and nik IS NOT NULL and over_time_member.status = 1 and hari = 'N'
    group by nik, tanggal) d 
    where jam > 3
    group by d.nik ) z

    INNER JOIN

    ( select s.nik, avg(jam) as avg from
    (select nik, sum(jam) jam, week_name from
    (select over_time.id, tanggal, nik, sum(final) as jam, status, over_time.hari, week(over_time.tanggal) as week_name from over_time
    left join over_time_member on over_time_member.id_ot = over_time.id
    where deleted_at IS NULL and date_format(over_time.tanggal, '%Y-%m') = '".$tgl."' and nik IS NOT NULL and over_time_member.status = 1 and hari = 'N'
    group by nik, tanggal) m
    group by nik, week_name) s
    where jam > 14
    group by s.nik) x on z.nik = x.nik
    ) c
    left join karyawan on karyawan.nik = c.nik
    group by karyawan.kode
    ) tiga_patblas on kd.kode = tiga_patblas.kode
    left join 
    (
    select kode, count(nik) as limanam_jam from
    ( select d.nik, sum(jam) as jam, karyawan.kode from
    (select over_time.id, tanggal, nik, sum(final) as jam, status, over_time.hari from over_time
    left join over_time_member on over_time_member.id_ot = over_time.id
    where deleted_at IS NULL and date_format(over_time.tanggal, '%Y-%m') = '".$tgl."' and nik IS NOT NULL and over_time_member.status = 1 and hari = 'N'
    group by nik, tanggal) d
    left join karyawan on karyawan.nik = d.nik
    group by d.nik ) c
    where jam > 56
    group by kode
    ) lima_nam on lima_nam.kode = kd.kode

    ";
    $query = $this->db->query($q);
    return $query->result();
}

public function get_p_data()
{
    $q = "SELECT presensi.tanggal, (DAY(LAST_DAY(presensi.tanggal)) - v.hari) as hari_kerja, z.final as total_lembur, jam_ketidakhadiran, total_keluar, totalMasuk, tot
    from presensi
    JOIN (
    SELECT tanggal, COUNT(tanggal) as hari from kalender GROUP BY DATE_FORMAT(tanggal,'%m-%Y')
    ) as v on v.tanggal = presensi.tanggal
    LEFT JOIN (
    SELECT tanggal, ROUND(SUM(jam)) as final from over 
    WHERE tanggal IS NOT NULL
    GROUP BY DATE_FORMAT(tanggal,'%m-%Y')
    ) z on DATE_FORMAT(z.tanggal,'%m-%Y') = DATE_FORMAT(presensi.tanggal,'%m-%Y')
    LEFT JOIN
    (
    SELECT i.*, (CT+SD+I+A) total_absen, (CT+SD+I+A)*8 jam_ketidakhadiran FROM (
    SELECT tanggal,
    COUNT(if(shift = 'CT' , shift, null)) CT,
    COUNT(if(shift = 'SD' , shift, null)) SD,
    COUNT(if(shift = 'I' , shift, null)) I,
    COUNT(if(shift = 'A' , shift, null)) A
    from presensi
    GROUP BY DATE_FORMAT(tanggal,'%m-%Y')
    ) as i 
    ) u on DATE_FORMAT(u.tanggal,'%m-%Y') = DATE_FORMAT(presensi.tanggal,'%m-%Y')
    LEFT JOIN
    (


    Select c.*, d.totalMasuk from (     
    SELECT count(nik) as total_keluar, tanggalKeluar, (SELECT count(nik) from karyawan) as tot
    from karyawan
    WHERE tanggalKeluar IS NOT NULL
    GROUP BY DATE_FORMAT(tanggalKeluar,'%m-%Y')

    ) c 
    JOIN (
    SELECT COUNT(nik) totalMasuk, tanggalMasuk from karyawan
    GROUP BY DATE_FORMAT(tanggalMasuk,'%m-%Y')
    ) d on DATE_FORMAT(d.tanggalMasuk,'%m-%Y') = DATE_FORMAT(c.tanggalKeluar,'%m-%Y')


    ) as r 
    on DATE_FORMAT(r.tanggalKeluar,'%m-%Y') = DATE_FORMAT(presensi.tanggal,'%m-%Y')

    GROUP BY presensi.tanggal;
    ";

    // $this->db->select("STR_TO_DATE('".$bulan."', '%d-%m-%Y') - INTERVAL 1 MONTH as bulan, count(nik) as tot");
    // $this->db->from('karyawan');
    // $this->db->where("nik NOT IN (
    //     SELECT nik from karyawan
    //     WHERE tanggalKeluar >= '2019-01-01'
    //     AND tanggalKeluar < '".$bulan."'
    // )");
    // $this->db->where("tanggalMasuk < '".$bulan."'");

    $query = $this->db->query($q);
    return $query->result();
}

public function coba1($bulan)
{
    $this->db->select("STR_TO_DATE('".$bulan."', '%Y-%m-%d') - INTERVAL 1 MONTH as bulan, count(nik) as tot");
    $this->db->from('karyawan');
    $this->db->where("nik NOT IN (
        SELECT nik from karyawan
        WHERE tanggalKeluar >= '2019-01-01'
        AND tanggalKeluar < '".$bulan."'
    )");
    $this->db->where("tanggalMasuk < '".$bulan."'");

    $query = $this->db->get();
    return $query->result();
}

public function tes1()
{
    $this->db->select("periode,aktif,`non-aktif` as non_aktif, total_karyawan");
    $this->db->from('karyawan_period');

    $query = $this->db->get();
    return $query->result();
}

public function chart_dep2($tgl)
{
    $q = "select d.tanggal, ROUND(sum(total_jam),1) as tot, departemen.nama as namaDep from (
    select over.tanggal, sum(over.jam) as total_jam, nik from over 
    WHERE DATE_FORMAT(over.tanggal,'%m-%Y') = DATE_FORMAT('".$tgl."','%m-%Y')
    GROUP BY nik
    ) as d
    join posisi on d.nik = posisi.nik
    join departemen on posisi.id_dep = departemen.id
    GROUP BY posisi.id_dep
    ORDER BY tot DESC";
    $query = $this->db->query($q);
    return $query->result();
}


public function nik_by_cc($sec)
{
    $q = "select nik, namaKaryawan, 'Max-OT' target from karyawan
    where costCenter = ".$sec."
    ORDER BY nik ASC";
    $query = $this->db->query($q);
    return $query->result();
}

public function jam_by_nik($nik, $tahun)
{
    $q = "select ab.tanggal, ab.nik, ab.tot, budget from (
    select tanggal, nik, ROUND(sum(jam), 1) as tot from over
    where nik = '".$nik."' and DATE_FORMAT(tanggal, '%Y') = '".$tahun."' 
    group by DATE_FORMAT(tanggal, '%M %Y')
    ) as ab

    join karyawan on karyawan.nik = ab.nik
    join cost_center_budget on cost_center_budget.id_cc = karyawan.costCenter
    group by DATE_FORMAT(ab.tanggal, '%M %Y')";
    $query = $this->db->query($q);
    return $query->result();
}

public function manajemen_section($fiskal, $costCenter)
{
    $q = "select main.fiskal, main.tanggal, main.nik, main.namaKaryawan, concat(main.nik, ' ', main.namaKaryawan) as nama, IFNULL(jam ,0) jam from
    (
    select * from 
    ( Select * from kalender_fy
    where fiskal = '".$fiskal."' 
    group by DATE_FORMAT(tanggal, '%m-%Y')
    ) as d
    cross join 
    ( select karyawan.nik, namaKaryawan from karyawan where costCenter = '".$costCenter."') as m
    ) main left join 
    (
    select tanggal, nik, sum(jam) jam from over
    group by DATE_FORMAT(tanggal, '%m-%Y'), nik
    ) as u on u.nik = main.nik and DATE_FORMAT(u.tanggal, '%m-%Y') = DATE_FORMAT(main.tanggal, '%m-%Y')
    order by main.tanggal
    ";

    $query = $this->db->query($q);
    return $query->result();
}

public function getTarget($fiskal, $cc)
{
    $q = "SELECT kalender_fy.tanggal , '0' nik,'target' as namaKaryawan, 'target' as nama, IFNULL(budget,0) jam, '".$fiskal."' as fiskal from kalender_fy 
    left join (
    select period ,budget, id_cc from cost_center_budget where id_cc = '".$cc."'
    ) as d on d.period = kalender_fy.tanggal
    where fiskal = '".$fiskal."'
    group by DATE_FORMAT(kalender_fy.tanggal, '%m-%Y')
    ORDER BY tanggal asc";

    $query = $this->db->query($q);
    return $query->result();
}

public function get_chart_dep()
{
    $q = "select tanggal, ROUND(sum(jam),1) as jam, IFNULL(k.costCenter,0) as cc from over
    left join karyawan k on k.nik = over.nik
    WHERE DATE_FORMAT(tanggal,'%m-%Y') = '02-2019'
    GROUP BY k.costCenter, tanggal";
    $query = $this->db->query($q);
    return $query->result();
}

public function get_tgl()
{
    $q = "select tanggal from over
    where DATE_FORMAT(tanggal, '%m-%Y') = '02-2019'
    group by tanggal";
    $query = $this->db->query($q);
    return $query->result();
}

public function get_cc2()
{
    $q = "select id_cc from cost_center_budget
    group by id_cc";
    $query = $this->db->query($q);
    return $query->result();
}

public function get_o_data($tgl)
{
    $q = "select IFNULL(costCenter,0) as cc, tanggal, sum(jam) as jam from over
    left join karyawan k on k.nik = over.nik
    where DATE_FORMAT(tanggal, '%m-%Y') = '02-2019'
    and tanggal = '".$tgl."'
    group by costCenter";
    $query = $this->db->query($q);
    return $query->result();
}


  // ------------- LAMA ---------------
public function get_cc5($tgl,$cc)
{
    if ($cc != "0"){
        $where = "where c.departemen ='".$cc."'";
    }
    else{
        $where = "";
    }

    $q = "  
    select c.*, IFNULL(total_jam,0) as jam from
    (
    select b.tanggal, master_cc.departemen from (select tanggal from kalender_fy where DATE_FORMAT(tanggal, '%m-%Y') = '".$tgl."') b
    cross join master_cc
    group by b.tanggal, departemen
    ) as c
    left join 
    (
    select '".$tgl."' as mon, tanggal, departemen, round(sum(jam),1) as total_jam from over
    left join karyawan on karyawan.nik = over.nik
    left join master_cc on master_cc.id_cc = karyawan.costCenter
    where DATE_FORMAT(tanggal, '%m-%Y') = '".$tgl."'
    GROUP BY mon, tanggal, departemen
    ) as d on d.departemen = c.departemen and d.tanggal = c.tanggal ".$where."
    order by c.tanggal, c.departemen asc";
    $query = $this->db->query($q);
    return $query->result();
}

public function get_cc5_hari($tgl,$tgl2,$cc,$fiskal)
{
    if ($cc != "0"){
        $where = "where c.departemen ='".$cc."'";
        $where1 = "where departemen ='".$cc."'";
    }
    else{
        $where = "";
        $where1 = "";
    }

    $q = "  
    select c.mon, c.tanggal, c.departemen, coalesce(d.total_jam, 0) as jam, IFNULL(tot_budget,0) tot_budget from
    (
    select a.mon, a.tanggal, b.departemen from
    (
    select distinct date_format(over.tanggal, '%m-%Y') as mon, over.tanggal 
    from over 
    where date_format(over.tanggal, '%m-%Y') = '".$tgl."'
    ) as a
    left join
    (
    select distinct '03-2019' as mon, departemen from master_cc
    ) as b on b.mon = date_format(a.tanggal, '%m-%Y')
    ) as c
    left join
    (
    select '03-2019' as mon, tanggal, departemen, round(sum(jam),1) as total_jam from over
    left join karyawan on karyawan.nik = over.nik
    left join master_cc on master_cc.id_cc = karyawan.costCenter
    where DATE_FORMAT(tanggal, '%m-%Y') = '".$tgl."'
    GROUP BY mon, tanggal, departemen
    ) as d on c.mon = d.mon and c.departemen = d.departemen and c.tanggal = d.tanggal
    left join (
    select c.tanggal , (d.tot_karyawan * c.budget_jam) as tot_budget, master_cc.departemen from (
    select mon, costCenter, count(if(if(date_format(a.tanggalMasuk, '%Y-%m') < mon, 1, 0 ) - if(date_format(a.tanggalKeluar, '%Y-%m') < mon, 1, 0 ) = 0, null, 1)) as tot_karyawan from
    (
    select distinct fiskal, date_format(tanggal, '%Y-%m') as mon
    from kalender_fy
    ) as b
    join
    (
    select '".$fiskal."' as fy, karyawan.kode, tanggalKeluar, tanggalMasuk, nik, costCenter
    from karyawan
    ) as a
    on a.fy = b.fiskal
    group by mon, costCenter
    having mon = '".$tgl2."'
    ) as d
    left join master_cc on master_cc.id_cc = d.costCenter
    left join (
    select tanggal, dep, sum(jam) as budget_jam from budget_harian
    where DATE_FORMAT(tanggal,'%m-%Y') = '".$tgl."'
    group by tanggal,dep
    ) c on c.dep = master_cc.departemen
    group by tanggal,dep
    ) as m on m.tanggal = c.tanggal and c.departemen = m.departemen
    order by c.tanggal asc";
    $query = $this->db->query($q);
    return $query->result();
}

public function get_budget_hari($tgl)
{
    $q = "select tanggal, dep, sum(jam) as budget_jam from budget_harian
    where DATE_FORMAT(tanggal,'%m-%Y') = '".$tgl."'
    group by tanggal";
    $query = $this->db->query($q);
    return $query->result();
}


// public function get_budget($tgl, $cc, $fiskal)
// {
//     if ($cc != "0"){
//         $where = "where departemen ='".$cc."'";
//     }
//     else{
//         $where = "";
//     }

//     $q = "
//     select period, departemen, sum(budget) as budget from 
//     (
//     select cost_center_budget.period, master_cc.departemen, sum((cost_center_budget.budget*a.tot_karyawan)) as budget from cost_center_budget

//     left join
//     (
//     select mon, costCenter, count(if(if(date_format(a.tanggalMasuk, '%Y-%m') < mon, 1, 0 ) - if(date_format(a.tanggalKeluar, '%Y-%m') < mon, 1, 0 ) = 0, null, 1)) as tot_karyawan from
//     (
//     select distinct fiskal, date_format(tanggal, '%Y-%m') as mon
//     from kalender_fy
//     ) as b
//     join
//     (
//     select '".$fiskal."' as fy, karyawan.kode, tanggalKeluar, tanggalMasuk, nik, costCenter
//     from karyawan
//     ) as a
//     on a.fy = b.fiskal
//     group by mon, costCenter
//     having mon = '".$tgl."'
//     ) as a on a.costCenter = cost_center_budget.id_cc
//     left join master_cc on master_cc.id_cc = a.costCenter
//     where date_format(cost_center_budget.period, '%Y-%m') = '".$tgl."'
//     group by departemen
// ) as m ".$where."";
//  // WHERE departemen = "LOG"

// $query = $this->db->query($q);
// return $query->result();

// }

public function get_budget($tgl, $tgl2, $cc)
{
    if ($cc == '0') {
        $where = "";
    } else {
        $where = 'where departemen = "'.$cc.'"';
    }

    $q = "
    select tanggal, departemen, act, sum(budget_tot) as budget_tot from 
    (
    select DATE_FORMAT( d.period, '%d' ) as tanggal, round(sum(d.budget_total) / DATE_FORMAT( LAST_DAY( '".$tgl."' ), '%d' ),1) as budget_tot, master_cc.departemen, '0' as act from
    (select id_cc, period, budget_total from cost_center_budget where DATE_FORMAT(period,'%Y-%m') = '".$tgl2."') d left join master_cc on master_cc.id_cc = d.id_cc
    ".$where."
    group by departemen
) d";

$query = $this->db->query($q);
return $query->result();
}

public function get_data_ot_month($fiskal)
{
    $q = "
    select c.mon, (budget*jml_kar) budget_tot, (act*jml_kar) act_tot, (fr*jml_kar) forecast_tot, c.bagian from
    (
    SELECT mon, SUM(budget) as budget , SUM(aktual) act, SUM(forecast) as fr, bagian from
    (
    SELECT date_format(period, '%Y-%m') as mon, master_cc.id_cc, budget, aktual, forecast, 
    IF(costCenter LIKE '1%' ,'PL',
    IF(costCenter LIKE '2%','OFFICE','PRODUKSI')) as bagian from master_cc
    LEFT JOIN cost_center_budget ON master_cc.id_cc = cost_center_budget.id_cc
    LEFT join karyawan on karyawan.costCenter = cost_center_budget.id_cc
    GROUP BY date_format(period, '%Y-%m'), id_cc
    ) as b
    group by bagian, mon
    ) as c
    left join
    (
    select mon, bagian, count(if(if(date_format(a.tanggalMasuk, '%Y-%m') < mon, 1, 0 )-if(date_format(a.tanggalKeluar, '%Y-%m') < mon, 1, 0 ) = 0, null, 1)) as jml_kar from
    (
    select distinct fiskal, date_format(tanggal, '%Y-%m') as mon
    from kalender_fy where fiskal = '".$fiskal."'
    ) as b
    left join
    (
    select '".$fiskal."' as fy, karyawan.kode, tanggalKeluar, tanggalMasuk, nik, 
    IF(costCenter LIKE '1%' ,'PL',
    IF(costCenter LIKE '2%','OFFICE','PRODUKSI')) as bagian
    from karyawan
    ) as a
    on a.fy = b.fiskal
    group by mon, bagian
    ) as d on c.mon = d.mon and c.bagian = d.bagian

    group by mon, c.bagian

    ";
    $query = $this->db->query($q);
    return $query->result();
}

public function get_jam_by_nik($nik,$tgl)
{
    $this->db->select('date_format(tanggal, "%d-%m-%Y") as tgl, round(sum(jam),1) as jam, over.nik, namaKaryawan');
    $this->db->from('over');
    $this->db->join('karyawan','karyawan.nik = over.nik');
    $this->db->where('over.nik',$nik);
    $this->db->where('jam <> 0');
    $this->db->where('date_format(tanggal, "%Y") = "'.$tgl.'"');
    $this->db->group_by('DATE_FORMAT(tanggal,"%m-%Y")');
    $q =  $this->db->get();
    return $q->result();
}

public function exportdata($id)
{
    $this->db->select("id,id_ot,nik,dari,sampai, jam, transport, makan,ext_food,jam_aktual, final, satuan");
    $this->db->from("over_time_member");
    $this->db->where("id_ot = '".$id."'");

    $query = $this->db->get();
    return $query->result();
}

public function exportdatahr($id)
{
    $this->db->select("*");
    $this->db->from("(select o.*, karyawan.namaKaryawan, p.masuk, p.keluar, section.nama as section, ov.jam as aktual, satuan from
        (select over_time.id as id_ot, over_time.tanggal, over_time_member.nik, dari, sampai, sum(jam) as jam, hari, sum(final) as final_jam, over_time_member.status as status_final from over_time left join over_time_member on over_time.id = over_time_member.id_ot
        where tanggal = '".$id."' and deleted_at is null and over_time_member.jam_aktual = 0
        group by nik) o
        left join (select nik, jam from over where tanggal = '".$id."') ov on ov.nik = o.nik
        left join karyawan on karyawan.nik = o.nik
        left join (select nik, masuk, keluar from presensi where tanggal = '".$id."') p on p.nik = o.nik
        left join posisi on posisi.nik = o.nik
        left join section on section.id = posisi.id_sec
        left join satuan_lembur on satuan_lembur.jam = o.final_jam and satuan_lembur.hari = o.hari
        order by o.nik asc
    ) a");

    $query = $this->db->get();
    return $query->result();
}

public function get_presentase($tgl2, $bagian)
{
    if ($bagian == "0") {
        $where = "";
    } else {
        $where = "where master_cc.departemen = '".$bagian."'";
    }

    $q = "select semua.act, semua.budget, semua.kode from
    (select COALESCE(sum(d.act) ,0) act, COALESCE(sum(d.budget) ,0) budget, d.kode from 
    ( select COALESCE(sum(jam),0) as act, master_cc.kode, master_cc.id_cc, master_cc.departemen, round(sum(budget_total),2) as budget from 
    ( select sum(ovr.jam) as jam, departemen, master_cc.kode, master_cc.id_cc from 
    ( select GROUP_CONCAT(over_time.id) id, over_time.tanggal, over_time_member.nik, sum(if(status = 0,over_time_member.jam,over_time_member.final)) jam, over_time_member.status from over_time left join over_time_member on over_time.id = over_time_member.id_ot where DATE_FORMAT(tanggal,'%Y-%m') = '".$tgl2."' and deleted_at IS NULL and nik IS NOT NULL and jam_aktual = 0
    group by nik, tanggal) ovr 
    left join karyawan on karyawan.nik = ovr.nik
    left join master_cc on master_cc.id_cc = karyawan.costCenter
    group by id_cc ) as m
    right JOIN master_cc on master_cc.id_cc = m.id_cc
    left join 
    (select id_cc, budget_total from cost_center_budget where DATE_FORMAT(period,'%Y-%m') = '".$tgl2."') bgd 
    on bgd.id_cc = master_cc.id_cc
    ".$where."
    group by master_cc.departemen
    ) as d
    group by d.kode
    union all
    select 0 as act, 0 as budget, kode from master_cc
    group by kode) as semua
    group by kode
    ";
    $query = $this->db->query($q);

    return $query->result();
}

public function hapus($id)
{
    $nik = $this->session->userdata('nikLogin');
    $this->db->set('deleted_at', date('Y-m-d'));
    $this->db->set('nik_delete', $nik);
    $this->db->where('id', $id);
    $this->db->update('over_time');
}


public function change_over($nik, $tgl, $jam)
{
    $qcekTgl = "SELECT * FROM kalender where tanggal = '".$tgl."'";
    $cekTgl = $this->db->query($qcekTgl);

    if($cekTgl->num_rows() > 0) {
        $hari = 'L';
    }
    else {
        $hari = 'N';
    }

    $this->db->query('CALL masukDataOverSPLAktual ("'.$nik.'","'.$tgl.'","'.$hari.'","'.$jam.'")');
}

public function change_over_all($nik, $tgl, $jam)
{
    $this->db->query('update over set jam = '.$jam.', status_final = 1 where tanggal = "'.$tgl.'" and nik = "'.$nik.'" and status_final = 0');
}

public function get_break($hari, $dari, $sampai, $shift)
{
    $q = "SELECT IFNULL(sum(TIME_TO_SEC(duration)),'0') as istirahat from breaktime 
    where day = '".$hari."' AND shift= '".$shift."'  
    and breaktime.start >= '".$dari."' and breaktime.end <= '".$sampai."'";
    $query = $this->db->query($q);

    return $query->result();

}

public function get_over_time($tgl, $tgl2, $cc)
{
    if ($cc == '0') {
        $where = '';
    } else {
        $where = "where departemen = '".$cc."'";
    }
    
    $q = "SELECT
    tanggal,
    departemen,
    sum(act) as act,
    sum(budget_tot) as budget_tot
    FROM
    (
    SELECT
    l.id_cc,
    l.NAME,
    l.departemen,
    DATE_FORMAT( d.tanggal, '%d' ) tanggal,
    COALESCE ( act, 0 ) act,
    l.budget_tot AS budget_tot 
    FROM
    (
    SELECT
    cost_center_budget.id_cc,
    ROUND( ( budget_total / DATE_FORMAT( LAST_DAY( '".$tgl."' ), '%d' ) ), 1 ) budget_tot,
    master_cc.NAME,
    master_cc.departemen 
    FROM
    cost_center_budget
    LEFT JOIN master_cc ON master_cc.id_cc = cost_center_budget.id_cc 
    WHERE
    DATE_FORMAT( period, '%Y-%m' ) = '".$tgl2."' 
    ) AS l
    CROSS JOIN ( SELECT tanggal FROM kalender_fy WHERE DATE_FORMAT( tanggal, '%Y-%m' ) = '".$tgl2."' ) AS d
    LEFT JOIN (
    SELECT
    d.tanggal,
    sum( jam ) AS act,
    karyawan.costCenter 
    FROM
    (
    SELECT
    over_time_member.nik,
    over_time.tanggal,
    sum(if(status = 0,over_time_member.jam,over_time_member.final)) AS jam 
    FROM
    over_time
    LEFT JOIN over_time_member ON over_time.id = over_time_member.id_ot 
    WHERE
    DATE_FORMAT( over_time.tanggal, '%Y-%m' ) = '".$tgl2."' 
    AND over_time_member.nik IS NOT NULL 
    AND over_time.deleted_at IS NULL 
    and jam_aktual = 0
    GROUP BY
    over_time_member.nik,
    over_time.tanggal 
    ) d
    LEFT JOIN karyawan ON karyawan.nik = d.nik 
    GROUP BY
    tanggal,
    costCenter 
    ) x ON x.costCenter = l.id_cc 
    AND x.tanggal = d.tanggal 
    ) as p 
    ".$where." group by departemen,tanggal";

    $query = $this->db->query($q);

    return $query->result();
}

public function update_cuti($nik, $tgl)
{
    $ympimis = $this->load->database('ympimisdev', TRUE);
    $query = 'UPDATE leaves SET leave_left = leave_left - 1 where employee_id = "'.$nik.'" and valid_from < "'.$tgl.'" and valid_to > "'.$tgl.'"';
    $ympimis->query($query);    
}
public function get_cuti()
{
    $ympimis = $this->load->database('ympimisdev', TRUE);
    $query = $ympimis->select('absence_code')
    ->where('deduction','1')
    ->get('absence_categories');

    return $query->result();
}

    // public function ot_summary_m()
    // {
    //     $q = "select mon,p.id_cc, name, karyawan, aktual, round((aktual/karyawan),2) as avg, coalesce(min_final, 0) as min_final, coalesce(max_final, 0) as max_final from (

    //     select mon, master_cc.id_cc, kode, name, sum(tot_karyawan) as karyawan from (
    //         select mon, costCenter, count(if(if(date_format(a.tanggalMasuk, '%Y-%m') < mon, 1, 0 ) - if(date_format(a.      tanggalKeluar, '%Y-%m') < mon, 1, 0 ) = 0, null, 1)) as tot_karyawan from
    //         (
    //         select distinct fiskal, date_format(tanggal, '%Y-%m') as mon
    //         from kalender_fy
    //         ) as b
    //         join
    //         (
    //             select '195' as fy, karyawan.kode, tanggalKeluar, tanggalMasuk, nik, costCenter
    //             from karyawan
    //         ) as a
    //         on a.fy = b.fiskal
    //         group by mon, costCenter
    //         having mon = '2019-03'
    // ) as b 
    // left join master_cc on master_cc.id_cc = b.costCenter
    // GROUP BY mon, kode, master_cc.id_cc
    //     ) as p

    //     left join (
    //     select id_cc, aktual, budget from cost_center_budget where period = '2019-03-01'
    //     ) as n on p.id_cc = n.id_cc

    //     left join (
    //     select costCenter, min(final) as min_final, max(final) as max_final from over_time_member om
    //     left join over_time o on om.id_ot = o.id
    //     left join karyawan k on k.nik = om.nik
    //     where DATE_FORMAT(tanggal,'%Y-%m') = '2019-03' and final > 0
    //     group by costCenter
    //     ) as m on m.costCenter = n.id_cc

    //     ";
    //     $query = $this->db->query($q);

    //     return $query->result();
    // }

}
?>

