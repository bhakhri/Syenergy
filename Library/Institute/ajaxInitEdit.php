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
define('MODULE','InstituteMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
   if ($errorMessage == '' && (!isset($REQUEST_DATA['instituteName']) || trim($REQUEST_DATA['instituteName']) == '')) {
        $errorMessage .= ENTER_INSTITUTE_NAME."\n";
    }
    if (!isset($REQUEST_DATA['instituteCode']) || trim($REQUEST_DATA['instituteCode']) == '') {
        $errorMessage .= ENTER_INSTITUTE_CODE."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['instituteAbbr']) || trim($REQUEST_DATA['instituteAbbr']) == '')) {
        $errorMessage .= ENTER_INSTITUTE_ABBR."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['instituteEmail']) || trim($REQUEST_DATA['instituteEmail']) == '')) {
        $errorMessage .= ENTER_INSTITUTE_EMAIL."\n";
    }
    if (!isset($REQUEST_DATA['instituteAddress1']) || trim($REQUEST_DATA['instituteAddress1']) == '') {
        $errorMessage .= ENTER_INSTITUTE_ADDRESS1."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['instituteAddress2']) || trim($REQUEST_DATA['instituteAddress2']) == '')) {
        $errorMessage .= ENTER_INSTITUTE_ADDRESS2."\n";       
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['instituteWebsite']) || trim($REQUEST_DATA['instituteWebsite']) == '')) {
        $errorMessage .= ENTER_INSTITUTE_WEBSITE."\n";       
    }
    /*
    if ($errorMessage == '' && (!isset($REQUEST_DATA['employeeId']) || trim($REQUEST_DATA['employeeId']) == '')) {
        $errorMessage .= "Enter employee name \n";
    }
    */
    if ($errorMessage == '' && (!isset($REQUEST_DATA['employeePhone']) || trim($REQUEST_DATA['employeePhone']) == '')) {
         $errorMessage .= ENTER_INSTITUTE_CONTACT_NO."\n"; 
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
    if ($errorMessage == '' && (!isset($REQUEST_DATA['instituteLogo']) || trim($REQUEST_DATA['instituteLogo']) == '')) {
        $errorMessage .= "Enter institute logo \n";
    }
    */
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/InstituteManager.inc.php");
        $foundArray = InstituteManager::getInstance()->getInstitute(' WHERE ( UCASE(instituteCode)="'.add_slashes(strtoupper(trim($REQUEST_DATA['instituteCode']))).'" OR UCASE(instituteName)="'.add_slashes(strtoupper(trim($REQUEST_DATA['instituteName']))).'" OR UCASE(instituteAbbr)="'.add_slashes(strtoupper(trim($REQUEST_DATA['instituteAbbr']))).'" ) AND instituteId!='.$REQUEST_DATA['instituteId']);
        if(trim($foundArray[0]['instituteCode'])=='') {  //DUPLICATE CHECK
            $returnStatus = InstituteManager::getInstance()->editInstitute($REQUEST_DATA['instituteId']);
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                //Id to upload logo
                $sessionHandler->setSessionVariable('IdToFileUpload',$REQUEST_DATA['instituteId']); 
                echo SUCCESS;
            }
        }
        else {
            if( strtoupper(trim($foundArray[0]['instituteCode']))==strtoupper(trim($REQUEST_DATA['instituteCode'])) ){
              echo INSTITUTE_ALREADY_EXIST;
              die;
            }
            if( strtoupper(trim($foundArray[0]['instituteName']))==strtoupper(trim($REQUEST_DATA['instituteName'])) ){
              echo INSTITUTE_NAME_ALREADY_EXIST;
              die;
            }
            if( strtoupper(trim($foundArray[0]['instituteAbbr']))==strtoupper(trim($REQUEST_DATA['instituteAbbr'])) ){
              echo INSTITUTE_ABBR_ALREADY_EXIST;
              die;
            }
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitEdit.php $
//
//*****************  Version 2  *****************
//User: Administrator Date: 8/06/09    Time: 14:13
//Updated in $/LeapCC/Library/Institute
//Done bug fixing.
//bug ids---> 1318 to 1329 ,Leap bugs4.doc(5 to 10,12,20)
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Institute
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:31p
//Updated in $/Leap/Source/Library/Institute
//Added access rules
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/20/08    Time: 12:28p
//Updated in $/Leap/Source/Library/Institute
//Added standard messages
//
//*****************  Version 4  *****************
//User: Pushpender   Date: 6/23/08    Time: 5:23p
//Updated in $/Leap/Source/Library/Institute
//modified the code of file upload and replaced ' with " and <br/> to \n
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 6/20/08    Time: 7:43p
//Updated in $/Leap/Source/Library/Institute
//Placed the code to upload and replaced ' with "
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/14/08    Time: 3:19p
//Updated in $/Leap/Source/Library/Institute
//Modifying Done
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/14/08    Time: 7:30p
//Created in $/Leap/Source/Library/Institute
//Initial Checkin
?>
