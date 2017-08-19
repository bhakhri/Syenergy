<?php
//--------------------------------------------------------------------------------------------------------------
// Purpose: To show data in array from the database, pagination 
//
// Author : Gurkeerat Sidhu
// Created on : (29.12.09)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','UserLoginReport');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true); 
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FineManager.inc.php");
    $fineManager = FineManager::getInstance();
    require_once(MODEL_PATH . "/DashBoardManager.inc.php");
    $dashboardManager = DashBoardManager::getInstance();
    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'roleUserName';
    $listView = $REQUEST_DATA['listView'];
    $orderBy = " $sortField $sortOrderBy";

    ////////////

	$startDate = $REQUEST_DATA['startDate'];
	$toDate = $REQUEST_DATA['toDate'];

	$filter = "(DATE_FORMAT(dateTimeIn,'%Y-%m-%d') BETWEEN '$startDate' AND '$toDate')";
    if ($listView == 1){
        $totalRecordArray = $dashboardManager->getStudentNotLoggedInTotal($filter);
        $userLoginRecordArray = $dashboardManager->getStudentNotLoggedinList($filter,$limit,$orderBy);
        $cnt = count($userLoginRecordArray);
        $totalRecord=$totalRecordArray[0]['totalCount'];  
    }
    else{
	    $totalRecordArray = $dashboardManager->getUserLoginTotal($filter);
	    $userLoginRecordArray = $dashboardManager->getUserLoginList($filter,$limit,$orderBy);
	    $cnt = count($userLoginRecordArray);
        $totalRecord=count($totalRecordArray);
	}
    for($i=0;$i<$cnt;$i++) {
        if($listView == 0){
        if($userLoginRecordArray[$i]['roleUserName']=='')
            $userLoginRecordArray[$i]['roleUserName']="Administrator";
        }
		
        $valueArray = array_merge(array( 'srNo' => ($records+$i+1) ),$userLoginRecordArray[$i]);  
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalRecord.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    

?>