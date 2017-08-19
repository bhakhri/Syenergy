<?php 
//This file is used as csv version for TestType.
//
// Author :Gurkeerat Sidhu
// Created on : 11.01.2010
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/FeedbackAnswerSetManager.inc.php");
    $feedBackAnswerSetManager = FeedbackAnswerSetManager::getInstance();

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
       $filter = ' AND (answerSetName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'answerSetName';
    
    $orderBy = " $sortField $sortOrderBy"; 


    $recordArray = $feedBackAnswerSetManager->getAnswerSetList('', $filter, $orderBy);

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$csvData = '';
    $csvData .= "#,Name\n";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].', '.parseCSVComments($record['answerSetName']);
		$csvData .= "\n";
	}
	 if($cnt==0){
       $csvData .=",".NO_DATA_FOUND;
    }
	
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="feedBackAdvAnswerSetReport.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: feedBackAdvAnswerSetCSV.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 1/20/10    Time: 5:05p
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Resolved issues:0002615,0002635,0002600,0002601,0002614
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 1/12/10    Time: 6:15p
//Created in $/LeapCC/Templates/FeedbackAdvanced
//Created file under Feedback Advanced AnswerSet module
//

?>