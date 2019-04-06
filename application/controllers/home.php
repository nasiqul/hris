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
        
        if ($this->session->userdata('nik')) { 
            $data['menu2'] = 'home';
            $data['menu'] = '';
            $this->load->view('report', $data);
            
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
        $data['menu2'] = 'Absence';
        $data['menu'] = 'Absence Data';
        $this->load->view('absen',$data);
    }

    public function absensi_graph()
    {
        $data['menu2'] = 'Absence';
        $data['menu'] = 'Absence Graph';
        $this->load->view("absensi_graph",$data);
    }
    
//--------------- Chart 
    public function budget_chart()
    {
        $data['menu2'] = 'Overtime';
        $data['menu'] = "Budget Total";
        if (isset($_POST['tgl2'])) {
            $n = date('Y-m', strtotime($_POST['tgl2']));
            $tgl = $n."-01";
        }
        else {
            $tgl = date('Y-m')."-01";
        }
        $data['cc'] = $this->budget_model->get_chart_data($tgl);
        $data['menu'] = 'Btot';
        $this->load->view("budget_graph",$data);
    }

    public function chart_ot_report()
    {
        $data['menu'] = 'ovrR2';
        $this->load->view("graph_report",$data);
    }

    public function budget_chart_mp()
    {
        $data['menu2'] = 'Overtime';
        $data['menu'] = 'Budget Total / MP';
        if (isset($_POST['tgl2'])) {
            $n = date('Y-m', strtotime($_POST['tgl2']));
            $tgl = $n."-01";
        }
        else {
            $tgl = date('Y-m')."-01";
        }
        $data['cc2'] = $this->budget_model->get_chart_data_mp($tgl);
        $data['menu'] = 'Btot2';
        $this->load->view("budget_graph_mp",$data);
    }

    public function outSource()
    {
        $data['menu2'] = 'Employee';
        $data['menu'] = 'Outsource Employee';
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
        $this->load->view("ot_summary", $data);
    }

    public function monthlyMon()
    {
        $data['menu2'] = 'Overtime';
        $data['menu'] = 'Monthly Overtime Monitor';
        $this->load->view("ot_monthly_monitor", $data);
    }

    public function hr_ot()
    {
        $data['dep'] = $this->home_model->get_dep_all();
        $data['menu2'] = 'Overtime';
        $data['menu'] = 'HR - Overtime';
        $this->load->view("ot_hr", $data);
    }

    public function emp_tot()
    {
        $data['menu2'] = 'Overtime';
        $data['prs'] = $this->over_model->get_p_data();
        $data['prs2'] = $this->over_model->tes1();
        $data['menu'] = 'ovrG';
        $this->load->view('tabel1',$data);
    }

    public function monthlyC()
    {
        $data['menu2'] = 'Overtime';
        $data['menu'] = 'Monthly Overtime Control';
        $data['section'] = $this->budget_model->get_name_cc2();
        $this->load->view("ot_monthly_control", $data);
    }

    public function ot_m()
    {
        $data['menu2'] = 'Overtime';
        $data['menu'] = 'OT Management By Section';
        $data['section'] = $this->budget_model->get_name_cc();
        $this->load->view("ot_management",$data);
    }

    public function overtime_form()
    {
        $data['dep'] = $this->home_model->get_dep_all();
        $data['id_doc'] = $this->over_model->get_id_doc();
        $data['menu2'] = 'Overtime';
        $data['menu'] = 'Overtime Data';
        $this->load->view("overtime_form3",$data);
    }

     public function overtime_edit($id_ot)
    {
        $data['dep'] = $this->home_model->get_dep_all();
        $data['isi'] = $this->over_model_new->ot_hr($id_ot);

        $isi2 = $this->over_model_new->ot_hr($id_ot);

        $data['sec'] = $this->over_model->get_sub_sec($isi2[0]->departemen);
        $data['sub_sec'] = $this->over_model->get_grup($isi2[0]->section);

        $data['menu2'] = 'Overtime';
        $data['menu'] = 'HR - Overtime';
        $this->load->view("overtime_edit",$data);
    }

    public function ot_graph()
    {
        $data['menu2'] = 'Overtime';
        $data['menu'] = 'Overtime Graph';

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
        $this->load->view("overtime_report",$data);
    }

    public function ot_report2()
    {
        $data['menu2'] = 'Overtime';
        $data['menu'] = 'OT Management By NIK';
        $this->load->view("overtime_report2",$data);
    }

    public function detailSPL($nik,$tgl)
    {
        $data['menu2'] = 'Overtime';
        $data['menu'] = 'ovrR2';
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
        $data['menu2'] = 'Employee';
        $data['menu'] = 'Employee Graph';
        $this->load->view("karyawan_graph", $data);
    }

    public function karyawan_coba()
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
        $data['menu'] = 'emp';
        $this->load->view("karyawan", $data);
    }

    public function user_QA()
    {
        $this->load->view("qa_user");
    }

    public function tanya()
    {
        $data['menu'] = 'qa';
        $this->load->view('qa',$data);
    }

    public function overtime_user()
    {
        $username = $this->session->userdata('nik');
        $data['dep'] = $this->over_model->get_dep($username);
        $data['menu2'] = 'ovrU';
        $data['menu'] = 'ovrU';
        $this->load->view('overtime_user2',$data);
    }

    public function report_GA()
    {
        $data['menu2'] = 'Overtime';
        $data['menu'] = 'GA - Report';
        $this->load->view('GAreport',$data);
    }

    public function karyawan_2()
    {
        $data['menu2'] = 'Employee';
        $data['menu'] = 'Employee2';
        $this->load->view('karyawan_2',$data);
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
        $data['menu2'] = 'Overtime';
        $data['menu'] = 'Overtime Data';
        $this->load->view('overtime2',$data);
    }

    public function presensi()
    {
        if (isset($_POST['tanggal']) || isset($_POST['nik']) || isset($_POST['nama']) || isset($_POST['shift'])) 
        {
            $newdata = array(
                'tanggal'  => $_POST['tanggal'],
                'nik3'     => $_POST['nik'],
                'nama' => $_POST['nama'],
                'shift' => $_POST['shift']
            );

            $this->session->set_userdata($newdata);
        }
        $data['menu2'] = 'Presence';
        $data['menu'] = 'Presence Data';
        $this->load->view('index',$data);
    }

    public function presensi_graph()
    {
        $data['persentase'] = $this->home_model->by_persentase();
        $data['persentase_tidakMasuk'] = $this->home_model->persentase_tidakMasuk();
        $data['kary'] = $this->karyawan_model->tot();
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
        $data['menu2'] = 'Employee';
        $data['menu'] = 'emp';
        $this->load->view('karyawan_form',$data);
    }

    public function ajax()
    {
        if ((isset($_SESSION['tanggal']) && $_SESSION['tanggal'] != "") || (isset($_SESSION['nik3']) && $_SESSION['nik3'] != "") || (isset($_SESSION['nama']) && $_SESSION['nama'] != "") || (isset($_SESSION['shift']) && $_SESSION['shift'] != "")) 
        {
            $tgl = $this->session->userdata('tanggal');
            $nik = $this->session->userdata('nik3');
            $nama = $this->session->userdata('nama');
            $shift = $this->session->userdata('shift');

            $list = $this->cari_model->get_data_presensi_cari($tgl, $nik, $nama, $shift);
            $tot = $this->cari_model->count_all($tgl, $nik, $nama, $shift);
            $filter = $this->cari_model->count_filtered($tgl, $nik, $nama, $shift);
        }
        else {
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
                //$row[] = $key->foto;
            $row[] = $key->namaKaryawan;
            $row[] = $key->namadev;
            $row[] = $key->namadep;
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

public function ajax_emp()
{
    if (isset($_SESSION['status']) || isset($_SESSION['grade']) || isset($_SESSION['dep']) || isset($_SESSION['pos'])) 
    {
        $status = $this->session->userdata('status');
        $grade = $this->session->userdata('grade');
        $dep = $this->session->userdata('dep');
        $pos = $this->session->userdata('pos');
        $list = $this->cari_karyawan_model->get_data_karyawan_cari($status,$grade,$dep,$pos);
        $tot = $this->cari_karyawan_model->count_all();
        $filter = $this->cari_karyawan_model->count_filtered($status,$grade,$dep,$pos);
    }
    elseif (isset($_SESSION['bulan'])) {
        $bulan = $this->session->userdata('bulan');
        $list = $this->karyawan_by_period_model->get_karyawan($bulan);
    }
    else {
        $list = $this->karyawan_model->get_data_karyawan();
        $tot = $this->karyawan_model->count_all();
        $filter = $this->karyawan_model->count_filtered();
    }

    $data = array();
    $i = 1;
    foreach ($list as $key) {
        $row = array();
        $row[] = $key->nik;
                //$row[] = $key->foto;
        $row[] = "<a style='cursor: pointer' onclick='ShowModal(".$i.")' data-toggle='modal' data-id='".$key->nik."' id='tes".$i."'>".$key->namaKaryawan."</a>";
        $row[] = $key->dep;
        $row[] = $key->group;
        $row[] = date("j M Y", strtotime($key->tanggalMasuk));
        $row[] = $key->statusKaryawan;
        $row[] = "<p class='text-center'><small class='label bg-green'>".$key->status." <i class='fa fa-check'></i> </small></p>";

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

// public function ajax_emp_by_nik($id)
// {
//             // echo $_GET['dataId'];
//     $list = $this->karyawan_model->get_data_karyawan_by_nik($id);
//     $data = array();
//     foreach ($list as $key) {
//         $row = array();
//         $row[] = $key->pin;
//         $row[] = $key->nik;
//         $row[] = $key->costCenter;
//         $row[] = $key->foto;
//         $row[] = $key->namaKaryawan;
//         $row[] = $key->dep;
//         $row[] = $key->group;
//         $row[] = $key->kode;
//         $row[] = date("d-m-Y", strtotime($key->tanggalMasuk));
//         $row[] = $key->jk;
//         $row[] = $key->statusKaryawan;
//         $row[] = $key->grade;
//         $row[] = $key->namaGrade;
//         $row[] = $key->jabatan;
//         $row[] = $key->statusKeluarga;
//         $row[] = $key->tempatLahir;
//         $row[] = $key->tanggalLahir;
//         $row[] = $key->alamat;
//         $row[] = $key->hp;
//         $row[] = $key->ktp;
//         $row[] = $key->rekening;
//         $row[] = $key->bpjstk;
//         $row[] = $key->jp;
//         $row[] = $key->bpjskes;
//         $row[] = $key->npwp;
//         $row[] = $key->status;

//         $data[] = $row;
//     }

//             //output to json format
//     echo json_encode($data);
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
    $array_items = array('tanggal' , 'nik3', 'nama', 'shift');
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
    $data['menu'] = 'emp';
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
    // elseif (isset($_SESSION['bulan'])) {
    //     $bulan = $this->session->userdata('bulan');
    //     $list = $this->karyawan_by_period_model->get_karyawan($bulan);
    // }
    else {
        $list = $this->karyawan_model->get_data_karyawan_coba();
        $tot = $this->karyawan_model->count_all();
        $filter = $this->karyawan_model->count_filtered();
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

}
