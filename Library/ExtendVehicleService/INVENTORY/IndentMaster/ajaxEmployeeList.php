<?php
//-------------------------------------------------------
// Purpose: To store the records of cities in array from the database, pagination and search, delete 
// functionality
// Author : Dipanjan Bbhattacharjee
// Created on : (27.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','IndentMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(INVENTORY_MODEL_PATH . "/IndentManager.inc.php");
    $indentManager = IndentManager::getInstance();
    
    $userId=$sessionHandler->getSessionVariable('UserId');
    /////////////////////////
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      $filter = ' AND ( emp.employeeCode LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" )';
    }
    //list of employees should not see himself/herself in the list
    //of suggested emplyees
    $filter .=' AND emp.userId!='.$userId; 
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray          = $indentManager->getTotalEmployees($filter);
    $employeeRecordArray = $indentManager->getEmployeeList($filter,$limit,$orderBy);
    $cnt = count($employeeRecordArray);
    
    $mode=$REQUEST_DATA['mode'];
    $title='title="Select an emplyee"';
    $style='style="cursor:pointer;"';
    for($i=0;$i<$cnt;$i++) {
        $empCode=$employeeRecordArray[$i]['employeeCode'];
        
        $srNo='<a '.$title.' '.$style.' onclick="selectEmployee(this.id,'.$mode.');return false;" id="'.$empCode.'" >'.($records+$i+1).'</a>';
        $employeeRecordArray[$i]['employeeName']='<a '.$title.' '.$style.' onclick="selectEmployee(this.id,'.$mode.');return false;" id="'.$empCode.'" >'.$employeeRecordArray[$i]['employeeName'].'</a>';
        $employeeRecordArray[$i]['employeeCode']='<a '.$title.' '.$style.' onclick="selectEmployee(this.id,'.$mode.');return false;" id="'.$empCode.'" >'.$employeeRecordArray[$i]['employeeCode'].'</a>';
        $employeeRecordArray[$i]['designationCode']='<a '.$title.' '.$style.' onclick="selectEmployee(this.id,'.$mode.');return false;" id="'.$empCode.'" >'.$employeeRecordArray[$i]['designationCode'].'</a>';
        
        $valueArray = array_merge(array('srNo' => ($srNo)),$employeeRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
  echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}';
    
// for VSS
// $History: ajaxEmployeeList.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/09/09   Time: 18:22
//Created in $/Leap/Source/Library/INVENTORY/IndentMaster
//Created  "Indent Master" module under "Inventory Management"
?>