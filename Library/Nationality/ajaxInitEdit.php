<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A NATIONALITY
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
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();
    $errorMessage ='';
     if (!isset($REQUEST_DATA['nationName']) || trim($REQUEST_DATA['nationName']) == '') {
        $errorMessage .= 'Enter Nationality <br/>';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/NationalityManager.inc.php");
        $foundArray = NationalityManager::getInstance()->getNationality(' WHERE UCASE(nationName)="'.add_slashes(strtoupper($REQUEST_DATA['nationName'])).'"');
        if(trim($foundArray[0]['nationName'])=='') {  //DUPLICATE CHECK
            $returnStatus = NationalityManager::getInstance()->editNationality($REQUEST_DATA['nationId']);
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
?>
<?php
  // $History: ajaxInitEdit.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Nationality
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/12/08    Time: 7:12p
//Updated in $/Leap/Source/Library/Nationality
//Complted Comments
?>
