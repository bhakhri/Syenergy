<?php
//--------------------------------------------------------------------------------------------------------------
// THIS FILE IS USED TO POPULATE test details(testAbbr,testTopic,maxMarks,testDate,testIndex) List
// Author : Dipanjan Bhattacharjee
// Created on : (23.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TestMarks');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
    
    $classId = trim($REQUEST_DATA['classId'] );
    $testTypeId = trim($REQUEST_DATA['testTypeId'] );
    $subjectId = trim($REQUEST_DATA['subjectId'] );
    $groupId = trim($REQUEST_DATA['groupId'] );

    $newTestIndexArray = $studentManager->getNewTestIndexNew($classId, $subjectId, $groupId, $testTypeId);
    $testIndex='0';
    for($i=0;$i<count($newTestIndexArray);$i++) {
       $ttTestIndex = trim($newTestIndexArray[$i]['testIndex']);  
       if($ttTestIndex > $testIndex) {
         $testIndex= $ttTestIndex; 
       }
    }
    $foundArray[0]['testIndex']= $testIndex;
    
    echo json_encode($foundArray);  
    

?>