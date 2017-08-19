<?php
	if (defined('INCLUDE_ACCOUNTS') and (INCLUDE_ACCOUNTS === true)) {
		//Groups
		define('ENTER_GROUP_NAME','Enter Group Name');
		define('ENTER_PARENT_GROUP','Enter Parent Group Name');
		define('LEDGERS_EXIST_UNDER_THIS_GROUP','Ledgers exist under this group.');
		define('GROUP_NAME_ALREADY_EXISTS','Group Name already exists.');
		define('INVALID_PARENT_GROUP_ENTERED','Invalid parent group entered');


		//Ledgers
		define('ENTER_LEDGER_NAME','Enter Ledger Name');
		define('ENTER_PARENT_GROUP_NAME','Enter Parent Group Name');
		define('INVALID_PARENT_GROUP_ENTERED','Invalid parent group entered');
		define('ENTER_VALID_OPENING_BALANCE','Enter valid opening balance');
		define('LEDGER_NAME_ALREADY_EXISTS','Ledger Name already exists.');
		define('VOUCHERS_ENTERED_FOR_THIS_LEDGER','Vouchers have been entered for this ledger.');


		//Company
		define('ENTER_COMPANY_NAME','Enter Company Name');
		define('ENTER_COMPANY_ADDRESS','Enter Company Address');
		define('ENTER_EMAIL_ADDRESS','Enter Email Address');
		define('ENTER_VALID_EMAIL_ADDRESS','Enter Valid Email Address');
		define('ENTER_PHONE_NUMBER','Enter Phone Number');
		define('SELECT_FINANCIAL_START','Select Financial Year Start Date');
		define('COMPANYNAME_AND_FINANCIAL_YEAR_ALREADY_EXIST','A company with same name and same financial year details already exists.');
		define('COMPANY_DELETE_CONFIRM','All Data of this company will be deleted.\nAre you sure, you want to delete this company?');
		define('FAILURE_WHILE_ADDING_NEW_COMPANY','All Data of this company will be deleted.\nAre you sure, you want to delete this company?');
		define('FAILURE_WHILE_ADDING_GROUPS_TO_COMPANY','Could not add groups to company');
		define('FAILURE_WHILE_ADDING_LEDGERS_TO_COMPANY','Could not add ledgers to company');
		define('VOUCHERS_ENTERED_ALREADY_IN_OLD_FINANCIAL_DATES','Vouchers entered already in previous financial year dates.');
		define('FAILURE_WHILE_REMOVING_COMPANY_VOUCHER_DETAILS','Could not delete voucher details.');
		define('FAILURE_WHILE_REMOVING_COMPANY_VOUCHER_MASTERS','Could not delete voucher masters.');
		define('FAILURE_WHILE_REMOVING_COMPANY_LEDGERS','Could not delete company ledgers.');
		define('FAILURE_WHILE_REMOVING_COMPANY_GROUPS','Could not delete company groups.');
		define('FAILURE_WHILE_REMOVING_COMPANY','Could not delete company.');
		define('FAILURE_WHILE_COMMITTING_TRANSACTION','Could not end the process');
		define('FAILURE_WHILE_COMMITTING_TRANSACTION','Could not start the process');


		//Company Select
		define('INVALID_COMPANY_SELECTED','Invalid Company Selected');

		//voucher
		define('INVALID_VOUCHER_TYPE','Invalid Voucher Type');
		define('ENTER_VALID_DATE','Enter valid date');
		define('VOUCHER_DATE_NOT_FALLS_IN_COMPANY_FINANCIAL_YEAR','Voucher date not in company financial year date range');
		define('INVALID_LEDGER_','Invalid ledger: ');
		define('VOUCHER_INCOMPLETE','Voucher incomplete');
		define('INVALID_VOUCHER','Voucher invalid');
		define('INVALID_VOUCHER_NO_ENTERED','Invalid voucher no. entered');
		define('ERROR_WHILE_UPDATING_VOUCHER','Error while updating voucher');
		define('ERROR_WHILE_SAVING_VOUCHER','Error while saving voucher');
}

//$History: accountsMessages.inc.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 5:22p
//Created in $/LeapCC/Library/Accounts
//file added
//



?>