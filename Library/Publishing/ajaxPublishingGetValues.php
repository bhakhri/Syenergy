<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE DOCUMENT LIST
//
// Author : Jaineesh
// Created on : (28.02.2009 )
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
    
if(trim($REQUEST_DATA['publishId'] ) != '') {
    require_once(MODEL_PATH . "/PublishingManager.inc.php");
	$publishingManager = PublishingManager::getInstance();
    $publishingArray = $publishingManager->getPublishing(' AND publishId="'.$REQUEST_DATA['publishId'].'"');
    if(is_array($publishingArray) && count($publishingArray)>0 ) {  
        echo json_encode($publishingArray[0]);
		//die();
    }
    else {
        echo 0;
    }
}
// $History: ajaxPublishingGetValues.php $
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