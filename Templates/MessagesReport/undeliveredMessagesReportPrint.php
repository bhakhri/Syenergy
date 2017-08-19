<?php
//This file is used as printing version for SMS
//
// Created on : 22-1-2011
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

    $messageId  = $REQUEST_DATA['messageId']; 
    
	$htmlManager = HtmlFunctions::getInstance();
	$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
	$records    = ($page-1)* RECORDS_PER_PAGE;
	$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////

    /// Search filter /////  
	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
	$sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'userName';
	$orderBy = " ORDER BY $sortField $sortOrderBy";
	
   if(trim($messageId ) != '') {
		$smsdetailManager  = SMSDetailManager::getInstance();
		$detailsArray = $smsdetailManager->getUndeliveredMessages($messageId,$orderBy,''); 
		$cnt=count($detailsArray);					
		$messageDetailArray = $smsdetailManager->getMessageDetails($messageId); 
		if($messageDetailArray == false){
		 echo FALURE;
		}

		$valueArray = array();

		for($i=0;$i<$cnt;$i++) {
        $valueArray[] = array(  'srNo' => $i+1,
                                'userName' =>$detailsArray[$i]['userName'],
								'role'=>$detailsArray[$i]['role'],
                                'name' => $detailsArray[$i]['name']                              
                            );
		}
   }
  // print_r($valueArray); die;
	$reportHead  = "<b>Type</b>:&nbsp;".$messageDetailArray[0]['messageType']."<br/>";
	$reportHead .= "<b>Message</b>:&nbsp;".$messageDetailArray[0]['message']."<br>";
	$reportHead .= "<b>Dated</b>:&nbsp;". UtilityManager::formatDate($messageDetailArray[0]['dated'])."<br>";
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Undelivered Recipients');
    $reportManager->setReportInformation($reportHead);    

    $reportTableHead = array();
    $reportTableHead['srNo'] = array('#','width="5%"  align="center"','align="center"');
    $reportTableHead['userName'] = array('User Name','width="15%" align="left"', 'align="left"');
    $reportTableHead['role'] = array('Role','width="10%" align="left"', 'align="left"');
    $reportTableHead['name'] = array('Name','width="15%" align="left"', 'align="left"');
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
