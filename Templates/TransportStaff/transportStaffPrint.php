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
    require_once(MODEL_PATH . "/TransportStaffManager.inc.php");
    $tranportManager = TransportStaffManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";

    /// Search filter /////
    if(strtoupper(trim(trim($REQUEST_DATA['searchbox'])))=='DRIVER' ){
         $trType=1;  
       }
    elseif(strtoupper(trim(trim($REQUEST_DATA['searchbox'])))=='CONDUCTOR'){
         $trType=2;  
    }
    elseif(strtoupper(trim(trim($REQUEST_DATA['searchbox'])))=='OTHER'){
         $trType=3;  
    }
    else{
        $trType=-1;
    }
    
    if(strtoupper(trim(trim($REQUEST_DATA['searchbox'])))=='YES' ){
         $verificationDone = 1;  
       }
    elseif(strtoupper(trim(trim($REQUEST_DATA['searchbox'])))=='NO'){
     $verificationDone = 0;
    }
    else{
      $verificationDone = -1;
    }
        
    if(UtilityManager::notEmpty(trim($REQUEST_DATA['searchbox']))) {
       $conditions = ' WHERE  ( name LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR staffCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR staffType LIKE "'.$trType.'%" OR DATE_FORMAT(dlExpiryDate,"%d-%b-%y") LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR  DATE_FORMAT(joiningDate,"%d-%b-%y") LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%"OR dlNo LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR verificationDone LIKE "'.$verificationDone.'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'name';
    
    $orderBy = " $sortField $sortOrderBy"; 


    $recordArray = $tranportManager->getTransportStaffList($conditions,$orderBy,'');

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

	$cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $recordArray[$i]['dlExpiryDate'] = UtilityManager::formatDate($recordArray[$i]['dlExpiryDate']);
        $recordArray[$i]['joiningDate']  = UtilityManager::formatDate($recordArray[$i]['joiningDate']);
        $recordArray[$i]['staffType']    = $transportStaffTypeArr[$recordArray[$i]['staffType']];
        
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Transport Staff Report');
    $reportManager->setReportInformation("SearchBy: ".trim($REQUEST_DATA['searchbox']));
	 
	$reportTableHead						   =   array();
	$reportTableHead['srNo']                   =   array('#','width="1%"', "align='center' ");
    $reportTableHead['name']                   =   array('Staff','width=12% align="left"', 'align="left"');
    $reportTableHead['staffCode']              =   array('Code','width=8% align="left"', 'align="left"');
    $reportTableHead['joiningDate']            =   array('Joining Date','width="8%" align="center" ', 'align="center"');
    $reportTableHead['staffType']              =   array('Type','width="8%" align="left" ', 'align="left"');
    $reportTableHead['verificationDone']       =   array('Verification Done','width="7%" align="center" ', 'align="center"');
    $reportTableHead['dlNo']                   =   array('License','width="8%" align="left" ', 'align="left"');
    $reportTableHead['dlExpiryDate']           =   array('Expiry Date','width="8%" align="center" ', 'align="center"');
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: transportStaffPrint.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/17/09   Time: 3:41p
//Updated in $/Leap/Source/Templates/TransportStaff
//put DL image in transport staff and changes in modules
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/10/09   Time: 4:15p
//Updated in $/Leap/Source/Templates/TransportStaff
//add new fields and upload image
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 4/08/09    Time: 10:39
//Created in $/Leap/Source/Templates/TransportStuff
//done bug fixing.
//bug ids---
//0000844,0000845,0000847,0000850,000843
?>