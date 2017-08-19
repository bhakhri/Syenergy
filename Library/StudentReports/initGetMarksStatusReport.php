<?php
//--------------------------------------------------------
//This file returns the array of attendance missed records
//
// Author :Ajinder Singh
// Created on : 15-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','MarksStatusReport');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	$studentReportsManager = StudentReportsManager::getInstance();
	 
	
	$classId = $REQUEST_DATA['degree'];
	$subjectId = $REQUEST_DATA['subjectId'];
	$sortOrderBy = $REQUEST_DATA['sortOrderBy'];
	$sortField = $REQUEST_DATA['sortField'];
	$labelId = $REQUEST_DATA['labelId'];

    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'userName';
    
    $orderBy = " $sortField $sortOrderBy";         



	$conditions = '';
	if ($classId != 'all') {
		$conditions = " AND ttc.classId = $classId";
		if ($subjectId != 'all') {
			$conditions .= " AND t.subjectId = $subjectId";
		}
	}
	$totalRecordsArray =  $studentReportsManager->getMarksStatusReport($labelId, $sortField, $sortOrderBy, $conditions, '');  
	$recordArray = $studentReportsManager->getMarksStatusReport($labelId, $sortField, $sortOrderBy, $conditions, $limit);
	$cnt = count($totalRecordsArray);
	//die;



    for($i=0;$i<count($recordArray);$i++) {
        $valueArray = array_merge(array('srNo' => ($records+$i+1)),$recordArray[$i]);
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$REQUEST_DATA['sortOrderBy'].'","sortField":"'.$REQUEST_DATA['sortField'].'","totalRecords":"'.$cnt.'","page":"'.$page.'","info" : ['.$json_val.']}'; 

?>