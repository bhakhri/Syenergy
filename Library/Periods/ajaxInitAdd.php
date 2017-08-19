<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD NEW PERIOD
//
//
// Author : Jaineesh
// Created on : (14.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PeriodsMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    $startAmPm = $REQUEST_DATA['startAmPm'];
    $endAmPm = $REQUEST_DATA['endAmPm'];
    $startTime = $REQUEST_DATA['startTime'];
    $endTime = $REQUEST_DATA['endTime'];

	$startHours = intval(substr($startTime,0,2));
	
	$endHours = intval(substr($endTime,0,2));

	if($startHours > 12 or $startHours < 0) {
		echo 'Hours cannot greater than 12 or less than 0';
		die;
	}
	if($endHours > 12 or $endHours < 0) {
		echo 'Hours cannot greater than 12 or less than 0';
		die;
	}
	
	$startMins = intval(substr($startTime,3));
	//echo ($startMins);
	//die;
	$endMins = intval(substr($endTime,3));
	//echo($endMins);
	//die;
	
	if($startAmPm == $endAmPm  and $startAmPm == 'AM') {
		if($startHours > $endHours) {
			echo 'Start Time should not be greater than End Time';
			die;
		}
		elseif($startHours == $endHours and $startMins > $endMins) {
			echo 'Start Time should not be greater than End Time';
			die;
		}
	}
	elseif ($startAmPm == 'AM'  and $endAmPm == 'PM') {
		//everything ok
	}
	elseif ($startAmPm == 'PM' and $endAmPm == 'AM') {
		echo 'Start Time should not be greater than End Time';
		die;
	}
	elseif ($startAmPm == 'PM'  and $endAmPm == 'PM') {
		if($startHours == 12) {
			if($endHours == 12) {
				if($startMins > $endMins) {
					echo 'Start Time should not be greater than End Time';
					die;
				}
			}
			else {
				//ok
			}
		}
		else {
			if($startHours > $endHours) {
				echo 'Start Time should not be greater than End Time';
				die;
			}
			elseif($startHours == $endHours and $startMins > $endMins) {
				echo 'Start Time should not be greater than End Time';
				die;
			}
		}
	}

	$periodStartTime = explode(':',$startTime);

	if(strlen($periodStartTime[0]) == 1){
		$periodEndTime = $periodStartTime[1];
		$periodStartTime = "0".$periodStartTime[0];
		$periodStartTime = $periodStartTime.":".$periodEndTime;
	}
	else {
		$periodStartTime = $startTime;
	}

	$periodSecondTime = explode(':',$endTime);

	if(strlen($periodSecondTime[0]) == 1){
		$periodSecondEndTime = $periodSecondTime[1];
		$periodSecondTime = "0".$periodSecondTime[0];
		$periodSecondTime = $periodSecondTime.":".$periodSecondEndTime;
	}
	else {
		$periodSecondTime = $endTime;
	}

	//$periodStartTime = $startTime;
	//$periodSecondTime = $endTime;

#############	LOGIC FOR TIME BASED CHECKING STARTS :) #########################################

	$date = date('d-m-Y');
	list($newDay,$newMonth,$newYear) = explode('-',$date);
	$startTime			= $REQUEST_DATA['startTime'];
	$endTime			= $REQUEST_DATA['endTime'];
	$periodStartAMPM = $REQUEST_DATA['startAmPm'];
	$periodEndAMPM	= $REQUEST_DATA['endAmPm'];
	list($startHour, $startMin, $startSec) = explode(':', $startTime);
	$periodStartAMPM = $startAmPm;
	if ($startHour != 12 and $periodStartAMPM == 'PM') {
		$startHour += 12;
	}
	else if ($startHour == 12 and $periodStartAMPM == 'AM') {
		$startHour = 0;
	}
	list($endHour, $endMin, $endSec) = explode(':', $endTime);
	$periodEndAMPM = $endAmPm;
	if ($endHour != 12 and $periodEndAMPM == 'PM') {
		$endHour += 12;
	}
	else if ($endHour == 12 and $periodEndAMPM == 'AM') {
		$endHour = 0;
	}

	$oIperiodStartDateTime = mktime($startHour, $startMin, $startSec, $newMonth, $newDay, $newYear);
	$oIperiodEndDateTime = mktime($endHour, $endMin, $endSec, $newMonth, $newDay, $newYear);
	if ($oIperiodStartDateTime > $oIperiodEndDateTime) {
		echo 'Start Time should not be greater than End Time';
		die;
	}

	require_once(MODEL_PATH . "/PeriodsManager.inc.php");
	$periodsManager = PeriodsManager::getInstance();

	$periodSlotId = $REQUEST_DATA['slotName'];
	$periodDetailsArray = $periodsManager->getPeriodSlotPeriods($periodSlotId);
	if (is_array($periodDetailsArray) and count($periodDetailsArray)) {
		foreach($periodDetailsArray as $periodRecord) {
			$dbStartTime			= $periodRecord['startTime'];
			$dbEndTime				= $periodRecord['endTime'];
			$dbPeriodStartAMPM	= $periodRecord['startAmPm'];
			$dbPeriodEndAMPM		= $periodRecord['endAmPm'];
			$errorFound = false;
			list($dbStartHour, $dbStartMin, $dbStartSec) = explode(':', $dbStartTime);
			$periodStartAMPM = $dbPeriodStartAMPM;

			if ($dbStartTime != 12 and $dbPeriodStartAMPM == 'PM') {
				$dbStartHour += 12;
			}
			list($dbEndHour, $dbEndMin, $dbEndSec) = explode(':', $dbEndTime);
			$periodEndAMPM = $dbPeriodEndAMPM;
			if ($dbEndTime != 12 and $dbPeriodEndAMPM == 'PM') {
				$dbEndHour += 12;
			}
			$periodStartDateTime = mktime($dbStartHour, $dbStartMin, $dbStartSec, $newMonth, $newDay, $newYear);
			$periodEndDateTime = mktime($dbEndHour, $dbEndMin, $dbEndSec, $newMonth, $newDay, $newYear);
			if ($periodStartDateTime == $oIperiodStartDateTime) {
				$errorFound = true;
			}
			elseif ($periodStartDateTime > $oIperiodStartDateTime and $periodStartDateTime < $oIperiodEndDateTime) {
				$errorFound = true;
			}
			elseif ($periodStartDateTime < $oIperiodStartDateTime and $periodEndDateTime > $oIperiodStartDateTime) {
				$errorFound = true;
			}
			if ($errorFound == true) {
				echo 'Time Clash Found';
				die;
			}
		}
	}

#############	LOGIC FOR TIME BASED CHECKING ENDS :) #########################################






	//if ($periodStartTime[0]
	$errorMessage ='';
    if (!isset($REQUEST_DATA['periodNumber']) || trim($REQUEST_DATA['periodNumber']) == '') {
        $errorMessage .= ENTER_PERIOD_NUMBER. '<br/>';
    }
	    
    if (trim($errorMessage) == '') {
       
		if ($startTime != '' AND $endTime != '') {
			//$foundTimeArray = PeriodsManager::getInstance()->getPeriods('AND (("'.$periodStartTime.'" BETWEEN pr.startTime AND pr.endTime) AND ("'.$periodStartTime.'" < (pr.endTime)) OR ("'.$periodSecondTime.'" BETWEEN pr.startTime AND pr.endTime) OR ("'.$periodStartTime.'" < pr.startTime AND pr.endTime >= "'.$periodSecondTime.'")) AND pr.periodSlotId = "'.add_slashes(strtoupper($REQUEST_DATA['slotName'])).'"' );
            $foundTimeArray = PeriodsManager::getInstance()->getPeriods('AND (("'.$periodStartTime.'" BETWEEN pr.startTime AND pr.endTime) AND ("'.$periodStartTime.'" < (pr.endTime)) AND ("'.$periodSecondTime.'" BETWEEN pr.startTime AND pr.endTime)) AND pr.periodSlotId = "'.add_slashes(strtoupper($REQUEST_DATA['slotName'])).'"' );
			if(count($foundTimeArray)!= 0) {  //DUPLICATE TIME CHECK
			echo TIME_ALREADY_ALLOTED;
			die;
			}
		}
				
		$foundArray = PeriodsManager::getInstance()->getPeriods(' AND UCASE(pr.periodNumber)="'.add_slashes(trim(strtoupper($REQUEST_DATA['periodNumber']))).'" AND pr.periodSlotId="'.add_slashes(strtoupper($REQUEST_DATA['slotName'])).'"' );

         if(trim($foundArray[0]['periodNumber'])=='') {  //DUPLICATE CHECK    
		
            $returnStatus = PeriodsManager::getInstance()->addPeriods();
				if($returnStatus === false) {
					echo FAILURE;
				}
				else {
					echo SUCCESS;           
				}
			}
			else {
				echo PERIOD_NUMBER_EXIST;
			}
    }
    else {
        echo $errorMessage;
    }

// $History: ajaxInitAdd.php $
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 3/02/10    Time: 4:15p
//Updated in $/LeapCC/Library/Periods
//put the checks on time beween 9:00 A.M. to 12.00 A.M.
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 2/16/10    Time: 10:39a
//Updated in $/LeapCC/Library/Periods
//changed message
//
//*****************  Version 10  *****************
//User: Ajinder      Date: 2/11/10    Time: 6:25p
//Updated in $/LeapCC/Library/Periods
//fixed bug: 2247
//
//*****************  Version 9  *****************
//User: Parveen      Date: 1/04/10    Time: 3:08p
//Updated in $/LeapCC/Library/Periods
//condition format updated
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 12/18/09   Time: 6:10p
//Updated in $/LeapCC/Library/Periods
//fixed bug nos. 0002247, 0002270
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 11/14/09   Time: 5:42p
//Updated in $/LeapCC/Library/Periods
//fixed bug no.0001936
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 11/05/09   Time: 5:33p
//Updated in $/LeapCC/Library/Periods
//fixed bug nos.0001936,0001938,0001939
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 9/30/09    Time: 6:46p
//Updated in $/LeapCC/Library/Periods
//fixed bug nos.0001611, 0001632, 0001612, 0001600, 0001599, 0001598,
//0001594
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 9/24/09    Time: 5:32p
//Updated in $/LeapCC/Library/Periods
//resolved issue 1595
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/18/09    Time: 7:37p
//Updated in $/LeapCC/Library/Periods
//Remove administrator role from role type so that no new administrator
//can be made and Chalkpad will be administrator and Applied time
//validation so that start time can not be greater than end time.
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/08/09    Time: 2:46p
//Updated in $/LeapCC/Library/Periods
//remove mandatory fields on start time & end time
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/16/08   Time: 3:37p
//Created in $/LeapCC/Library/Periods
//get existing period files in leap
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 12/16/08   Time: 3:19p
//Updated in $/Leap/Source/Library/Periods
//modified to get slot name
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 11/06/08   Time: 5:10p
//Updated in $/Leap/Source/Library/Periods
//add define access in module
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 10/25/08   Time: 5:44p
//Updated in $/Leap/Source/Library/Periods
//add new field time table label Id
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/28/08    Time: 6:18p
//Updated in $/Leap/Source/Library/Periods
//modified in indentation
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/26/08    Time: 5:27p
//Updated in $/Leap/Source/Library/Periods
//modified message
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 7/28/08    Time: 7:39p
//Updated in $/Leap/Source/Library/Periods
//modified for institute id
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/19/08    Time: 5:37p
//Updated in $/Leap/Source/Library/Periods
//change error message with echo
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/19/08    Time: 3:17p
//Updated in $/Leap/Source/Library/Periods
?>