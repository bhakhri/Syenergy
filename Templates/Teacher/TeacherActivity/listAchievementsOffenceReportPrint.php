<?php 
//  This File contains student Achievements/Offence Details Print 
// Author :Parveen Sharma
// Created on : 29-05-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifTeacherNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentManager = StudentReportsManager::getInstance();
    
    require_once($FE . "/Library/HtmlFunctions.inc.php");
    $htmlManager  = HtmlFunctions::getInstance();
    
    $condition = '';
    
    $employeeId=$sessionHandler->getSessionVariable('EmployeeId'); //as teacherId is EmployeeId      
    
    $reportHead = '';
    
    if(trim($REQUEST_DATA['classId'])!=''){
       $condition .=" AND cls.classId=".$REQUEST_DATA['classId'];
       $reportHead .= 'Class: '.$REQUEST_DATA['className'].'&nbsp;&nbsp;';
    }    
    
    if(trim($REQUEST_DATA['subjectId'])!=''){
        $condition .=" AND tt.subjectId=".$REQUEST_DATA['subjectId'];
        $reportHead .= 'Subject: '.$REQUEST_DATA['subjectName'].'&nbsp;&nbsp;';
    }    
    
    if(trim($REQUEST_DATA['groupId'])!=''){
        $condition .=" AND gr.groupId=".$REQUEST_DATA['groupId'];  
        $reportHead .= 'Group: '.$REQUEST_DATA['groupName'].'&nbsp;&nbsp;';
    }    
    
    if(trim($REQUEST_DATA['studentRollNo'])!=''){
        $condition .=' AND s.rollNo="'.add_slashes(trim($REQUEST_DATA['studentRollNo'])).'"';
        $reportHead .= 'Roll No: '.$REQUEST_DATA['studentRollNo'];   
    }    
    
    $condition .=" AND tt.employeeId=".$employeeId;
     
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'offenseName';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $resourceRecordArray = $studentManager->getStudentOffenceList($condition,$orderBy,'');
    $cnt = count($resourceRecordArray);
    for($i=0;$i<$cnt;$i++) {
       // add stateId in actionId to populate edit/delete icons in User Interface
       $resourceRecordArray[$i]['offenseDate']=UtilityManager::formatDate($resourceRecordArray[$i]['offenseDate']); 
       $valueArray[] = array_merge(array('srNo' => ($i+1)),$resourceRecordArray[$i]);
       $employee = 'Teacher Code:&nbsp;'.$resourceRecordArray[$i]['employeeCode'].'&nbsp;&nbsp;Teacher Name:&nbsp;'.$resourceRecordArray[$i]['employeeName']."<br>"; 
    }
    
    
    
	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Student Achievements/Offense Details Report');
    $reportManager->setReportInformation($employee);         
	 
	$reportTableHead				    =	array();
	$reportTableHead['srNo']		    =	array('#','width="2%"', "align='center' ");
	$reportTableHead['rollNo']	        =	array('Roll No','width="8%" align="left" ','align="left"');
	$reportTableHead['studentName']		=	array('Name','width=15% align="left"','align="left"');
	$reportTableHead['className']	    =	array("Class",'width="13%" align="left"', 'align="left"');
    $reportTableHead['groupName']       =   array("Group",'width="8%" align="left"', 'align="left"');
    $reportTableHead['offenseName']     =   array("Offense",'width="12%" align="left"', 'align="left"');
    $reportTableHead['offenseDate']     =   array("Date",'width="8%" align="center"', 'align="center"');
    $reportTableHead['reportedBy']      =   array("Reported By",'width="15%" align="left"', 'align="left"');
    $reportTableHead['remarks']         =   array("Remarks",'width="20%" align="left"', 'align="left"');

	$reportManager->setRecordsPerPage(40);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();
 
// $History: listAchievementsOffenceReportPrint.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/29/09    Time: 4:42p
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//employeeName, employee Code added in Report
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/29/09    Time: 4:15p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//initial checkin 
//
           
?>