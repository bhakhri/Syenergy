 <?php 
//This file is used as CSV version for display cities.
// Author :Dipanjan Bhattacharjee
// Created on : 13-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");

    require_once(MODEL_PATH . "/LeaveReportsManager.inc.php");
    $leaveManager = LeaveReportsManager::getInstance();
	
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  
    
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();


    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeCode';
    
    $orderBy = " $sortField $sortOrderBy";
    
    global $sessionHandler;  
    $leaveAuthorizersId=$sessionHandler->getSessionVariable('LEAVE_AUTHORIZERS');    
    if($leaveAuthorizersId=='') {
       $leaveAuthorizersId=1;  
    }
    
    if(trim($REQUEST_DATA['leaveSessionId'])!="-1"){
        $filter =' AND l.leaveSessionId ='.trim($REQUEST_DATA['leaveSessionId']);
    }
    
    if(trim($REQUEST_DATA['employeeDD'])!="-1"){
        $filter .=' AND l.employeeId='.trim($REQUEST_DATA['employeeDD']);
    }
    
    if(trim($REQUEST_DATA['leaveStatus'])=="1"){
        $filter .=" AND l.leaveStatus=$leaveAuthorizersId";
    }
    
    if($leaveAuthorizersId==2) {
        if(trim($REQUEST_DATA['leaveStatus'])=="0"){
            if(trim($REQUEST_DATA['pendingStatus'])=="0"){
                $filter .=' AND l.leaveStatus=0';
            }
            else if(trim($REQUEST_DATA['pendingStatus'])=="1"){
                $filter .=' AND l.leaveStatus=1';
            }
            else{
                $filter .=' AND l.leaveStatus IN (0,1)';
            }
        }
    }
    else {
        if(trim($REQUEST_DATA['leaveStatus'])=="0"){
         $filter .=' AND l.leaveStatus=0';
       }
    }
    
    if(trim($REQUEST_DATA['fromDate'])!='' and trim($REQUEST_DATA['toDate'])!=''){
        $filter .=' AND ( ( l.leaveFromDate BETWEEN "'.trim($REQUEST_DATA['fromDate']).'" AND "'.trim($REQUEST_DATA['toDate']).'" ) OR ( l.leaveToDate BETWEEN "'.trim($REQUEST_DATA['fromDate']).'" AND "'.trim($REQUEST_DATA['toDate']).'" ) )';
        $dateSearch=' From : '.UtilityManager::formatDate(trim($REQUEST_DATA['fromDate'])).'  To : '.UtilityManager::formatDate(trim($REQUEST_DATA['toDate']));
    }
     
    $leaveRecordArray = $leaveManager->getLeavesTakenList($filter,' ',$orderBy);
    $cnt = count($leaveRecordArray);

    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $leaveRecordArray[$i]['leaveFromDate']=UtilityManager::formatDate($leaveRecordArray[$i]['leaveFromDate']); 
        $leaveRecordArray[$i]['leaveToDate']=UtilityManager::formatDate($leaveRecordArray[$i]['leaveToDate']);
        $leaveRecordArray[$i]['leaveStatus']=$leaveStatusArray[$leaveRecordArray[$i]['leaveStatus']];
        
        $valueArray[] = array_merge(array('srNo'=>($i+1)),$leaveRecordArray[$i]);
    }
    
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Employee Leaves Report');
    if(trim($REQUEST_DATA['leaveStatus'])==1){
        $leaveStatus='Approved';
    }
    else{
       if($leaveAuthorizersId==2) { 
          $leaveStatus='Pending ( '. trim($REQUEST_DATA['pendingStatusName']) .' )'; 
       }
       else {
          $leaveStatus='Pending';   
       }
    }
    
    $search="Leave Session : ".trim($REQUEST_DATA['yearName']);
    if(trim($REQUEST_DATA['employeeName'])!='All') {
      $search .=" &nbsp;Employee : ".trim($REQUEST_DATA['employeeName']);
    }
    $search .="<br>Leave Status : ".$leaveStatus."<br/>".$dateSearch;
    
	$reportManager->setReportInformation($search);
	

    $reportTableHead                   =    array();
    $reportTableHead['srNo']		   =    array('#','width="2%" align="left"', "align='left'");
    $reportTableHead['employeeCode']   =    array('Employee Code ',' width=10% align="left" ','align="left" ');
    $reportTableHead['employeeName']   =    array('Employee Name',' width="15%" align="left" ','align="left"');
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
