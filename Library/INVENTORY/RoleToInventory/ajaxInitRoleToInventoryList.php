<?php
//-------------------------------------------------------
// Purpose: To store the records of department in array from the database, pagination and search, delete
// functionality
//
// Author : Jaineesh
// Created on : (28 July 10)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','RoleToInventory');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(INVENTORY_MODEL_PATH . "/InventoryMappingManager.inc.php");
    $inventoryMappingManager = InventoryMappingManager::getInstance();

	require_once(BL_PATH.'/HtmlFunctions.inc.php');
	$htmlFunctions = HtmlFunctions::getInstance();

    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (departmentName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR abbr LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';

     $orderBy = " $sortField $sortOrderBy";

    ////////////
	$roleId = $REQUEST_DATA['roleId'];

    if($roleId != '') {
		$roleMappingRecordArray = $inventoryMappingManager->getRoleMappingList($roleId);
		$recordCount = count($roleMappingRecordArray);

		$filter = " AND r.roleId =".$roleId;
		$employeeRecordArray = $inventoryMappingManager->getEmployeeList($filter,$limit,$orderBy);
	}

    $cnt = count($employeeRecordArray);

    for($i=0;$i<$cnt;$i++) {
		$userId = $employeeRecordArray[$i]['userId'];
		if($recordCount > 0 && is_array($roleMappingRecordArray)) {
			for($s=0; $s < $recordCount; $s++) {
				if($roleMappingRecordArray[$s]['userId'] == $employeeRecordArray[$i]['userId']) {
					$mappingRoleId = $roleMappingRecordArray[$s]['mappingRoleId'];
					$mappingUserId = $roleMappingRecordArray[$s]['mappingUserId'];

					//$roleMappingRecordArray = $inventoryMappingManager->getUserData("WHERE u.roleId=".$mappingRoleId);
					$role = '<select size="1" class="selectfield" name="role_'.$employeeRecordArray[$i]['employeeId'].'" onchange="getUserRole(this.name,this.value);"><option value="">Select</option>'.$htmlFunctions->getRoleData($mappingRoleId,"WHERE roleId > 5").'</select>';

					$user = '<select size="1" class="selectfield" name="user_'.$employeeRecordArray[$i]['employeeId'].'" id="user_'.$employeeRecordArray[$i]['employeeId'].'"><option value="">Select</option>'.$htmlFunctions->getAllUserData($mappingUserId,"WHERE roleId =".$mappingRoleId).'</select>';
					break;
				}
				else {
					$role = '<select size="1" class="selectfield" name="role_'.$employeeRecordArray[$i]['employeeId'].'" onchange="getUserRole(this.name,this.value);"><option value="">Select</option>'.$htmlFunctions->getRoleData('',"WHERE roleId > 5").'</select>';
					$user = '<select size="1" class="selectfield" name="user_'.$employeeRecordArray[$i]['employeeId'].'" id="user_'.$employeeRecordArray[$i]['employeeId'].'"><option value="">Select</option></select>';
				}
			}
		}
		else {
			$role = '<select size="1" class="selectfield" name="role_'.$employeeRecordArray[$i]['employeeId'].'" onchange="getUserRole(this.name,this.value);"><option value="">Select</option>'.$htmlFunctions->getRoleData('',"WHERE roleId > 5").'</select>';
			$user = '<select size="1" class="selectfield" name="user_'.$employeeRecordArray[$i]['employeeId'].'" id="user_'.$employeeRecordArray[$i]['employeeId'].'"><option value="">Select</option></select>';

		}

        $valueArray = array_merge(array('role' => $role, 'user' => $user, 'srNo' => ($records+$i+1) ),$employeeRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}';

// for VSS
// $History: $
//
?>