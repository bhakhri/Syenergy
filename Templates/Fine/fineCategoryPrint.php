<?php 
// This file is used as printing version for fine categories.
// Author :Dipanjan Bhattacharjee
// Created on : 02.07.2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/FineManager.inc.php");
    $fineCategoryManager = FineManager::getInstance();

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
    
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();

    //search filter
    $search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if (!empty($search)) {
         $conditions = ' WHERE ( fineCategoryName LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR fineCategoryAbbr LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" )';
    }

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'fineCategoryName';

    $orderBy=" $sortField $sortOrderBy"; 


    $recordArray = $fineCategoryManager->getFineCategoriesList($conditions,$orderBy,'');

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Fine Category Report');
    $reportManager->setReportInformation("Search By: $search");
	 
	$reportTableHead						=	array();
	$reportTableHead['srNo']				=	array('#','width="2%" align="left"', "align='left' ");
    $reportTableHead['fineCategoryName']    =   array('Category Name','width=50% align="left"', 'align="left"');
	$reportTableHead['fineCategoryAbbr']	=   array('Category Abbr.','width=40% align="left"', 'align="left"');
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: fineCategoryPrint.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 3/09/09    Time: 11:37
//Updated in $/LeapCC/Templates/Fine
//Done bug fixing.
//Bug ids---
//00001407,00001408,00001409,
//00001419,00001420
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/07/09    Time: 16:46
//Updated in $/LeapCC/Templates/Fine
//Changes html and model files names in "Fine  Category" module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/07/09    Time: 15:31
//Created in $/LeapCC/Templates/Fine
//Added files for "fine_category" module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 2/07/09    Time: 16:08
//Created in $/LeapCC/Templates/FineCategory
//Created "Fine Category Master" module
?>