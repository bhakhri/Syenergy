<?php
//-------------------------------------------------------
// Purpose: To store the records of class in array from the database functionality
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	 define('MODULE','AssignOptionalCourseToClass');
	 define('ACCESS','view');
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subtocls.subjectId';
	 $orderBy = " $sortField $sortOrderBy";

	$classId	= $REQUEST_DATA['classId'];
	$parentSubjectId	= $REQUEST_DATA['mmSubjectId'];

	require_once(MODEL_PATH . "/StudentManager.inc.php");
	$studentManager = StudentManager::getInstance();


	$classMMSubjectArray = $studentManager->getClassMMSubjects($classId,$parentSubjectId);
	$cnt = count($classMMSubjectArray);

    for($i=0;$i<$cnt;$i++) {

		$subjectId = $classMMSubjectArray[$i]['subjectId'];
		$mapped = $classMMSubjectArray[$i]['mapped'];
		$subjectCode = $classMMSubjectArray[$i]['subjectCode'];
		$subjectName = $classMMSubjectArray[$i]['subjectName'];
		$categoryName = $classMMSubjectArray[$i]['categoryName'];
		$subjectTypeName = $classMMSubjectArray[$i]['subjectTypeName'];


		if($mapped != '0'){

			$checkall = '<span style="background-color:RED"><input type="checkbox" name="chb[]" value="'.strip_slashes($classMMSubjectArray[$i]['subjectId']).'" checked></span>';
		}
		else{

			$checkall = '<input type="checkbox" name="chb[]" value="'.strip_slashes($classMMSubjectArray[$i]['subjectId']).'">';
		}



        // add subjectId in actionId to populate edit/delete icons in User Interface
        $valueArray = array_merge(array('subjectId' => $subjectId,'subjectTypeName' => $subjectTypeName,'subjectCode' => $subjectCode,'subjectName' => $subjectName,'mapped' => $mapped,'checkAll' => $checkall ,'action' => $classMMSubjectArray[$i]['subjectId'] , 'srNo' => ($records+$i+1) ),$classMMSubjectArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }
    echo '{"classId":"'.$classId.'","sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}';

// for VSS
// $History: ajaxInitList.php $
//
//*****************  Version 10  *****************
//User: Ajinder      Date: 3/30/10    Time: 1:33p
//Updated in $/LeapCC/Library/SubjectToClass
//bugs fixed. FCNS No.1490
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 09-08-18   Time: 3:07p
//Updated in $/LeapCC/Library/SubjectToClass
//Fixed 1090,1089,1088,1058 bugs
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 7/20/09    Time: 12:56p
//Updated in $/LeapCC/Library/SubjectToClass
//Added "hasParentCategory" in subject to class module
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 7/14/09    Time: 5:45p
//Updated in $/LeapCC/Library/SubjectToClass
//added define variable for Role Permission
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 5/07/09    Time: 2:11p
//Updated in $/LeapCC/Library/SubjectToClass
//Updated subject list function to show subjectype also
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 1/12/09    Time: 10:23a
//Updated in $/LeapCC/Library/SubjectToClass
//added required field and centralized message
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 1/05/09    Time: 6:04p
//Updated in $/LeapCC/Library/SubjectToClass
//Updated javascript validations
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/11/08   Time: 3:00p
//Updated in $/LeapCC/Library/SubjectToClass
//Updated module as per CC functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/SubjectToClass
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 9/11/08    Time: 5:38p
//Updated in $/Leap/Source/Library/SubjectToClass
//updated formatting and added comments
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 8/18/08    Time: 7:34p
//Updated in $/Leap/Source/Library/SubjectToClass
//updated file
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 8/18/08    Time: 3:44p
//Updated in $/Leap/Source/Library/SubjectToClass
//updated checked property of check box
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 8/09/08    Time: 2:02p
//Updated in $/Leap/Source/Library/SubjectToClass
//updated the functionality to map subject with class.
//made ajax based and removed study period and batch from search
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/29/08    Time: 12:40p
//Updated in $/Leap/Source/Library/SubjectToClass
//optimize the query
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/07/08    Time: 12:56p
//Created in $/Leap/Source/Library/SubjectToClass
//intial checkin
?>