<?php

	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','MarksDistribution');
	define('ACCESS','view');
	define('MANAGEMENT_ACCESS',1);
	UtilityManager::ifNotLoggedIn();
	UtilityManager::headerNoCache();

	$reportManager = StudentReportsManager::getInstance();

    $page = $REQUEST_DATA['page'];
    $sortOrderBy = $REQUEST_DATA['sortOrderBy'];
    $sortField = $REQUEST_DATA['sortField'];
    $degree = $REQUEST_DATA['degree'];
    $subjectTypeId = $REQUEST_DATA['subjectTypeId'];
    $subjectId = $REQUEST_DATA['subjectId'];
	$sortField = $sortField == 'subjectCode' ? " subjectCode $sortOrderBy, conductingAuthority, testTypeName " : " conductingAuthority $sortOrderBy, subjectCode, testTypeName ";


    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;


	if ($subjectId == 'all') {
		$limit = '';
        $condition = " AND (a.hasMarks=1 OR a.hasAttendance=1) ";
		$totalRecordsArray = $reportManager->getMarksDistributionAllSubjects($degree, $subjectTypeId, $sortField, $limit,$condition);
		$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
		$subjectsArray = $reportManager->getMarksDistributionAllSubjects($degree, $subjectTypeId, $sortField, $limit,$condition);
	}
	else {
		$limit = '';
        $condition = " AND (a.hasMarks=1 OR a.hasAttendance=1) ";   
		$totalRecordsArray = $reportManager->getMarksDistributionOneSubject($degree, $subjectTypeId, $subjectId, $sortField, $limit, $condition);
		$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
		$subjectsArray = $reportManager->getMarksDistributionOneSubject($degree, $subjectTypeId, $subjectId, $sortField, $limit, $condition);
	}

	//echo $sortField;
	/*
	echo '<pre>';
	print_r($subjectsArray);
	echo '</pre>';
	*/
	//die;


	$cnt = count($subjectsArray);

    $cnt1=count($totalRecordsArray);


	$oldValue = '';
	$newValue = '';


    for($i=0;$i<$cnt;$i++) {
		$newValue = $subjectsArray[$i]['conductingAuthority2'] . $subjectsArray[$i]['subjectCode'];
        // add stateId in actionId to populate edit/delete icons in User Interface   

		if ($newValue === $oldValue) {
			$subjectsArray[$i]['conductingAuthority2'] = '';
			$subjectsArray[$i]['subjectCode'] = '';
		}

		$valueArray = array_merge(array('srNo' => ($records+$i+1) ),$subjectsArray[$i]);
		/*
		echo '<pre>';
		print_r($valueArray);
		echo '</pre>';
		*/


       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
		$oldValue = $newValue;
    }
    echo '{"sortOrderBy":"'.$REQUEST_DATA['sortOrderBy'].'","sortField":"'.$REQUEST_DATA['sortField'].'","totalRecords":"'.$cnt1.'","page":"'.$page.'","info" : ['.$json_val.']}'; 

// $History : initMarksDistributionReport.php $

?>