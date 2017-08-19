<?php
//-------------------------------------------------------
// Purpose: To store the records of student attendance from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : 12-11-2008
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifStudentNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
    $studentManager = StudentInformationManager::getInstance();
    
	require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
	$studentManager = StudentInformationManager::getInstance();
	global $sessionHandler;
	$currentClassId=$sessionHandler->getSessionVariable('ClassId');
	$classIdArray = $studentManager -> getPrevClass($currentClassId);
	$classId = $classIdArray[0]['classId'];


// $History: studentFeedBack.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 5/27/09    Time: 11:02a
//Updated in $/LeapCC/Library/Student
//copy from sc and modifications in the files as per requirement of CC
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/09/08   Time: 5:31p
//Updated in $/LeapCC/Library/Student
//modification in code for cc
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/24/08   Time: 10:47a
//Created in $/Leap/Source/Library/ScStudent
//file to get student feedback
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 11/12/08   Time: 1:50p
//Created in $/Leap/Source/Library/ScStudent
//intial checkin
?>