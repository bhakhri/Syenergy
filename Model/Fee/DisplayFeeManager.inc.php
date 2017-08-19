<?php 
//-------------------------------------------------------
//  THIS FILE IS USED FOR DB OPERATION FOR "student and teacher_comment" TABLE
// Author :Nishu Bindal
// Created on : (8.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId

class DisplayFeeManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "StudentConcessionManager" CLASS
//
// Author :Nishu Bindal
// Created on : (8.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------      
	private function __construct(){
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "StudentConcessionManager" CLASS
//
// Author :Nishu Bindal
// Created on : (8.Feb.2012)
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------       

	public function showFeeAlert(){
		global $sessionHandler;
        	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        	$sessionId = $sessionHandler->getSessionVariable('SessionId');
		
		$query = "SELECT  count(feeCycleId) AS cnt 
				FROM	`fee_cycle_new` 
				WHERE	instituteId = '$instituteId'
					AND now('Y-m-d') between fromDate and toDate";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}
    
}

// $History: StudentConcessionManager.inc.php $
?>
