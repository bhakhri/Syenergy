<?php
//  This File checks  whether record exists in Subject Form Table
//
// Author :Arvind Singh Rawat
// Created on : 14-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
global  $sessionHandler;
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2) {
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else if($roleId==3) {
  UtilityManager::ifParentNotLoggedIn(true);
}
else if($roleId==4) {
  UtilityManager::ifStudentNotLoggedIn(true); 
}
else{
  UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");     
    $studentReportsManager = StudentReportsManager::getInstance();  

    $periodValue = $REQUEST_DATA['id']+3;
    $subjectId = $REQUEST_DATA['subjectId'];
    $currentClassId = $REQUEST_DATA['currentClassId'];  
        
    $condition=" AND s.subjectId  = '".$REQUEST_DATA['subjectId']."' 
                 AND sp.periodValue = '".$periodValue."'";
                 
    $foundArray = $studentReportsManager->getShowCourseCredits($condition,$currentClassId);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
      echo json_encode($foundArray[0]);
    }
    else {
      echo 0;
    }
?>

