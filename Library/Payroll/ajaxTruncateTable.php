<?php
////  This File is used to clear the temp array
//
// Author :Abhiraj Malhotra
// Created on : 21-April-2010
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Payroll');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
   
    $sessionHandler->unsetSessionVariable('tempHeadArray');

?>