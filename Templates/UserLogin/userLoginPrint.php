<?php 
//This file is used as printing version for TestType.
//
// Author :Gurkeerat Sidhu
// Created on : 20-02-2010
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/DashBoardManager.inc.php");
    $dashboardManager = DashBoardManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	$conditionsArray = array();
	$qryString = "";
    


    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'roleUserName';
    
    $orderBy = " $sortField $sortOrderBy";

    ////////////

    $startDate = $REQUEST_DATA['startDate'];
    $toDate = $REQUEST_DATA['toDate'];

    $filter = "DATE_FORMAT(dateTimeIn,'%Y-%m-%d') BETWEEN '$startDate' AND '$toDate'";
    $reportFormat=1;  
    if(trim($REQUEST_DATA['listView'])==1){ 
    $userLoginRecordArray = $dashboardManager->getStudentNotLoggedinList($filter,' ',$orderBy);
    $cnt = count($userLoginRecordArray);
    $reportFormat=3;
    $report= "Student not loggedIn";
    }
    else{
    $userLoginRecordArray = $dashboardManager->getUserLoginList($filter,' ',$orderBy);
    $cnt = count($userLoginRecordArray);
     if(trim($REQUEST_DATA['reportFormat'])==1){
         $reportFormat=1;
         $report= "Consolidated";
    }
    else if(trim($REQUEST_DATA['reportFormat'])==0){
        $reportFormat=0;
        $report= "Detailed"; 
    }
    }
    for($i=0;$i<$cnt;$i++) {
            if($userLoginRecordArray[$i]['roleUserName']=='' AND trim($REQUEST_DATA['listView'])!=1){
            $userLoginRecordArray[$i]['roleUserName']="Administrator";
        }
        $valueArray[] = array_merge(array( 'srNo' => ($records+$i+1) ),$userLoginRecordArray[$i]); 
    }
    
   
	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading($reportTitle);
    $reportManager->setReportInformation("Report Type: ".$report.", Login Date Between: ".UtilityManager::formatDate($startDate)." And ".UtilityManager::formatDate($toDate) );
    
    if($reportFormat==3){
    $reportTableHead                  =    array();
    $reportTableHead['srNo']          =   array('#','width="5%" align="left"', "align='left' ");
    $reportTableHead['roleUserName']  =   array('Name','width=25% align="left"', 'align="left"');
    $reportTableHead['rollNo']      =   array('Roll No.','width=25% align="left"', 'align="left"');
    $reportTableHead['className']      =   array('Class','width=45% align="left"', 'align="left"');
    }
	else{          
	$reportTableHead				  =	array();
	$reportTableHead['srNo']		  =   array('#','width="3%" align="left"', "align='left' ");
    $reportTableHead['roleUserName']  =   array('Name','width=15% align="left"', 'align="left"');
    $reportTableHead['userName']      =   array('UserName','width=15% align="left"', 'align="left"');
    $reportTableHead['roleName']      =   array('Role','width=15% align="left"', 'align="left"');
    if($reportFormat==1){
      $reportTableHead['dateTimeIn']   =   array('Date&Time','width=28% align="center"', 'align="center"');
    }
    else{
        $reportTableHead['loggedInTime'] =   array('Date','width=15% align="center"', 'align="center"');
        $reportTableHead['timeIn']       =   array('Time','width=28% align="left"', 'align="left"');
    }
    $reportTableHead['userCount']      =   array('Count','width=15% align="right"', 'align="right"');
    }
	$reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: userLoginPrint.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/04/10    Time: 11:50
//Updated in $/LeapCC/Templates/UserLogin
//Done bug fixing.
//Fixed bugs---
//0003231,0003230,0003229,0003228,0003227,0003225,0003224,0003156
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 2/22/10    Time: 5:49p
//Created in $/LeapCC/Templates/UserLogin
//created file under user login report
//


?>