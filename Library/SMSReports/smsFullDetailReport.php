<?php
//-------------------------------------------------------
// Purpose: To store the records of time table report in array from the database for subject centric
//
// Author : Rajeev Aggarwal
// Created on : (31.10.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

     global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','MessagesCountList');
    define('ACCESS','view');

    if($sessionHandler->getSessionVariable('RoleId') == 5){
	UtilityManager::ifManagementNotLoggedIn();
    }
    else{
	UtilityManager::ifNotLoggedIn();
    }
    UtilityManager::headerNoCache();
    require_once(MODEL_PATH . "/SMSDetailManager.inc.php");
    $smsdetailManager  = SMSDetailManager::getInstance();

//    require_once(BL_PATH.'/HtmlFunctions.inc.php');
//    $htmlManager = HtmlFunctions::getInstance();

    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////

    /// Search filter /////


    $fromDate =  $REQUEST_DATA['fromDate'];
    $toDate  =   $REQUEST_DATA['toDate'];


    $filter = " AND (DATE_FORMAT(dated,'%Y-%m-%d') BETWEEN '$fromDate' AND '$toDate') ";


    if(UtilityManager::notEmpty($REQUEST_DATA['messageType'])) {
       if($REQUEST_DATA['messageType']!='All') {
            $filter .= " AND (messageType ='".$REQUEST_DATA['messageType']."')";
       }
    }

    if(UtilityManager::notEmpty($REQUEST_DATA['receiverType'])) {
       if($REQUEST_DATA['receiverType']!='All') {
          if($REQUEST_DATA['receiverType']=='Parent') {
            $filter .= " AND (receiverType IN ('Father','Mother','Guardian'))";
          }
          else {
            $filter .= " AND (receiverType ='".$REQUEST_DATA['receiverType']."')";
          }
       }
    }

   $searchOrder = $REQUEST_DATA['searchOrder'];
		
	$flag="'".$REQUEST_DATA['txtSearch']."%' ";
	if($searchOrder==2) {
	   $flag="'%".$REQUEST_DATA['txtSearch']."%' ";
	}
	if(UtilityManager::notEmpty($REQUEST_DATA['txtSearch'])) {
	   $filter .= " AND (u.userName LIKE  $flag )";
	}


    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'fromDate';
    //$orderBy = " ORDER by messageType, receiverType, dated DESC, cnt DESC ";
    $orderBy = " ORDER BY $sortField $sortOrderBy";

    $totalArray = $smsdetailManager->getTotalSMSFullDetailList($filter);
    $SMSDetailRecordArray = $smsdetailManager->getSMSFullDetailList($filter,$orderBy,$limit);
    $cnt = count($SMSDetailRecordArray);

    for($i=0;$i<$cnt;$i++) {

        $SMSDetailRecordArray[$i]['fromDate'] = UtilityManager::formatDate($SMSDetailRecordArray[$i]['fromDate']);
        $SMSDetailRecordArray[$i]['toDate'] = UtilityManager::formatDate($SMSDetailRecordArray[$i]['toDate']);
        $SMSDetailRecordArray[$i]['cnt'] = $SMSDetailRecordArray[$i]['cnt'];
        $valueArray = array_merge(array('srNo' => ($records+$i+1)), $SMSDetailRecordArray[$i]);
        if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
        }
        else{
            $json_val .= ','.json_encode($valueArray);
        }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}';

// for VSS
// $History: smsFullDetailReport.php $
//
//*****************  Version 6  *****************
//User: Parveen      Date: 11/16/09   Time: 3:55p
//Updated in $/LeapCC/Library/SMSReports
//date format check updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/21/09    Time: 6:00p
//Updated in $/LeapCC/Library/SMSReports
//sorting formatting updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/21/09    Time: 3:58p
//Updated in $/LeapCC/Library/SMSReports
//role permission & removePHPJS  function updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 8/21/09    Time: 12:28p
//Updated in $/LeapCC/Library/SMSReports
//sorting order check updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/19/09    Time: 2:36p
//Updated in $/LeapCC/Library/SMSReports
//code update search for & condition update
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/SMSReports
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/28/08   Time: 5:36p
//Updated in $/Leap/Source/Library/SMSReports
//list and report formatting
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/28/08   Time: 11:30a
//Updated in $/Leap/Source/Library/SMSReports
//change list formatting
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/27/08   Time: 1:08p
//Updated in $/Leap/Source/Library/SMSReports
//sms details message search
//
//*****************  Version 1  *****************
//User: Parveen      Date: 11/27/08   Time: 12:27p
//Created in $/Leap/Source/Library/SMSReports
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/26/08   Time: 5:06p
//Updated in $/Leap/Source/Library/SMSReports
//sms details report added
//



?>
