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

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifTeacherNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
    require_once(MODEL_PATH."/CommonQueryManager.inc.php");
    
    //attendanceCode from the table "attendance_code" table
    $attCode = CommonQueryManager::getInstance()->getAttendanceCode();
    $cntAttCode=count($attCode);
    
    //as this will be same for all the studentIds that are not in attendance_daily it is kept outside loop
    $optStr2="";
    for($k=0 ; $k < $cntAttCode ; $k++){
      if($optStr2 == ""){
          if($attCode[$k]['attendanceCodeId']==1){ //hardcoded  for "PRE"
            $optStr2 = '<option value="'.$attCode[$k]['attendanceCodeId'].'" selected="selected" >'.$attCode[$k]['attendanceCode'].'</option>';
          }
         else{
            $optStr2 = '<option value="'.$attCode[$k]['attendanceCodeId'].'" >'.$attCode[$k]['attendanceCode'].'</option>'; 
         }  
      }
     else{
         if($attCode[$k]['attendanceCodeId']==1){ //hardcoded  for "PRE"   
          $optStr2 .= '<option value="'.$attCode[$k]['attendanceCodeId'].'"  selected="selected"  >'.$attCode[$k]['attendanceCode'].'</option>';                     
         }
        else{
          $optStr2 .= '<option value="'.$attCode[$k]['attendanceCodeId'].'" >'.$attCode[$k]['attendanceCode'].'</option>';                        
        }  
     } 
    }
   //$optStr2 .='<option value="" >SELECT ATTD. CODE</option>';
    
    
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
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    
     $orderBy = " $sortField $sortOrderBy";         
    
    $totalArray = $teacherManager->getTotalStudent($filter);
    $studentRecordArray = $teacherManager->getStudentList($filter,$limit,$orderBy);  
    $attendanceRecordArray=$teacherManager->getDailyAttendanceList($filter,$orderBy);
    //print_r($attendanceRecordArray);
    

    $cnt = count($studentRecordArray);  //count of student records
    $attendanceRecordCount = count($attendanceRecordArray);  //count of attendance records
    
    //for adding null values when not member of class
    $nullStr='<option value="NULL" selected="selected"></option>';

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
       
       if($fl==1){ //if studentId exist in attendance_daily table
        //echo $attendanceRecordArray[$j]['studentId']."~~".$attendanceRecordArray[$i]['attendanceCodeId']."<br>";
        $optStr="";
        for($k=0 ; $k < $cntAttCode ; $k++){
          if($optStr==""){
              if($attCode[$k]['attendanceCodeId']==$attendanceRecordArray[$i]['attendanceCodeId']){
                $optStr='<option value="'.$attCode[$k]['attendanceCodeId'].'" selected="selected">'.$attCode[$k]['attendanceCode'].'</option>';  
              }
             else{
               $optStr='<option value="'.$attCode[$k]['attendanceCodeId'].'" >'.$attCode[$k]['attendanceCode'].'</option>';                     
             } 
          }
         else{
             if($attCode[$k]['attendanceCodeId']==$attendanceRecordArray[$i]['attendanceCodeId']){
                $optStr .= '<option value="'.$attCode[$k]['attendanceCodeId'].'" selected="selected">'.$attCode[$k]['attendanceCode'].'</option>';  
              }
             else{
               $optStr .= '<option value="'.$attCode[$k]['attendanceCodeId'].'" >'.$attCode[$k]['attendanceCode'].'</option>';                     
             }
         } 
      }
      //even if attendenceCode is not in datebase select will appended
      //$optStr .='<option value="" >SELECT ATTD. CODE</option>';
        if($attendanceRecordArray[$j]['isMemberOfClass']=="1"){ //if memberof class           //tabIndex starts from 10(for attCodes) and (10+noofrecords) for memofclasses
        $valueArray = array_merge(array('srNo' => ($records+$i+1),
        'attendanceCode'=>'<select name="attendanceCode" id="attendanceCode'.$i.'" size="1" class="selectfield3" tabIndex='.($i+10).' >'.$optStr.'</select>',
        'memberOfClass'=>'<input type="checkbox" name="mem" id="mem'.$i.'" checked  onclick="mocAction('.$i.');" tabIndex='.($i+$cnt+10).'  >',
        'attendance'=>'<input type="hidden" name="attendance" id="attendance" value="'.strip_slashes($attendanceRecordArray[$j]['attendanceId']).'~'.strip_slashes($studentRecordArray[$i]['studentId']).'~'.strip_slashes($studentRecordArray[$i]['classId']).'" >'),
        $studentRecordArray[$i]);
       }
      else{   //if not memberof class
         $valueArray = array_merge(array('srNo' => ($records+$i+1),
        'attendanceCode'=>'<select name="attendanceCode" id="attendanceCode'.$i.'" size="1" class="selectfield3" disabled="true" tabIndex='.($i+10).'  >'.$optStr.$nullStr.'</select>',
        'memberOfClass'=>'<input type="checkbox" name="mem" id="mem'.$i.'"  onclick="mocAction('.$i.');"  tabIndex='.($i+$cnt+10).' >',
        'attendance'=>'<input type="hidden" name="attendance" id="attendance" value="'.strip_slashes($attendanceRecordArray[$j]['attendanceId']).'~'.strip_slashes($studentRecordArray[$i]['studentId']).'~'.strip_slashes($studentRecordArray[$i]['classId']).'" >'),
        $studentRecordArray[$i]); 
      }  
     }
    else{ //if studentId does exist in attendance_daily table

        $valueArray = array_merge(array('srNo' => ($records+$i+1),
        'attendanceCode'=>'<select name="attendanceCode" id="attendanceCode'.$i.'" size="1" class="selectfield3"  tabIndex='.($i+10).' >'.$optStr2.'</select>',
        'memberOfClass'=>'<input type="checkbox" name="mem" id="mem'.$i.'" checked="checked" onclick="mocAction('.$i.');" tabIndex='.($i+$cnt+10).'  >',
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
// $History: scAjaxInitDailyAttendanceList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScTeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/17/08    Time: 10:40a
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
?>
