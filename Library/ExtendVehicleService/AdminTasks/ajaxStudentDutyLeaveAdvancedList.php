<?php
//-----------------------------------------------------------------------------------------------------------------------
// Purpose: To store the records of student daily attendance in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (18.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------------------
    set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','DutyLeavesAdvanced');
    define('ACCESS','edit');
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/AdminTasksManager.inc.php");
    $teacherManager = AdminTasksManager::getInstance();

    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE_TEACHER;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE_TEACHER;  

    $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
    $classId=trim($REQUEST_DATA['classId']);
    $subjectId=trim($REQUEST_DATA['subjectId']);
    $groupId=trim($REQUEST_DATA['groupId']);
    $rollNos=trim($REQUEST_DATA['studentRollNo']);
    $employeeId=$REQUEST_DATA['employeeId'];
    
    if($timeTableLabelId==''){
        echo 'Required Parameters Missing';
        die;
    }
    $filter = ' AND ttc.timeTableLabelId='.$timeTableLabelId;
    if($classId!='' and $classId!=0){
        $filter .=' AND c.classId='.$classId;
    }
    if($subjectId!='' and $subjectId!=0){
        $filter .=' AND sc.subjectId='.$subjectId;
    }
    if($groupId!='' and $groupId!=0){
        $filter .=' AND g.groupId='.$groupId;
    }
    if($employeeId!='' and $employeeId!=0){
        $filter .=' AND t.employeeId='.$employeeId;
    }
    if($rollNos!=''){
        $rollNoArray=explode(',',$rollNos);
        $cnt=count($rollNoArray);
        $rollNoString='';
        for($i=0;$i<$cnt;$i++){
            if($rollNoString!=''){
                $rollNoString .=',';
            }
            $rollNoString .="'".add_slashes(trim($rollNoArray[$i]))."'";
        }
        if($rollNoString!=''){
         $filter .=" AND s.rollNo IN (".$rollNoString.")";
        }
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
    
    if($sortField=='rollNo'){
        $orderBy = " LENGTH(rollNo)+0,rollNo $sortOrderBy";  //to make "Lexographical" sorting on "rollNo" field
    }
    else if($sortField=='universityRollNo'){
        $orderBy = " LENGTH(universityRollNo)+0,universityRollNo $sortOrderBy";  //to make "Lexographical" sorting on "universityRollNo" field
    }
    else if($sortField=='regNo'){
        $orderBy = " LENGTH(regNo)+0,regNo $sortOrderBy";  //to make "Lexographical" sorting on "regNo" field
    }
    else{
     $orderBy = " $sortField $sortOrderBy";                                 
    }                                                                  
                                                        
    
    //$totalArray            = $teacherManager->getTotalStudentDutyLeave($filter);
    $totalArray                 = $teacherManager->getStudentDutyLeaveList($filter);
    $studentRecordArray         = $teacherManager->getStudentDutyLeaveList($filter,$limit,$orderBy);  
    $cnt = count($studentRecordArray);  //count of student records
    for($i=0;$i<$cnt;$i++) {
        $fStudentId=$studentRecordArray[$i]['studentId'];
        $fClassId=$studentRecordArray[$i]['classId'];
        $fGroupId=$studentRecordArray[$i]['groupId'];
        $fSubjectId=$studentRecordArray[$i]['subjectId'];
        $fTimeTableIdId=$studentRecordArray[$i]['timeTableLabelId'];
        $leavesTaken=trim($studentRecordArray[$i]['leavesTaken']);
        $comments=trim($studentRecordArray[$i]['comments']);
        $delivered=0;
        $attended=0;
        if($leavesTaken==''){
            $leavesTaken=0;
        }
        $leavesTaken=round($leavesTaken);
        if($comments==''){
            $comments='';
        }

        $studentRecordArray[$i]['attended']=''.round($studentRecordArray[$i]['attended']).'';
        $studentRecordArray[$i]['delivered']=''.round($studentRecordArray[$i]['delivered']).'';
        
        $hiddenString  ='<input type="hidden" name="hiddenDel" id="hiddenDel'.$i.'" value="'.$studentRecordArray[$i]['delivered'].'" />';
        $hiddenString .='<input type="hidden" name="hiddenAtt" id="hiddenAtt'.$i.'" value="'.$studentRecordArray[$i]['attended'].'" />';
        
        $alt=$fStudentId.'~!~'.$fClassId.'~!~'.$fGroupId.'~!~'.$fSubjectId.'~!~'.$fTimeTableIdId;
        $studentRecordArray[$i]['leavesTaken']='<input type="text" class="inputbox" style="width:50px;" name="leavesTaken"   id="leavesTaken'.$i.'"   alt="'.$alt.'" value="'.$leavesTaken.'" onkeyup="checkDutyLeaveValidation('.$i.')" />';
        $studentRecordArray[$i]['comments']='<input type="text" class="inputbox"    style="width:350px;" name="leavesComment" id="leavesComment'.$i.'" alt="'.$alt.'" value="'.$comments.'" maxlength="255" />'.$hiddenString;
        
        $valueArray = array_merge(array('srNo' => ($records+$i+1)),$studentRecordArray[$i]);         

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
   echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxStudentDutyLeaveAdvancedList.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 21/11/09   Time: 12:55
//Updated in $/LeapCC/Library/AdminTasks
//Done bug fixing.
//Bug ids :
//0002087 to 0002093
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 20/11/09   Time: 13:04
//Updated in $/LeapCC/Library/AdminTasks
//Done bug fixing.
//Bug ids---
//0002084
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 19/11/09   Time: 12:53
//Updated in $/LeapCC/Library/AdminTasks
//Completed/modified "Duty Leaves" module in admin section
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 18/11/09   Time: 13:14
//Created in $/LeapCC/Library/AdminTasks
//Modified Duty Leaves module in admin section
?>