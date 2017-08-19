<?php
//-------------------------------------------------------
// THIS FILE IS USED TO Edit A slab
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (13.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SlabsMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['deliveredFrom']) || trim($REQUEST_DATA['deliveredFrom']) == '') {
        $errorMessage .= ENTER_DELIVERED_FROM."\n";   
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['deliveredTo']) || trim($REQUEST_DATA['deliveredTo']) == '')) {
        $errorMessage .= ENTER_DELIVERED_TO."\n";  
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['marks']) || trim($REQUEST_DATA['marks']) == '')) {
        $errorMessage .= ENTER_ATT_MARKS."\n";  
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/SlabsManager.inc.php");
        $foundArray = SlabsManager::getInstance()->checkSlabs($REQUEST_DATA['deliveredFrom'],$REQUEST_DATA['deliveredTo'],$REQUEST_DATA['slabId']);
        if(trim($foundArray[0]['deliveredFrom'])=='') {  //DUPLICATE CHECK
            $returnStatus = SlabsManager::getInstance()->editSlabs($REQUEST_DATA['slabId']);
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else {
            echo SLAB_ALREADY_EXIST;    
        }
    }
    else {
        echo $errorMessage;
    }

// $History: ajaxInitEdit.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Slabs
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/06/08   Time: 10:39a
//Updated in $/Leap/Source/Library/Slabs
//Added access rules
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/21/08    Time: 3:02p
//Updated in $/Leap/Source/Library/Slabs
//Added Standard Messages
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/12/08    Time: 11:44a
//Created in $/Leap/Source/Library/Slabs
?>
