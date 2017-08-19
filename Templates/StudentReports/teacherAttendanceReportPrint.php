<?php 
//This file is used as printing version for TestType.
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportsManager = StudentReportsManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	
    $labelId=trim($REQUEST_DATA['labelId']);
    $classId=trim($REQUEST_DATA['classId']);
    $employeeId=trim($REQUEST_DATA['employeeId']);
    $fromDate=trim($REQUEST_DATA['fromDate']);
    $toDate=trim($REQUEST_DATA['toDate']);
    $groupId=trim($REQUEST_DATA['groupId']);
    $chkHierarchy=trim($REQUEST_DATA['chkHierarchy']);
    
    if($chkHierarchy=='') {
      $chkHierarchy=0;  
    }
     
    
    
    if($labelId=='' or $classId=='' or $employeeId=='' or $fromDate=='' or $toDate=='' or $groupId==''){
        echo 'Required Parametes Missing';
        die;
    }
    
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
    
    $orderBy = " ORDER BY $sortField $sortOrderBy";         

    ////////////
    
    $filter =' AND ttc.timeTableLabelId='.$labelId.' AND c.classId='.$classId.' AND ( att.fromDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" AND att.toDate BETWEEN "'.$fromDate.'" AND "'.$toDate.'" )';
    
    if($employeeId!=-1 and $employeeId!=''){
        $filter .=' AND e.employeeId='.$employeeId;
    }
    
    $groupConditions='';
    if($groupId!=-1){
       //find group hierarchy
       if($chkHierarchy==1) {   
         $groupHierarchyString=$studentReportsManager->getGroupHierarchy($classId,$groupId);
         $groupConditions=' AND att.groupId IN ('.$groupHierarchyString.')';
         $filter .=$groupConditions;
       }
       else {
         $filter .=' AND att.groupId IN ('.$groupId.')';   
       }
    }
    
    
    $teacherAttendanceRecordArray = $studentReportsManager->getTeacherAttendanceSummeryList($filter,' ',$orderBy);
    $cnt = count($teacherAttendanceRecordArray);

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($teacherAttendanceRecordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$teacherAttendanceRecordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Teacher Attendance Report');
    $search='Time Table :'.trim($REQUEST_DATA['labelName']).' Class :'.trim($REQUEST_DATA['className']).' Group :'.trim($REQUEST_DATA['groupName']).' Teacher :'.trim($REQUEST_DATA['employeeName']).' <br/>From :'.UtilityManager::formatDate(trim($REQUEST_DATA['fromDate'])).' To :'.UtilityManager::formatDate(trim($REQUEST_DATA['toDate']));
    $reportManager->setReportInformation("Search By: $search");
	 
	$reportTableHead					 =	array();
	$reportTableHead['srNo']			 =   array('#','width="2%"', "align='center' ");
    $reportTableHead['className']        =   array('Class','width=20% align="left"', 'align="left"');
    $reportTableHead['groupName']        =   array('Group','width=10% align="left"', 'align="left"');
	$reportTableHead['subjectCode']		 =   array('Subject Code','width=10% align="left"', 'align="left"');
    $reportTableHead['subjectName']      =   array('Subject Name','width=20% align="left"', 'align="left"');
	$reportTableHead['employeeName']	 =   array('Teacher','width="15%" align="left" ', 'align="left"');
	$reportTableHead['attendanceTaken']	 =   array('Delivered','width="15%" align="left" ', 'align="left"');
	$reportTableHead['adjustmentTaken']	 =   array('Adjustment','width="15%" align="left" ', 'align="left"');
    $reportTableHead['totalDelivered']   =   array('Tot Delivered','width="5%" align="right" ', 'align="right"');
	$reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: teacherAttendanceReportPrint.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 16/04/10   Time: 10:22
//Created in $/LeapCC/Templates/StudentReports
//Created "Teacher Attendance Report".This report is used to see total
//lectured delivered by a teacher for a subject within a specified date
//interval.
?>