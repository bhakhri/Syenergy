<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A CITY
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once(MODEL_PATH . "/StudentEnquiryManager.inc.php");
define('MODULE','AddStudentEnquiry');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';

    global $sessionHandler;
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $sessionId = $sessionHandler->getSessionVariable('SessionId');


    if($instituteId=='') {
       $errorMessage .=  "Invalid Institute\n";
    }

    if($sessionId=='') {
       $errorMessage .=  "Invalid Session\n";
    }

 /* if (!isset($REQUEST_DATA['degree']) || trim($REQUEST_DATA['degree']) == '') {
        $errorMessage .=  SELECT_DEGREE."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['studentFName']) || trim($REQUEST_DATA['studentFName']) == '')) {
        $errorMessage .= ENTER_STUDENT_FIRST_NAME."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['studentNo']) || trim($REQUEST_DATA['studentNo']) == '')) {
        $errorMessage .= ENTER_STUDENT_CONTACT_NO."\n";
    }

    if ($errorMessage == '' && (!isset($REQUEST_DATA['studentNationality']) || trim($REQUEST_DATA['studentNationality']) == '')) {
        $errorMessage .= SELECT_NATIONALITY."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['fatherName']) || trim($REQUEST_DATA['fatherName']) == '')) {
        $errorMessage .= ENTER_FATHER_NAME."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['correspondeceAddress1']) || trim($REQUEST_DATA['correspondeceAddress1']) == '')) {
        $errorMessage .= ENTER_STUDENT_CONTACT_NO."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['correspondenceCountry']) || trim($REQUEST_DATA['correspondenceCountry']) == '')) {
       $errorMessage .= SELECT_COUNTRY."\n";
   }
   if ($errorMessage == '' && (!isset($REQUEST_DATA['correspondenceStates']) || trim($REQUEST_DATA['correspondenceStates']) == '')) {
       $errorMessage .= SELECT_STATE."\n";
   }
   if ($errorMessage == '' && (!isset($REQUEST_DATA['correspondenceCity']) || trim($REQUEST_DATA['correspondenceCity']) == '')) {
     $errorMessage .= SELECT_CITY."\n";
   }
*/

	
	if (trim($errorMessage) == '') {

       /* START: function to check duplicate comp. exam. roll no./ application form no. check */
        $entranceExam    = trim($REQUEST_DATA['entranceExam']);
        $compExamRollNo  = trim($REQUEST_DATA['compExamRollNo']);
        $studentId = trim($REQUEST_DATA['studentId']);
        $applicationNo = trim($REQUEST_DATA['applicationNo']);

        $studentDomicile = trim($REQUEST_DATA['studentDomicile']);
        $studentCategory = trim($REQUEST_DATA['studentCategory']);
		$dateofBirth = trim($REQUEST_DATA['studentDob']);
        $enquiryDate = trim($REQUEST_DATA['enquiryDate']);
		$dateofBirthTime = strtotime($dateofBirth);
		$enquiryDateTime = strtotime($enquiryDate);
		if ($dateofBirthTime > $enquiryDateTime) {
			echo NOT_VALID_DATE;
			die;
		}

        if($studentDomicile=='') {
          $studentDomicile='';
        }
        if($studentCategory=='') {
          $studentCategory='';
        }

        $chk=0;
        $conditions = "  a.instituteId = $instituteId AND a.sessionId = $sessionId ";
        if($entranceExam!='') {
           $conditions .= " AND a.compExamRank = '$entranceExam' ";
        }
        if($compExamRollNo!='') {
           $conditions .= " AND a.compExamRollNo = '$compExamRollNo' ";
           $chk=1;
        }

        if($chk==1) {
            if($studentId!=''){
               $conditions .= " AND a.studentId != '$studentId' ";
            }
            $returnStatus = StudentEnquiryManager::getInstance()->getStudentList($conditions);
            if($returnStatus===false){
              echo FAILURE;
              die;
            }
            $cnt = count($returnStatus);
            if($cnt>0) {
              echo COMP_EXAM_ROLLNO_EXIST;
              die;
            }
        }
        if($chk==1) {
            if($studentId!=''){
               $conditions .= " AND a.studentId != '$studentId' ";
            }
            $returnStatus = StudentEnquiryManager::getInstance()->getStudentList($conditions);
            if($returnStatus===false){
              echo FAILURE;
              die;
            }
            $cnt = count($returnStatus);
            if($cnt>0) {
              echo COMP_EXAM_ROLLNO_EXIST;
              die;
            }
        }

        if($applicationNo!='') {
           $conditions = " WHERE applicationNo = '$applicationNo' ";
           if($studentId!=''){
             $conditions .= " AND studentId != '$studentId' ";
           }
           $returnStatus = StudentEnquiryManager::getInstance()->getStudentEnquiryData($conditions);
           if($returnStatus===false){
             echo FAILURE;
             die;
           }
           $cnt = count($returnStatus);
           if($cnt>0) {
             echo APPLICATION_NO_EXIST;
             die;
           }
        }

        /* END: function to check duplicate comp. exam. roll no./ application form no. check */

        if(trim($REQUEST_DATA['cityNameExtra'])!=''){ //check whether new city is added or not
            $ret1=StudentEnquiryManager::getInstance()->getCity(' WHERE cityName="'.trim(add_slashes($REQUEST_DATA['cityNameExtra'])).'"');
            if(trim($ret1[0]['cityCode'])!=''){   //check for duplicate
                echo CITY_NAME_ALREADY_EXIST;
                die;
            }
            $ret=StudentEnquiryManager::getInstance()->addCity(add_slashes($REQUEST_DATA['cityNameExtra']),add_slashes($REQUEST_DATA['cityNameExtra']),$REQUEST_DATA['correspondenceStates']);
            if($ret===false){
                echo FAILURE;
                die;
            }
            $cityId=SystemDatabaseManager::getInstance()->lastInsertId();
        }
        else{
            $cityId=$REQUEST_DATA['correspondenceCity'];
        }

        $correspondenceStates=trim($REQUEST_DATA['correspondenceStates']);
        $correspondenceCountry=trim($REQUEST_DATA['correspondenceCountry']);
        $studentNationality=trim($REQUEST_DATA['studentNationality']);

        if($studentNationality=='') {
          $studentNationality='';
        }
        if($correspondenceStates=='') {
          $correspondenceStates='';
        }
        if($correspondenceCountry=='') {
          $correspondenceCountry='';
        }

        if(isset($REQUEST_DATA['studentId']) && trim($REQUEST_DATA['studentId'])!=''){
           $returnStatus = StudentEnquiryManager::getInstance()->editStudentEnquiry(trim($REQUEST_DATA['studentId']),$cityId, $instituteId, $sessionId,$studentDomicile,$studentCategory,$correspondenceStates,$correspondenceCountry,$studentNationality);
        }
        else{
           $returnStatus = StudentEnquiryManager::getInstance()->addStudentEnquiry($cityId, $instituteId, $sessionId,$studentDomicile,$studentCategory,$correspondenceStates,$correspondenceCountry,$studentNationality);
        }

        if($returnStatus === false){
             echo FAILURE;
             die;
        }
        else{
            echo SUCCESS;
            die;
        }
    }
    else {
        echo $errorMessage;
        die;
    }
// $History: ajaxInitAdd.php $
//
//*****************  Version 5  *****************
//User: Parveen      Date: 4/13/10    Time: 4:36p
//Updated in $/LeapCC/Library/StudentEnquiry
//query and validation format updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 3/05/10    Time: 4:58p
//Updated in $/LeapCC/Library/StudentEnquiry
//validation & condition format updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 3/05/10    Time: 1:08p
//Updated in $/LeapCC/Library/StudentEnquiry
//comp. exam roll no. validation check added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/02/09    Time: 5:30p
//Updated in $/LeapCC/Library/StudentEnquiry
//validation modify & formatting update
//
//*****************  Version 1  *****************
//User: Administrator Date: 29/05/09   Time: 16:51
//Created in $/LeapCC/Library/StudentEnquiry
//Created "Student Enquiry" module
?>