<?php
//-------------------------------------------------------
// Purpose: To change theme upon selection by the user
// Author : Dipanjan Bhattacharjee
// Created on : (17.12.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::headerNoCache();
$userId=$sessionHandler->getSessionVariable('UserId');
if(!isset($userId) or trim($userId)==0 or trim($userId)==''){
    redirectBrowser(UI_HTTP_PATH.'/indexHome.php?z=1');
}
    $errorMessage='';
    
    if (!isset($REQUEST_DATA['themeId']) || trim($REQUEST_DATA['themeId']) == '') {
        $errorMessage = 'Invalid Theme';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/ThemeManager.inc.php");
        $themeManager =  ThemeManager::getInstance();
        
		
		/* START: User Pref Updation*/
		$userExistPrefArr = $themeManager->checkUserPref($userId);
		If(empty($userExistPrefArr[0]['userExists'])){
		
			$returnStatus = $themeManager->insertUserPref($userId);
			if($returnStatus === false) {
				$errorMessage = FAILURE;
			}
		}
		/* END: User Pref Updation*/
		   
        if($themeManager->changeTheme($REQUEST_DATA['themeId'],$userId) ) {
                //change user's theme id
                $sessionHandler->setSessionVariable('UserThemeId',$REQUEST_DATA['themeId']);
                echo CHANGE_THEME_OK;
            }
           else {
                echo CHANGE_THEME_NOK;
            }
    }
    else {
        echo $errorMessage;
    }
// $History: changePreference.php $    
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 8/11/09    Time: 2:52p
//Updated in $/LeapCC/Library/ChangePreference
//Updated user prefs logic to insert record in user_prefs if respective
//user doesnot exists
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 6/08/09    Time: 3:01p
//Created in $/LeapCC/Library/ChangePreference
//file added for changing themes.
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 17/12/08   Time: 13:52
//Created in $/Leap/Source/Library/ChangePreference
//Theme change done
?>

