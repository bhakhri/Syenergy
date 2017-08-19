<?php
//--------------------------------------------------------
//This file returns the array of class, based on class and subjectType
//
// Author :Ajinder Singh
// Created on : 22-04-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
	require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$studentReportsManager = StudentReportsManager::getInstance();

	$timeTable = $REQUEST_DATA['timeTable'];

    if($timeTable=='') {
      $timeTable = 0;  
    } 
    
	$classArray = $studentReportsManager->getLabelMarksTransferredClass($timeTable);
	echo json_encode($classArray);

// $History: initGetLabelFoxproClass.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/05/10    Time: 1:03p
//Created in $/LeapCC/Library/StudentReports
//initial checkin



?>