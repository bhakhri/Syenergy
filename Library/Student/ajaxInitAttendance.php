<?php
//-------------------------------------------------------
// Purpose: To store the records of class in array from the database functionality
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH."/CommonQueryManager.inc.php");
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
	$commonAttendanceArr = CommonQueryManager::getInstance();

	$startDate2 = $REQUEST_DATA['startDate2'];
	$endDate2 = $REQUEST_DATA['endDate2'];
	$studentId = $REQUEST_DATA['studentId'];
	
	if($startDate2)
		$where = " AND fromDate BETWEEN '$startDate2' AND '$endDate2'";

	if($endDate2)
		$where .= " AND toDate BETWEEN '$startDate2' AND '$endDate2'";

	$where .= " AND s.studentId = '$studentId'";
	
    $studentSubjectArray = $commonAttendanceArr->getAttendance($where);
    $cnt = count($studentSubjectArray);
	$json_val ="";
	 
    for($i=0;$i<$cnt;$i++) {
		if($studentSubjectArray[$i]['delivered'])
			$percentageAtt = number_format((($studentSubjectArray[$i]['attended']/$studentSubjectArray[$i]['delivered'])*100),2,'.','');
		else
			$percentageAtt = "0";

	   $percentageAtt = number_format($percentageAtt,'2','.','');	
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
// $History: ajaxInitAttendance.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 8/16/08    Time: 2:54p
//Updated in $/Leap/Source/Library/Student
//updated with number format function
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 8/06/08    Time: 3:50p
//Updated in $/Leap/Source/Library/Student
//updated query
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 8/01/08    Time: 4:03p
//Updated in $/Leap/Source/Library/Student
//updated attendance function
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/29/08    Time: 3:56p
//Updated in $/Leap/Source/Library/Student
//updated single atttendance query records
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/15/08    Time: 11:18a
//Created in $/Leap/Source/Library/Student
//intial checkin
 
?>
