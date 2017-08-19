<?php 
//This file is used as csv output of SMS Report.
//
// Created on : 25-1-2011
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
	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
	$sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'userName';
	
	$orderBy = " ORDER BY $sortField $sortOrderBy"; 
    $messageId  = $REQUEST_DATA['messageId'];  

	if(trim($messageId) != '') {
    
		$smsdetailManager  = SMSDetailManager::getInstance();
		$detailsArray = $smsdetailManager->getUndeliveredMessages($messageId,$orderBy,''); 
		$cnt=count($detailsArray);
		$valueArray = array();
		for($i=0;$i<$cnt;$i++) {
			$valueArray[] = array(  'srNo' => $i+1,
										'userName' => $detailsArray[$i]['userName'],
										'role' => $detailsArray[$i]['role'],
										'name' => $detailsArray[$i]['name']    
								  );
	   }
	
		$messageDetailArray = $smsdetailManager->getMessageDetails($messageId); 
			if($messageDetailArray == false){
				echo FALURE;
			}
		$csvData = '';
		$reportHead  = "Undelivered Recipients\n";
		$reportHead .= "Type :".$messageDetailArray[0]['messageType']."\n";
		$reportHead .= "Message :".$messageDetailArray[0]['message']."\n";
		$reportHead .= "Dated :". UtilityManager::formatDate($messageDetailArray[0]['dated'])."\n";

		$csvData .= $reportHead;
		$csvData .= "#, User Name, Role,Name\n";
		$count= COUNT($valueArray);
		if($count == 0){
			$csvData .= "No Data Found";
		}
		foreach($valueArray as $record) {
			$csvData .= $record['srNo'].','.$record['userName'].','.$record['role'].','
			.$record['name']."\n";                                                                            
		}
	}
    ob_end_clean();
    header("Cache-Control: public, must-revalidate");
    // We'll be outputting a PDF
    header('Content-type: application/octet-stream');
    header("Content-Length: " .strlen($csvData) );
    // It will be called downloaded.pdf
    header('Content-Disposition: attachment;  filename="MessagesList.csv"');
    // The PDF source is in original.pdf
    header("Content-Transfer-Encoding: binary\n");
    echo $csvData;
    die;     
?>