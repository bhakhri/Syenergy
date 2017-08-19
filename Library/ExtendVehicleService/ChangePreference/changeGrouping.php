<?php
//-------------------------------------------------------
// Purpose: To change theme upon selection by the user
// Author : Dipanjan Bhattacharjee
// Created on : (17.12.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
    
    if (!isset($REQUEST_DATA['grouping']) || trim($REQUEST_DATA['grouping']) == '') {
        $errorMessage = 'Invalid Grouping';
    }
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/ThemeManager.inc.php");
        $themeManager =  ThemeManager::getInstance();
           
        if($themeManager->changeGrouping($REQUEST_DATA['grouping'],$userId) ) {
                //change user's theme id
                $sessionHandler->setSessionVariable('UserExpandCollapseGrouping',$REQUEST_DATA['grouping']);
                echo GROUPING_THEME_OK;
            }
           else {
                echo GROUPING_THEME_OK;
            }
    }
    else {
        echo $errorMessage;
    }
// $History: changeGrouping.php $    
?>

