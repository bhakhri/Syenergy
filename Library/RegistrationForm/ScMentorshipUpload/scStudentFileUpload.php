<?php
//-------------------------------------------------------
// THIS FILE IS USED TO upload student photo for subject centric
//
// Author : Rajeev Aggarwal
// Created on : (06.09.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//                      
//--------------------------------------------------------

	set_time_limit(0);
	ini_set('memory_limit','500M');
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(BL_PATH . "/RegistrationForm/FileUploadManager.inc.php");
	define('MODULE','MentorshipUpload');
	define('ACCESS','add');
	UtilityManager::ifNotLoggedIn();
	global $sessionHandler;
    $fileObj = FileUploadManager::getInstance('mentorshipUploadFile');
	$filename = $fileObj->tmp;
   
	if ($fileObj->fileExtension != 'xls') {
	
		$inconsistenciesArray[] = "Incorrect file format. Please read Notes.";
		$csvData = '';
		$i = 1;
		foreach($inconsistenciesArray as $key=>$record) {
			$csvData .= "$i $record\r\n";
			$i++;
		}
		$csvData = trim($csvData);
		$fileName = "Mentorship_Inconsistencies.txt";
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
		
	require_once(MODEL_PATH . "/RegistrationForm/ScStudentStatusUpload.inc.php");
	$studentManager = StudentStatusUpload::getInstance();


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

	$str = '';
	$totalRecordCounter = 0;
	$inconsistenciesArray = array();
	$queryArray = array();
	foreach($sheetNameArray as $sheetIndex => $userName) {
		$userArray = $studentManager->getUserId($userName);
		$userId = $userArray[0]['userId'];
		if (empty($userId)) {
			$inconsistenciesArray[] = "Please give the name of sheet name exactly as [user name of employee $userName]";
			continue;
            
		}
		for ($i = 1; $i <= $data->sheets[$sheetIndex]['numRows']; $i++) {
			$srNo = $data->sheets[$sheetIndex]['cells'][$i][1];
			$rollNo = $data->sheets[$sheetIndex]['cells'][$i][2];
			$studentName = $data->sheets[$sheetIndex]['cells'][$i][3];
			if ($srNo == '') {
				break;
			}
			if(ord(substr($rollNo,-1)) == 160) {
				$rollNo = substr($rollNo,0,-1);
			}

			$studentArray = $studentManager->getStudentId($rollNo);
			$studentId = $studentArray[0]['studentId'];
			$classId = $studentArray[0]['classId'];

			if (empty($studentId) or empty($classId)) {
				$inconsistenciesArray[] = "Invalid Roll No. '$rollNo' in user: '$userName'";
				continue;
			}

			$userClassMappingArray = $studentManager->checkStudentClassMapping($studentId,$classId);
			$userClassMapping = $userClassMappingArray[0]['userName'];
			if ($userClassMapping != '') {
				$inconsistenciesArray[] = "$rollNo is already mapped with $userClassMapping user.";
				continue;
			}
			if (!empty($str)) {
				$str .= ',';
			}
			$str .= "($classId,$userId,$studentId)";
			$totalRecordCounter++;
			if ($totalRecordCounter % 200 == 0) {
				$insertQueryArray[] = $str;
				$str = '';
			}
		}
	}
	if ($str != '') {
		$insertQueryArray[] = $str;
	}
	if (count($inconsistenciesArray) == 0) {
		require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
		if(SystemDatabaseManager::getInstance()->startTransaction()) {
			foreach($insertQueryArray as $insertQuery) {
				$return = $studentManager->insertUserStudentClass($insertQuery);
				if ($return == false) {
					$inconsistenciesArray[] = "Error while saving mentorship";
				}
			}
			if (count($inconsistenciesArray) == 0) {
				if(SystemDatabaseManager::getInstance()->commitTransaction()) {
					?>
					<script language="javascript">
					parent.dataPassed("<?php echo SUCCESS;?>");
					</script>
					<?php
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
				$fileName = "Mentorship_Inconsistencies.txt";
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
			echo FAILURE;
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
		$fileName = "Mentorship_Inconsistencies.txt";
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

?>