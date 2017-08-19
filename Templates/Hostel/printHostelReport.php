 <?php 
//This file is used as printing version for designations.
//
// Author :Jaineesh
// Created on : 13-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','HostelMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/HostelManager.inc.php");
    $hostelManager = HostelManager::getInstance();

	$search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {

		if(strtolower(trim($REQUEST_DATA['searchbox']))=='girls') {
           $type=1;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='boys') {
           $type=2;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='mixed') {
           $type=3;
       }
	   else {
		   $type=-1;
	   }
       $filter = ' WHERE (hostelName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR hostelCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR totalCapacity LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%"
       OR hostelType LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR floorTotal LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR roomTotal LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR wardenName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR wardenContactNo LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR hostelType LIKE "'.$type.'%" )';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'hostelName';
    
     $orderBy = " $sortField $sortOrderBy";
     
	$hostelArray = $hostelManager->getHostelList($filter,'',$orderBy);

		$recordCount = count($hostelArray);
		
		$hostelPrintArray[] =  Array();
		if($recordCount >0 && is_array($hostelArray) ) { 
			
			for($i=0; $i<$recordCount; $i++ ) {
				$hostelArray[$i]['hostelType']=$hostelTypeArr[$hostelArray[$i]['hostelType']];
				$valueArray[] = array_merge(array('srNo' => ($i+1) ),$hostelArray[$i]);
			
			}
		}
                           
    $reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Hostel Report ');
	$reportManager->setReportInformation("Search By : $search");

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#','width="4%" align="left"', "align='left'");
    $reportTableHead['hostelName']			=    array('Hostel Name ',' width=15% align="left" ','align="left" ');
    $reportTableHead['hostelCode']			=    array('Abbr.',' width="15%" align="left" ','align="left"');
	$reportTableHead['hostelType']			=    array('Type',' width="15%" align="left" ','align="left"');
    $reportTableHead['floorTotal']          =    array('No. of Floors',' width="15%" align="right" ','align="right"');
    $reportTableHead['roomTotal']           =    array('No. of Rooms',' width="15%" align="right" ','align="right"');
    $reportTableHead['totalCapacity']       =    array('Total Capacity',' width="15%" align="right" ','align="right"');
    $reportTableHead['wardenName']       =    array('Warden Name',' width="15%" align="right" ','align="right"');
    $reportTableHead['wardenContactNo']       =    array('Warden Contact No.',' width="15%" align="right" ','align="right"');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
//
//*****************  Version 1  *****************
//User: Gurkeerat Sidhu     Date: 18/04/09   Time: 5:43p
//Updated in $/Leap/Source/Template/Hostel
//added new fields (floorTotal,hostelType,totalCapacity) 
?>
