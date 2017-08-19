<?php
//-------------------------------------------------------
// Purpose: To store the records of Attendance in array from the database functionality
//
// Author : Jaineesh
// Created on : (06.05.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','StudentClassRollNo');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

	
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentClassManager  = StudentManager::getInstance();
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
	$orderBy = " $sortField $sortOrderBy";
	
	$rollNo	= $REQUEST_DATA['rollNo'];
	$criteria	= $REQUEST_DATA['criteria'];
	
	if($criteria == 1) {
		$conditions = 	"AND scs.rollNo='".$rollNo."'";
	}
	else if ($criteria == 2) {
		$conditions = 	"AND scs.universityRollNo='".$rollNo."'";
	}
	else if ($criteria == 3) {
		$conditions = 	"AND scs.regNo='".$rollNo."'";
	}
	
	//$conditions = "(AND scs.rollNo='".$rollNo."' OR scs.universityRollNo='".$rollNo."' OR scs.regNo='".$rollNo."')";
    $studentCurrentClassArray = $studentClassManager->getStudentCurrentClass($conditions);
	
	$cnt=count($studentCurrentClassArray);
	if (is_array($studentCurrentClassArray) && count($studentCurrentClassArray)>0) {
		echo json_encode($studentCurrentClassArray[0]);
	}
	else {
		echo "0~$criteria"; 
	}
	
	
// for VSS
// $History: ajaxUpdateStudentClass.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/14/09    Time: 5:35p
//Created in $/LeapCC/Library/UpdateStudentClass
//new files copy from sc
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/25/09    Time: 12:17p
//Created in $/Leap/Source/Library/UpdateStudentClass
//new ajax file to update student class/rollno
//
?>