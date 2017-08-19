<?php
//-------------------------------------------------------
// Purpose: To delete blobk detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (11.7.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BlockCourse');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['blockId']) || trim($REQUEST_DATA['blockId']) == '') {
        $errorMessage = 'Invalid Block';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/BlockManager.inc.php");
        $blockManager =  BlockManager::getInstance();
        //as "room" table depends on "block"  table
        $recordArray = $blockManager->checkInRoom($REQUEST_DATA['blockId']);
        if($recordArray[0]['found']==0) {
            if($blockManager->deleteBlock($REQUEST_DATA['blockId']) ) {
                echo DELETE;
            }
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
//Created in $/LeapCC/Library/Block
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/05/08   Time: 5:41p
//Updated in $/Leap/Source/Library/Block
//Added access rules
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/11/08    Time: 12:43p
//Updated in $/Leap/Source/Library/Block
//Created "Block" Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/10/08    Time: 7:05p
//Created in $/Leap/Source/Library/Block
//Initial Checkin
?>

