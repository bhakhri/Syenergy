<?php
//-------------------------------------------------------
// Purpose: To store the records of states in array from the database, pagination and search, delete 
// functionality
//
// Author : Nishu Bindal	
// Created on : (4.feb.2012 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','FeeConcessionMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Fee/FeeConcessionManager.inc.php");
    $feeConcessionManager = FeeConcessionManager::getInstance();
       
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    /// Search filter /////  
	

    if(UtilityManager::notEmpty(strtoupper($REQUEST_DATA['searchbox']))) {
       $filter = ' AND (categoryName LIKE "%'.add_slashes(strtoupper(trim($REQUEST_DATA['searchbox']))).'%" OR categoryOrder LIKE "%'.add_slashes(strtoupper(trim($REQUEST_DATA['searchbox']))).'%" OR categoryDescription LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';         
    }
    
   
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'categoryName';
    
     $orderBy = " $sortField $sortOrderBy";
    
    $totalArray = $feeConcessionManager->getTotalFeeConcession($filter);
    $feeConcessionRecordArray = $feeConcessionManager->getFeeConcessionList($filter,$limit,$orderBy);
    
    $cnt = count($feeConcessionRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add countryId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $feeConcessionRecordArray[$i]['categoryId'] , 'srNo' => ($records+$i+1) ),$feeConcessionRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
  

?>
