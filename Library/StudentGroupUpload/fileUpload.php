<?php
//-------------------------------------------------------
// THIS FILE IS USED TO upload student photo for subject centric
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
	define('MODULE','UploadStudentGroup');
	define('ACCESS','add');
	UtilityManager::ifNotLoggedIn();
	global $sessionHandler;
	
    $fileObj = FileUploadManager::getInstance('studentGroupUploadFile');
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
		$fileName = "StudentGroup_Inconsistencies.txt";
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

	$getClassId = $REQUEST_DATA['degree'];
	

	foreach($sheetNameArray as $sheetIndex=>$value) {
		if ($getClassId == '') {
			$inconsistenciesArray[] = "Please select a class";
			continue;
		}
		if ($sheetIndex != '0') {
			$inconsistenciesArray[] = "Select only one sheet in Excel";
			continue;
		}
		for ($i = 1; $i <= $data->sheets[$sheetIndex]['numRows']; $i++) {
			if ($data->sheets[$sheetIndex]['cells'][1][1] != "[Sr.No]") {
				$inconsistenciesArray[] = "Data has not entered in given format";
				continue;
			}
		}

		for ($i = 2; $i <= $data->sheets[$sheetIndex]['numRows']; $i++) {
			$totalClassStudents++;

			$srNo = $data->sheets[$sheetIndex]['cells'][$i][1];
			$studentName = $data->sheets[$sheetIndex]['cells'][$i][2];
			$rollNo = $data->sheets[$sheetIndex]['cells'][$i][3];
			
			$studentArray = $studentManager->getStudentId($rollNo,$getClassId);
			$studentId = $studentArray[0]['studentId'];
			$classId = $studentArray[0]['classId'];
			$className = $studentArray[0]['className'];
			$studentInstituteId = $studentArray[0]['instituteId'];
			$studentSessionId = $studentArray[0]['sessionId'];
			
			if (empty($studentId) or empty($classId)) {
				$inconsistenciesArray[] = "Invalid Roll No. '$rollNo' of selected class";
				continue;
			}
			elseif ($instituteId != $studentInstituteId) {
				$inconsistenciesArray[] = "Roll No. '$rollNo' does not belong to current institute";
				continue;
			}

			elseif ($sessionId != $studentSessionId) {
				$inconsistenciesArray[] = "Roll No. '$rollNo' does not belong to current session";
				continue;
			}

			$studentGroupArray = $studentManager->getStudentGroup($getClassId);
			
			$newGroupArray = array();
			foreach($studentGroupArray as $groupRecord) {
				$newGroupArray[$groupRecord['groupId']] = array('groupShort'=>$groupRecord['groupShort'], 'parentGroupId'=>$groupRecord['parentGroupId']);
			}
			

			$newStudentGroupArray = array();
			$groupNameIdArray = array();

			for ($j = 4; $j <= $data->sheets[$sheetIndex]['numCols']; $j++) {
				$groupShortName = $data->sheets[$sheetIndex]['cells'][$i][$j];
				
				if($groupShortName !='') {
					$groupShortNameCombination = $groupShortName . '#'. $studentId;
				
				if (!in_array($groupShortNameCombination, $groupNameIdArray)) {
					$groupNameIdArray[] = $groupShortNameCombination;
				}
				else {
					$inconsistenciesArray[] = "Group name duplicate at Sr. No.'$srNo'";
					continue;
				}
				}
				
				$conditions = "gr.groupShort='".$groupShortName."'";
				$studentFindGroupArray = $studentManager->getStudentGroupDetail($conditions);
				
				//if ($groupShortName != '') {
					foreach ($studentFindGroupArray AS $studentGroupRecord) {
					$newStudentGroupArray[] = $studentGroupRecord['groupId'];
					}
					
					if(empty($newStudentGroupArray)) {
						$inconsistenciesArray[] = "Group does not belong to select class at Sr. No.'$srNo'";
						continue;
					}
				//}
			 }
			 
			 foreach ($newStudentGroupArray as $studentGroupId ) {
				if (!array_key_exists($studentGroupId,$newGroupArray)) {
					$inconsistenciesArray[] = "Group does not belong to select class at Sr. No.'$srNo'";
					continue;
				}
			 }

			 foreach ($newStudentGroupArray as $studentGroupId ) {
				$parentGroupId = $newGroupArray[$studentGroupId]['parentGroupId'];
					if($parentGroupId != 0) {
						if (!in_array($parentGroupId,$newStudentGroupArray)){
							$inconsistenciesArray[] = "Group/Parent Group does not belong to select class at Sr. No.'$srNo'";
							continue;
						}
					}
				}

			foreach ($newStudentGroupArray as $studentGroupId ) {
				$groupId = $studentGroupId;

			if (!empty($str)) {
				$str .= ',';
			}

			$str .= "($studentId,$classId,$groupId,$instituteId,$sessionId)";
			$insertQueryArray[] = $str;
			$str = '';

			}
		}

	}

	if (count($inconsistenciesArray) == 0) {
		
		require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
		if(SystemDatabaseManager::getInstance()->startTransaction()) {
			foreach($insertQueryArray as $insertQuery) {
				$return = $studentManager->addGroupInTransaction($insertQuery);
				if ($return == false) {
					$inconsistenciesArray[] = "Error while saving student group";
				}
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
					$fileName = "Upload Student Group.txt";
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
				$fileName = "StudentGroup_Inconsistencies.txt";
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
		$fileName = "StudentGroup_Inconsistencies.txt";
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