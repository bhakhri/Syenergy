<?php 
//This file is used as csv version for TestType.
//
// Author :Gurkeerat Sidhu
// Created on : 24.10.2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/FeedbackOptionsManager.inc.php");
    $feedbackOptionsManager = FeedbackOptionsManager::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    $conditionsArray = array();
    $qryString = "";
    

//to parse csv values    
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

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (faso.optionLabel LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR faso.optionPoints LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR fas.answerSetName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR faso.printOrder LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'answerSetName';
    
    $orderBy = "  $sortField $sortOrderBy";


    $recordArray = $feedbackOptionsManager->getFeedBackOptionsList($filter,$orderBy,'');

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$csvData = '';
    $csvData .= "#, Answer Set , Option Text, Option Weight,Print Order \n";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].','.parseCSVComments($record['answerSetName']).','.parseCSVComments($record['optionLabel']).','.parseCSVComments($record['optionPoints']).','.parseCSVComments($record['printOrder']);
		$csvData .= "\n";
	}
	 if($cnt==0){
       $csvData .=",".NO_DATA_FOUND;
    }
	
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="feedBackAdvOptionsReport.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: feedbackAdvOptionsCSV.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 25/01/10   Time: 15:52
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Made UI related changes as instructed by sachin sir
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 1/14/10    Time: 6:22p
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Resolved issue: 0002609,0002607,0002608,0002610,0002611,
//0002612,0002613
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 1/12/10    Time: 5:20p
//Created in $/LeapCC/Templates/FeedbackAdvanced
//Created file under Feedback Advanced Answer Set Options Module
//

?>