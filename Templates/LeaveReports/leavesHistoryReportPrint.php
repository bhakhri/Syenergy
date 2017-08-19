 <?php 
//This file is used as CSV version for display cities.
// Author :Dipanjan Bhattacharjee
// Created on : 13-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");

    require_once(MODEL_PATH . "/LeaveReportsManager.inc.php");
    $leaveManager = LeaveReportsManager::getInstance();
	
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'leaveTypeName';
    $orderBy = " $sortField $sortOrderBy";         

    if(trim($REQUEST_DATA['leaveSessionId'])!="-1"){
        $filter =' AND l.leaveSessionId='.trim($REQUEST_DATA['leaveSessionId']);
    }
    
    if(trim($REQUEST_DATA['employeeDD'])==""){
      echo 'Employee Information Missing';
      die;  
    }
    
    $filter .=' AND l.employeeId='.trim($REQUEST_DATA['employeeDD']);
     
    $leaveRecordArray = $leaveManager->getLeavesHistoryList($filter,' ',$orderBy);
    $cnt = count($leaveRecordArray);

    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $leaveRecordArray[$i]['leaveFromDate']=UtilityManager::formatDate($leaveRecordArray[$i]['leaveFromDate']); 
        $leaveRecordArray[$i]['leaveToDate']=UtilityManager::formatDate($leaveRecordArray[$i]['leaveToDate']);
        $leaveRecordArray[$i]['leaveStatus']=$leaveStatusArray[$leaveRecordArray[$i]['leaveStatus']];
        
        $valueArray[] = array_merge(array('srNo'=>($i+1)),$leaveRecordArray[$i]);
    }
    
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Leaves History Report');
    
	$reportManager->setReportInformation("Leave Session : ".trim($REQUEST_DATA['yearName'])."  &nbsp;Employee : ".trim($REQUEST_DATA['employeeName']));
	

    $reportTableHead                   =    array();
    $reportTableHead['srNo']		   =    array('#','width="2%" align="left"', "align='left'");
	$reportTableHead['leaveTypeName']  =    array('Leave Type',' width="10%" align="left" ','align="left"');
    $reportTableHead['leaveFromDate']  =    array('From',' width="10%" align="center" ','align="center"');
    $reportTableHead['leaveToDate']    =    array('To',' width="10%" align="center" ','align="center"');
    $reportTableHead['noOfDays']       =    array('Days',' width="5%" align="right" ','align="right"');
    $reportTableHead['leaveStatus']    =    array('Status',' width="10%" align="left" ','align="left"');

    $reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
