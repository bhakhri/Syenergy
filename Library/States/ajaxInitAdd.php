<?php
//-------------------------------------------------------
// Purpose: To add state detail
//
// Author : Pushpender Kumar Chauhan
// Created on : (11.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StateMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['stateCode']) || trim($REQUEST_DATA['stateCode']) == '') {
        $errorMessage .= ENTER_STATE_CODE."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['stateName']) || trim($REQUEST_DATA['stateName']) == '')) {
        $errorMessage .= ENTER_STATE_NAME."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['countries']) || trim($REQUEST_DATA['countries']) == '')) {
        $errorMessage .= SELECT_COUNTRY."\n";
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/StatesManager.inc.php");
        $foundArray = StatesManager::getInstance()->getState(' WHERE ( UCASE(stateCode)="'.add_slashes(strtoupper($REQUEST_DATA['stateCode'])).'" OR LCASE(stateName)= "'.add_slashes(trim(strtolower($REQUEST_DATA['stateName']))).'") AND countryId = '.trim($REQUEST_DATA['countries']));

        if(trim($foundArray[0]['stateCode'])=='') {  //DUPLICATE CHECK
            $returnStatus = StatesManager::getInstance()->addState();
            if($returnStatus === false) {
                echo FAILURE;
            }
            else {
                echo SUCCESS;           
            }
        }
        else {
			   if(strtolower($foundArray[0]['stateCode'])==trim(strtolower($REQUEST_DATA['stateCode']))) {
				 echo STATE_ALREADY_EXIST;
				 die;
			   }
			   else if(strtolower($foundArray[0]['stateName'])==trim(strtolower($REQUEST_DATA['stateName']))) {
				   echo STATE_NAME_ALREADY_EXIST;
				   die;
			   }
			}
    }
    else {
        echo $errorMessage;
    }
   
    
// $History: ajaxInitAdd.php $    
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 10/26/09   Time: 6:44p
//Updated in $/LeapCC/Library/States
//fixed bug no.0001604
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/10/09    Time: 5:38p
//Created in $/LeapCC/Library/States
//copy from sc with modifications
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 6/03/09    Time: 4:12p
//Updated in $/Leap/Source/Library/States
//fixed bug nos. 1198 to 1206 of bug4.doc
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 6/03/09    Time: 2:44p
//Updated in $/Leap/Source/Library/States
//fixed bug nos.1213,1219,1220,1221
//
//*****************  Version 5  *****************
//User: Pushpender   Date: 8/27/08    Time: 3:45p
//Updated in $/Leap/Source/Library/States
//optimized code and  removed trailing spaces
//
//*****************  Version 4  *****************
//User: Pushpender   Date: 8/06/08    Time: 2:01p
//Updated in $/Leap/Source/Library/States
//Replaced centralised messages variables 
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 6/18/08    Time: 7:56p
//Updated in $/Leap/Source/Library/States
//changed duplicate message and single quote to double quotes in error
//messages
//
//*****************  Version 2  *****************
//User: Administrator Date: 6/13/08    Time: 3:46p
//Updated in $/Leap/Source/Library/States
//To add comments and Refine the code: DONE
?>