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
     define('MODULE','PublicationMaster'); 
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/PublicationManager.inc.php");
    $PublicationManager =PublicationManager::getInstance();
	
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    
    
	/// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (publicationName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'publicationName';
    
    $orderBy = " $sortField $sortOrderBy";  
     

    $publicationRecordArray = $PublicationManager->getPublicationList($filter,'',$orderBy);
    $cnt = count($publicationRecordArray);
       

    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray[] = array_merge(array('srNo'=>($i+1)),$publicationRecordArray[$i]);
    }
    
    $search = add_slashes(trim($REQUEST_DATA['searchbox']));
    
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Publication Master Report Print');
	$reportManager->setReportInformation("Search by: ".$search);
	

    $reportTableHead                            =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']			     	=    array('#',                ' width="2%"  align="left"', "align='left'");
    $reportTableHead['publicationName']			=    array('Publication Name ',    ' width=33%   align="left" ','align="left" ');
  
    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
