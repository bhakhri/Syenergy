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
define('MODULE','EmployeeInformation');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn(true);   
UtilityManager::headerNoCache();
    
//if(trim($REQUEST_DATA['seminarId'] ) != '') {
    require_once(MODEL_PATH . "/EmployeeManager.inc.php");
    $consultManager = EmployeeManager::getInstance();
        
    $consultArray = $consultManager->getConsulting(' AND consultId ='.add_slashes($REQUEST_DATA['consultId']));
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
//*****************  Version 2  *****************
//User: Parveen      Date: 7/17/09    Time: 5:26p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//role permission, alignment, new enhancements added 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/13/09    Time: 3:39p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//file added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/24/09    Time: 3:00p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity/Consulting
//formatting, conditions, validations updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/24/09    Time: 12:07p
//Created in $/LeapCC/Library/Teacher/TeacherActivity/Consulting
//initial checkin
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