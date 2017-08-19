<?php
//--------------------------------------------------------------------------------------------------------------
// THIS FILE IS USED TO POPULATE test details(testAbbr,testTopic,maxMarks,testDate,testIndex) List
// Author : Dipanjan Bhattacharjee
// Created on : (23.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['testTypeId'] ) != '' and trim($REQUEST_DATA['classId'] ) != '' and trim($REQUEST_DATA['groupId'] ) != '' and trim($REQUEST_DATA['subjectId'] ) != '') {
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $foundArray = TeacherManager::getInstance()->getMaxTestIndex($REQUEST_DATA['testTypeId']);
    if(is_array($foundArray) && count($foundArray)>0 ) {  
        echo json_encode($foundArray[0]);
    }
    else {
        echo 0;
    }
}
else{
    echo 0;
}
// $History: ajaxGetMaxTestId.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/09/09    Time: 16:58
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Fixed Query Error : 
//
//1.SELECT testId,testAbbr,testIndex FROM test WHERE testTypeCategoryId=1
//AND subjectId=18 AND classId=87 AND groupId= AND sessionId=1 AND
//instituteId=1 ORDER BY testId DESC
//
//2.SELECT IF(MAX(testIndex) IS NULL ,0,MAX(testIndex)) AS testIndex FROM
//test WHERE testTypeCategoryId=1 AND subjectId=18 AND classId=87 AND
//groupId= AND sessionId=1 AND instituteId=1
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/04/08    Time: 6:03p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
?>