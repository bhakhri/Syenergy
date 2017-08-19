<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of institute notices  and show it in dashboard
//
// Author : Dipanjan Bbhattacharjee
// Created on : (22.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifTeacherNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
    $teacherManager = ScTeacherManager::getInstance();

    //********For BirthDay wishes**************
    $birthDayTakenRecordArray = $teacherManager->checkBirthDay();
    $greetingMsg="";
    if($birthDayTakenRecordArray[0]['birthDay'] >0){
        $greetingMsg="Happy BirthDay.............";
    }
    //********For For BirthDay wishes(ends)**************
    
    
    //********For MarriageDay wishes**************
    $marriageDayTakenRecordArray = $teacherManager->checkMarriageDay();
    if($marriageDayTakenRecordArray[0]['marriageDay'] >0){
       if($greetingMsg==""){ 
        $greetingMsg ="Happy Marriage Anniversary.............";
       } 
       else{
           $greetingMsg .=" and Happy Marriage Anniversary.............";
       } 
    }
    if($greetingMsg != ""){
        $greetingMsg .=$sessionHandler->getSessionVariable('EmployeeName');
    }
    
    //********For For MarriageDay wishes(ends)**************
    
    /* Check for whether time has been changed on this day for this teacher*/
    
    $timeTableAlert="";
    $timeTableAlertRecordArray= $teacherManager->getTeacherTimeTableAlert(); 
    if($timeTableAlertRecordArray[0]['talert'] >0){
      $timeTableAlert='<a href="scListTimeTable.php" title="please re-look at your TimeTable"><u>TimeTable has been changed , please re-look at your TimeTable</u></a>';  
    }
    
    /* Check for whether time has been changed on this day for this teacher*/
    
    
    $limit      = ' LIMIT 0,5';  //showing first three records
   //********For Attendance Not Taken**************
    
    $filter="";
     ////////////   
    $attendanceNotTakenRecordArray = $teacherManager->checkAttendanceNotTaken($filter,$limit);
    //********For Attendance Not Taken(ends)**************
    
    
    
    //********For Notices**************
   // $limit      = ' LIMIT 0,3';  //showing first three records
    $curDate=date('Y')."-".date('m')."-".date('d');
    $filter=" AND ( '$curDate' >= n.visibleFromDate AND '$curDate' <= n.visibleToDate)";  
     ////////////   
    //$totalArray = $teacherManager->getTotalNotice($filter);
    $noticeRecordArray = $teacherManager->getNoticeList($filter,$limit,'n.visibleFromDate DESC');
    //********For Notice(ends)**************
    
    
    
    //********For Events**************
    //$limit      = ' LIMIT 0,3';  //showing first three records
    $curDate=date('Y')."-".date('m')."-".date('d');
    //$filter=" AND ( '$curDate' >= e.startDate AND '$curDate' <= e.endDate)";  
    $filter =" AND DATE_SUB(e.startDate,INTERVAL ".EVENT_DAY_PRIOR." DAY) <=CURDATE() AND e.endDate>=CURDATE() ";

     ////////////   
    //$totalArray = $teacherManager->getTotalEvent($filter);
    $eventRecordArray = $teacherManager->getEventList($filter,$limit,'e.startDate DESC');
    //********For Events(ends)**************
    
    //*************For Message**************
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $mlimit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE_ADMIN_MESSAGE_EMPLOYEE;
        
     //////////// 
    $filter="";
       
    $totalArray = $teacherManager->getTotalAdminMessage($filter);
    $msgRecordArray = $teacherManager->getAdminMessageList($filter,$mlimit,' adm.dated DESC');
    

    
    
// $History: scDashBoardList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScTeacherActivity
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 10/21/08   Time: 11:54a
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Added code for time table alerts
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/29/08    Time: 5:48p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/24/08    Time: 1:36p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Corrected date range in event showing criteria
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
?>