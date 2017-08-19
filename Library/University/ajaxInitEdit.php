<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A UNIVERSITY
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (14.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','UniversityMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if ($errorMessage == '' && (!isset($REQUEST_DATA['universityName']) || trim($REQUEST_DATA['universityName']) == '')) {
        $errorMessage .= ENTER_UNIVERSITY_NAME."\n";
    }
    if (!isset($REQUEST_DATA['universityCode']) || trim($REQUEST_DATA['universityCode']) == '') {
        $errorMessage .= ENTER_UNIVERSITY_CODE."\n";     
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['universityAbbr']) || trim($REQUEST_DATA['universityAbbr']) == '')) {
        $errorMessage .= ENTER_UNIVERSITY_ABBR."\n";     
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['universityEmail']) || trim($REQUEST_DATA['universityEmail']) == '')) {
        $errorMessage .= ENTER_UNIVERSITY_EMAIL."\n";     
    }
    if (!isset($REQUEST_DATA['universityAddress1']) || trim($REQUEST_DATA['universityAddress1']) == '') {
        $errorMessage .= ENTER_UNIVERSITY_ADDRESS1."\n";     
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['universityAddress2']) || trim($REQUEST_DATA['universityAddress2']) == '')) {
        $errorMessage .= ENTER_UNIVERSITY_ADDRESS2."\n";     
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['universityWebsite']) || trim($REQUEST_DATA['universityWebsite']) == '')) {
        $errorMessage .= ENTER_UNIVERSITY_WEBSITE."\n";     
    } 
    if ($errorMessage == '' && (!isset($REQUEST_DATA['contactPerson']) || trim($REQUEST_DATA['contactPerson']) == '')) {
        $errorMessage .= ENTER_UNIVERSITY_CONTACT_PERSON."\n";     
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['contactNumber']) || trim($REQUEST_DATA['contactNumber']) == '')) {
        $errorMessage .= ENTER_UNIVERSITY_CONTACT_NO."\n";     
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['designation']) || trim($REQUEST_DATA['designation']) == '')) {
        $errorMessage .= SELECT_DESIGNATION."\n";     
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['country']) || trim($REQUEST_DATA['country']) == '')) {
        $errorMessage .= SELECT_COUNTRY."\n";      
    }
    if (!isset($REQUEST_DATA['states']) || trim($REQUEST_DATA['states']) == '') {
        $errorMessage .= SELECT_STATE."\n";      
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['city']) || trim($REQUEST_DATA['city']) == '')) {
        $errorMessage .= SELECT_CITY."\n";      
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['pin']) || trim($REQUEST_DATA['pin']) == '')) {
        $errorMessage .= ENTER_PIN."\n";      
    } 
    /*
    if ($errorMessage == '' && (!isset($REQUEST_DATA['universityLogo']) || trim($REQUEST_DATA['universityLogo']) == '')) {
        $errorMessage .= "Enter university logo \n";
    }
    */
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/UniversityManager.inc.php");
        $foundArray = UniversityManager::getInstance()->getUniversity(' WHERE ( UCASE(universityCode)="'.add_slashes(trim(strtoupper($REQUEST_DATA['universityCode']))).'" OR UCASE(universityAbbr)="'.add_slashes(trim(strtoupper($REQUEST_DATA['universityAbbr']))).'" ) AND universityId!='.$REQUEST_DATA['universityId']);
        if(trim($foundArray[0]['universityCode'])=='') {  //DUPLICATE CHECK
            $returnStatus = UniversityManager::getInstance()->editUniversity($REQUEST_DATA['universityId']);
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                //Id to upload logo
                $sessionHandler->setSessionVariable('IdToFileUpload',$REQUEST_DATA['universityId']); 
                echo SUCCESS;
            }
        }
        else {
           if(strtoupper(trim($foundArray[0]['universityCode']))==strtoupper(trim($REQUEST_DATA['universityCode']))){ 
             echo UNIVERSITY_ALREADY_EXIST;
             die;
           }
           else if(strtoupper(trim($foundArray[0]['universityAbbr']))==strtoupper(trim($REQUEST_DATA['universityAbbr']))) {
             echo UNIVERSITY_ABBR_ALREADY_EXIST;
             die;
           }
           else{
               echo UNIVERSITY_ALREADY_EXIST;
               die;
           }
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitEdit.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 20/10/09   Time: 18:11
//Updated in $/LeapCC/Library/University
//Done bug fixing.
//Bug ids---
//00001812
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 15/10/09   Time: 13:31
//Updated in $/LeapCC/Library/University
//Done bug fixing.
//Bug ids---
//00001787,00001788,00001789
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/University
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 11/06/08   Time: 11:16a
//Updated in $/Leap/Source/Library/University
//Added access rules
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/20/08    Time: 12:34p
//Updated in $/Leap/Source/Library/University
//Added standard messages
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/20/08    Time: 12:09p
//Updated in $/Leap/Source/Library/University
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/09/08    Time: 1:52p
//Updated in $/Leap/Source/Library/University
//Added Image upload functionality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/09/08    Time: 11:09a
//Created in $/Leap/Source/Library/University
//
//*****************  Version 4  *****************
//User: Pushpender   Date: 6/23/08    Time: 5:23p
//Updated in $/Leap/Source/Library/University
//modified the code of file upload and replaced ' with " and <br/> to \n
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 6/20/08    Time: 7:43p
//Updated in $/Leap/Source/Library/University
//Placed the code to upload and replaced ' with "
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/14/08    Time: 3:19p
//Updated in $/Leap/Source/Library/University
//Modifying Done
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/14/08    Time: 7:30p
//Created in $/Leap/Source/Library/University
//Initial Checkin
?>
