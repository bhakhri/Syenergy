<?php
//-------------------------------------------------------
// Purpose: To store the records of Periodicity in array from the database, pagination and search, delete 
// functionality
//
// Author : Arvind Singh Rawat
// Created on : (30.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','PeriodicityMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
//	print_r("HEllo");

    require_once(MODEL_PATH . "/PeriodicityManager.inc.php");
    $periodicityManager = PeriodicityManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = 'WHERE  (periodicityName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR periodicityCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR periodicityFrequency LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'periodicityName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $periodicityManager->getTotalPeriodicity($filter);
    $periodicityRecordArray = $periodicityManager->getPeriodicityList($filter,$limit,$orderBy);
    $cnt = count($periodicityRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add periodicityId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $periodicityRecordArray[$i]['periodicityId'] , 'srNo' => ($records+$i+1) ),$periodicityRecordArray[$i]);

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
