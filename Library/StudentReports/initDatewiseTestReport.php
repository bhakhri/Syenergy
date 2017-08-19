<?php
//--------------------------------------------------------
//This file returns the array of subjects, based on class
//
// Author :Ajinder Singh
// Created on : 13-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportsManager = StudentReportsManager::getInstance();
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MANAGEMENT_ACCESS',1);
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    
    $fromDate = add_slashes($REQUEST_DATA['fromDate']);
    $toDate= add_slashes($REQUEST_DATA['toDate']);
     
    $classId = add_slashes($REQUEST_DATA['degreeId']);
    $subjectId = add_slashes($REQUEST_DATA['subjectId']);
    $groupId = add_slashes($REQUEST_DATA['groupId']);
    $testTypeCategoryId= add_slashes($REQUEST_DATA['testTypeCategoryId']);
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'testType';
    
    $orderBy =" $sortField $sortOrderBy";
    
    
    $recordArray = array();
    $conditions = '';
	if(strtolower($classId) != 'all'){
    
    $conditions.=" AND t.classId='$classId' ";
    
    if(strtolower($subjectId) != 'all') {
        $conditions.=" AND s.subjectId='$subjectId' ";
    }     
    
    if(strtolower($groupId) != 'all'){
        $conditions.=" AND gr.groupId='$groupId' ";
    }
    
    if(strtolower($testTypeCategoryId) != 'all'){
        $conditions.=" AND t.testTypeCategoryId='$testTypeCategoryId' ";
    }
	}
	else{
		
		if(strtolower($testTypeCategoryId) != 'all'){
        $conditions.=" AND t.testTypeCategoryId='$testTypeCategoryId' ";
    }
	}
    
    $conditions.=" AND t.insertDate BETWEEN '$fromDate' AND '$toDate' ";
    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

    
    $orderBy ="   $sortField $sortOrderBy";

    $recordArray = $studentReportsManager->getCountTestRecord($conditions);
    $cnt = $recordArray[0]['cnt'];
$recordArray2 = $studentReportsManager->getTestRecordNew($conditions,$orderBy);
$cnt2 = count($recordArray2);
    $recordArray1 = $studentReportsManager->getTestRecordNew($conditions,$orderBy,$limit);
    $cnt1 = count($recordArray1);

    $valueArray = array();
	for($i=0; $i<$cnt1; $i++) {
       $recordArray1[$i]['testDate'] = UtilityManager::formatDate($recordArray1[$i]['testDate']);
       $valueArray = array_merge(array('srNo' => ($records+$i+1)),$recordArray1[$i]);
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$REQUEST_DATA['sortOrderBy'].'","sortField":"'.$REQUEST_DATA['sortField'].'","totalRecords":"'.$cnt2.'","page":"'.$page.'","info" : ['.$json_val.']}'; 

// $History: initDatewiseTestReport.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/14/09   Time: 3:25p
//Updated in $/LeapCC/Library/StudentReports
//class base format updated
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/10/09    Time: 5:38p
//Updated in $/LeapCC/Library/StudentReports
//Gurkeerat: updated access defines
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/25/09    Time: 4:43p
//Updated in $/LeapCC/Library/StudentReports
//report format update 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/19/09    Time: 5:21p
//Created in $/LeapCC/Library/StudentReports
//file added
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/19/09    Time: 2:09p
//Updated in $/Leap/Source/Library/ScStudentReports
//search for & condition update
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/29/09    Time: 1:20p
//Updated in $/Leap/Source/Library/ScStudentReports
//issue fix
//


?>
