<?php
//-------------------------------------------------------
// Purpose: conatins logic of classes which can be promoted
//
// Author : Ajinder Singh
// Created on : (28-jan-2010)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentManager = StudentManager::getInstance();

$activeClassArray  = $studentManager->getActiveClasses();
$validClassesArray = array();

$pickActiveNonMarksTransferredClasses = true;

foreach($activeClassArray as $activeClassRecord) {
	$classId = $activeClassRecord['classId'];
	$className = $activeClassRecord['className'];
	$marksTransferred = $activeClassRecord['marksTransferred'];
	if($marksTransferred == 1 or $pickActiveNonMarksTransferredClasses == true) {
		$validClassesArray[] = Array('classId'=>$classId, 'className'=>$className);
	}
	else {
		$prevClassMarksTransferredArray = $studentManager->getPrevClass($classId);
		$prevClassMT = $prevClassMarksTransferredArray[0]['marksTransferred'];
		$prevClassId = $prevClassMarksTransferredArray[0]['classId'];
		$prevClassName = $prevClassMarksTransferredArray[0]['className'];
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
}
// $History: getClassesReadyForPromotion.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 1/29/10    Time: 3:41p
//Created in $/LeapCC/Library/PromoteStudentsAdvanced
//file added for new interface of session end activities
//





?>