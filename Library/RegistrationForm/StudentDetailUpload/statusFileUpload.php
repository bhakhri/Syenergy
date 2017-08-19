<?php
//-------------------------------------------------------
// THIS FILE IS USED TO upload student Detail
//
// Author : Jaineesh
// Created on : (14.11.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	set_time_limit(0);
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(BL_PATH . "/RegistrationForm/FileUploadManager.inc.php");
	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
	define('MODULE','UploadStudentStatus');
	define('ACCESS','add');
	UtilityManager::ifNotLoggedIn(true);
	global $sessionHandler;

	//require_once(MODEL_PATH . "/ScStudentManager.inc.php");
	//$studentManager = StudentManager::getInstance();

	require_once(MODEL_PATH . "/RegistrationForm/ScStudentStatusUpload.inc.php");
	$studentStatusManager = StudentStatusUpload::getInstance();

	$fileObj = FileUploadManager::getInstance('studentStatusUploadFile');
	$filename = $fileObj->tmp;

	if($filename == '') {
       echo ('<script type="text/javascript">alert("Please Select File");</script>');
       die;
    }

    if($fileObj->fileExtension != 'xls') {
       echo ('<script type="text/javascript">alert("Please Select Excel File");</script>');
       die;
    }

    require_once(BL_PATH . "/reader.php");
    $data = new Spreadsheet_Excel_Reader();
    $data->setOutputEncoding('CP1251');
    $data->read($filename);

	$m=0;
    $sheetNameArray = array();
    while(isset($data->boundsheets[$m]['name'])) {
       $sheetNameArray[] =  $data->boundsheets[$m]['name'];
       $m++;
    }
    
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId = $sessionHandler->getSessionVariable('SessionId');


	$str = '';
	$totalRecordCounter = 0;
	$inconsistenciesArray = array();
	$successArray = array();

    $totalClassStudents = 0;
    /*  $getClassId = trim($REQUEST_DATA['degree']);
        if($getClassId == '') {
   	      echo ('<script type="text/javascript">alert("Please select class");</script>');
	      die;
	    }
        
	    $conditions = "WHERE cl.classId = ".$getClassId;
	    $classArray = $studentManager->getClassInfo($conditions);
	    $className = $classArray[0]['className'];
	*/		
    
    
    $rollNoArray = array();
    $univRollNoArray = array();	
    $str='';   
    foreach($sheetNameArray as $sheetIndex=>$value) {
		/*
        for ($i = 1; $i <= $data->sheets[$sheetIndex]['numRows']; $i++) {
			if ($data->sheets[$sheetIndex]['cells'][1][1] != "[Sr.No.]") {
				$inconsistenciesArray[] = "Data has not entered in given format";
				continue;
			}
		}
        echo($data->sheets[$sheetIndex]['numRows']);
        */
		for ($i = 2; $i <= $data->sheets[$sheetIndex]['numRows']; $i++) {
		    $srNo = trim($data->sheets[$sheetIndex]['cells'][$i][1]);
		    $univRollNo = trim($data->sheets[$sheetIndex]['cells'][$i][2]);
            $scholarType = trim($data->sheets[$sheetIndex]['cells'][$i][3]);
		    
            
            if($srNo=='') {
              continue;  
            }
            
		    if($univRollNo=='') {
		       $inconsistenciesArray[] = "Please mention University Roll No. of Student for selected class at Sr. No.'$srNo'";
		       continue;
		    }

            $ttFind='';
            for($k=0;$k<count($univRollNoArray);$k++) {
               if($univRollNoArray[$k]==$univRollNo) {
                  $ttFind='1'; 
                  break;
               }  
            }
            
		    if($ttFind=='1'){
		       $inconsistenciesArray[] = "Duplicate University Roll No for selected class at Sr. No.'$srNo'";
		       continue;
		    }          
		    
		    // Fetch StudentId	
	 	    $condition = "WHERE universityRollNo = '".$univRollNo."'";
	  	    $studentArray = $studentStatusManager->validateStudent($condition);
		    $studentId = $studentArray[0]['studentId'];
	        $getClassId = $studentArray[0]['classId'];  
            
            if(count($studentArray)==0){
		       $inconsistenciesArray[] = "University Roll No. doesn't exist at Sr. No.'$srNo'";
		       continue;
		    }	

		    if($scholarType=='') {
			   $inconsistenciesArray[] = "Please mention Scholar Type of Student for selected class at Sr. No.'$srNo'";
			   continue;
		    }

		    if($scholarType!='' && ($scholarType!='D' && $scholarType!='H')) {
			   $inconsistenciesArray[] = "Please mention appropiate Scholar Type at Sr. No.'$srNo'";
			   continue;
		    }

            $check=$studentStatusManager->checkExistingRecordNew($getClassId,$studentId);
		    if($check[0]['cnt']>0) {
		       $inconsistenciesArray[] = "Record of ".$univRollNo." at Sr. No.".$srNo." already exist";
		       continue;
		    }
		    
		    if ($scholarType=='D') {
			  $dayScholar=1;	
			  $hostler=0;
	  	    }
		    else if ($scholarType=='H') {
			  $hostler=1;
			  $dayScholar=0;
	 	    }
            if($str!='') {
              $str .=",";    
            } 
		    $str .= "('$getClassId','$studentId','$univRollNo','$dayScholar','$hostler')";

		    $univRollNoArray[] = $univRollNo;
		    $totalClassStudents++;
		 }
        }
        $csvData = '';
        if(SystemDatabaseManager::getInstance()->startTransaction()) {
	         if($str!='') { 
	            $returnInsertStudentStatus= $studentStatusManager->addStudentStatusInTransaction($str);
	         }
	         if(SystemDatabaseManager::getInstance()->commitTransaction()) {
		        if($returnInsertStudentStatus === false) {
		           $inconsistenciesArray = "Error while saving student detail";
                   die; 
	            }
		        else {
                  if($totalClassStudents != '0') {  
	  	            $csvData .= "Data saved successfully for $totalClassStudents students \n";
                  }
                }
             }
        }



	$i = 1;
	foreach($inconsistenciesArray as $key=>$record) {
	  $csvData .= "$i. $record\r\n";
	   $i++;
	}
	$csvData = trim($csvData);
	$fileName = "Upload Student Information.txt";
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header("Pragma: hack"); // WTF? oh well, it works...
	header("Content-Type: application/octet-stream");
	header("Content-Length: " .strlen($csvData));
	header('Content-Disposition: attachment; filename="'.$fileName.'"');
	header("Content-Transfer-Encoding: text\n");
	echo $csvData;
	die;
		    
             

