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
        $this->load->model('over_model');
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

            $data['report1'] = $this->home_model->report1_by_tgl($bln);
            $data['report2'] = $this->home_model->report2_by_tgl($bln);
            $data['report3'] = $this->home_model->report3_by_tgl($bln);
        }
        else {
            $data['report1'] = $this->home_model->report1();
            $data['report2'] = $this->home_model->report2();
            $data['report3'] = $this->home_model->report3();
        }
            // $data['laporan'] = $this->home_model->laporan();
        $this->load->view('report', $data);
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
        $this->load->view('absen');
    }

    public function absensi_graph()
    {
        $this->load->view("absensi_graph");
    }

    public function client()
    {
        $this->load->view("client");
    }

    public function overtime_form()
    {
        $data['dep'] = $this->over_model->get_dep();
        $data['id_doc'] = $this->over_model->get_id_doc();
        $this->load->view("overtime_form",$data);
    }

    public function ot_graph()
    {
        $this->load->view("ot_graph");
    }

    public function ot_report()
    {
        $this->load->view("overtime_report");
    }

    public function karyawan_graph()
    {
        $data['status'] = $this->karyawan_model->by_status();
        $data['gender'] = $this->karyawan_model->by_gender();
        $data['grade'] = $this->karyawan_model->by_grade();
        $data['kode'] = $this->karyawan_model->by_kode();
        $data['posisi'] = $this->karyawan_model->by_posisi();
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
        $this->load->view("karyawan");
    }

    public function detail()
    {
        $this->load->view("detailPerHari");
    }

    public function user_QA()
    {
        $this->load->view("qa_user");
    }

    public function tanya()
    {
        $this->load->view('qa');
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
        $this->load->view('overtime');
    }

    public function presensi()
    {
        if (isset($_POST['tanggal']) || isset($_POST['nik']) || isset($_POST['nama']) || isset($_POST['shift'])) 
        {
            $newdata = array(
                'tanggal'  => $_POST['tanggal'],
                'nik'     => $_POST['nik'],
                'nama' => $_POST['nama'],
                'shift' => $_POST['shift']
            );

            $this->session->set_userdata($newdata);
        }
        $this->load->view('index');
    }

    public function presensi_graph()
    {
        $data['persentase'] = $this->home_model->by_persentase();
        $data['persentase_tidakMasuk'] = $this->home_model->persentase_tidakMasuk();
        $data['kary'] = $this->karyawan_model->tot();
        $this->load->view("presensi_graph", $data);
    }

    public function karyawan_t()
    {
        $data['dev'] = $this->karyawan_model->get_dev();
        $data['grade'] = $this->karyawan_model->get_grade();
        $data['jabatan'] = $this->karyawan_model->get_jabatan();
        $data['kode'] = $this->karyawan_model->get_kode();
        $this->load->view('karyawan_form',$data);
    }

    public function ajax()
    {
        if ((isset($_SESSION['tanggal']) && $_SESSION['tanggal'] != "") || (isset($_SESSION['nik']) && $_SESSION['nik'] != "") || (isset($_SESSION['nama']) && $_SESSION['nama'] != "") || (isset($_SESSION['shift']) && $_SESSION['shift'] != "")) 
        {
            $tgl = $this->session->userdata('tanggal');
            $nik = $this->session->userdata('nik');
            $nama = $this->session->userdata('nama');
            $shift = $this->session->userdata('shift');

            $list = $this->cari_model->get_data_presensi_cari($tgl, $nik, $nama, $shift);

        }
        else {
            $list = $this->home_model->get_data_persensi();
        }

        $data = array();
        foreach ($list as $key) {
            $row = array();
            $row[] = date('j M Y', strtotime($key->tanggal));
            $row[] = $key->nik;
            $row[] = $key->namaKaryawan;
            if ($key->masuk == 0) 
                $row[] = '';
            else
                $row[] = $key->masuk;
            if ($key->keluar == 0) 
                $row[] = '';
            else
                $row[] = $key->keluar;
            $row[] = $key->shift;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->home_model->count_all(),
            "recordsFiltered" => $this->home_model->count_filtered(),
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

        $data = array();
        foreach ($list as $key) {
            $row = array();
            $row[] = date('j M Y', strtotime($key->tanggal));
            $row[] = $key->nik;
            $row[] = $key->namaKaryawan;
            if ($key->masuk == 0) 
                $row[] = '';
            else
                $row[] = $key->masuk;
            if ($key->keluar == 0) 
                $row[] = '';
            else
                $row[] = $key->keluar;
            $row[] = $key->shift;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
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
        $tot = $this->cari_absen_model->count_all();
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

public function ajax_emp_by_nik($id)
{
            // echo $_GET['dataId'];
    $list = $this->karyawan_model->get_data_karyawan_by_nik($id);
    $data = array();
    foreach ($list as $key) {
        $row = array();
        $row[] = $key->pin;
        $row[] = $key->nik;
        $row[] = $key->costCenter;
        $row[] = $key->foto;
        $row[] = $key->namaKaryawan;
        $row[] = $key->dep;
        $row[] = $key->group;
        $row[] = $key->kode;
        $row[] = date("d-m-Y", strtotime($key->tanggalMasuk));
        $row[] = $key->jk;
        $row[] = $key->statusKaryawan;
        $row[] = $key->grade;
        $row[] = $key->namaGrade;
        $row[] = $key->jabatan;
        $row[] = $key->statusKeluarga;
        $row[] = $key->tempatLahir;
        $row[] = $key->tanggalLahir;
        $row[] = $key->alamat;
        $row[] = $key->hp;
        $row[] = $key->ktp;
        $row[] = $key->rekening;
        $row[] = $key->bpjstk;
        $row[] = $key->jp;
        $row[] = $key->bpjskes;
        $row[] = $key->npwp;
        $row[] = $key->status;

        $data[] = $row;
    }

            //output to json format
    echo json_encode($data);
}

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

public function ajax_over_section()
{
    $id = $_POST['id'];
    $list = $this->karyawan_model->getSection($id);
    $data = array();

    $row1 = array();
    $row1[] = "";
    $row1[] = "Select Section";

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
    $array_items = array('tanggal' , 'nik', 'nama', 'shift');
    $this->session->unset_userdata($array_items);
    redirect('home/presensi');
}

public function sess_destroy2()
{
    $array_items = array('tanggal2' , 'nik2', 'nama2', 'shift2');
    $this->session->unset_userdata($array_items);
    redirect('home/absen');
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
    $this->load->view("karyawan_coba");
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
    elseif (isset($_SESSION['bulan'])) {
        $bulan = $this->session->userdata('bulan');
        $list = $this->karyawan_by_period_model->get_karyawan($bulan);
    }
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
        $row[] = $key->namaGrade;
        $row[] = $key->jabatan;
        $row[] = $key->statusKeluarga;
        $row[] = $key->tempatLahir;
        $row[] = $key->tanggalLahir;
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
}
