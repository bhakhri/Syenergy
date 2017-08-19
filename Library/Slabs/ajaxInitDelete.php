<?php
//-------------------------------------------------------
// Purpose: To delete slab detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (25.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SlabsMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['slabId']) || trim($REQUEST_DATA['slabId']) == '') {
        $errorMessage = 'Invalid Slab';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/SlabsManager.inc.php");
        $slabsManager =  SlabsManager::getInstance();
        if($slabsManager->deleteSlabs($REQUEST_DATA['slabId']) ) {
               echo DELETE;
        }
       else {
               echo DEPENDENCY_CONSTRAINT;
       }
    }
    else {
        echo $errorMessage;
    }
   
    
// $History: ajaxInitDelete.php $    
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Slabs
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/06/08   Time: 10:39a
//Updated in $/Leap/Source/Library/Slabs
//Added access rules
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/12/08    Time: 11:44a
//Created in $/Leap/Source/Library/Slabs
?>

