<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of class wise attendance
//
// Author : Dipanjan Bbhattacharjee
// Created on : (07.08.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifTeacherNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
    $teacherManager = ScTeacherManager::getInstance();

    /////////////////////////
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
    $orderBy = " $sortField $sortOrderBy";  
    //creates the condition
    $conditions=($REQUEST_DATA['studentRollNo']!="" ? " AND s.rollNo='".$REQUEST_DATA['studentRollNo']."'" : " AND su.subjectId=".$REQUEST_DATA['subject']." AND ssc.subjectId=".$REQUEST_DATA['subject']." AND ssc.sectionId=".$REQUEST_DATA['section']);        
    
    $conditions .=" AND att.fromDate >='".$REQUEST_DATA['fromDate']."' AND att.toDate <='".$REQUEST_DATA['toDate']."'";

    ////////////
    
    
    $classAttendanceRecordArray = $teacherManager->getClassWiseAttendanceList($conditions,$orderBy);
    $cnt = count($classAttendanceRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       
      /*
       if($classAttendanceRecordArray[$i]['delivered'])
            $percentage = round(($classAttendanceRecordArray[$i]['attended']/$classAttendanceRecordArray[$i]['delivered'])*100);
        else
            $percentage = "0"; 
      */  
       //$valueArray = array_merge(array('srNo' => ($records+$i+1),'percentage' =>$percentage), $classAttendanceRecordArray[$i]);
       $valueArray = array_merge(array('srNo' => ($records+$i+1)), $classAttendanceRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cnt.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
// for VSS
// $History: scAjaxClassAttendanceList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScTeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
?>
