<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE Parent Categories 
// Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_TeacherMapping');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['mappingId'] ) != '') {
    require_once(MODEL_PATH . "/FeedBackTeacherMappingManager.inc.php");
    
    $foundArray=FeedBackTeacherMappingManager::getInstance()->fetchMappedValuesNew("'".trim($REQUEST_DATA['mappingId'])."'");
    if(is_array($foundArray) and count($foundArray)>0){
        $selectionString='';
        $cnt=count($foundArray);
        for($i=0;$i<$cnt;$i++){
          if($selectionString!=''){
              $selectionString .=" , ";
          }
          $selectionString .="'".$foundArray[$i]['timeTableLabelId']."~".$foundArray[$i]['classId']."~".$foundArray[$i]['groupId']."~".$foundArray[$i]['subjectId']."'";
        }
        $foundArray2=FeedBackTeacherMappingManager::getInstance()->fetchMappedValues($selectionString);
        if(is_array($foundArray2) and count($foundArray2)>0){
         echo json_encode($foundArray2);
         die;
        }
        else{
          echo 0;
          die;  
        }
    }
    else{
        echo 0;
        die;
    }
}
else{
    echo 0;
    die;
}
// $History: ajaxGetMappedTeachers.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 1/02/10    Time: 16:33
//Created in $/LeapCC/Library/FeedbackAdvanced
//Created "Class->Group->Subject->Teacher" mapping module for "Adv.
//Feedback Modules"
?>