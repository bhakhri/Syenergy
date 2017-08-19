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
	$mode = add_slashes(trim($REQUEST_DATA['mode']));

	$accessMode = strtolower($mode);
	define('MODULE','CompanySelect');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn(true);
	//UtilityManager::ifCompanyNotSelected();
	UtilityManager::headerNoCache();

	$companyId	=	add_slashes(trim($REQUEST_DATA['companyId']));

	require_once(MODEL_PATH . '/Accounts/CompanyManager.inc.php');
	$companyManager = CompanyManager::getInstance();
	$companyCountArray = $companyManager->isCompanyValid($companyId);
	$companyCount = $companyCountArray[0]['cnt'];
	if ($companyCount == 0) {
		echo INVALID_COMPANY_SELECTED;
		die;
	}
	
	$companyNameArray = $companyManager->getCompaniesList(" WHERE companyId = $companyId");
	$companyName = $companyNameArray[0]['companyName'];
	$financialYear = $companyNameArray[0]['financialYear'];

	$sessionHandler->setSessionVariable('CompanyId', $companyId);
	$sessionHandler->setSessionVariable('CompanyName', $companyName);
	$sessionHandler->setSessionVariable('FinancialYear', $financialYear);

	echo SUCCESS;

// $History: ajaxInitSelect.php $
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