<?php
//-------------------------------------------------------
// Purpose: To count the students in class
//
// Author : Ajinder Singh
// Created on : (11-June-2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','add');
	UtilityManager::ifNotLoggedIn();
    
    require_once(MODEL_PATH . "/StudentFeeConcessionMappingManager.inc.php");
    $studentFeeConcessionManager = StudentFeeConcessionMappingManager::getInstance(); 

	$feeClassId = $REQUEST_DATA['feeClassId'];
	if($feeClassId == '') {
	  $feeClassId=0;	
	} 
                               
	$cateogryArray =  $studentFeeConcessionManager->getClassWiseConcessionList($feeClassId);
	
	echo json_encode($cateogryArray);
    die;
?>