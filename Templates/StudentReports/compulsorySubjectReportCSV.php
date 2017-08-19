<?php
//--------------------------------------------------------------------------------------------------------------
// Purpose: To show data in array from the database, pagination 
//
// Author : Jaineesh
// Created on : (15.05.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','OptionalGroupReport');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true); 
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
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

	/////////////////////////
    
	$timeTableLabelId = $REQUEST_DATA['labelId'];
	$classId = $REQUEST_DATA['degree'];
	
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
    $orderBy = " $sortField $sortOrderBy";

    ////////////

	$freeClassGroupArray = array();

	$withoutOptionalSubjectArray = $studentManager->getWithoutOptionalSubject($classId,'',$orderBy);
	$cntWihoutOptionalSubject = count($withoutOptionalSubjectArray);
	  $csvData ='';
    $csvData.="#,Student Name,University Roll No.,Roll No.,Group";
    $csvData .="\n";
    
    for($i=0;$i<$cntWihoutOptionalSubject;$i++) {  
		  $csvData .= ($i+1).",";
		  $csvData .= parseCSVComments($withoutOptionalSubjectArray[$i]['studentName']).",";
		  $csvData .= parseCSVComments($withoutOptionalSubjectArray[$i]['universityRollNo']).",";
		    $csvData .= parseCSVComments($withoutOptionalSubjectArray[$i]['rollNo']).",";
			$csvData .= parseCSVComments($withoutOptionalSubjectArray[$i]['groupName'])."\n";
    }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment; filename="CompulsorySubjectReport.csv"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>

