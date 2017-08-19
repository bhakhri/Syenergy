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
    require_once(MODEL_PATH . "/FineManager.inc.php");
    $fineManager = FineManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    
define('MODULE','COMMON');
define('ACCESS','view');
global $sessionHandler; 
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
  UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();


    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
        /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter =' HAVING fineCategoryAbbrs LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR userNames LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR roleNames LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%"';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'roleName';
    
    if($REQUEST_DATA['sortField']=='userNames'){
        $sortF='userName';
    }
    elseif($REQUEST_DATA['sortField']=='roleNames'){
        $sortF='roleName';
    }
    elseif($REQUEST_DATA['sortField']=='fineCategoryAbbrs'){
        $sortF='fineCategoryAbbr';
    }
    else{
        $sortF='roleName';
    }
    $orderBy = " $sortF $sortOrderBy";


    $recordArray = $fineManager->getMappedFineList($filter,$orderBy,'');

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Assign Role to Fines Report');
    $reportManager->setReportInformation("Search By: ".trim($REQUEST_DATA['searchbox']));
	 
	$reportTableHead						=	array();
	$reportTableHead['srNo']				=   array('#','width="2%"', "align='center' ");
    $reportTableHead['roleNames']            =   array('Role','width=10% align="left"', 'align="left"');
	$reportTableHead['fineCategoryAbbrs']	=   array('Fines to be Taken','width=45% align="left"', 'align="left"');
	$reportTableHead['userNames']		    =   array('Approver','width="35%" align="left" ', 'align="left"');
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: assignFineToRolePrint.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 29/07/09   Time: 11:16
//Updated in $/LeapCC/Templates/Fine
//Done bug fixing.
//bug ids---
//0000739,0000740,0000746,0000747,0000748,0000752
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 27/07/09   Time: 16:05
//Created in $/LeapCC/Templates/Fine
//Done bug fixing.
//bug ids---0000697 to 0000702
?>