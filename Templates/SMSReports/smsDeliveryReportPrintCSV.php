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
             return $comments; 
         }
    }
    
    
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
         //$msg = $htmlManager->removePHPJS($SMSDetailRecordArray[$i]['message']); 
         $msg = parseCSVComments($SMSDetailRecordArray[$i]['message']);
         $valueArray[] = array( 'srNo' => $i+1,
                                'sentDate' => parseCSVComments(UtilityManager::formatDate($SMSDetailRecordArray[$i]['sentDate'])),
                                'smsStatus' => parseCSVComments($SMSDetailRecordArray[$i]['smsStatus']),
                                'smsFrom' => parseCSVComments($SMSDetailRecordArray[$i]['smsFrom']),
                                'smsTo' => parseCSVComments($SMSDetailRecordArray[$i]['smsTo']),
                                'smsText' => parseCSVComments($htmlManager->removePHPJS(str_replace("+"," ",$SMSDetailRecordArray[$i]['smsText'])))
                              );
   }
   
    $csvData = '';
    $reportHead  = "SMS Type,".$REQUEST_DATA['messageName'];
    $reportHead .= ",From Date,".UtilityManager::formatDate($REQUEST_DATA['fromDate']).",To Date,".UtilityManager::formatDate($REQUEST_DATA['toDate'])."\n";

    $csvData .= $reportHead;
    $csvData .= "Sr. No., Sent From, Sent To, Message, Sent On, Status\n";
    foreach($valueArray as $record) {
        $csvData .= $record['srNo'].','.$record['smsFrom'].','.$record['smsTo'].','.$record['smsText'].','
        .$record['sentDate'].','.$record['smsStatus'];
        $csvData .= "\n";                                                                            
    }
    ob_end_clean();
    header("Cache-Control: public, must-revalidate");
    // We'll be outputting a PDF
    header('Content-type: application/octet-stream');
    header("Content-Length: " .strlen($csvData) );
    // It will be called downloaded.pdf
    header('Content-Disposition: attachment;  filename="SMSDeliveryReport.csv"');
    // The PDF source is in original.pdf
    header("Content-Transfer-Encoding: binary\n");
    echo $csvData;
    die;    
 ?>