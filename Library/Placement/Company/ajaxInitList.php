<?php
//-------------------------------------------------------
// Purpose: To store the records of universities in array from the database, pagination and search, delete 
// functionality
// Author : Dipanjan Bbhattacharjee
// Created on : (30.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','PlacementComapanyMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Placement/CompanyManager.inc.php");
    $companyManager = CompanyManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    

	/// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND ( companyName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR companyCode LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR landline LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR mobileNo LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR contactPerson LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR emailId LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';         
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'companyName';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $companyManager->getTotalCompany($filter);
    $companyRecordArray = $companyManager->getCompanyList($filter,$limit,$orderBy);
    $cnt = count($companyRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       $companyRecordArray[$i]['companyCode']=htmlentities($companyRecordArray[$i]['companyCode']);
       $companyRecordArray[$i]['companyName']=htmlentities($companyRecordArray[$i]['companyName']);
       $companyRecordArray[$i]['landline']=htmlentities($companyRecordArray[$i]['landline']);
       $companyRecordArray[$i]['mobileNo']=htmlentities($companyRecordArray[$i]['mobileNo']);
       $companyRecordArray[$i]['contactPerson']=htmlentities($companyRecordArray[$i]['contactPerson']);
       $companyRecordArray[$i]['emailId']=htmlentities($companyRecordArray[$i]['emailId']);
       
       if(strlen($companyRecordArray[$i]['companyName'])>100){
           $companyRecordArray[$i]['companyName']=substr($companyRecordArray[$i]['companyName'],0,97).'...';
       }         
       $valueArray = array_merge(array('action' => $companyRecordArray[$i]['companyId'] , 'srNo' => ($records+$i+1) ),$companyRecordArray[$i]);
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