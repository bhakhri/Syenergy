
<?php 
//
//  This File checks  whether record exists in "FeeFundAllocation" Form Table
//
// Author :Arvind Singh Rawat
// Created on : 2-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FundAllocationMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
 
 //Function gets data from FeeFundAllocation table
 
if(trim($REQUEST_DATA['feeFundAllocationId'] ) != '') {
    require_once(MODEL_PATH . "/FeeFundAllocationManager.inc.php");
    $foundArray = FeeFundAllocationManager::getInstance()->getFeeFundAllocation(' AND feeFundAllocationId="'.$REQUEST_DATA['feeFundAllocationId'].'"');
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
//Created in $/LeapCC/Library/FeeFundAllocation
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Library/FeeFundAllocation
//Define Module, Access  Added
//
//*****************  Version 2  *****************
//User: Arvind       Date: 8/05/08    Time: 1:35p
//Updated in $/Leap/Source/Library/FeeFundAllocation
//modified the query
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/03/08    Time: 11:18a
//Created in $/Leap/Source/Library/FeeFundAllocation
//Added files for new module

?>
