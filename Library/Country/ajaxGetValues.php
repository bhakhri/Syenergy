
<?php 

////  This File checks  whether record exists in Country Form Table
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CountryMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
 
 //Function gets data from country table
 
if(trim($REQUEST_DATA['countryId'] ) != '') {
    require_once(MODEL_PATH . "/CountryManager.inc.php");
    $foundArray = CountryManager::getInstance()->getCountry(' WHERE countryId="'.$REQUEST_DATA['countryId'].'"');
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
//Created in $/LeapCC/Library/Country
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/05/08   Time: 4:42p
//Updated in $/Leap/Source/Library/Country
//define MODULE, ACCESS - added
//
//*****************  Version 4  *****************
//User: Arvind       Date: 6/30/08    Time: 4:38p
//Updated in $/Leap/Source/Library/Country
//modified echo function
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/13/08    Time: 12:04p
//Updated in $/Leap/Source/Library/Country
//Make $history a comment
//
//*****************  Version 1  *****************
//User: Administrator Date: 6/12/08    Time: 8:19p
//Created in $/Leap/Source/Library/Country
//New Files Added in Country Folder

?>
