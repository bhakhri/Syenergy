<?php
//This file is used as printing version for SMS
//
// Author :Parveen Sharma
// Created on : 26-11-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>

<?php                
    require_once(BL_PATH . "/UtilityManager.inc.php");                    
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once($FE . "/Library/HtmlFunctions.inc.php");
    $htmlManager  = HtmlFunctions::getInstance();
    
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    require_once(MODEL_PATH . "/SMSDetailManager.inc.php");
    $smsdetailManager  = SMSDetailManager::getInstance();

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
        $msg = $SMSDetailRecordArray[$i]['message'];
        $msg = html_entity_decode($msg);
        
        $valueArray[] = array(  'srNo' => $i+1,
                                'dated' => UtilityManager::formatDate($SMSDetailRecordArray[$i]['dated']),
                                'userName' => $SMSDetailRecordArray[$i]['userName'],
                                'cnt' => $SMSDetailRecordArray[$i]['cnt'],
                                'subject' => $SMSDetailRecordArray[$i]['subject'],
                                'messageType' => $SMSDetailRecordArray[$i]['messageType'],
                                'receiverType' => $SMSDetailRecordArray[$i]['receiverType'],
                                'message' => $msg                                
                            );
   }
    
    $reportHead  = "<b>Message Type</b>:&nbsp;".$REQUEST_DATA['messageName'];
    $reportHead .= ",&nbsp;&nbsp;<b>Receiver Type</b>:&nbsp;".$REQUEST_DATA['receiverName']."<br>";
    $reportHead .= "<b>Date</b>:&nbsp;".UtilityManager::formatDate($REQUEST_DATA['fromDate'])."&nbsp;&nbsp;to&nbsp;&nbsp;".UtilityManager::formatDate($REQUEST_DATA['toDate']);
   if(trim($REQUEST_DATA['txtSearch'])!='') {	
      $reportHead .= ",<br><b>Search By</b>:&nbsp;".$REQUEST_DATA['txtSearch'];
    }
    
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Messages List Report');
    $reportManager->setReportInformation($reportHead);    
    
    $reportTableHead                        =    array();
                //associated key          col.label,             col. width,      data align
    $reportTableHead['srNo']         = array('#',                'width="5%"  align="center"',  'align="center"');
    $reportTableHead['dated']        = array('Date ',             'width="15%" align="center"', 'align="center"');
    $reportTableHead['userName']     = array('Sender',           'width="10%" align="left"', 'align="left"');
   // $reportTableHead['cnt']          = array('No. of Messages',   'width="15%" align="right"', 'align="right"');
    $reportTableHead['subject']      = array('Subject',          'width="15%" align="left"', 'align="left"');
    $reportTableHead['messageType']  = array('Type',  'width="15%" align="left"', 'align="left"');
    $reportTableHead['receiverType'] = array('Receiver',         'width="10%" align="left"', 'align="left"');
    $reportTableHead['message']      = array('Brief Description','width="15%" align="left"',  'align="left"');
   

    $reportManager->setRecordsPerPage(50);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();
    
// $History: smsDetailReportPrint.php $
//
//*****************  Version 8  *****************
//User: Parveen      Date: 12/11/09   Time: 2:50p
//Updated in $/LeapCC/Templates/SMSReports
//format updated
//
//*****************  Version 7  *****************
//User: Parveen      Date: 12/01/09   Time: 11:56a
//Updated in $/LeapCC/Templates/SMSReports
//report print width updated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 11/24/09   Time: 4:38p
//Updated in $/LeapCC/Templates/SMSReports
//parent send message condition updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/14/09   Time: 6:06p
//Updated in $/LeapCC/Templates/SMSReports
//date format checks updated
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
//*****************  Version 7  *****************
//User: Parveen      Date: 11/28/08   Time: 5:36p
//Updated in $/Leap/Source/Templates/SMSReports
//list and report formatting
//
//*****************  Version 6  *****************
//User: Parveen      Date: 11/28/08   Time: 12:23p
//Updated in $/Leap/Source/Templates/SMSReports
//tabs settings
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/28/08   Time: 10:45a
//Updated in $/Leap/Source/Templates/SMSReports
//changed lists view format
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/27/08   Time: 5:22p
//Updated in $/Leap/Source/Templates/SMSReports
//add fields messages
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/27/08   Time: 11:27a
//Updated in $/Leap/Source/Templates/SMSReports
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/26/08   Time: 5:06p
//Updated in $/Leap/Source/Templates/SMSReports
//sms details report added
//

?>
