<?php
//-------------------------------------------------------
// THIS FILE IS USED TO upload student Roll No.
//
// Author : Jaineesh
// Created on : (08.10.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//                      
//--------------------------------------------------------

	set_time_limit(0);
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(BL_PATH . "/FileUploadManager.inc.php");
	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
	define('MODULE','UploadStudentRollNo');
	define('ACCESS','add');
	UtilityManager::ifNotLoggedIn();
	global $sessionHandler;
	
    $fileObj = FileUploadManager::getInstance('studentRollNoUploadFile');
	$filename = $fileObj->tmp;

	if ($filename == '') {
		echo ('<script type="text/javascript">alert("Please Select File");</script>');
		die;
	}

	if ($fileObj->fileExtension != 'xls') {
		/*$inconsistenciesArray[] = "Incorrect file format. Please read Notes.";
		$csvData = '';
		$i = 1;
		foreach($inconsistenciesArray as $key=>$record) {
			$csvData .= "$i $record\r\n";
			$i++;
		}
		$csvData = trim($csvData);
		$fileName = "StudentRollNo_Inconsistencies.txt";
		ob_end_clean();
		header("Cache-Control: public, must-revalidate");
		header("Pragma: hack"); // WTF? oh well, it works...
		header("Content-Type: application/octet-stream");
		header("Content-Length: " .strlen($csvData));
		header('Content-Disposition: attachment; filename="'.$fileName.'"');
		header("Content-Transfer-Encoding: text\n");
		echo $csvData;*/
		echo ('<script type="text/javascript">alert("Please Select Excel File");</script>');
		die;
	}
	
	require_once(MODEL_PATH . "/StudentManager.inc.php");
	$studentManager = StudentManager::getInstance();

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
	$insertQueryArray = array();
	
	$roomNameBlockIdArray = array();
	$roomAbbrBlockIdArray = array();
	
	$totalClassStudents = 0;

	$getClassId = $REQUEST_DATA['classId'];
        $studentArray = array();

	if ($getClassId == '') {
		echo ('<script type="text/javascript">alert("Please select class");</script>');
		die;
	}

	if(SystemDatabaseManager::getInstance()->startTransaction()) {
		foreach($sheetNameArray as $sheetIndex=>$value) {
			
			/*if ($sheetIndex != '0') {
				$inconsistenciesArray[] = "Select only one sheet in Excel";
				continue;
			}*/

			for ($i = 1; $i <= $data->sheets[$sheetIndex]['numRows']; $i++) {

				if ($data->sheets[$sheetIndex]['cells'][1][1] != "Sr.No.") {
					$inconsistenciesArray[] = "Data has not entered in given format";
					continue;
				}
			}
			for ($i = 2; $i <= $data->sheets[$sheetIndex]['numRows']; $i++) {

				//if ($data->sheets[$sheetIndex]['cells'][$i][3] != "") {
				
				$srNo = $data->sheets[$sheetIndex]['cells'][$i][1];
				$srNo = trim($srNo);
				if (empty($srNo)) {
					break;
				}
				$rollNo = $data->sheets[$sheetIndex]['cells'][$i][2];
				$universityRollNo = $data->sheets[$sheetIndex]['cells'][$i][3];
				$studentName = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][4]));
				$fatherName = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][5]));
				$dateOfBirth = htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][6]));
                                $dateOfBirth = str_replace("/","-",$dateOfBirth);
                                $dateOfBirth = str_replace(".","-",$dateOfBirth);     
				$status =  htmlentities(trim($data->sheets[$sheetIndex]['cells'][$i][7]));
				$status = addslashes(strtolower($status));
     
				//$studentName = str_replace(' ','',$studentName); echo  $fatherName;die;
		                //$fatherName = str_replace(' ','',$fatherName); 
				if ($status == 'yes') {

					$totalClassStudents++;

					if (empty($rollNo)) {
						$inconsistenciesArray[] = "Please mention Roll No. of Student for selected class at Sr. No.'$srNo'";
						continue;
					}
					if (empty($studentName)) {
						$inconsistenciesArray[] = "Please mention Student Name of student for selected class at Sr. No.'$srNo'";
						continue;
					}
					if (empty($fatherName)) {
						$inconsistenciesArray[] = "Please mention Father Name of student for selected class at Sr. No.'$srNo'";
						continue;
					}
					if (empty($dateOfBirth)) {
						$inconsistenciesArray[] = "Please mention Date of Birth of student for selected class at Sr. No.'$srNo'";
						continue;
					}

                                   $dateOfBirth = str_replace('&nbsp;',"",$dateOfBirth);
					$conditions = "WHERE trim(concat(Ifnull(trim(s.firstName),''),' ',ifnull(trim(s.lastName),''))) LIKE '".trim($studentName)."' AND Ifnull(trim(s.fatherName),'') LIKE '".trim($fatherName)."' AND s.dateOfBirth = '".trim($dateOfBirth)."' AND s.classId = ".$getClassId." AND s.classId = cl.classId";

					$studentArray = $studentManager->getStudentDetailInfo($conditions);
                  
					
					$studentId = $studentArray[0]['studentId'];
					
					$getStudentName = $studentArray[0]['studentName'];
					$getFatherName = $studentArray[0]['fatherName'];
					$getDateOfBirth = $studentArray[0]['dateOfBirth'];
					$classId = $studentArray[0]['classId'];
					$className = $studentArray[0]['className'];
					//$studentRollNo = $studentArray[0]['rollNo'];

					$studentInstituteId = $studentArray[0]['instituteId'];
					$studentSessionId = $studentArray[0]['sessionId'];
					
					if (empty($getStudentName)) {
						$inconsistenciesArray[] = "Invalid Student '$studentName' of selected class at Sr. No.'$srNo'";
						continue;
					}

					elseif (empty($getFatherName)) {
						$inconsistenciesArray[] = "Invalid Father Name '$fatherName' of selected class at Sr. No.'$srNo'";
						continue;
					}

					elseif (empty($getDateOfBirth)) {
						$inconsistenciesArray[] = "Invalid Date of Birth '$dateOfBirth' of selected class at Sr. No.'$srNo'";
						continue;
					}


					elseif ($instituteId != $studentInstituteId) {
						$inconsistenciesArray[] = "Student '$studentName' does not belong to current institute";
						continue;
					}

					elseif ($sessionId != $studentSessionId) {
						$inconsistenciesArray[] = "Student '$studentName' does not belong to current session";
						continue;
					}

				
					$cond = "WHERE RollNo = '".$rollNo."' AND universityRollNo = '".$universityRollNo."'";

					$studentRollNoArray = $studentManager->getStudentRollNo($cond);
					
					$studentRollNo = $studentRollNoArray[0]['rollNo'];
					$studentUniversityRollNo = $studentRollNoArray[0]['universityRollNo'];

					if (!empty($studentRollNo)) {
						$inconsistenciesArray[] = "Roll No. '$rollNo' is already existed in selected class at Sr. No.'$srNo'";
						continue;
					}

					if (!empty($studentUniversityRollNo)) {
						$inconsistenciesArray[] = "University Roll No. '$universityRollNo' is already existed in selected class at Sr. No.'$srNo'";
						continue;
					}

					$checkCondition = "WHERE studentId = ".$studentId;
					$return = $studentManager->addStudentRollNoInTransaction($rollNo,$universityRollNo,$checkCondition);
					if ($return == false) {
						$inconsistenciesArray[] = "Error while saving student roll no.";
					}
				  }
				//}
			}
		}
	}
	else {
		echo FAILURE;
	}

if (count($inconsistenciesArray) == 0) {
		
	if(SystemDatabaseManager::getInstance()->commitTransaction()) {
		$successArray[] = "Data saved successfully for $totalClassStudents students of Class: '$className'";
		$csvData = '';
		$i = 1;
		foreach($successArray as $key=>$record) {
			$csvData .= "$i. $record\r\n";
			$i++;
		}
		$csvData = trim($csvData);
		$fileName = "Upload Student Roll No.txt";
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
else {

	$csvData = '';
	$i = 1;
	foreach($inconsistenciesArray as $key=>$record) {
		$csvData .= "$i $record\r\n";
		$i++;
	}
	$csvData = trim($csvData);
	$fileName = "StudentRollNo_Inconsistencies.txt";
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

//$History: fileUpload.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 3/26/10    Time: 10:32a
//Updated in $/LeapCC/Library/StudentRollNoUpload
//fixed bug no.  0003109
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/18/10    Time: 11:28a
//Updated in $/LeapCC/Library/StudentRollNoUpload
//put new field university Roll No.
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 11/24/09   Time: 4:51p
//Updated in $/LeapCC/Library/StudentRollNoUpload
//fixed bug nos. 0002118, 0002117, 0002116, 0002115
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 11/17/09   Time: 3:31p
//Updated in $/LeapCC/Library/StudentRollNoUpload
//modified in code to show sr.no.
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/04/09   Time: 11:48a
//Created in $/LeapCC/Library/StudentRollNoUpload
//new files for student roll no. uploading
//
?>
