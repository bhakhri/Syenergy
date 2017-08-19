<?php

//This file calls Listing Function and creates Global Array in "DashBoard of Parent " Module 
//
// Author :Arvind Singh Rawat
// Created on : 14-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   //global attendance parameter
    $GlobalAttendanceParameter ='50';
    global $FE;   
    require_once(MODEL_PATH."/CommonQueryManager.inc.php");  
    require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
    $studentInformationManager = StudentInformationManager::getInstance();

    require_once(MODEL_PATH . "/Parent/ParentManager.inc.php");
    $parentManager = ParentManager::getInstance();
    
    require_once(MODEL_PATH . "/DashBoardManager.inc.php"); 
    $dashboardManager = DashBoardManager::getInstance(); 
    
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifParentNotLoggedIn();
    
    
    $studentId= $sessionHandler->getSessionVariable('StudentId');
    $classIdArray = CommonQueryManager::getInstance()->getStudyPeriodData($studentId);
    $classId = $classIdArray[count($classIdArray)-1]['classId'];
    $holdAttendance = $classIdArray[$cnt]['holdAttendance'];
	$holdTestMarks = $classIdArray[$cnt]['holdTestMarks'];
    if($studentId=='') {
      $studentId=0; 
    }
    
    if($classId=='') {
      $classId=0; 
    }
    //********For Notices**************


    $noticeRecordArray = $studentInformationManager->getInstituteNotices1();
    //********For Notice(ends)**************
    
    //********For Events**************
 
    $eventRecordArray = $studentInformationManager->getEventList1();
    //********For Events(ends)**************
    
    $totalMessages=$parentManager->getCommentsListing1();

    $adminMessages=$parentManager->getAdminMessages1(); 

	$showTask=$studentInformationManager->getTaskMessages();

    if($_SESSION['ParentAlerts']['view']=='1') {
      $timeTableMessages = $studentInformationManager->getStudentTimeTable1($classId);
    }

 /* $studentAttendanceArray = $studentInformationManager -> getAttendanceDashboard($studentId,$classId);
    $strList1 ="";
    $cnt = count($studentAttendanceArray);
    $strList1 .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
    $strList1 .="<series>\n";
    for($i=0;$i<$cnt;$i++) {
        $strList1 .= "<value xid='".$i."'>".$studentAttendanceArray[$i]['subjectCode']."</value>\n";
    }
    $strList1 .="</series>\n";
    $strList1 .="<graphs>\n";
    for($k=1;$k<2;$k++) {
        $strList1 .="<graph gid='".$k."'>\n";
        for($i=0;$i<$cnt;$i++) {
            $percentage = "0.00";
			$threshold = $sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');
            if($studentAttendanceArray[$i]['delivered']) {
                $percentage = number_format((($studentAttendanceArray[$i]['attended']/$studentAttendanceArray[$i]['delivered'])*100),2,'.','');
            }
		//////These changes done by Jaineesh on 10.02.10
            if($percentage < $threshold) {
				$strList1 .= "<value xid='".$i."' >".$percentage."</value>\n";  
			}
            else {
				$strList1 .= "<value xid='".$i."' color='#42A342'>".$percentage."</value>\n";
            }
        }
        $strList1 .="</graph>\n";
    }
    $strList1 .="</graphs>\n";
    $strList1 .="</chart>";
    $xmlFilePath = TEMPLATES_PATH."/Xml/studentAvgAttendanceBarData.xml";
    if(is_writable($xmlFilePath)){
        $handle = @fopen($xmlFilePath, 'w');
        if (@fwrite($handle, $strList1) === FALSE){
            die("unable to write");
        }
    }
    else{
         
        logError("unable to open avg att data xml file");
    }
*/

    $studentAttendanceArray=array(); 
/* Committed: 02-Nov-12
    $studentAttendanceArray=array();
    $str = "<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";
    $series = "<series>\n";
    $graphs = "<graphs>\n";
    $graph1 = "<graph gid='1'>\n";
    $graph2 = "<graph gid='2'>\n";
    $valueStr = "";
    if($blockedFlag==0){   
        if($studentId != "" AND $classId != '0') {  
            $getStudentId = $studentId;
            $getClassId = $classId;
            $i = 0;
            $teacherSubjectsArray = $dashboardManager->getClassSubjects($getClassId);
            foreach ($teacherSubjectsArray as $record) {
                $subjectCode = $record['subjectCode'];
                $subjectId = $record['subjectId'];
                $series .= "<value xid='$i'>$subjectCode</value>\n";
                $activityRecordArray = CommonQueryManager::getInstance()->getConsolidatedStudentAttendance($studentId,$classId,''," AND att.subjectId = $subjectId",'ORDER BY subjectCode');
                $per = 0;
                if(count($activityRecordArray) > 0 ) {
                  if($activityRecordArray[0]['delivered']==0) {
                    $per = 0;   
                  } 
                  else {
                    $per = number_format((($activityRecordArray[0]['attended']+$activityRecordArray[0]['dutyLeave'])/$activityRecordArray[0]['delivered']*100),2,'.',''); 
                  }
                }
                $valueStr .= "<value xid='$i' title='$per'>$per</value>\n<value xid='$i' bullet='round'>$per</value>\n";
                $i++;
            }
        }
    }
    $series .= "</series>";
    $text = $str . $series . $graphs . $graph1 . $valueStr . "</graph>\n" . $graph2 . $valueStr . "</graph>\n" . "</graphs>\n</chart>\n";
    $strList = $text;
    $graphs = "</graphs>";
    
    $xmlFilePath = TEMPLATES_PATH."/Xml/studentAvgAttendanceBarData.xml"; 
    if(is_writable($xmlFilePath)){
        $handle = @fopen($xmlFilePath, 'w');
        if (@fwrite($handle, $strList) === FALSE){
            die("unable to write");
        }
    }
    else{
        logError("unable to open user activity data xml file");
    }
*/

////------------------------------------------------------------------------------------------    
///////                        FEE ALERT                              ////////////////////////
////------------------------------------------------------------------------------------------        
    if($_SESSION['ParentAlerts']['view']=='1') {
      $totalFeeStatus = $studentInformationManager->getFeeStatus();
     if($holdAttendance==0){
	         $attendanceShortArray = $studentInformationManager->getShortAttendance($classId,'');
		}
      if($holdTestMarks==0){
	         $testMartsArray = $studentInformationManager->getStudentMarks1($classId);
		}
    }

    
/// ---------------------- BIRTHDAY WISHES ------------------------------

    $birthDayTakenRecordArray = $studentInformationManager->checkBirthDay();
    $greetingMsg="";
    if($birthDayTakenRecordArray[0]['birthDay'] >0){
        $greetingMsg="WISH YOU A VERY HAPPY BIRTHDAY";
    }
    else {
        $greetingMsg = "Welcome";
        }

//$History: initDashboard.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 2/10/10    Time: 5:22p
//Updated in $/LeapCC/Library/Parent
//show attendance activities graph according to active class and
//attendance threshold
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/18/09    Time: 6:22p
//Updated in $/LeapCC/Library/Parent
//formating, validations & conditions updated
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 4/21/09    Time: 1:30p
//Updated in $/LeapCC/Library/Parent
//put the task files in parent module
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/09/09    Time: 4:33p
//Updated in $/LeapCC/Library/Parent
//dashboard message settings
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Parent
//
//*****************  Version 10  *****************
//User: Arvind       Date: 9/26/08    Time: 5:21p
//Updated in $/Leap/Source/Library/Parent
//modify
//
//*****************  Version 9  *****************
//User: Arvind       Date: 9/26/08    Time: 4:23p
//Updated in $/Leap/Source/Library/Parent
//modified the function name getStudentMarksClass()  from
//getScStudentMarksClass()
//
//*****************  Version 8  *****************
//User: Arvind       Date: 9/17/08    Time: 6:46p
//Updated in $/Leap/Source/Library/Parent
//modified the events functions
//
//*****************  Version 7  *****************
//User: Arvind       Date: 8/08/08    Time: 3:07p
//Updated in $/Leap/Source/Library/Parent
//modified the parameter of getattendance
//
//*****************  Version 6  *****************
//User: Arvind       Date: 7/31/08    Time: 6:30p
//Updated in $/Leap/Source/Library/Parent
//changed the path of ParentManager file
//
//*****************  Version 5  *****************
//User: Arvind       Date: 7/30/08    Time: 7:27p
//Updated in $/Leap/Source/Library/Parent
//modified the div's
//
//*****************  Version 4  *****************
//User: Arvind       Date: 7/28/08    Time: 6:54p
//Updated in $/Leap/Source/Library/Parent
//modified the Calculate Attenmdance Section
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/17/08    Time: 6:26p
//Updated in $/Leap/Source/Library/Parent
//added new functions for ALERT Display in dashboard modlue
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/17/08    Time: 3:35p
//Updated in $/Leap/Source/Library/Parent
//removed Header clases
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/17/08    Time: 12:08p
//Created in $/Leap/Source/Library/Parent
//initial checkin


?>
