<?php
//-------------------------------------------------------
//  This File contains showing section assignment students
//
//
// Author :Ajinder Singh
// Created on : 18-Mar-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TransferMarks');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();
$timeTableLabelId = $REQUEST_DATA['labelId'];
if(trim($timeTableLabelId)==''){
    echo 'Required Parameters Missing';
    die;
}
require_once(MODEL_PATH . "/StudentManager.inc.php");
$studentManager = StudentManager::getInstance();
$ttClassArray  = $studentManager->getTimeTableClasses($timeTableLabelId);
echo json_encode($ttClassArray);


// $History: getClassesForTransfer.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/12/09    Time: 14:53
//Updated in $/LeapCC/Library/Student
//Added server side checks for missing paramters
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 3/18/09    Time: 12:46p
//Created in $/LeapCC/Library/Student
//file added for transfer of internal marks
//




?>