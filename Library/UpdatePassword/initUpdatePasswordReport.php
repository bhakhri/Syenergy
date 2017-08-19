<?php
//-------------------------------------------------------
// Purpose: To list student detail of roll no & student name
//
// Author : Jaineesh
// Created on : (05.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','UpdatePasswordReport');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    
    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

    //Roll Number
    $rollNo = add_slashes($REQUEST_DATA['rollNo']);
    if ($rollNo!='') {           
        $conditionsArray[] = " a.rollNo LIKE '$rollNo%' ";
        $qryString.= "&rollNo=$rollNo";
    }

    //Student Name
    $studentName = add_slashes($REQUEST_DATA['studentName']);
    if ($studentName!='') {
        //$conditionsArray[] = " CONCAT(a.firstName, ' ', a.lastName) like '%$studentName%' ";
        $conditionsArray[] = " CONCAT(IFNULL(a.firstName,''),' ',IFNULL(a.lastName,'')) LIKE '".$studentName."%'";
        $qryString.= "&studentName=$studentName";
    }

    
    // Group wise
    $groupId = $REQUEST_DATA['groupId'];
    if ($groupId!='' && $groupId!='all' ) {
        $conditionsArray[] = "(a.studentId IN (SELECT DISTINCT(studentId) FROM student_groups WHERE classId = b.classId AND groupId IN ($groupId)) 
                               OR
                               a.studentId IN (SELECT DISTINCT(studentId) FROM student_optional_subject WHERE classId = b.classId AND groupId IN ($groupId))) ";
    }

    
    //class 
    $classId = $REQUEST_DATA['degree'];
    if ($classId!='') {
        $conditionsArray[] = " a.classId = $classId ";
    }

    
    $conditions = '';
    if (count($conditionsArray) > 0) {
        $conditions = ' AND '.implode(' AND ',$conditionsArray);
    }

    $conditions .= " AND (IFNULL(a.rollNo,'') != '') ";
        
    //echo  $qryString;
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'regNo';
    
    $orderBy = " $sortField $sortOrderBy";
    
  /*  echo "<pre>";
      echo  $conditions;
      die('here11');
  */    
    
    $updateRollNoArray = $studentManager->getStudentList($conditions, '', $orderBy);
    $cnt = count($updateRollNoArray);
    for($i=0;$i<$cnt;$i++) {
        /*$rollNo = $updateRollNoArray[$i]['rollNo'];
        $firstName = $updateRollNoArray[$i]['firstName'];
        $dateOfBirth = $updateRollNoArray[$i]['dateOfBirth'];
        $dateOfBirth = explode("-",$dateOfBirth);
        $birthYear = $dateOfBirth[0];*/
        $checkall = '<input type="checkbox" name="chb[]" value="'.strip_slashes($updateRollNoArray[$i]['studentId']).'">';
        if ($updateRollNoArray[$i]['userName']==''){
            $updateRollNoArray[$i]['userName']=NOT_APPLICABLE_STRING ;
        }
        // add subjectId in actionId to populate edit/delete icons in User Interface
        $valueArray = array_merge(array('checkAll' => $checkall, 'srNo' => ($records+$i+1)),$updateRollNoArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }
    echo

    '{"classId":"'.$classId.'","sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: initUpdatePasswordReport.php $
//
//*****************  Version 7  *****************
//User: Gurkeerat    Date: 11/13/09   Time: 5:57p
//Updated in $/LeapCC/Library/UpdatePassword
//Updated code to add new field in 'Generate Student Login' 
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 11/12/09   Time: 4:59p
//Updated in $/LeapCC/Library/UpdatePassword
//rollno checks updated
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 11/11/09   Time: 6:29p
//Updated in $/LeapCC/Library/UpdatePassword
//resolved issues: 1967, 1968, 1969, 1971, 1972, 1980, 1981
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 11/06/09   Time: 1:46p
//Updated in $/LeapCC/Library/UpdatePassword
//Updated code to modify 'Generate student login'
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/09/09    Time: 2:07p
//Updated in $/LeapCC/Library/UpdatePassword
//replicate of bug Nos.3,4 in cc
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/01/09    Time: 3:26p
//Updated in $/LeapCC/Library/UpdatePassword
//changes as per leap cc
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/01/09    Time: 1:14p
//Created in $/LeapCC/Library/UpdatePassword
//copy files from sc
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/30/09    Time: 3:37p
//Created in $/Leap/Source/Library/ScUpdatePassword
//new ajax file to show student list & save student user name, password
//
?>