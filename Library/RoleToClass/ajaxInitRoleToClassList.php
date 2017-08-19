<?php
//-------------------------------------------------------
// Purpose: To store the records of class in array from the database functionality
//
// Author : Jaineesh
// Created on : (26.09.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','RoleToClass');
	define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/RoleToClassManager.inc.php");
    $roletoclassManager = RoleToClassManager::getInstance();
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
	$orderBy = " $sortField $sortOrderBy";

	$classId	= $REQUEST_DATA['classId'];
	$allSubject	= $REQUEST_DATA['allSubject'];
	$filter1='';
	if(UtilityManager::notEmpty($REQUEST_DATA['subjectDetail'])) {


       $filter1 = ' AND (sub.subjectCode LIKE "%'.add_slashes($REQUEST_DATA['subjectDetail']).'%"  OR sub.subjectName LIKE "%'.add_slashes($REQUEST_DATA['subjectDetail']).'%" OR sub.subjectAbbreviation LIKE "%'.add_slashes($REQUEST_DATA['subjectDetail']).'%")';
    }

	$roleId = $REQUEST_DATA['roleId'];
	$employeeId = $REQUEST_DATA['teacher'];

	$totalGroupTypeArray = $roletoclassManager->getGroupType();
	$count = count($totalGroupTypeArray);

	for($j=0;$j<count($totalGroupTypeArray);$j++) {
		$groupType .="<option value=".$totalGroupTypeArray[$j][groupTypeId].">".$totalGroupTypeArray[$j][groupTypeName]."</option>";
	}

	$getUserIdArray	= $roletoclassManager->getEmployeeUserId("WHERE employeeId=".$employeeId);
	$userId = $getUserIdArray[0]['userId'];


	$getVisibleRole = $roletoclassManager->getVisibleClassRole("AND cvtr.userId=".$userId." AND cvtr.roleId=".$roleId);
	$classId = $getVisibleRole[0]['classId'];

	$getVisibleRoleCount = count($getVisibleRole);


	$totalClassArray = $roletoclassManager->getClassData();

	$cnt = count($totalClassArray);

    for($i=0;$i<$cnt;$i++) {
		$classId = $totalClassArray[$i]['classId'];
		$className = $totalClassArray[$i]['className'];
		$groupTypeId = $getVisibleRole[$i]['groupTypeId'];

		$groupTypeName = explode(',',$totalClassArray[$i]['groupTypeName']);
		$groupName = explode(',',$totalClassArray[$i]['groupName']);

		$groupId = explode(',',$totalClassArray[$i]['groupId']);

		$selectCheckBox = "";


		for($s=0; $s < $getVisibleRoleCount; $s++) {
			$visibleRoleClassId = $getVisibleRole[$s]['classId'];
			if ($classId == $visibleRoleClassId) {
				$selectCheckBox = "checked=checked";
				break;
			}
		}
		$checkall = '<input type="checkbox" name="chb[]" id="chb'.$classId.'" title="'.$className.'" value="'.strip_slashes($totalClassArray[$i]['classId']).'"'.$selectCheckBox.'" onClick=getSelected("chb'.$classId.'","'.$classId.'")>';


		$totalGroupTypeRoleArray = $roletoclassManager->getGroupTypeRole(" cvtr.userId=".$userId." AND cvtr.classId=".$classId." AND cvtr.roleId=".$roleId);

		//print_r($totalGroupTypeRoleArray);

		$groupTypeSelect = '<select multiple name=groupType'.$classId.'[] id=groupType'.$classId.' class="inputbox1" style="width:100px" onChange=getGroupValue("groupType'.$classId.'[]","groupType'.$classId.'",'.$classId.',"groupType","Add","group'.$classId.'") size="3">';

		$groupSelect = '<select multiple name=group['.$classId.'][] id=group'.$classId.' class="inputbox1" style="width:170px" size="3">';

		$j=0;

		for($m=0; $m < count($totalGroupTypeArray); $m++) {
		   $selectGroupType = "";
		   $temp1=0;
           for($k=0; $k <count($totalGroupTypeRoleArray); $k++) {
			 if($totalGroupTypeRoleArray[$k]['groupTypeName'] == $totalGroupTypeArray[$m]['groupTypeName']) {
  		       $temp1=1;
			   break;
			 }
		   }
		   if($temp1==1) {
			   //print_r($groupTypeName);
			   for($k=0; $k <count($groupTypeName); $k++) {
				   if($groupTypeName[$k]==$totalGroupTypeArray[$m]['groupTypeName']) {
					  $selectGroupType = "selected = 'selected'";
					  $groupDetailArr[$j]=$classId.'~'.$totalGroupTypeArray[$m]['groupTypeId'];
					  $j++;
					  $getGroupArray = $roletoclassManager->getVisibleGroup($userId,$roleId," gr.classId=".$classId." AND gr.groupTypeId=".$totalGroupTypeArray[$m]['groupTypeId']);
						//print_r($getGroupArray);

					  for($g=0;$g<count($getGroupArray);$g++) {
						  $temp=0;
						  for($n=0; $n < count($groupName); $n++) {
							if($groupName[$n]==$getGroupArray[$g]['groupName'] && $getGroupArray[$g]['egroupId']!='') {
							  $groupSelect .="<option value=".$groupId[$n]." selected = 'selected' >".$groupName[$n]."</option>";
							  $temp=1;
							  break;
							}
						  }
						  if($temp==0) {
							$groupSelect .="<option value=".$getGroupArray[$g]['groupId']." >".$getGroupArray[$g]['groupName']."</option>";
						  }
					  }
					  break;
					}
				}
		     }
		   $groupTypeSelect .="<option value=".$totalGroupTypeArray[$m]['groupTypeId'].' ' .$selectGroupType.">".$totalGroupTypeArray[$m]['groupTypeName']."</option>";
		 }
		$groupTypeSelect .="</select> <a class='link' href='javascript:selUnselGroupType(".$classId.",1);'>All</a> / <a href='javascript:selUnselGroupType(".$classId.",0);'>None</a>";
	    $groupSelect .="</select> <a class='link' href='javascript:selUnselGroup(".$classId.",1);'>All</a> / <a href='javascript:selUnselGroup(".$classId.",0);'>None</a>";

        // add subjectId in actionId to populate edit/delete icons in User Interface
        $valueArray = array_merge(array('checkAll' => $checkall,
										'groupType'=> $groupTypeSelect,
										'group' => $groupSelect,
										'srNo' => ($records+$i+1)),$totalClassArray[$i]);



       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }

    echo '{"classId":"'.$classId.'","sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}';

// for VSS
// $History: ajaxInitRoleToClassList.php $
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 2/06/10    Time: 3:20p
//Updated in $/LeapCC/Library/RoleToClass
//fixed issue: 1722
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 10-01-23   Time: 3:53p
//Updated in $/LeapCC/Library/RoleToClass
//added Javascript check to select grouptype and group on single click
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 10/15/09   Time: 2:35p
//Updated in $/LeapCC/Library/RoleToClass
//fixed bug nos. 0001790, 0001789, 0001768, 0001767, 0001769, 0001761,
//0001758, 0001759, 0001757, 0001791
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 10/07/09   Time: 4:58p
//Updated in $/LeapCC/Library/RoleToClass
//fixed bug nos.0001727, 0001725, 0001724, 0001723, 0001721, 0001720,
//0001719, 0001718, 0001729
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 10/03/09   Time: 6:08p
//Updated in $/LeapCC/Library/RoleToClass
//fixed bug nos.0001681, 0001680, 0001679, 0001678, 0001677, 0001676,
//0001675, 0001666, 0001665, 0001664, 0001631, 0001614, 0001682, 0001610
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 10/03/09   Time: 9:43a
//Updated in $/LeapCC/Library/RoleToClass
//fixed bug nos.0001679, 0001678, 0001677, 0001676, 0001675
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 10/01/09   Time: 6:51p
//Updated in $/LeapCC/Library/RoleToClass
//changed queries and flow in send message to student, student report
//list according to HOD role and make new role advisory, modified in
//queries according to this role
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 9/30/09    Time: 6:03p
//Created in $/LeapCC/Library/RoleToClass
//new ajax files for add, edit, list
//
?>