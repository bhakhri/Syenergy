<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
    
    $leaveAuthorizersId=$sessionHandler->getSessionVariable('LEAVE_AUTHORIZERS');   

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
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
    
    $totalArray = $empLeaveAuthManager->getTotalEmployeeLeaveAuthorizer($filter);
    $empLeaveAuthRecordArray = $empLeaveAuthManager->getEmployeeLeaveAuthorizerList($filter,$limit,$orderBy);
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
        
        $actionStr='<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" alt="Edit" onclick="editWindow('.$id.',\'EditEmployeeLeaveAuthorizer\',700,600);return false;" border="0">
        <a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" alt="Delete" onclick="deleteEmployeeLeaveAuthorizer('.$id.');return false;"></a>';
        
        $valueArray = array_merge(array('action1' =>$actionStr, 'srNo' => ($records+$i+1) ),$empLeaveAuthRecordArray[$i]);

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