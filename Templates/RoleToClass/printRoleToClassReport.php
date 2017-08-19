 <?php 
//This file is used as printing version for role to class.
//
// Author :Jaineesh
// Created on : 05-10-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/RoleToClassManager.inc.php");
    $roletoclassManager = RoleToClassManager::getInstance();

	$search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
        
	$employeeId = $REQUEST_DATA['employeeId'];
	$roleId = $REQUEST_DATA['roleId'];


	$getUserIdArray	= $roletoclassManager->getEmployeeUserId("WHERE employeeId=".$employeeId);
	$userId = $getUserIdArray[0]['userId'];
	$employeeName = $getUserIdArray[0]['employeeName'];
	$getRoleNameArray	= $roletoclassManager->getUserRole("WHERE ur.roleId = r.roleId AND ur.userId=".$userId." AND ur.roleId=".$roleId);
	$roleName = $getRoleNameArray[0]['roleName'];

	$employeeName = $getUserIdArray[0]['employeeName'];
	
	
	$getVisibleRole = $roletoclassManager->getPrintVisibleClassRole("AND cvtr.userId=".$userId);
	//echo '<pre>';
	//print_r($getVisibleRole);
	
	$recordCount = count($getVisibleRole);
		
	if($recordCount >0 && is_array($getVisibleRole) ) { 
		$catName=$getVisibleRole[0]['className'];
		$groupName = '';
		$groupTypeName = '';
		$j=1;
		$temp=0;
		for($i=0; $i<$recordCount; $i++ ) {
			if($catName==$getVisibleRole[$i]['className']) {
			  $catName = strip_slashes($getVisibleRole[$i]['className']);
			  $groupName .= strip_slashes($getVisibleRole[$i]['groupName'])."<br>";
			  $groupTypeName .= strip_slashes($getVisibleRole[$i]['groupTypeName'])."<br>";
  			  if($i==($recordCount-1)) {
	    		  $valueArray[] = array_merge(array('srNo' => ($j),
													'className' => $catName,
													'groupName' => $groupName,
													'groupTypeName' => $groupTypeName));	
				  $j++;
 			  }

		    }
			else {
    		  $valueArray[] = array_merge(array('srNo' => ($j),
												'className' => $catName,
												'groupName' => $groupName,
												'groupTypeName' => $groupTypeName));	
  			  $temp=1;
  			  $catName = strip_slashes($getVisibleRole[$i]['className']);
			  $groupName = strip_slashes($getVisibleRole[$i]['groupName'])."<br>";
			  $groupTypeName = strip_slashes($getVisibleRole[$i]['groupTypeName'])."<br>";

			  if($i==($recordCount-1)) {
	  			  $j++;
	    		  $valueArray[] = array_merge(array('srNo' => ($j),
													'className' => $catName,
													'groupName' => $groupName,
													'groupTypeName' => $groupTypeName));	
 			  }
  			  $j++;
			}
		}
	}
	
                           
    $reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Academic Head Privileges Report ');
	$reportManager->setReportInformation("Role Name : $roleName, User : $employeeName");

    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#','width="4%" align="left"', "valign='top' align='left'");
    $reportTableHead['className']			=    array('Class Name ',' width=15% align="left" ',' valign="top" align="left" ');
    $reportTableHead['groupTypeName']		=    array('Group Type Name',' width="15%" align="left" ',' valign="top" align="left"');
	$reportTableHead['groupName']			=    array('Group Name',' width="15%" align="left" ',' valign="top" align="left"');

    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
//
?>