<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class import_excel extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->helper('file');
		$this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
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
            	$insert = $this->db->insert("cost_center_budget",$data);
            	delete_files($config['upload_path']);

            }
            redirect('budget');
        }
    }

    ?>