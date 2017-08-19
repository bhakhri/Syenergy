<?php
//-------------------------------------------------------
//  This File contains code for making promtoe class table.
//
//
// Author :Ajinder Singh
// Created on : 29-05-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PromoteStudents');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();

require_once(BL_PATH . '/PromoteStudentsAdvanced/getClassesReadyForPromotion.php');

$resultArray = array();
$i = 0;
$j = 0;
$json_val = '';
foreach($validClassesArray as $record) {
	$i++;
	$classId = $record['classId'];
	$resultArray['srNo'] = $i;
	$resultArray['classId'] = $record['classId'];
	$resultArray['className'] = $record['className'];
	$resultArray['select'] = "<input type='checkbox' name='promoteClass[]' value='$classId' onClick='selThisCopyGroups(this.value, this.checked);'/>";
	$resultArray['copyGroups'] = "<input type='checkbox' name='copyGroups[]' value='$classId' disabled onClick='selThisCopyPrivileges(this.value, this.checked);' />";
	$resultArray['copyPrivileges'] = "<input type='checkbox' name='copyPrivileges[]' value='$classId' disabled />";

   if(trim($json_val)=='') {
		$json_val = json_encode($resultArray);
   }
   else {
		$json_val .= ','.json_encode($resultArray);           
   }
}

echo '{"sortOrderBy":"Asc","sortField":"className","totalRecords":"'.count($resultArray).'","page":"1","info" : ['.$json_val.']}'; 

//$History: getClassesForPromotion.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 1/29/10    Time: 3:41p
//Created in $/LeapCC/Library/PromoteStudentsAdvanced
//file added for new interface of session end activities


?>