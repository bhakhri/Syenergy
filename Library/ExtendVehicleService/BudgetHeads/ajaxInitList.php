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
    define('MODULE','BudgetHeads');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/BudgetHeadsManager.inc.php");
    $budgetManager = BudgetHeadsManager::getInstance();
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
	if($REQUEST_DATA['searchbox'] == "Guest House"){
		$REQUEST_DATA['searchbox']=1;
	}
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (headName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR headAmount LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR headTypeId Like"%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" )';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'headName';
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $budgetManager->getTotalBudgetHeads($filter);
    $budgetRecordArray = $budgetManager->getBudgetHeadsList($filter,$limit,$orderBy);
    $cnt = count($budgetRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        $budgetRecordArray[$i]['headTypeId']=$globalBudgetHeadTypeArray[$budgetRecordArray[$i]['headTypeId']];
        $valueArray = array_merge(array('action' => $budgetRecordArray[$i]['budgetHeadId'] , 'srNo' => ($records+$i+1) ),$budgetRecordArray[$i]);

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