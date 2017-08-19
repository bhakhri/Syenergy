<?php 
// This file is used as printing version for TestType.
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

    $roleId=$sessionHandler->getSessionVariable('RoleId');
    
    $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
    $classId=trim($REQUEST_DATA['classId']);
    $subjectId=trim($REQUEST_DATA['subjectId']);
    $groupId=trim($REQUEST_DATA['groupId']);
    
    if($timeTableLabelId=='' or $classId==''){
        die('Required Parameters Missing');
    }
	
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    if($roleId==2){
      $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subjectCode';
    }
    else{
      $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';
    }

    $orderBy=" $sortField $sortOrderBy"; 

    $filter =' AND asg.classId='.$classId.' AND ttc.timeTableLabelId='.$timeTableLabelId;
    
    if($subjectId!='' and $subjectId!=-1){
        $filter .=' AND asg.subjectId='.$subjectId;
    }
    
    if($groupId!='' and $groupId!=-1){
        $filter .=' AND asg.groupId='.$groupId;
    }
    
    if($roleId==2){
       $filter .=' AND asg.employeeId='.$sessionHandler->getSessionVariable('EmployeeId');    
    }

    $recordArray = $teacherManager->getAssignmentList($filter,' ',$orderBy);

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
    }

    $search ="Time Table : ".trim($REQUEST_DATA['timeTableLabelName'])."  Class : ".trim($REQUEST_DATA['className'])." Subject : ".trim($REQUEST_DATA['subjectName'])."  Group : ".trim($REQUEST_DATA['groupName']);
    
	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Assignment Report');
    $reportManager->setReportInformation("Search By: $search");
	 
	$reportTableHead					  =	  array();
	$reportTableHead['srNo']			  =   array('#','width=2% align="left"', 'align="left"');
    if($roleId!=2){
      $reportTableHead['employeeName']    =   array('Teacher','width=15% align="left"', 'align="left"');  
    }
    $reportTableHead['subjectCode']       =   array('Subject','width=5% align="left"', 'align="left"');
	$reportTableHead['groupName']		  =   array('Group','width=5% align="left"', 'align="left"');
	$reportTableHead['topicTitle']		  =   array('Topic','width="15%" align="left" ', 'align="left"');
    $reportTableHead['topicDescription']  =   array('Description','width="30%" align="left" ', 'align="left"');
    $reportTableHead['assignedOn']        =   array('Assigned','width="5%" align="center" ', 'align="center"');
    $reportTableHead['totalAssigned']     =   array('Total','width="5%" align="right" ', 'align="right"');
    
	$reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: testTypePrint.php $
?>