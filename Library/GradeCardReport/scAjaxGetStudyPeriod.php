<?php
//-------------------------------------------------------------------------------------
//  This File contains Validation and ajax function used in all Trimester.
//
// Author :PArveen Sharma
// Created on : 21-May-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
	define('MANAGEMENT_ACCESS',1);
    
    
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();  
    
    require_once(MODEL_PATH . "/GradeCardRepotManager.inc.php");
    $gradeCardRepotManager = GradeCardRepotManager::getInstance();
    
    $degreeId = $REQUEST_DATA['degreeId'];     
    $branchId = $REQUEST_DATA['branchId'];     
    $batchId = $REQUEST_DATA['batchId'];  
    
    $conditions = " AND c.degreeId= '$degreeId'  AND c.batchId = '$batchId' AND c.branchId = '$branchId' ";
    $foundArray =  $gradeCardRepotManager->getStudentPeriodData($conditions);
    echo json_encode($foundArray);          
    
?>