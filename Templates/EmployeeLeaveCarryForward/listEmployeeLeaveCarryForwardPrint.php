<?php
//This file is used as printing version for SMS
//
// Author :Parveen Sharma
// Created on : 26-11-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php                
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
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);      
    $search4 .= "As On $formattedDate ";
   
   
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
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
       $id = $employeeRecordArray[$i]['employeeId'];
       
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
              $checkall ='N';
              
              $leaveTypeId = $carryForwardArray[$j]['leaveTypeId'];
              
              if($find=='') {
                 $valueArray[$k]['srNo'] = $i+1;
                 $valueArray[$k]['employeeName'] = $employeeRecordArray[$i]['employeeName'];
                 $valueArray[$k]['employeeCode'] = $employeeRecordArray[$i]['employeeCode'];
                 $valueArray[$k]['designationName'] = $employeeRecordArray[$i]['designationName'];
                 $valueArray[$k]['dateOfJoining'] = $employeeRecordArray[$i]['dateOfJoining'];
                 $find=1;               
              }
              else {
                 $valueArray[$k]['srNo'] = "";
                 $valueArray[$k]['employeeName'] = "";
                 $valueArray[$k]['employeeCode'] = "";
                 $valueArray[$k]['designationName'] = "";
                 $valueArray[$k]['dateOfJoining'] = "";
              }
              $balance = $carryForwardArray[$j]['leaveValue']-$carryForwardArray[$j]['taken'];
              if($balance <0) {
                $balance = 0;
              }
              $allowed = $carryForwardArray[$j]['leaveValue']+$carryForwardArray[$j]['carryForward'];
              
              $checkStatus ="N";
              if($carryForwardArray[$j]['carryForwardStatus']!=-1) {
                $checkStatus = 'Y';   
              }              

              $valueArray[$k]['leaveTypeName'] = $carryForwardArray[$j]['leaveTypeName'];
              $valueArray[$k]['allowed'] = $allowed;
              $valueArray[$k]['taken'] = $carryForwardArray[$j]['taken'];
              $valueArray[$k]['balance'] = $balance;
              $valueArray[$k]['status'] = $checkStatus;
            
              $k=$k+1;               
          } 
          else 
          if($carryForwardArray[$j]['employeeId'] != $id && $recordFind=='1') {
            break;
          }    
       }
    }
    
  
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);   
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Employee Leave Carry Forward Report');
    $reportManager->setReportInformation("AS On ".$formattedDate);
    $reportTableHead                            =    array();
    
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']              =    array('#',               'width="3%" align=left', "align='left' ");
    $reportTableHead['employeeName']      =    array('emp. Name',       'width="15%" align="left"', 'align="left"');
    $reportTableHead['employeeCode']      =    array("Emp. Code ",      'width="12%" align="left"', 'align="left"');
    $reportTableHead['designationName']   =    array('Designation',     'width="14%" align="left"', 'align="left"');
    $reportTableHead['dateOfJoining']     =    array('Dt. of Joining',  'width="12%" align="center"', 'align="center"');
    $reportTableHead['leaveTypeName']     =    array('Leave Type',      'width="14%" align="left"', 'align="left"');
    $reportTableHead['allowed']           =    array('Allowed',         'width="8%" align="right"', 'align="right"');
    $reportTableHead['taken']             =    array('Taken',           'width="8%" align="right"', 'align="right"');
    $reportTableHead['balance']           =    array('Balance',         'width="8%" align="right"', 'align="right"');
    $reportTableHead['status']            =    array('Carry Forwd.',     'width="8%" align="center"', 'align="center"');
  

    
    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();
