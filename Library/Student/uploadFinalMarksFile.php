<?php
//-------------------------------------------------------
// THIS FILE IS USED TO upload student photo for subject centric
//
// Author : Ajinder Singh
// Created on : 02-May-2009
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//                      
//--------------------------------------------------------

	
	ob_start();
	set_time_limit(0);
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(BL_PATH . "/FileUploadManager.inc.php");
	define('MODULE','UploadStudentMarks');
	define('ACCESS','add');
	if (empty($REQUEST_DATA['isFileUploaded'])) {
		die;
	}

	$inconsistenciesArray = array();

	UtilityManager::ifNotLoggedIn();
	$classId = $REQUEST_DATA['class1'];
	$fileObj = FileUploadManager::getInstance('uploadFile');
	$fileName = $fileObj->tmp;
	if (empty($fileName)) {
		$inconsistenciesArray[] = "INVALID or NO FILE UPLOADED";
	}

	if (count($inconsistenciesArray) > 0) {
		$csvData = '';
		$i = 1;
		foreach($inconsistenciesArray as $key=>$record) {
			$csvData .= "$i. $record\r\n";
			$i++;
		}
		$csvData = trim($csvData);
		$fileName = "Marks Uploading Status.txt";
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

	$data = file_get_contents($fileName);
	$dataArray = explode("\n",$data);
	$totalDataRecords = count($dataArray);

	require_once(MODEL_PATH . "/StudentManager.inc.php");
	$studentManager = StudentManager::getInstance();

	
	
	$i = 1;
	$subjectsArray = array();
	$marksArray = array();
	$subjectMarksArray = array();
	foreach($dataArray as $recordRow) {
		if ($i == 1) {
			$subjectsArray = explode(',',$recordRow);
			$subjectsArray = array_slice($subjectsArray,3);
			foreach($subjectsArray as $subjectKey => $subjectCode) {
				$subjectCode = trim($subjectCode);
				$cntArray = $studentManager->checkSubjectExists($subjectCode,$classId);
				$cnt = $cntArray[0]['cnt'];
				if (empty($cnt)) {
					$inconsistenciesArray[] = SUBJECT_.$subjectCode._NOT_MAPPED_TO_CLASS;
					break;
				}
			}
		}
		elseif ($i == 2) {
			$marksArray = explode(',',$recordRow);
			$marksArray = array_slice($marksArray,3);
			$m = 0;
			foreach($subjectsArray as $subjectKey => $subjectCode) {
				$subjectCode = trim($subjectCode);
				$finalMarksArray = $studentManager->getSubjectFinalMarks($subjectCode,$classId);
				$finalMarks = $finalMarksArray[0]['externalTotalMarks'];
				if ($marksArray[$m] != $finalMarks) {
					$inconsistenciesArray[] = FINAL_MARKS_DOES_NOT_MATCH_FOR_SUBJECT_.$subjectCode;
					break;
				}
			}
		}
		elseif($i > 3) {
			if ($i == $totalDataRecords) {
				break;
			}
			$studentRecord = explode(',',$recordRow);
			$univRollNo = $studentRecord[1];
			$studentName = $studentRecord[2];
			if(ord(substr($univRollNo,-1)) == 160) {
				$univRollNo = substr($univRollNo,0,-1);
			}
			$studentIdArray = $studentManager->checkStudentId($univRollNo,$classId);
			$studentId = $studentIdArray[0]['studentId'];
			if (empty($studentId)) {
				$inconsistenciesArray[] = INVALID_UNIVERSITY_ROLL_NO_.$univRollNo;
				break;
			}
			$colCtr = 3;
			foreach($subjectsArray as $subjectKey => $subjectCode) {
				$subjectCode = trim($subjectCode);
				$subjectMarksArray[$subjectCode][$studentId] = $studentRecord[$colCtr];
				$colCtr++;
			}
		}
		$i++;
	}

	if (count($inconsistenciesArray) > 0) {
		$csvData = '';
		$i = 1;
		foreach($inconsistenciesArray as $key=>$record) {
			$csvData .= "$i. $record\r\n";
			$i++;
		}
		$csvData = trim($csvData);
		$fileName = "Marks Uploading Status.txt";
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

	
	$allInconsistenciesArray = array();
	$successArray = array();

	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
	$i = 0;
	foreach($subjectMarksArray as $subjectCode => $recordArray) {
		$inconsistenciesArray = array();
		if(SystemDatabaseManager::getInstance()->startTransaction()) {
			$subjectIdArray = $studentManager->getSubjectId($subjectCode,$classId);
			$subjectId = $subjectIdArray[0]['subjectId'];

			//delete from test transferred marks
			$externalTestTypeArray = $studentManager->getExternalMarksDistribution($classId, $subjectId);
			$testTypeId = $externalTestTypeArray[0]['testTypeId'];
			$returnStatus = $studentManager->deleteTestTransferredMarks($classId, " AND subjectId = $subjectId AND testTypeId = $testTypeId");
			if ($returnStatus == false) {
				$inconsistenciesArray[] = FAILURE_WHILE_CLEARING_OLD_DATA_FOR_SUBJECT_.$subjectCode;
				break;
			}
			$returnStatus = $studentManager->deleteTotalTransferredMarks($classId, " AND subjectId = $subjectId AND conductingAuthority = 2 ");
			if ($returnStatus == false) {
				$inconsistenciesArray[] = FAILURE_WHILE_CLEARING_OLD_DATA_FOR_SUBJECT_.$subjectCode;
				break;
			}
			$testCntArray = $studentManager->checkExternalTestExist($subjectId,$classId);
			$testCnt = $testCntArray[0]['cnt'];
			
			if (empty($testCnt)) {
				$testTypeCategoryArray = $studentManager->getSubjectExternalTestTypeCategory($subjectId);
				$testTypeCategoryId = $testTypeCategoryArray[0]['testTypeCategoryId'];
				$testTypeCategoryName = $testTypeCategoryArray[0]['testTypeName'];
				$maxMarks = $marksArray[$i];
				$testDate = date('Y-m-d');
				$returnStatus = $studentManager->addNewTest($subjectId,$classId,$testTypeCategoryName,$testTypeCategoryId, $maxMarks, $testDate);
				if ($returnStatus == false) {
					$inconsistenciesArray[] = FAILURE_WHILE_CREATING_NEW_TEST_FOR_SUBJECT_.$subjectCode;
					break;
				}
			}
			$lastTestArray = $studentManager->getLastTest($subjectId,$classId);
			$lastTestId = $lastTestArray[0]['lastTestId'];
			$maxMarks = $lastTestArray[0]['maxMarks'];
			$insertStr = '';
			$insertStrTestTransferred = '';
			$insertStrTotalTransferred = '';
			$marksScored = '';
			$queryPart = '';
			$tableCtr = 0;
			$tables = '';
			$setCondition = '';
			$whereCondition = '';

			foreach($recordArray as $studentId => $marksScored) {
				$isPresent = 1;
				$marksScoredStatus = 'Marks'; 
				if (ord(substr($marksScored,-1)) == 13) {
					$marksScored = substr($marksScored,0,-1);
				}
				if (!is_numeric($marksScored)) {
					$marksScored = strtoupper(trim($marksScored));
					if (array_key_exists($marksScored, $marksScoredArray)) {
						if ($marksScored == "AB") {
							$isPresent = 0;
						}
						$marksScoredStatus = $marksScored;
						$marksScored = $marksScoredArray[$marksScored];
					}
					else {
						$inconsistenciesArray[] = INVALID_STATUS_.$marksScored;
						$inconsistenciesArray[] = MARKS_NOT_SAVED_FOR_SUBJECT_.$subjectCode;
						continue;
					}
				}

				if (!empty($insertStrTestTransferred)) {
					$insertStrTestTransferred .= ',';
					$insertStrTotalTransferred .= ',';
				}
				$insertStrTestTransferred .= "($studentId,$testTypeId,$classId,$subjectId,$maxMarks,$marksScored)";
				$insertStrTotalTransferred .= "($studentId,$classId,$subjectId,$maxMarks,$marksScored,0,2,'$marksScoredStatus')";

				$cntArray = $studentManager->checkStudentTest($lastTestId,$studentId);
				$cnt = $cntArray[0]['cnt'];
				if ($cnt > 0) {
					if (!empty($tables)) {
						$tables .= ',';
						$setCondition .= ',';
						$whereCondition .= ' AND ';
					}

					$tables .= TEST_MARKS_TABLE." as tm$tableCtr";
					$setCondition .= "tm$tableCtr.marksScored = '$marksScored', tm$tableCtr.isPresent = $isPresent";
					$whereCondition .= "tm$tableCtr.studentId = $studentId AND tm$tableCtr.testId = $lastTestId ";

					$tableCtr++;
					
					if ($tableCtr % 10 == 0) {
						$return = $studentManager->updateRecordInTransaction($tables, $setCondition, $whereCondition);
						if ($return === false) {
							$inconsistenciesArray[] = FAILURE_WHILE_SAVING_TEST_MARKS_FOR_SUBJECT_.$subjectCode;
							continue;
						}
						$tableCtr = 0;
						$tables = '';
						$setCondition = '';
						$whereCondition = '';
					}
				}
				else {
					if (!empty($insertStr)) {
						$insertStr .= ',';
						$insertStrTestTransferred .= ',';
					}
					$insertStr .= "($lastTestId,$studentId,$subjectId,$maxMarks,$marksScored,$isPresent,1)";
				}
			}
			$returnStatus = $studentManager->addTotalMarksInTransaction($insertStrTestTransferred);
			if ($returnStatus == false) {
				$inconsistenciesArray[] = FAILURE_WHILE_INSERTING_NEW_DATA_FOR_SUBJECT_.$subjectCode;
				continue;
			}
			$returnStatus = $studentManager->addGradingRecordInTransaction($insertStrTotalTransferred);
			if ($returnStatus == false) {
				$inconsistenciesArray[] = FAILURE_WHILE_INSERTING_NEW_DATA_FOR_SUBJECT_.$subjectCode;
				continue;
			}
			if ($insertStr != '') {
				$returnStatus = $studentManager->addTestMarks($insertStr);
				if ($returnStatus == false) {
					$inconsistenciesArray[] = FAILURE_WHILE_SAVING_TEST_MARKS_FOR_SUBJECT_.$subjectCode;
					continue;
				}
			}
			if ($tableCtr > 0) {
				$return = $studentManager->updateRecordInTransaction($tables, $setCondition, $whereCondition);
				if ($return === false) {
					$inconsistenciesArray[] = FAILURE_WHILE_SAVING_TEST_MARKS_FOR_SUBJECT_.$subjectCode;
					continue;
				}
			}
			
			$i++;
			if (count($inconsistenciesArray) == 0) {
				//save data to test transferred marks

				//save data to total transferred marks

				if(SystemDatabaseManager::getInstance()->commitTransaction()) {
					$successArray[] = MARKS_SAVED_FOR_SUBJECT_.$subjectCode;
				}
				else {
					$inconsistenciesArray[] = FAILURE_WHILE_SAVING_TEST_MARKS_FOR_SUBJECT_.$subjectCode;
					continue;
				}
			}
			else {
				foreach($inconsistenciesArray as $inconsistency) {
					$allInconsistenciesArray[] = $inconsistency;
				}
			}
		}
		else {
			$inconsistenciesArray[] = FAILURE_WHILE_SAVING_TEST_MARKS_FOR_SUBJECT_.$subjectCode;
			continue;
		}
	}

	$i = 1;
	if (count($allInconsistenciesArray) > 0) {
		$csvData = '';
		
		foreach($allInconsistenciesArray as $key=>$record) {
			$csvData .= "$i. $record\r\n";
			$i++;
		}
	}
	if (count($successArray) > 0) {
		foreach($successArray as $key=>$record) {
			$csvData .= "$i. $record\r\n";
			$i++;
		}
	}
	$csvData = trim($csvData);
	$fileName = "Marks Uploading Status.txt";
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header("Pragma: hack"); // WTF? oh well, it works...
	header("Content-Type: application/octet-stream");
	header("Content-Length: " .strlen($csvData));
	header('Content-Disposition: attachment; filename="'.$fileName.'"');
	header("Content-Transfer-Encoding: text\n");
	echo $csvData;
	die;

//$History: uploadFinalMarksFile.php $
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/24/09    Time: 7:14p
//Updated in $/LeapCC/Library/Student
//added code for multiple tables.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 5/02/09    Time: 7:12p
//Created in $/LeapCC/Library/Student
//file added for 'marks upload'
//



?>