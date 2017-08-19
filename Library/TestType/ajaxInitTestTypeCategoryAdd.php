<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A TESTTYPECATEGORY
//
//
// Author : Jaineesh
// Created on : (19.02.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TestTypeCategoryMaster');
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
$commonQueryManager = CommonQueryManager::getInstance();
$testtypeName=$REQUEST_DATA['testTypeCategoryName'];
$universityId=$REQUEST_DATA['subjectType'];
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if ($errorMessage == '' && (!isset($REQUEST_DATA['testTypeCategoryName']) || trim($REQUEST_DATA['testTypeCategoryName']) == '')) {
        $errorMessage .= ENTER_TESTTYPECATEGORY_NAME."\n";    
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['testTypeCategoryName']) || trim($REQUEST_DATA['testTypeCategoryName']) == '')) {
        $errorMessage .= ENTER_TESTTYPECATEGORY_NAME."\n";    
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/TestTypeManager.inc.php");
		$testTypeManager = TestTypeManager::getInstance();

        $foundArray = $testTypeManager ->getTestTypeCategory(' AND UCASE(testTypeName)="'.add_slashes(strtoupper($REQUEST_DATA['testTypeCategoryName'])).'" OR LCASE(testTypeAbbr)="'.add_slashes(strtolower($REQUEST_DATA['testTypeCategoryAbbr'])).'"');
		
        if(trim($foundArray[0]['testTypeName'])=='') {  //DUPLICATE CHECK

		 if ($REQUEST_DATA['attendanceCategory'] == 1) {
			$foundAttendance = $testTypeManager->getCheckAttendance("WHERE isAttendanceCategory = ".$REQUEST_DATA['attendanceCategory']." AND subjectTypeId = ".$REQUEST_DATA['subjectType']." ");
			if ($foundAttendance[0]['attendanceCount'] > 0 ) {
				echo ATTENDANCE_CATEGORY_ALREADY_EXIST;
				die();
			}
		 }

			if ($REQUEST_DATA['examType'] == 'C') {
				$foundExam = $testTypeManager->getCheckExam("WHERE examType='".add_slashes($REQUEST_DATA['examType'])."' AND subjectTypeId = ".$REQUEST_DATA['subjectType']."");
				 if ($foundExam[0]['examCount'] > 0 ) {
					echo EXTERNAL_EXAMTYPE_ALREADY_EXIST;
					die();
				  }
			}
            $returnStatus = $testTypeManager->addTestTypeCategory();
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                    ########################### CODE FOR AUDIT TRAIL STARTS HERE ###########################################
			$auditTrialDescription = "Test type {$testtypeName} for subject type {$universityId} is Added ";
			$auditTrialDescription .= $headName;
			$type = "Test type added"; //test type added
			$returnStatus = $commonQueryManager->addAuditTrialRecord($type, $auditTrialDescription);
			if($returnStatus == false) {
				echo  "Error while saving data for audit trail";
				die;
			}
			########################### CODE FOR AUDIT TRAIL ENDS HERE ###########################################
                echo SUCCESS;           
            }
        }
        else {
			   if(strtolower($foundArray[0]['testTypeAbbr'])==trim(strtolower($REQUEST_DATA['testTypeCategoryAbbr']))) {
				 echo TEST_TYPE_CATEGORY_ABBR_EXIST;
				 die;
			   }
			   else if(strtolower($foundArray[0]['testTypeName'])==trim(strtolower($REQUEST_DATA['testTypeCategoryName']))) {
				   echo TEST_TYPE_CATEGORY_EXIST;
				   die;
			   }
		 }
    }
    else {
        echo $errorMessage;
    }

// $History: ajaxInitTestTypeCategoryAdd.php $
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 1/20/10    Time: 5:08p
//Updated in $/LeapCC/Library/TestType
//done changes to Assign Colour scheme to test type and refect this
//colour in student tab. FCNS No. 1102
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/05/10    Time: 12:48p
//Updated in $/LeapCC/Library/TestType
//fixed bug to add exam type for practical, training
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/03/09    Time: 6:03p
//Updated in $/LeapCC/Library/TestType
//add new filed test type category abbr.
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:30p
//Updated in $/LeapCC/Library/TestType
//modified for test type category
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/26/09    Time: 4:29p
//Created in $/LeapCC/Library/TestType
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 2/27/09    Time: 4:04p
//Created in $/SnS/Library/TestType
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 2/24/09    Time: 11:32a
//Created in $/Leap/Source/Library/TestType
//new ajax files for test type category
//
 
?>
