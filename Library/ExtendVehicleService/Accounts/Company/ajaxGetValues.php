<?php
//-------------------------------------------------------
//  This File contains logic for company
//
//
// Author :Ajinder Singh
// Created on : 10-aug-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Company');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
//UtilityManager::ifCompanyNotSelected();
UtilityManager::headerNoCache();


if(trim($REQUEST_DATA['companyId'] ) != '') {

	require_once(MODEL_PATH . '/Accounts/CompanyManager.inc.php');
	$companyManager = CompanyManager::getInstance();

	$companyArray = $companyManager->getCompaniesList(" WHERE companyId = ".$REQUEST_DATA['companyId']);

	if(is_array($companyArray) && count($companyArray)>0 ) {  
		echo json_encode($companyArray[0]);
	}
	else {
		echo 0;
	}
	
}

// $History: ajaxGetValues.php $
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/10/09    Time: 6:47p
//Updated in $/LeapCC/Library/Accounts/Company
//removed access rights, placed accidently
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 4:44p
//Created in $/LeapCC/Library/Accounts/Company
//file added
//




?>