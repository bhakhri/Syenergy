<?php
//This file is used as printing version for subject details
// Author :cheena
// Created on : 01-08-2011
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','SubjectCategory');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);

    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/SubjectCategoryManager.inc.php");
    $subjectCategoryManager =  SubjectCategoryManager::getInstance();

 
    //to parse csv values
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

    $subjectCategoryId = $REQUEST_DATA['categoryId'];
    $condition = "subjectCategoryId = '".$subjectCategoryId."'";
	
    $subjectRecordArray = $subjectCategoryManager->getSubjectListNew($condition,'');
    $cnt = count($subjectRecordArray);
 $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$subjectRecordArray[$i]);
   }


    $csvData = '';
    $csvData .= "#, Subject Name, Subject Code,Subject Type,Attendance,Marks \n";
    foreach($valueArray as $record) {
         $csvData .= ($i+1).','.parseCSVComments($record['subjectName']).','.parseCSVComments($record['subjectCode']).','.parseCSVComments($record['subjectTypeName']).','.parseCSVComments($record['hasAttendance']).','.parseCSVComments($record['hasMarks']);
		$csvData .= "\n";
    }
    
    if($i==0) {
       $csvData .= " ,".NO_DATA_FOUND."\n"; 
    }

    UtilityManager::makeCSV($csvData,'subjectCategoryDetails.csv');
    die;	
?>
