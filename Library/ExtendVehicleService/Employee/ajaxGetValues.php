<?php

//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE EMPLOYEE LIST
//
//
// Author : Jaineesh
// Created on : (14.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

if(trim($REQUEST_DATA['employeeId'] ) != '') {
    require_once(MODEL_PATH . "/EmployeeManager.inc.php");
	$foundArray = null;
	$foundArray = EmployeeManager::getInstance()->getEmployee(' AND emp.employeeId='.$REQUEST_DATA['employeeId']);
	//print_r($foundArray); die;
	// to populate city, state dropdowns as per stored countryId & State Id
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonManager = CommonQueryManager::getInstance();
		if($foundArray[0]['countryId'] != '') {
			$statesArray = $commonManager->getStatesCountry(' WHERE countryId='.$foundArray[0]['countryId']);
			$stateCount = count($statesArray);
			if(is_array($statesArray) && $stateCount>0) {
				$jsonStates  = '';
				for($s = 0; $s<$stateCount; $s++) {
					$jsonStates .= json_encode($statesArray[$s]). ( $s==($stateCount-1) ? '' : ',' );                }
			}
		}
		if ($foundArray[0]['stateId'] != '') {
			$cityArray   = $commonManager->getCityState(' WHERE stateId='.$foundArray[0]['stateId']);
			$cityCount = count($cityArray);
		if(is_array($cityArray) && $cityCount>0) {
			$jsonCity  = '';
			for($s = 0; $s<$cityCount; $s++) {
				$jsonCity .= json_encode($cityArray[$s]). ( $s==($cityCount-1) ? '' : ',' );
			}
		  }
		}

         $empPhoto=$foundArray[0]['employeeImage'];
		if(!file_exists(IMG_PATH.'/Employee/'.$empPhoto)){
		 $foundArray[0]['employeeImage']=-1;
		}
		 $thumbPhoto=$foundArray[0]['thumbImageDisplayDiv'];
		if(!file_exists(IMG_PATH.'/Employee/'.$thumbPhoto)){
		 $foundArray[0]['thumbImageDisplayDiv']=-1;
		}
		$txtEmpThumpPhoto=$foundArray[0]['txtEmpThumbImage'];
		if(!file_exists(IMG_PATH.'/Employee/'.$txtEmpThumpPhoto)){
		 $foundArray[0]['txtEmpThumbImage']=-1;
		}
		if(is_array($foundArray) && count($foundArray)>0 ) {
			echo '{"edit":'.json_encode($foundArray).',"state":['.$jsonStates.'],"city":['.$jsonCity.']}';

			//echo json_encode($foundArray);
		}
		else {
			echo 0; // no record found
		}
	}

// $History: ajaxGetValues.php $
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 4/19/10    Time: 1:06p
//Updated in $/LeapCC/Library/Employee
//fixed bug for multiple institute
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 4/15/10    Time: 6:56p
//Updated in $/LeapCC/Library/Employee
//fixed bug nos. 0003247, 0003250, 0003174
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/19/08   Time: 3:31p
//Updated in $/LeapCC/Library/Employee
//modified for employee can teach in
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Employee
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 11/06/08   Time: 12:39p
//Updated in $/Leap/Source/Library/Employee
//define access file
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/28/08    Time: 12:01p
//Updated in $/Leap/Source/Library/Employee
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/20/08    Time: 6:31p
//Updated in $/Leap/Source/Library/Employee
//modified in messages
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/08/08    Time: 5:15p
//Updated in $/Leap/Source/Library/Employee
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/19/08    Time: 3:55p
//Created in $/Leap/Source/Library/Employee
//checkin
?>