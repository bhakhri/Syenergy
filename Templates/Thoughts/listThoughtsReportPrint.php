<?php 
//This file is used as printing version for thoughts.
//
// Author :PArveen Sharma
// Created on : 20-03-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ThoughtsMaster');
    define('ACCESS','view');

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/ThoughtsManager.inc.php");
    $thoughtsManager = ThoughtsManager::getInstance();

    /////////////////////////
    
     $search = $REQUEST_DATA['searchbox'];    
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (thought LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'thought';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    $recordArray = $thoughtsManager->getThoughtsList($filter,'',$orderBy);
    $cnt = count($recordArray);
    
    for($i=0;$i<$cnt;$i++) {
        $valueArray[] = array_merge(array('srNo' => $i+1),$recordArray[$i]);  
    }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Thoughts Report');
    if($search!='') {
      $reportManager->setReportInformation("Search By: $search");
	}
     
	$reportTableHead					   =	array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']			   =	array('#','width="3%"', "align='center' ");
    $reportTableHead['thought']            =   array('Thoughts','width=95% align="left"', 'align="left"');
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: listThoughtsReportPrint.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/11/09    Time: 12:05p
//Updated in $/LeapCC/Templates/Thoughts
//spelling correct
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/20/09    Time: 11:39a
//Created in $/LeapCC/Templates/Thoughts
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/20/09    Time: 11:35a
//Created in $/Leap/Source/Templates/Thoughts
//file added
//

?>