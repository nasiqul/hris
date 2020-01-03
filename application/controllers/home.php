<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	function __construct(){
        parent::__construct();
        $this->load->model('home_model');
        $this->load->model('absensi_model');
        $this->load->model('karyawan_model');
        $this->load->model('cari_model');
        $this->load->model('cari_absen_model');
        $this->load->model('qa_model');
        $this->load->model('cari_karyawan_model');
        $this->load->model('karyawan_graph_model');
        $this->load->model('karyawan_by_period_model');
        $this->load->model('report_detail_m');
        $this->load->model('over_model');
        $this->load->model('over_model_new');
        $this->load->model('budget_model');
        $this->load->model('os_model');
    }

    public function index()
    {

        if (isset($_POST['bulan'])) {
            $newdata = array(
                'bulan'  => $_POST['bulan']
            );

            $this->session->set_userdata($newdata);
        }

        if ($this->session->userdata("bulan")) {

            $bln = $this->session->userdata("bulan");

            $data['report1'] = $this->home_model->report1_1_by_tgl($bln);
            $data['report2'] = $this->home_model->report2_2_by_tgl($bln);
        }
        else {
            $data['report1'] = $this->home_model->report1_1();
            $data['report2'] = $this->home_model->report2_2();
        }
        $data['parentMenu'] = $this->home_model->getParentMenu();
        
        if ($this->session->userdata('nikLogin')) { 
            if ($this->session->userdata('role') == '1') {
                $data['menu2'] = 'home';
                $data['menu'] = '';
                $this->load->view('report', $data);
            }
            else
            {
                $data['menu2'] = 'Overtime';
                $data['menu'] = 'Overtime User';
                $username = $this->session->userdata('nikLogin');
                $data['dep'] = $this->over_model->get_dep($username);
                $this->load->view('overtime_user2', $data);
            }
            
        } else {
            redirect('login'); 
        }
    }

    public function absen()
    {
        if (isset($_POST['tanggal']) || isset($_POST['nik']) || isset($_POST['nama']) || isset($_POST['absensi']))
        {
            $newdata = array(
                'tanggal2'  => $_POST['tanggal'],
                'nik2'     => $_POST['nik'],
                'nama2' => $_POST['nama'],
                'shift2' => $_POST['absensi']
            );

            $this->session->set_userdata($newdata);
        }
        $data['parentMenu'] = $this->home_model->getParentMenu();
        $data['menu2'] = 'Absence';
        $data['menu'] = 'Absence Data';
        $this->load->view('absen',$data);
    }

    public function absensi_graph()
    {
        $data['menu2'] = 'Absence';
        $data['menu'] = 'Absence Graph';
        $data['parentMenu'] = $this->home_model->getParentMenu();
        $this->load->view("absensi_graph",$data);
    }
    
//--------------- Chart 
    public function budget_chart()
    {
        $data['menu2'] = 'Overtime';
        $data['menu'] = "Budget Total";
        $data['parentMenu'] = $this->home_model->getParentMenu();
        if (isset($_POST['tgl2'])) {
            $n = date('Y-m', strtotime($_POST['tgl2']));
            $tgl = $n."-01";
        }
        else {
            $tgl = date('Y-m')."-01";
        }
        $data['cc'] = $this->budget_model->get_chart_data($tgl);
        $this->load->view("budget_graph",$data);
    }

    public function chart_ot_report()
    {
        $data['menu'] = 'ovrR2';
        $this->load->view("graph_report",$data);
    }

    public function overtime_control_new()
    {
        $data['menu2'] = 'Overtime';
        $data['menu'] = 'Overtime Control New';
        $data['parentMenu'] = $this->home_model->getParentMenu();
        // $data['tgl2'] = $this->over_model_new->getlastData();
        $this->load->view("overtime_control_new",$data);
    }

    public function hr_report()
    {
        $data['menu2'] = 'Overtime';
        $data['menu'] = 'HR Report';
        $data['parentMenu'] = $this->home_model->getParentMenu();
        $this->load->view("ot_hr_report",$data);
    }

    public function overtime_control_mp()
    {
        $data['menu2'] = 'Overtime';
        $data['menu'] = 'Overtime Control MP';
        $data['parentMenu'] = $this->home_model->getParentMenu();
        $data['tgl2'] = $this->over_model_new->getlastData();
        $this->load->view("overtime_control_mp",$data);
    }

    public function budget_chart_mp()
    {
        $data['menu2'] = 'Overtime';
        $data['menu'] = 'Budget Total / MP';
        $data['parentMenu'] = $this->home_model->getParentMenu();
        if (isset($_POST['tgl2'])) {
            $n = date('Y-m', strtotime($_POST['tgl2']));
            $tgl = $n."-01";
        }
        else {
            $tgl = date('Y-m')."-01";
        }
        $data['cc2'] = $this->budget_model->get_chart_data_mp($tgl);
        $this->load->view("budget_graph_mp",$data);
    }

    public function outSource()
    {
        $data['menu2'] = 'Employee';
        $data['menu'] = 'Outsource Employee';
        $data['parentMenu'] = $this->home_model->getParentMenu();
        $this->load->view("outsource",$data);
    }

    public function client()
    {
        $this->load->view("client");
    }

    public function monthly()
    {
        $data['menu2'] = 'Overtime';
        $data['menu'] = 'Monthly Overtime Summary';
        $data['parentMenu'] = $this->home_model->getParentMenu();
        $this->load->view("ot_summary", $data);
    }

    public function monthlyMon()
    {
        $data['menu2'] = 'Overtime';
        $data['menu'] = 'Monthly Overtime Monitor';
        $data['parentMenu'] = $this->home_model->getParentMenu();
        $this->load->view("ot_monthly_monitor", $data);
    }

    public function hr_ot()
    {
        $data['dep'] = $this->home_model->get_dep_all();
        $data['menu2'] = 'Overtime';
        $data['menu'] = 'HR - Overtime';
        $data['parentMenu'] = $this->home_model->getParentMenu();
        $this->load->view("ot_hr", $data);
    }

    public function emp_tot()
    {
        $data['menu2'] = 'Employee';
        // $data['prs'] = $this->over_model->get_p_data();
        $data['menu'] = 'Total Employee';
        $data['parentMenu'] = $this->home_model->getParentMenu();
        $this->load->view('tabel1',$data);
    }

    public function monthlyC()
    {
        $data['menu2'] = 'Overtime';
        $data['menu'] = 'Monthly Overtime Control';
        $data['parentMenu'] = $this->home_model->getParentMenu();
        $data['section'] = $this->budget_model->get_name_cc2();
        $this->load->view("ot_monthly_control", $data);
    }

    public function ot_m()
    {
        $data['menu2'] = 'Overtime';
        $data['menu'] = 'OT Management By Section';
        $data['parentMenu'] = $this->home_model->getParentMenu();
        $data['section'] = $this->budget_model->get_name_cc();
        $data['fiskal'] = $this->home_model->getFiskalAll();
        $this->load->view("ot_management",$data);
    }

    public function overtime_form()
    {
        $data['dep'] = $this->home_model->get_dep_all();
        $data['kep'] = $this->home_model->get_kep_all();
        $data['id_doc'] = $this->over_model->get_id_doc();
        $data['parentMenu'] = $this->home_model->getParentMenu();
        $data['menu2'] = 'Overtime';
        $data['menu'] = 'Overtime Data';
        // $this->load->view("overtime_form3",$data);
        // $this->load->view('sunfish');
        redirect('http://172.17.128.4/mirai/public/information_board');
    }

    public function overtime_edit($id_ot)
    {
        $data['dep'] = $this->home_model->get_dep_all();
        $data['isi'] = $this->over_model_new->ot_hr($id_ot);

        $isi2 = $this->over_model_new->ot_hr($id_ot);

        $data['sec'] = $this->over_model->get_sub_sec($isi2[0]->departemen);
        $data['sub_sec'] = $this->over_model->get_grup($isi2[0]->section);

        $data['menu2'] = 'Overtime';
        $data['menu'] = 'Overtime User';
        $data['parentMenu'] = $this->home_model->getParentMenu();
        $this->load->view("overtime_edit",$data);
    }

    public function ot_graph()
    {
        $data['menu2'] = 'Overtime';
        $data['menu'] = 'Report Graph';
        $data['parentMenu'] = $this->home_model->getParentMenu();

        $d = date('n');
        $data3 = array();
        for ($i = $d; $i > 0; $i--) {
            $s = date('d-m-Y',strtotime('2019-'.$d.'-01'));

            $list = $this->over_model->coba1($s);

            $data2 = array();

            foreach ($list as $key) {
                $row = array();
                $row[] = $key->bulan;
                $row[] = $key->tot;

                array_push($data2, $row);
            }
            array_push($data3, $data2);
        }
        $data['tes'] = $data3;
        $this->load->view("ot_graph",$data);
    }

    public function ot_report()
    {
        $data['menu2'] = 'Overtime';
        $data['menu'] = 'Report';
        $data['parentMenu'] = $this->home_model->getParentMenu();
        $this->load->view("overtime_report",$data);
    }

    public function ot_report2()
    {
        $data['menu2'] = 'Overtime';
        $data['menu'] = 'OT Management By NIK';
        $data['parentMenu'] = $this->home_model->getParentMenu();
        $this->load->view("overtime_report2",$data);
    }

    public function detailSPL($nik,$tgl)
    {
        $data['menu2'] = 'Overtime';
        $data['menu'] = 'OT Management By NIK';
        $data['parentMenu'] = $this->home_model->getParentMenu();
        $data['nik'] = $nik;
        $data['tgl'] = $tgl;
        $this->load->view("graph_report",$data);
    }

    public function karyawan_graph()
    {
        $data['status'] = $this->karyawan_model->by_status();
        $data['gender'] = $this->karyawan_model->by_gender();
        $data['grade'] = $this->karyawan_model->by_grade();
        $data['kode'] = $this->karyawan_model->by_kode();
        $data['posisi'] = $this->karyawan_model->by_posisi();
        $data['parentMenu'] = $this->home_model->getParentMenu();
        $data['menu2'] = 'Employee';
        $data['menu'] = 'Employee Graph';
        $this->load->view("karyawan_graph", $data);
    }

    public function user_QA()
    {
        $this->load->view("qa_user");
    }

    public function tanya()
    {
        $data['parentMenu'] = $this->home_model->getParentMenu();
        $data['menu'] = 'qa';
        $this->load->view('qa',$data);
    }

    public function overtime_user()
    {
        $username = $this->session->userdata('nikLogin');
        $data['dep'] = $this->over_model->get_dep($username);
        $data['parentMenu'] = $this->home_model->getParentMenu();
        $data['menu2'] = 'Overtime';
        $data['menu'] = 'Overtime User';
        // $this->load->view('overtime_user2',$data);
        // $this->load->view('sunfish');
        
        redirect('http://172.17.128.4/mirai/public/information_board');
    }

    public function report_GA()
    {
        $data['parentMenu'] = $this->home_model->getParentMenu();
        $data['menu2'] = 'Overtime';
        $data['menu'] = 'GA - Report';
        $this->load->view('GAreport',$data);
    }

    public function ot()
    {
        if (isset($_POST['tanggal']) || isset($_POST['departemen'])) 
        {
            $newdata = array(
                'tanggal3'  => $_POST['tanggal'],
                'dep2'     => $_POST['departemen']
            );

            $this->session->set_userdata($newdata);
        }
        $data['parentMenu'] = $this->home_model->getParentMenu();
        $data['menu2'] = 'Overtime';
        $data['menu'] = 'Overtime Data';
        $this->load->view('overtime2',$data);
    }

    public function presensi()
    {
        if (isset($_POST['tanggal_from']) || isset($_POST['tanggal_to']) || isset($_POST['nik']) || isset($_POST['nama']) || isset($_POST['shift'])) 
        {
            $newdata = array(
                'tanggal_from'  => $_POST['tanggal_from'],
                'tanggal_to'  => $_POST['tanggal_to'],
                'nik3'     => $_POST['nik'],
                'nama' => $_POST['nama'],
                'shift' => $_POST['shift']
            );

            $this->session->set_userdata($newdata);
        }

        $data['parentMenu'] = $this->home_model->getParentMenu();
        $data['menu2'] = 'Presence';
        $data['menu'] = 'Presence Data';
        $this->load->view('index',$data);
    }

    public function presensi_os()
    {
        $data['parentMenu'] = $this->home_model->getParentMenu();
        $data['menu2'] = 'Presence';
        $data['menu'] = 'Outsource Presence';
        $this->load->view('presence_outsource',$data);
    }

    public function presensi_graph()
    {
        $data['persentase'] = $this->home_model->by_persentase();
        $data['persentase_tidakMasuk'] = $this->home_model->persentase_tidakMasuk();
        $data['kary'] = $this->karyawan_model->tot();
        $data['parentMenu'] = $this->home_model->getParentMenu();
        $data['menu2'] = 'Presence';
        $data['menu'] = 'Presence Graph';
        $this->load->view("presensi_graph", $data);
    }

    public function karyawan_t()
    {
        $data['dev'] = $this->karyawan_model->get_dev();
        $data['grade'] = $this->karyawan_model->get_grade();
        $data['jabatan'] = $this->karyawan_model->get_jabatan();
        $data['kode'] = $this->karyawan_model->get_kode();
        $data['parentMenu'] = $this->home_model->getParentMenu();
        $data['menu2'] = 'Employee';
        $data['menu'] = 'Employee Data';
        $this->load->view('karyawan_form',$data);
    }

    public function ajax()
    {
        if ((isset($_SESSION['tanggal_from']) && $_SESSION['tanggal_from'] != "") || (isset($_SESSION['tanggal_to']) && $_SESSION['tanggal_to'] != "") || (isset($_SESSION['nik3']) && $_SESSION['nik3'] != "") || (isset($_SESSION['nama']) && $_SESSION['nama'] != "") || (isset($_SESSION['shift']) && $_SESSION['shift'] != "")) 
        {
            $tgl_from = $this->session->userdata('tanggal_from');
            $tgl_to = $this->session->userdata('tanggal_to');
            $nik = $this->session->userdata('nik3');
            $nama = $this->session->userdata('nama');
            $shift = $this->session->userdata('shift');

            $list = $this->cari_model->get_data_presensi_cari($tgl_from, $tgl_to, $nik, $nama, $shift);
            $tot = $this->cari_model->count_all($tgl_from, $tgl_to, $nik, $nama, $shift);
            $filter = $this->cari_model->count_filtered($tgl_from, $tgl_to, $nik, $nama, $shift);
        }
        else {
            $tgl_from = "";
            $tgl_to = "";
            $nik = "";
            $nama = "";
            $shift = "";
            $list = $this->home_model->get_data_persensi();
            $tot = $this->home_model->count_all();
            $filter = $this->home_model->count_filtered();
        }

        $data = array();
        foreach ($list as $key) {
            $row = array();
            $row[] = date('j M Y', strtotime($key->tanggal));
            $row[] = $key->nik;
            $row[] = $key->namaKaryawan;
            $row[] = $key->sec." - ".$key->subsec." - ".$key->grup;
            if ($key->masuk == '') 
                $row[] = '';
            else
                $row[] = $key->masuk;
            if ($key->keluar == '') 
                $row[] = '';
            else
                $row[] = $key->keluar;
            $row[] = $key->shift;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $tot,
            "recordsFiltered" => $filter,
            "data" => $data,
            "params" => [$tgl_from, $tgl_to, $nik, $nama, $shift]
        );
            //output to json format
        echo json_encode($output);
    }

    public function ajax_karyawan_cari_g()
    {
        $status = $_POST['status'];
        $grade = $_POST['grade'];
        $dep = $_POST['dep'];
        $pos = $_POST['pos'];

        $list = $this->cari_karyawan_model->get_data_karyawan_cari($status,$grade,$dep,$pos);
        $tot = $this->cari_karyawan_model->count_all();
        $filter = $this->cari_karyawan_model->count_filtered($status,$grade,$dep,$pos);


        $data = array();
        foreach ($list as $key) {
            $row = array();
            $row[] = $key->nik;
            $row[] = $key->namaKaryawan;
            $row[] = $key->namadev;
            $row[] = $key->namadep;
            $row[] = $key->section;
            $row[] = $key->sub_section;
            $row[] = $key->tanggalMasuk;
            $row[] = $key->statusKaryawan;
            if ($key->status == "Aktif")
                $row[] = "<p class='text-center'><small class='label bg-green'>".$key->status." <i class='fa fa-check'></i> </small></p>";
            else
                $row[] = "<p class='text-center'><small class='label bg-red'>".$key->status." <i class='fa fa-close'></i> </small></p>";

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

    public function ajax_presensi_cari_g()
    {

        $tgl = date('d/m/Y', strtotime($_POST['tanggal']));
        $nik = $_POST['nik'];
        $nama = $_POST['nama'];
        $shift = $_POST['shift'];

        $list = $this->cari_model->get_data_presensi_cari($tgl, $nik, $nama, $shift);
        $tot = $this->cari_model->count_all($tgl, $nik, $nama, $shift);
        $filter = $this->cari_model->count_filtered($tgl, $nik, $nama, $shift);

        $data = array();
        foreach ($list as $key) {
            $row = array();
            $row[] = date('j M Y', strtotime($key->tanggal));
            $row[] = $key->nik;
            $row[] = $key->namaKaryawan;
            if ($key->masuk == '') 
                $row[] = '';
            else
                $row[] = $key->masuk;
            if ($key->keluar == '') 
                $row[] = '';
            else
                $row[] = $key->keluar;
            $row[] = $key->shift;

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

    public function ajax_absen_g()
    {

        if (isset($_POST['sortTgl'])) 
        {
            $tgl = $_POST['sortTgl'];

            $absen = $this->absensi_model->by_absensi_cari($tgl);
        }
        else {
            $absen = $this->absensi_model->by_absensi();
        }

        $arr = array();
        $result = array();
        if(!empty($absen)) {
            foreach($absen as $r2){
              $tgl = date('d-m-Y', strtotime($r2->tanggal));

              $arr['name'] = $r2->shift;
              $arr['y'] = (int) $r2->jml;
              $arr['tgl'] = $tgl;

              $result[] = $arr;
          }
      }
      else
        $result[] = json_decode("{}");

    echo json_encode($result);
}


public function ajax_absensi()
{
    if ((isset($_SESSION['tanggal2']) && $_SESSION['tanggal2'] != "") || (isset($_SESSION['nik2']) && $_SESSION['nik2'] != "") || (isset($_SESSION['nama2']) && $_SESSION['nama2'] != "") || (isset($_SESSION['shift2']) && $_SESSION['shift2'] != ""))
    // if (isset($_SESSION['tanggal2']) || isset($_SESSION['nik2']) || isset($_SESSION['nama2']) || isset($_SESSION['shift2'])) 
    {
        $tgl = $this->session->userdata('tanggal2');
        $nik = $this->session->userdata('nik2');
        $nama = $this->session->userdata('nama2');
        $absen = $this->session->userdata('shift2');

        $list = $this->cari_absen_model->get_data_absensi_cari($tgl, $nik, $nama, $absen);
        $tot = $this->cari_absen_model->count_all($tgl, $nik, $nama, $absen);
        $filter = $this->cari_absen_model->count_filtered($tgl, $nik, $nama, $absen);
    }
    else {
        $list = $this->absensi_model->get_data_absensi();
        $tot = $this->absensi_model->count_all();
        $filter = $this->absensi_model->count_filtered();
    }

    $data = array();
    foreach ($list as $key) {
        $row = array();
        $row[] = date('j M Y', strtotime($key->tanggal));
        $row[] = $key->nik;
        $row[] = $key->namaKaryawan;
        $row[] = $key->bagian;
        $row[] = $key->shift;

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


public function ajax_absensi_cari_g()
{
    $tgl = date('d/m/Y', strtotime($_POST['tanggal']));
    $nik = $_POST['nik'];
    $nama = $_POST['nama'];
    $absen = $_POST['absensi'];

    $list = $this->cari_absen_model->get_data_absensi_cari($tgl, $nik, $nama, $absen);
    $tot = $this->cari_absen_model->count_all($tgl, $nik, $nama, $absen);
    $filter = $this->cari_absen_model->count_filtered($tgl, $nik, $nama, $absen);

    $data = array();
    foreach ($list as $key) {
        $row = array();
        $row[] = date('j M Y', strtotime($key->tanggal));
        $row[] = $key->nik;
        $row[] = $key->namaKaryawan;
        $row[] = $key->bagian;
        $row[] = $key->shift;

        $data[] = $row;
    }

    $output = array(
        "draw" => $_POST['draw'],
        "data" => $data,
        "recordsTotal" => $tot,
        "recordsFiltered" => $filter,
    );
            //output to json format
    echo json_encode($output);
}

// public function ajax_emp()
// {
//     if (isset($_SESSION['status']) || isset($_SESSION['grade']) || isset($_SESSION['dep']) || isset($_SESSION['pos'])) 
//     {
//         $status = $this->session->userdata('status');
//         $grade = $this->session->userdata('grade');
//         $dep = $this->session->userdata('dep');
//         $pos = $this->session->userdata('pos');
//         $list = $this->cari_karyawan_model->get_data_karyawan_cari($status,$grade,$dep,$pos);
//         $tot = $this->cari_karyawan_model->count_all();
//         $filter = $this->cari_karyawan_model->count_filtered($status,$grade,$dep,$pos);
//     }
//     elseif (isset($_SESSION['bulan'])) {
//         $bulan = $this->session->userdata('bulan');
//         $list = $this->karyawan_by_period_model->get_karyawan($bulan);
//     }
//     else {
//         $list = $this->karyawan_model->get_data_karyawan();
//         $tot = $this->karyawan_model->count_all();
//         $filter = $this->karyawan_model->count_filtered();
//     }

//     $data = array();
//     $i = 1;
//     foreach ($list as $key) {
//         $row = array();
//         $row[] = $key->nik;
//                 //$row[] = $key->foto;
//         $row[] = "<a style='cursor: pointer' onclick='ShowModal(".$i.")' data-toggle='modal' data-id='".$key->nik."' id='tes".$i."'>".$key->namaKaryawan."</a>";
//         $row[] = $key->dep;
//         $row[] = $key->group;
//         $row[] = date("j M Y", strtotime($key->tanggalMasuk));
//         $row[] = $key->statusKaryawan;
//         $row[] = "<p class='text-center'><small class='label bg-green'>".$key->status." <i class='fa fa-check'></i> </small></p>";

//         $data[] = $row;
//         $i++;
//     }

//     $output = array(
//         "draw" => $_POST['draw'],
//         "recordsTotal" => $tot,
//         "recordsFiltered" => $filter,
//         "data" => $data,
//     );
//             //output to json format
//     echo json_encode($output);
// }

public function ajax_presensi_shift()
{

    if (isset($_POST['txtTgl'])) 
    {
        $tgl = $_POST['txtTgl'];

        $presen = $this->karyawan_graph_model->by_total_kehadiran_date($tgl);
    }
    else {
        $presen = $this->karyawan_graph_model->by_total_kehadiran();
    }

    $arr = array();
    $result = array();
    if(!empty($presen)) {
        foreach($presen as $r2){
          $tgl = date('d-m-Y', strtotime($r2->tanggal));

          $arr['name'] = $r2->shift;
          $arr['y'] = (int) $r2->jml;
          $arr['tgl'] = $tgl;

          $result[] = $arr;
      }
  }
  else
   $result[] = json_decode ("{}");

echo json_encode($result);
}

public function ajax_emp_keluarga()
{
    $list = $this->karyawan_model->getKeluarga();
    $data = array();
    foreach ($list as $key) {
        $row = array();
        $row[] = $key->statusKeluarga;

        $data[] = $row;
    }

    echo json_encode($data);
}

public function ajax_dev()
{
    $list = $this->karyawan_model->get_dev();
    $data = array();
    foreach ($list as $key) {
        $row = array();
        $row[] = $key->id;
        $row[] = $key->nama;

        $data[] = $row;
    }

    //output to json format
    echo json_encode($data);
}

public function ajax_dep()
{
    $id = $_POST['id'];
    $list = $this->karyawan_model->get_dep($id);
    $data = array();
    foreach ($list as $key) {
        $row = array();
        $row[] = $key->id;
        $row[] = $key->nama;

        $data[] = $row;
    }

    //output to json format
    echo json_encode($data);
}

public function ajax_sec()
{
    $id = $_POST['id'];
    $list = $this->karyawan_model->get_sec($id);
    $data = array();
    foreach ($list as $key) {
        $row = array();
        $row[] = $key->id;
        $row[] = $key->nama;

        $data[] = $row;
    }

    //output to json format
    echo json_encode($data);
}

public function ajax_sub_sec()
{
    $id = $_POST['id'];
    $list = $this->karyawan_model->get_sub_sec($id);
    $data = array();
    foreach ($list as $key) {
        $row = array();
        $row[] = $key->id;
        $row[] = $key->nama;

        $data[] = $row;
    }

    //output to json format
    echo json_encode($data);
}

public function ajax_group()
{
    $id = $_POST['id'];
    $list = $this->karyawan_model->get_group($id);
    $data = array();
    foreach ($list as $key) {
        $row = array();
        $row[] = $key->id;
        $row[] = $key->nama;

        $data[] = $row;
    }

    //output to json format
    echo json_encode($data);
}

public function ajax_kode()
{
    $list = $this->karyawan_model->get_kode();
    $data = array();
    foreach ($list as $key) {
        $row = array();
        $row[] = $key->id;
        $row[] = $key->nama;

        $data[] = $row;
    }

    //output to json format
    echo json_encode($data);
}

public function ajax_grade()
{
    $list = $this->karyawan_model->get_grade();
    $data = array();
    foreach ($list as $key) {
        $row = array();
        $row[] = $key->id;
        $row[] = $key->kode_grade;
        $row[] = $key->nama_grade;

        $data[] = $row;
    }

    //output to json format
    echo json_encode($data);
}

public function ajax_jabatan()
{
    $list = $this->karyawan_model->get_jabatan();
    $data = array();
    foreach ($list as $key) {
        $row = array();
        $row[] = $key->id;
        $row[] = $key->jabatan; 

        $data[] = $row;
    }

    //output to json format
    echo json_encode($data);
}


public function ajax_over_namadept()
{
    $id = $_POST['id'];
    $list = $this->karyawan_model->getNamadept($id);
    $data = array();

    
    if ($list) {
        foreach ($list as $key) {
            $row = array();            
            $row[] = $key->nama;
            array_push($data, $row);
        }
    }

            //output to json format
    echo json_encode($data);
}

public function ajax_over_section()
{
    $id = $_POST['id'];
    $list = $this->karyawan_model->getSection($id);
    $data = array();

    $row1 = array();
    $row1[] = "";
    $row1[] = "Select Sub Section";

    $data[] = $row1;
    if ($list) {
        foreach ($list as $key) {
            $row = array();
            $row[] = $key->id;
            $row[] = $key->nama;

            array_push($data, $row);
        }
    }

            //output to json format
    echo json_encode($data);
}


public function ajax_over_subsection()
{
    $id = $_POST['id'];
    $list = $this->karyawan_model->get_sub_sec($id);
    $data = array();

    $row1 = array();
    $row1[] = "";
    $row1[] = "Select Group";

    $data[] = $row1;
    if ($list) {
        foreach ($list as $key) {
            $row = array();
            $row[] = $key->id;
            $row[] = $key->nama;

            array_push($data, $row);
        }
    }

            //output to json format
    echo json_encode($data);
}

public function ajax_get_nama()
{
    $nik = $_POST['nik'];

    $list = $this->karyawan_model->get_data_karyawan_by_nik($nik);
    $data = array();
    foreach ($list as $key) {
        $row = array();
        $row[] = $key->nik;
        $row[] = $key->namaKaryawan;
        $row[] = $key->costCenter;
        $row[] = $key->dep;
        $row[] = $key->group;
        $row[] = $key->kode;

        $data[] = $row;
    }

            //output to json format
    echo json_encode($data);
}

public function ajax_qa()
{
    $list = $this->qa_model->get_data_qa();
    $data = array();
    foreach ($list as $key) {
        $row = array();
        $row[] = $key->tanggal_tanya;
        $row[] = $key->nik_penanya;
        $row[] = $key->pertanyaan;
        $row[] = $key->jawaban;
        $row[] = $key->nik_penjawab;
        if ($key->tanggal_jawab != 0) {
            $row[] = $key->tanggal_jawab;
        }
        else
            $row[] = "";
        $row[] = "<button class='btn btn-success'><i class='fa fa-bullhorn'></i> Answer</button>";

        $data[] = $row;
    }

    $output = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->qa_model->count_all(),
        "recordsFiltered" => $this->qa_model->count_filtered(),
        "data" => $data,
    );
            //output to json format
    echo json_encode($output);
}

public function get_presensi(){
    $data=$this->home_model->get_presensi();
    echo json_encode($data);
}

public function session_destroy()
{
    $array_items = array('tanggal_from','tanggal_to' , 'nik3', 'nama', 'shift');
    $this->session->unset_userdata($array_items);
    redirect('home/presensi');
}

public function sess_destroy2()
{
    $array_items = array('tanggal2' , 'nik2', 'nama2', 'shift2');
    $this->session->unset_userdata($array_items);
    redirect('home/absen');
}

public function logout()
{
    redirect("login");
}

//------------------------- KARYAWAN COBA -------------------

public function karyawan()
{
    if (isset($_POST['status']) || isset($_POST['grade']) || isset($_POST['dep']) || isset($_POST['pos'])) 
    {
        $newdata = array(
            'status'  => $_POST['status'],
            'grade'  => $_POST['grade'],
            'dep'  => $_POST['dep'],
            'pos'  => $_POST['pos']
        );

        $this->session->set_userdata($newdata);
    }

    if (isset($_POST['bulan'])) {
        $newdata = array(
            'bulan' => $_POST['bulan']
        );

        $this->session->set_userdata($newdata);
    }
    $data['menu2'] = 'Employee';
    $data['menu'] = 'Employee Data';
    $data['parentMenu'] = $this->home_model->getParentMenu();
    $this->load->view("karyawan2", $data);
}

public function ajax_emp_coba()
{

    if ((isset($_SESSION['status']) && $_SESSION['status'] != "") || (isset($_SESSION['grade']) && $_SESSION['grade'] != "") || (isset($_SESSION['dep']) && $_SESSION['dep'] != "") || (isset($_SESSION['pos']) && $_SESSION['pos'] != ""))
    {
        $status = $this->session->userdata('status');
        $grade = $this->session->userdata('grade');
        $dep = $this->session->userdata('dep');
        $pos = $this->session->userdata('pos');
        $list = $this->cari_karyawan_model->get_data_karyawan_cari($status,$grade,$dep,$pos);
        $tot = $this->cari_karyawan_model->count_all();
        $filter = $this->cari_karyawan_model->count_filtered($status,$grade,$dep,$pos);
    }
    else {
        if (isset($_POST['stat'])) {
            $stat = $_POST['stat'];
        } else {
            $stat = "";
        }
        
        $list = $this->karyawan_model->get_data_karyawan_coba($stat);
        $tot = $this->karyawan_model->count_all2($stat);
        $filter = $this->karyawan_model->count_filtered2($stat);
    }

    $data = array();
    $i = 1;
    foreach ($list as $key) {
        $row = array();
        $row[] = $key->nik;
                //$row[] = $key->foto;
        $row[] = "<a style='cursor: pointer' onclick='ShowModal(".$i.")' data-toggle='modal' data-id='".$key->nik."' id='tes".$i."'>".$key->namaKaryawan."</a>";
        $row[] = $key->namadev;
        $row[] = $key->namadep;
        $row[] = date('d M Y', strtotime($key->tanggalMasuk));
        $row[] = $key->statusKaryawan;
        if ($key->status == "Aktif")
            $row[] = "<p class='text-center'><small class='label bg-green'>".$key->status." <i class='fa fa-check'></i> </small></p>";
        else
            $row[] = "<p class='text-center'><small class='label bg-red'>".$key->status." <i class='fa fa-close'></i> </small></p>";

        $data[] = $row;
        $i++;
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

public function ajax_emp_by_nik_coba($id)
{
            // echo $_GET['dataId'];
    $list = $this->karyawan_model->get_data_karyawan_by_nik2($id);
    $data = array();
    foreach ($list as $key) {
        $row = array();
        $row[] = $key->pin;
        $row[] = $key->nik;
        $row[] = $key->costCenter;
        $row[] = $key->foto;
        $row[] = $key->namaKaryawan;
        $row[] = $key->dev;
        $row[] = $key->dep;
        $row[] = $key->sec;
        $row[] = $key->kode;
        $row[] = date("d-m-Y", strtotime($key->tanggalMasuk));
        $row[] = $key->jk;
        $row[] = $key->statusKaryawan;
        $row[] = $key->grade;
        $row[] = $key->nama_grade;
        $row[] = $key->jabatan;
        $row[] = $key->statusKeluarga;
        $row[] = $key->tempatLahir;
        $row[] = date("d-m-Y", strtotime($key->tanggalLahir));
        $row[] = $key->alamat;
        $row[] = $key->hp;
        $row[] = $key->ktp;
        $row[] = $key->rekening;
        $row[] = $key->bpjstk;
        $row[] = $key->jp;
        $row[] = $key->bpjskes;
        $row[] = $key->npwp;
        $row[] = $key->status;
        $row[] = $key->id_devisi;
        $row[] = $key->id_dep;
        $row[] = $key->id_sec;
        $row[] = $key->id_sub_sec;
        $row[] = $key->sub_sec2;
        $row[] = $key->grup;
        $row[] = $key->kode;
        $row[] = $key->gdid;
        $row[] = $key->atasan;

        $data[] = $row;
    }

            //output to json format
    echo json_encode($data);
}

public function sess_destroy()
{
    $this->session->sess_destroy();
    redirect("home");
}

public function reset_karyawan()
{
    $array_items = array('status' , 'grade', 'grade', 'dep', 'pos');
    $this->session->unset_userdata($array_items);
    redirect('home/karyawan');
}

public function ajax_kar()
{
    $bulan = $_POST['bulan'];
    $list = $this->karyawan_model->get_karywan2($bulan);
    $data = array();
    foreach ($list as $key) {
        $row = array();
        $row[] = $key->tanggal_tanya;
        $row[] = $key->nik_penanya;
        $row[] = $key->pertanyaan;
        $row[] = $key->jawaban;
        $row[] = $key->nik_penjawab;
        if ($key->tanggal_jawab != 0) {
            $row[] = $key->tanggal_jawab;
        }
        else
            $row[] = "";
        $row[] = "<button class='btn btn-success'><i class='fa fa-bullhorn'></i> Answer</button>";

        $data[] = $row;
    }

    $output = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->qa_model->count_all(),
        "recordsFiltered" => $this->qa_model->count_filtered(),
        "data" => $data,
    );
            //output to json format
    echo json_encode($output);
}

public function ajax_dep_over($tgl)
{
    $tanggal = date('Y-m-d',strtotime($tgl));

    $list = $this->over_model->chart_dep2($tanggal);
    $data = array();
    if(!empty($list)) {
        foreach ($list as $key) {
            $row = array();
            $row['tgl'] = $key->tanggal;
            $row['name'] = $key->namaDep;
            $row['y'] = $key->tot;

            $data[] = $row;
        }
    } else {
        $data[] = json_decode("{}");
    }

    //output to json format
    echo json_encode($data);
}


public function report_detail()
{
    $tgl = $_GET['tgl'];
    $tanggal = date('Y-m-d',strtotime($tgl));

    $list = $this->report_detail_m->get_data($tanggal);
    $data = array();
    foreach ($list as $key) {
        $row = array();
        $row[] = $key->tanggal;
        $row[] = $key->nik;
        $row[] = $key->namaKaryawan;
        $row[] = $key->section;
        $row[] = $key->masuk;
        $row[] = $key->keluar;
        $row[] = $key->shift;

        $data[] = $row;
    }

    $output = array(
        "draw" => $_GET['draw'],
        "recordsTotal" => $this->report_detail_m->count_all($tanggal),
        "recordsFiltered" => $this->report_detail_m->count_filtered($tanggal),
        "data" => $data,
    );
            //output to json format
    echo json_encode($output);
}

public function ajax_budget_g()
{
    $tgl = '10-'.$_POST['tgl'];
    $tanggal = date('Y-m-d',strtotime($tgl));

    $list = $this->_m->get_data($tanggal);
    $data = array();
    foreach ($list as $key) {
        $row = array();
        $row[] = $key->tanggal;
        $row[] = $key->nik;
        $row[] = $key->namaKaryawan;
        $row[] = $key->section;
        $row[] = $key->masuk;
        $row[] = $key->keluar;
        $row[] = $key->shift;

        $data[] = $row;
    }

    $output = array(
        "draw" => $_GET['draw'],
        "recordsTotal" => $this->report_detail_m->count_all($tanggal),
        "recordsFiltered" => $this->report_detail_m->count_filtered($tanggal),
        "data" => $data,
    );
            //output to json format
    echo json_encode($output);
}
public function get_total_emp()
{
    $fy = $this->home_model->getFiskal(date('Y-m'));
    $fiskal = $fy[0]->fiskal;
    $all_emp = $this->home_model->all_emp($fiskal);
    $all_work = $this->home_model->tot_jam_kerja($fiskal);

    $data = array();
    foreach ($all_emp as $key) {
        $row = array();
        $row[] = date('M',strtotime($key->mon."-01"));
        $row[] = 0;
        $row[] = $key->tot_karyawan;
        $row[] = $key->masuk;
        $row[] = $key->keluar;
        $row[] = $key->normal;
        $row[] = $key->libur;
        $row[] = $key->avg;
        $row[] = $key->tiga_jam;
        $row[] = $key->patblas_jam;
        $row[] = $key->tiga_patblas;
        $row[] = $key->limanam;

        $data[] = $row;
    }

    $data2 = array();
    foreach ($all_work as $key2) {
        $row = array();
        $row[] = date('M-y',strtotime($key2->mon."-01"));
        $row[] = $key2->hari_kerja;
        $row[] = $key2->tot_karyawan;
        $row[] = $key2->jam_kerja;
        $row[] = $key2->lembur;
        $row[] = $key2->tot_kerja;
        $row[] = $key2->CT;
        $row[] = $key2->SD;
        $row[] = $key2->I;
        $row[] = $key2->A;
        $row[] = $key2->tot_absen;
        $row[] = $key2->jam_tdk;
        $row[] = round((float) $key2->persen * 100,2);

        $data2[] = $row;
    }

    $response = array(
        'all_emp' => $data,
        'jam_kerja' => $data2
    );

    echo json_encode($response);
}

public function ajax_outsource()
{
    $list = $this->os_model->get_data_persensi();
    $tot = $this->os_model->count_all();
    $filter = $this->os_model->count_filtered();


    $data = array();
    foreach ($list as $key) {
        $row = array();
        $row[] = date('j M Y', strtotime($key->tanggal));
        $row[] = $key->nik;
        $row[] = $key->namaKaryawan;
        $row[] = $key->sec." - ".$key->subsec." - ".$key->grup;
        if ($key->masuk == '') 
            $row[] = '';
        else
            $row[] = $key->masuk;
        if ($key->keluar == '') 
            $row[] = '';
        else
            $row[] = $key->keluar;

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
