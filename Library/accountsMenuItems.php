<?php
//-------------------------------------------------------
//  This File contains menu items for accounts module.
//
//
// Author :Ajinder Singh
// Created on : 10-Aug-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
		if (defined('INCLUDE_ACCOUNTS') and (INCLUDE_ACCOUNTS === true)) {

			$accountsSetupMenu = Array();
			$accountsSetupMenu[] = Array(SET_MENU_HEADING, "Accounts");
			$accountsSetupMenu[] = Array(MAKE_MENU, "Setup", Array(
																			Array('CompanySelect','Select Company',UI_HTTP_ACCOUNTS_PATH . '/listCompanySelect.php'), 
																			Array('CompanyCreate','Company Master',UI_HTTP_ACCOUNTS_PATH . '/listCompany.php'),
				                                                            Array('Groups','Groups Master',UI_HTTP_ACCOUNTS_PATH . '/listGroups.php'), 
																			Array('Ledgers','Ledgers Master',UI_HTTP_ACCOUNTS_PATH . '/listLedger.php')
															));

			$accountsSetupMenu[] = Array(MAKE_MENU, "Process", Array(
																			Array('Voucher','Voucher Master',UI_HTTP_ACCOUNTS_PATH . '/listVoucher.php')
															));

			$accountsSetupMenu[] = Array(MAKE_MENU, "Reports", Array(
																			Array('BalanceSheet', 'Balance Sheet', UI_HTTP_ACCOUNTS_PATH . '/listReport.php?t=bs'),
																			Array('ProfitLoss', 'Profit & Loss', UI_HTTP_ACCOUNTS_PATH . '/listReport.php?t=pl'),
				Array('TrialBalance', 'Trial Balance', UI_HTTP_ACCOUNTS_PATH . '/listReport.php?t=tb'), 
				Array('DayBook', 'Day Book', UI_HTTP_ACCOUNTS_PATH . '/listReport.php?t=db'),
				Array('CashBook', 'Cash Book', UI_HTTP_ACCOUNTS_PATH . '/listReport.php?t=cb'),
				Array('LedgerAccount', 'Ledger Account', UI_HTTP_ACCOUNTS_PATH . '/listReport.php?t=lr'),
				Array('GroupSummary', 'Group Summary', UI_HTTP_ACCOUNTS_PATH . '/listReport.php?t=gsd'),
				Array('GroupVouchers', 'Group Vouchers', UI_HTTP_ACCOUNTS_PATH . '/listReport.php?t=gv'),
				Array('VoucherRegister', 'Voucher Type Register', UI_HTTP_ACCOUNTS_PATH . '/listReport.php?t=vr')

															));
			/*
			$changePasswordMenu = Array();
			$changePasswordMenu[] = Array(MAKE_HEADING_MENU, "ChangePassword, Change Password, ".UI_HTTP_PATH."/changePassword.php");
			*/

			
			$allMenus[] = $accountsSetupMenu;
			
			//$allMenus[] = $changePasswordMenu;

		}


// $History : accountsMenuItems.php $
//


?>