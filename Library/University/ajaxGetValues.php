<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE UNIVERSITY LIST
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (14.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','UniversityMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonManager = CommonQueryManager::getInstance();

if(trim($REQUEST_DATA['universityId'] ) != '') {
    require_once(MODEL_PATH . "/UniversityManager.inc.php");
    $foundArray = UniversityManager::getInstance()->getUniversity(" WHERE universityId='".$REQUEST_DATA['universityId']."'");
    
    // to populate city, state dropdowns as per stored countryId & State Id
    $statesArray = $commonManager->getStatesCountry(" WHERE countryId='".$foundArray[0]['countryId']."'");
    $cityArray   = $commonManager->getCityState(" WHERE stateId='".$foundArray[0]['stateId']."'");
    
    $stateCount = count($statesArray);
    if(is_array($statesArray) && $stateCount>0) {
        $jsonStates  = '';
        for($s = 0; $s<$stateCount; $s++) {
            $jsonStates .= json_encode($statesArray[$s]). ( $s==($stateCount-1) ? '' : ',' );                }
    }
    $cityCount = count($cityArray);
    if(is_array($cityArray) && $cityCount>0) {
        $jsonCity  = '';
        for($s = 0; $s<$cityCount; $s++) {
            $jsonCity .= json_encode($cityArray[$s]). ( $s==($cityCount-1) ? '' : ',' );
        }    
    }
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo '{"edit":['.json_encode($foundArray[0]).'],"state":['.$jsonStates.'],"city":['.$jsonCity.']}';
    }
    else {
        echo 0;
    }
    
}
// $History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/University
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/06/08   Time: 11:16a
//Updated in $/Leap/Source/Library/University
//Added access rules
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/09/08    Time: 1:52p
//Updated in $/Leap/Source/Library/University
//Added Image upload functionality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/09/08    Time: 11:09a
//Created in $/Leap/Source/Library/University
//
//*****************  Version 4  *****************
//User: Pushpender   Date: 7/08/08    Time: 6:01p
//Updated in $/Leap/Source/Library/University
//added code to populate states, cities 
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/30/08    Time: 4:35p
//Updated in $/Leap/Source/Library/University
//Added AjaxListing and AjaxSearch Functionality
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/14/08    Time: 3:19p
//Updated in $/Leap/Source/Library/University
//Modifying Done
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/14/08    Time: 7:30p
//Created in $/Leap/Source/Library/University
//Initial Checkin
?>