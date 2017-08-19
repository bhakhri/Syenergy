
<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT EXISTING GROUP TYPE 
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
define('MODULE','GroupTypeMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['groupTypeName']) || trim($REQUEST_DATA['groupTypeName']) == '')) {
        $errorMessage .= ENTER_GROUPTYPE_NAME. "\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['groupTypeCode']) || trim($REQUEST_DATA['groupTypeCode']) == '')) {
        $errorMessage .= ENTER_GROUPTYPE_CODE. "\n";
    }
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/GroupTypeManager.inc.php");
		$foundArray = GroupTypeManager::getInstance()->getGroupType(' WHERE LCASE(groupTypeName)= "'.add_slashes(trim(strtolower($REQUEST_DATA['groupTypeName']))).'" AND groupTypeId!='.$REQUEST_DATA['groupTypeId']);
			if(trim($foundArray[0]['groupTypeName'])=='') {  //DUPLICATE CHECK
				$foundArray2 = GroupTypeManager::getInstance()->getGroupType(' WHERE UCASE(groupTypeCode)="'.add_slashes(strtoupper($REQUEST_DATA['groupTypeCode'])).'" AND groupTypeId!='.$REQUEST_DATA['groupTypeId']);
			if(trim($foundArray2[0]['groupTypeCode'])=='') {  //DUPLICATE CHECK
				$returnStatus = GroupTypeManager::getInstance()->editGroupType($REQUEST_DATA['groupTypeId']);
					if($returnStatus === false) {
						echo FAILURE;
					}
					else {
						echo SUCCESS;
					}
				}
				else {
					echo GROUPTYPECODE_ALREADY_EXIST;
				}
			}
			else {
				echo GROUPTYPE_NAME_EXIST;
			}
    }
    else {
        echo $errorMessage;
    }

// $History: ajaxInitEdit.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/10/09    Time: 1:09p
//Updated in $/LeapCC/Library/GroupType
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/GroupType
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/28/08    Time: 2:58p
//Updated in $/Leap/Source/Library/GroupType
//modification in indentation
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/21/08    Time: 2:25p
//Updated in $/Leap/Source/Library/GroupType
//modified in messages
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/11/08    Time: 2:53p
//Updated in $/Leap/Source/Library/GroupType
//modified to check duplicate records
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/19/08    Time: 5:34p
//Updated in $/Leap/Source/Library/GroupType
//change error message with echo
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/19/08    Time: 3:37p
//Updated in $/Leap/Source/Library/GroupType
?>