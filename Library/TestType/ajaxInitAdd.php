<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A TESTTYPE
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (14.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TestTypesMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if ($errorMessage == '' && (!isset($REQUEST_DATA['testtypeName']) || trim($REQUEST_DATA['testtypeName']) == '')) {
        $errorMessage .= ENTER_TESTTYPE_NAME."\n";    
    }
    if (!isset($REQUEST_DATA['testtypeCode']) || trim($REQUEST_DATA['testtypeCode']) == '') {
        $errorMessage .= ENTER_TESTTYPE_CODE."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['testtypeAbbr']) || trim($REQUEST_DATA['testtypeAbbr']) == '')) {
        $errorMessage .= ENTER_TESTTYPE_ABBR."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['universityId']) || trim($REQUEST_DATA['universityId']) == '')) {
        $errorMessage .= SELECT_UNIVERSITY."\n";       
    }
    /*if (!isset($REQUEST_DATA['degreeId']) || trim($REQUEST_DATA['degreeId']) == '') {
        $errorMessage .= SELECT_DEGREE."\n";     
    }
    if (!isset($REQUEST_DATA['branchId']) || trim($REQUEST_DATA['branchId']) == '') {
        $errorMessage .= SELECT_BRANCH."\n";     
    }*/
    if (!isset($REQUEST_DATA['weightageAmount']) || trim($REQUEST_DATA['weightageAmount']) == '') {
        $errorMessage .= ENTER_TESTTYPE_WEIGHTAGE."\n";     
    }
    /*if (!isset($REQUEST_DATA['weightagePercentage']) || trim($REQUEST_DATA['weightagePercentage']) == '') {
        $errorMessage .= ENTER_TESTTYPE_WEIGHTAGE_PERCENTAGE."\n"; 
    }*/
    /*if (!isset($REQUEST_DATA['subjectId']) || trim($REQUEST_DATA['subjectId']) == '') {
        $errorMessage .= SELECT_SUBJECT."\n";    
    }
    
    if (!isset($REQUEST_DATA['studyPeriodId']) || trim($REQUEST_DATA['studyPeriodId']) == '') {
        $errorMessage .= SELECT_STUDY_PERIOD."\n";  
    }*/
    if ($errorMessage == '' && (!isset($REQUEST_DATA['evaluationCriteriaId']) || trim($REQUEST_DATA['evaluationCriteriaId']) == '')) {
        $errorMessage .= SELECT_TESTTYPE_EVALUATION."\n";  
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['cnt']) || trim($REQUEST_DATA['cnt']) == '')) {
        $errorMessage .= ENTER_TESTTYPE_COUNT."\n";  
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['sortOrder']) || trim($REQUEST_DATA['sortOrder']) == '')) {
        $errorMessage .= ENTER_TESTTYPE_SORT_ORDER."\n";  
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['subjectTypeId']) || trim($REQUEST_DATA['subjectTypeId']) == '')) {
        $errorMessage .= SELECT_SUBJECT_TYPE."\n";  
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['conductingAuthority']) || trim($REQUEST_DATA['conductingAuthority']) == '')) {
        $errorMessage .= SELECT_CONDUCTING_AUTHORITY."\n";  
    }
    
    if(trim($REQUEST_DATA['weightageAmount'])>100){
        echo WEIGHTAGE_AMOUNT_RESTRICTION;
        die;
    }
    if(trim($REQUEST_DATA['cnt'])>100){
        echo CNT_AMOUNT_RESTRICTION;
        die;
    }
    if(trim($REQUEST_DATA['sortOrder'])>100){
        echo SORT_ORDER_AMOUNT_RESTRICTION;
        die;
    }
  
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/TestTypeManager.inc.php");
        $foundArray = TestTypeManager::getInstance()->getTestType(' WHERE UCASE(testTypeCode)="'.add_slashes(strtoupper(trim($REQUEST_DATA['testtypeCode']))).'"');
        if(trim($foundArray[0]['testTypeCode'])=='') {  //DUPLICATE CHECK
            $returnStatus = TestTypeManager::getInstance()->addTestType();
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else {
            echo TESTTYPE_ALREADY_EXIST;
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitAdd.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/10/09    Time: 14:19
//Updated in $/LeapCC/Library/TestType
//Done bug fixing.
//Bug ids---
//00001621,00001644,00001645,00001646,
//00001647,00001711
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 3/25/09    Time: 6:34p
//Updated in $/LeapCC/Library/TestType
//modified to show test type category 
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/16/09    Time: 6:24p
//Updated in $/LeapCC/Library/TestType
//modified for test type & put test type category
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/TestType
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 11/06/08   Time: 11:10a
//Updated in $/Leap/Source/Library/TestType
//Added access rules
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/20/08    Time: 1:59p
//Updated in $/Leap/Source/Library/TestType
//Added standard messages
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 7/09/08    Time: 7:18p
//Updated in $/Leap/Source/Library/TestType
//Add `Select` as default selected value in dropdowns of University,
//Degree, Branch, Study Period, Evaluation Criteria, subject and subject
//type.
//and made modifications so that data is  being populated in study period
//dropdown
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/01/08    Time: 1:04p
//Updated in $/Leap/Source/Library/TestType
//Modified DataBase Column names
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/19/08    Time: 3:01p
//Updated in $/Leap/Source/Library/TestType
//Adding Extra Fields Done
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/16/08    Time: 10:26a
//Updated in $/Leap/Source/Library/TestType
//Done
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/14/08    Time: 3:41p
//Created in $/Leap/Source/Library/TestType
//Initial CheckIn
?>