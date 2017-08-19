<?php

//The file contains data base functions work on dashboard
//
// Author : Jaineesh
// Created on : 23.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifStudentNotLoggedIn(true);

    //global attendance parameter
    $GlobalAttendanceParameter ='50';

    require_once(MODEL_PATH."/CommonQueryManager.inc.php");
	require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
    $studentInformationManager = StudentInformationManager::getInstance();

    require_once(MODEL_PATH . "/DashBoardManager.inc.php");
    $dashboardManager = DashBoardManager::getInstance();

	$studentId= $sessionHandler->getSessionVariable('StudentId');
	$classIdArray = CommonQueryManager::getInstance()->getStudyPeriodData($studentId);
    $cnt = count($classIdArray)-1;
	$classId = $classIdArray[$cnt]['classId'];
	$holdAttendance = $classIdArray[$cnt]['holdAttendance'];
	$holdTestMarks = $classIdArray[$cnt]['holdTestMarks'];
	

    if($classId=='') {
      $classId=0;
    }


    $blockedFlag=0;
    //if student login is disabled due to incomplete feedback
    if($sessionHandler->getSessionVariable('UserIdDisabledForInCompleteFeedback')==2){
       $blockedFlag=1;
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




    //********For Notices**************


    $noticeRecordArray = $studentInformationManager->getInstituteNotices1();


    //********For Notice(ends)**************

	//********For Events**************

    //$eventRecordArray = $studentInformationManager->getEventList1();
    if($blockedFlag==0){
     $resourceRecordArray = $studentInformationManager->getStudentCourseResourceList($studentId,$classId,'','courseResourceId DESC',$limit='LIMIT 0,5');
    }
    //********For Events(ends)**************

	$totalMessages=$studentInformationManager->getCommentsListing1();

	$adminMessages=$studentInformationManager->getAdminMessages1();

	$showTask=$studentInformationManager->getTaskMessages();

    if($_SESSION['StudentAllAlerts']['view']==1) { 
	  $timeTableMessages = $studentInformationManager->getStudentTimeTable1($classId);
    }
    
    $studentAttendanceArray=array();
    /*   committed: 02-Nov-12
        if($blockedFlag==0){
	      //$studentAttendanceArray = $studentInformationManager -> getAttendanceDashboard($studentId,$classId);
        }
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
                    //$activityRecordArray = array();
                   // $activityRecordArray = CommonQueryManager::getInstance()->getConsolidatedStudentAttendance($studentId,$classId,''," AND att.subjectId = $subjectId",'ORDER BY subjectCode');
                 $attCondition = " AND att.classId ='$classId' AND att.subjectId ='$subjectId' AND att.studentId = '$studentId' "; 
                 $activityRecordArray = CommonQueryManager::getInstance()->getFinalAttendance($attCondition,'','1','','',$classId,$subjectId,$studentId);
                   $per  = $activityRecordArray[0]['percentage'];
                   if($per=='') {
                     $per=NOT_APPLICABLE_STRING;  
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

/*
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


            if($percentage < $threshold)
            {
            $strList1 .= "<value xid='".$i."' >".$percentage."</value>\n";
			}
            else{
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


////------------------------------------------------------------------------------------------
///////                        FEE ALERT                              ////////////////////////
////------------------------------------------------------------------------------------------
        
        if($_SESSION['StudentAllAlerts']['view']==1) {
            $feeArray = array();
            if($_SESSION['FEE_ALERT_IN_STUDENT_LOGIN'] == 1){
                $feeArray = $studentInformationManager->showFeeAlert();
            }
            $totalFeeStatus = $studentInformationManager->getFeeStatus();

            $attendanceShortArray=array();
            if($blockedFlag==0){
		if($holdAttendance==0){
	         $attendanceShortArray = $studentInformationManager->getShortAttendance($classId,'');
		}
            }

	        $testMartsArray=array();
            if($blockedFlag==0){
		if($holdTestMarks==0){
	         $testMartsArray = $studentInformationManager->getStudentMarks1($classId);
		}
            }

            /************CODE FOR SHOWING ALERT FOR Remaining EXPECTED DATE OF CHECKOUT*********/
              $dateInterval=7;
              $expectedDateArray=array();
              if($studentId!=''){
               $expectedDateArray=$studentInformationManager->getExpectedDateOfCheckOut($studentId);
              }
              $expectedDateString='';
              if(is_array($expectedDateArray) and count($expectedDateArray)>0){
                 if($expectedDateArray[0]['daysLeft']<=$dateInterval){
                   $linkString=$expectedDateArray[0]['daysLeft'].' days(s) remaining to checkout from hostel';
                   //$expectedDateString='<a href="Javascript:void(0);" class="redLink" title="'.$linkString.'">'.$linkString.'</a>';
                   $expectedDateString='<span style="font-size: 11px;font-weight: bold;color: #bb0000" >'.$linkString.'</span>';
                 }
              }
         }
        /************CODE FOR SHOWING ALERT FOR Remaining EXPECTED DATE OF CHECKOUT*********/



//$History: initDashboard.php $
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 3/04/10    Time: 11:29a
//Updated in $/LeapCC/Library/Student
//Modified code so that when students are blocked due to incomplete
//feedback ,all alerts except attendance and marks are shown.
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 12/02/10   Time: 13:51
//Updated in $/LeapCC/Library/Student
//Modified Student Dashboard---Now Only "Uploaded Reources" and
//"Attendance Activities" Information will be hidden from "Student" if
//he/she failed to complete feedback.
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 6/02/10    Time: 18:21
//Updated in $/LeapCC/Library/Student
//Made modifications in Feedback modules---Added block/unblock feature
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 12/21/09   Time: 5:58p
//Updated in $/LeapCC/Library/Student
//Updated look n feel of student dashboard
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 4/21/09    Time: 11:35a
//Updated in $/LeapCC/Library/Student
//show pop up on dashboard in event, notices, admin messages, teacher
//messages
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/25/08   Time: 4:40p
//Updated in $/LeapCC/Library/Student
//modification in queries for student dashboard
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/09/08   Time: 5:31p
//Updated in $/LeapCC/Library/Student
//modification in code for cc
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 10/08/08   Time: 3:29p
//Updated in $/Leap/Source/Library/Student
//remove spaces
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 9/20/08    Time: 6:13p
//Updated in $/Leap/Source/Library/Student
//remove all the unusable functions
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 9/20/08    Time: 5:34p
//Updated in $/Leap/Source/Library/Student
//remove the function getmarks
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 9/05/08    Time: 7:35p
//Updated in $/Leap/Source/Library/Student
//bugs fixation
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 9/01/08    Time: 4:05p
//Updated in $/Leap/Source/Library/Student
//fix the bug
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/18/08    Time: 3:44p
//Updated in $/Leap/Source/Library/Student
//modification in template
//
//*****************  Version 2  *****************
//User: Administrator Date: 7/31/08    Time: 1:20p
//Updated in $/Leap/Source/Library/Student
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/25/08    Time: 12:42p
//Created in $/Leap/Source/Library/Student
//contains the data base function to run data base queries
//


?>
