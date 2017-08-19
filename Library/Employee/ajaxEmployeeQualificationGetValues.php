<?php
//-------------------------------------------------------
// Purpose: To get values of coursewise timetable from the database
//
// Author : Parveen Sharma
// Created on : 28.01.09
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/EmployeeManager.inc.php");
define('MODULE','EmployeeMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

global $sessionHandler;

$employeeManager    = EmployeeManager::getInstance();    
$employeeId = add_slashes(trim($REQUEST_DATA['employeeId']));

if($employeeId!='') { 
    
       
/*
    if(trim($REQUEST_DATA['subjectTypeId']) != '') { 
        //$timeTableArray = $percentManager->getActiveTimeTableLabelId();
        //$activeTimeTableLabelId  = $timeTableArray[0]['timeTableLabelId'];
    $subjectTypeId = $REQUEST_DATA['subjectTypeId'];
    $timeTableLabelId = $REQUEST_DATA['timeTableLabelId'];
	$degreeId = $REQUEST_DATA['degreeId'];
    
    $str = ' AND ams.subjectTypeId='.$subjectTypeId.' AND ams.timeTableLabelId='.$timeTableLabelId.' AND ams.instituteId = '.$instituteId.' AND ams.degreeId = '.$degreeId;
*/ 
    
    $foundArray = $employeeManager->getEmployeeQualification($employeeId);
	
    $resultsCount = count($foundArray);
    if(is_array($foundArray) && $resultsCount>0) {
        $jsonQualificationArray  = array();
        for($s = 0; $s<$resultsCount; $s++) {
            $jsonQualificationArray[] = $foundArray[$s];         
        }
    }
    
    $resultArray = array('employeeQualificationArr' => $jsonQualificationArray);
 //   print_r($resultArray);  
    
     echo json_encode($resultArray);
}
// $History: ajaxEmployeeQualificationGetValues.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/29/10    Time: 3:32p
//Created in $/LeapCC/Library/Employee
//new files for employee experience, education & gap analysis
//
//
?>