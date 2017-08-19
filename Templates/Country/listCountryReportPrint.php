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
     define('MODULE','CountryMaster'); 
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/CountryManager.inc.php");
    $countryManager =CountryManager::getInstance();
	
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    
    
	/// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (countryName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR countryCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR nationalityName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'countryName';
    
    $orderBy = " $sortField $sortOrderBy";  
     

    $countryRecordArray = $countryManager->getCountryList($filter,'',$orderBy);
    $cnt = count($countryRecordArray);
       

    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray[] = array_merge(array('srNo'=>($i+1)),$countryRecordArray[$i]);
    }
    
    $search = add_slashes(trim($REQUEST_DATA['searchbox']));
    
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Country Master Report Print');
	$reportManager->setReportInformation("Search by: ".$search);
	

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#',                ' width="2%"  align="left"', "align='left'");
    $reportTableHead['countryName']			=    array('Country Name ',    ' width=33%   align="left" ','align="left" ');
    $reportTableHead['countryCode']	        =    array('Country Code',     ' width="30%" align="left" ','align="left"');
	$reportTableHead['nationalityName']		=    array('Nationality',      ' width="25%" align="left" ','align="left"');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
