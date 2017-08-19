<?php
//This file is used as internal file for csv part
//
// Author :Ajinder Singh
// Created on : 28-nov-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>

<?php
	set_time_limit(0);
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$studentReportsManager = StudentReportsManager::getInstance();


	$classId = $REQUEST_DATA['class1']; //8
	$allDetailsArray = array();

	$subjectsArray = $studentReportsManager->getClassMarksTotalSubjects($classId);
	$queryPart = '';

	$classId = $REQUEST_DATA['class1']; //8
	$graceMarksValue = $REQUEST_DATA['showGraceMarks'];

	$allDetailsArray = array();

	$subjectsArray = $studentReportsManager->getClassMarksTotalSubjects($classId);
	$allDetailsArray['subjects'] = $subjectsArray;
	$queryPart = '';

	$str = ',';
	$str2 = ',';
	$str3 = ',';

  global $sessionHandler;
  $instituteId = $sessionHandler->getSessionVariable('InstituteId');

	foreach($subjectsArray as $subjectRecord) {
		$subjectId = $subjectRecord['subjectId'];
		$subjectCode = $subjectRecord['subjectCode'];
		$str .= $subjectCode.",,,,,";
		$str2 .= "A,I,E,T,G,";
		$subjectMarksArray = $studentReportsManager->getSubjectMarks($classId,$subjectId);
		$attendanceMarks = $subjectMarksArray[0]['attendance'];
		$preCompreMarks = $subjectMarksArray[0]['internal'];
		$compreMarks = $subjectMarksArray[0]['externalMarks'];
        
        $totMarks='0';
        if(doubleval($attendanceMarks)>0) {
          $totMarks += $attendanceMarks;  
        }
        if(doubleval($preCompreMarks)>0) {
          $totMarks += $preCompreMarks;  
        }
        if(doubleval($compreMarks)>0) {
          $totMarks += $compreMarks;  
        }
        if(doubleval($totMarks)==0) {  
          $totMarks='';  
        } 
        
		$str3 .= "$attendanceMarks,$preCompreMarks,$compreMarks,$totMarks,,";
		$thisArray = array_merge($subjectRecord, $subjectMarksArray[0]);
		$subjectsArrayNew[] = $thisArray;
	}

	$allDetailsArray['subjects'] = $subjectsArrayNew;

	$totalRecordsArray = $studentReportsManager->countTotalMarksData($classId,'');
	$totalRecords = $totalRecordsArray[0]['count'];
	$allDetailsArray['totalStudents'] = $totalRecords;
	$limit = "0, $totalRecords";

	//For Sorting
	$sortBy = '';
	$sorting = $REQUEST_DATA['sorting'];
	if ($sorting == 'RollNo') {
		$sortBy = ' length(rollNo)+0,rollNo ';
	}
	elseif ($sorting == 'uRollNo') {
		$sortBy = ' length(universityRollNo)+0,universityRollNo ';
	}
	elseif ($sorting == 'name') {
		$sortBy = ' studentName ';
	}
	$sortBy .= $REQUEST_DATA['ordering'];
	$resultDataArray = $studentReportsManager->getStudentList($classId, $limit, $sortBy);
	$testArray = array();
	$studentIdList = UtilityManager::makeCSList($resultDataArray, 'studentId');
	$marksDataArray = $studentReportsManager->getTotalMarksData4($classId, $studentIdList);
	$gradeArray = array();
	$studentGradeArray = array();

	$newDataArray = array();
	foreach($marksDataArray as $record) {
		$marksScoredStatus = $record['marksScoredStatus'];
		$gradeId = $record['gradeId'];
		if (!in_array($gradeId, $gradeArray) and $gradeId != '') {
			$gradeArray[] = $gradeId;
		}
		$marks = $marksScoredStatus;
		if ($marksScoredStatus == 'Marks') {
			$marks = round($record['marksScored'],2);
		}
		if($graceMarksValue == 1 && $record['conductingAuthority'] == 1) {
            $graceArray = explode('!~~!!~~!',$record['graceMarks']);
            //$marks += $record['graceMarks'];
            $marks += $graceArray[0];
            $newDataArray[$record['studentId']][$record['subjectId']]['totGrace'] = $graceArray[2];
        }  
        
        if($graceMarksValue == 1 && $record['conductingAuthority'] == 2) {
            $graceArray = explode('!~~!!~~!',$record['graceMarks']);
            //$marks += $record['graceMarks'];
            $marks += $graceArray[1];
            $newDataArray[$record['studentId']][$record['subjectId']]['totGrace'] = $graceArray[2];
        }  
        
		$newDataArray[$record['studentId']][$record['subjectId']][$record['conductingAuthority']] = $marks;
		$studentGradeArray[$record['studentId']][$record['subjectId']]['gradeId'] = $gradeId;
	}
	$gradeList = implode(",", $gradeArray);
	if ($gradeList == '') {
		$gradeList = 0;
	}
	$gradeLabelArray = $studentReportsManager->getGradeIdLabels($gradeList);
	$gradeIdLabelArray = array();
	foreach($gradeLabelArray as $record) {
		$gradeIdLabelArray[$record['gradeId']] = $record['gradeLabel'];
	}
	$marksDataArray = $studentReportsManager->getTotalMarksData4($classId, $studentIdList);
	$finalDataArray = array();
	$conductingAthorityArray = array(1,2,3);
	$counter = 0;
	foreach($resultDataArray as $record) {
		$studentId = $record['studentId'];
		$rollNo = $record['rollNo'];
		$studentName = $record['studentName'];
		$studentSubjectMarksArray = array();
		foreach($subjectsArray as $subjectRecord) {
			$subjectMarks = '';
			$subjectId = $subjectRecord['subjectId'];
            $totalMarks='';
			foreach($conductingAthorityArray as $conductingAuthority) {
				if (isset($newDataArray[$studentId][$subjectId][$conductingAuthority])) {
					if ($subjectMarks != '') {
						$subjectMarks .= '#';
					}
					$subjectMarks .= $newDataArray[$studentId][$subjectId][$conductingAuthority];
                    $totalMarks += doubleval($newDataArray[$studentId][$subjectId][$conductingAuthority]);
				}
				else {
					if ($subjectMarks != '') {
						$subjectMarks .= '#';
					}
					$subjectMarks .= '-';
				}
			}
			$subjectMarks .= '#' . $gradeIdLabelArray[$studentGradeArray[$studentId][$subjectId]['gradeId']];
            $totalMarks += doubleval($newDataArray[$studentId][$subjectId]['totGrace']);
            if($totalMarks=='') {
              $totalMarks = '-'; 
            }
            $subjectMarks .= '#' . $totalMarks;
			$studentSubjectMarksArray[$subjectId.'_Marks'] = $subjectMarks;
		}
		$finalDataArray[$counter]['studentId'] = $studentId;
		$finalDataArray[$counter]['rollNo'] = $rollNo;
		$finalDataArray[$counter]['studentName'] = $studentName;
		foreach($studentSubjectMarksArray as $key => $value) {
			$finalDataArray[$counter][$key] = $value;
		}
		$counter++;
	}

	$resultDataArray = $finalDataArray;
	$cnt = count($resultDataArray);

	$allDetailsArray['resultData'] = $valueArray;

	$csvData = '';
	$csvData .= "#, RollNo, Student Name $str \n";
	$csvData .= ", , $str2 \n";
	$csvData .= ", , $str3 \n";

	$i=0;
	$allSumArray = array();
	foreach($resultDataArray as $record) {
		$i++;
		$marksScoredAll='';
		foreach($allDetailsArray['subjects'] as $subjectRecord) {
			$subjectId = $subjectRecord['subjectId'];
			list($marksScored,$marksScored2,$marksScored3,$marksScored4,$marksScored5) = explode('#', $record[$subjectId . '_Marks']);
			/*
			if ($marksScored == '-1') {
				$marksScored = 'A';
			}
			if ($marksScored == '-2') {
				$marksScored = 'UMC';
			}
			if ($marksScored == '-3') {
				$marksScored = 'I';
			}
			if ($marksScored == '-4') {
				$marksScored = 'MU';
			}
			if ($marksScored2 == '-1') {
				$marksScored2 = 'A';
			}
			if ($marksScored2 == '-2') {
				$marksScored2 = 'UMC';
			}
			if ($marksScored2 == '-3') {
				$marksScored2 = 'I';
			}
			if ($marksScored2 == '-4') {
				$marksScored2 = 'MU';
			}
			*/
			$marksScoredAll .= ", ". $marksScored3 . ", ".$marksScored . ", ".$marksScored2 . ", ".$marksScored5. ", ".$marksScored4;
		}
		$csvData .= $i.','.$record['rollNo'].','.$record['studentName'].$marksScoredAll;
		$csvData .= "\n";
	}

		require_once(BL_PATH . "/UtilityManager.inc.php");
		UtilityManager::makeCSV($csvData, 'TotalMarksReportPrint.csv');
		die;

//$History: scListTotalMarksReportPrintCSV.php $
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 2/15/10    Time: 12:23p
//Updated in $/Leap/Source/Templates/ScStudentReports
//done changes to implement multi-institute in SC
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 12/11/09   Time: 2:50p
//Updated in $/Leap/Source/Templates/ScStudentReports
//fixed PR1001 problem of Grades not coming
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 3/10/09    Time: 12:33p
//Updated in $/Leap/Source/Templates/ScStudentReports
//files modified as per database changes in ".SC_TEST_TABLE.",
//".TOTAL_TRANSFERRED_MARKS_TABLE." tables
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 2/16/09    Time: 4:36p
//Updated in $/Leap/Source/Templates/ScStudentReports
//changed query part
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 2/11/09    Time: 2:03p
//Updated in $/Leap/Source/Templates/ScStudentReports
//added code for attendance
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 1/09/09    Time: 6:31p
//Updated in $/Leap/Source/Templates/ScStudentReports
//updated query
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 12/17/08   Time: 1:52p
//Updated in $/Leap/Source/Templates/ScStudentReports
//changed query to make report faster
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 12/01/08   Time: 11:25a
//Updated in $/Leap/Source/Templates/ScStudentReports
//changed query, removed linking with sc_student_grades
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 11/28/08   Time: 1:40p
//Created in $/Leap/Source/Templates/ScStudentReports
//file added for total marks report
//
//
?>