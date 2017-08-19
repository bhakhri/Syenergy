<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE group LIST
// Author : Parveen Sharma
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

require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
$studentReport = StudentReportsManager::getInstance();    
    
if(trim($REQUEST_DATA['degree'] ) != '' && trim($REQUEST_DATA['studyPeriodId']) != '') {
    $arr = explode('-',$REQUEST_DATA['degree']);
    $universityId = $arr[0];
    $degreeId = $arr[1];
    $branchId = $arr[2];
    $studyPeriodId = $REQUEST_DATA['studyPeriodId'];
                                                  
    
    $tableName = ' `group` grp, class c';
    $fieldName = ' DISTINCT grp.groupId, grp.groupName';

    $condition = " WHERE c.classId = grp.classId AND c.universityId ='".$arr[0]."' AND c.degreeId='".$arr[1]."' AND c.branchId='".$arr[2]."'"; 
    $condition .= " AND c.studyPeriodId='".$studyPeriodId."'";

    $foundArray = $studentReport->getSingleField($tableName, $fieldName, $condition);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray);
    }
    else {
        echo 0;
    }
}
// $History: ajaxGetGroup.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/04/09   Time: 6:53p
//Created in $/LeapCC/Library/AdminTasks
//initial checkin
//

?>