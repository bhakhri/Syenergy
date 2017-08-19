<?php
//-------------------------------------------------------
//  This File checks  whether record exists in Branch Form Table
//
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BranchMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
if(trim($REQUEST_DATA['branchId'] ) != '') {
    require_once(MODEL_PATH . "/BranchManager.inc.php");
    $foundArray = BranchManager::getInstance()->getBranch(' WHERE branchId="'.$REQUEST_DATA['branchId'].'"');
    if(is_array($foundArray) && count($foundArray)>0 ) {
        echo json_encode($foundArray[0]);
    }
    else {
        echo '0';
    }
}
//$History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Branch
//
//*****************  Version 6  *****************
//User: Parveen      Date: 11/05/08   Time: 4:47p
//Updated in $/Leap/Source/Library/Branch
//Define Module-Access - Added
//
//*****************  Version 5  *****************
//User: Arvind       Date: 8/27/08    Time: 4:40p
//Updated in $/Leap/Source/Library/Branch
//removed spaces
//
//*****************  Version 4  *****************
//User: Arvind       Date: 6/30/08    Time: 4:02p
//Updated in $/Leap/Source/Library/Branch
//changed echo function
//
//*****************  Version 3  *****************
//User: Arvind       Date: 6/14/08    Time: 7:16p
//Updated in $/Leap/Source/Library/Branch
//modification
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/13/08    Time: 12:03p
//Updated in $/Leap/Source/Library/Branch
//Make $history a comment
//
//*****************  Version 1  *****************
//User: Administrator Date: 6/12/08    Time: 8:19p
//Created in $/Leap/Source/Library/Branch
//NEw Files Added in Branch Folder
?>