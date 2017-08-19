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
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');   
    $sessionId = $sessionHandler->getSessionVariable('SessionId');   
    
    
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

    ?>
      <script language="javascript">
         //parent.dataPassed(divHide());
      </script>
   <?php  
    echo '<script language="javascript"> parent.divHide(); </script>';  
    
    
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

    // Sheet Count
	while(isset($data->boundsheets[$m]['name'])) {
		$sheetNameArray[] =  $data->boundsheets[$m]['name'];
		$m++;
	}

    
	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
    //$candidateManager->emptyData('adm_application_form');
    //$candidateManager->emptyData('adm_candidate_program_preference');
	if(SystemDatabaseManager::getInstance()->startTransaction()) {
		foreach($sheetNameArray as $sheetIndex => $sheet) {              
            $totalRows = $data->sheets[$sheetIndex]['numRows'] ;
    		for ($i = 2; $i <= $totalRows; $i++) {
                $temp=0;
                if($i==2) {
                    if (strtoupper(trim($data->sheets[$sheetIndex]['cells'][1][2])) != 'FIRST_NAME') {
                        $inconsistenciesArray[] = "Data format incorrect for 'Candidate Details-FIRST_NAME' ";
                        continue;
                    }
                    if (strtoupper(trim($data->sheets[$sheetIndex]['cells'][1][3]))  != 'LAST_NAME') {
                        $inconsistenciesArray[] = "Data format incorrect for 'Candidate Details-LAST_NAME' ";
                        continue;
                    }
                }
              //Fetch Data from Excel Sheet
              /* $studentName = strtoupper(trim($data->sheets[$sheetIndex]['cells'][$i][1]));
                $fetchdata['candidateName'] = $studentName;
                
                $pos =  instr(" ",$studentName); 
                if($pos==0) {
                  $fetchdata['firstName']   = $studentName;
                  $fetchdata['lastName']    = '';
                }
                else {
                  $fetchdata['firstName']   = trim(substr($studentName,0,$pos-1));
                  $len = strlen($studentName);
                  $fetchdata['lastName']    = trim(substr($studentName,$pos,$len));
                }
               */ 
                $srNo = strtoupper(trim($data->sheets[$sheetIndex]['cells'][$i][1]));  
                $fetchdata['firstName'] = strtoupper(trim($data->sheets[$sheetIndex]['cells'][$i][2]));  
                $fetchdata['lastName']  = strtoupper(trim($data->sheets[$sheetIndex]['cells'][$i][3]));  
                
                if($fetchdata['firstName'] =='') {
                   $inconsistenciesArray[] = "Data format incorrect for 'SR_NO [$srNo]' :  [FIRST_NAME] ";
                   $temp=1; 
                   continue;
                }
                
                $val = strtoupper(trim($data->sheets[$sheetIndex]['cells'][$i][5]));
                if($val=='F' || $val=='FATHER') {
                  $fetchdata['fatherName']  = strtoupper(trim($data->sheets[$sheetIndex]['cells'][$i][4]));
                }
                else if($val=='M' || $val=='MOTHER') { 
                  $fetchdata['motherName']   = strtoupper(trim($data->sheets[$sheetIndex]['cells'][$i][4]));
                }
                else if($val=='G' || $val=='GUARDIAN') {
                  $fetchdata['guardianName']   = strtoupper(trim($data->sheets[$sheetIndex]['cells'][$i][4]));
                }
                else {
                  $fetchdata['fatherName']  = strtoupper(trim($data->sheets[$sheetIndex]['cells'][$i][4]));
                }
                
               /* 
                $fetchdata['quotaId'] = NULL; 
                if(strtoupper(trim($data->sheets[$sheetIndex]['cells'][$i][6]))!='') {  
                    $quotaName = strtoupper(trim($data->sheets[$sheetIndex]['cells'][$i][6]));
                    require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
                    $returnValue = CommonQueryManager::getInstance()->getQuota('quotaName',"WHERE UCASE(TRIM(quotaAbbr)) = '".add_slashes($quotaName)."'");
                    if($returnValue === false) {
                       $fetchdata['quotaId'] = NULL;     
                    }
                    else {
                       $fetchdata['quotaId'] = $returnValue[0]['quotaId'];
                    }
                }
                else {
                     $fetchdata['quotaId'] = NULL;
                }
                */
                
                $studentGender = $data->sheets[$sheetIndex]['cells'][$i][6];       //Status for Hostel Facility
                if(strtoupper(trim($studentGender)) == 'F' || strtoupper(trim($studentGender)) == 'FEMALE' ) {
                   $fetchdata['studentGender'] = 'F';
                } 
                else if(strtoupper(trim($studentGender)) == 'M' || strtoupper(trim($studentGender)) == 'MALE' ) {
                   $fetchdata['studentGender'] = 'M';
                } 
              
                $dateOfBirth = $data->sheets[$sheetIndex]['cells'][$i][7];
                if(strlen($dateOfBirth) == 6 ) {
                   $dateOfBirth = substr($dateOfBirth,0,1) . "-" . substr($dateOfBirth,2,1) . "-" . substr($dateOfBirth,4,2);    
                } else {
                   $dateOfBirth = substr($dateOfBirth,0,2) . "-" . substr($dateOfBirth,3,2) . "-" . substr($dateOfBirth,6,2);  
                }
                $fetchdata['dateOfBirth'] = date('Y-m-d',strtotime($dateOfBirth));
               
               
                // Duplicate Check :   Comp. Exam. Roll No. 
                $fetchdata['compExamRollNo']  = trim($data->sheets[$sheetIndex]['cells'][$i][8]);
                if($fetchdata['compExamRollNo']!='') {
                    require_once(MODEL_PATH."/StudentManager.inc.php"); 
                    //getSingleField($table, $field, $conditions='') 
                    $returnValue2 = StudentManager::getInstance()->getSingleField('`student_enquiry`',"compExamRollNo","WHERE ucase(trim(compExamRollNo)) = '".add_slashes($fetchdata['compExamRollNo'])."'");
                    if (count($returnValue2) > 0 ) {
                      $inconsistenciesArray[] = "'COMP_ROLL [".$fetchdata['compExamRollNo']."]  doesn't exist 'SR_NO [$srNo]'";   
                      $temp=1;  
                      continue;
                    }
                }
                else {
                   $inconsistenciesArray[] = "Data format incorrect for 'SR_NO [$srNo]' :  [COMP_ROLL] "; 
                   $temp=1; 
                   continue;
                }
                
                $fetchdata['studentMobileNo'] = trim($data->sheets[$sheetIndex]['cells'][$i][9]);
                if($fetchdata['studentMobileNo']=='') {
                   $inconsistenciesArray[] = "Data format incorrect for 'SR_NO [$srNo]' :  [STUD_MOBILENO] "; 
                   $temp=1; 
                   continue;
                }
                $fetchdata['fatherMobileNo'] = trim($data->sheets[$sheetIndex]['cells'][$i][10]);
                
                $hostel = $data->sheets[$sheetIndex]['cells'][$i][11];       //Status for Hostel Facility
                if(strtoupper(trim($hostel)) == 'N' || strtoupper(trim($hostel)) == 'NO' ) {
                   $fetchdata['hostelFacility'] = 0;
                } 
                else if(strtoupper(trim($hostel)) == 'Y' || strtoupper(trim($hostel)) == 'YES' ) {
                   $fetchdata['hostelFacility'] = 1;
                } 
                else {
                   $fetchdata['hostelFacility'] = 0;
                }
                
                $fetchdata['studentEmail'] = strtoupper(trim($data->sheets[$sheetIndex]['cells'][$i][12])); 
                if($fetchdata['studentEmail'] == '') {
                   $fetchdata['studentEmail'] = '';      
                }       
                
                global $results;
                $fetchdata['compExamBy'] = strtoupper(trim($data->sheets[$sheetIndex]['cells'][$i][13])); 
                if($fetchdata['compExamBy'] == '') {
                   $fetchdata['compExamBy'] = '';      
                }       
                
                $fetchdata['compExamRank'] = strtoupper(trim($data->sheets[$sheetIndex]['cells'][$i][14])); 
                if($fetchdata['compExamRank'] == '') {
                   $fetchdata['compExamRank'] = '';      
                }       
                
                $query = "  `firstName`           = '".$fetchdata['firstName']."',
                            `lastName`            = '".$fetchdata['lastName']."',
                            `applicationNo`       = '".$fetchdata['applicationNo']."',
                            `enquiryDate`         = '".date('Y-m-d')."', 
                            `fatherName`          = '".$fetchdata['fatherName']."',
                            `motherName`          = '".$fetchdata['motherName']."',
                            `guardianName`        = '".$fetchdata['guardianName']."',  
                            `studentGender`       = '".$fetchdata['studentGender']."', 
                            `dateOfBirth`         = '".$fetchdata['dateOfBirth']."', 
                            `compExamRollNo`      = '".$fetchdata['compExamRollNo']."', 
                            `studentMobileNo`     = '".$fetchdata['studentMobileNo']."', 
                            `fatherMobileNo`      = '".$fetchdata['fatherMobileNo']."',
                            `hostelFacility`      = '".$fetchdata['hostelFacility']."',
                            `studentEmail`        = '".$fetchdata['studentEmail']."',
                            `compExamBy`          = '".$fetchdata['compExamBy']."',  
                            `compExamRank`        = '".$fetchdata['compExamRank']."',
                            `addedByUserId`       = '".$userId."',
                            `instituteId`         = '".$instituteId."',
                            `sessionId`           = '".$sessionId."'";
                if($temp==0) { 
                  $returnStatus = $candidateManager->addCandidate($query); 
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
 
 