<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of institute notices  and show it in dashboard
//
// Author : Rajeev Aggarwal
// Created on : (13.08.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/DashBoardManager.inc.php");
    $dashboardManager = DashBoardManager::getInstance();

	require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();

	/**/
	global $sessionHandler;
	if($sessionHandler->getSessionVariable('RoleType')=='11'){
		
		/* START: function to fetch student enquiry city list */
		$strList = "";
		$studentRecordArray = $dashboardManager->getStudentEnquiryCityList();
		$cnt = count($studentRecordArray);
		$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
		for($i=0;$i<$cnt;$i++){
	
			$totalCount = $studentRecordArray[$i]['foundCity'];
			if($studentRecordArray[$i]['notfoundCity'])
				$totalCount = $studentRecordArray[$i]['notfoundCity'];
			$strList .= "<slice title='".$studentRecordArray[$i]['cityName']."' description='".$studentRecordArray[$i]['cityId']."~city'>".$totalCount."</slice>\n";
			 
		} 
		$strList .="</pie>";
		
		$xmlFilePath = TEMPLATES_PATH."/Xml/studentEnquiryCityData.xml";
		UtilityManager::writeXML($strList, $xmlFilePath);
		/* END: function to fetch student enquiry  city list */

		/* START: function to fetch student enquiry state list */
		$strList = "";
		$studentRecordArray = $dashboardManager->getStudentEnquiryStateList();
		$cnt = count($studentRecordArray);
		$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
		for($i=0;$i<$cnt;$i++){
			
			$totalCount = $studentRecordArray[$i]['foundState'];
			if($studentRecordArray[$i]['notfoundState'])
				$totalCount = $studentRecordArray[$i]['notfoundState'];

			$strList .= "<slice title='".$studentRecordArray[$i]['stateName']."' description='".$studentRecordArray[$i]['stateId']."~state'>".$totalCount."</slice>\n";
			 
		} 
		$strList .="</pie>";
		
		$xmlFilePath = TEMPLATES_PATH."/Xml/studentEnquiryStateData.xml";
		UtilityManager::writeXML($strList, $xmlFilePath);
		/* END: function to fetch student enquiry state list */

		/* START: function to fetch student enquiry degree list */
		$strList = "";
		$studentRecordArray = $dashboardManager->getStudentEnquiryDegreeList();
		$cnt = count($studentRecordArray);
		$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<pie>\n";
		for($i=0;$i<$cnt;$i++){

			$strList .= "<slice title='".$studentRecordArray[$i]['className']."' description='".$studentRecordArray[$i]['classId']."~class'>".$studentRecordArray[$i]['totalCount']."</slice>\n";
			 
		} 
		$strList .="</pie>";
		
		$xmlFilePath = TEMPLATES_PATH."/Xml/studentEnquiryDegreeData.xml";
		UtilityManager::writeXML($strList, $xmlFilePath);
		/* END: function to fetch student enquiry degree list */
	}
	else{

    //********For Showing UnApproved Fine Count**************
    $unApprovedRecordArray = $dashboardManager->getUnApprovedFineCount();
    //********For mailbox**************
        
	$dashboardFrameCount = $dashboardManager->getDashboardFrameTotal(" WHERE isActive =1"); 
	$dashboardFrameArray = $dashboardManager->getDashboardFrameList(" WHERE isActive =1"); 
  
    //********For fees due(starts)**************
	$limit       = ' LIMIT 0,5';  //showing first seven records
    //$feesDueArray=$dashboardManager->getAllFeesDue('',$limit,'  previousDues DESC','');

	//$feesCountArray=$dashboardManager->getAllFeesDue('','','  previousDues DESC','');
	//$feesDueCount=count($feesCountArray);
    
    //********For fees due(ends)**************
    
    //********For Notices**************
    $limit    = ' LIMIT 0,16';  //showing first three records  // DONT REINITIALIZE VARIABLE UNLESS IT IS NEEDED
    $curDate  = date('Y-m-d');
    $filter   = " AND ('$curDate' >= visibleFromDate AND '$curDate' <= visibleToDate)";  
     ////////////   
    //$totalArray = $teacherManager->getTotalNotice($filter);
    $noticeRecordArray = $dashboardManager->getNoticeList($filter,$limit,'visibleFromDate DESC, visibleMode DESC, noticeId DESC');
    /*  $noticeCountArray  = $dashboardManager->getNoticeListCount($filter);
	    $noticeRecordCount = $noticeCountArray[0]['totalRecords'];
        if($noticeRecordCount=='') {
          $noticeRecordCount=0;  
        }
    */
    //********For Notice(ends)**************
    
    //********For Events**************
    //$limit      = ' LIMIT 0,5';  //showing first three records   
    //$curDate=date('Y-m-d');     // DO WE NEED THESE VARIABLES AGAIN? 
	
    //$filter=" AND ( '$curDate' >= e.startDate AND '$curDate' <= e.endDate)";  
	$filter="";  
     ////////////   
    //$totalArray = $teacherManager->getTotalEvent($filter);
	$limit       = ' LIMIT 0,5'; 
    $eventRecordArray = $dashboardManager->getEventList($filter,$limit,' e.startDate DESC');

	//$eventCountArray = $dashboardManager->getEventList($filter,'',' e.startDate DESC');
	//$eventRecordCount=count($eventCountArray);
    //********For Events(ends)**************

	/* for BAR Graph*/

	/* START: User activity graph*/
	$strList ="";
	$activityRecordArray = $dashboardManager->getEmployeeActivityList();
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

			$strList .= "<value xid='".$i."' bullet='round' url='javascript:showData(\"".$activityRecordArray[$i]['loggedInTime']."\")'>".$activityRecordArray[$i]['totalCount']."</value>\n";
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
	
	/* START: Average Attendance graph*/

	/*
	$strList ="";
	$activityRecordArray = $dashboardManager->getAverageAttendance();
	$activityRecordArray  =array();
	$cnt = count($activityRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";

	$strList .="<series>\n";
	for($i=0;$i<$cnt;$i++) {

		$strList .= "<value xid='".$i."'>".$activityRecordArray[$i]['subjectCode']."</value>\n";
	}
	$strList .="</series>\n";
	$strList .="<graphs>\n";
	 
	for($k=1;$k<3;$k++) {
		
		$strList .="<graph gid='".$k."'>\n";
		for($i=0;$i<$cnt;$i++) {

			$strList .= "<value xid='".$i."' bullet='round'>".$activityRecordArray[$i]['percentage']."</value>\n";
		}
		$strList .="</graph>\n";
	}
	$strList .="</graphs>\n";
	$strList .="</chart>";
	*/
	

	/*
	$xmlFilePath = TEMPLATES_PATH."/Xml/averageAttencanceActivityBarData.xml";
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
	/* END: Average Attendance graph*/

	/* START: Average Attendance marks*/
	/*
	$strList ="";
	$activityRecordArray = $dashboardManager->getAverageMarks();
	$cnt = count($activityRecordArray);
	$strList .="<?xml version='1.0' encoding='UTF-8'?>\n<chart>\n";

	$strList .="<series>\n";
	for($i=0;$i<$cnt;$i++) {

		$strList .= "<value xid='".$i."'>".$activityRecordArray[$i]['subjectCode']."</value>\n";
	}
	$strList .="</series>\n";
	$strList .="<graphs>\n";
	 
	for($k=1;$k<3;$k++) {
		
		$strList .="<graph gid='".$k."'>\n";
		for($i=0;$i<$cnt;$i++) {

			$strList .= "<value xid='".$i."' bullet='round'>".$activityRecordArray[$i]['percentage']."</value>\n";
		}
		$strList .="</graph>\n";
	}
	$strList .="</graphs>\n";
	$strList .="</chart>";

	$xmlFilePath = TEMPLATES_PATH."/Xml/averageMarksActivityBarData.xml";
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
	/* END: Average Attendance marks*/

	/**/
	
	    $activeClassArray = $dashboardManager->getActiveClassId();
	    $results       = $commonQueryManager->getSessionClasses();
	    $scaleCount    = count($results);  
	    $returnValues1 = '';
	    if(isset($results) && is_array($results)) {
		    for($i=0;$i<$scaleCount;$i++) {
	//if($results[$i]['classId']==$activeClassArray[0]['classId']){
	//$returnValues1 .='<option value="'.$results[$i]['classId'].'" SELECTED="SELECTED">'.strip_slashes($results[$i]['className']).'</option>';
	//}
			    //else{
				    $returnValues1 .='<option value="'.$results[$i]['classId'].'">'.strip_slashes($results[$i]['className']).'</option>';
			    //}
		    }
	    }
	}
	//@chmod($xmlFilePath, 0444); // to make the document readonlys
	/* for bar graph*/

	
  //********For Events(ends)**************
  
 /************CODE FOR SHOWING ALERT FOR EXPECTED DATE OF CHECKOUT*********/
  $dateInterval=7;
  $expectedDateArray=$dashboardManager->getExpectedDateOfCheckOutList($dateInterval);
  $expectedDateString='';
  if(is_array($expectedDateArray) and count($expectedDateArray)>0){
    if($expectedDateArray[0]['occupied']>0){  
     $linkString=$expectedDateArray[0]['occupied'].' student(s) are expected to checkout in coming '.$dateInterval.' days'; 
     $expectedDateString='<a href="'.UI_HTTP_PATH.'/roomAllocation.php" class="redLink" title="'.$linkString.'">'.$linkString.'</a>';
    }
  }
 /************CODE FOR SHOWING ALERT FOR EXPECTED DATE OF CHECKOUT*********/
  
  

// $History: dashBoardList.php $
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 3/11/10    Time: 11:07a
//Updated in $/LeapCC/Library/Index
//fixed issue of showing notice.
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 3/02/10    Time: 3:04p
//Updated in $/LeapCC/Library/Index
//show active classes 
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 6/07/09    Time: 18:29
//Updated in $/LeapCC/Library/Index
//Added "UnApproved Fine Display" in admin's dashboard
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 6/02/09    Time: 7:22p
//Updated in $/LeapCC/Library/Index
//Updated with "unsepcified" parameter if city and state is NULL
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 6/02/09    Time: 6:15p
//Updated in $/LeapCC/Library/Index
//Updated with "Pre admission" dashboard and print report
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 5/19/09    Time: 5:56p
//Updated in $/LeapCC/Library/Index
//Updated Admin dashboard with role permission, test type and average
//attendance
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 1/19/09    Time: 4:26p
//Updated in $/LeapCC/Library/Index
//added role permission and dashboard permission
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/11/08   Time: 3:00p
//Updated in $/LeapCC/Library/Index
//Updated module as per CC functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Index
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 9/08/08    Time: 3:36p
//Updated in $/Leap/Source/Library/Index
//updated formatting
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 9/01/08    Time: 3:31p
//Updated in $/Leap/Source/Library/Index
//updated with fees dues on dashboard
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 8/28/08    Time: 11:45a
//Updated in $/Leap/Source/Library/Index
//updated with issues sent by QA of build 24
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 8/13/08    Time: 6:05p
//Created in $/Leap/Source/Library/Index
//intial checkin
?>