<?php
//--------------------------------------------------------------------------------------------------------------  
// Purpose: To fetch attendance record of a student from the database
//
// Author : Dipanjan Bhattacharjee
// Created on : (15.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------  
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH."/CommonQueryManager.inc.php");
    UtilityManager::ifTeacherNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
    $teacherManager = ScTeacherManager::getInstance();
    $commonAttendanceArr = CommonQueryManager::getInstance();

    $startDate2 = $REQUEST_DATA['startDate2'];
    $endDate2 = $REQUEST_DATA['endDate2'];
    $studentId = $REQUEST_DATA['studentId'];
    
    if($startDate2)
        $conditions = " AND ( fromDate BETWEEN '$startDate2' AND '$endDate2' ";

    if($endDate2)
        $conditions .= " OR  toDate BETWEEN '$startDate2' AND '$endDate2' )";

    $conditions .= " AND scs.studentId=". $studentId;
    
    $studentSubjectArray = $commonAttendanceArr->getScAttendance1($conditions);
    $cnt = count($studentSubjectArray);
    $json_val ="";
     
    for($i=0;$i<$cnt;$i++) {
        if($studentSubjectArray[$i]['delivered'] >0 and $studentSubjectArray[$i]['attended'] >0)
            $percentageAtt = round(($studentSubjectArray[$i]['attended']/$studentSubjectArray[$i]['delivered'])*100,2);
        else
            $percentageAtt = "0";

       $valueArray = array_merge(array('percentageAtt' =>  $percentageAtt,'srNo' => ($records+$i+1) ),$studentSubjectArray[$i]);
        
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
     
     
    echo '{"info" : ['.$json_val.']}'; 
    
// for VSS
// $History: scAjaxInitAttendance.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScStudentActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 10/20/08   Time: 6:12p
//Updated in $/Leap/Source/Library/Teacher/ScStudentActivity
//Mofified attendance and grades showing
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/17/08    Time: 2:23p
//Updated in $/Leap/Source/Library/Teacher/ScStudentActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/16/08    Time: 1:40p
//Updated in $/Leap/Source/Library/Teacher/ScStudentActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Library/Teacher/ScStudentActivity
?>
