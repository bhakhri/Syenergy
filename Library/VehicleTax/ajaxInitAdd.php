<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A BUSSTOP 
//
//
// Author : Dipanjan Bhattacharjee
// Created on : (26.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TransportStaffMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    $errorMessage ='';
    if (!isset($REQUEST_DATA['busNo']) || trim($REQUEST_DATA['busNo']) == '') {
        $errorMessage .= SELECT_BUS."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['regnNoValidTill']) || trim($REQUEST_DATA['regnNoValidTill']) == '')) {
        $errorMessage .= ENTER_REGDNO_VALID_TILL."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['passengerTaxValidTill']) || trim($REQUEST_DATA['passengerTaxValidTill']) == '')) {
        $errorMessage .= ENTER_PASSENGER_TAX_VALID_TILL."\n";
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['roadTaxValidTill']) || trim($REQUEST_DATA['roadTaxValidTill']) == '')) {
        $errorMessage .= ENTER_ROAD_TAX_VALID_TILL."\n";    
    }
    if ($errorMessage == '' && (!isset($REQUEST_DATA['pollutionCheckValidTill']) || trim($REQUEST_DATA['pollutionCheckValidTill']) == '')) {
        $errorMessage .= ENTER_POLLUTION_CHECK_VALID_TILL."\n";    
    }
	if ($errorMessage == '' && (!isset($REQUEST_DATA['passingValidTill']) || trim($REQUEST_DATA['passingValidTill']) == '')) {
        $errorMessage .= ENTER_PASSING_VALID_TILL."\n";    
    }
    
    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/VehicleTaxManager.inc.php");
        //$foundArray = VehicleTaxManager::getInstance()->getTransportStaff(' WHERE UCASE(staffCode)="'.add_slashes(trim(strtoupper($REQUEST_DATA['staffCode']))).'"');
		if(SystemDatabaseManager::getInstance()->startTransaction()) {
			//if(trim($foundArray[0]['staffCode'])=='') {  //DUPLICATE CHECK
				$returnStatus = VehicleTaxManager::getInstance()->addVehicleTax();
				if($returnStatus === false) {
					echo FAILURE;
					die;
				}
		   /*}
		   else {
			echo STAFF_CODE_ALREADY_EXIST;
			$sessionHandler->setSessionVariable('DUPLICATE_USER',STAFF_CODE_ALREADY_EXIST);
			die;*/
		   
		   if(SystemDatabaseManager::getInstance()->commitTransaction()) {
			   //echo 'hi';
				echo SUCCESS;
				die;
			 }
			 else {
				echo FAILURE;
				die;
			}
		}
		else {
			echo FAILURE;
			die;
		}
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitAdd.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/17/09   Time: 2:21p
//Created in $/Leap/Source/Library/VehicleTax
//new ajax files for vehicle tax
//
?>