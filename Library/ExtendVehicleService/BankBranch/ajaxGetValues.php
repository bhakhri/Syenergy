<?php
////  This File gets  record from the bank branch Form Table
//
// Author :Ajinder Singh
// Created on : 23-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


if(trim($REQUEST_DATA['bankBranchId'] ) != '') {
    require_once(MODEL_PATH . "/BankBranchManager.inc.php");
    $foundArray = BankBranchManager::getInstance()->getBankBranch(' WHERE bankBranchId='.$REQUEST_DATA['bankBranchId']);
	
   if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
	
}
// $History: ajaxGetValues.php $
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 12/29/09   Time: 12:58p
//Updated in $/LeapCC/Library/BankBranch
//Merged Bank & BankBranch module in single module
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/BankBranch
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/10/08   Time: 11:58a
//Updated in $/Leap/Source/Library/BankBranch
//add define access in module
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/23/08    Time: 5:49p
//Created in $/Leap/Source/Library/BankBranch
//File added for bank branch master

?>