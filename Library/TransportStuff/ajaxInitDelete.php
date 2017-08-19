<?php
//-------------------------------------------------------
// Purpose: To delete TransportStuff detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (26.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TransportStuffMaster');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    if (!isset($REQUEST_DATA['stuffId']) || trim($REQUEST_DATA['stuffId']) == '') {
        $errorMessage = 'Invalid Stuff Record';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/TransportStuffManager.inc.php");
        $transportManager =  TransportStuffManager::getInstance();
        if($transportManager->deleteTransportStuff($REQUEST_DATA['stuffId']) ) {
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
//User: Dipanjan     Date: 1/04/09    Time: 15:37
//Created in $/LeapCC/Library/TransportStuff
//Added Files for bus modules
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 1/04/09    Time: 15:01
//Created in $/Leap/Source/Library/TransportStuff
//Added Files for bus modules
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/09   Time: 16:46
//Created in $/SnS/Library/TransportStuff
//Created module Transport Stuff Master
?>

