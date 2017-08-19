<?php 
////  This File checks  whether record exists in Notice Form Table
// Author :Arvind Singh Rawat
// Created on : 5-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;  
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1);
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
 
 //Function gets data from Notice table
require_once(MODEL_PATH . "/EventManager.inc.php");
$eventManager =EventManager::getInstance();  
    
    $userWishEventId = $REQUEST_DATA['userWishEventId']; 

    if($userWishEventId=='') {
      $userWishEventId=0;  
    } 
     
    $conditions = " um.userWishEventId='$userWishEventId'";
    $foundArray = $eventManager->getEvent($conditions);    
	$cnt = count($foundArray);
	
	if(is_array($foundArray) && count($foundArray)>0 ) {  
      echo json_encode($foundArray);
    }
    else {
      echo 0;
    }

?>
