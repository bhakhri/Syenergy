
<?php 
//--------------------------------------------------------------------------
////  This File checks  whether record exists in FEE HEAD VALUES Form Table
//
//
// Author :Arvind Singh Rawat
// Created on : 18-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeHeadValues');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
 
 //Function gets data from country table
 
if(trim($REQUEST_DATA['feeHeadValueId'] ) != '') {
    require_once(MODEL_PATH . "/FeeHeadValuesManager.inc.php");
    $foundArray = FeeHeadValuesManager::getInstance()->getFeeHeadValues(' WHERE feeHeadValueId="'.$REQUEST_DATA['feeHeadValueId'].'"');
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
//Created in $/LeapCC/Library/FeeHeadValues
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Library/FeeHeadValues
//Define Module, Access  Added
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/19/08    Time: 12:45p
//Created in $/Leap/Source/Library/FeeHeadValues
//initial checkin

?>
