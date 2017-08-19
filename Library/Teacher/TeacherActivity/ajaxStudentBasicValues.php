<?php
//-------------------------------------------------------
// Purpose: To store the records of class in array from the database functionality
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifTeacherNotLoggedIn(true);
    UtilityManager::headerNoCache();
	
    require_once(MODEL_PATH . "/FineManager.inc.php");
    $fineStudentManager = FineManager::getInstance();

	/* START: function to fetch student details along with class */
    $studentFeesArray = $fineStudentManager->getStudentDetail("  AND st.rollNo='".trim(add_slashes($REQUEST_DATA['rollNo']))."'");

	if(is_array($studentFeesArray) && count($studentFeesArray)>0 ) {  
        $json_student =  json_encode($studentFeesArray[0]);
    }
    else {
        $json_student =  "exit"; // no record found
		exit;
    }
	/* END: function to fetch student details along with class */
	
	/* START: function to fetch student Serial Number */
	global $sessionHandler;

	$studentFineSerialArr = $fineStudentManager->getStudentFineSerial();
	$studentFineSerialNo  = $studentFineSerialArr[0]['maxSerialNo'];
	if($studentFineSerialNo)
		$studentFineSerialNo  = $studentFineSerialArr[0]['maxSerialNo']+1;
	else
		$studentFineSerialNo  = $sessionHandler->getSessionVariable('STUDENT_FINE_START_FROM');
	/* END: function to fetch student Serial Number */

	$studentFeesArray = $fineStudentManager->getFineStudentList(" AND fs.studentId =".$studentFeesArray[0]['studentId']." AND status=1 AND paid=0");
	$cnt = count($studentFeesArray);
	$totalFees = "";
    for($i=0;$i<$cnt;$i++) {

		$totalFine += $studentFeesArray[$i]['amount'];

		$checkall = '<input type="checkbox" name="chb1[]"  id="chb1'.$i.'" value="'.$studentFeesArray[$i]['fineStudentId'].'"><input type="hidden" name="chb1Value['.$studentFeesArray[$i][fineStudentId].']" id="chb1Value'.$i.'" value="'.$studentFeesArray[$i]['amount'].'">';
		$studentFeesArray[$i]['fineDate'] = UtilityManager::formatDate($studentFeesArray[$i]['fineDate']);
		$studentFeesArray[$i]['reason'] = '<a title="View Details" onClick="editWindow('.$studentFeesArray[$i]['fineStudentId'].',\'ViewReason\',400,400); return false;"  style="cursor:pointer">'.substr($studentFeesArray[$i]['reason'],0,10).'&nbsp;...</a>'; 
		 
		$valueArray = array_merge(array('checkAll' => $checkall,'srNo' => ($records+$i+1) ),$studentFeesArray[$i]);
		
		$totalFees += $studentFeesArray[$i]['feeHeadAmount'];
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }

	 
    echo '{"serialNo":"'.$studentFineSerialNo.'","totalAmount":"'.$totalFine.'","studentinfo" : ['.$json_student.'],"info" : ['.$json_val.']}'; 
 
// for VSS
// $History: ajaxStudentBasicValues.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/08/09    Time: 7:21p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//intial checkin
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/07/09    Time: 4:03p
//Updated in $/LeapCC/Library/Fine
//Updated collect fine with fine by and reason detail div pop up in
//collect fine
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/06/09    Time: 6:37p
//Created in $/LeapCC/Library/Fine
//Intial checkin

?>