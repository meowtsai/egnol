<?php 

require_once 'excel/PHPExcel.php';  
require_once 'excel/PHPExcel/IOFactory.php';  
  
class Php_excel
{  
    public function load_data($filename) 
    {  
        try {  
            if (!file_exists($filename)) {  
                return false;  
            }  
            //chmod($filename, 0750);linux下改变文件权限  
            $filetype = $this->get_file_type($filename);
             
            //根据文件类型读取excel文件  
            if ($filetype == "xlsx") {  
                $objReader = PHPExcel_IOFactory::createReader('Excel2007');  
                $objReader->setReadDataOnly(true);  
            } elseif ($filetype == "xls") {  
                $objReader = PHPExcel_IOFactory::createReader('Excel5');  
                $objReader->setReadDataOnly(true);  
            } else {  
                return false;  
            }  
            
            $mems = NULL;  
            $objPHPExcel = $objReader->load($filename); 
            $objWorksheet = $objPHPExcel->getActiveSheet();  
            $highestRow = $objWorksheet->getHighestRow(); // e.g. 10  
            $highestColumn = $objWorksheet->getHighestColumn(); // e.g 'F'  
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); // e.g. 5  
            for ($row = 1; $row <= $highestRow; $row++) {  
                for ($col = 0; $col < $highestColumnIndex; $col++) {  
                    $mem[$col] = trim($objWorksheet->getCellByColumnAndRow($col, $row)->getValue());  
                }  
                $mems[$row - 1] = $mem;  
            }  
            return $mems;  
        } catch (Exception $e) {  
            echo 'EXCEL ERROR:' . $e->getMessage();  
            $errText = "Read excel error:Please retry later!";  
            return $errText;  
        }  
    }  
      
    /** 
     * 获取文件类型 
     * @param $filenamePath 文件路径或者文件名 
     */  
    private function get_file_type($filenamePath){  
        if (!$filenamePath){  
            return false;  
        }  
        $filenameArr = explode('/', $filenamePath);  
        $count = count($filenameArr);  
        $filename = $filenameArr[$count-1];  
          
        $filetypeArr = explode('.', $filename);  
        $count = count($filetypeArr);  
        $filetype = $filetypeArr[$count-1];  
        return $filetype;     
    }  
}  