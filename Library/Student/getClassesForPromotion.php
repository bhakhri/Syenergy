<?php
//-------------------------------------------------------
//  This File contains showing section assignment students
//
//
// Author :Ajinder Singh
// Created on : 29-05-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PromoteStudents');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentManager = StudentManager::getInstance();
$activeClassArray  = $studentManager->getActiveClasses();



$validClassesArray = array();

foreach($activeClassArray as $activeClassRecord) {
	$classId = $activeClassRecord['classId'];
	$studentCount = $activeClassRecord['studentCount'];
	$nextClassIdArray = $studentManager->getNextClassId($classId);
	$nextClassName = $nextClassIdArray[0]['className'];
	if (is_null($nextClassName)) {
		$nextClassName = 'Alumni Class, and will become Alumni';
	}
	$className = $activeClassRecord['className'];
	$marksTransferred = $activeClassRecord['marksTransferred'];
	if (is_null($marksTransferred)) {
		$marksTransferred = 0;
	}
	$validClassesArray[] = Array('classId'=>$classId, 'className'=>$className, 'marksTransferred'=>$marksTransferred, 'studentCount' => $studentCount, 'nextClassName' => $nextClassName);
	/*
	THIS LOGIC STOPPED BECAUSE THEY WANT ONLY ACTIVE CLASSES IN CURRENT SESSION
	$marksTransferred = $activeClassRecord['marksTransferred'];
	if($marksTransferred == 1) {
		$validClassesArray[] = Array('classId'=>$classId, 'className'=>$className);
	}
	else {
		$prevClassMarksTransferredArray = $studentManager->getPrevClass($classId);
		$prevClassMT = $prevClassMarksTransferredArray[0]['marksTransferred'];
		$prevClassId = $prevClassMarksTransferredArray[0]['classId'];
		if ($prevClassId) {
			$prevClassMT = $prevClassMarksTransferredArray[0]['marksTransferred'];
			if ($prevClassMT == 1) {
				$validClassesArray[] = Array('classId'=>$classId, 'className'=>$className);
			}
		}
		else {
			$validClassesArray[] = Array('classId'=>$classId, 'className'=>$className);
		}
	}
	*/
}

echo json_encode($validClassesArray);


// $History: getClassesForPromotion.php $
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 11/03/09   Time: 2:09p
//Updated in $/LeapCC/Library/Student
//done changes to show first tri classes.
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 7/25/09    Time: 12:55p
//Updated in $/LeapCC/Library/Student
//code updated to show classes for which marks have not been transferred.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 5/29/09    Time: 11:36a
//Created in $/LeapCC/Library/Student
//file added for student promotion.
//




?>