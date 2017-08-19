<?php
//-------------------------------------------------------
// THIS FILE IS USED TO ADD A ITEM CATEGORY
//
//
// Author : Gurkeerat Sidhu
// Created on : (08.05.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','InventoryDeptartment');
define('ACCESS','edit');
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
		$toDate = 	$REQUEST_DATA['toDate'];
	}

	 if (trim($errorMessage) == '') {
        require_once(INVENTORY_MODEL_PATH . "/InventoryDeptartmentManager.inc.php");
		$inventoryDeptartmentManager = InventoryDeptartmentManager::getInstance();
        $foundArray = $inventoryDeptartmentManager->getInventoryDepartment(' AND (UCASE(invd.invDepttName) = "'.add_slashes(trim(strtoupper($REQUEST_DATA['inventoryDeptName']))).'" OR UCASE(invd.invDepttAbbr)="'.add_slashes(strtoupper($REQUEST_DATA['abbr'])).'") AND invd.invDepttId!='.$REQUEST_DATA['invDepttId']);
		//print_r($REQUEST_DATA);
		//die;
                 if(trim($foundArray[0]['invDepttAbbr'])=='') { //DUPLICATE CHECK  
					if(SystemDatabaseManager::getInstance()->startTransaction()) {
                   $returnStatus = $inventoryDeptartmentManager->editInventoryDepartment($REQUEST_DATA['invDepttId']);
                        if($returnStatus === false) {
                            echo FAILURE;
                        }

						$returnDeleteStatus = $inventoryDeptartmentManager->deleteInventoryDepartmentIncharge($REQUEST_DATA['invDepttId']);
							if($returnDeleteStatus === false) {
								echo FAILURE;
							}

                        $returnInchargeStatus = $inventoryDeptartmentManager->addInventoryDepartmentIncharge($REQUEST_DATA['invDepttId'],$toDate);
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
                       elseif(trim(strtoupper($foundArray[0]['invDepttName']))==trim(strtoupper($REQUEST_DATA['inventoryDeptName']))) { 
                           echo INVENTORY_DEPT_NAME_EXIST;
                           die;
                       }
                    }
    }
    else {
        echo $errorMessage;
    }
?>