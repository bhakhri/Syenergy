<?php
//-------------------------------------------------------
//  This File contains code for fetching classes for marks transfer
//
//
// Author :Ajinder Singh
// Created on : 28-Dec-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','TransferInternalMarksAdvanced');
    define('ACCESS','add');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

$timeTableLabelId = $REQUEST_DATA['labelId'];
if(trim($timeTableLabelId)==''){
    echo 'Required Parameters Missing';
    die;
}

$labelId = $REQUEST_DATA['labelId'];

require_once(MODEL_PATH . "/TransferMarksManager.inc.php");

if (false == TransferMarksManager::getInstance()->fetchTransferMarksManager()) {
	$transferMarksManager = TransferMarksManager::getInstance();
}
else {
	$transferMarksManager = TransferMarksManager::getInstance()->fetchTransferMarksManager();
}

$transferMarksManager->unsetAllValues();
$ttClassArray  = $transferMarksManager->getTimeTableClasses($timeTableLabelId);
$transferMarksManager->storeTransferMarksManager($transferMarksManager);
echo json_encode($ttClassArray);


// for VSS
//$History: getClassesForTransfer.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 12/28/09   Time: 4:43p
//Created in $/LeapCC/Library/TransferMarksAdvanced
//initial checkin for advanced marks transfer
//














?>