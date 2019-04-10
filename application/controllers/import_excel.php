<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class import_excel extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->helper('file');
		// $this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
        $this->load->model('export_model');
        $this->load->model('home_model');
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

        }

        public function export_excel_presensi(){

            $bulan = $_POST['tgl'];
            $tgl = date('Y-m-d',strtotime('01-'.$_POST['tgl']));
            $lastDay = date('t',strtotime($tgl));

        // Load plugin PHPExcel nya
            include APPPATH.'third_party/PHPExcel/PHPExcel.php';

        // Panggil class PHPExcel nya
            $excel = new PHPExcel();

        // Settingan awal fil excel
            $excel->getProperties()->setCreator('MIRAI')
            ->setLastModifiedBy('MIRAI')
            ->setTitle("Data Presensi MIRAI")
            ->setSubject("MIRAI")
            ->setDescription("Laporan Presensi MIRAI")
            ->setKeywords("Data Presensi");

        // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
            $style_col = array(
            'font' => array('bold' => true), // Set font nya jadi bold
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
        );

        // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
            $style_row = array(
                'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ),
                'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border top dengan garis tipis
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  // Set border right dengan garis tipis
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), // Set border bottom dengan garis tipis
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) // Set border left dengan garis tipis
            )
            );

        $excel->setActiveSheetIndex(0)->setCellValue('A1', "DATA PRESENSI BULAN ".$bulan); // Set kolom A1 dengan tulisan "DATA SISWA"
        $excel->getActiveSheet()->mergeCells('A1:K1'); // Set Merge Cell pada kolom A1 sampai E1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

        $siswa = $this->home_model->get_report_presensi($tgl);


        // Set kolom A3 dengan tulisan "NO"
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "PIN"); // Set kolom C3 dengan tulisan "NAMA"
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "NIK"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "Nama Karyawan"); // Set kolom E3 dengan tulisan "ALAMAT"
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "Tgl. Masuk"); 
        
        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);

        // $current_col = 0;
        $current_row = 3;
        $no = 1;

        for ($i=4; $i < $lastDay+4; $i++) {
            $excel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($i, $current_row, $no); 
            $excel->getActiveSheet()->getStyleByColumnAndRow($i, $current_row)->applyFromArray($style_col);
            $no++;
            // $current_col++;
        }

        $numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
        foreach($siswa as $data){ // Lakukan looping pada variabel siswa
            $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $data->pin);
            $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $data->nik);
            $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $data->namaKaryawan);
            $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $data->tanggalMasuk);
            
            
            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
            $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
            
            $numrow++; // Tambah 1 setiap kali looping
        }

        
        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Data Lembur");
        $excel->setActiveSheetIndex(0);

        // Proses file excel
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="DataLembur.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        ob_end_clean();
        $write->save('php://output');

    }

    protected function alert($text) {
        return "<script> alert('".$text."'); </script>";
    }

}

?>