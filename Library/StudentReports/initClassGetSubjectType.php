<?php
//--------------------------------------------------------
//This file returns the array of subjects and subjectType, based on class
//
// Author :Rajeev Aggarwal
// Created on : 22-04-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

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
    if($classId=='') {
      $classId=0;  
    }

	if ($classId != '') {
		
		//$subjectsArray = $reportManager->getSubjectList($classId);
        //$subjectsArray = $reportManager->getTransferredSubjectList($classId);
        $cond = " AND c.classId = $classId";   
        $filter= " DISTINCT c.classId, su.subjectId, su.subjectName, su.subjectCode ";
        $groupBy = "";
        $orderSubjectBy = " classId, subjectCode";
        $subjectsArray = $reportManager->getAllSubjectAndSubjectTypes($cond, $filter, $groupBy, $orderSubjectBy);
        $results1Count = count($subjectsArray);
		if(is_array($subjectsArray) && $results1Count>0) {
			$jsonType  = '';
			for($s = 0; $s<$results1Count; $s++) {
				
				$jsonSubject .= json_encode($subjectsArray[$s]). ( $s==($results1Count-1) ? '' : ',' );         
			}
		}


		$results2 = CommonQueryManager::getInstance()->getSubjectTypeClass(' st.subjectTypeName'," AND cls.classId='".$classId."'");
		$results2Count = count($results2);
		if(is_array($results2) && $results2Count>0) {
			$jsonType  = '';
			for($s = 0; $s<$results2Count; $s++) {

				$jsonType .= json_encode($results2[$s]). ( $s==($results2Count-1) ? '' : ',' );                }
		}

		$cond = " AND c.classId = $classId";   
		$filter= " DISTINCT c.classId, g.groupId, g.groupName ";
	    $groupBy = "";
		$orderSubjectBy = " classId, groupId, groupName";
	    $groupArray =  $reportManager->getAllSubjectAndSubjectTypes($cond, $filter, $groupBy, $orderSubjectBy);  
		$group2Count = count($groupArray);
		if(is_array($groupArray) && $group2Count>0) {
			$groupType  = '';
			for($s = 0; $s<$group2Count; $s++) {

				$groupType .= json_encode($groupArray[$s]). ( $s==($group2Count-1) ? '' : ',' );                }
		}
		

		echo '{"subjectArr":['.$jsonSubject.'],"typeArr":['.$jsonType.'],"groupArr":['.$groupType.']}';
	 
	}
// $History: initClassGetSubjectType.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/10/09    Time: 5:38p
//Updated in $/LeapCC/Library/StudentReports
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/29/09    Time: 7:11p
//Created in $/LeapCC/Library/StudentReports
//intial checkin
?>