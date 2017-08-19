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
    define('MODULE','AuthorizeEmployeeLeave');
    define('ACCESS','view');
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==1){
     UtilityManager::ifNotLoggedIn(true);
    }
    else if($roleId==2){
     UtilityManager::ifTeacherNotLoggedIn(true);
    }
    else if($roleId==5){
      UtilityManager::ifManagementNotLoggedIn(true);
    }
    else{
      UtilityManager::ifNotLoggedIn(true);  
    }
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/AuthorizeLeaveManager.inc.php");
    $authorizeLeaveManager = AuthorizeLeaveManager::getInstance();

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
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $search=strtoupper(trim($REQUEST_DATA['searchbox']));
       $leaveStatus=-1;
       if($search=='APPLIED'){
           $leaveStatus=0;
       }
       else if($search=='FIRST APPROVAL'){
           $leaveStatus=1;
       }
       else if($search=='SECOND APPROVAL'){
           $leaveStatus=2;
       }
       else if($search=='REJECTED'){
           $leaveStatus=3;
       }
       else if($search=='CANCELLED'){
           $leaveStatus=4;
       }
       else{
         $leaveStatus=-1;   
       }
       $filter = ' AND (lt.leaveTypeName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR e.employeeName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR e.employeeCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR l.leaveStatus LIKE "'.$leaveStatus.'%")';
    }
    
   $filter .= " AND l.leaveSessionId = $leaveSessionId";      
    
   //fetch employeeId of logged in user
   $empArray=$authorizeLeaveManager->getEmployeeInformation($sessionHandler->getSessionVariable('UserId'));
   $employeeId=$empArray[0]['employeeId'];
   
   if($employeeId==''){
      echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
      die; 
   }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeCode';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $authorizeLeaveManager->getLeavesForAuthorizeList($employeeId,$filter);
$leaveRecordArray = $authorizeLeaveManager->getLeavesForAuthorizeList($employeeId,$filter,$limit,$orderBy);
    $cnt = count($leaveRecordArray);
	
    for($i=0;$i<$cnt;$i++) {
        $leaveType = $leaveRecordArray[$i]['leaveTypeName'];
        $leaveFormat = $leaveRecordArray[$i]['leaveDay']; 
	$leaveRecordArray[$i]['leaveTypeName'] = $leaveType." (".$leaveFormat.")";
        if(trim($leaveRecordArray[$i]['employeeCode'])==''){
            $leaveRecordArray[$i]['employeeCode']=NOT_APPLICABLE_STRING;
        }
        if(trim($leaveRecordArray[$i]['employeeName'])==''){
            $leaveRecordArray[$i]['employeeName']=NOT_APPLICABLE_STRING;
        }
        $substitute = $leaveRecordArray[$i]['substituteEmployee'];
        if(trim($substitute)==''){
          $substitute=NOT_APPLICABLE_STRING;
        }
        
        $leaveStatus=$leaveRecordArray[$i]['leaveStatus'];
        $actionString='<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" border="0" alt="Edit" onclick="editWindow('.$leaveRecordArray[$i]['leaveId'].');return false;"></a>';

        $leaveRecordArray[$i]['leaveFromDate']=UtilityManager::formatDate($leaveRecordArray[$i]['leaveFromDate']);
        $leaveRecordArray[$i]['leaveToDate']=UtilityManager::formatDate($leaveRecordArray[$i]['leaveToDate']);
         if($leaveFormat=="Half Day") {
           $leaveRecordArray[$i]['leaveToDate']=NOT_APPLICABLE_STRING;  
        }
        $leaveRecordArray[$i]['leaveStatus']=$leaveStatusArray[$leaveStatus];
	
	
        if(trim($leaveRecordArray[$i]['documentAttachment'])=='') {
          $documentAttachment = NOT_APPLICABLE_STRING;  
        }
        else {
		  $documentAttachment = '<img src="'.IMG_HTTP_PATH.'/download.gif" name="'.strip_slashes($leaveRecordArray[$i]['documentAttachment']).'" onClick="download(this.name);" title="Download File" />';
        }
        $valueArray = array_merge(array('actionString' =>$actionString , 'substitute' =>$substitute,
                                        'attachment' =>$documentAttachment,'srNo' => ($records+$i+1) ),$leaveRecordArray[$i]);
 	
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitList.php $
?>
