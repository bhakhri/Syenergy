<?php
//This file is used as printing version for SMS
//
// Author :Parveen Sharma
// Created on : 31-04-2011
// Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
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
    
    if(UtilityManager::notEmpty($REQUEST_DATA['fromDate'])) {
       $filter .= " where (sentDate >='".add_slashes($REQUEST_DATA['fromDate'])."')";         
    }

    if(UtilityManager::notEmpty($REQUEST_DATA['toDate'])) {
       $filter .= " AND (sentDate <='".add_slashes($REQUEST_DATA['toDate'])."')";         
    }

     
    if(UtilityManager::notEmpty($REQUEST_DATA['messageType'])) {
       if($REQUEST_DATA['messageType']!='All') {
            $filter .= " AND (smsStatus ='".$REQUEST_DATA['messageType']."')";         
       }
    }
    
     
   /// Search filter /////  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'sentDate';
    $orderBy = " ORDER BY $sortField $sortOrderBy";    
    

    $totalArray = $smsdetailManager->getTotalSMSStatusDetailList($filter);  
    
    $SMSDetailRecordArray = $smsdetailManager->getSMSStatusDetailList($filter,$orderBy,'');
    $cnt = count($SMSDetailRecordArray);

    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {   
        $valueArray[] = array(  'srNo' => $i+1,
                                'sentDate' => UtilityManager::formatDate($SMSDetailRecordArray[$i]['sentDate']),
                                'smsStatus' => $SMSDetailRecordArray[$i]['smsStatus'],
                                'smsFrom' => $SMSDetailRecordArray[$i]['smsFrom'],
                                'smsTo' => $SMSDetailRecordArray[$i]['smsTo'],
                                'smsText' => $htmlManager->removePHPJS(str_replace("+"," ",$SMSDetailRecordArray[$i]['smsText']))                                
                            );
   }
    
    $reportHead  = "for <b>SMS Type</b>:&nbsp;".$REQUEST_DATA['messageName'];
    $reportHead .= "&nbsp;&nbsp;<b> From Date</b>:&nbsp;".UtilityManager::formatDate($REQUEST_DATA['fromDate'])."&nbsp;&nbsp;<b>To Date</b>&nbsp;&nbsp;".UtilityManager::formatDate($REQUEST_DATA['toDate']);

    
    $reportManager->setReportWidth(900);
    $reportManager->setReportHeading('SMS Delivery Report');
    $reportManager->setReportInformation($reportHead);    
    
    $reportTableHead                        =    array();
                //associated key          col.label,             col. width,      data align
    $reportTableHead['srNo']         = array('#',                'width="5%"  align="center"',  'align="center"');
    $reportTableHead['smsFrom']        = array('Sent From',             'width="15%" align="left"', 'align="left"');
    $reportTableHead['smsTo']     = array('Sent To',           'width="15%" align="left"', 'align="left"');
    $reportTableHead['smsText']          = array('Message',   'width="40%" align="left"', 'align="left"');
    $reportTableHead['sentDate']      = array('Sent On',          'width="10%" align="left"', 'align="left"');
    $reportTableHead['smsStatus']  = array('Status',  'width="15%" align="left"', 'align="left"');
   

    $reportManager->setRecordsPerPage(50);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();
?>