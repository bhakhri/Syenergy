<?php
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','ChangeTestCategory');
	define('ACCESS','edit');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/StudentManager.inc.php");
	$studentManager = StudentManager::getInstance();

	$classId = $REQUEST_DATA['degree']; //1
	$subjectId = $REQUEST_DATA['subjectId']; //8
	$groupId = $REQUEST_DATA['groupId']; //3

	if (empty($classId) or empty($subjectId) or empty($groupId)) {
		echo REQUIRED_PARAMETERS_MISSING;
		die;
	}

	$allDetailsArray = array();

	$recordArray = $studentManager->getTests($classId, $subjectId, $groupId);

	$cnt = count($recordArray);


    for($i=0;$i<count($recordArray);$i++) {
		$testId = $recordArray[$i]['testId'];
		$recordArray[$i]['action2'] = '<input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="'.IMG_HTTP_PATH.'/edit2.gif" onClick="getTestDetails('.$testId.');" />';
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