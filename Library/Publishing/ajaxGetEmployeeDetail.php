<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE EMPLOYEE NAME 
//
// Author : Jaineesh
// Created on : (04.03.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeInfo');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


    require_once(MODEL_PATH . "/PublishingManager.inc.php");
    $foundArray = PublishingManager::getInstance()->getEmployeeDetail(' WHERE employeeId="'.$REQUEST_DATA['employeeId'].'"');
	
		if(is_array($foundArray) && count($foundArray)>0 ) {  
			echo json_encode($foundArray[0]);
			die();
		}
		else {
			echo 0; // no record found
		}


// $History: ajaxGetEmployeeDetail.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/23/09    Time: 12:14p
//Created in $/LeapCC/Library/Publishing
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/18/09    Time: 1:14p
//Created in $/Leap/Source/Library/Publishing
//initial checkin 
//
//

?>