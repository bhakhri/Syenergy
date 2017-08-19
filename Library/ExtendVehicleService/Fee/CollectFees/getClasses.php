<?php
//-------------------------------------------------------
// This File is used for fetching Classes
// Author :Nishu Bindal
// Created on : 4-April-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache(); 
	
    require_once(MODEL_PATH . "/Fee/CollectFeesManager.inc.php");   
	$CollectFeesManager = CollectFeesManager::getInstance(); 
    
    $rollNoRegNo = trim($REQUEST_DATA['rollNoRegNo']);
    $condition = '';
    
    
	if($rollNoRegNo != ''){
	    //migration check for student for previous classes fee
	    $migrationStudyPeriod=0;
        $migratedArray = $CollectFeesManager->getStudentMigrationCheck($rollNoRegNo);
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
        
		$classArray = $CollectFeesManager->fetchClases($rollNoRegNo,$migrationStudyPeriod);
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
