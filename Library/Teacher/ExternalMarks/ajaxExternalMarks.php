<?php
//-------------------------------------------------------
// THIS FILE IS USED TO upload student photo for subject centric
//
// Author : Jaineesh
// Created on : 05-Feb-2010
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//                      
//--------------------------------------------------------
	ob_start();
	set_time_limit(0);
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/StudentManager.inc.php");
	define('MODULE','MannualExternalMarks');
	define('ACCESS','add');
	global $sessionHandler; 
    $roleId = $sessionHandler->getSessionVariable('RoleId');
    if($roleId=='2') {      // Teacher Login
      UtilityManager::ifTeacherNotLoggedIn(true); 
    }
    else {
      UtilityManager::ifNotLoggedIn(true); 
    }
    UtilityManager::headerNoCache();
    
    
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
	
    
    $stu = $REQUEST_DATA['stu'];    
    $extMks = $REQUEST_DATA['extMks'];    
    $classId = trim($REQUEST_DATA['class']);
    $subjectId = trim($REQUEST_DATA['subject']);
    $groupId = trim($REQUEST_DATA['group']);
    $testTypeId = trim($REQUEST_DATA['testType']);
    $maxMarks = $REQUEST_DATA['maxMarks'];
     
    
    $conductingAuthority='2';
    $find='0';
    for ($i = 0; $i < count($stu); $i++) {
        $studentId = $stu[$i];
        $maxValue = trim($extMks[$i]);    
        
        if($maxValue!='')
            if($maxValue <= $maxMarks) {
                $subjectArray = $studentManager->getSingleField("`".TOTAL_TRANSFERRED_MARKS_TABLE."`", 
                         'COUNT(*) AS cnt',  "WHERE subjectId = '$subjectId' AND  classId = '$classId' AND conductingAuthority='2' AND studentId='$studentId' ");
                $marksScoredStatus='Marks';
                if($subjectArray[0]['cnt']=='0') {                    
                $returnStatus = $studentManager->addTransferredMarks($conductingAuthority,$studentId,$classId,$subjectId,$maxMarks,$maxValue,$marksScoredStatus);    
                 $find='1';  
                }
                else {
                   $returnStatus = $studentManager->updateTransferredMarks($conductingAuthority,$studentId,$classId,$subjectId,$maxMarks,$maxValue,$marksScoredStatus);       
                    $find='1';  
                }
               
                if($returnStatus === false) {
                    echo FAILURE;
                    die;
                }
              
            }
    }
    
    if($find=='1') {
      echo SUCCESS;  
    }         
    else {
      echo "Data could not be updated. Please try again";
    }
    
   
   ?>
