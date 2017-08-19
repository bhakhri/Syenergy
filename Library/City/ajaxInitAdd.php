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
define('MODULE','CityMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
     if (!isset($REQUEST_DATA['cityCode']) || trim($REQUEST_DATA['cityCode']) == '') {
        $errorMessage .=  ENTER_CITY_CODE."\n"; 
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['cityName']) || trim($REQUEST_DATA['cityName']) == '')) {
        $errorMessage .= ENTER_CITY_NAME."\n";  
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['states']) || trim($REQUEST_DATA['states']) == '')) {
        $errorMessage .= SELECT_STATE."\n";  
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/CityManager.inc.php");
        $foundArray = CityManager::getInstance()->getCity(' WHERE stateId='.trim($REQUEST_DATA['states']).' AND ( UCASE(cityCode)="'.add_slashes(strtoupper(trim($REQUEST_DATA['cityCode']))).'" OR UCASE(cityName)="'.add_slashes(strtoupper(trim($REQUEST_DATA['cityName']))).'" )');
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
              if(strtoupper(trim($foundArray[0]['cityName']))==strtoupper(trim($REQUEST_DATA['cityName']))){  
                echo CITY_NAME_ALREADY_EXIST;
                die;
              }
              if(strtoupper(trim($foundArray[0]['cityCode']))==strtoupper(trim($REQUEST_DATA['cityCode']))){  
                echo CITY_CODE_ALREADY_EXIST;
                die;
              }
        }
    }
    else {
        echo $errorMessage;
    }
?>

<?php
// $History: ajaxInitAdd.php $
//
//*****************  Version 2  *****************
//User: Administrator Date: 13/06/09   Time: 16:29
//Updated in $/LeapCC/Library/City
//Make city code and city name unique for a state
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/City
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 11/05/08   Time: 5:35p
//Updated in $/Leap/Source/Library/City
//Added access rules
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/20/08    Time: 12:35p
//Updated in $/Leap/Source/Library/City
//Added standard messages
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/08/08    Time: 12:30p
//Updated in $/Leap/Source/Library/City
//Made modification as per Task: 2
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