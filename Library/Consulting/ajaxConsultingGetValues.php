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
    
//if(trim($REQUEST_DATA['seminarId'] ) != '') {
    require_once(MODEL_PATH . "/ConsultingManager.inc.php");
    $consultManager = ConsultingManager::getInstance();
        
    $consultArray = $consultManager->getConsulting(' AND consultId ='.$REQUEST_DATA['consultId']);
    if(is_array($consultArray) && count($consultArray)>0 ) {  
        echo json_encode($consultArray[0]);
		//die();
    }
    else {
        echo 0;
    }
//}
// $History: ajaxConsultingGetValues.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/23/09    Time: 12:13p
//Created in $/LeapCC/Library/Consulting
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/18/09    Time: 1:15p
//Created in $/Leap/Source/Library/Consulting
//initial checkin 
//
//
?>