<?php
//--------------------------------------------------------------------------------------------------------------
// Purpose: To store the records of student attendance in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (16.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifTeacherNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
    $teacherManager = ScTeacherManager::getInstance();

    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE_TEACHER;
    //$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE_TEACHER;  
    
    /*
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (sub.subjectName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR sub.subjectCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    } 
    */
    $filter="";
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'firstName';
    
    $orderBy = " $sortField $sortOrderBy";         
     
    $totalArray = $teacherManager->getTotalStudent($filter);
    $studentRecordArray = $teacherManager->getStudentList($filter,$limit,$orderBy);  
    $attendanceRecordArray=$teacherManager->getBulkAttendanceList($filter); 
    
    $letDel=$REQUEST_DATA['lectureDelivered']; //lecture delivered
    
    
    $cnt = count($studentRecordArray);  //count of student records
    $attendanceRecordCount = count($attendanceRecordArray);  //count of attendance records

    for($i=0;$i<$cnt;$i++) {
        //matching of student and attendance records
        if($attendanceRecordCount >0 && is_array($attendanceRecordArray) ) { 
        $fl=0;
         for($j=0; $j<$attendanceRecordCount; $j++ ) {
          if($attendanceRecordArray[$j]['studentId']==$studentRecordArray[$i]['studentId']){
              $fl=1;
              break;
          }
         }
       }
       if($fl==1){ //if studentId exist in attendance_bulk table
        if(strip_slashes($attendanceRecordArray[$j]['lectureDelivered']) >0){
         $per=number_format((strip_slashes($attendanceRecordArray[$j]['lectureAttended'])/strip_slashes($attendanceRecordArray[$j]['lectureDelivered']))*100,2,'.','');
        }
        else{
            $per="0.00";
        } 
        if($attendanceRecordArray[$j]['isMemberOfClass']=="1"){
         $valueArray = array_merge(array('srNo' => ($records+$i+1),
         //'delivered'=>'<input type="textbox"  style="width:50px;text-align:center;" name="ldel" id="ldel'.$i.'" value="'.strip_slashes($attendanceRecordArray[$j]['lectureDelivered']).'" disabled   onkeyup="changePercentage('.$i.',1);" tabIndex='.($i+2*$cnt+10).' >',
         'delivered'=>'<input type="textbox"  style="width:50px;text-align:center;" name="ldel" id="ldel'.$i.'" value="'.strip_slashes($attendanceRecordArray[$j]['lectureDelivered']).'"    onkeyup="changePercentage('.$i.',1);" tabIndex='.($i+2*$cnt+10).' >',
         'attended'=>'<input type="textbox"  style="width:50px;text-align:center;" name="latt" id="latt'.$i.'" value="'.strip_slashes($attendanceRecordArray[$j]['lectureAttended']).'" onkeyup="changePercentage('.$i.',2);" tabIndex='.($i+10).' onfocus="attAction();" >',
         'percentage'=>'<input type="textbox"  style="width:60px;text-align:center;" name="lcep" id="lcep'.$i.'" value="'.$per.'%" readOnly="true" tabIndex='.($i+3*$cnt+10).' >',
         'memberOfClass'=>'<input type="checkbox" name="mem" id="mem'.$i.'" checked  onclick="mocAction('.$i.');" tabIndex='.($i+$cnt+10).'>',
         'attendance'=>'<input type="hidden" name="attendance" id="attendance" value="'.strip_slashes($attendanceRecordArray[$j]['attendanceId']).'~'.strip_slashes($studentRecordArray[$i]['studentId']).'~'.strip_slashes($studentRecordArray[$i]['classId']).'" >'),
         $studentRecordArray[$i]);
        }
        else{
         $valueArray = array_merge(array('srNo' => ($records+$i+1),
         'delivered'=>'<input type="textbox"  style="width:50px;text-align:center;" name="ldel" id="ldel'.$i.'" value="'.strip_slashes($attendanceRecordArray[$j]['lectureDelivered']).'"  disabled   onkeyup="changePercentage('.$i.',1);" tabIndex='.($i+2*$cnt+10).' >',
         //'delivered'=>'<input type="textbox"  style="width:50px;text-align:center;" name="ldel" id="ldel'.$i.'" value="'.strip_slashes($attendanceRecordArray[$j]['lectureDelivered']).'"     onkeyup="changePercentage('.$i.',1);" tabIndex='.($i+2*$cnt+10).' >',
         'attended'=>'<input type="textbox"  style="width:50px;text-align:center;" name="latt" id="latt'.$i.'" value="'.strip_slashes($attendanceRecordArray[$j]['lectureAttended']).'" disabled onkeyup="changePercentage('.$i.',2);"   tabIndex='.($i+10).' onfocus="attAction();">',
         'percentage'=>'<input type="textbox"  style="width:60px;text-align:center;" name="lcep" id="lcep'.$i.'" value="'.$per.'%" readOnly="true" tabIndex='.($i+3*$cnt+10).' >',
         'memberOfClass'=>'<input type="checkbox" name="mem" id="mem'.$i.'" onclick="mocAction('.$i.');" tabIndex='.($i+$cnt+10).'>',
         'attendance'=>'<input type="hidden" name="attendance" id="attendance" value="'.strip_slashes($attendanceRecordArray[$j]['attendanceId']).'~'.strip_slashes($studentRecordArray[$i]['studentId']).'~'.strip_slashes($studentRecordArray[$i]['classId']).'" >'),
         $studentRecordArray[$i]);  
        }  
       }
       
      else{ //if studentId does exist in attendance_bulk table
      
        $valueArray = array_merge(array('srNo' => ($records+$i+1),
        'delivered'=>'<input type="textbox"  style="width:50px;text-align:center;" name="ldel" id="ldel'.$i.'" value="'.$letDel.'" onkeyup="changePercentage('.$i.',1);"  tabIndex='.($i+2*$cnt+10).' >',
        'attended'=>'<input type="textbox"  style="width:50px;text-align:center;" name="latt" id="latt'.$i.'" value="0" onkeyup="changePercentage('.$i.',2);" tabIndex='.($i+10).' onfocus="attAction();" >',
        'percentage'=>'<input type="textbox"  style="width:60px;text-align:center;" name="lcep" id="lcep'.$i.'" value="0%" readOnly="true" tabIndex='.($i+3*$cnt+10).' >',
        'memberOfClass'=>'<input type="checkbox" name="mem" id="mem'.$i.'" checked="checked" onclick="mocAction('.$i.');" tabIndex='.($i+$cnt+10).'>',
        'attendance'=>'<input type="hidden" name="attendance" id="attendance" value="-1'.'~'.strip_slashes($studentRecordArray[$i]['studentId']).'~'.strip_slashes($studentRecordArray[$i]['classId']).'" >'),
        $studentRecordArray[$i]);         
      }  

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: scAjaxInitBulkAttendanceList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScTeacherActivity
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/19/08    Time: 12:40p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/16/08    Time: 1:58p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/10/08    Time: 6:36p
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/09/08    Time: 5:18p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 9/01/08    Time: 5:36p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/18/08    Time: 5:39p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/14/08    Time: 2:58p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/06/08    Time: 6:50p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//Done modifications as discussed in the demo session
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/05/08    Time: 4:51p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/19/08    Time: 10:33a
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//Created DailyAttendance Module
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/18/08    Time: 1:16p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/17/08    Time: 5:20p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
//Initial checkin
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/17/08    Time: 5:17p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//ifTeacherNotLoggedIn
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/16/08    Time: 7:13p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
//Initial Checkin
?>
