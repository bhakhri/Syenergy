<?php
//-------------------------------------------------------
// Purpose: To store the records of visitors in array from the database, pagination and search, delete 
// functionality
//
// Author : Gurkeerat Sidhu
// Created on : (20.04.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','HostelVisitor');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/HostelVisitorManager.inc.php");
    $hostelVisitorManager = HostelVisitorManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       if(strtolower(trim($REQUEST_DATA['searchbox']))=='father'){
           $rel=1;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='mother'){
           $rel=2;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='sister'){
           $rel=3;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='brother'){
           $rel=4;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='others'){
           $rel=5;
       }
       else{
           $rel=-1;
       } 
      $filter = ' WHERE (visitorName LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR toVisit LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR address LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%"
       OR purpose LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%"  OR contactNo LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR relation LIKE "'.$rel.'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'visitorName';
    
     $orderBy = "$sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $hostelVisitorManager->getTotalHostelVisitor($filter);
    $hostelVisitorRecordArray = $hostelVisitorManager->getHostelVisitorList($filter,$limit,$orderBy);
    $cnt = count($hostelVisitorRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add quotaId in actionId to populate edit/delete icons in User Interface 
        $hostelVisitorRecordArray[$i]['relation']=$hostelVisitorRelArr[$hostelVisitorRecordArray[$i]['relation']];
        $hostelVisitorRecordArray[$i]['dateOfVisit']=UtilityManager::formatDate($hostelVisitorRecordArray[$i]['dateOfVisit']);
        $hostelVisitorRecordArray[$i]['timeOfVisit']=substr($hostelVisitorRecordArray[$i]['timeOfVisit'],0,5); 
        if(strlen($hostelVisitorRecordArray[$i]['address'])>20){
          $hostelVisitorRecordArray[$i]['address']=substr($hostelVisitorRecordArray[$i]['address'],0,20).'...';
        }
        else if(strlen($hostelVisitorRecordArray[$i]['purpose'])>20){
          $hostelVisitorRecordArray[$i]['purpose']=substr($hostelVisitorRecordArray[$i]['purpose'],0,20).'...';
        }    
        $valueArray = array_merge(array('action' => $hostelVisitorRecordArray[$i]['visitorId'] , 'srNo' => ($records+$i+1) ),$hostelVisitorRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    

  
?>
