<?php
//-------------------------------------------------------
// Purpose: To store the records of employees in array from the database, pagination and search, delete 
// functionality
//
// Author : Gurkeerat Sidhu
// Created on : (29.04.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','TemporaryEmployee');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/EmployeeTempManager.inc.php");
    $tempEmployeeManager = TempEmployeeManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       if(strtolower(trim($REQUEST_DATA['searchbox']))=='on job'){
           $sat=1;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='left job'){
           $sat=2;
       }
       else{
           $sat=-1;
       } 
      $filter = ' AND (et.tempEmployeeName LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%"  OR et.address LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%"
        OR et.contactNo LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR et.status LIKE "%'.$sat.'%" OR dt.designationName LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'tempEmployeeName';
    
     $orderBy = "$sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $tempEmployeeManager->getTotalTempEmployee($filter);
    $tempEmployeeRecordArray = $tempEmployeeManager->getTempEmployeeList($filter,$limit,$orderBy);
    $cnt = count($tempEmployeeRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add quotaId in actionId to populate edit/delete icons in User Interface 
        $tempEmployeeRecordArray[$i]['status']=$statusArr[$tempEmployeeRecordArray[$i]['status']];
        $tempEmployeeRecordArray[$i]['dateOfJoining']=UtilityManager::formatDate($tempEmployeeRecordArray[$i]['dateOfJoining']);
         if(strlen($tempEmployeeRecordArray[$i]['address'])>20){
          $tempEmployeeRecordArray[$i]['address']=substr($tempEmployeeRecordArray[$i]['address'],0,20).'...';
        }
        $valueArray = array_merge(array('action' => $tempEmployeeRecordArray[$i]['tempEmployeeId'] , 'srNo' => ($records+$i+1) ),$tempEmployeeRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    

  
?>
