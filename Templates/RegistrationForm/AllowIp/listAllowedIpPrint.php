 <?php  
//This file is used as CSV version for display countries.
//
// Author :Abhay Kant
// Created on : 22-June-2011
// Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
   define('MODULE','AllowIp');
define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/RegistrationForm/AllowIpManager.inc.php");
    $allowIpManager = AllowIpManager::getInstance();
	
   require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    
    
	/// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
        $filter = " WHERE allowIPNo LIKE '%".$searchBoxData."'";
    }
  $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'allowIPNo';
    $orderBy = " $sortField $sortOrderBy";         

	
    $totalArray = $allowIpManager->getTotalIp($filter);
    $ipRecordArray = $allowIpManager->getIpList($filter,$limit,$orderBy);
    $cnt = count($ipRecordArray);
    
 
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray[] = array_merge(array('srNo'=>($i+1)),$ipRecordArray[$i]);
    }
    
    $search = add_slashes(trim($REQUEST_DATA['searchbox']));
    
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Allowed IP Report ');
	//$reportManager->setReportInformation("Search by: ".$search);
	

    $reportTableHead                    =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']		=    array('#',' width="2%"  align="left"', "align='left'");
    $reportTableHead['allowIPNo']	    =    array('Allowed IP No. ',' width=10%   align="left" ','align="left" ');
 

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
