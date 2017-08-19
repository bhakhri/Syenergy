<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
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
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeCode';
    $orderBy = " $sortField $sortOrderBy";
    
    $criteria=trim($REQUEST_DATA['criteriaType']);
    $criteriaValue=trim(add_slashes($REQUEST_DATA['criteriaText']));
    
    if(!is_numeric($criteriaValue)){
        echo ENTER_CRITERIA_VALUE_IN_INTERGER;
        die;
    }
    
    if($criteriaValue<0){
        echo ENTER_CRITERIA_VALUE_POSITIVE;
        die;
    }

    if(trim($REQUEST_DATA['leaveSessionId'])!="-1"){
        $filter1 .=' AND l.leaveSessionId='.trim($REQUEST_DATA['leaveSessionId']);
    }
    
    if($criteria==1){
        $filter2 =' HAVING noOfDays > '.$criteriaValue;
    }
    else if($criteria==2){
        $filter2 =' HAVING noOfDays < '.$criteriaValue;
    }
    else{
        $filter2 =' HAVING noOfDays = '.$criteriaValue; 
    }
    
    if(trim($REQUEST_DATA['leaveType'])!="-1"){
      $filter1 .=' AND l.leaveTypeId='.trim($REQUEST_DATA['leaveType']);  
    }
     
        
        
        
    $totalArray = $leaveManager->getLeavesAnalysisList($filter1,$filter2,' ' ,' e.employeeCode');
    $leaveRecordArray = $leaveManager->getLeavesAnalysisList($filter1,$filter2,$limit,$orderBy);
    $cnt = count($leaveRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$leaveRecordArray[$i]);
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