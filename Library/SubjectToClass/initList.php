<?php
//-------------------------------------------------------
// Purpose: To store the records of subject to class in array from the database functionality
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    require_once(MODEL_PATH . "/SubjectToClassManager.inc.php");
    $subjecttoclassManager = SubjectToClassManager::getInstance();

    if(UtilityManager::notEmpty($REQUEST_DATA['listSubject'])) 
	{
		$concatDegreeId = $REQUEST_DATA['degree'];
		$concatArr		= explode("-", $concatDegreeId);
		$universityID	= $concatArr[0];
		$degreeID		= $concatArr[1];
		$branchID		= $concatArr[2];
		$batchId		= $REQUEST_DATA['batch'];
		$studyperiodId	= $REQUEST_DATA['studyperiod'];
		 
		$classIDArr = $subjecttoclassManager->getClass($universityID,$degreeID,$branchID,$batchId,$studyperiodId);
		 
		if(count($classIDArr))
		{
			$classId		= $classIDArr[0][classId];
			$className		= $classIDArr[0][className];
			$filter			= " AND subtocls.classId=".$classId;
		    $subjecttoclassRecordArray = $subjecttoclassManager->getSubToClassList($filter,$limit);
		   //$subjecttoclassManager->insertSubToClass($classId);
		}
    }
	// $History: initList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/SubjectToClass
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 9/11/08    Time: 5:38p
//Updated in $/Leap/Source/Library/SubjectToClass
//updated formatting and added comments
?>