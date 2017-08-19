<?php
//--------------------------------------------------------
//This file returns the report data
//
// Author :Ajinder Singh
// Created on : 28-nov-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	set_time_limit(0);
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','TotalMarksReport');
	define('ACCESS','view');
	define('MANAGEMENT_ACCESS',1);
	UtilityManager::ifNotLoggedIn();
	require_once($FE . "/Library/common.inc.php");
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");

	$studentReportsManager = StudentReportsManager::getInstance();
	$allDetailsArray = array();
	$classId = $REQUEST_DATA['class1']; //8
	$page = $REQUEST_DATA['page']; //8
	$graceMarksValue = $REQUEST_DATA['showGraceMarks'];
	$subjectsArray = $studentReportsManager->getClassMarksTotalSubjects($classId);
	$limit = ($page - 1) * RECORDS_PER_PAGE.','.RECORDS_PER_PAGE;
	$subjectsArrayNew = array();

	foreach($subjectsArray as $subjectRecord) {
		$subjectId = $subjectRecord['subjectId'];
		$subjectCode = $subjectRecord['subjectCode'];
		$subjectMarksArray = $studentReportsManager->getSubjectMarks($classId,$subjectId);
		$thisArray = array_merge($subjectRecord, $subjectMarksArray[0]);
		$subjectsArrayNew[] = $thisArray;
	}

	$allDetailsArray['subjects'] = $subjectsArrayNew;
	$totalRecordsArray = $studentReportsManager->countTotalMarksData($classId,'');
	$totalRecords = $totalRecordsArray[0]['count'];
	$allDetailsArray['totalStudents'] = $totalRecords;
	
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
	$finalDataArray = null;
	$cnt = count($resultDataArray);
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$resultDataArray[$i]);
    }

	$allDetailsArray['resultData'] = $valueArray;

	echo json_encode($allDetailsArray);



// $History: scInitTotalMarksReport.php $
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 2/15/10    Time: 12:23p
//Updated in $/Leap/Source/Library/ScStudentReports
//done changes to implement multi-institute in SC
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 12/11/09   Time: 1:22p
//Updated in $/Leap/Source/Library/ScStudentReports
//done changes to pick grade [missed earlier by mistake]
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 3/10/09    Time: 12:33p
//Updated in $/Leap/Source/Library/ScStudentReports
//files modified as per database changes in ".SC_TEST_TABLE.",
//".TOTAL_TRANSFERRED_MARKS_TABLE." tables
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 2/16/09    Time: 4:29p
//Updated in $/Leap/Source/Library/ScStudentReports
//changed query part
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 2/11/09    Time: 2:02p
//Updated in $/Leap/Source/Library/ScStudentReports
//added code for attendance
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 1/09/09    Time: 6:46p
//Updated in $/Leap/Source/Library/ScStudentReports
//changed query to fetch data to 3 decimals
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 12/17/08   Time: 1:50p
//Updated in $/Leap/Source/Library/ScStudentReports
//changed query to make report better
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 12/01/08   Time: 11:25a
//Updated in $/Leap/Source/Library/ScStudentReports
//changed query, removed linking with sc_student_grades
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 11/28/08   Time: 1:42p
//Created in $/Leap/Source/Library/ScStudentReports
//file added for total marks report
//
//




?>