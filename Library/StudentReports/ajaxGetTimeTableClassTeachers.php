<?php
//-------------------------------------------------------
// This File is used for fetching class for 
// Author :Dipanjan Bhattacharjee
// Created on : 07.08.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
	UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
	$labelId = $REQUEST_DATA['labelId'];
    $classId = $REQUEST_DATA['classId'];
    $groupId = $REQUEST_DATA['groupId'];
    $chkHierarchy=trim($REQUEST_DATA['chkHierarchy']);
    
    if($chkHierarchy=='') {
      $chkHierarchy=0;  
    }
    
    if($labelId=='' or $classId=='' or $groupId ==''){
        echo 'Required Parametes Missing';
        die;
    }
    
    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportsManager = StudentReportsManager::getInstance();
    
    $conditions='';
    if($groupId!=-1){
       //find group hierarchy
       if($chkHierarchy==1) { 
         $groupHierarchyString=$studentReportsManager->getGroupHierarchy($classId,$groupId);
         $conditions=' AND att.groupId IN ('.$groupHierarchyString.')';
       }
       else {
         $conditions .=' AND att.groupId IN ('.$groupId.')';   
       }
    }
    
    
	$classArray = $studentReportsManager->getTimeTableClassTeachers($labelId,$classId,$conditions);
    if(is_array($classArray) and count($classArray)>0){
	    echo json_encode($classArray);
        die;
    }
    else{
        echo 0;
        die;
    }

// $History: ajaxGetTimeTableClassTeachers.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 16/04/10   Time: 10:22
//Created in $/LeapCC/Library/StudentReports
//Created "Teacher Attendance Report".This report is used to see total
//lectured delivered by a teacher for a subject within a specified date
//interval.
?>