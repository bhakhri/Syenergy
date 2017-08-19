<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of class wise attendance
//
// Author : Dipanjan Bbhattacharjee
// Created on : (07.08.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','GraceMarksEntry');
    define('ACCESS','view');
    UtilityManager::ifTeacherNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();

    /////////////////////////
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
    $orderBy = " $sortField $sortOrderBy";
    
    //creates the condition
    $conditions ='';
    
    $conditions = " AND ttm.classId=".$REQUEST_DATA['class']." AND ttm.subjectId=".$REQUEST_DATA['subject']." AND sg.groupId=".$REQUEST_DATA['group'];
    
    
    if(trim($REQUEST_DATA['studentRollNo'])!=''){
        $conditions .=' AND s.rollNo LIKE "'.add_slashes(trim($REQUEST_DATA['studentRollNo'])).'%"';
    }
    ////////////

    $graceMarksRecordArray = $teacherManager->getGraceMarksList($conditions,$REQUEST_DATA['subject'],$orderBy);
    $cnt = count($graceMarksRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       $gmarks=$graceMarksRecordArray[$i]['graceMarks'];
       $graceMarksRecordArray[$i]['graceMarks']='<input type="text" name="graceMarks" id="graceMarks'.$i.'" value="'.$graceMarksRecordArray[$i]['graceMarks'].'" class="inputbox" style="width:40px" onkeyup="alertData('.$i.')" /><input type="hidden" name="student" value="1" /><input type="hidden" name="students" value="'.$graceMarksRecordArray[$i]['studentId'].'" /><input type="hidden" name="markScored" id="markScored'.$i.'"  value="'.$graceMarksRecordArray[$i]['marksScored'].'" />
       <input type="hidden" name="maxMarkScored" id="maxMarkScored'.$i.'"  value="'.$graceMarksRecordArray[$i]['maxMarks'].'" />'; 
       $valueArray = array_merge(array('srNo' => ($records+$i+1),'newMarks'=>'<span name="newMarks" id="newMarks'.$i.'">'.abs($gmarks + $graceMarksRecordArray[$i]['marksScored']).'</span>'), $graceMarksRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cnt.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
// for VSS
// $History: ajaxGraceMarksList.php $
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 3/12/09    Time: 16:29
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Modified roll no search query condition
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 3/12/09    Time: 12:46
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Done bug fixing.
//Bug ids---
//0002167,0002168,0002170 to 0002175
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/05/09    Time: 11:06
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Corrected query and logic in grace marks modules
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 21/04/09   Time: 16:02
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//Created "Grace Marks Master"
?>
