<?php
//-------------------------------------------------------
// THIS FILE IS USED TO upload Candidate Details
//
// Author : Vimal Sharma
// Created on : 07-Feb-2009
// Copyright 2008-2009f: syenergy Technologies Pvt. Ltd.
//                      
//--------------------------------------------------------
    set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(BL_PATH . "/FileUploadManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','add');
    UtilityManager::ifNotLoggedIn();  
    UtilityManager::headerNoCache();  

    
    global $sessionHandler;
    $userId = $sessionHandler->getSessionVariable('UserId');
        
  
    $fileObj = FileUploadManager::getInstance('candidateUploadFile');
    $filename = $fileObj->tmp;
    
    if ($filename == '') {
        echo ('<script type="text/javascript">alert("Please select a file");</script>');      
        die;
    }
    
    if($fileObj->fileExtension != 'xls') {
      echo ('<script type="text/javascript">alert("Please select an excel file");</script>');
      die;
    }
    
   
    function inStr ($needle, $haystack, $start=0) {
      $needlechars = strlen($needle);                                //gets the number of characters in our needle
      for($i=$start; $i < strlen($haystack); $i++) {                //creates a loop for the number of characters in our haystack
        if(substr($haystack, $i, $needlechars) == $needle) {        //checks to see if the needle is in this segment of the haystack
          return ($i+1); //if it is return true
        }
      }
      return 0; //if not, return false
    }  
    
    require_once(MODEL_PATH . "/StudentEnquiryManager.inc.php");
    $candidateManager = StudentEnquiryManager::getInstance();

    require_once(BL_PATH . "/reader.php");
    $data = new Spreadsheet_Excel_Reader();
    $data->setOutputEncoding('CP1251');
    $data->read($filename);

    
	$inconsistenciesArray   = array(); 
    $sheetNameArray         = array();
	$m                      =0;
    $str                    = '';  
    
	while(isset($data->boundsheets[$m]['name'])) {
		$sheetNameArray[] =  $data->boundsheets[$m]['name'];
		$m++;
	}

	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
	if(SystemDatabaseManager::getInstance()->startTransaction()) {
		foreach($sheetNameArray as $sheetIndex => $sheet) {
			$totalRows = $data->sheets[$sheetIndex]['numRows'] ;
    		for ($i = 2; $i <= $totalRows; $i++) {
                $temp=0;
                if($i==2) {
                  if ($data->sheets[$sheetIndex]['cells'][1][2] != 'COMP_ROLL') {
                     $inconsistenciesArray[] = "Data format incorrect for 'Candidate Details' : [COMP_ROLL] ";
                     continue;
                   }
                   if ($data->sheets[$sheetIndex]['cells'][1][3] != 'COMP_EXAM_RANK') {
                     $inconsistenciesArray[] = "Data format incorrect for 'Candidate Details' : COMP_EXAM_RANK ";
                     continue;
                   }  
                }
                //Fetch Data from Excel Sheet
                $srNo = strtoupper(trim($data->sheets[$sheetIndex]['cells'][$i][1]));     
                $compExamRollNo  = trim($data->sheets[$sheetIndex]['cells'][$i][2]);
                $compExamRank    = trim($data->sheets[$sheetIndex]['cells'][$i][3]);
                
                if($compExamRollNo!='' && $compExamRank!='') {
                    require_once(MODEL_PATH."/StudentManager.inc.php"); 
                    //getSingleField($table, $field, $conditions='') 
                    $returnValue2 = StudentManager::getInstance()->getSingleField('`student_enquiry`',"compExamRollNo","WHERE ucase(trim(compExamRollNo)) = '".add_slashes($compExamRollNo)."'");
                    if (count($returnValue2) > 0 ) {
                        $query = "`compExamRollNo` = '".$compExamRollNo."', 
                                  `compExamRank`   = '".$compExamRank."'
                                  WHERE ucase(trim(compExamRollNo)) = '".add_slashes($compExamRollNo)."'";
                        if($temp==0) { 
                          $returnStatus = $candidateManager->updateCandidate($query); 
                        }
                    }
                    else {
                      $inconsistenciesArray[] = "'COMP_ROLL [$compExamRollNo]  doesn't exist 'SR_NO [$srNo]'";   
                      $temp=1;  
                      continue; 
                    }
                }
                else {
                   $msg =''; 
                   if($compExamRollNo=='') { 
                     $msg = "Data format incorrect for 'SR_NO [$srNo]' :  [COMP_ROLL] ";  
                   }
                   if($compExamRank=='') {
                      if($msg=='') {
                        $msg = "Data format incorrect for 'SR_NO [$srNo]' : [COMP_EXAM_RANK] ";   
                      }
                      else {
                         $msg .= " and [COMP_EXAM_RANK] ";    
                      }
                   }
                   $inconsistenciesArray[] = $msg; 
                   $temp=1; 
                   continue;
                }
            }
        }        
		 if (count($inconsistenciesArray) == 0) {
            if(SystemDatabaseManager::getInstance()->commitTransaction()) {
            ?>
                <script language="javascript">
                    parent.dataPassed("<?php echo ADMISSION_UPLOAD_DONE;?>");
                </script>
            <?php
            } else {
                echo ADMISSION_UPLOAD_FAILURE;
            }
        }
        else {
            if(SystemDatabaseManager::getInstance()->commitTransaction()) {
            ?>
                <script language="javascript">
                    parent.dataPassed("<?php echo ADMISSION_UPLOAD_DONE;?>");
                </script>
            <?php
            } else {
                echo ADMISSION_UPLOAD_FAILURE;
            }            
           
            echo '<script language="javascript"> parent.divShow(); </script>';     
            
            $csvData    = '';
            $i          = 0;
            $temp=0;
            foreach($inconsistenciesArray as $key=>$record) {
              if($i==0) {
                $csvData .= "Candidate Inconsistencies List Below:\r\n \r\n";  
              }  
              $csvData .= "   $record\r\n";
              $i++;
              $temp=1;
            }
            
            if($temp==1) {
                $csvData    = trim($csvData);
                $fileName   = "Candidate_Inconsistencies.txt";
                //@unlink($fileName);
                ob_end_clean();
                header("Cache-Control: public, must-revalidate");
                header("Pragma: hack"); // WTF? oh well, it works...
                header("Content-Type: application/octet-stream");
                header("Content-Length: " .strlen($csvData));
                header('Content-Disposition: attachment; filename="'.$fileName.'"');
                header("Content-Transfer-Encoding: text\n");
                echo $csvData;
                die;
           }
        }
    } else {
        echo ADMISSION_UPLOAD_FAILURE;
    } 
    
     ?>
     <script language="javascript">
        //parent.dataPassed(divShow());
     </script>
 ?>
 