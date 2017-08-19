<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A STUDENT
//
//
// Author : Rajeev Aggarwal
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (trim($errorMessage) == '') {
		require_once(MODEL_PATH . "/StudentManager.inc.php");
		$studentManager = StudentManager::getInstance();
		$siblingRoll = trim($REQUEST_DATA['siblingRoll']);
		$siblingRoll = add_slashes($siblingRoll);
		$userExistArr   = $studentManager->getStudentUserDetailClass($siblingRoll);	

		$fatherUserId = $userExistArr[0]['fatherUserId'];
		$motherUserId =	$userExistArr[0]['motherUserId'];
		$guardianUserId = $userExistArr[0]['guardianUserId'];
		$firstName = $userExistArr[0]['firstName'];
		$lastName = $userExistArr[0]['lastName'];
		 
		if(($fatherUserId!='') OR ($motherUserId!='') OR ($guardianUserId!=''))
		{
		$returnStatus   = $studentManager->editStudentParentDetails($fatherUserId,$motherUserId,$guardianUserId,$REQUEST_DATA['studentId']);
		 
			if($returnStatus === false) 
			{
			   $errorMessage = FAILURE;
			}
			else 
			{
			   $getStudentUserDetailArr = $studentManager->getSiblingStudents($fatherUserId,$motherUserId,$guardianUserId);
			   $cnt = count($getStudentUserDetailArr);
				
			   for($i=0;$i<$cnt;$i++) {

				   $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$getStudentUserDetailArr[$i]);
					
				   if(trim($json_val)=='') {
						$json_val = json_encode($valueArray);
				   }
				   else {
						$json_val .= ','.json_encode($valueArray);           
				   }
				}
				//print_r($valueArray);
			   echo '{"info" : ['.$json_val.']}';         
			}
		}
		else {
        echo $firstName." ".$lastName."(".$siblingRoll.") does not have parent username and password";
		}
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitSibling.php $
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 2/06/10    Time: 1:51p
//Updated in $/LeapCC/Library/Student
//fixed issue: 0002707
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/21/09    Time: 5:19p
//Updated in $/LeapCC/Library/Student
//Gurkeerat: updated access defines
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 6/01/09    Time: 7:37p
//Updated in $/LeapCC/Library/Student
//Fixed issues of find student of formatting
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Student
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 8/25/08    Time: 5:31p
//Updated in $/Leap/Source/Library/Student
//updated student detail functions
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/29/08    Time: 4:03p
//Created in $/Leap/Source/Library/Student
//intial checkin
?>
