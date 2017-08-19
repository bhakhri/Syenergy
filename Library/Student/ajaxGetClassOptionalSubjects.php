<?php
//-------------------------------------------------------
// Purpose: To count the students in class
//
// Author : Ajinder Singh
// Created on : (11-June-2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','AssignOptionalSubjects');
	define('ACCESS','add');
	UtilityManager::ifNotLoggedIn();
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");

	$classId = $REQUEST_DATA['degree'];
	if ($classId == '') {
		echo json_encode(array());
		die;
	}
	
	$subjectsArray = CommonQueryManager::getInstance()->getClassSubject(' sub.subjectName'," WHERE classId=$classId AND sub.subjectId = subTocls.subjectId AND subTocls.optional=1 AND subTocls.offered=1 ");
	echo json_encode($subjectsArray);


// for VSS
// $History: ajaxGetClassOptionalSubjects.php $
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 11/20/09   Time: 5:45p
//Updated in $/LeapCC/Library/Student
//fixed query error: FCNS No. 835 
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 6/11/09    Time: 12:47p
//Updated in $/LeapCC/Library/Student
//added access defines.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 6/11/09    Time: 10:48a
//Created in $/LeapCC/Library/Student
//file added for assigning optional subject to students
//


?>