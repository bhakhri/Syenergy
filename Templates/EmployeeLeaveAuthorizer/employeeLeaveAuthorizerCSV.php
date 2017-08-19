<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
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

	   //to parse csv values    
    function parseCSVComments($comments) {
       $comments = str_replace('"', '""', $comments);
       $comments = str_ireplace('<br/>', "\n", $comments);
       if(eregi(",", $comments) or eregi("\n", $comments)) {
         return '"'.$comments.'"'; 
       } 
       else {
         return $comments.chr(160); 

       }
    }    
    
    $leaveAuthorizersId=$sessionHandler->getSessionVariable('LEAVE_AUTHORIZERS');   

    //// Search filter //  
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
    
    //$search  = $REQUEST_DATA['searchbox'];
	//$csvData = parseCSVComments($search);
	//$csvData .= "\n";
	$ss="";

    $csvData = '';
    $csvData .= "Search By,".parseCSVComments($REQUEST_DATA['searchbox']);   
    $csvData .= "\n";
   // $csvData .= "#,Employee Code,Employee Name,Leave Set \n";
	if($sessionHandler->getSessionVariable('LEAVE_AUTHORIZERS')==2) { 
	  $ss = "Second Authorizer,";
	}
	$csvData .= "#,Employee Name,Employee Code,First Authorizer,Second Authorizer,Leave Type\n";
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
        
       $csvData .= parseCSVComments(($i+1)).",". parseCSVComments($empLeaveAuthRecordArray[$i]['employeeName']);
       $csvData .= ",".parseCSVComments($empLeaveAuthRecordArray[$i]['employeeCode']); 
	   $csvData .= ",".parseCSVComments($empLeaveAuthRecordArray[$i]['firstApprovingEmployee']);
	   if($sessionHandler->getSessionVariable('LEAVE_AUTHORIZERS')==2) { 	
         $csvData .= ",".parseCSVComments($empLeaveAuthRecordArray[$i]['secondApprovingEmployee']); 
	   }
	   $csvData .= ",".parseCSVComments($empLeaveAuthRecordArray[$i]['leaveTypeName']);
       $csvData .= "\n";
    }

    if($cnt==0) {
      $csvData .= ",No Data Found";   
    }
  
	UtilityManager::makeCSV($csvData,'EmployeeLeaveAuthorizer.csv');
	die;
?>
