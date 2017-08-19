<?php
//--------------------------------------------------------
//This file returns the array of class, based on class and subjectType
//
// Author :Rajeev Aggarwal
// Created on : 22-04-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','Admit');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
	UtilityManager::headerNoCache();
	require_once(MODEL_PATH . "/StudentManager.inc.php");
	$studentManager = StudentManager::getInstance();

	$instituteId = $REQUEST_DATA['instituteId'];
	$condition = ' AND 	instituteId='.$instituteId;
	$groupsArray = $studentManager->getInstituteClass($condition);
	echo json_encode($groupsArray);
	 
// $History: initGetInstituteClass.php $
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 09-11-04   Time: 10:54a
//Updated in $/LeapCC/Library/Student
//Updated with utility manager file which was not included earlier
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/21/09    Time: 5:19p
//Updated in $/LeapCC/Library/Student
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/27/09    Time: 7:15p
//Created in $/LeapCC/Library/Student
//intial checkin
?>