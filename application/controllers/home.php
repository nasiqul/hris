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
    }

    public function index()
    {
        $data['report1'] = $this->home_model->report1();
        $data['report2'] = $this->home_model->report2();
        $data['report3'] = $this->home_model->report3();
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
        $data['absen'] = $this->absensi_model->by_absensi();
        $this->load->view("absensi_graph", $data);
    }

    public function client()
    {
        $this->load->view("client");
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

    public function karyawan()
    {
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
        $data['tot'] = $this->home_model->by_total_kehadiran();
        $data['persentase'] = $this->home_model->by_persentase();
        $data['kary'] = $this->karyawan_model->tot();
        $this->load->view("presensi_graph", $data);
    }

    public function ajax()
    {
        if (isset($_SESSION['tanggal']) || isset($_SESSION['nik']) || isset($_SESSION['nama']) || isset($_SESSION['shift'])) 
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
            $row[] = date("d-m-Y", strtotime($key->tanggal));
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


    public function ajax_absensi()
    {
        if (isset($_SESSION['tanggal2']) || isset($_SESSION['nik2']) || isset($_SESSION['nama2']) || isset($_SESSION['shift2'])) 
        {
            $tgl = $this->session->userdata('tanggal2');
            $nik = $this->session->userdata('nik2');
            $nama = $this->session->userdata('nama2');
            $absen = $this->session->userdata('shift2');

            $list = $this->cari_absen_model->get_data_absensi_cari($tgl, $nik, $nama, $absen);
        }
        else {
            $list = $this->absensi_model->get_data_absensi();
        }

        $data = array();
        foreach ($list as $key) {
            $row = array();
            $row[] = date("d-m-Y", strtotime($key->tanggal));
            $row[] = $key->nik;
            $row[] = $key->namaKaryawan;
            $row[] = $key->shift;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->absensi_model->count_all(),
            "recordsFiltered" => $this->absensi_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }    

    public function ajax_emp()
    {
        $list = $this->karyawan_model->get_data_karyawan();
        $data = array();
        $i = 1;
        foreach ($list as $key) {
            $row = array();
            $row[] = $key->nik;
            //$row[] = $key->foto;
            $row[] = "<a style='cursor: pointer' onclick='ShowModal(".$i.")' data-toggle='modal' data-id='".$key->nik."' id='tes".$i."'>".$key->namaKaryawan."</a>";
            $row[] = $key->dep;
            $row[] = $key->group;
            $row[] = date("d-m-Y", strtotime($key->tanggalMasuk));
            $row[] = $key->statusKaryawan;

            $data[] = $row;
            $i++;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->karyawan_model->count_all(),
            "recordsFiltered" => $this->karyawan_model->count_filtered(),
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
        // $items = array('tanggal' => '', 'nik' => '', 'nama' => '', 'shift' => '');
        // $this->session->unset_userdata($items);
        session_destroy();
        redirect('home/presensi');
    }

    public function sess_destroy2()
    {
        session_destroy();
        redirect('home/absen');
    }
}
