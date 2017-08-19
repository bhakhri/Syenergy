<?php
//-------------------------------------------------------
// Purpose: To add hostel detail
//
// Author : Jaineesh
// Created on : (26.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HostelMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['hostelName']) || trim($REQUEST_DATA['hostelName']) == '') {
        $errorMessage .= ENTER_HOSTEL_NAME. "\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['hostelCode']) || trim($REQUEST_DATA['hostelCode']) == '')) {
        $errorMessage .= ENTER_HOSTEL_CODE. "\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['roomTotal']) || trim($REQUEST_DATA['roomTotal']) == '')) {
        $errorMessage .= ENTER_TOTAL_ROOM. "\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['floorTotal']) || trim($REQUEST_DATA['floorTotal']) == '')) {
        $errorMessage .= ENTER_TOTAL_FLOOR. "\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['hostelType']) || trim($REQUEST_DATA['hostelType']) == '')) {
        $errorMessage .= ENTER_HOSTEL_TYPE. "\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['totalCapacity']) || trim($REQUEST_DATA['totalCapacity']) == '')) {
        $errorMessage .= ENTER_TOTAL_CAPACITY. "\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['wardenName']) || trim($REQUEST_DATA['wardenName']) == '')) {
        $errorMessage .= ENTER_WARDEN_NAME. "\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['wardenContactNo']) || trim($REQUEST_DATA['wardenContactNo']) == '')) {
        $errorMessage .= ENTER_WARDEN_CONTACT_NUMBER. "\n";
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/HostelManager.inc.php");
                 $foundArray = HostelManager::getInstance()->getHostel(' WHERE UCASE(hostelName)= "'.add_slashes(trim(strtoupper($REQUEST_DATA['hostelName']))).'" OR UCASE(hostelCode)="'.add_slashes(strtoupper($REQUEST_DATA['hostelCode'])).'"');
				 
                 if(trim($foundArray[0]['hostelCode'])=='') {  //DUPLICATE CHECK  
				   $returnStatus = HostelManager::getInstance()->addHostel();
					    if($returnStatus === false) {
						    echo FAILURE;
					    }
					    else {
						    echo SUCCESS;           
					    }
					}
					else {
                       if(strtoupper($foundArray[0]['hostelCode'])==trim(strtoupper($REQUEST_DATA['hostelCode']))) {
					  	 echo HOSTEL_ALREADY_EXIST;
                         die;
                       }
                       else if(strtoupper($foundArray[0]['hostelName'])==trim(strtoupper($REQUEST_DATA['hostelName']))) {
                           echo HOSTEL_NAME_EXIST;
                           die;
                       }
					}
    }
	else {
        echo $errorMessage;
    }
// $History: ajaxInitAdd.php $ 
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/08/09    Time: 1:07p
//Updated in $/LeapCC/Library/Hostel
//fixed bugs Issues BuildCC# cc0001.doc
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/05/09    Time: 11:10a
//Created in $/LeapCC/Library/Hostel
//ajax files include add, delete, edit & list functions made by Jaineesh
//& modifications by Gurkeerat and added in VSS by Jaineesh
//
//*****************  Version 6  *****************
//User: Gurkeerat Sidhu     Date: 18/04/09   Time: 5:43p
//Updated in $/Leap/Source/Library/Hostel
//added new fields (floorTotal,hostelType,totalCapacity)  
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 11/06/08   Time: 5:43p
//Updated in $/Leap/Source/Library/Hostel
//add define access in module
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/28/08    Time: 3:29p
//Updated in $/Leap/Source/Library/Hostel
//modification in indentation
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/21/08    Time: 3:56p
//Updated in $/Leap/Source/Library/Hostel
//modified in messages
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/11/08    Time: 5:04p
//Updated in $/Leap/Source/Library/Hostel
//modified to check duplicate records
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/27/08    Time: 12:30p
//Created in $/Leap/Source/Library/Hostel
//ajax functions for add, edit, delete and search
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 6/18/08    Time: 7:56p
//Updated in $/Leap/Source/Library/States
//changed duplicate message and single quote to double quotes in error
//messages
//
//*****************  Version 2  *****************
//User: Administrator Date: 6/13/08    Time: 3:46p
//Updated in $/Leap/Source/Library/States
//To add comments and Refine the code: DONE
?>