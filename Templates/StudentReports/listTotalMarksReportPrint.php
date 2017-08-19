<?php
//This file is used as printing version for testwise marks report.
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
	require_once(BL_PATH . "/UtilityManager.inc.php");
	UtilityManager::ifNotLoggedIn();
	UtilityManager::headerNoCache();
	$studentReportsManager = StudentReportsManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

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
	$finalDataArray = null;
	$cnt = count($resultDataArray);
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$resultDataArray[$i]);
    }


	$allDetailsArray['resultData'] = $valueArray;

   $classNameArray = $studentReportsManager->getSingleField('class', 'SUBSTRING_INDEX(className,"-",-3) as className', "where classId  = $classId");
   $className = $classNameArray[0]['className'];
   $className2 = str_replace("-",' ',$className);


	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Total Marks Report');
	$reportManager->setReportInformation("Degree: $className2");

	$reportTableHead							=	array();
					//associated key				  col.label,		   col. width,	  data align
	$reportTableHead['srNo']					=	array('#',			  'width="2%" align=right rowspan="3"', "align='right' ");
	$reportTableHead['rollNo']					=	array('Roll No.',	  'width=8% align="left" rowspan="3"' , 'align="left"');
	$reportTableHead['studentName']				=	array('Student Name', 'width="20%" rowspan="3" align="left"', 'align="left"');


	$reportManager->setRecordsPerPage(25);
	$reportManager->setReportWidth(1500);
	$reportManager->setReportData($reportTableHead, $allDetailsArray);
	$reportManager->showTotalMarksReport();

//$History : scListTotalMarksReportPrint.php $
//

?>