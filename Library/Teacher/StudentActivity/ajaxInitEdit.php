<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A CITY
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
        $foundArray = CityManager::getInstance()->getCity(' WHERE UCASE(cityCode)="'.add_slashes(strtoupper($REQUEST_DATA['cityCode'])).'" AND cityId!='.$REQUEST_DATA['cityId']);
        if(trim($foundArray[0]['cityCode'])=='') {  //DUPLICATE CHECK
            $returnStatus = CityManager::getInstance()->editCity($REQUEST_DATA['cityId']);
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
// $History: ajaxInitEdit.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/StudentActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/17/08    Time: 5:17p
//Updated in $/Leap/Source/Library/Teacher/StudentActivity
//ifTeacherNotLoggedIn
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/14/08    Time: 7:19p
//Created in $/Leap/Source/Library/Teacher/StudentActivity
//Initial Checkin
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/18/08    Time: 11:52a
//Updated in $/Leap/Source/Library/City
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/12/08    Time: 7:04p
//Updated in $/Leap/Source/Library/City
//Completed Comment Insertion
?>
