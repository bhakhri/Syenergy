<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','EmployeeLeavesHistoryReport');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/LeaveReportsManager.inc.php");
    $leaveManager = LeaveReportsManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'leaveTypeName';
    $orderBy = " $sortField $sortOrderBy";         

    if(trim($REQUEST_DATA['leaveSessionId'])!="-1"){
        $filter =' AND l.leaveSessionId='.trim($REQUEST_DATA['leaveSessionId']);
    }
    
    if(trim($REQUEST_DATA['employeeDD'])==""){
      echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
      die;  
    }
    
    $filter .=' AND l.employeeId='.trim($REQUEST_DATA['employeeDD']);
    
    
            
    $totalArray = $leaveManager->getTotalLeavesHistory($filter);
    $leaveRecordArray = $leaveManager->getLeavesHistoryList($filter,$limit,$orderBy);
    $cnt = count($leaveRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       $actionStr='<a href="#" title="View Details"><img src="'.IMG_HTTP_PATH.'/zoom.gif" border="0" alt="View Details" onclick="viewWindow('.$leaveRecordArray[$i]['leaveId'].');return false;"></a>';
        
       $leaveRecordArray[$i]['leaveFromDate']=UtilityManager::formatDate($leaveRecordArray[$i]['leaveFromDate']); 
       $leaveRecordArray[$i]['leaveToDate']=UtilityManager::formatDate($leaveRecordArray[$i]['leaveToDate']);
       $leaveRecordArray[$i]['leaveStatus']=$leaveStatusArray[$leaveRecordArray[$i]['leaveStatus']];
       
       $valueArray = array_merge(array('leaveDetails' => $actionStr,'srNo' => ($records+$i+1) ),$leaveRecordArray[$i]);
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