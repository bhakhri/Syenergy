 <?php 
//This file is used as CSV version for display countries.
//
// Author :Parveen Sharma
// Created on : 13-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
   define('MODULE','StateMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StatesManager.inc.php");
    $stateManager = StatesManager::getInstance();
	
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    
    
	/// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (st.stateName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR st.stateCode LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR cnt.countryName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'stateName';
    
    $orderBy = " $sortField $sortOrderBy";

     

    $stateRecordArray = $stateManager->getStateList($filter,'',$orderBy);  
    $cnt = count($stateRecordArray);
       

    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray[] = array_merge(array('srNo'=>($i+1)),$stateRecordArray[$i]);
    }
    
    $search = add_slashes(trim($REQUEST_DATA['searchbox']));
    
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('State Master Report Print');
	$reportManager->setReportInformation("Search by: ".$search);
	

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#',                ' width="2%"  align="left"', "align='left'");
    $reportTableHead['stateName']			=    array('State Name ',      ' width=33%   align="left" ','align="left" ');
    $reportTableHead['stateCode']	        =    array('State Code',       ' width="25%" align="left" ','align="left"');
	$reportTableHead['countryName']		    =    array('Country Name',     ' width="30%" align="left" ','align="left"');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
