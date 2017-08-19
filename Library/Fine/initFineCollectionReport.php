<?php
//--------------------------------------------------------------------------------------------------------------
// Purpose: To show data in array from the database, pagination 
//
// Author : Jaineesh
// Created on : (06.07.09)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','FineCollectionReport');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
global $sessionHandler; 
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
  UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FineManager.inc.php");
    $fineManager = FineManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'fc.fineCategoryName';
    
    $orderBy = " $sortField $sortOrderBy";

    ////////////

	$startDate = $REQUEST_DATA['startDate'];
	$toDate = $REQUEST_DATA['toDate'];

	$filter = "AND (fr.receiptDate BETWEEN '$startDate' AND '$toDate')";
	$fineTotalRecordArray = $fineManager->getTotalFineCollectionReportDetail($filter);
	$collectionFineRecordArray = $fineManager->getFineCollectionReportDetail($filter,$limit,$orderBy);
	$cnt = count($collectionFineRecordArray);
	
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface
		//$studentId = $collectionFineRecordArray[$i]['studentId'];
		$fineCategoryId = $collectionFineRecordArray[$i]['fineCategoryId'];

		$viewDetail = '<img src='.IMG_HTTP_PATH.'/print.jpg border="0" alt="View" title="View" width="20" height="20" style="cursor:hand" onclick="printReport('.$fineCategoryId.','.$startDate.','.$toDate.');return false;" title="View Detail">';
        $valueArray = array_merge(array('viewDetail'=>$viewDetail, 'srNo' => ($records+$i+1) ),$collectionFineRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($fineTotalRecordArray).'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: initFineCollectionReport.php $
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/Fine
//added access defines for management login
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 12/09/09   Time: 2:33p
//Updated in $/LeapCC/Library/Fine
//fixed bug No.0002201
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/05/09   Time: 6:23p
//Updated in $/LeapCC/Library/Fine
//fixed bug nos.0002204, 0002202, 0002201, 0002203, 0002198, 0002197,
//0002185, 0002187, 0002200, 0002199, 0002183, 0002160, 0002156, 0002157,
//0002166, 0002165, 0002164, 0002163, 0002162, 0002161, 0002176, 0002181,
//0002180, 0002179, 0002178, 0002159, 0002158
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/07/09    Time: 7:10p
//Updated in $/LeapCC/Library/Fine
//some modification in code & put approveByUserId in fine_student table
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/07/09    Time: 10:30a
//Created in $/LeapCC/Library/Fine
//new ajax file to show fine collection report
//
?>