<?php 
//This file is used as csv version for TestType.
//
// Author :Gurkeerat Sidhu
// Created on : 11.01.2010
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/FeedbackLabelManager.inc.php");
    $feedBackLabelManager = FeedbackLabelManager::getInstance();

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

       
       if(strtolower(trim($REQUEST_DATA['searchbox']))=='yes') {
           $activeType=1;
       }
       elseif(strtolower(trim($REQUEST_DATA['searchbox']))=='no') {
           $activeType=0;
       }
       else {
           $activeType=-1;
       }
            
     $filter = ' AND (ffl.feedbackSurveyLabel LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR  ffl.noOfAttempts LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ffl.isActive LIKE "%'.$activeType.'%")';
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
        if($recordArray[$i]['isActive']==1){
           $recordArray[$i]['isActive']='Yes'; 
        }
        else{
            $recordArray[$i]['isActive']='No';
        }
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$csvData = '';
    $csvData .= "#,Name,From,To,No. of Attempts,Active\n";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].','.parseCSVComments($record['feedbackSurveyLabel']).', '.parseCSVComments($record['visibleFrom']).', '.$record['visibleTo'].','.$record['noOfAttempts'].','.$record['isActive'];
		$csvData .= "\n";
	}
	if($cnt==0){
       $csvData .=",".NO_DATA_FOUND;
    }
	
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="feedBackAdvancedLabelReport.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
 

// $History: feedBackAdvLabelCSV.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 18/02/10   Time: 15:50
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Done bug fixing.
//Bug ids---
//0002895,0002896,0002894,0002892,
//0002891,0002882,0002833
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/01/10   Time: 16:03
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Modified "Feedback Label Master(Advanced)" as two new fields are added
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 1/12/10    Time: 1:11p
//Created in $/LeapCC/Templates/FeedbackAdvanced
//created file under feedback advanced label module
//

?>