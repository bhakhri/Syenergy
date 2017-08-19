<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A DEGREE
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (13.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DegreeMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
   if (!isset($REQUEST_DATA['degreeCode']) || trim($REQUEST_DATA['degreeCode']) == '') {
        $errorMessage .= ENTER_DEGREE_CODE."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['degreeName']) || trim($REQUEST_DATA['degreeName']) == '')) {
        $errorMessage .= ENTER_DEGREE_NAME."\n"; 
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['degreeAbbr']) || trim($REQUEST_DATA['degreeAbbr']) == '')) {
        $errorMessage .= ENTER_DEGREE_ABBR."\n"; 
    }

    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/DegreeManager.inc.php");
        $foundArray = DegreeManager::getInstance()->getDegree(' WHERE ( UCASE(degreeCode)="'.add_slashes(strtoupper($REQUEST_DATA['degreeCode'])).'" OR UCASE(degreeName)="'.add_slashes(strtoupper($REQUEST_DATA['degreeName'])).'" OR UCASE(degreeAbbr)="'.add_slashes(strtoupper($REQUEST_DATA['degreeAbbr'])).'" ) AND degreeId!='.$REQUEST_DATA['degreeId']);
        if(trim($foundArray[0]['degreeCode'])=='') {  //DUPLICATE CHECK
            $returnStatus = DegreeManager::getInstance()->editDegree($REQUEST_DATA['degreeId']);
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else {
            if(strtoupper($REQUEST_DATA['degreeCode'])==trim(strtoupper($foundArray[0]['degreeCode']))){ 
             echo DEGREE_ALREADY_EXIST ;
             die;
           }
          if(strtoupper($REQUEST_DATA['degreeName'])==trim(strtoupper($foundArray[0]['degreeName']))){ 
             echo DEGREE_NAME_ALREADY_EXIST ;
             die;
           }
          if(strtoupper($REQUEST_DATA['degreeAbbr'])==trim(strtoupper($foundArray[0]['degreeAbbr']))){ 
             echo DEGREE_ABBR_ALREADY_EXIST ;
             die;
          }  
          echo DEGREE_ALREADY_EXIST ;
          die;
        }
    }
    else {
        echo $errorMessage;
    }

// $History: ajaxInitEdit.php $
//
//*****************  Version 2  *****************
//User: Administrator Date: 29/05/09   Time: 11:43
//Updated in $/LeapCC/Library/Degree
//Done bug fixing------ Issues [28-May-09]Build# cc0007
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Degree
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:27p
//Updated in $/Leap/Source/Library/Degree
//Added access rules
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/20/08    Time: 2:10p
//Updated in $/Leap/Source/Library/Degree
//Added standard messages
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/18/08    Time: 2:38p
//Updated in $/Leap/Source/Library/Degree
//Removing errors done
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/16/08    Time: 7:24p
//Updated in $/Leap/Source/Library/Degree
//Removing degreeDuratioin Done
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/13/08    Time: 11:34a
//Updated in $/Leap/Source/Library/Degree
//Complete
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/13/08    Time: 10:05a
//Created in $/Leap/Source/Library/Degree
//Initial checkin
?>
