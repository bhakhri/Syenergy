<?php 
//This file is used as csv version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");

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

    require_once(MODEL_PATH . "/FeedBackQuestionSetAdvancedManager.inc.php");
    $fbMgr = FeedBackQuestionSetAdvancedManager::getInstance();
    //////
    
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $search=add_slashes(trim($REQUEST_DATA['searchbox']));
       $filter = '  AND ( qs.feedbackQuestionSetName LIKE "'.$search.'%" )';
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'feedbackQuestionSetName';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $fbRecordArray = $fbMgr->getFeedbackQuestionSetList($filter,' ',$orderBy);
    $cnt = count($fbRecordArray);
    
    $valueArray=array();
    for($i=0;$i<$cnt;$i++) {
       $valueArray[] = array_merge(array('actionString' => $actionString , 'srNo' => ($records+$i+1) ),$fbRecordArray[$i]);
    }

	$csvData = '';
    $csvData .= "#,Question Set\n";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].','.parseCSVComments($record['feedbackQuestionSetName']);
		$csvData .= "\n";
	}
	 if($cnt==0){
       $csvData .=",".NO_DATA_FOUND;
    }
	
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="questionSetReport.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
// $History: questionSetCSV.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 19/02/10   Time: 14:22
//Created in $/LeapCC/Templates/FeedbackAdvanced
//Done Bug fixing.
//Bug ids---
//0002910,0002909,0002907,
//0002906,0002904,0002908,
//0002905
?>