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
    
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();
    

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

    $search=trim($REQUEST_DATA['search']);
    if($search!='') {
       $isVisible=-1;
       if(strtoupper($search)=='YES'){
           $isVisible=1;
       }
       else if(strtoupper($search)=='NO'){
           $isVisible=0;
       }
       else{
           $isVisible=-1;
       }
       $search=add_slashes($search);
       $filter = ' HAVING ( aa.topicTitle LIKE "%'.$search.'%" OR aa.topicDescription LIKE "%'.$search.'%" OR totalAssignment LIKE "%'.$search.'%" OR isVisible2 LIKE "'.$isVisible.'%" )';         
    }

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'assignedOn';

    $orderBy=" $sortField $sortOrderBy"; 


    $recordArray =$teacherManager->getTeacherAssignmentList($filter,'');

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
       $recordArray[$i]['assignedOn'] = UtilityManager::formatDate($recordArray[$i]['assignedOn']);
       $recordArray[$i]['tobeSubmittedOn'] = UtilityManager::formatDate($recordArray[$i]['tobeSubmittedOn']);
       $recordArray[$i]['addedOn'] = UtilityManager::formatDate($recordArray[$i]['addedOn']);
       $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
    }

	$csvData = "SearchBy : ".$search."\n";
    $csvData .= "#, Topic, Description, Assigned, Due Date, Added , Total, Visible \n";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].','.parseCSVComments($record['topicTitle']).','.parseCSVComments($record['topicDescription']).','.parseCSVComments($record['assignedOn']).', '.parseCSVComments($record['tobeSubmittedOn']).','.$record['addedOn'].','.$record['totalAssignment'].','.$record['isVisible'];
		$csvData .= "\n";
	}
    if($cnt==0){
        $csvData .=",".NO_DATA_FOUND;
    }
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="allocateAssignmentReport.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    
// $History: testTypeCSV.php $
?>