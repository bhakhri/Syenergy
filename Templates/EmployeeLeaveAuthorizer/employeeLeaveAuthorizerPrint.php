<?php
//-------------------------------------------------------
//  
// functionality


// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','EmployeeLeaveAuthorizer');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

	require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/EmployeeLeaveAuthorizerManager.inc.php");
    $empLeaveAuthManager = EmployeeLeaveAuthorizerManager::getInstance();

    
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();
    
    // get Active session
    $leaveSessionArray = $commonQueryManager->getLeaveSessionList(' WHERE active=1 ');   
    $leaveSessionId='';
    if($leaveSessionArray[0]['leaveSessionId']!='') {
      $leaveSessionId=$leaveSessionArray[0]['leaveSessionId']; 
    }                                                                                                         

    if($leaveSessionId=='') {
      $leaveSessionId=0;  
    }
    
    $leaveAuthorizersId=$sessionHandler->getSessionVariable('LEAVE_AUTHORIZERS');   

    /// /Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (e1.employeeName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                        e1.employeeCode LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%"  OR 
                        e2.employeeName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                        lt.leaveTypeName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%"';
       if($leaveAuthorizersId==2) {
         $filter .= ' OR e3.employeeName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%"';
       }
       $filter .= ')';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeCode';
    
    $orderBy = " $sortField $sortOrderBy";         

    $filter .= " AND l.leaveSessionId = $leaveSessionId";
    
    $empLeaveAuthRecordArray = $empLeaveAuthManager->getEmployeeLeaveAuthorizerList($filter,'',$orderBy);
    $cnt = count($empLeaveAuthRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        if(trim($empLeaveAuthRecordArray[$i]['employeeCode'])==''){
            $empLeaveAuthRecordArray[$i]['employeeCode']=NOT_APPLICABLE_STRING;
        }
        if(trim($empLeaveAuthRecordArray[$i]['employeeName'])==''){
            $empLeaveAuthRecordArray[$i]['employeeName']=NOT_APPLICABLE_STRING;
        }
        if(trim($empLeaveAuthRecordArray[$i]['firstApprovingEmployee'])==''){
            $empLeaveAuthRecordArray[$i]['firstApprovingEmployee']=NOT_APPLICABLE_STRING;
        }
        if(trim($empLeaveAuthRecordArray[$i]['secondApprovingEmployee'])==''){
            $empLeaveAuthRecordArray[$i]['secondApprovingEmployee']=NOT_APPLICABLE_STRING;
        }
        
        $id = $empLeaveAuthRecordArray[$i]['approvingId'];
        
        
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$empLeaveAuthRecordArray[$i]);
    }
  

    $search = $REQUEST_DATA['searchbox'];
    $reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Employee Leave Authorizer Report ');
	$reportManager->setReportInformation("Search By : $search");

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#','width="4%" align="left"', "align='left'");
    $reportTableHead['employeeName']			=    array('Employee Name ',' width=15% align="left" ','align="left" ');
    $reportTableHead['employeeCode']			=    array('Employee Code',' width="15%" align="left" ','align="left"');
	$reportTableHead['firstApprovingEmployee']			=    array('First Authorizer',' width="15%" align="left" ','align="left"');
	if($sessionHandler->getSessionVariable('LEAVE_AUTHORIZERS')==2) { 
   	  $reportTableHead['secondApprovingEmployee']			=    array('Second Authorizer',' width="15%" align="left" ','align="left"');
	}
	$reportTableHead['leaveTypeName']			=    array('Leave Type',' width="15%" align="left" ','align="left"');


	$reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

    
// for VSS
// $History: ajaxInitList.php $
?>