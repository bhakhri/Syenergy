<?php
//-------------------------------------------------------
//  This File is used for fetching attendance subjects as per time table label
// Author :Ajinder Singh
// Created on : 29-01-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','UploadStudentExternalMarks');
	define('ACCESS','view');
	global $sessionHandler; 
    $roleId = $sessionHandler->getSessionVariable('RoleId');
    if($roleId=='2') {      // Teacher Login
      $employeeId = $sessionHandler->getSessionVariable('EmployeeId');
      UtilityManager::ifTeacherNotLoggedIn(true); 
    }
    else {
      UtilityManager::ifNotLoggedIn(true); 
    }
    UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

	require_once(BL_PATH.'/HtmlFunctions.inc.php');
	$htmlFunctions = HtmlFunctions::getInstance();
    
    
    $degree = $REQUEST_DATA['degree'];     
    $timeTable = $REQUEST_DATA['timeTable'];
    
    if($degree=='') {
      $degree='0';  
    }
    
    if($timeTable=='') {
      $timeTable='0';  
    }
    
    $tableName='';
    $condition =  "";
    if($roleId=='2') {  
      $tableName = ",  ".TIME_TABLE_TABLE."  tt";  
      $condition = " AND tt.employeeId = '$employeeId' AND tt.timeTableLabelId = '$timeTable' 
                     AND tt.subjectId = a.subjectId
                     AND tt.classId = a.classId ";  
    }

	$subjectArray = $studentManager->getClassSubjectsTestTypes($degree,$condition,$tableName);
	$cnt = count($subjectArray);

	$marksDiv = '<table border = "1" cellspacing="2" width="100%" style="border:1px solid #cccccc;border-collapse:collapse;">';
	$marksDiv .= '<tr class="rowheading">
					<td align="left" width="5%"><b>Sr.No.</b></td><td align="left" width="60%"><b>Subject</b></td><td align="center"><b>Test Type</b></td><td align="center"><b>File</b></td>
				  </tr>';
	for($i=0;$i<$cnt;$i++) {
		$srNoCounter = $i+1;
		$subjectId = $subjectArray[$i]['subjectId'];
		$bg = $bg == "row0" ? "row1" : "row0";
		$marksDiv .= '<tr class="$bg"><td align="left">'.$srNoCounter.'</td>';
		$marksDiv .= '<td width="50%" name="subjectCode" id="subjectCode_'.$i.'" align="left">'.$subjectArray[$i]['subjectCode'].' </td>';
		$marksDiv .= '<td width="20%"><select size="1" class="selectfield" name="testType_'.$subjectArray[$i]['subjectId'].'" id="testType_'.$i.'"><option value="">Select</option>'.$htmlFunctions->getTestTypeData('','AND conductingAuthority = 2 AND subjectId = '.$subjectArray[$i]['subjectId'].' AND classId='.$degree).'</select></td>';
		$marksDiv .= '<td width="20%"><input type="file" size="30" onkeydown="return false;" class="inputBox1" name="file_'.$subjectArray[$i]['subjectId'].'" id="file_'.$i.'"/></td>';
		$marksDiv .= '</tr>';
		$marksDiv .= '<input type="hidden" id="dataCount" name="dataCount" value="'.$cnt.'"';
	}
	$marksDiv .= "<tr><td colspan='4' align='right'><input type='hidden' name='submitForm'/><input type='image' name='studentListSubmit' value='studentListSubmit' src='".IMG_HTTP_PATH."/upload_external_marks.gif' onclick='onSubmitAcion();return false;'/><input type='image' name='studentListSubmit' value='studentListSubmit' src='".IMG_HTTP_PATH."/reset.gif' onclick='reset1();return false;'/></td></tr>";

	$marksDiv .= '</table><div id="alertUser" style="display:none;color:red;" align=left></div>';

	echo ($marksDiv);


?>
