<?php

	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
	UtilityManager::ifTeacherNotLoggedIn(true);
	UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();

    $teacherId = $sessionHandler->getSessionVariable('EmployeeId');
	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
    
     $orderBy = " $sortField $sortOrderBy";

	$activeTimeTableLabelArray = $teacherManager->getActiveTimeTable();
	$activeTimeTableLabelId = $activeTimeTableLabelArray[0]['timeTableLabelId'];
	$teacherSubjectsArray = $teacherManager->getTeacherSubjects($activeTimeTableLabelId);
	$concatStr = '';
	foreach($teacherSubjectsArray as $teacherSubjectRecord) {
		$subjectId = $teacherSubjectRecord['subjectId'];
		$classId = $teacherSubjectRecord['classId'];
		if ($concatStr != '') {
			$concatStr .= ',';
		}
		$concatStr .= "'$subjectId#$classId'";
	}
	$teacherTestsArray = $teacherManager->getTeacherTests($teacherId, $concatStr,$orderBy);

	$cnt = count($teacherTestsArray);

	for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface  
		$teacherTestsArray[$i]['testDate'] = UtilityManager::formatDate($teacherTestsArray[$i]['testDate']);
        $valueArray = array_merge(array('srNo' => ($i+1) ),$teacherTestsArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$REQUEST_DATA['sortOrderBy'].'","sortField":"'.$REQUEST_DATA['sortField'].'","totalRecords":"'.$cnt.'","page":"1","info" : ['.$json_val.']}'; 


// $History: ajaxGetTeacherTests.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 1/08/10    Time: 3:07p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//file added for teacher dashboard enhancements
//




?>