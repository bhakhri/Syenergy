<?php
//--------------------------------------------------------------------------------------------------------------
// Purpose: To show data in array from the database, pagination 
//
// Author : Jaineesh
// Created on : (15.05.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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


	/////////////////////////
    
	$timeTableLabelId = $REQUEST_DATA['labelId'];
	$classId = $REQUEST_DATA['degree'];
	
	// to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
    $orderBy = " $sortField $sortOrderBy";

    ////////////

	$freeClassGroupArray = array();

	$countWithoutSubjectArray = $studentManager->getCountWithoutOptionalSubject($classId);
	$cnt = COUNT($countWithoutSubjectArray);

	$withoutOptionalSubjectArray = $studentManager->getWithoutOptionalSubject($classId,$limit,$orderBy);
	$cntWihoutOptionalSubject = count($withoutOptionalSubjectArray);

	if($cntWihoutOptionalSubject > 0 and is_array($withoutOptionalSubjectArray)) {
		//echo($recordCount);
		for($s=0;$s<$cntWihoutOptionalSubject;$s++) {

			$valueArray = array_merge(array('srNo' => ($records+$s+1)),$withoutOptionalSubjectArray[$s]);

			if(trim($json_val)=='') {
				$json_val = json_encode($valueArray);
			}
			else {
				$json_val .= ','.json_encode($valueArray);           
			}
		}
	}
	

   echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cnt.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
	
    
// for VSS
// $History: listWithoutOptionalSubject.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/17/10    Time: 4:44p
//Created in $/LeapCC/Library/StudentReports
//new file for optional subject report
//
?>