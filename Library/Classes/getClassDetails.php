<?php
////  This File gets  record from the class Form Table
//
// Author :Ajinder Singh
// Created on : 23-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CreateClass');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$recordsArray = null;
require_once(MODEL_PATH . "/ClassesManager.inc.php");
if (isset($REQUEST_DATA['classId'])) {
	$classId = $REQUEST_DATA['classId'];
	$recordsArray = ClassesManager::getInstance()->getClassStudyPeriods($classId);
}
else if(trim($REQUEST_DATA['degreeDuration']) != '' and trim($REQUEST_DATA['periodicity']) != '') {
    $recordArray = ClassesManager::getInstance()->getPeriodicityFrequency(' WHERE periodicityId='.$REQUEST_DATA['periodicity']);
	$periodicityFrequency = $recordArray[0]['periodicityFrequency'];
	$totalPeriods = $periodicityFrequency * $REQUEST_DATA['degreeDuration'];
	$periodsEnteredArray = ClassesManager::getInstance()->countStudyPeriods($REQUEST_DATA['periodicity']);
	$periodsEntered = $periodsEnteredArray[0]['cnt'];
	
	if ($totalPeriods > $periodsEntered) {
		$recordsArray = array();
	}
	else {
		//fetch first "$totalPeriods" records.
		$recordsArray = ClassesManager::getInstance()->getStudyPeriods($REQUEST_DATA['periodicity'], $totalPeriods);
	}
}
echo json_encode($recordsArray);


// $History: getClassDetails.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Classes
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/10/08   Time: 12:10p
//Updated in $/Leap/Source/Library/Classes
//add define access in module
//
//*****************  Version 1  *****************
//User: Admin        Date: 8/05/08    Time: 6:44p
//Created in $/Leap/Source/Library/Classes
//file added for fetching class details

?>