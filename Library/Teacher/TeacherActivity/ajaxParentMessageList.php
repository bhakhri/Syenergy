<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To store the records of students in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (21.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------------------------
//Purpose:To show ""Not Present" when the database field is empty
//Author:Dipanjan Bhttacharjee
//Date: 19.08.2008
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------
function trim_output($val){
 return (trim($val)!="" ? $val : "Not Present");   
}

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','SendMessageToParents');
    define('ACCESS','view');
    UtilityManager::ifTeacherNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();

    /////////////////////////

    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE_TEACHER;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE_TEACHER;
    //////
    
    /////search functionility not needed   
    $filter="";

    if(trim($REQUEST_DATA['subject'])!=""){
        $filter =$filter." AND sc.subjectId=".trim($REQUEST_DATA['subject']); 
    }
    if(trim($REQUEST_DATA['group'])!=""){
        $filter =$filter." AND g.groupId=".trim($REQUEST_DATA['group']); 
    }
    if(trim($REQUEST_DATA['class'])!=""){
        $filter =$filter." AND c.classId=".trim($REQUEST_DATA['class']); 
    }
    if(trim($REQUEST_DATA['studentRollNo'])!=""){
        $filter =$filter." AND s.rollNo LIKE '".trim(add_slashes($REQUEST_DATA['studentRollNo']))."%'"; 
    }
    
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $teacherManager->getSearchTotalStudent($filter);
    $studentRecordArray = $teacherManager->getSearchStudentList($filter,$limit,$orderBy);
    $cnt = count($studentRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface
       $fdisable=(trim($studentRecordArray[$i]['fatherName'])!="" ? " "   : "disabled"); 
       $mdisable=(trim($studentRecordArray[$i]['motherName'])!="" ? " "   : "disabled");
       $gdisable=(trim($studentRecordArray[$i]['guardianName'])!="" ? " " : "disabled");
       $valueArray = array_merge(
       array('srNo' => ($records+$i+1),"fatherName" => "<input type=\"checkbox\" name=\"fathers\" id=\"fathers\" $fdisable value=\"".$studentRecordArray[$i]['studentId'] ."\">".strip_slashes(trim_output($studentRecordArray[$i]['fatherName'])),
       "motherName" => "<input type=\"checkbox\" name=\"mothers\" id=\"mothers\" $mdisable  value=\"".$studentRecordArray[$i]['studentId'] ."\">".strip_slashes(trim_output($studentRecordArray[$i]['motherName'])),
       "guardianName" => "<input type=\"checkbox\" name=\"guardians\" id=\"guardians\" $gdisable value=\"".$studentRecordArray[$i]['studentId'] ."\">".strip_slashes(trim_output($studentRecordArray[$i]['guardianName'])),
       'studentName' =>strip_slashes($studentRecordArray[$i]['studentName']) ,
       'rollNo' => strip_slashes($studentRecordArray[$i]['rollNo']),
       'universityRollNo' =>strip_slashes($studentRecordArray[$i]['universityRollNo']))
       );

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxParentMessageList.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 19/08/09   Time: 15:28
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Done bug fixing
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/08/08   Time: 4:41p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added "SC" enhancements
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/01/08    Time: 5:36p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/19/08    Time: 4:43p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/12/08    Time: 5:29p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/21/08    Time: 6:53p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
//Initial Checkin
?>
