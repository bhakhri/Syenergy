<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE CITY LIST
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AttendanceSetMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['setId'] ) != '') {
    require_once(MODEL_PATH . "/AttendanceSetManager.inc.php");
    $foundArray = AttendanceSetManager::getInstance()->getAttendanceSet(' WHERE attendanceSetId="'.trim($REQUEST_DATA['setId']).'"');
      if(is_array($foundArray) && count($foundArray)>0 ) {
            if($foundArray[0]['evaluationCriteriaId']==PERCENTAGES){
            $foundArray[0]['evaluationCriteriaId']=1;
      }
      else if($foundArray[0]['evaluationCriteriaId']==SLABS){
            $foundArray[0]['evaluationCriteriaId']=2;
      }
      else{
            $foundArray[0]['evaluationCriteriaId']=-1;
      }
      echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetValues.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 29/12/09   Time: 13:38
//Created in $/LeapCC/Library/AttendanceSet
//Added  "Attendance Set Module"
?>