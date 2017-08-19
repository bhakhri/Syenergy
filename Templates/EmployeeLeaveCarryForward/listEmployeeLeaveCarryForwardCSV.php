<?php 
//This file is used as csv output of SMS Detail Report.
//
// Author :Parveen Sharma
// Created on : 27-11-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MANAGEMENT_ACCESS',1);  
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/EmployeeManager.inc.php");
    $employeeManager = EmployeeManager::getInstance();
    
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();
    
    require_once(MODEL_PATH . "/EmployeeLeaveCarryForwardManager.inc.php");
    $carryForwardManager = EmployeeLeaveCarryForwardManager::getInstance();
    
    
      // CSV data field Comments added 
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
    
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);      
    
    
    
    $csvData = "AS On, ".parseCSVComments($formattedDate);
    $csvData .= "\n";
    $csvData .= "Sr. No.,Emp. Name,Emp. Code,Designation,Dt. of Joining,Leave Type,Allowed,Taken,Balance,Carry Forwd."; 
    $csvData .= "\n"; 
    
    
      /// Search filter /////  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';
    
    $orderBy = "$sortField $sortOrderBy";       
    
    
     // Set leave sets
    $leaveSetArray = $commonQueryManager->getLeaveSessionSetAdvData(' AND s.active=1 AND ls.isActive=1');
    
    // Set session
    $leaveSessionArray = $commonQueryManager->getLeaveSessionList(' WHERE active=1 ');   
    $leaveSessionId='';
    if($leaveSessionArray[0]['leaveSessionId']!='') {
      $leaveSessionId=$leaveSessionArray[0]['leaveSessionId']; 
    }                                                                                                         
    
    if($leaveSessionId=='') {
      echo "Please select atleast one active session";    
      die;  
    }
    
    
    $employeeId = $sessionHandler->getSessionVariable('carryForwardEmployeeId');      
    
    $nextLeaveSessionId = $sessionHandler->getSessionVariable('nextLeaveSessionId');      
    
    
    if($employeeId=='') {
      $employeeId=0;  
    }
    
    if($nextLeaveSessionId=='') {
       $nextLeaveSessionId=0; 
    }
    
    
    
    
    
    $conditionCarryForward = " AND lse.leaveSessionId = $leaveSessionId AND lse.employeeId IN ($employeeId) ";
    $carryForwardArray = $carryForwardManager->getEmployeeCarryForwardList($conditionCarryForward,' lse.employeeId, ls.leaveSetId ','',$nextLeaveSessionId);
    $cnt = count($carryForwardArray);        
    
    $conditions = " WHERE emp.employeeId IN ($employeeId) ";
    
    $employeeRecordArray = $employeeManager->getIcardEmployeeList($conditions,'',$orderBy);  
    

    $k=0;
    for($i=0;$i<$cnt;$i++) {
       $id = $employeeRecordArray[$i]['employeeId'];
       $k=1; 
       if($employeeRecordArray[$i]['dateOfJoining']=='0000-00-00') {
          $employeeRecordArray[$i]['dateOfJoining'] = NOT_APPLICABLE_STRING;
       }
       else {
          $employeeRecordArray[$i]['dateOfJoining'] = UtilityManager::formatDate($employeeRecordArray[$i]['dateOfJoining']); 
       }
       
       if(strip_slashes($employeeRecordArray[$i]['employeeName']) == '') {
         $employeeRecordArray[$i]['employeeName']  = NOT_APPLICABLE_STRING;
       }
       
       if(strip_slashes($employeeRecordArray[$i]['employeeCode']) == '') {
         $employeeRecordArray[$i]['employeeCode']  = NOT_APPLICABLE_STRING;
       }
       
       if(strip_slashes($employeeRecordArray[$i]['departmentAbbr']) == '') {
         $employeeRecordArray[$i]['departmentAbbr']  = NOT_APPLICABLE_STRING;
       }
       
       if(strip_slashes($employeeRecordArray[$i]['designationName']) == '') {
         $employeeRecordArray[$i]['designationName']  = NOT_APPLICABLE_STRING;
       }
       
       $employeeRecordArray[$i]['leaveTypeName']  = NOT_APPLICABLE_STRING;
       $employeeRecordArray[$i]['leaveValue']  = NOT_APPLICABLE_STRING;
       $employeeRecordArray[$i]['taken']  = NOT_APPLICABLE_STRING;
       $employeeRecordArray[$i]['carryForward']  = NOT_APPLICABLE_STRING;
       
       
       $find='';
       $recordFind='';
     
       for($j=0;$j<count($carryForwardArray);$j++) {
          if($carryForwardArray[$j]['employeeId'] == $id) {
              $recordFind='1';
              
              $leaveTypeId = $carryForwardArray[$j]['leaveTypeId'];
              
              if($find=='') {
                 $csvData .= ($i+1).",".parseCSVComments($employeeRecordArray[$i]['employeeName']);
                 $csvData .= ",".parseCSVComments($employeeRecordArray[$i]['employeeCode']).",".parseCSVComments($employeeRecordArray[$i]['designationName']);
                 $csvData .= ",".parseCSVComments($employeeRecordArray[$i]['dateOfJoining']);
                 $find=1;               
              }
              else {
                 $csvData .= ",,,,";
              }
              $balance = $carryForwardArray[$j]['leaveValue']-$carryForwardArray[$j]['taken'];
              if($balance <0) {
                $balance = 0;
              }
              $checkStatus ='N';
              if($carryForwardArray[$j]['carryForwardStatus']!=-1) {
                $checkStatus = 'Y';   
              }
              
              $allowed = $carryForwardArray[$j]['leaveValue']+$carryForwardArray[$j]['carryForward'];
              $csvData .= ",".parseCSVComments($carryForwardArray[$j]['leaveTypeName']).",".parseCSVComments($allowed);
              $csvData .= ",".parseCSVComments($carryForwardArray[$j]['taken']).",".parseCSVComments($balance).",".parseCSVComments($checkStatus);
              $csvData .= "\n";              
          } 
          else 
          if($carryForwardArray[$j]['employeeId'] != $id && $recordFind=='1') {
            break;
          }    
       }
    }
    
    
    if($k=='0') {
       $csvData .= ",,,,No Data Found";  
    }
    
    UtilityManager::makeCSV($csvData,'EmployeeLeaveCarryForward.csv');
    die;   