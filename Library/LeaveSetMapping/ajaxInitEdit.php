<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A CITY
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(MODEL_PATH . "/LeaveSetMappingManager.inc.php");
define('MODULE','LeaveSetMapping');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$mappingId=trim($REQUEST_DATA['leaveSetMappingId']);
$leaveSet=trim($REQUEST_DATA['leaveSet']);
$leaveType=trim($REQUEST_DATA['leaveType']);
$leaveTypeValue=trim($REQUEST_DATA['leaveTypeValue']);

if($mappingId==''){
    echo LEAVE_SET_MAPPING_NOT_EXIST;
    die;
}
if($leaveSet==''){
    echo SELECT_LEAVE_SET;
    die;
}

if($leaveType=='' or $leaveTypeValue==''){
    echo 'Required Parameters Missing';
    die;
}

$leaveSetMappingManager = LeaveSetMappingManager::getInstance();

//check for leave set usage
$usageArray=$leaveSetMappingManager->checkLeaveSetUsage($leaveSet);
if($usageArray[0]['cnt']!=0){
     echo DEPENDENCY_CONSTRAINT_EDIT;
     die;
} 

//checking for integrity
$chkArray=$leaveSetMappingManager->getLeaveSetMapping(' WHERE  leaveSetMappingId='.$mappingId);
if($chkArray[0]['leaveSetId']!=$leaveSet){
    echo 'Mismatched leave set found';
    die;
}

//check for duplicate mappinig
$dupArray=$leaveSetMappingManager->getLeaveSetMapping(' WHERE  leaveSetId='.$leaveSet.' AND leaveTypeId='.$leaveType.' AND leaveSetMappingId!='.$mappingId);
if($dupArray[0]['leaveSetMappingId']!=''){
    echo DUPLICATE_LEAVE_SET_TYPE;
    die;
}

//edit leave set mapping
$ret=$leaveSetMappingManager->editLeaveSetMapping($mappingId,$leaveSet,$leaveType,$leaveTypeValue);
if($ret==false){
    echo FAILURE;
    die;
}
else{
    echo SUCCESS;
    die;
}


// $History: ajaxInitEdit.php $
?>