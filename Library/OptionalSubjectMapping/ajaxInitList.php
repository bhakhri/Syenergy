<?php
//-------------------------------------------------------
// Purpose: To display the records of students Selected on the basis of class,group and subject 
// functionality
//
// Author : Arvind Singh Rawat
// Created on : (28.08.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','AssignOptionalSubjectToStudents');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    require_once(MODEL_PATH . "/OptionalSubjectMapping.inc.php");
    $optionalSubjectMappingManager = OptionalSubjectMappingManager::getInstance();
    // filter used to get a student list based on selected class,student,group
    $filter=" AND stc.subjectId='".$REQUEST_DATA['subject']."'
              AND s.classId='".$REQUEST_DATA['studentClass']."' 
              AND g.groupId='".$REQUEST_DATA['studentGroup']."' 
              AND (
                    s.thGroupId = '".$REQUEST_DATA['studentGroup']."'
                    OR s.prGroupId = '".$REQUEST_DATA['studentGroup']."'
                  ) ";                                                                                                              
    $optionalSubjectArray = $optionalSubjectMappingManager->getStudentList($filter);
    // cnt counts total Records
    $cnt = count($optionalSubjectArray);
     $checkedBoxes="";
    for($i=0;$i<$cnt;$i++) {
        $optionalSubjectCheck= $optionalSubjectMappingManager->checkStudentRecord($optionalSubjectArray[$i]['studentId'],$REQUEST_DATA);
        if(isset($optionalSubjectCheck[0]['studentId'])){
            $checkall = '<input type="checkbox" name="chb[]" value="'.strip_slashes($optionalSubjectArray[$i]['studentId']).'" checked>';
            $checkedBoxes.="-".strip_slashes($optionalSubjectCheck[0]['studentId'])."";
        }
        else{
             $checkall = '<input type="checkbox" name="chb[]" value="'.strip_slashes($optionalSubjectArray[$i]['studentId']).'" >';
        }
       // add attendanceCodeId in actionId to populate edit/delete icons in User Interface   
       $valueArray = array_merge(array('checkAll' =>$checkall , 'srNo' => ($records+$i+1) ),$optionalSubjectArray[$i]);
       
       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"checkedBoxes":"'.$checkedBoxes.'","sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cnt.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
                     
// $History: ajaxInitList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/OptionalSubjectMapping
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/06/08   Time: 1:27p
//Updated in $/Leap/Source/Library/OptionalSubjectMapping
//define module,access
//
//*****************  Version 3  *****************
//User: Arvind       Date: 8/29/08    Time: 3:32p
//Updated in $/Leap/Source/Library/OptionalSubjectMapping
//code formatted
//
//*****************  Version 1  *****************
//User: Arvind       Date: 8/28/08    Time: 8:06p
//Created in $/Leap/Source/Library/OptionalSubjectMapping
//added a new file for student to optional subject mapping
?>