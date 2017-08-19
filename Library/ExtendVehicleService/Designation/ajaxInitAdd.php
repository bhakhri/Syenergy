<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD NEW DESIGNATION
// Author : Jaineesh
// Created on : (13.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DesignationMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if ($errorMessage == '' && (!isset($REQUEST_DATA['designationName']) || trim($REQUEST_DATA['designationName']) == '')) {
        $errorMessage .= ENTER_DESIGNATION_NAME. '<br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['designationCode']) || trim($REQUEST_DATA['designationCode']) == '')) {
        $errorMessage .= ENTER_DESIGNATION_CODE. '<br/>';
    }
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/DesignationManager.inc.php");
		$foundArray = DesignationManager::getInstance()->getDesignation(' WHERE LCASE(designationName)= "'.add_slashes(trim(strtolower($REQUEST_DATA['designationName']))).'"');
		if(trim($foundArray[0]['designationName'])=='') {  //DUPLICATE CHECK
			$foundArray2 = DesignationManager::getInstance()->getDesignation(' WHERE UCASE(designationCode)="'.add_slashes(strtoupper($REQUEST_DATA['designationCode'])).'"');
			if(trim($foundArray2[0]['designationCode'])=='') {  //DUPLICATE CHECK
				$returnStatus = DesignationManager::getInstance()->addDesignation();
				if($returnStatus === false) {
					echo FAILURE;
				}
				else {
					echo SUCCESS;           
				}
			}
			else {
				echo DESIGNATION_ALREADY_EXIST;
			}
		}
		else {
			echo DESIGNATION_NAME_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }

// $History: ajaxInitAdd.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Designation
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 11/06/08   Time: 12:33p
//Updated in $/Leap/Source/Library/Designation
//define access values
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/28/08    Time: 12:16p
//Updated in $/Leap/Source/Library/Designation
//modified in indentation
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/21/08    Time: 2:42p
//Updated in $/Leap/Source/Library/Designation
//modified in messages
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/20/08    Time: 2:32p
//Updated in $/Leap/Source/Library/Designation
//modified for error message
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/11/08    Time: 12:42p
//Updated in $/Leap/Source/Library/Designation
//modified for duplicate record check
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/19/08    Time: 5:21p
//Updated in $/Leap/Source/Library/Designation
//change errormessage from echo
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/19/08    Time: 2:24p
//Updated in $/Leap/Source/Library/Designation
?>