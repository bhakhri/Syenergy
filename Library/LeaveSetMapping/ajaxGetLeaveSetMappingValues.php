<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE  Leave Set Mapping Values
// Author : Parveen Sharma
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/LeaveSetMappingManager.inc.php");     
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
   $leaveSessionId = trim($REQUEST_DATA['leaveSessionId']);
   $leaveSet = trim($REQUEST_DATA['leaveSet']);
    
   if($leaveSessionId=='') {
     $leaveSessionId=0;  
   }
    
   if($leaveSet=='') {
     $leaveSet=0;  
   }
    
   $condition = " WHERE leaveSetId= $leaveSet AND leaveSessionId = $leaveSessionId "; 
   $foundArray = LeaveSetMappingManager::getInstance()->getLeaveSetMapping($condition);
   if(is_array($foundArray) && count($foundArray)>0 ) {  
      echo json_encode($foundArray);
   }
   else {
      echo 0;
   }
?>