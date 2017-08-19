 <?php 
//This file is used as printing version for display Designation
//
// Author :Jaineesh
// Created on : 04.08.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','student info');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

	// CSV data field Comments added 
    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
           return '"'.$comments.'"'; 
         } 
         else {
             return $comments.chr(160); 
         }
    }
    
    /// Search filter /////  
   if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) { 
       $filter = ' AND ( description LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR resourceUrl LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR resourceName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'postedDate';
    
    $orderBy = " $sortField $sortOrderBy";

     
    $resourceRecordArray = $studentManager->getStudentCourseResourceList($studentId,$classId,$filter,$orderBy,' ');
    $cnt = count($resourceRecordArray);
     $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface   
		$resourceRecordArray[$i]['postedDate'] = UtilityManager::formatDate($resourceRecordArray[$i]['postedDate']);
        $valueArray[] = array_merge(array('srNo'=>($i+1)),$resourceRecordArray[$i]);
    }
    $search = add_slashes(trim($REQUEST_DATA['searchbox']));    
    
    $csvData ='';
    $csvData ="Search By,".parseCSVComments($search)."\n";
    $csvData.="Sr No.,Subject Code,Descriptiom,Type,Date,Link,Creator";
    $csvData .="\n";
    
    for($i=0;$i<$cnt;$i++) {  
		  $csvData .= ($i+1).",";
		  //$csvData .= parseCSVComments($resourceRecordArray[$i]['srNo']).",";
		  $resourceRecordArray[$i]['resourceUrl']=strip_slashes($resourceRecordArray[$i]['resourceUrl'])==-1 ? NOT_APPLICABLE_STRING:$resourceRecordArray[$i]['resourceUrl'];
		  $csvData .= parseCSVComments($resourceRecordArray[$i]['subject']).",";
		  $csvData .= parseCSVComments($resourceRecordArray[$i]['description']).",";
		  $csvData .= parseCSVComments($resourceRecordArray[$i]['resourceName']).",";
		  $csvData .= parseCSVComments($resourceRecordArray[$i]['postedDate']).",";
		  $csvData .= parseCSVComments($resourceRecordArray[$i]['resourceUrl']).",";
		  $csvData .= parseCSVComments($resourceRecordArray[$i]['employeeName'])."\n";
		  
    }

    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment; filename="ResourceReport.csv"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>