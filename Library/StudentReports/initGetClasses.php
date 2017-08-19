<?php
//-------------------------------------------------------
//  This File is used for fetching class for 
//
//
// Author :Jaineesh
// Created on : 07.08.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    
	$labelId = trim($REQUEST_DATA['labelId']);
    
    if($labelId=='') {
      $labelId=-1; 
    }
    
    $studentReportsManager = StudentReportsManager::getInstance();
    $classArray = $studentReportsManager->getSelectedTimeTableClasses($labelId);
    
	echo json_encode($classArray);

// $History: initGetClasses.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 10/12/09   Time: 10:35a
//Updated in $/LeapCC/Library/StudentReports
//done changes to fix bugs:
//0001740, 0001738, 0001737, 0001736, 0001735, 0001728	
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 8/25/09    Time: 5:28p
//Created in $/LeapCC/Library/StudentReports
//new ajax file to get classes
//
//
?>