<?php
//-------------------------------------------------------
// Purpose: To store the records of designation in array from the database, pagination and search, delete 
// functionality
//
// Author : Gurkeerat Sidhu
// Created on : (29.09.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','TemporaryDesignationMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/DesignationTempManager.inc.php");
    $designationTempManager = DesignationTempManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (designationName LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR designationCode LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'designationName';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $designationTempManager->getTotalDesignation($filter);
    $designationRecordArray = $designationTempManager->getDesignationList($filter,$limit,$orderBy);
    $cnt = count($designationRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add tempDesignationId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $designationRecordArray[$i]['tempDesignationId'] , 'srNo' => ($records+$i+1) ),$designationRecordArray[$i]);

        if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
        }
        else {
            $json_val .= ','.json_encode($valueArray);           
        }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    

  
?>