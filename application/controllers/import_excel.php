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

                    $list_cuti = $this->over_model->get_cuti();

                    foreach ($list_cuti as $key2) {
                        if ($key2->absence_code == $shift2) {
                            $this->over_model->update_cuti($row[1], date('Y-m-d' , strtotime($row[2])));
                        }
                    }
                }
            }

            $this->updatedataover($date);

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

                        $this->over_model->update_data_final($where,'over_time_member',$jam);

                    }
                    else {
                        $jam = "0";
                    }

                    $nik = $key->nik;
                    $tgl = $key->tanggal;

                    $this->over_model->change_over_all($nik, $tgl, $jam);
                }
            }

        }

        protected function alert($text) {
            return "<script> alert('".$text."'); </script>";
        }

        public function exportOvertime($tgl){
            if ($tgl == 1) {
                $bulan = date('Y-m');
            } 
            else {
                $bulan = date('Y-m',strtotime('01-'.$tgl));
            }

        // $tgl = $_POST['tgl'];

        // Load plugin PHPExcel nya
            include APPPATH.'third_party/PHPExcel/PHPExcel.php';

        // Panggil class PHPExcel nya
            $excel = new PHPExcel();

        // Settingan awal fil excel
            $excel->getProperties()->setCreator('MIRAI')
            ->setLastModifiedBy('MIRAI')
            ->setTitle("Data MIRAI")
            ->setSubject("MIRAI")
            ->setDescription("Laporan MIRAI")
            ->setKeywords("Data Lembur");

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

        $excel->setActiveSheetIndex(0)->setCellValue('A1', "DATA LEMBUR"); // Set kolom A1 dengan tulisan "DATA SISWA"
        $excel->getActiveSheet()->mergeCells('A1:I1'); // Set Merge Cell pada kolom A1 sampai E1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

        // Buat header tabel nya pada baris ke 3
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "Tanggal"); // Set kolom A3 dengan tulisan 

        $excel->setActiveSheetIndex(0)->setCellValue('B3', "NIK"); // Set kolom A3 dengan tulisan "NO"
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "Nama Karyawan"); // Set kolom B3 dengan tulisan "NIS"
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "ID CC"); // Set kolom C3 dengan tulisan "NAMA"
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "CC"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "IN"); // Set kolom D3 dengan tulisan 
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "OUT"); // Set kolom D3 dengan tulisan 
        $excel->setActiveSheetIndex(0)->setCellValue('H3', "Jam"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
        $excel->setActiveSheetIndex(0)->setCellValue('I3', "Satuan"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('I3')->applyFromArray($style_col);

        // Panggil function view yang ada di SiswaModel untuk menampilkan semua data siswanya
        $isi = $this->over_model_new->export_over_time($bulan,'tgl');

        $numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
        foreach($isi as $data){ // Lakukan looping pada variabel siswa
            $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $data->tanggal);
            $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $data->nik);
            $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $data->namaKaryawan);
            $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $data->costCenter);
            $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $data->name);
            $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $data->in);
            $excel->setActiveSheetIndex(0)->setCellValue('G'.$numrow, $data->out);
            $excel->setActiveSheetIndex(0)->setCellValue('H'.$numrow, $data->jam);
            $excel->setActiveSheetIndex(0)->setCellValue('I'.$numrow, $data->satuan);
            
            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
            $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('G'.$numrow)->applyFromArray($style_row);

            
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
        header('Content-Disposition: attachment; filename="DataLembur'.$bulan.'.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        ob_end_clean();
        $write->save('php://output');

    }


    public function exportOvertime2($tgl){
            if ($tgl == 1) {
                $bulan = date('Y-m');
            } 
            else {
                $bulan = date('Y-m',strtotime('01-'.$tgl));
            }

        // $tgl = $_POST['tgl'];

        // Load plugin PHPExcel nya
            include APPPATH.'third_party/PHPExcel/PHPExcel.php';

        // Panggil class PHPExcel nya
            $excel = new PHPExcel();

        // Settingan awal fil excel
            $excel->getProperties()->setCreator('MIRAI')
            ->setLastModifiedBy('MIRAI')
            ->setTitle("Data MIRAI")
            ->setSubject("MIRAI")
            ->setDescription("Laporan MIRAI")
            ->setKeywords("Data Lembur");

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

        $excel->setActiveSheetIndex(0)->setCellValue('A1', "DATA LEMBUR ".$tgl); // Set kolom A1 dengan tulisan "DATA SISWA"
        $excel->getActiveSheet()->mergeCells('A1:F1'); // Set Merge Cell pada kolom A1 sampai E1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom A1

        // Buat header tabel nya pada baris ke 3
        $excel->setActiveSheetIndex(0)->setCellValue('A3', "NIK"); // Set kolom A3 dengan tulisan 

        $excel->setActiveSheetIndex(0)->setCellValue('B3', "Nama Karyawan"); // Set kolom B3 dengan tulisan "NIS"
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "ID CC"); // Set kolom C3 dengan tulisan "NAMA"
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "CC"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "Jam"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "Satuan");
        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);

        // Panggil function view yang ada di SiswaModel untuk menampilkan semua data siswanya
        $isi = $this->over_model_new->export_over_time($bulan,'nik');

        $numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
        foreach($isi as $data){ // Lakukan looping pada variabel siswa
            $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, $data->nik);
            $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, $data->namaKaryawan);
            $excel->setActiveSheetIndex(0)->setCellValue('C'.$numrow, $data->costCenter);
            $excel->setActiveSheetIndex(0)->setCellValue('D'.$numrow, $data->name);
            $excel->setActiveSheetIndex(0)->setCellValue('E'.$numrow, $data->jam);
            $excel->setActiveSheetIndex(0)->setCellValue('F'.$numrow, $data->satuan);
            
            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
            $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E'.$numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F'.$numrow)->applyFromArray($style_row);
            
            $numrow++; // Tambah 1 setiap kali looping
        }

        
        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

        // Set orientasi kertas jadi LANDSCAPE
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

        // Set judul file excel nya
        $excel->getActiveSheet(0)->setTitle("Data Lembur 2");
        $excel->setActiveSheetIndex(0);

        // Proses file excel
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="DataLemburKar'.$bulan.'.xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        ob_end_clean();
        $write->save('php://output');

    }

}

?>