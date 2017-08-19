<?php
	global $FE;
	require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	require_once(MODEL_PATH."/CommonQueryManager.inc.php");  
	$reportManager = StudentReportsManager::getInstance();
	 
	$classId = $REQUEST_DATA['class1'];
	//fetching subject data only if any one class is selected

	$jsonSubject = '';

	if ($classId != '') {
		$subjectsArray = $reportManager->getAllSubjectList($classId);
		$results1Count = count($subjectsArray);
		if(is_array($subjectsArray) && $results1Count>0) {
			$jsonType  = '';
			for($s = 0; $s<$results1Count; $s++) {
				$jsonSubject .= json_encode($subjectsArray[$s]). ( $s==($results1Count-1) ? '' : ',' );         
			}
		}
	}

	echo '{"subjectArr":['.$jsonSubject.'],"typeArr":['.$jsonType.'],"groupArr":['.$groupType.']}';

?>