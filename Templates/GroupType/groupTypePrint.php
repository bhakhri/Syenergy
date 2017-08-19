<?php 
//This file is used as printing version for blocks.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/GroupTypeManager.inc.php");
    $groupTypeManager = GroupTypeManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    
    define('MODULE','GroupTypeMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

    
     /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $search=trim($REQUEST_DATA['searchbox']); 
       $filter = ' WHERE (groupTypeName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR groupTypeCode LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'groupTypeName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $groupTypeManager->getTotalGroupType($filter);
    $groupTypeRecordArray = $groupTypeManager->getGroupTypeList($filter,'',$orderBy);
    $cnt = count($groupTypeRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray[] = array_merge(array('action' => $groupTypeRecordArray[$i]['groupTypeId'] , 
                                        'srNo' => ($records+$i+1) ),$groupTypeRecordArray[$i]);
    }
   
 
	$reportManager->setReportWidth(780);
	$reportManager->setReportHeading('Group Type Master Report');
    $reportManager->setReportInformation("SearchBy: $search");
   
	$reportTableHead				   = array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']		    =  array('#','width="3%" align="left"', "align='left' ");
    $reportTableHead['groupTypeName']   =  array('Group Type Name','width=30% align="left"', 'align="left"');
    $reportTableHead['groupTypeCode']   =  array('Abbr.','width=20% align="left"', 'align="left"');
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

?>