<?php 
//This file is used as printing version for users.
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/ManageUserManager.inc.php");
    $manageUserManager = ManageUserManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

     $conditions = '';
    //search filter
     $filter = '';
     if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = 'HAVING (userName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR
                          roleUserName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                          roleName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR
                          displayName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';         
     }
    
     $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
     $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'userName';
     $orderBy = " $sortField $sortOrderBy";  
   

    $recordArray = $manageUserManager->getUserList($filter,'',$orderBy);
    $cnt = count($recordArray);
	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        if($recordArray[$i]['userStatus']==1){
          $recordArray[$i]['userStatus']='Yes';
        }
        else{
          $recordArray[$i]['userStatus']='No';
        }
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
    }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('User Report');
    
    if($REQUEST_DATA['searchbox']!='') {
        $reportManager->setReportInformation("SearchBy: ".$REQUEST_DATA['searchbox']);
    }
	 
    $reportTableHead                        =    array();
    //associated key                  col.label,            col. width,      data align    
    $reportTableHead['srNo']                =    array('#','width="5%"', "align='center' ");
    $reportTableHead['userName']            =   array('User Name','width=15% align="left"', 'align="left"');
    $reportTableHead['roleName']            =   array('Role Name','width=20% align="left"', 'align="left"');
    $reportTableHead['roleUserName']        =   array('Name','width=30% align="left"', 'align="left"');
    $reportTableHead['displayName']         =   array('Display Name','width=30% align="left"', 'align="left"');
    $reportTableHead['userStatus']          =   array('Active','width=10% align="center"', 'align="center"');
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: manageUserPrint.php $
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 12/14/09   Time: 6:04p
//Updated in $/LeapCC/Templates/ManageUser
//updated code to add new field 'name' that shows name of user
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 28/07/09   Time: 17:53
//Updated in $/LeapCC/Templates/ManageUser
//Added "userStatus" field in manage user module and added the check in
//login page that if a user is in active then he/she can not login
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/28/09    Time: 4:40p
//Updated in $/LeapCC/Templates/ManageUser
//New File Added in displayName
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/09/08   Time: 11:06a
//Updated in $/LeapCC/Templates/ManageUser
//employeeId is added
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/ManageUser
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 10/24/08   Time: 12:02p
//Updated in $/Leap/Source/Templates/ManageUser
//Corrected default sorting field
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/24/08   Time: 11:46a
//Created in $/Leap/Source/Templates/ManageUser
//Added functionality for manage user report print
?>