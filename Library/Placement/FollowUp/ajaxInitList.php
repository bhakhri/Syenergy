<?php
//-------------------------------------------------------
// Purpose: To store the records of universities in array from the database, pagination and search, delete 
// functionality
// Author : Dipanjan Bbhattacharjee
// Created on : (30.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','PlacementFollowUpsMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Placement/FollowUpManager.inc.php");
    $followUpManager = FollowUpManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $search=strtoupper(trim($REQUEST_DATA['searchbox']));
       $contactedVia=-1;
       if($search=='EMAIL'){
           $contactedVia=1;
       }
       elseif($search=='LANDLINE'){
           $contactedVia=2;
       }
       elseif($search=='MOBILE'){
           $contactedVia=3;
       }
       elseif($search=='SMS'){
           $contactedVia=4;
       }
       $filter = ' AND ( c.companyCode LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR f.contactedPerson LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR f.designation LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR f.contactedVia LIKE "%'.$contactedVia.'%")';         
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'companyCode';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $followUpManager->getTotalFollowUp($filter);
    $followUpRecordArray = $followUpManager->getFollowUpList($filter,$limit,$orderBy);
    $cnt = count($followUpRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       
       $followUpRecordArray[$i]['contactedOn'] =UtilityManager::formatDate($followUpRecordArray[$i]['contactedOn']);
        
       if($followUpRecordArray[$i]['contactedVia']==1){
           $followUpRecordArray[$i]['contactedVia']='Email';
       }
       elseif($followUpRecordArray[$i]['contactedVia']==2){
           $followUpRecordArray[$i]['contactedVia']='Landline';
       }
       elseif($followUpRecordArray[$i]['contactedVia']==3){
           $followUpRecordArray[$i]['contactedVia']='Mobile';
       }
       else{
           $followUpRecordArray[$i]['contactedVia']='SMS';
       }
       $valueArray = array_merge(array('action' => $followUpRecordArray[$i]['followUpId'] , 'srNo' => ($records+$i+1) ),$followUpRecordArray[$i]);
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