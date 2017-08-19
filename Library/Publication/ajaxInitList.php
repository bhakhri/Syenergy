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
    define('MODULE','PublicationMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/PublicationManager.inc.php");
    $publicationManager =publicationManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (publicationName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'publicationName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $publicationManager->getTotalPublication($filter);
    $publicationRecordArray = $publicationManager->getPublicationList($filter,$limit,$orderBy);
	$cnt = count($publicationRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add countryId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $publicationRecordArray[$i]['publicationId'] , 'srNo' => ($records+$i+1) ),$publicationRecordArray[$i]);

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