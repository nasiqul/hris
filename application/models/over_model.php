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

    public function get_data_ot()
    {
    	$this->_get_datatables_query();
    	if($_GET['length'] != -1)
    		$this->db->limit($_GET['length'], $_GET['start']);
    	$query = $this->db->get();
    	return $query->result();
    }

    private function _get_datatables_query()
    {
    	$this->db->select("tanggal, nik,nama,masuk,keluar,id,shift,status,jam,final,id_jam,jam_lembur, IFNULL(aktual, 0) as aktual, IFNULL(diff, 0) as diff, IFNULL(final2, 0) as final2");
    	$this->db->from("
            (select tanggal,c.nik1 as nik, d.namaKaryawan as nama, masuk, keluar, id, shift, c.status, jam, final, c.id_jam, c.jam_lembur,
            (IF(hari = 'L',
            floor((TIME_TO_SEC(TIMEDIFF(concat('2010-08-20 ',keluar), concat('2010-08-20 ',masuk)))) / 60 / 60 * 2) / 2, 
            IF(shift = 1,
            floor((

            IF(c.jam_lembur = 'Awal',0,(TIME_TO_SEC(TIMEDIFF(concat('2010-08-20 ',keluar), '2010-08-20 16:00:00'))))
            + 

            IF(c.jam_lembur = 'Awal',(TIME_TO_SEC(TIMEDIFF('2010-08-20 07:00:00' , concat('2010-08-20 ',masuk)))),0)
            )/ 60 / 60 * 2) / 2
            , IF(shift = 2,
            floor((                                                                      IF(c.jam_lembur = 'Awal',0,(TIME_TO_SEC(TIMEDIFF(concat('2010-08-20 ',keluar), '2010-08-20 00:00:00'))))

            + 

            IF(c.jam_lembur = 'Awal',(TIME_TO_SEC(TIMEDIFF('2010-08-20 16:00:00' , concat('2010-08-20 ',masuk)))),0)

            )/ 60 / 60 * 2) / 2
            , IF(shift = 3,
            floor((                                                                  IF(c.jam_lembur = 'Awal',0,(TIME_TO_SEC(TIMEDIFF(concat('2010-08-20 ',keluar), '2010-08-20 07:00:00'))))

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
    	$this->db->where(1,1);


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

    public function get_dep()
    {
    	$query = $this->db->get('departemen');
    	return $query->result();
    }

    public function save_master($no_doc, $tgl, $dep, $sec, $kep, $cat, $hari)
    {
    	$data = array(
    		'id' => $no_doc,
    		'tanggal' => $tgl,
    		'departemen' => $dep,
    		'section' => $sec,
    		'keperluan' => $kep,
    		'catatan' => $cat,
            'hari' => $hari,
            'status' => 0
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

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from('over_time');
        return $this->db->count_all_results();
    }


    public function get_over_by_id($id)
    {
        $this->db->select("o.id as id_over, tanggal, d.nama as departemen, s.nama as section, keperluan, catatan, hari");
        $this->db->from('over_time o');
        $this->db->join('departemen d','o.departemen = d.id','left');
        $this->db->join('section s','o.section = s.id','left');
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

    public function cek_id($nik, $dep)
    {
        $this->db->select("k.nik");
        $this->db->from('karyawan k');
        $this->db->join("posisi p","p.nik = k.nik");
        $this->db->join("departemen d","p.id_dep = d.id");
        $this->db->where("k.nik",$nik);
        $this->db->where("p.id_dep",$dep);
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

public function tambah_aktual($id_cc,$val,$tgl)
{
    $query2 = "UPDATE `cost_center_budget` SET `aktual` = `aktual` + ".(float)$val." WHERE `id_cc` = '".$id_cc."' AND MONTH(period) = MONTH(STR_TO_DATE('".$tgl."', '%d-%m-%Y')) 
    AND YEAR(period) = YEAR(STR_TO_DATE('".$tgl."', '%d-%m-%Y'))";
    $this->db->query($query2);
}

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

public function get_data_chart($tgl,$cc,$target)
{
    $q = "select tanggal, jam,".$target." as target from (
    SELECT tanggal, costCenter, SUM(final) as jam from over_time o
    JOIN over_time_member om ON o.id = om.id_ot
    JOIN karyawan k ON k.nik = om.nik
    WHERE costCenter = ".$cc." AND
    MONTH(tanggal) = MONTH(STR_TO_DATE('".$tgl."', '%Y-%m-%d'))
    GROUP BY tanggal
    )a
    where a.jam > 0";
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
        JOIN master_lembur ON over_time_member.id_jam = master_lembur.id 
        WHERE shift = 1 AND over_time.tanggal = STR_TO_DATE('".$tgl."', '%d-%m-%Y')) as makan1 ,

        (SELECT IFNULL(SUM(makan), 0) from over_time_member 
        JOIN over_time ON over_time.id = over_time_member.id_ot
        JOIN master_lembur ON over_time_member.id_jam = master_lembur.id 
        WHERE shift = 2 AND over_time.tanggal = STR_TO_DATE('".$tgl."', '%d-%m-%Y')) as makan2 , 

        (SELECT IFNULL(SUM(makan), 0) from over_time_member 
        JOIN over_time ON over_time.id = over_time_member.id_ot
        JOIN master_lembur ON over_time_member.id_jam = master_lembur.id 
        WHERE shift = 3 AND over_time.tanggal = STR_TO_DATE('".$tgl."', '%d-%m-%Y')) as makan3");
    $this->db->from("over_time o");
    $this->db->join("over_time_member om","o.id = om.id_ot");
    $this->db->where("o.tanggal = STR_TO_DATE('".$tgl."', '%d-%m-%Y')");
    $this->db->group_by("o.tanggal");

    $query = $this->db->get();
    return $query->result();
}

public function getGA_trans($tgl)
{
    $this->db->select("*");
    $this->db->from("(SELECT o.tanggal, ml.jam_awal, ml.jam_akhir, COUNT(if(transport = 'B' , transport, null)) B, COUNT(if(transport = 'P' , transport, null)) P from over_time o 
        JOIN over_time_member om ON o.id = om.id_ot
        JOIN master_lembur ml ON ml.id = om.id_jam
        WHERE o.tanggal =  STR_TO_DATE('".$tgl."', '%d-%m-%Y')
        GROUP BY ml.jam_awal, ml.jam_akhir) a");
    $this->db->where("a.B <> 0");
    $this->db->or_where("a.P <> 0");
    

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
        select date_format(over_time.tanggal, '%m-%Y') as month_name, week(over_time.tanggal) as week_name, karyawan.nik, karyawan.kode, sum(over_time_member.      final) as jam from over_time_member right join over_time on over_time.id = over_time_member.id_ot left join karyawan on karyawan.nik = over_time_member         .nik 
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
    $q = "SELECT kode.nama, '".$tgl."' month_name, IFNULL(SUM(3_jam),0) tiga_jam, IFNULL(14_jam,0) blas_jam, IFNULL(3_14_jam,0) tiga_blas_jam, IFNULL(56_jam,0) manam_jam from kode left join
(
    select date_format(over.tanggal, '%m-%Y') as month_name, over.nik, karyawan.kode, if(count(over.nik) > 1, 1, count(over.nik)) as 3_jam from over 
    left join karyawan on karyawan.nik = over.nik 
    where over.jam > 3 and over.status = 'N' AND MONTH(over.tanggal) = MONTH('".$tgl."') AND YEAR(over.tanggal) = YEAR('".$tgl."')
    group by date_format(over.tanggal, '%m-%Y'), karyawan.kode, over.nik
) as a on a.kode = kode.nama
left join
(
        select m.month_name, m.kode, sum(14_jam) as 14_jam from 
    (
        select b.month_name, b.kode, if(count(b.nik) > 1, 1, count(b.nik)) as 14_jam, b.nik from 
        (
            select date_format(over.tanggal, '%m-%Y') as month_name, week(over.tanggal) as week_name, karyawan.nik, karyawan.kode, sum(over.jam) as jam from over
            left join karyawan on karyawan.nik = over.nik 
            where over.status = 'N' AND MONTH(over.tanggal) = MONTH('".$tgl."') AND YEAR(over.tanggal) = YEAR('".$tgl."')
            group by date_format(over.tanggal, '%m-%Y'), week(over.tanggal), karyawan.nik, karyawan.kode
            having jam > 14
        ) as b GROUP BY b.nik
    ) as m GROUP BY m.kode
) as c on c.kode = kode.nama
left join
(
        SELECT u.month_name as bulan, u.kode, COUNT(u.nik) as 3_14_jam from 
        (
            select date_format(over.tanggal, '%m-%Y') as month_name, over.nik, karyawan.kode, if(count(over.nik) > 1, 1, count(over.nik)) as 3_jam from over 
            left join karyawan on karyawan.nik = over.nik 
            where over.jam > 3 and over.status = 'N' AND MONTH(over.tanggal) = MONTH('".$tgl."') AND YEAR(over.tanggal) = YEAR('".$tgl."')
            group by date_format(over.tanggal, '%m-%Y'), karyawan.kode, over.nik
        ) as u
            
            JOIN
            
            (
                select b.month_name, b.kode, b.nik from 
                (
                    select date_format(over.tanggal, '%m-%Y') as month_name, week(over.tanggal) as week_name, karyawan.nik, karyawan.kode, sum(over.jam) as jam from over
                    left join karyawan on karyawan.nik = over.nik 
                    where over.status = 'N' AND MONTH(over.tanggal) = MONTH('".$tgl."') AND YEAR(over.tanggal) = YEAR('".$tgl."')
                    group by date_format(over.tanggal, '%m-%Y'), week(over.tanggal), karyawan.nik, karyawan.kode
                    having jam > 14
                ) as b GROUP BY b.nik
            ) as l
            on u.nik = l.nik
            GROUP BY u.kode

) as z on z.kode = kode.nama
left join
(
    select month_name, kode, sum(nik) as 56_jam from (
        select month_name, kode, COUNT(nik) as nik, jam from (
        select date_format(over.tanggal, '%m-%Y') as month_name, sum(over.jam) as jam, k.nik, k.kode from over
        join karyawan k on k.nik = over.nik
        WHERE MONTH(over.tanggal) = MONTH('".$tgl."') AND YEAR(over.tanggal) = YEAR('".$tgl."') AND over.status = 'N'
        GROUP BY k.kode, k.nik, date_format(over.tanggal, '%m-%Y')
        ) as d
        GROUP BY kode, nik
        HAVING jam > 56
) a

GROUP BY kode

) t on t.kode = kode.nama
GROUP by kode.nama


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
    $query = $this->db->query($q);
    return $query->result();
}
}
?>
