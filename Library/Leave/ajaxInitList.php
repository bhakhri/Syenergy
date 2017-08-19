<?php
//-------------------------------------------------------
// Purpose: To store the records of states in array from the database, pagination and search, delete 
// functionality
//
// Author : Arvind Singh Rawat
// Created on : (28.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','LeaveMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/LeaveManager.inc.php");
    $leaveManager =leaveManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter ///// 

	if(UtilityManager::notEmpty(trim($REQUEST_DATA['searchbox']))) {

		if(strtolower(trim($REQUEST_DATA['searchbox']))=='yes') {
           $type=1;
		}
		elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='no') {
           $type=0;
		}
	   else {
		   $type=-1;                                    
	   }
       
       $filter = ' AND (TRIM(leaveTypeName) LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" 
                        OR isActive LIKE "'.$type.'" OR carryForward LIKE "'.$type.'" OR reimbursed LIKE "'.$type.'" )';
    }
	
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'leaveTypeName';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $leaveManager->getTotalLeave($filter);
    $leaveRecordArray = $leaveManager->getLeaveList($filter,$limit,$orderBy);
    
    $cnt = count($leaveRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
	
        if($leaveRecordArray[$i]['isActive']==1){
            $leaveRecordArray[$i]['isActive']='Yes';
        }
        else if($leaveRecordArray[$i]['isActive']==0){
            $leaveRecordArray[$i]['isActive']='No';
        }
        
        if($leaveRecordArray[$i]['carryForward']==1){
            $leaveRecordArray[$i]['carryForward']='Yes';
        }
        else if($leaveRecordArray[$i]['carryForward']==0){
            $leaveRecordArray[$i]['carryForward']='No';
        }
        
        if($leaveRecordArray[$i]['reimbursed']==1){
            $leaveRecordArray[$i]['reimbursed']='Yes';
        }
        else if($leaveRecordArray[$i]['reimbursed']==0){
            $leaveRecordArray[$i]['reimbursed']='No';
        }
        // add leaveId in actionId to populate edit/delete icons in User Interface   
		
        $valueArray = array_merge(array('action' => $leaveRecordArray[$i]['leaveTypeId'] , 'srNo' => ($records+$i+1) ),$leaveRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
  
  //$History : $  
?>