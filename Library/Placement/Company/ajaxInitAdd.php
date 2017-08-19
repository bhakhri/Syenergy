<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A UNIVERSITY
//
//
// Author : Dipanjan Bhattacharjee
// Modified By: Pushpender Kumar Chauhan
// Created on : (14.06.2008 )
 //modified on: 20.06.2008
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PlacementComapanyMaster');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
	$errorMessage ='';
	if ($errorMessage == '' && (!isset($REQUEST_DATA['companyName']) || trim($REQUEST_DATA['companyName']) == '')) {
		$errorMessage .= ENTER_PLACEMENT_COMPANY_NAME."\n";
	}
	if (!isset($REQUEST_DATA['companyCode']) || trim($REQUEST_DATA['companyCode']) == '') {
		$errorMessage .= ENTER_PLACEMENT_COMPANY_CODE."\n";
	}
	if ($errorMessage == '' && (!isset($REQUEST_DATA['contactAddress']) || trim($REQUEST_DATA['contactAddress']) == '')) {
		$errorMessage .= ENTER_PLACEMENT_COMPANY_ADDRESS."\n";
	}
	if ($errorMessage == '' && (!isset($REQUEST_DATA['contactPerson']) || trim($REQUEST_DATA['contactPerson']) == '')) {
		$errorMessage .= ENTER_PLACEMENT_COMPANY_CONTACT_PERSON."\n";
	}
	if (!isset($REQUEST_DATA['designation']) || trim($REQUEST_DATA['designation']) == '') {
		$errorMessage .= ENTER_PLACEMENT_COMPANY_PERSON_DESIGNATION."\n"; 
	}
	if ($errorMessage == '' && (trim($REQUEST_DATA['landline'])!=='')) {
		if(!is_numeric($REQUEST_DATA['landline'])) {
			$errorMessage .= ENTER_VALID_LANDLINE_NUMBER."\n";
		}
	}
	if ($errorMessage == '' && (!isset($REQUEST_DATA['mobileNo']) || trim($REQUEST_DATA['mobileNo']) == '')) {
		$errorMessage .= ENTER_PLACEMENT_COMPANY_MOBILE_NO."\n";
	}
	if ($errorMessage == '' && isset($REQUEST_DATA['mobileNo'])) {
		if(!is_numeric($REQUEST_DATA['mobileNo']) && (strlen($REQUEST_DATA['mobileNo'] < 10))) {
			$errorMessage .= ENTER_VALID_MOBILE_NUMBER."\n";
		}
	} 
	if ($errorMessage == '' && (!isset($REQUEST_DATA['emailId']) || trim($REQUEST_DATA['emailId']) == '')) {
		$errorMessage .= ENTER_PLACEMENT_COMPANY_EMAIL_ID."\n";
	}
   // if ($errorMessage == '' && (!isset($REQUEST_DATA['remarks']) || trim($REQUEST_DATA['remarks']) == '')) {
	//	$errorMessage .= ENTER_PLACEMENT_COMPANY_REMARKS."\n";
  //  }


    if (trim($errorMessage) == '') {
        require_once(MODEL_PATH . "/Placement/CompanyManager.inc.php");
        $foundArray = CompanyManager::getInstance()->getCompany(' AND UCASE(companyName)="'.add_slashes(trim(strtoupper($REQUEST_DATA['companyName']))).'" OR UCASE(companyCode)="'.add_slashes(trim(strtoupper($REQUEST_DATA['companyCode']))).'" OR UCASE(emailId)="'.add_slashes(trim(strtoupper($REQUEST_DATA['emailId']))).'"');
        if(trim($foundArray[0]['companyName'])=='') {  //DUPLICATE CHECK
            $returnStatus = CompanyManager::getInstance()->addCompany();
            if($returnStatus == false) {
                die(FAILURE);
            }
              die(SUCCESS);
        }
        else {
           if(strtoupper(trim($foundArray[0]['companyName']))==strtoupper(trim($REQUEST_DATA['companyName']))){ 
             echo PLACEMENT_COMPANY_NAME_ALREADY_EXIST;
             die;
           }
           else if(strtoupper(trim($foundArray[0]['companyCode']))==strtoupper(trim($REQUEST_DATA['companyCode']))) {
             echo PLACEMENT_COMPANY_CODE_ALREADY_EXIST;
             die;
           }
           else if(strtoupper(trim($foundArray[0]['emailId']))==strtoupper(trim($REQUEST_DATA['emailId']))) {
             echo PLACEMENT_COMPANY_EMAIL_ID_ALREADY_EXIST;
             die;
           }
           else{
               echo PLACEMENT_COMPANY_NAME_ALREADY_EXIST;
               die;
           }
        }
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitAdd.php $
?>