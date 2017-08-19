<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A CITY 
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GuestHouseRequest');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if(trim($REQUEST_DATA['allocationId'])==''){
        echo 'Required Parameters Missing';
        die;
    }
    
    require_once(MODEL_PATH . "/GuestHouseManager.inc.php");
    $guestHouseManager = GuestHouseManager::getInstance();
    $requestedUserId=$sessionHandler->getSessionVariable('UserId');
    $date=date('Y-m-d');
    $reason='Rejected by requester';
    
    $ret=$guestHouseManager->makeGuestHouseRequestReject(trim($REQUEST_DATA['allocationId']),$reason,$requestedUserId,$date,2);
    
    if($ret==false){
        echo FAILURE;
        die;
    }
    else{
        echo SUCCESS;
        die;
    }
// $History: ajaxInitAdd.php $
?>