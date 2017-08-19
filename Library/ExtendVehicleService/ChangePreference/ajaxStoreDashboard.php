<?php
//-------------------------------------------------------
// THIS FILE IS USED TO POPULATE CITY LIST
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
$roleId=$sessionHandler->getSessionVariable('RoleId');
$userId=$sessionHandler->getSessionVariable('UserId');
if($roleId=='' or $userId==''){
    die;
}
UtilityManager::headerNoCache();
$string=trim($_POST['dashboardLayout']);
if($string!=''){
 require_once(MODEL_PATH . "/ThemeManager.inc.php");
 $themeManager =  ThemeManager::getInstance();
 if($themeManager->changeDashBoardLayout($string,$userId) ) {
 }
}
else{
    die;
}

// $History: ajaxGetValues.php $
?>