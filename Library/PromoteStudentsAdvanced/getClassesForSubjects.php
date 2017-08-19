<?php
//-------------------------------------------------------
// Purpose: To store the records of class in array from the database functionality
//
// Author : Ajinder Singh
// Created on : (28-jan-2010)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PromoteStudentsAdvanced');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();
require_once(MODEL_PATH . "/CommonQueryManager.inc.php");

$resultArray = CommonQueryManager::getInstance()->geClass();

echo json_encode($resultArray);

// $History: getClassesForSubjects.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 1/29/10    Time: 3:41p
//Created in $/LeapCC/Library/PromoteStudentsAdvanced
//file added for new interface of session end activities
//


?>