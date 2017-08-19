<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A CITY 
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifTeacherNotLoggedIn();
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['cityCode']) || trim($REQUEST_DATA['cityCode']) == '') {
        $errorMessage .= 'Enter city code <br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['cityName']) || trim($REQUEST_DATA['cityName']) == '')) {
        $errorMessage .= 'Enter city name <br/>';
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['states']) || trim($REQUEST_DATA['states']) == '')) {
        $errorMessage .= 'Enter state name <br/>';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/CityManager.inc.php");
        $foundArray = CityManager::getInstance()->getCity(' WHERE UCASE(cityCode)="'.add_slashes(strtoupper($REQUEST_DATA['cityCode'])).'"');
        if(trim($foundArray[0]['cityCode'])=='') {  //DUPLICATE CHECK
            $returnStatus = CityManager::getInstance()->addCity();
            if($returnStatus === false) {
                $errorMessage = FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else {
            echo "This City Code already exists. Please provide a new City Code";
        }
    }
    else {
        echo $errorMessage;
    }
?>

<?php
// $History: scAjaxInitAdd.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScStudentActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Library/Teacher/ScStudentActivity
?>