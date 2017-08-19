<?php
//-------------------------------------------------------
// Purpose: To store the records of FeeCycleFine in array from the database, pagination and search, delete 
// functionality
//
// Author : Arvind Singh Rawat
// Created on : (1.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','FeeCycleFines');    
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FeeCycleFineManager.inc.php");
    $feeCycleFineManager =FeeCycleFineManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (fc.cycleName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                    DATE_FORMAT(fcf.fromDate,"%d-%b-%y") LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                    DATE_FORMAT(fcf.toDate,"%d-%b-%y")  LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                    fcf.fineAmount LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                    fcf.fineType LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';        
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'fineAmount';
    
     $orderBy = "$sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $feeCycleFineManager->getFeeCycleFineListPrint($filter);
    $totalRecord = count($totalArray);
    
    $feeCycleFineRecordArray = $feeCycleFineManager->getFeeCycleFineListPrint($filter,$limit,$orderBy);
    $cnt = count($feeCycleFineRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
	 
       $feeCycleFineRecordArray[$i]['fromDate']=UtilityManager::formatDate($feeCycleFineRecordArray[$i]['fromDate']);
	   $feeCycleFineRecordArray[$i]['toDate']=UtilityManager::formatDate($feeCycleFineRecordArray[$i]['toDate']);
		// add feeCycleFineId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $feeCycleFineRecordArray[$i]['feeCycleFineId'] , 'srNo' => ($records+$i+1) ),
                                    $feeCycleFineRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalRecord.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
  
  //$History : $  
?>

 