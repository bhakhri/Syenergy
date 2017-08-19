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
    require_once(MODEL_PATH . "/FineManager.inc.php");
    $fineCategoryManager = FineManager::getInstance();

define('MODULE','COMMON');
define('ACCESS','view');
global $sessionHandler; 
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
  UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();

    
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
       $filter =' HAVING fineCategoryAbbrs LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR userNames LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR roleNames LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%"';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'roleName';
    
   if($REQUEST_DATA['sortField']=='userNames'){
        $sortF='userName';
    }
    elseif($REQUEST_DATA['sortField']=='roleNames'){
        $sortF='roleName';
    }
    elseif($REQUEST_DATA['sortField']=='fineCategoryAbbrs'){
        $sortF='fineCategoryAbbr';
    }
    else{
        $sortF='roleName';
    }
    $orderBy = " $sortF $sortOrderBy";  


    $recordArray = $fineCategoryManager->getMappedFineList($filter,$orderBy,'');

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

	$csvData = '';
    $csvData .= "Sr, Role, Fines to be Taken, Approver \n";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].', '.parseCSVComments($record['roleNames']).','.parseCSVComments($record['fineCategoryAbbrs']).','.parseCSVComments($record['userNames']);
		$csvData .= "\n";
	}
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="assignFinetoRoleReport.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;    

// $History: assignFineToRoleCSV.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 26/11/09   Time: 11:01
//Updated in $/LeapCC/Templates/Fine
//Done bug fixing.
//Bug ids---
//0002154,0002146
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 27/07/09   Time: 16:05
//Created in $/LeapCC/Templates/Fine
//Done bug fixing.
//bug ids---0000697 to 0000702
?>