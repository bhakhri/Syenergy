<?php
//-------------------------------------------------------
// Purpose: To delete city detail
//
// Author : Dipanjan Bhattacharjee
// Created on : (25.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AddStudentEnquiry');
define('ACCESS','delete');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    if (!isset($REQUEST_DATA['studentId']) || trim($REQUEST_DATA['studentId']) == '') {
        $errorMessage = STUDENT_NOT_EXIST;
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/StudentEnquiryManager.inc.php");
        $studentEnquiryManager =  StudentEnquiryManager::getInstance();
            if($studentEnquiryManager->deleteStudentEnquiry($REQUEST_DATA['studentId']) ) {
                echo DELETE;
                die;
            }
           else {
                echo FAILURE;
                die;
            }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitDelete.php $    
//
//*****************  Version 1  *****************
//User: Administrator Date: 29/05/09   Time: 16:51
//Created in $/LeapCC/Library/StudentEnquiry
//Created "Student Enquiry" module
?>