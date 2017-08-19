<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A ITEMCATEGORY
//
//
// Author : Gurkeerat Sidhu
// Created on : (08.05.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
define('MODULE','InventoryDeptartment');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

    $errorMessage ='';
    if ($errorMessage == '' && (!isset($REQUEST_DATA['inventoryDeptName']) || trim($REQUEST_DATA['inventoryDeptName']) == '')) {
        $errorMessage .= ENTER_INVENTORY_DEPT_NAME."\n";    
    }
    
    if ($errorMessage == '' && (!isset($REQUEST_DATA['abbr']) || trim($REQUEST_DATA['abbr']) == '')) {
        $errorMessage .= ENTER_ABBR."\n";    
    }

	if ($errorMessage == '' && (!isset($REQUEST_DATA['departmentType']) || trim($REQUEST_DATA['departmentType']) == '')) {
        $errorMessage .= SELECT_DEPT_TYPE."\n";    
    }

	//print_r($REQUEST_DATA);	
	
	if ($REQUEST_DATA['toDate'] == '') {
		$toDate = 'NULL';
	}
	else {
		$toDate = $REQUEST_DATA['toDate'];
	}
	    
    if (trim($errorMessage) == '') {
        require_once(INVENTORY_MODEL_PATH . "/InventoryDeptartmentManager.inc.php");
		$inventoryDeptartmentManager = InventoryDeptartmentManager::getInstance();
        $foundArray = $inventoryDeptartmentManager->getInventoryDepartment(' AND LCASE(invd.invDepttName)= "'.add_slashes(trim(strtolower($REQUEST_DATA['inventoryDeptName']))).'" OR UCASE(invd.invDepttAbbr)="'.add_slashes(strtoupper($REQUEST_DATA['abbr'])).'"');
		
                 if(trim($foundArray[0]['invDepttAbbr'])=='') {  //DUPLICATE CHECK  
					 if(SystemDatabaseManager::getInstance()->startTransaction()) {
					   $returnStatus = $inventoryDeptartmentManager->addInventoryDepartment();
							if($returnStatus === false) {
								echo FAILURE;
							}
							$lastInvDepttId = SystemDatabaseManager::getInstance()->lastInsertId();

							$returnInchargeStatus = $inventoryDeptartmentManager->addInventoryDepartmentIncharge($lastInvDepttId,$toDate);
							if($returnInchargeStatus === false) {
								echo FAILURE;
							}
							if(SystemDatabaseManager::getInstance()->commitTransaction()) {
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
                       if(trim(strtoupper($foundArray[0]['invDepttAbbr']))==trim(strtoupper($REQUEST_DATA['abbr']))){ 
							echo INVENTORY_DEPT_ABBR_EXIST;
							die;
                       }
						else if(strtolower($foundArray[0]['invDepttName'])==trim(strtolower($REQUEST_DATA['inventoryDeptName']))) { 
                           echo INVENTORY_DEPT_NAME_EXIST;
                           die;
                       }
                    }
    }
    else {
        echo $errorMessage;
    }

 
?>