<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE INSTITUTE LIST
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (14.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','InstituteMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['instituteId'] ) != '') {
    require_once(MODEL_PATH . "/InstituteManager.inc.php");
    $foundArray = InstituteManager::getInstance()->getInstitute(' WHERE instituteId="'.$REQUEST_DATA['instituteId'].'"');
    $empArray = InstituteManager::getInstance()->getEmployee(' WHERE e.instituteId="'.$REQUEST_DATA['instituteId'].'"',' AND ect.instituteId="'.$REQUEST_DATA['instituteId'].'"');
    
    // to populate city, state dropdowns as per stored countryId & State Id
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonManager = CommonQueryManager::getInstance();
    $statesArray = $commonManager->getStatesCountry(' WHERE countryId='.$foundArray[0]['countryId']);
    $cityArray   = $commonManager->getCityState(' WHERE stateId='.$foundArray[0]['stateId']);
    
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
        echo '{"edit":['.json_encode($foundArray[0]).'],"state":['.$jsonStates.'],"city":['.$jsonCity.'],"employee":'.json_encode($empArray).'}';
    }
    else {
        echo 0;
    }
    
}
// $History: ajaxGetValues.php $
//
//*****************  Version 4  *****************
//User: Administrator Date: 24/07/09   Time: 14:57
//Updated in $/LeapCC/Library/Institute
//Done bug fixing.
//Bug ids----0000648,0000650,0000667,0000651,0000676,0000649,0000652
//
//*****************  Version 3  *****************
//User: Administrator Date: 28/05/09   Time: 12:40
//Updated in $/LeapCC/Library/Institute
//Corrected institute module
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 26/05/09   Time: 15:45
//Updated in $/LeapCC/Library/Institute
//Fixed bugs-----Issues [26-May-09]1
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Institute
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:31p
//Updated in $/Leap/Source/Library/Institute
//Added access rules
//
//*****************  Version 4  *****************
//User: Pushpender   Date: 7/08/08    Time: 6:01p
//Updated in $/Leap/Source/Library/Institute
//added code to populate states, cities 
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/30/08    Time: 4:35p
//Updated in $/Leap/Source/Library/Institute
//Added AjaxListing and AjaxSearch Functionality
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/14/08    Time: 3:19p
//Updated in $/Leap/Source/Library/Institute
//Modifying Done
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/14/08    Time: 7:30p
//Created in $/Leap/Source/Library/Institute
//Initial Checkin
?>