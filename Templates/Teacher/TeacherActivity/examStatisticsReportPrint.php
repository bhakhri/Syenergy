<?php 
//This file is used as Print topic details 
//
// Author :Parveen Sharma
// Created on : 06-01-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    

    global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
	UtilityManager::ifTeacherNotLoggedIn(true);
	UtilityManager::headerNoCache();
	require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance(); 
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();

    $teacherId = $sessionHandler->getSessionVariable('EmployeeId');
	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
    
     $orderBy = " $sortField $sortOrderBy";

	$activeTimeTableLabelArray = $teacherManager->getActiveTimeTable();
	$activeTimeTableLabelId = $activeTimeTableLabelArray[0]['timeTableLabelId'];
	$teacherSubjectsArray = $teacherManager->getTeacherSubjects($activeTimeTableLabelId);
	$concatStr = '';
	foreach($teacherSubjectsArray as $teacherSubjectRecord) {
		$subjectId = $teacherSubjectRecord['subjectId'];
		$classId = $teacherSubjectRecord['classId'];
		if ($concatStr != '') {
			$concatStr .= ',';
		}
		$concatStr .= "'$subjectId#$classId'";
	}
	$teacherTestsArray = $teacherManager->getTeacherTests($teacherId, $concatStr,$orderBy);

	$cnt = count($teacherTestsArray);
    
    $k=0;
    for($i=0; $i<$cnt; $i++) {
        // Findout Topics & Pending Topics List 
        $className = $teacherTestsArray[$i]['className'];    
        $subjectCode = $teacherTestsArray[$i]['subjectCode'];
        $groupShort = $teacherTestsArray[$i]['groupShort'];
        $testName   = $teacherTestsArray[$i]['testName'];
        $testDate     = UtilityManager::formatDate($teacherTestsArray[$i]['testDate']);
        $maxMarks   = $teacherTestsArray[$i]['maxMarks']; 
		$maxMarksScored = $teacherTestsArray[$i]['maxMarksScored'];
		$minMarksScored=  $teacherTestsArray[$i]['minMarksScored'];
		$avgMarks = $teacherTestsArray[$i]['avgMarks'];
		$presentCount = $teacherTestsArray[$i]['presentCount'];
		$absentCount = $teacherTestsArray[$i]['absentCount'];   
        
		   
        $valueArray[] = array_merge(array('srNo' => ($records+$k+1),
                                          'className'   => $className, 
                                          'subjectCode' => $subjectCode,
                                          'groupShort' => $groupShort,
                                          'testName'  => $testName , 
                                          'testDate' => $testDate,
										   'maxMarks' => $maxMarks,
											'maxMarksScored' =>$maxMarksScored,
											'minMarksScored'=> $minMarksScored,
											'avgMarks'=> $avgMarks,
											'presentCount'=> $presentCount,
											'absentCount'=> $absentCount ) );
          $k++;
    }

    
         
    $reportHead  = "Employee Name&nbsp;:&nbsp;".$REQUEST_DATA['employeeName'];
    $reportHead .= ",&nbsp;&nbsp;Employee Code&nbsp;:&nbsp;".$REQUEST_DATA['employeeCode'];
     
    $reportManager->setReportWidth(780);
    $reportManager->setReportHeading('Exam Statistics Report');
    $reportManager->setReportInformation($reportHead); 
    $reportTableHead = array();
    
    
                    //associated key                  col.label,         col. width,      data align        
    $reportTableHead['srNo']          =    array('#',      'width="2%"  align="center"', 'align="center" valign="top"');
    $reportTableHead['className']     =    array('Class',   'width=17%   align="left" ','align="left" valign="top"  ');
    $reportTableHead['subjectCode']     =    array('Subject',    'width="9%" align="left" ','align="left" valign="top" ');
	$reportTableHead['groupShort']     =    array('Group',    'width="9%" align="left" ','align="left" valign="top" ');
    $reportTableHead['testName']   =    array('Test',  'width=15%   align="left" ','align="left" valign="top"  ');
    $reportTableHead['testDate']   =    array('Test Date',  'width="10%" align="left" ','align="left" valign="top" ');
    $reportTableHead['maxMarks']     =    array('M.Marks', 'width="15%" align="right" ','align="right" valign="top" ');
    $reportTableHead['maxMarksScored']       =    array('Max.Scored',       'width="15%" align="right" ','align="right" valign="top" ');
	$reportTableHead['minMarksScored']       =    array('Min.Scored',       'width="15%" align="right" ','align="right" valign="top" ');
	$reportTableHead['avgMarks']       =    array(' Avg.',       'width="20%" align="right" ','align="right" valign="top" ');
	$reportTableHead['presentCount']       =    array('Pre.',       'width="25%" align="right" ','align="right" valign="top" ');
	$reportTableHead['absentCount']       =    array('Ab.',       'width="25%" align="right" ','align="right" valign="top" ');
	


    $reportManager->setRecordsPerPage(10);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 
 
// $History: topicwiseReportPrint.php $
//
//*****************  Version 6  *****************
//User: Parveen      Date: 12/14/09   Time: 12:25p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//subjectTopicId  null check added
//
//*****************  Version 5  *****************
//User: Parveen      Date: 12/01/09   Time: 11:48a
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//report width updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/23/09   Time: 2:36p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//sorting order updated 
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/23/09   Time: 2:13p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//topicswise report format updated (classname added)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 10/01/09   Time: 10:47a
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//check attendance, marks condition updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 10/01/09   Time: 10:33a
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//file added
//
//*****************  Version 7  *****************
//User: Parveen      Date: 9/16/09    Time: 6:00p
//Updated in $/LeapCC/Templates/EmployeeReports
//report formatting updated (condition changes)
//
//*****************  Version 6  *****************
//User: Parveen      Date: 7/17/09    Time: 4:02p
//Updated in $/LeapCC/Templates/EmployeeReports
//record limits remove,format & new enhancements added
//
//*****************  Version 5  *****************
//User: Parveen      Date: 7/17/09    Time: 2:41p
//Updated in $/LeapCC/Templates/EmployeeReports
//role permission,alignment, new enhancements added 
//
//*****************  Version 4  *****************
//User: Parveen      Date: 7/15/09    Time: 1:08p
//Updated in $/LeapCC/Templates/EmployeeReports
//file system change, condition, formating & new enhancements added
//(Workshop)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/26/09    Time: 4:55p
//Updated in $/LeapCC/Templates/EmployeeReports
//code updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/24/09    Time: 6:01p
//Updated in $/LeapCC/Templates/EmployeeReports
//report heading updated (employeeName, employeeCode Added)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/17/09    Time: 3:37p
//Created in $/LeapCC/Templates/EmployeeReports
//initial checkin
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/10/09    Time: 5:33p
//Updated in $/Leap/Source/Templates/ScEmployeeReports
//condition, formatting & validation updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/18/09    Time: 1:20p
//Created in $/Leap/Source/Templates/ScEmployeeReports
//initial checkin 
//

?>