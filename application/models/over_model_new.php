<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Over_model_new extends CI_Model {

	var $column_order = array('id','m.tanggal','m.nik','namaKaryawan'); //set column field database for datatable orderable
    var $column_search = array('id','DATE_FORMAT(m.tanggal, "%d-%m-%Y")','m.nik','namaKaryawan'); //set column field database for datatable searchable 
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
        $this->db->select("m.*,  p.masuk, p.keluar, (m.act - m.jam_plan) as diff, IF(m.act < m.jam_plan, m.act, m.jam_plan) as final, namaKaryawan");
        $this->db->from("(
            select o.*, over_time_member.nik, over_time_member.jam as jam_plan, IFNULL(over.jam,0) as act, over.hari from (select id, tanggal from over_time where tanggal = '".$tgl."' and deleted_at IS NULL) as o
            join over_time_member on o.id = over_time_member.id_ot
            left join (select tanggal, nik, jam, status as hari from over where tanggal = '".$tgl."') over on over.nik = over_time_member.nik
            where status = 0
            GROUP BY nik, id_ot

        ) m");
        $this->db->join('karyawan','karyawan.nik = m.nik', 'left');
        $this->db->join("(select masuk,keluar, nik from presensi where tanggal = '".$tgl."') p",'p.nik = m.nik');


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

    public function tabel_konfirmasi($tgl)
    {
        $q = "select *, COUNT(nik) as jml_nik from
        (
        select IFNULL(d.id,'-') as id, over.tanggal, over.nik, over.jam as aktual, IFNULL(d.jam,0) as jam_plan, IFNULL((over.jam - d.jam),0) as diff, d.status from over
        left join (
        select over_time_member.id_ot, over_time.id, tanggal, nik, jam, final, over_time_member.status from over_time
        join over_time_member on over_time_member.id_ot = over_time.id
        where deleted_at IS NULL
        ) d on d.tanggal = over.tanggal and d.nik = over.nik
        where over.tanggal = '".$tgl."'
        group by over.nik, id_ot
        ) as m 
        where diff = 0
        GROUP BY nik
        ";

        $query = $this->db->query($q);
        return $query->result();
    }

    public function ot_hr($id)
    {
        $q = "select over_time.id, over_time_member.id as id_user, DATE_FORMAT(over_time.tanggal,'%d-%m-%Y') as tanggal, over_time.departemen, over_time.section, over_time.sub_sec, over_time.keperluan, over_time.catatan, over_time.hari, over_time.shift, over_time.status_shift, over_time_member.nik, namaKaryawan, jam, dari, sampai, makan, ext_food, transport from over_time
        left join over_time_member on over_time.id = over_time_member.id_ot
        left join karyawan on karyawan.nik = over_time_member.nik
        where over_time.id = '".$id."' and jam_aktual = 0";
        $query = $this->db->query($q);
        return $query->result();
    }

    public function ot_control($tgl, $tgl2)
    {        
        $q = "SELECT datas.*, d.jam_harian from
        ( SELECT  n.id_cc, master_cc.NAME,
        sum( n.act ) AS act, sum( budget_tot ) AS tot, sum( budget_tot ) - sum( n.act ) AS diff FROM
        (
        
        select id_cc, tanggal, sum(budget_tot) as budget_tot, sum(act) as act from (
        SELECT
        karyawan.costCenter as id_cc,
        d.tanggal,
        0 as budget_tot,
        sum( jam ) AS act
        FROM
        (
        SELECT
        over_time_member.nik,
        over_time.tanggal,
        sum( IF ( STATUS = 0, over_time_member.jam, over_time_member.final ) ) AS jam 
        FROM
        over_time
        LEFT JOIN over_time_member ON over_time.id = over_time_member.id_ot 
        WHERE
        DATE_FORMAT( over_time.tanggal, '%Y-%m' ) = '".$tgl2."' 
        AND over_time.tanggal <= '".$tgl."'
        AND over_time_member.nik IS NOT NULL 
        AND over_time.deleted_at IS NULL 
        AND jam_aktual = 0 
        GROUP BY
        over_time_member.nik,
        over_time.tanggal 
        ) d
        LEFT JOIN karyawan ON karyawan.nik = d.nik 
        GROUP BY
        tanggal,
        costCenter 

        UNION ALL

        SELECT
        l.id_cc,
        d.tanggal,
        l.budget_tot,
        0 as act
        FROM
        (
        SELECT
        id_cc,
        ROUND( ( budget_total / DATE_FORMAT( LAST_DAY( '".$tgl."' ), '%d' ) ), 1 ) budget_tot 
        FROM
        cost_center_budget 
        WHERE
        DATE_FORMAT( period, '%Y-%m' ) = '".$tgl2."' 
        ) AS l
        CROSS JOIN ( SELECT tanggal FROM over_time WHERE DATE_FORMAT( tanggal, '%Y-%m' ) = '".$tgl2."' AND tanggal <= '".$tgl."' 
        GROUP BY tanggal ) AS d
        ) as p
        group by id_cc, tanggal

        ) AS n
        LEFT JOIN master_cc ON master_cc.id_cc = n.id_cc 
        GROUP BY
        id_cc 
        ORDER BY
        diff ASC    ) AS datas
        LEFT JOIN (
        SELECT
        cost_center,
        sum( jam ) AS jam_harian 
        FROM
        budget_harian 
        WHERE
        DATE_FORMAT( tanggal, '%Y-%m' ) = '".$tgl2."' 
        AND tanggal <= '".$tgl."' 
        GROUP BY
        cost_center 
        ) d on datas.id_cc = d.cost_center
        ";
        $query = $this->db->query($q);
        return $query->result();
    }

    public function ot_control_mp($tgl, $tgl2, $fy)
    {
        $q = "select budget.id_cc, master_cc.name, budget.period, budget.budget, round((aktual.act / kar.karyawan),2) as act, budget.budget-round((aktual.act / kar.karyawan),2) as diff from
        ( select id_cc, period, budget from cost_center_budget where DATE_FORMAT(period,'%Y-%m') = '".$tgl2."' ) as budget
        left join
        ( select costCenter, COALESCE(sum(ot.jam),0) act from
        ( SELECT over_time_member.nik, sum(over_time_member.jam) jam from over_time
        LEFT JOIN over_time_member on over_time.id = over_time_member.id_ot
        WHERE DATE_FORMAT(tanggal,'%Y-%m') = '".$tgl2."' and tanggal <= '".$tgl."' and deleted_at is null
        group by nik ) as ot
        right join karyawan on karyawan.nik = ot.nik
        group by costCenter ) as aktual on budget.id_cc = aktual.costCenter
        left join
        ( select mon, master_cc.id_cc, sum(tot_karyawan) as karyawan from (
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
        having mon = '".$tgl2."'
        ) as b 
        left join master_cc on master_cc.id_cc = b.costCenter
        GROUP BY mon, master_cc.id_cc ) as kar on kar.id_cc = budget.id_cc
        left join master_cc on master_cc.id_cc = budget.id_cc
        order by diff asc";
        $query = $this->db->query($q);
        return $query->result();
    }

    public function getlastData()
    {
        $q = "select tanggal from over ORDER BY tanggal desc limit 1";
        $query = $this->db->query($q);
        return $query->result();
    }

    public function ot_control_detail($cc, $tgl, $tgl2)
    {
        $q = "SELECT
        final2.nik,
        c.namaKaryawan,
        sum( final2.jam ) AS jam,
        GROUP_CONCAT( DISTINCT c.kep ) AS kep 
        FROM
        (
        SELECT
        over_time_member.nik,
        over_time.tanggal,
        sum( over_time_member.jam ) AS jam 
        FROM
        over_time
        LEFT JOIN over_time_member ON over_time.id = over_time_member.id_ot 
        WHERE
        DATE_FORMAT( over_time.tanggal, '%Y-%m' ) = '".$tgl2."' 
        AND over_time_member.nik IS NOT NULL 
        AND over_time.deleted_at IS NULL 
        and over_time.tanggal <='".$tgl."'
        GROUP BY
        over_time_member.nik,
        over_time.tanggal 
        ) AS final2
        LEFT JOIN (
        SELECT
        over_time_member.nik,
        karyawan.namaKaryawan,
        karyawan.costCenter,
        GROUP_CONCAT( DISTINCT over_time.keperluan ) AS kep 
        FROM
        over_time
        LEFT JOIN over_time_member ON over_time_member.id_ot = over_time.id
        LEFT JOIN karyawan ON karyawan.nik = over_time_member.nik 
        WHERE
        DATE_FORMAT( over_time.tanggal, '%Y-%m' ) = '".$tgl2."' 
        AND over_time.tanggal <= '".$tgl."' 
        AND over_time_member.nik IS NOT NULL 
        GROUP BY
        over_time_member.nik,
        karyawan.namaKaryawan 
        ) AS c ON final2.nik = c.nik 
        WHERE
        c.costCenter = '".$cc."' 
        GROUP BY
        final2.nik 
        ORDER BY
        sum( final2.jam ) DESC";
        $query = $this->db->query($q);
        return $query->result();
    }

    public function get_cc($cc_name)
    {
        $this->db->select("id_cc");
        $this->db->from("master_cc");
        $this->db->where("name",$cc_name);
        $query = $this->db->get();
        return $query->result();
    }

    public function tot_karyawan($tgl, $fy)
    {
        $q = "select mon, costCenter, count(if(if(date_format(a.tanggalMasuk, '%Y-%m') < mon, 1, 0 ) - if(date_format(a.tanggalKeluar, '%Y-%m') < mon, 1, 0 ) = 0, null, 1)) as tot_karyawan from
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
        having mon = '".$tgl."'";
        $query = $this->db->query($q);
        return $query->result();
    }

    public function get_budget_total($cc, $tgl)
    {
        $q = "select cost_center_budget.id_cc, period, budget_total as budget from cost_center_budget
        left join master_cc on master_cc.id_cc = cost_center_budget.id_cc
        where DATE_FORMAT(period,'%Y-%m') = '".$tgl."' and master_cc.name = '".$cc."'";
        $query = $this->db->query($q);
        return $query->result();
    }

    public function ganti_aktual($nik, $tgl, $jam, $hari)
    {
        $this->db->where('nik', $nik);
        $this->db->where('tanggal', $tgl);
        $data = $this->db->get('over');

        $hasil = $data->num_rows();

        if ($hasil <= 0) {
            $this->db->set('nik', $nik);
            $this->db->set('tanggal', $tgl);
            $this->db->set('jam', $jam);
            $this->db->set('status', $hari);
            $query = $this->db->insert('over');
            return 'Success Insert Data';
        } else {
            $this->db->set('jam', $jam);
            $this->db->where('nik', $nik);
            $this->db->where('tanggal', $tgl);
            $query = $this->db->update('over');

            return 'Success Update Data';
        }
    }

    public function budget_harian($tgl, $tgl2)
    {
        $q = "select bdg.*, master_cc.name from
        (select cost_center, sum(jam) as jam from budget_harian where DATE_FORMAT(tanggal,'%Y-%m') = '".$tgl2."' and tanggal <= '".$tgl."'
        group by cost_center) as bdg
        left join master_cc on master_cc.id_cc = bdg.cost_center";

        $query = $this->db->query($q);
        return $query->result();
    }

    public function export_over_time($bulan, $stat)
    {
        if ($stat == 'tgl') {
            $s = ",tanggal";
        }
        else if ($stat == 'nik') {
            $s = "";
        }

        $q = "select ovr.tanggal, ovr.nik, karyawan.namaKaryawan, masuk as `in`, keluar as `out`, karyawan.costCenter, master_cc.name, sum(ovr.final) as jam, COALESCE(sum(satuan),0)  as satuan from
        (
        select over_time.tanggal, over_time_member.nik, sum(final) final, over_time.hari from over_time left join over_time_member on over_time.id = over_time_member.id_ot where DATE_FORMAT(tanggal,'%Y-%m') = '".$bulan."' and deleted_at IS NULL and nik IS NOT NULL and over_time_member.status = 1 and over_time_member.jam_aktual = 0
        group by nik, tanggal
        ) ovr
        left join karyawan on karyawan.nik = ovr.nik
        left join (select tanggal, masuk, keluar, nik from presensi where DATE_FORMAT(tanggal,'%Y-%m') = '2019-05') p on p.nik = ovr.nik AND p.tanggal = ovr.tanggal
        left join master_cc on karyawan.costCenter = master_cc.id_cc
        left join satuan_lembur on satuan_lembur.jam = ovr.final and satuan_lembur.hari = ovr.hari
        where ovr.final <> 0
        group by nik ".$s."
        ORDER BY tanggal asc
        ";

        $query = $this->db->query($q);
        return $query->result();
    }

    public function exportspl($awal, $akhir)
    {
        $q = "select id_ot, d.nik, d.tanggal, karyawan.namaKaryawan, CONCAT(section.nama,' - ',IFNULL(sub_section.nama,' ')) as bagian, dari, sampai, d.jam, masuk, keluar, d.hari as status_hari, d.status as status_konfirmasi, final as final_jam, satuan from 
        (select id_ot, nik, dari, sampai, sum(jam) as jam, sum(final) as final, status, over_time.tanggal, over_time.section, over_time.sub_sec, hari from over_time 
        left join over_time_member on over_time.id = over_time_member.id_ot
        WHERE deleted_at is null and jam_aktual = 0 and tanggal >= '".$awal."' and tanggal <= '".$akhir."'
        group by nik, tanggal) d
        left join karyawan on karyawan.nik = d.nik
        left join presensi on presensi.nik = d.nik and presensi.tanggal = d.tanggal
        left join posisi on posisi.nik = d.nik
        left join section on section.id = posisi.id_sec
        left join sub_section on sub_section.id = posisi.id_sub_sec
        left join satuan_lembur on satuan_lembur.jam = d.final and satuan_lembur.hari = d.hari
        order by d.tanggal asc, nik asc";

        $query = $this->db->query($q);
        return $query->result();
    }
}

?>