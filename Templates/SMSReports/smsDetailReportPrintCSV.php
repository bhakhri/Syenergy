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

    require_once($FE . "/Library/HtmlFunctions.inc.php");
    $htmlManager  = HtmlFunctions::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    
      // CSV data field Comments added 
    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
           return '"'.$comments.'"'; 
         } 
         else {
             return chr(160).$comments; 
         }
    }
    
    
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
	   $filter .= " AND (a.subject LIKE  $flag OR
			     a.message LIKE  $flag OR
			     u.userName LIKE  $flag )";
	}
     
    /// Search filter /////  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'dated';
    $orderBy = " ORDER BY $sortField $sortOrderBy";
                                                                                  
    $totalArray = $smsdetailManager->getTotalSMSDetailList($filter);  
    
    $SMSDetailRecordArray = $smsdetailManager->getSMSDetailList($filter,$orderBy,'');
    $cnt = count($SMSDetailRecordArray);

    $valueArray = array();

    for($i=0;$i<$cnt;$i++) {
         //$msg = $htmlManager->removePHPJS($SMSDetailRecordArray[$i]['message']); 
         $msg = $htmlManager->removePHPJS(html_entity_decode($SMSDetailRecordArray[$i]['message']),'','1'); 
         
         $valueArray[] = array(  'srNo' => $i+1,
                                    'dated' => UtilityManager::formatDate($SMSDetailRecordArray[$i]['dated']),
                                    'userName' => $SMSDetailRecordArray[$i]['userName'],
                                    'cnt' => $SMSDetailRecordArray[$i]['cnt'],
                                    'subject' => str_replace(',','"',$SMSDetailRecordArray[$i]['subject']),
                                    'messageType' => $SMSDetailRecordArray[$i]['messageType'],
                                    'receiverType' => $SMSDetailRecordArray[$i]['receiverType'],
                                    'message' => $msg
                              );
   }
   
    $csvData = '';
    $reportHead  = "Message Type,".$REQUEST_DATA['messageName'];
    $reportHead .= ",Receiver Type,".$REQUEST_DATA['receiverName']."\n";
    $reportHead .= "From Date,".UtilityManager::formatDate($REQUEST_DATA['fromDate']).",To Date,".UtilityManager::formatDate($REQUEST_DATA['toDate'])."\n";

    $csvData .= $reportHead;
    $csvData .= "#, Date Sent, Sender, No. of Messages, Subject, Type of Messges, Receiver, Brief Description \n";
	$find=0;
    foreach($valueArray as $record) {
		$find=1;
        $csvData .= $record['srNo'].','.$record['dated'].','.$record['userName'].','.$record['cnt'].','.$record['subject'].','
        .$record['messageType'].','.$record['receiverType'].','.$record['message']."\n";                                                                            
    }
    if($find==0) {
		$csvData .= ",,,No Data Found";
	}

	UtilityManager::makeCSV($csvData,'messagelist.csv');
    
    die;

// $History: smsDetailReportPrintCSV.php $
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
//User: Parveen      Date: 9/17/09    Time: 12:55p
//Updated in $/LeapCC/Templates/SMSReports
//html_entity_decode function added
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/21/09    Time: 3:58p
//Updated in $/LeapCC/Templates/SMSReports
//role permission & removePHPJS  function updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/19/09    Time: 2:36p
//Updated in $/LeapCC/Templates/SMSReports
//code update search for & condition update
//
//*****************  Version 2  *****************
//User: Parveen      Date: 3/31/09    Time: 5:46p
//Updated in $/LeapCC/Templates/SMSReports
//formatting settings
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/SMSReports
//
//*****************  Version 6  *****************
//User: Parveen      Date: 11/28/08   Time: 5:36p
//Updated in $/Leap/Source/Templates/SMSReports
//list and report formatting
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/28/08   Time: 12:23p
//Updated in $/Leap/Source/Templates/SMSReports
//tabs settings
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/28/08   Time: 10:45a
//Updated in $/Leap/Source/Templates/SMSReports
//changed lists view format
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/27/08   Time: 5:22p
//Updated in $/Leap/Source/Templates/SMSReports
//add fields messages
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
