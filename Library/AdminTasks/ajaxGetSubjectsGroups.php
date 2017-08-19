<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE subject and groups lists
// Author : Dipanjan Bhattacharjee
// Created on : (14.04.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['timeTableLabelId'] ) != '' and trim($REQUEST_DATA['classId'] ) != '') {
    require_once(MODEL_PATH . "/AdminTasksManager.inc.php");
    $results = AdminTasksManager::getInstance()->getClassSubject(' sub.subjectCode'," AND subTocls.classId='".$REQUEST_DATA['classId']."' AND tt.timeTableLabelId=".$REQUEST_DATA['timeTableLabelId']." AND sub.subjectId = subTocls.subjectId");
    
    $resultsCount = count($results);
    if(is_array($results) && $resultsCount>0) {
        $jsonSubjects  = '';
        for($s = 0; $s<$resultsCount; $s++) {
            $jsonSubjects .= json_encode($results[$s]). ( $s==($resultsCount-1) ? '' : ',' );  
        }
    }

  /*
    $results1 = AdminTasksManager::getInstance()->getClassGroups(' groupName'," AND g.classId='".$REQUEST_DATA['classId']."' AND tt.timeTableLabelId=".$REQUEST_DATA['timeTableLabelId']);
    $results1Count = count($results1);
    if(is_array($results1) && $results1Count>0) {
        $jsonGroups  = '';
        for($s = 0; $s<$results1Count; $s++) {
            $jsonGroups .= json_encode($results1[$s]). ( $s==($results1Count-1) ? '' : ',' );                }
    }
  */  
    //echo '{"subjectArr":['.$jsonSubjects.'],"groupArr":['.$jsonGroups.']}';
    echo '{"subjectArr":['.$jsonSubjects.']}';
}
else{
    echo 0;
}
// $History: ajaxGetSubjectsGroups.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/AdminTasks
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 14/04/09   Time: 17:21
//Created in $/LeapCC/Library/AdminTasks
//Created Attendance Delete Module
?>