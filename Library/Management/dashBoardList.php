<?php               
//-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of management notices  and show it in dashboard
//
// Author : Rajeev Aggarwal
// Created on : (08.10.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------
?>
<?php
    set_time_limit(0);  
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
	define('ACCESS','view');
    UtilityManager::ifManagementNotLoggedIn();
    UtilityManager::headerNoCache();        

        
    require_once(MODEL_PATH . "/Management/DashboardManager.inc.php");
    $managementManager = DashBoardManager::getInstance();

	/* function to fetch total student*/
	$getTotalStudentArr = $managementManager->getTotalStudent();

	/* function to fetch total teaching employee*/
	$getTotalEmployeeArr = $managementManager->getTotalEmployee(" AND isTeaching=1");

	/* function to fetch total non teaching employee*/
	$getTotalNonEmployeeArr = $managementManager->getTotalEmployee(" AND isTeaching!=1");

	/* function to fetch total degree*/
	$getTotalDegreeArr = $managementManager->getTotalDegree();

	/* function to fetch total branch*/
	$getTotalBranchArr = $managementManager->getTotalBranch();

	/* function to fetch total notice*/
	$getTotalAllNoticeArr = $managementManager->getAllCountNotice();

	/* function to fetch total event*/
	$getTotalAllEventArr = $managementManager->getTotalEvent();

	/* function to fetch total Fees deposited*/
	$getTotalFeesArr = $managementManager->getTotalFees();

	//********For BirthDay wishes**************
    // $birthDayTakenRecordArray = $managementManager->checkBirthDay();
    $greetingMsg="";
    if($birthDayTakenRecordArray[0]['birthDay'] >0){
        $greetingMsg="Happy BirthDay.............";
    }
    //********For For BirthDay wishes(ends)**************
    
    
    //********For MarriageDay wishes**************
    //$marriageDayTakenRecordArray = $managementManager->checkMarriageDay();
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
    
    $limit      = ' LIMIT 0,5';  //showing first three records
   //********For Attendance Not Taken**************
    
    $filter="";
     ////////////   
    //$attendanceNotTakenRecordArray = $managementManager->checkAttendanceNotTaken($filter,$limit);
    //********For Attendance Not Taken(ends)**************
    
    
    
    //********For Notices**************
   // $limit      = ' LIMIT 0,3';  //showing first three records
    $curDate=date('Y')."-".date('m')."-".date('d');
    $filter=" AND ( '$curDate' >= n.visibleFromDate AND '$curDate' <= n.visibleToDate) AND nvr.roleId=5";  
     ////////////   
    $totalArray = $managementManager->getNoticeList($filter);
	 
    $noticeRecordArray = $managementManager->getNoticeList($filter,$limit,'n.visibleFromDate DESC');
    //********For Notice(ends)**************
    
    
    
    //********For Events**************
    //$limit      = ' LIMIT 0,3';  //showing first three records
	$filter ="";
    $curDate=date('Y')."-".date('m')."-".date('d');
    $filter=" AND DATE_SUB(startDate,INTERVAL ".EVENT_DAY_PRIOR." DAY)<=CURDATE() AND endDate>=CURDATE() AND ( '$curDate' >= e.startDate AND '$curDate' <= e.endDate) AND roleIds LIKE '%~5~%'";  
     ////////////   
    $totalEventArray = $managementManager->getEventList($filter);
    $eventRecordArray = $managementManager->getEventList($filter,$limit,'e.startDate DESC');
    //********For Events(ends)**************

	/* for BAR Graph*/
	$strList1 ="";
	$branchRecordArray = $managementManager->getStudentBranchList();
	$cnt = count($branchRecordArray);
	$strList1 .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";

	$strList1 .="<series>\n";
	for($i=0;$i<$cnt;$i++) {

		$strList1 .= "<value xid='".$i."'>".$branchRecordArray[$i]['branchCode']."</value>\n";
		 
	}
	$strList1 .="</series>\n";
	$strList1 .="<graphs>\n";

	for($k=1;$k<2;$k++) {
		
		$strList1 .="<graph gid='".$k."'>\n";
		for($i=0;$i<$cnt;$i++) {

			$strList1 .= "<value xid='".$i."' url='javascript:showData(".$branchRecordArray[$i]['branchId'].")'>".$branchRecordArray[$i]['totalCount']."</value>\n";
			 
		}
		$strList1 .="</graph>\n";
	}
	$strList1 .="</graphs>\n";
	$strList1 .="</chart>";

	$xmlFilePath = TEMPLATES_PATH."/Xml/studentBranchBarData.xml";
	if(is_writable($xmlFilePath)){

		$handle = @fopen($xmlFilePath, 'w');
		if (@fwrite($handle, $strList1) === FALSE){
			die("unable to write");
		}
	}
	else{
		logError("unable to open student branch data xml file");
	}

	/* for bar graph*/

	/* START: function to fetch student degree data */
	$degreeRecordArray = $managementManager->getStudentDegreeList();
	$cnt = count($degreeRecordArray);
	$strList2 .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList2 .= "<slice title='".$degreeRecordArray[$i]['degreeName']."' description='".$degreeRecordArray[$i]['degreeId']."~degree'>".$degreeRecordArray[$i]['totalCount']."</slice>\n";
		 
    } 
	$strList2 .="</pie>";

	$xmlFilePath = TEMPLATES_PATH."/Xml/studentDegreeData.xml";
	if(is_writable($xmlFilePath)){

		$handle = @fopen($xmlFilePath, 'w');
		if (@fwrite($handle, $strList2) === FALSE){
			die("unable to write");
		}
	}
	else{
		logError("unable to open degee data xml file");
	}
	
	/* END: function to fetch student degree data */

	/* START: function to fetch employee designation data */
	$strList ="";
	$designationRecordArray = $managementManager->getEmployeeDesignationList();
	$cnt = count($designationRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
    for($i=0;$i<$cnt;$i++) {

		$strList .= "<slice title='".$designationRecordArray[$i]['designationName']."' description='".$designationRecordArray[$i]['designationId']."~designation'>".$designationRecordArray[$i]['totalCount']."</slice>\n";
    } 
	$strList .="</pie>";
	
	$xmlFilePath = TEMPLATES_PATH."/Xml/employeeDesignationData.xml";
	if(is_writable($xmlFilePath)){

		$handle = @fopen($xmlFilePath, 'w');
		if (@fwrite($handle, $strList) === FALSE){
			die("unable to write");
		}
	}
	else{
		logError("unable to open employee designation data xml file");
	}
	/* END: function to fetch employee designation data */
    
    
    /* START: User activity graph*/
    $strList ="";
    $activityRecordArray = $managementManager->getEmployeeActivityList();
    $cnt = count($activityRecordArray);
    $strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";

    $strList .="<series>\n";
    for($i=0;$i<$cnt;$i++) {

        $strList .= "<value xid='".$i."'>".$activityRecordArray[$i]['loggedTime']."</value>\n";
    }
    $strList .="</series>\n";
    $strList .="<graphs>\n";
    if(count($activityRecordArray)==1)
    {
        for($k=1;$k<2;$k++) {
        
            $strList .="<graph gid='".$k."'>\n";
            for($i=0;$i<$cnt;$i++) {

                $strList .= "<value xid='".$i."'>".$activityRecordArray[$i]['totalCount']."</value>\n";
            }
            $strList .="</graph>\n";
        }
    }
    for($k=2;$k<3;$k++) {
        
        $strList .="<graph gid='".$k."'>\n";
        for($i=0;$i<$cnt;$i++) {

            $strList .= "<value xid='".$i."' bullet='round' url='javascript:loginShowData(\"".$activityRecordArray[$i]['loggedInTime']."\")'>".$activityRecordArray[$i]['totalCount']."</value>\n";
        }
        $strList .="</graph>\n";
    }
    $strList .="</graphs>\n";
    $strList .="</chart>";

    $xmlFilePath = TEMPLATES_PATH."/Xml/employeeActivityBarData.xml";
    if(is_writable($xmlFilePath)){

        $handle = @fopen($xmlFilePath, 'w');
        if (@fwrite($handle, $strList) === FALSE){
            die("unable to write");
        }
    }
    else{
        logError("unable to open user activity data xml file");
    }
    /* END: User activity graph*/
    
    
  
   /*
    -----------------------------------------------
        FOR ATTENDANCE BELOW THRESHOLD
    -----------------------------------------------
    */
    $concatStr = "'0#0'";
    $activeTimeTableLabelArray = $managementManager->getActiveTimeTable();
    $activeTimeTableLabelId = $activeTimeTableLabelArray[0]['timeTableLabelId'];
    //$attendanceThresholdlimit      = ' LIMIT 0,'.RECORDS_PER_PAGE_ADMIN_MESSAGE_EMPLOYEE;
    $teacherSubjectsArray = $managementManager->getTeacherSubjects($activeTimeTableLabelId);
    $concatStr = "'0#0'"; 
    foreach($teacherSubjectsArray as $teacherSubjectRecord) {
        $subjectId = $teacherSubjectRecord['subjectId'];
        $classId = $teacherSubjectRecord['classId'];
        $employeeId = $teacherSubjectRecord['employeeId'];
        $employeeName = $teacherSubjectRecord['employeeName'];
        if ($concatStr != '') {
            $concatStr .= ',';
        }
        $concatStr .= "'$subjectId#$classId'";
    }
    
    $attCountArray = $managementManager->countAttendanceThresholdRecords($activeTimeTableLabelId, $concatStr,'',$groupBy='att.classId, att.studentId,att.subjectId',"DISTINCT");
    $totalStudentCountBelowThreshold =0;
    $strAttendanceThreshold = "<table width='100%' border='0' align='left' cellspacing='0' cellpadding='0' align='left'>"; 
    if(count($attCountArray)>0) {
     /*  $strAttendanceThreshold .= " <tr>
                                        <td valign='top' align='left' class='padding_top' style='padding-left:10px'><b>Class</b></td>
                                        <td valign='top' align='right' class='padding_top'><b>Count</b></td>
                                    </tr>";
     */
    }
    for($i=0; $i<count($attCountArray);$i++) {
       $totalStudentCountBelowThreshold = $totalStudentCountBelowThreshold + $attCountArray[$i]['cnt']; 
       $classId =  $attCountArray[$i]['classId']; 
       $className = $attCountArray[$i]['className']; 
       $classSubjectCount = $attCountArray[$i]['cnt']; 
       //<a href='javascript:showMessageSending(\"attendanceThreshold\",\"$classId\")'>$className</a>
       $strAttendanceThreshold .= "
            <tr>
            <td valign='top' align='left' class='padding_top' style='padding-left:22px'>
                <a href=listAttendanceThreshold.php?mode=a&val=$classId>$className</a>
            </td>
            <td valign='top' align='right' class='padding_top'>
                <a href=listAttendanceThreshold.php?mode=a&val=$classId>$classSubjectCount</a>
            </td>
            </tr>";
    }
    if(count($attCountArray)>0) {
      $strAttendanceThreshold .= "<td valign='top' align='center' class='padding_top' colspan='2'>
                                    <hr color='#6f6f6f' size='1'/>
                                  </td>";
    }
    $strAttendanceThreshold .= "</table>";
    
    //-----------------------------------------------
    //  Eaxm Statistics Report
    //-----------------------------------------------
    
    $concatStr = "'0#0'";
    foreach($teacherSubjectsArray as $teacherSubjectRecord) {
        $subjectId = $teacherSubjectRecord['subjectId'];
        $classId = $teacherSubjectRecord['classId'];
        $employeeId = $teacherSubjectRecord['employeeId']; 
        if ($concatStr != '') {
            $concatStr .= ',';
        }
        $concatStr .= "'$subjectId#$classId'";
    }

    $teacherCountTestsArray = $managementManager->getCountTeacherTests($concatStr);
    $examStatistics = $teacherCountTestsArray[0]['cnt'];   
    
    
    //-----------------------------------------------
    //    FOR TOPPERS
    //-----------------------------------------------
    
    
   /* $concatStrArr = explode(',',$concatStr);
    $strTopper = 0;
    for($i=0;$i<count($concatStrArr);$i++) {
      $totalRecordArray = $managementManager->countTopperRecords($concatStrArr[$i]);
      if($totalRecordArray[0]['cnt']>0) {
        $strTopper = $strTopper + $totalRecordArray[0]['cnt'];
      }
    }
   */
    $attCountArray = $managementManager->countClassTopperRecords($concatStr);
    $strTopper = 0;  
    $strClassTopper = "<table width='100%' border='0' align='left' cellspacing='0' cellpadding='0' align='left'>"; 
    if(count($attCountArray)>0) {
     /*  $strAttendanceThreshold .= " <tr>
                                        <td valign='top' align='left' class='padding_top' style='padding-left:10px'><b>Class</b></td>
                                        <td valign='top' align='right' class='padding_top'><b>Count</b></td>
                                    </tr>";
     */
    }
    for($i=0; $i<count($attCountArray);$i++) {
       $strTopper = $strTopper + $attCountArray[$i]['cnt']; 
       $classId =  $attCountArray[$i]['classId']; 
       $className = $attCountArray[$i]['className']; 
       $classSubjectCount = $attCountArray[$i]['cnt']; 
       //<a href='javascript:showMessageSending(\"attendanceThreshold\",\"$classId\")'>$className</a>
       $strClassTopper .= "
            <tr>
            <td valign='top' align='left' class='padding_top' style='padding-left:22px'>
                <a href=listAttendanceThreshold.php?mode=t&val=$classId>$className</a>
            </td>
            </tr>";
    }
    if(count($attCountArray)>0) {
      $strClassTopper .= "<td valign='top' align='center' class='padding_top' colspan='2'>
                                    <hr color='#6f6f6f' size='1'/>
                                  </td>";
    }
    $strClassTopper .= "</table>";
 
    //-----------------------------------------------
    //    FOR BELOW AVERAGE
    //-----------------------------------------------
    
   /* $strBelowAvg = 0;
    $teacherCountTestsArray = $managementManager->countBelowAvgRecords($concatStr);
    $strBelowAvg = $teacherCountTestsArray[0]['cnt'];   
   */
    $attCountArray = $managementManager->countClassBelowAvgRecords($concatStr,'','sg.classId, sg.studentId, b.subjectId','');
    $strBelowAvg = 0;   
    $strClassBelowAvg = "<table width='100%' border='0' align='left' cellspacing='0' cellpadding='0' align='left'>"; 
    if(count($attCountArray)>0) {
     /*  $strAttendanceThreshold .= " <tr>
                                        <td valign='top' align='left' class='padding_top' style='padding-left:10px'><b>Class</b></td>
                                        <td valign='top' align='right' class='padding_top'><b>Count</b></td>
                                    </tr>";
     */
    }
  for($i=0; $i<count($attCountArray);$i++) {
       $strBelowAvg = $strBelowAvg + $attCountArray[$i]['cnt']; 
     $classId =  $attCountArray[$i]['classId']; 
       $className = $attCountArray[$i]['className']; 
       $classSubjectCount = $attCountArray[$i]['cnt']; 
       //<a href='javascript:showMessageSending(\"attendanceThreshold\",\"$classId\")'>$className</a>
       $strClassBelowAvg .= "
            <tr>
            <td valign='top' align='left' class='padding_top' style='padding-left:22px'>
                <a href=listAttendanceThreshold.php?mode=b&val=$classId>$className</a>
            </td>
            <td valign='top' align='right' class='padding_top'>
                <a href=listAttendanceThreshold.php?mode=b&val=$classId>$classSubjectCount</a>
            </td>
            </tr>"; 
    } 
    if(count($attCountArray)>0) {
      $strClassBelowAvg .= "<td valign='top' align='center' class='padding_top' colspan='2'>
                                    <hr color='#6f6f6f' size='1'/>
                                  </td>";
    }
    $strClassBelowAvg .= "</table>";
 

    
    //-----------------------------------------------
    //    FOR ABOVE AVERAGE
    //-----------------------------------------------
    /*$strAboveAvg = 0;
    $teacherCountTestsArray = $managementManager->countAboveAvgRecords($concatStr);
    $strAboveAvg = $teacherCountTestsArray[0]['cnt'];    
    */
    $attCountArray = $managementManager->countClassAboveAvgRecords($concatStr,'','sg.classId, sg.studentId, b.subjectId','');
    $strAboveAvg = 0;    
    $strClassAboveAvg = "<table width='100%' border='0' align='left' cellspacing='0' cellpadding='0' align='left'>"; 
    if(count($attCountArray)>0) {
     /*  $strAttendanceThreshold .= " <tr>
                                        <td valign='top' align='left' class='padding_top' style='padding-left:10px'><b>Class</b></td>
                                        <td valign='top' align='right' class='padding_top'><b>Count</b></td>
                                    </tr>";
     */
    }
	 
    for($i=0; $i<count($attCountArray);$i++) {
       $strAboveAvg = $strAboveAvg + $attCountArray[$i]['cnt']; 
 $classId =  $attCountArray[$i]['classId']; 
       $className = $attCountArray[$i]['className']; 
       $classSubjectCount = $attCountArray[$i]['cnt']; 
       //<a href='javascript:showMessageSending(\"attendanceThreshold\",\"$classId\")'>$className</a>
       $strClassAboveAvg .= "
            <tr>
            <td valign='top' align='left' class='padding_top' style='padding-left:22px'>
                <a href=listAttendanceThreshold.php?mode=av&val=$classId>$className</a>
            </td>
            <td valign='top' align='right' class='padding_top'>
                <a href=listAttendanceThreshold.php?mode=av&val=$classId>$classSubjectCount</a>
            </td>
            </tr>";
    }
    if(count($attCountArray)>0) {
      $strClassAboveAvg .= "<td valign='top' align='center' class='padding_top' colspan='2'>
                                    <hr color='#6f6f6f' size='1'/>
                                  </td>";
    }
    $strClassAboveAvg .= "</table>"; 

  
?>
<?php  // $History: dashBoardList.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/11/10    Time: 2:30p
//Updated in $/LeapCC/Library/Management
//query & validation format updated (topper, below, average, i.e. added) 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/10/10    Time: 4:48p
//Updated in $/LeapCC/Library/Management
//validation & format updated
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:01p
//Created in $/LeapCC/Library/Management
//Inital checkin
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 11/13/08   Time: 3:39p
//Updated in $/Leap/Source/Library/Management
//updated XML path
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 11/05/08   Time: 4:51p
//Updated in $/Leap/Source/Library/Management
//added new reports on management dashboard
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 10/22/08   Time: 11:53a
//Updated in $/Leap/Source/Library/Management
//updated with validations for mangement role
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 10/20/08   Time: 3:40p
//Updated in $/Leap/Source/Library/Management
//updated with new pie charts
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 10/17/08   Time: 1:49p
//Updated in $/Leap/Source/Library/Management
//updated pie charts
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 10/15/08   Time: 5:29p
//Updated in $/Leap/Source/Library/Management
//added new files as per management role
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 10/15/08   Time: 10:10a
//Updated in $/Leap/Source/Library/Management
//added function to get total values for various statistics for dashboard
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 10/13/08   Time: 2:06p
//Created in $/Leap/Source/Library/Management
//intial checkin
?>