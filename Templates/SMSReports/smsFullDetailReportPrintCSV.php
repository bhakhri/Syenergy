<?php 
//This file is used as csv output of SMS Detail Report.
//
// Author :Parveen Sharma
// Created on : 27-11-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    
    require_once(MODEL_PATH . "/SMSDetailManager.inc.php");
    $smsdetailManager  = SMSDetailManager::getInstance();


    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    
    $messageType  = $REQUEST_DATA['messageType']; 
    $receiverType = $REQUEST_DATA['receiverType'];
    $fromDate =  $REQUEST_DATA['fromDate'];
    $toDate  =   $REQUEST_DATA['toDate'];
           

           
    
/*  
    if(UtilityManager::notEmpty($REQUEST_DATA['fromDate'])) {
       $filter .= " AND (dated >='".add_slashes($REQUEST_DATA['fromDate'])."')";         
    }

    if(UtilityManager::notEmpty($REQUEST_DATA['toDate'])) {
       $filter .= " AND (dated <='".add_slashes($REQUEST_DATA['toDate'])."')";         
    }
*/
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
    $orderBy = " ORDER BY $sortField $sortOrderBy";     

    
    $SMSDetailRecordArray = $smsdetailManager->getSMSFullDetailList($filter,$orderBy,'');
    $cnt = count($SMSDetailRecordArray);

    $valueArray = array();

    for($i=0;$i<$cnt;$i++) {
        $valueArray[] = array(  'srNo' => $i+1,
                                'userName' => $SMSDetailRecordArray[$i]['userName'],
                                'cnt' => $SMSDetailRecordArray[$i]['cnt'],
                                'messageType' => $SMSDetailRecordArray[$i]['messageType'],
                                'receiverType' => $SMSDetailRecordArray[$i]['receiverType'],
                                'fromDate' =>UtilityManager::formatDate($SMSDetailRecordArray[$i]['fromDate']),
                                'toDate' => UtilityManager::formatDate($SMSDetailRecordArray[$i]['toDate'])
                            );
   }
   
    $csvData = '';
    $reportHead  = "Message Type,".$REQUEST_DATA['messageName'];
    $reportHead .= ",Receiver Type,".$REQUEST_DATA['receiverName']."\n";
    $reportHead .= "From Date,".UtilityManager::formatDate($REQUEST_DATA['fromDate']).",To Date,".UtilityManager::formatDate($REQUEST_DATA['toDate'])."\n";
    $csvData .= $reportHead;
    $csvData .= "#, From Date, To Date, Sender, No. of Messages, Type of Messages, Receiver \n";
	$find=0;
    foreach($valueArray as $record) {
		$find=1;
        $csvData .= $record['srNo'].','.$record['fromDate'].','.$record['toDate'].','.$record['userName'].','.$record['cnt'].','.  
        $record['messageType'].','.$record['receiverType'];
        $csvData .= "\n";
    }
	   if($find==0) {
		$csvData .= ",,,No Data Found";
	}
   UtilityManager::makeCSV($csvData,'MessageCountList.csv');
    
    die;
 

// $History: smsFullDetailReportPrintCSV.php $
//
//*****************  Version 7  *****************
//User: Parveen      Date: 11/24/09   Time: 4:38p
//Updated in $/LeapCC/Templates/SMSReports
//parent send message condition updated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 11/14/09   Time: 6:06p
//Updated in $/LeapCC/Templates/SMSReports
//date format checks updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/21/09    Time: 5:40p
//Updated in $/LeapCC/Templates/SMSReports
//formatting & role permission added
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/21/09    Time: 3:58p
//Updated in $/LeapCC/Templates/SMSReports
//role permission & removePHPJS  function updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 8/21/09    Time: 12:28p
//Updated in $/LeapCC/Templates/SMSReports
//sorting order check updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/19/09    Time: 2:36p
//Updated in $/LeapCC/Templates/SMSReports
//code update search for & condition update
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/SMSReports
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/28/08   Time: 12:23p
//Updated in $/Leap/Source/Templates/SMSReports
//tabs settings
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/28/08   Time: 11:30a
//Updated in $/Leap/Source/Templates/SMSReports
//change list formatting
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/27/08   Time: 5:22p
//Updated in $/Leap/Source/Templates/SMSReports
//add fields messages
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/27/08   Time: 1:08p
//Updated in $/Leap/Source/Templates/SMSReports
//sms details message search
//
//*****************  Version 1  *****************
//User: Parveen      Date: 11/27/08   Time: 12:27p
//Created in $/Leap/Source/Templates/SMSReports
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/27/08   Time: 11:27a
//Updated in $/Leap/Source/Templates/SMSReports
//
//*****************  Version 1  *****************
//User: Parveen      Date: 11/27/08   Time: 10:55a
//Created in $/Leap/Source/Templates/SMSReports
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 9/24/08    Time: 7:11p
//Updated in $/Leap/Source/Templates/ScStudent
//changed CSV headers
?>
