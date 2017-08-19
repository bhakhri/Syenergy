<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE DOCUMENT LIST
//
// Author : Jaineesh
// Created on : (28.02.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeInfo');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
//if(trim($REQUEST_DATA['seminarId'] ) != '') {
    require_once(MODEL_PATH . "/SeminarsManager.inc.php");
    $seminarManager = SeminarsManager::getInstance();
        
    $seminarArray = $seminarManager->getSeminars(' AND seminarId ='.$REQUEST_DATA['seminarId']);
    if(is_array($seminarArray) && count($seminarArray)>0 ) {  
        echo json_encode($seminarArray[0]);
		//die();
    }
    else {
        echo 0;
    }
//}
// $History: ajaxSeminarGetValues.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/23/09    Time: 12:13p
//Created in $/LeapCC/Library/Seminar
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/18/09    Time: 1:15p
//Created in $/Leap/Source/Library/Seminar
//initial checkin 
//
//
?>