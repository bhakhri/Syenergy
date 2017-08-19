<?php

//The file contains data base functions for marks
//
// Author :Jaineesh
// Created on : 03.11.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    
	global $FE;
    require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','DeletedStudentReport'); 
    define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
	UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/StudentManager.inc.php");
	$studentManager = StudentManager::getInstance();

	
	// to limit records per page    
		$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
		$records    = ($page-1)* RECORDS_PER_PAGE;
		$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
	$sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
	
	$orderBy = " $sortField $sortOrderBy";

    $studentId= $REQUEST_DATA['studentId'];
	
	$finalResultArray = $studentManager->getDeleteStudentFinalReportDetail($studentId,$orderBy);
	$cnt = count($finalResultArray);
	//echo($count);
	
	//$studentRecordArray = $studentInformationManager->getStudentGroup($studentId,$classId,$limit,$orderBy);

	//$cnt = count($studentRecordArray);

	for($i=0;$i<$cnt;$i++) {
		
		$finalResultArray[$i]['maxMarks'] = "".ROUND($finalResultArray[$i]['maxMarks'],2)."";
		$finalResultArray[$i]['marksScored'] = "".ROUND($finalResultArray[$i]['marksScored'],2)."";

		$valueArray = array_merge(
			array(
						'srNo' => ($records+$i+1)), 
						$finalResultArray[$i]
				 );

		 if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }

    //print_r($valueArray);
   echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$count.'","page":"'.$page.'","info" : ['.$json_val.']}';
 
?>

<?php 

//$History: $
//
//
?>