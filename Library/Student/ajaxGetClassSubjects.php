<?php
//-------------------------------------------------------
// THIS FILE IS USED TO upload student photo for subject centric
//
// Author : Ajinder Singh
// Created on : 02-May-2009
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//                      
//--------------------------------------------------------
	global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','UploadExternalMarks');
	define('ACCESS','add');
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();
	$classId = $REQUEST_DATA['class1'];
	require_once(MODEL_PATH . "/StudentManager.inc.php");
	$studentManager = StudentManager::getInstance();
	$subjectsArray = $studentManager->getClassSubjects($classId);
	$resultArray = array('subjects'=>$subjectsArray);
	echo json_encode($resultArray);

// $History: ajaxGetClassSubjects.php $
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 5/02/09    Time: 7:23p
//Updated in $/LeapCC/Library/Student
//updated access defines.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 5/02/09    Time: 7:14p
//Created in $/LeapCC/Library/Student
//file added for marks upload.
//



?>