<?php
//-------------------------------------------------------
//  This File is used for fetching marks transferred classes for a time label 
//
//
// Author :Ajinder Singh
// Created on : 16-feb-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','CgpaCalculation');
	define('ACCESS','add');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	$labelId = $REQUEST_DATA['labelId'];
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportsManager = StudentReportsManager::getInstance();
	$classArray = $studentReportsManager->getMarksTotalClasses($labelId);
	echo json_encode($classArray);

// $History: scGetMarksTotalClasses.php $
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 2/16/09    Time: 4:58p
//Updated in $/Leap/Source/Library/ScStudent
//updated access defines
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 2/16/09    Time: 4:47p
//Created in $/Leap/Source/Library/ScStudent
//file added for cgpa calculation
//



?>