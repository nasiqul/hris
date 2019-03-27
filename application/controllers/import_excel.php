<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class import_excel extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->helper('file');
		$this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
        $this->load->model('export_model');
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
            $file = rand(1000, 100000) . "-" . $_FILES['file']['name'];
            $file_loc = $_FILES['file']['tmp_name'];
            $file_size = $_FILES['file']['size'];
            $file_type = $_FILES['file']['type'];
            $folder = "./app/excel/";
            $location = $_FILES['file'];


        $new_size = $file_size / 1024; // new file size in KB
        $new_file_name = strtolower($file);
        $final_file = str_replace(' ', '-', $new_file_name); // make file name in lower case

        if (move_uploaded_file($file_loc, $folder . $final_file)) {

            //Prepare upload data
            $upload_data = Array(
                'file'  => $final_file,
                'type'  => $file_type,
                'size'  => $new_size
            );
            //Insert into tbl_uploads
            // $this->db->insert('daily_data2', $upload_data);

            $handle = fopen(base_url()."app/excel/$file", "r");

            if ($handle) {
                while (($line = fgets($handle)) !== false) {

                    $lineArr = explode("\t", "$line");
                    // instead assigning one by onb use php list -> http://php.net/manual/en/function.list.php
                    list($pin, $nik, $tgl , $masuk, $keluar, $shift) = $lineArr;

                    $shift2 = trim(preg_replace('/\s\s+/', ' ', $shift));

                    $daily_data = Array(
                        'tgl'    => date('m/d/Y' , strtotime($tgl)) ,
                        'nik' => $nik,
                        'masuk'       => $masuk,
                        'keluar'       => $keluar,
                        'shift'       => $shift2,
                        'hari'       => date('w' , strtotime($tgl)),
                    );

                    //Insert data
                    $this->export_model->export_presensi($daily_data);
                }
                fclose($handle);
                $path2 = base_url()."/app/excel/$file";
                // unlink($path2) or die("Couldn't delete file");
                redirect('home/presensi');
            }
        } else {
                //Alert error
            $this->alert('error while uploading file');
        }

    }

    protected function alert($text) {
        return "<script> alert('".$text."'); </script>";
    }

}

?>