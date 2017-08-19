<?php
//-------------------------------------------------------
//  This File is used for fetching Batches
// Author :Nishu Bindal
// Created on : 6-Feb-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache(); 
	$rollNo = trim($REQUEST_DATA['rollNo']);
	$condition = '';
    require_once(MODEL_PATH . "/Fee/StudentAdhocConcessionManager.inc.php");
    $StudentAdhocConcessionManager = StudentAdhocConcessionManager::getInstance();

	 $migrationStudyPeriod=0;
        $migratedArray = $StudentAdhocConcessionManager->getStudentMigrationCheck($rollNo);
	
	$migrationStudyPeriod=$migratedArray[0]['migrationStudyPeriod'];
	
	 $ttIsLeet = $migratedArray[0]['isLeet']; 

	 if($migrationStudyPeriod=='') {
          $migrationStudyPeriod=0;   
        }     
        
        if($migrationStudyPeriod==0) {
          if($ttIsLeet=='1') {
             $migrationStudyPeriod = 3; 
          }
        }
	
	
		if($rollNo != ''){
	
		$classArray = $StudentAdhocConcessionManager->fetchClases($rollNo,$migrationStudyPeriod);
		if(count($classArray) > 0 && is_array($classArray)) {
			echo json_encode($classArray);
		}
		else {
			echo 0;
		}
	}
	else{
		echo 0;
	}
?>
