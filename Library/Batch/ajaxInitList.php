<?php
//-------------------------------------------------------
// Purpose: To store the records of Batch in array from the database, pagination and search, delete
// functionality
//
// Author : Arvind Singh Rawat
// Created on : (30.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','BatchMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    require_once(MODEL_PATH . "/BatchManager.inc.php");
    $batchManager = BatchManager::getInstance();

    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (bat.batchName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR bat.startDate LIKE "'.date("Y-m-d",strtotime($REQUEST_DATA['searchbox'])).'%" OR bat.endDate LIKE "'.date("Y-m-d",strtotime($REQUEST_DATA['searchbox'])).'%"  OR studentId LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR bat.batchYear LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
	 /* if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      $filter = ' HAVING (ipm.poNo LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR u.userName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ipm.poDate LIKE "%'.date("Y-m-d", strtotime($REQUEST_DATA['searchbox'])).'%" )';
    }*/
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'batchName';
    if ($sortField == "studentId") {
		 $orderBy = " $sortField $sortOrderBy";
    }
	 else {
		 $orderBy = " bat.$sortField $sortOrderBy";
	 }

	 $totalArray = $batchManager->getBatchList($filter);
    $batchRecordArray = $batchManager->getBatchList($filter,$limit,$orderBy);
    $cnt = count($batchRecordArray);
    for($i=0;$i<$cnt;$i++) {
        $batchRecordArray[$i]['startDate']=UtilityManager::formatDate(strip_slashes($batchRecordArray[$i]['startDate']));
        $batchRecordArray[$i]['endDate']=UtilityManager::formatDate(strip_slashes($batchRecordArray[$i]['endDate']));
        // add batchId in actionId to populate edit/delete icons in User Interface
        $valueArray = array_merge(array('action' => $batchRecordArray[$i]['batchId'] , 'srNo' => ($records+$i+1) ),$batchRecordArray[$i]);
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}';
// $History: ajaxInitList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Batch
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/06/08   Time: 10:14a
//Updated in $/Leap/Source/Library/Batch
//Added the module, access
//
//*****************  Version 4  *****************
//User: Arvind       Date: 9/20/08    Time: 2:59p
//Updated in $/Leap/Source/Library/Batch
//added common date function
//
//*****************  Version 3  *****************
//User: Arvind       Date: 8/27/08    Time: 3:21p
//Updated in $/Leap/Source/Library/Batch
//removed spaces
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/21/08    Time: 5:03p
//Updated in $/Leap/Source/Library/Batch
//added a new fields batchYear in query
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/30/08    Time: 4:06p
//Created in $/Leap/Source/Library/Batch
//added ajax listing file and ajax deletes file
?>