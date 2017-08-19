<?php 
//This file is used as csv version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/FeedBackManager.inc.php");
    $feedBackLabelManager = FeedBackManager::getInstance();

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

        if(strtolower(trim($REQUEST_DATA['searchbox']))=='general feedback') {
           $type=1;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='teacher feedback') {
           $type=2;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='general') {
           $type=1;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='teacher') {
           $type=2;
       }
       else {
           $type=-1;
       }
       if(strtolower(trim($REQUEST_DATA['searchbox']))=='yes') {
           $activeType=1;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='no') {
           $activeType=0;
       }
       else {
           $activeType=-1;
       }
            
     $filter = ' AND (ffl.feedbackSurveyLabel LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ffl.surveyType LIKE "%'.$type.'%" OR ffl.noAttempts LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ffl.isActive LIKE "%'.$activeType.'%")';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'feedbackSurveyLabel';
    
    $orderBy = " ffl.$sortField $sortOrderBy"; 


    $recordArray = $feedBackLabelManager->getFeedBackLabelList($filter,$orderBy,'');

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $recordArray[$i]['visibleFrom'] =UtilityManager::formatDate($recordArray[$i]['visibleFrom'] );
        $recordArray[$i]['visibleTo'] =UtilityManager::formatDate($recordArray[$i]['visibleTo'] );
        
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$csvData = '';
    $csvData .= "Sr, Name, Survey Type, From, To, No. of Attempts , Active \n";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].', '.parseCSVComments($record['feedbackSurveyLabel']).', '.parseCSVComments($record['surveyType']).', '.parseCSVComments($record['visibleFrom']).', '.$record['visibleTo'].','.$record['noAttempts'].','.$record['isActive'];
		$csvData .= "\n";
	}
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="feedBackLabelReport.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: feedBackLabelCSV.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 25/07/09   Time: 13:13
//Created in $/LeapCC/Templates/FeedBack
//Done Bug Fixing.
//Bug ids---0000680 to 0000688,0000690 to 0000696
?>