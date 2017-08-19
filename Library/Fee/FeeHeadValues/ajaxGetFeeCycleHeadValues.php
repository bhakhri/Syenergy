<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE QUOTA Seats LIST
//
// Author : Nishu Bindal
// Created on : (6.feb.2012 )
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeHeadValuesNew');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/Fee/FeeHeadValuesManager.inc.php");   
$feeHeadValuesManager = FeeHeadValuesManager::getInstance(); 
    
/* $feeCycleId = $REQUEST_DATA['feeCycleId'];
    if($feeCycleId=='') {
      $feeCycleId=0;  
    }
*/    
    global $sessionHandler;
        
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');     
    $sessionId = $sessionHandler->getSessionVariable('SessionId');
	$classId = $REQUEST_DATA['classId'];
    
	$condition = " 	fh.classId = '$classId'              
		            AND	ff.isSpecial=0
		            AND	fh.instituteId = '$instituteId'
		            AND	fh.sessionId = '$sessionId'";
		
    $foundArray = $feeHeadValuesManager->getFeeCycleHeadList($condition);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
      echo json_encode($foundArray);
    }
    else {
      echo 0;
    }
?>


