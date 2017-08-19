<?php 
//This file is used as printing version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/SubjectManager.inc.php");
    $SubjectManager = SubjectManager::getInstance();
    
       
    define('MODULE','Subject');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    


    // Search filter 
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       
       $hasA ='';
       $hasM ='';
       if(strtoupper(add_slashes($REQUEST_DATA['searchbox']))=='YES') {  
          $hasA = " OR hasAttendance = 1 ";
          $hasM = " OR hasMarks = 1 ";
       }
       else
       if(strtoupper(add_slashes($REQUEST_DATA['searchbox']))=='NO') {  
          $hasA = " OR hasAttendance = 0 ";
          $hasM = " OR hasMarks = 0 ";
       }
       
     $filter .= ' AND (sub.subjectName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR sub.subjectCode LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR sub.subjectAbbreviation LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR categoryName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR subjectTypeName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%"'.$hasA.' '.$hasM.')';
    }
    
    
	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subjectName';

	//$orderBy="a.$sortField $sortOrderBy"; 
    $orderBy = " $sortField $sortOrderBy";   


    $recordArray = $SubjectManager->getSubjectList($filter,'',$orderBy);
 	$cnt = count($recordArray);
	
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
      // add stateId in actionId to populate edit/delete icons in User Interface 
  	  $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
    }

    $search = $REQUEST_DATA['searchbox'];
    
	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Subject Report');
    $reportManager->setReportInformation("SearchBy: $search");
	 
	$reportTableHead						   =  array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']				   =  array('#','width="3%" align="left"', "align='left' ");
    $reportTableHead['subjectName']            =  array('Subject Name','width=25% align="left"', 'align="left"');
	$reportTableHead['subjectCode']		       =  array('Subject Code','width=15% align="left"', 'align="left"');
	$reportTableHead['subjectAbbreviation']    =  array('Abbr.','width="15%" align="left" ', 'align="left"');
    $reportTableHead['subjectTypeName']        =  array('Subject Type','width="10%" align="left" ', 'align="left"');
	$reportTableHead['alternateSubjectName']        =  array('Alternate Subject Name','width="10%" align="left" ', 'align="left"');
	$reportTableHead['alternateSubjectCode']        =  array('Alternate Subject Code','width="10%" align="left" ', 'align="left"');
    $reportTableHead['categoryName']           =  array('Subject Category','width=15% align="left"', 'align="left"');
    $reportTableHead['hasAttendance']          =  array('Attendance','width=12% align="center"', 'align="center"'); 
    $reportTableHead['hasMarks']               =  array('Marks','width=12% align="center"', 'align="center"'); 
    
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: subjectPrint.php $
//
//*****************  Version 9  *****************
//User: Parveen      Date: 4/21/10    Time: 10:37a
//Updated in $/LeapCC/Templates/Subject
//�Attendance� and �Marks� fields display
//
//*****************  Version 8  *****************
//User: Parveen      Date: 10/20/09   Time: 11:16a
//Updated in $/LeapCC/Templates/Subject
//search by condition display 
//
//*****************  Version 7  *****************
//User: Parveen      Date: 10/06/09   Time: 11:26a
//Updated in $/LeapCC/Templates/Subject
//search condition checks updated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 9/23/09    Time: 3:27p
//Updated in $/LeapCC/Templates/Subject
//hasAttendance, hasMarks filed added
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/28/09    Time: 3:15p
//Updated in $/LeapCC/Templates/Subject
//condition & formating updated 
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/11/09    Time: 2:30p
//Updated in $/LeapCC/Templates/Subject
// issue fix 1012, 1011
//validation updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Templates/Subject
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/20/09    Time: 1:55p
//Updated in $/LeapCC/Templates/Subject
//new enhancement categoryId (link subject_category table) new field
//added 
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/12/08   Time: 16:58
//Created in $/LeapCC/Templates/Subject
//Added "Print" and "Export to excell" in subject and subjectType modules
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/12/08   Time: 16:01
//Updated in $/LeapCC/Templates/TestType
//Showing "weightage amount,weightage percentage and evaluation criteria"
//in list
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/TestType
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/24/08   Time: 2:10p
//Created in $/Leap/Source/Templates/TestType
//Added functionality for TestType report print and export to csv
?>