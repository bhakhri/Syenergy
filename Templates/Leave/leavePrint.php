<?php 
//This file is used as printing version for blocks.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/LeaveManager.inc.php");
    $leaveManager = LeaveManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    
    define('MODULE','LeaveMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    
    //search filter
  	if(UtilityManager::notEmpty(trim($REQUEST_DATA['searchbox']))) {

        if(strtolower(trim($REQUEST_DATA['searchbox']))=='yes') {
           $type=1;
        }
        elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='no') {
           $type=0;
        }
       else {
           $type=-1;                                    
       }
       
       $filter = ' AND (TRIM(leaveTypeName) LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" 
                        OR isActive LIKE "'.$type.'" OR carryForward LIKE "'.$type.'" OR reimbursed LIKE "'.$type.'" )';
    }
    $search=trim($REQUEST_DATA['searchbox']);   
    
	//$conditions = '';
	//if (count($conditionsArray) > 0) {
		//$conditions = ' AND '.implode(' AND ',$conditionsArray);
	//}

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'leaveTypeName';

	//$orderBy="a.$sortField $sortOrderBy"; 
    $orderBy="$sortField $sortOrderBy"; 


    $recordArray = $leaveManager->getLeaveList($filter,'',$orderBy);
	$cnt = count($recordArray);
    

	$valueArray = array();
    for($i=0;$i<$cnt;$i++) {
	    if($recordArray[$i]['isActive']==1){
            $recordArray[$i]['isActive']='Yes';
        }
        else if($recordArray[$i]['isActive']==0){
            $recordArray[$i]['isActive']='No';
        }
        if($recordArray[$i]['carryForward']==1){
            $recordArray[$i]['carryForward']='Yes';
        }
        else if($recordArray[$i]['carryForward']==0){
            $recordArray[$i]['carryForward']='No';
        }
        
        if($recordArray[$i]['reimbursed']==1){
            $recordArray[$i]['reimbursed']='Yes';
        }
        else if($recordArray[$i]['reimbursed']==0){
            $recordArray[$i]['reimbursed']='No';
        }
        // add stateId in actionId to populate edit/delete icons in User Interface 
		$valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }
   
 

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Leave Type Report');
    $reportManager->setReportInformation("SearchBy: $search");
    
   
	$reportTableHead				   = array();
	//associated key				  col.label,			col. width,	  data align	
	$reportTableHead['srNo']		   =  array('#','width="3%" align="left"', "align='left' ");
    $reportTableHead['leaveTypeName']  =  array('Leave Type Name','width=20% align="left"', 'align="left"');
    $reportTableHead['carryForward']   =  array('Carry Forward','width=20% align="center"', 'align="center"');
    $reportTableHead['reimbursed']     =  array('Reimbursement','width=20% align="center"', 'align="center"');
    $reportTableHead['isActive']	   =  array('Active','width=10% align="center"', 'align="center"');
	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: LeavePrint.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 4/08/09    Time: 16:01
//Updated in $/LeapCC/Templates/Leave
//Done bug fixing.
//bug ids--
//0000861 to 0000877
//
//*****************  Version 2  *****************
//User: Administrator Date: 12/06/09   Time: 19:25
//Updated in $/LeapCC/Templates/Leave
//Corrected display issues which are detected during user documentation
//preparation
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Leave
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/24/08   Time: 10:37a
//Created in $/Leap/Source/Templates/Leave
//Added functionality for Leave report print
?>