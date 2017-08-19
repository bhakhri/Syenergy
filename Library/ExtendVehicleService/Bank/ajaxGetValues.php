<?php
////  This File gets  record from the bank Form Table
//
// Author :Ajinder Singh
// Created on : 23-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BankMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


if(trim($REQUEST_DATA['bankId'] ) != '') {
    require_once(MODEL_PATH . "/BankManager.inc.php");
    $foundArray = BankManager::getInstance()->getBank(' WHERE bankId='.$REQUEST_DATA['bankId']);
	
   if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
	
}
// $History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Bank
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/10/08   Time: 11:56a
//Updated in $/Leap/Source/Library/Bank
//add define access in module
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/23/08    Time: 12:41p
//Created in $/Leap/Source/Library/Bank
//File created for Bank Master

?>