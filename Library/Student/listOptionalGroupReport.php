<?php
//--------------------------------------------------------------------------------------------------------------
// Purpose: To show data in array from the database, pagination 
//
// Author : Jaineesh
// Created on : (15.05.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','UpdateStudentOptionalGroups');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true); 
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

	require_once(BL_PATH.'/HtmlFunctions.inc.php');
	$htmlFunctions = HtmlFunctions::getInstance();


	/////////////////////////
    
	$rollNo = $REQUEST_DATA['rollNo'];
	
	// to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
    
    $orderBy = " $sortField $sortOrderBy";

    ////////////

	$getOptionalGroupArray = $studentManager->getOptionalGroupList($rollNo);
	$cnt = count($getOptionalGroupArray);

	if($cnt > 0 ) {
		$groupDiv = '<table border = "1" cellspacing="2" width="100%" style="border:1px solid #cccccc;border-collapse:collapse;">';
		$groupDiv .= '<tr class="rowheading">
						<td align="left"><b>Sr.No.</b></td>
						<td align="left"><b>Subject</b></td>
						<td align="left"><b>Allocated Group</b></td>
						<td align="center"><b>Other Group</b></td>
					  </tr>';
		for($i=0;$i<$cnt;$i++) {
			$subjectId = $getOptionalGroupArray[$i]['subjectId'];
			$groupId = $getOptionalGroupArray[$i]['groupId'];
			$srNoCounter = $i+1;
			$bg = $bg == "row0" ? "row1" : "row0";
			$groupDiv .= '<tr class="$bg"><td align="left" width="20%">'.$srNoCounter.'</td>';
			$groupDiv .= '<td width="20%" name="subjectCode" id="subjectCode" align="left">'.$getOptionalGroupArray[$i]['subjectCode'].'</td>';
			$groupDiv .= '<td width="20%" name="group" id="group" align="left">'.$getOptionalGroupArray[$i]['groupName'].'</td>';

			$groupDiv .= '<td width="20%"><select size="1" class="selectfield" name="optionalGroup_'.$getOptionalGroupArray[$i]['subjectId'].'" id="optionalGroup_'.$i.'"><option value="">Select</option>'.$htmlFunctions->getOptionalSubjects($subjectId,$groupId).'</select></td>';

			$groupDiv .= '</tr>';
			$groupDiv .= '<input type="hidden" id="dataCount" name="dataCount" value="'.$cnt.'"';
		}
			$groupDiv .= '</table>';
	}
		echo ($groupDiv);
		die;
// for VSS
// $History: listOptionalGroupReport.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/22/10    Time: 12:56p
//Created in $/LeapCC/Library/Student
//new ajax file for student optional change
//
//
?>