<?php 
//This file is used as printing version for role.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/RoleManager.inc.php");
    $roleManager = UserRoleManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    


    //search filter
    $search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if (!empty($search)) {
        $conditions =' WHERE (rl.roleName LIKE "'.add_slashes($search).'%")';        
    }
    
    
	//$conditions = '';
	//if (count($conditionsArray) > 0) {
		//$conditions = ' AND '.implode(' AND ',$conditionsArray);
	//}

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'routeName';

	//$orderBy="a.$sortField $sortOrderBy"; 
    $orderBy="rl.$sortField $sortOrderBy"; 


	$totalArray  = $roleManager->getTotalRole($conditions);
    $recordArray = $roleManager->getRoleList($conditions,$orderBy,'');

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface 
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Role Report');
    $reportManager->setReportInformation("SearchBy: $search");
	 
	$reportTableHead						=	array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']				=	array('#','width="3%" align="left"', "align='left' ");
    $reportTableHead['roleName']            =   array('Role Name','width=95% align="left"', 'align="left"');
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: rolePrint.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 2/09/09    Time: 11:08
//Updated in $/LeapCC/Templates/Role
//Done bug fixing.
//Bug ids---
//00001398,00001399,00001400,00001401
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/20/09    Time: 2:00p
//Updated in $/LeapCC/Templates/Role
//added role permission module for user other than admin
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Role
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/24/08   Time: 11:31a
//Created in $/Leap/Source/Templates/Role
//Added functionality for role report print
?>