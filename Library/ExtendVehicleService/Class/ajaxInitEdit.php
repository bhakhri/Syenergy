<?php
//-------------------------------------------------------
// Purpose: To update class table data
//
// Author : Rajeev Aggarwal
// Created on : (01.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['degreeDuration']) || trim($REQUEST_DATA['degreeDuration']) == '') {
        $errorMessage .= "Enter degree duration \n";
    }
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/ClassManager.inc.php");
        $foundArray = ClassManager::getInstance()->getClass(' WHERE UCASE(className)="'.add_slashes(strtoupper($REQUEST_DATA['className'])).'" AND classId!='.$REQUEST_DATA['classId']);
        if(trim($foundArray[0]['className'])=='') {  //DUPLICATE CHECK
            $returnStatus = ClassManager::getInstance()->editClass($REQUEST_DATA['classId']);
            if($returnStatus === false) {
                echo FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else {
            echo 'The class name you entered already exists.';
        }
    }
    else {
        echo $errorMessage;
    }
    
    
// $History: ajaxInitEdit.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Class
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/02/08    Time: 10:59a
//Created in $/Leap/Source/Library/Class
//intial checkin
?>

