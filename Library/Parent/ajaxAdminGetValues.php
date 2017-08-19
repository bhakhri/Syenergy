<?php 

////  Get the data from notice table 
//
// Author :Jaineesh
// Created on : 02-09-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ParentAdminMessages');
define('ACCESS','view');
UtilityManager::ifParentNotLoggedIn(true);     
UtilityManager::headerNoCache();
 
require_once(MODEL_PATH . "/Parent/ParentManager.inc.php");
$parentManager = ParentManager::getInstance();
 
 //Function gets data from notice table
 
if(trim($REQUEST_DATA['messageId']) != '') {
    $foundArray = $parentManager->getAdminMessages(' AND messageId = '.$REQUEST_DATA['messageId']);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
		//print_r($foundArray);
		$foundArray[0][message]=html_entity_decode(strip_slashes($foundArray[0][message]));
		echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
//$History: ajaxAdminGetValues.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 10/15/09   Time: 5:48p
//Updated in $/LeapCC/Library/Parent
//added access rights
//
//*****************  Version 2  *****************
//User: Parveen      Date: 9/04/09    Time: 3:01p
//Updated in $/LeapCC/Library/Parent
//div base berif information formating updated 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 8/18/09    Time: 3:47p
//Created in $/LeapCC/Library/Parent
//file added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 8/18/09    Time: 3:45p
//Created in $/LeapCC/Templates/Parent
//file added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 8/07/09    Time: 7:21p
//Updated in $/Leap/Source/Library/Parent
//validation, features, conditions, formatting updated (bug Nos.
//331, 323, 334, 338,339, 348, 350, 351,352, 354, 380, 381,342, 349, 427,
//428, 429,430, 431, 432, 433, 434,435, 436,437, 432, 479, 480, 481,482,
//493, 494, 495, 498,501, 502,478, 477, 934, 924, 925)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 8/07/09    Time: 6:34p
//Created in $/Leap/Source/Library/Parent
//initial checkin
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 10/08/08   Time: 3:29p
//Updated in $/Leap/Source/Library/Student
//remove spaces
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 9/04/08    Time: 11:04a
//Created in $/Leap/Source/Library/Student
//ajax file to show the admin message in div
//
?>