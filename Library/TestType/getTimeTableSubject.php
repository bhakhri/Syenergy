<?php
//-------------------------------------------------------
//  This File is used for fetching marks transferred classes for a time label 
//
//
// Author :Jaineesh
// Created on : 15-11-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','TestTypesMaster');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	$labelId = $REQUEST_DATA['labelId'];
    require_once(MODEL_PATH . "/TestTypeManager.inc.php");
    $subjectTimeTableManager = TestTypeManager::getInstance();
	$classArray = $subjectTimeTableManager->getTimeTableSubjectSelect($labelId);

	echo json_encode($classArray);


// $History: getTimeTableSubject.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/17/09    Time: 4:52p
//Created in $/LeapCC/Library/TestType
//

?>