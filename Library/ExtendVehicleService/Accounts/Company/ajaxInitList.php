<?php
//-------------------------------------------------------
//  This File contains logic for company
//
//
// Author :Ajinder Singh
// Created on : 10-aug-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','Company');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
	//UtilityManager::ifCompanyNotSelected();
    UtilityManager::headerNoCache();

	require_once(MODEL_PATH . '/Accounts/CompanyManager.inc.php');
    $companyManager = CompanyManager::getInstance();


    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

	/// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = " WHERE (companyName LIKE '".$REQUEST_DATA['searchbox']."%') OR (address LIKE '".$REQUEST_DATA['searchbox']."%')  OR (email LIKE '".$REQUEST_DATA['searchbox']."%')  OR (fyearFrom LIKE '".$REQUEST_DATA['searchbox']."%')  OR (fyearTo LIKE '".$REQUEST_DATA['searchbox']."%') ";
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'companyName';
    
    $orderBy = " $sortField $sortOrderBy";

    
    $totalArray = $companyManager->getTotalCompanies($filter);
    $companyRecordArray = $companyManager->getCompaniesList($filter,$limit,$orderBy);

    $cnt = count($companyRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add bankId in actionId to populate edit/delete icons in User Interface
        $valueArray = array_merge(array('action' => $companyRecordArray[$i]['companyId'] , 'srNo' => ($records+$i+1) ),$companyRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 

// $History: ajaxInitList.php $
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/10/09    Time: 6:47p
//Updated in $/LeapCC/Library/Accounts/Company
//removed access rights, placed accidently
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 4:44p
//Created in $/LeapCC/Library/Accounts/Company
//file added
//




?>
