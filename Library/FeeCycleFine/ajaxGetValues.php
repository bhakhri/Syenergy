
<?php 

//
//--------------------------------------------------------------------------------------------
//  THIS FILE IS USED FOR GETTING DATA FROM THE "FeeCycleFine" MODULE
//  
// Author :Arvind Singh Rawat
// Created on : 1st-JUlY-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------
//

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeCycleFines');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
 
 //Function gets data from FeeCycleFine table
 
if(trim($REQUEST_DATA['feeCycleFineId'] ) != '') {
    require_once(MODEL_PATH . "/FeeCycleFineManager.inc.php");
    $foundArray = FeeCycleFineManager::getInstance()->getFeeCycleFine(' AND feeCycleFineId="'.$REQUEST_DATA['feeCycleFineId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}


//$History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/FeeCycleFine
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Library/FeeCycleFine
//Define Module, Access  Added
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/11/08    Time: 5:12p
//Updated in $/Leap/Source/Library/FeeCycleFine
//modified the get function parameters
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/03/08    Time: 11:17a
//Created in $/Leap/Source/Library/FeeCycleFine
//Added library files of" feecyclefine" module
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/03/08    Time: 11:15a
//Created in $/Leap/Source/Library
//Added library files of" feecyclefine" module

?>
