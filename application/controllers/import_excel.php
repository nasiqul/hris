<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class import_excel extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->helper('file');
		// $this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
        $this->load->model('export_model');
        $this->load->model('home_model');
        $this->load->model('over_model_new');
        $this->load->model('over_model');
    }

    public function upload(){
      $fileName = time().$_FILES['file']['name'];

        $config['upload_path'] = './app/excel/'; //buat folder dengan nama assets di root folder
        $config['file_name'] = $fileName;
        $config['allowed_types'] = 'csv';
        $config['max_size'] = 10000;

        $this->load->library('upload');
        $this->upload->initialize($config);

        if(! $this->upload->do_upload('file'))
        	echo "<script>alert('GAGAL')</script>";


        $media = $this->upload->data('file');
        $inputFileName = './app/excel/'.$fileName;

        try {
        	$inputFileType = IOFactory::identify($inputFileName);
        	$objReader = IOFactory::createReader($inputFileType);
        	$objPHPExcel = $objReader->load($inputFileName);
        } catch(Exception $e) {
        	die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }

        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

            for ($row = 2; $row <= $highestRow; $row++){  //  Read a row of data into an array                 
            	$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
            		NULL,
            		TRUE,
            		FALSE);

                //Sesuaikan sama nama kolom tabel di database

            	$time = strtotime($rowData[0][1]);

            	$newformat = date('Y-m-d',$time);

            	$data = array(
            		"id_cc"=> $rowData[0][0],
            		"period"=> $newformat,
            		"budget"=> $rowData[0][2]
            	);

                //sesuaikan nama dengan nama tabel

                $query = "CALL import_excel(".$rowData[0][0].",'".$newformat."',".$rowData[0][2].")";
                $this->db->query($query);
                delete_files($config['upload_path']);

            }
            redirect('budget');
        }    

        public function upload3()
        { 
            $file = $_FILES['file']['tmp_name'];
            $data = file_get_contents($file);

            $rows = explode("\r\n", $data);

            // $row = explode("\t", $rows);
            $first = explode("\t", $rows[0]);
            $date =  date('Y-m-d' , strtotime($first[2]));

            $this->export_model->deletePresensi($date);

            foreach ($rows as $row)
            {
                if (strlen($row) > 0) {
                    $row = explode("\t", $row);
                    $shift2 = trim(preg_replace('/\s\s+/', ' ', $row[5]));
                    $daily_data = Array(
                        'pin' => $row[0],
                        'tgl'    => date('Y-m-d' , strtotime($row[2])),
                        'nik' => $row[1],
                        'masuk'       => $row[3],
                        'keluar'       => $row[4],
                        'shift'       => $shift2,
                        'hari'       => date('w' , strtotime($row[2]))
                    );

                    $this->export_model->export_presensi($daily_data);
                    // print_r($daily_data);
                }
            }

            // $this->updatedataover($date);

            $this->session->set_flashdata('status', 'sukses');
            redirect("home/presensi");

        }

        public function updatedataover($tgl)
        {
            $list = $this->over_model_new->tabel_konfirmasi($tgl);

            $data = array();
            foreach ($list as $key) {
                $cost = 0;  
                $budget = 0;
                if ($key->jml_nik == "1") {

                    if ($key->jam_plan != "0") {
                        $where = array(
                            'nik' => $key->nik,
                            'id_ot' =>$key->id
                        );

                        $jam = $key->jam_plan;

                        $this->over_model->update_data_over($where,'over_time_member');
                        $this->over_model->update_data_final($where,'over_time_member',$key->final2);

                    }
                    else {
                        $jam = "0";
                    }

                    $nik = $key->nik;
                    $tgl = $key->tanggal;

                    $this->over_model->change_over($nik, $tgl, $jam);

                }

            // echo json_encode($s);
            }

        }

        protected function alert($text) {
            return "<script> alert('".$text."'); </script>";
        }

    }

    ?>