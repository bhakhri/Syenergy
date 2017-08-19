<?php
////  This File gets  record from the class Form Table
//
// Author :Ajinder Singh
// Created on : 23-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CreateClass');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();


if(trim($REQUEST_DATA['classId'] ) != '') {
    require_once(MODEL_PATH . "/ClassesManager.inc.php");
    $foundArray = ClassesManager::getInstance()->getClasses(' AND a.classId='.$REQUEST_DATA['classId']);
	
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
//Created in $/LeapCC/Library/Classes
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/10/08   Time: 12:10p
//Updated in $/Leap/Source/Library/Classes
//add define access in module
//
//*****************  Version 1  *****************
//User: Admin        Date: 8/05/08    Time: 6:39p
//Created in $/Leap/Source/Library/Classes
//file added for fetching values for class
//

?>