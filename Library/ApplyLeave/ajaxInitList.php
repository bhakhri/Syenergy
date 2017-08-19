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
    
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();


    define('MODULE','ApplyEmployeeLeave');
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

    require_once(MODEL_PATH . "/ApplyLeaveManager.inc.php");
    $applyLeaveManager = ApplyLeaveManager::getInstance();

    /////////////////////////
    
     // Set session
    $leaveSessionArray = $commonQueryManager->getLeaveSessionList(' WHERE active=1 ');   
    $leaveSessionId='';
    if($leaveSessionArray[0]['leaveSessionId']!='') {
      $leaveSessionId=$leaveSessionArray[0]['leaveSessionId']; 
    }                                                                                                         
    
    if($leaveSessionId=='') {
      $leaveSessionId=-1;
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
       
       $filter  = ' AND (lt.leaveTypeName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR emp.employeeName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR emp.employeeCode LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR l.leaveStatus LIKE "%'.$leaveStatus.'%" ';
       $filter .= ' OR (SELECT DISTINCT employeeName FROM employee e WHERE e.employeeId = l.firstApprovingEmployeeId) LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%"'; 
       $filter .= ' OR (SELECT DISTINCT employeeName FROM employee e WHERE e.employeeId = l.secondApprovingEmployeeId) LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")'; 
    }
    
    
    $filter .= " AND l.leaveSessionId = $leaveSessionId ";
    
    if($sessionHandler->getSessionVariable('RoleId')!=1){
        $employeeId=$sessionHandler->getSessionVariable('EmployeeId');
        if($employeeId==''){
           echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
           die; 
        }
        $filter .=' AND emp.employeeId='.$employeeId;
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeCode';
    
    $sortField1= $sortField;
    if($sortField1 == 'attachment') {
      $sortField1 = "documentAttachment";  
    }
    if($sortField1 == 'substitute') {
      $sortField1 = "substituteEmployee";  
    }
    $orderBy = " $sortField1 $sortOrderBy";         

    
    $leaveAuthorizersId=$sessionHandler->getSessionVariable('LEAVE_AUTHORIZERS');   
    
    ////////////
    $totalArray = $applyLeaveManager->getTotalEmployeeLeaves($filter);
    $leaveRecordArray = $applyLeaveManager->getEmployeeLeavesList($filter,$limit,$orderBy);
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
        if($leaveStatus==0){
            $actionString='<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" border="0" alt="Edit" onclick="editWindow('.$leaveRecordArray[$i]['leaveId'].');return false;"></a>
                          <a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="cancelAppliedLeave('.$leaveRecordArray[$i]['leaveId'].');"/></a>';
        }
        else{
            $actionString='<a href="#" title="View"><img src="'.IMG_HTTP_PATH.'/zoom.gif" border="0" alt="View" onclick="viewWindow('.$leaveRecordArray[$i]['leaveId'].');return false;"></a>';
        }
        
        $leaveRecordArray[$i]['leaveFromDate']=UtilityManager::formatDate($leaveRecordArray[$i]['leaveFromDate']);
        $leaveRecordArray[$i]['leaveToDate']=UtilityManager::formatDate($leaveRecordArray[$i]['leaveToDate']);

        if($leaveRecordArray[$i]['leaveFormat']=='2') {
           $leaveRecordArray[$i]['leaveToDate']=NOT_APPLICABLE_STRING;  
        }
        
        
              
        if($leaveStatus==1) {
           if($leaveAuthorizersId==2) {
             $empStatus= $leaveStatusArray[$leaveStatus]." By ".$leaveRecordArray[$i]['firstEmployee'];
		   }
           else {
             $empStatus= "Approved By ".$leaveRecordArray[$i]['firstEmployee']; 
           }
           $leaveRecordArray[$i]['leaveStatus']=$empStatus;
        }
        else if($leaveStatus==2) {  
           if($leaveAuthorizersId==2) {
		   
              $empStatus= $leaveStatusArray[$leaveStatus]." By ".$leaveRecordArray[$i]['secondEmployee'];   
              $leaveRecordArray[$i]['leaveStatus']=$empStatus;   
           }
        }    
        else if($leaveStatus==3) { 
         if( $leaveRecordArray[$i]['secondApprovingEmployeeId']==""){
		  
		     $empStatus= $leaveStatusArray[$leaveStatus]." By ".$leaveRecordArray[$i]['firstEmployee'];
			  $leaveRecordArray[$i]['leaveStatus']=$empStatus;
			} 
		else{
		
             $empStatus= $leaveStatusArray[$leaveStatus]." By ".$leaveRecordArray[$i]['secondEmployee'];
			  $leaveRecordArray[$i]['leaveStatus']=$empStatus;  
           }
          
		}   //$leaveRecordArray[$i]['leaveStatus']=$empStatus;
        else {
           $leaveRecordArray[$i]['leaveStatus']=$leaveStatusArray[$leaveStatus];
        }
        
        if(trim($leaveRecordArray[$i]['documentAttachment'])=='') {
          $documentAttachment = NOT_APPLICABLE_STRING;  
        }
        else {
		  $documentAttachment = '<img src="'.IMG_HTTP_PATH.'/download.gif" name="'.strip_slashes($leaveRecordArray[$i]['documentAttachment']).'" onClick="download(this.name);" title="Download File" />';
        }

   
        $leaveRecordArray[$i]['taken']=number_format($leaveRecordArray[$i]['taken'],2);
        $leaveRecordArray[$i]['balance']=number_format($leaveRecordArray[$i]['allowed']-$leaveRecordArray[$i]['taken'],2);
        
        $valueArray = array_merge(array('actionString' =>$actionString , 
                                        'substitute' =>$substitute,
                                        'attachment' =>$documentAttachment,
                                        'srNo' => ($records+$i+1) ),
                                        $leaveRecordArray[$i]);

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
