<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A STUDENT
//
// Author : Rajeev Aggarwal
// Created on : (06.09.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','Admit');
	define('ACCESS','view'); 
	UtilityManager::ifNotLoggedIn(true);
	UtilityManager::headerNoCache();
	global $sessionHandler;
	$errorMessage ='';
	
	require_once(MODEL_PATH . "/StudentManager.inc.php");
	$studentManager = StudentManager::getInstance();
	
	$autoGenArr  = $studentManager->getConfigLabelValue(" AND param='AUTO_GENERATED_REG_NO'");
	if($autoGenArr[0]['value']){
	
		$prefixArr        = $studentManager->getConfigLabelValue(" AND param='INSTITUTE_REGISTRATION_NO_PREFIX'"); 
		$prefix		      = $prefixArr[0]['value'];

		$prefixLengthArr  = $studentManager->getConfigLabelValue(" AND param='INSTITUTE_REGISTRATION_NO_PREFIX'"); 
		$prefixLength	  = strlen($prefixLengthArr[0]['value']);

		$totalLengthArr  = $studentManager->getConfigLabelValue(" AND param='INSTITUTE_REGISTRATION_NO_LENGTH'"); 
		$totalLength	  = $totalLengthArr[0]['value'];

		//$prefixLength= strlen($sessionHandler->getSessionVariable('INSTITUTE_REGISTRATION_NO_PREFIX'));
		//$totalLength = $sessionHandler->getSessionVariable('INSTITUTE_REGISTRATION_NO_LENGTH');

		if (trim($errorMessage) == '') {

			$registrationArr = $studentManager->getRegistrationNumber();
			$registrationQuartineArr = $studentManager->getQuartineRegistrationNumber();
			
			if($registrationArr[0]['regNo']){
			
				if($registrationQuartineArr[0]['regNo']>$registrationArr[0]['regNo']){
				
					$latestRegNo = $registrationQuartineArr[0]['regNo'];
				}
				else{
				
					$latestRegNo = $registrationArr[0]['regNo'];
				}
				$lastNo = abs(substr($latestRegNo, $prefixLength)); 
				
				$newRegistrationNumber = $lastNo+1; 
				$newRegistrationNumberLength = strlen($newRegistrationNumber);
				$regLength = $totalLength - $prefixLength-$newRegistrationNumberLength;
				
				//$newRegistrationNumberLength = strlen($newRegistrationNumber); 
				for($i=0;$i<$regLength;$i++){
				
					$count.= "0";
				}
				$genratedRegNo = $count.$newRegistrationNumber;
				
			}
			else{
			
				
				$regLength = $totalLength - $prefixLength;
				for($i=0;$i<$regLength-1;$i++){
				
					$count.= "0";
				}
				$genratedRegNo = $count."1";
			}
			$genratedRegNo = $prefix.$genratedRegNo;
	   }
		else {
			echo $errorMessage;
		}
	}
// $History: getRegistrationNumber.php $
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 10-02-23   Time: 4:08p
//Updated in $/LeapCC/Library/Student
//updated with string length function in configuration parameter
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 10-02-23   Time: 3:46p
//Updated in $/LeapCC/Library/Student
//updated admit student with config setting for registration number
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/21/09    Time: 5:19p
//Updated in $/LeapCC/Library/Student
//Gurkeerat: updated access defines
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/29/09    Time: 10:21a
//Updated in $/LeapCC/Library/Student
//Updated with quartine student registration number increment
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 6/17/09    Time: 11:08a
//Created in $/LeapCC/Library/Student
//Dipanjan : Added this file to vss
?>