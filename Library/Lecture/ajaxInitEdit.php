<?php
//-------------------------------------------------------
// THIS FILE IS USED TO DELETE A LECTURE TYPE 
//
//
// Author : Rajeev Aggarwal
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','LectureTypeMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['lectureType']) || trim($REQUEST_DATA['lectureType']) == '') {
        $errorMessage .= 'Enter lecture type <br/>';
    }
    
    if (trim($errorMessage) == '') {

        require_once(MODEL_PATH . "/LectureManager.inc.php");
        $foundArray = LectureTypeManager::getInstance()->getLectureType(' WHERE lectureName="'.add_slashes($REQUEST_DATA['lectureType']).'" AND lectureTypeId!='.$REQUEST_DATA['lectureTypeId']);
		 
        if(trim($foundArray[0]['lectureName'])=='') {  //DUPLICATE CHECK
		 
            $returnStatus = LectureTypeManager::getInstance()->editLectureType($REQUEST_DATA['lectureTypeId']);
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else {
            echo DUPLICATE;
        }
    }
    else {
        echo $errorMessage;
    }

// $History: ajaxInitEdit.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/10/09    Time: 3:15p
//Updated in $/LeapCC/Library/Lecture
//Gurkeerat: updated access defines
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Lecture
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 8/27/08    Time: 2:59p
//Updated in $/Leap/Source/Library/Lecture
//updated formatting
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/17/08    Time: 11:33a
//Updated in $/Leap/Source/Library/Lecture
//updated issue no 0000062,0000061,0000070
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 6/25/08    Time: 4:34p
//Updated in $/Leap/Source/Library/Lecture
//updated the defects and comments
?>