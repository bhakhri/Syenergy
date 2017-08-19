<?php
//-------------------------------------------------------
// THIS FILE IS USED TO upload student photo for subject centric
// Author : Jaineesh
// Created on : 05-Feb-2010
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
	
    ob_start();
	set_time_limit(0);
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(BL_PATH . "/FileUploadManager.inc.php");
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
	define('MODULE','UploadStudentExternalMarks');
	define('ACCESS','add');
	global $sessionHandler; 
    $roleId = $sessionHandler->getSessionVariable('RoleId');
    if($roleId=='2') {      // Teacher Login
      UtilityManager::ifTeacherNotLoggedIn(true); 
    }
    else {
      UtilityManager::ifNotLoggedIn(true); 
    }
    UtilityManager::headerNoCache();
	
     
	$successArray = array();
	$failureArray = array();
	$inconsistenciesArray = array();
	$statusArray = array();
	$fileSize = 0;

	$commonManager = CommonQueryManager::getInstance();
	$studentManager = StudentManager::getInstance();
	$classId = $REQUEST_DATA['degree'];
    $timeTable = $REQUEST_DATA['timeTable']; 
    
    if($classId=='') {
      $classId='0';  
    }
    
    if($timeTable=='') {
      $timeTable='0';  
    }
    
	$queryDescription ='';
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $sessionId = $sessionHandler->getSessionVariable('SessionId');

    $className = $studentManager->getClassName($classId);
	$className1 = $className[0]['className'];
	$auditTrialDescription = 'External Marks Uploaded for class "'.$className1.'" subject(s):';
	
	
    $tableName='';
    $condition =  "";
    $conditions = "";
    if($roleId=='2') {  
      $conditions = " AND sg.classId='".$classId."'";
      $teacherStudentArray = $studentManager->getStudentListExternal($conditions);  
      
      $employeeId = $sessionHandler->getSessionVariable('EmployeeId');  
      $tableName = ",  ".TIME_TABLE_TABLE."  tt";  
      $condition = " AND tt.employeeId = '$employeeId' AND tt.timeTableLabelId = '$timeTable' 
                     AND tt.subjectId = a.subjectId
                     AND tt.classId = a.classId ";  
    }
    $subjectArray = $studentManager->getClassSubjectsTestTypes($classId,$condition,$tableName);
    
   if(count($subjectArray)==0) {
      echo ('<script type="text/javascript">alert("Subject List Not Found. Please try again");</script>');
      die;
    }    

	require_once(BL_PATH . "/reader.php");
	foreach($subjectArray as $subjectRecord) {

			$subjectIdMarks = $subjectRecord['subjectId'];
            $ttExternalMarks = $subjectRecord['externalTotalMarks'];
            $ttClassId = $subjectRecord['classId'];
		
           
        	$marksFileName = 'file_'.$subjectIdMarks;  
            
            $testTypeId = $REQUEST_DATA['testType_'.$subjectIdMarks];
			$subjectId = $subjectRecord['subjectId'];
			$subjectCode = $subjectRecord['subjectCode'];
			
			if($testTypeId != '' ) {
               if($_FILES[$marksFileName]['size'] == 0) {
                 echo ('<script type="text/javascript">alert("Please Browse Excel File against subject: '.$subjectCode.'");</script>');
                 die();
               }
			}
			if ($_FILES[$marksFileName]['size'] > 0) {
				$fileSize++;
				
			    //if ($getFileName != '') {
				if($testTypeId == '' ) {
					echo ('<script type="text/javascript">alert("Please Select Test Type against subject: '.$subjectCode.'");</script>');
					//echo ('<script type="text/javascript">document.getElementById("testType_'.$subjectIdMarks.'").focus()</script>');
					//echo ('<script type="text/javascript">document.getElementById("'.$testTypeId.'").focus()</script>');
					//document.$testTypeId.focus();
					die();
				}
				$fileObj = FileUploadManager::getInstance($marksFileName);
				$fileName = $fileObj->tmp;
				
				if($fileObj->fileExtension != 'xls') {
				  echo ('<script type="text/javascript">alert("Please Browse Excel File against subject: '.$subjectCode.'");</script>');
				  die();
				}
			
            	$fileObj = FileUploadManager::getInstance($marksFileName);
				$fileName = $fileObj->tmp;
				$data = new Spreadsheet_Excel_Reader();
				$data->setOutputEncoding('CP1251');
				$return = $data->read($fileName);
				if ($return === false) {
					echo ('<script type="text/javascript">alert("Please Browse Valid Excel File against subject: '.$subjectCode.'");</script>');
					die();
				}
	
             	//$statusArray[] = "Reading $subjectIdStr	$subjectFileName";
                $m=0;
				$sheetNameArray = null;
				while(isset($data->boundsheets[$m]['name'])) {
				  $sheetNameArray[] =  $data->boundsheets[$m]['name'];
				  $m++;
				}
                
                $totalCourseStudents = 0;
				$courseCodeArray = array();
				if(SystemDatabaseManager::getInstance()->startTransaction()) {	
					foreach($sheetNameArray as $sheetIndex => $courseCode) {

                       if ($sheetIndex > 0) {
							$inconsistenciesArray[] = "Excel Sheet should be only one for particular subject";
							continue;
						}
						$maxMarks = $data->sheets[$sheetIndex]['cells'][1][4];
						
						if (!is_numeric($maxMarks)) {
							$inconsistenciesArray[] = "Invalid External Max. Marks of subject: '$subjectCode' ";
							continue;
						}

                        
						if($classId != '' && $subjectId != '') {

							//$getSubjectArray = $studentManager -> checkSubjectExistance($subjectId,$classId);
							//$externalMarks = $getSubjectArray[0]['externalTotalMarks'];
							if ($ttExternalMarks == 0 or $ttExternalMarks == '') {
								$inconsistenciesArray[] = "Max. Marks of exam are not existed of subject: '$subjectCode'";
								continue;
							}
							/*
							if($externalMarks != $maxMarks) {
								$inconsistenciesArray[] = "External Max Marks are not existed of subject: '$subjectCode'";
								continue;
							}
							*/
						}
						else {
							$inconsistenciesArray[] = "Invalid class & subject";
							continue;
						}
   


						for ($i = 2; $i <= $data->sheets[$sheetIndex]['numRows']; $i++) {
							//echo($data->sheets[$sheetIndex]['numRows']);
							$srNo = $data->sheets[$sheetIndex]['cells'][$i][1];
                            $srNo = trim($srNo);
                            if (empty($srNo)) {
							  break;
							}
                            
                            
							$totalCourseStudents++;
							$universityRollNo = trim($data->sheets[$sheetIndex]['cells'][$i][2]);
							$studentName = trim($data->sheets[$sheetIndex]['cells'][$i][3]);
							$maxValue = trim($data->sheets[$sheetIndex]['cells'][$i][4]);
							//echo ($studentName);
                            
                            if($universityRollNo=='') {
                              $inconsistenciesArray[] = "University Roll No. not exist at sr. No. '$srNo' "; 
                              continue;   
                            }
                            
                            if($roleId=='2') {  
                              $findStudent='';  
                              for($jj=0;$jj<count($teacherStudentArray); $jj++) {
                                $ttUniversityRollNo = trim($teacherStudentArray[$jj]['universityRollNo']);  
                                if(strtolower($ttUniversityRollNo)==strtolower($universityRollNo)) {
                                   $findStudent='1';   
                                   break;
                                }
                              }
                              if($findStudent=='1') {
                                $inconsistenciesArray[] = "Invalid University Roll No. '$universityRollNo' of subject: '$subjectCode' at sr. No. '$srNo'"; 
                                continue; 
                              }
                            }
                            
                            if($maxValue == '') {
								$inconsistenciesArray[] = "External Max Marks against the university roll no. '$universityRollNo' at sr. No. '$srNo' cannot be blank of subject: '$subjectCode'";
								continue;
							}
							
							//global $marksScoredArray;
							//print_r($marksScoredArray);
							$marksStatus = '';
                           if(!is_numeric($maxValue)) {
								//$maxValue = trim($maxValue);
								if(array_key_exists($maxValue,$marksScoredArray)) {
									$marksStatus = 1;
								}
								else {
									$marksStatus = 2;
								}
							}
							 
                            if($marksStatus == 2) {
								$inconsistenciesArray[] = "Invalid External Max Marks for University Roll No. '$universityRollNo' of subject: '$subjectCode'";
								continue;					
							}
							
							/*
							if (!is_numeric($maxValue)) {
								$inconsistenciesArray[] = "Invalid External Max Marks for University Roll No. '$universityRollNo' of subject: '$subjectCode'";
								continue;
							}*/



							$studentArray = $studentManager->getUnivStudentId($universityRollNo,$classId);
                            if(count($studentArray)==0) {
                               $inconsistenciesArray[] = "Invalid University Roll No. '$universityRollNo' of subject: '$subjectCode' at sr. No. '$srNo'"; 
                               continue;  
                            }
							$studentId = trim($studentArray[0]['studentId']);
							$className = trim($studentArray[0]['className']);
							if($studentId == '') {
							   $inconsistenciesArray[] = "Invalid University Roll No. '$universityRollNo' of subject: '$subjectCode' at sr. No. '$srNo'";
							  continue;
							}
							
							$classArray = $studentManager->findStudentClass($studentId,$classId);
                            if(count($classArray)==0) {
                               $inconsistenciesArray[] = "University Roll No. '$universityRollNo' does not study in selected class at sr. No. '$srNo'";
                               continue;  
                            }
							$classId = trim($classArray[0]['classId']);
							if($classId == '') {
								$inconsistenciesArray[] = "University Roll No. '$universityRollNo' does not study in selected class at sr. No. '$srNo'";
								continue;
							}
							/*$scsArray = $studentManager->checkStudentClassSubject($studentId, $classId, $subjectId);
							$scsCount = $scsArray[0]['cnt'];
							if (empty($scsCount)) {
								$inconsistenciesArray[] = "Roll No. '$rollNo' does not study in Course: '$courseCode";
								continue;
							}*/
                            if(is_numeric($maxValue)) {
								if ($maxValue > $maxMarks) {
									$inconsistenciesArray[] = "Invalid External Max Marks for University Roll No. '$universityRollNo' of subject: '$subjectCode'. as Max Marks: $maxMarks";
									continue;
								}
							}
                            //$newMaxValue = ceil(($maxValue * $externalMarks) / $maxMarks);
                         	//$maxValue = $newMaxValue;
							if($studentId != '' && $classId != '' && $subjectId != '') {
								$testTypeTestArray = $studentManager->checkTestExist($studentId,$classId,$subjectId,$testTypeId);
								$testTypeTestCount = $testTypeTestArray[0]['cnt'];
							}
                            
                            if ($testTypeTestCount == 0) {
								if($marksStatus == 1) {
								  $maxValue = 0;
								}
								$returnStatus = $studentManager->addTestTransferredMarks($testTypeId,$studentId,$classId,$subjectId,$maxMarks,$maxValue);
							    if($returnStatus == false) {
								  $inconsistenciesArray[] = "Error while saving data of subject: '$subjectCode' at sr. No. '$srNo' ";
                                   continue; 
								}
							    $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');
							}
							//}
							else {
								if($marksStatus == 1) {
									$maxValue = 0;
								}
                                $returnTestStatus = $studentManager->updateTestTransferMarks($testTypeId,$studentId,$classId,$subjectId,$maxMarks,$maxValue);
								if ($returnTestStatus == false) {
									$inconsistenciesArray[] = "Error while saving data";
								}
							    $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');
							}
							if($studentId != '' && $classId != '' && $subjectId != '') {
								$transferredMarksArray = $studentManager->checkTransferredMarksExist($studentId,$classId,$subjectId);
								$transferredMarksCount = $transferredMarksArray[0]['cnt'];
							}

							if ($transferredMarksCount == 0) {
								$conductingAuthority = 2;
								if($marksStatus == 1) {
									$marksScoredStatus = trim($data->sheets[$sheetIndex]['cells'][$i][4]);
								}
								else {
									$marksScoredStatus = 'Marks';
								}

								$returnTransferredStatus = $studentManager->addTransferredMarks($conductingAuthority,$studentId,$classId,$subjectId,$maxMarks,$maxValue,$marksScoredStatus);
								
								if ($returnTransferredStatus == false) {
									$inconsistenciesArray[] = "Error while saving data of subject: '$subjectCode'";
								}
						    }
							else{	
								$queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');
								if($marksStatus == 1) {
									$marksScoredStatus = trim($data->sheets[$sheetIndex]['cells'][$i][4]);
								}
								else {
									$marksScoredStatus = 'Marks';
								}
								$returnTransferredUpdateStatus = $studentManager->updateTransferredMarks($conductingAuthority,$studentId,$classId,$subjectId,$maxMarks,$maxValue,$marksScoredStatus);
								if ($returnTransferredUpdateStatus == false) {
									$inconsistenciesArray[] = "Error while saving data";
								}
								else {
							        $queryDescription .= $sessionHandler->getSessionVariable('IdToQueryDescription');
									if (!in_array($subjectCode, $courseCodeArray)) {
										$auditTrialDescription .= "$subjectCode,";
										$courseCodeArray[] = $subjectCode;
									}
								}
							}
						}
                   }
                }         
				else {
						echo FAILURE;
				}
				//	echo  $auditTrialDescription;
				if (count($inconsistenciesArray) == 0 ) {
					$type =EXTERNAL_MARKS_ARE_UPLOADED; //used for uploading external marks
				//	echo $auditTrialDescription;
					$auditTrialDescription2 = substr($auditTrialDescription,0,-1); //remove last comma

					$return = $commonManager->addAuditTrialRecord($type,$auditTrialDescription2,$queryDescription);
					if ($return == false) {
						$inconsistenciesArray[] = "Error while saving data for audit trail";
					} 
							
				}
				if (count($inconsistenciesArray) == 0 ) {
					if(SystemDatabaseManager::getInstance()->commitTransaction()) {
						$successArray[] = "External Marks uploaded successfully for $totalCourseStudents students of Class: '$className1' and Subject: '$subjectCode'";
				}
			}
		}
	}
	//print_r($inconsistenciesArray);
	//die;
	
	if($fileSize == 0 ) {
		echo ('<script type="text/javascript">alert("Please Select File for Upload");</script>');
		die();
	}

	$csvData = '';
	$i = 1;
	$showError = $REQUEST_DATA['showError'];
	foreach($inconsistenciesArray as $key=>$record) {
		$csvData .= "$i. $record";
		if ($showError != 'screen') {
			$csvData .= "\r\n";
		}
		else {
			$csvData .= "<br>";
		}
		$i++;
	}
	foreach($successArray as $key=>$record) {
		$csvData .= "$i. $record";
		if ($showError != 'screen') {
			$csvData .= "\r\n";
		}
		else {
			$csvData .= "<br>";
		}
		$i++;
	}
	$csvData = trim($csvData);
	//echo($csvData);
	if ($showError == 'screen') {
		echo $csvData = str_replace("'",'', $csvData);
		echo "<script type='text/javascript'>parent.document.getElementById('statusDiv').innerHTML = ''</script>";
		echo "<script type='text/javascript'>parent.document.getElementById('statusDiv').innerHTML = '$csvData'</script>";
		die;
	}
	else {
		$fileName = "External Marks Uploading Status.txt";
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