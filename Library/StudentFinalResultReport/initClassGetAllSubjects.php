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
		//$subjectsArray = $reportManager->getAllSubjectGradeList($classId);
		$subjectsArray = $reportManager->getAlternateSubjectList($classId);
		
		
		$results1Count = count($subjectsArray);
		if(is_array($subjectsArray) && $results1Count>0) {
			$jsonType  = '';
			for($s = 0; $s<$results1Count; $s++) {
               // U have cannot remove this code
               // In chalkpad, some subjects for 2011-CSE-3rd Sem showing wrong mapped subject codes as due to changes in APG, 
               // some codes were also changed. 
               /*
               if(CLIENT_INSTITUTES >=10) { 
                   if($classId=='586') {
                     if($subjectsArray[$i]['subjectId']==1540) {
                       $subjectsArray[$i]['subjectCode'] = 'AML4209';
                     }   
                     else if($subjectsArray[$i]['subjectId']==1543) {
                       $subjectsArray[$i]['subjectCode'] = 'ECP1209';
                     }   
                     else if($subjectsArray[$i]['subjectId']==1538) {
                       $subjectsArray[$i]['subjectCode'] = 'ECL4209';
                     }   
                   }   
               }
			   */
			   $jsonSubject .= json_encode($subjectsArray[$s]). ( $s==($results1Count-1) ? '' : ',' );         
			}
		}
	}

	echo '{"subjectArr":['.$jsonSubject.'],"typeArr":['.$jsonType.'],"groupArr":['.$groupType.']}';

?>