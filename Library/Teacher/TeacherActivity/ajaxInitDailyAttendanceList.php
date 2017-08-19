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
    define('MODULE','DailyAttendance');
    define('ACCESS','view');
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==2){
     UtilityManager::ifTeacherNotLoggedIn(true);
    }
    else{
    UtilityManager::ifNotLoggedIn(true);
    }
    UtilityManager::headerNoCache();

    if(trim($REQUEST_DATA['group'])==''){
		die('Required Parameters Missing');
	}
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    require_once(MODEL_PATH."/CommonQueryManager.inc.php");
	$newAttendance = false;
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


    $teacherManager = TeacherManager::getInstance();

    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE_TEACHER;
    //$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE_TEACHER;

    /*
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (sub.subjectName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR sub.subjectCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
    */
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

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
	//$sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'isLeet,universityRollNo';
	//$sortField = $REQUEST_DATA['sortField'];
	if($sortField=="studentName"){
        $sortField2="studentName";
    }
    elseif($sortField=="rollNo"){
        $sortField2="LENGTH(rollNo)+0,rollNo";
    }
    elseif($sortField=="universityRollNo"){
        $sortField2=" IF(universityRollNo='' OR universityRollNo IS NULL,IF(rollNo='' OR rollNo IS NULL,studentName,rollNo),universityRollNo)";
    }
	else {
		$sortField2=" IF(universityRollNo='' OR universityRollNo IS NULL,IF(rollNo='' OR rollNo IS NULL,studentName,rollNo),universityRollNo) AND s.isLeet";
	}
    $orderBy = " $sortField2 $sortOrderBy";

    $totalArray            = $teacherManager->getSearchTotalStudent($filter); 
    $studentRecordArray    = $teacherManager->getStudentsList($filter,$limit,$orderBy);    
    $attendanceRecordArray = $teacherManager->getDailyAttendanceList();
	//print_r($studentRecordArray); die;
    //*********used to fetch student's attendance till <= "From Date"****************
    if(trim($REQUEST_DATA['subject'])!=""){
        $searchStr =$searchStr." AND su.subjectId=".trim($REQUEST_DATA['subject']);
    }
    if(trim($REQUEST_DATA['group'])!=""){
        $searchStr =$searchStr." AND att.groupId=".trim($REQUEST_DATA['group']);
    }
    if(trim($REQUEST_DATA['class'])!=""){
        $searchStr =$searchStr." AND c.classId=".trim($REQUEST_DATA['class']);
    }
	$searchStr1 = $searchStr;
    //$searchStr=' AND c.classId='.$REQUEST_DATA['class'].' AND su.subjectId='.$REQUEST_DATA['subject'].' AND att.groupId='.$REQUEST_DATA['group'].' AND att.fromDate <= "'.$REQUEST_DATA['forDate'].'"';
    if(trim($REQUEST_DATA['forDate'])!=""){
     $searchStr .=' AND att.fromDate <= "'.$REQUEST_DATA['forDate'].'"';
	 // $searchStr1 .=' AND att.fromDate = "'.$REQUEST_DATA['forDate'].'"';
    }

    if(trim($REQUEST_DATA['timeTableLabelId'])!=""){
      if($sessionHandler->getSessionVariable('RoleId')!=2){
        $searchStr .=' AND ttc.timeTableLabelId="'.trim($REQUEST_DATA['timeTableLabelId']).'"';
		//$searchStr1 .=' AND ttc.timeTableLabelId="'.trim($REQUEST_DATA['timeTableLabelId']).'"';
      }
    }
/*	if(trim($REQUEST_DATA['period'])!=""){
		//$searchStr1 .='AND att.periodId="'.trim($REQUEST_DATA['period']).'"';
	}*/

	//$checkAttendance = $teacherManager->getStudentAttendanceTillDateDailyAttendance($searchStr1,' ',$orderBy);
	//$checkAttendanceCount = COUNT($checkAttendance);
    $prevoiusRecordArray=$teacherManager->getStudentAttendanceTillDateDailyAttendance($searchStr,' ',$orderBy);
    $previousRecordCount=count($prevoiusRecordArray);
    //*********used to fetch student's attendance till <= "From Date"****************

    $cnt = count($studentRecordArray);  //count of student records
    $attendanceRecordCount = count($attendanceRecordArray);  //count of attendance records

	$topicsTaughtId=''; //id of topics taught
    $topicsComments=''; //comments of teacher


	if($attendanceRecordCount >0 && is_array($attendanceRecordArray) ) { //reduntant but dont called into loop
         //get the topicsId and comments
         $topicsTaughtId=$attendanceRecordArray[0]['taughtId'];
		 $subjectTaughtId=$attendanceRecordArray[0]['subjectTopicId'];
		 $topicTaughtId = $attendanceRecordArray[0]['topicsTaughtId'];
         $topicsComments=strip_slashes($attendanceRecordArray[0]['comments']);
    }
    if($topicsTaughtId==''){
         $topicsTaughtId="";
     }
     if($subjectTaughtId==''){
         $subjectTaughtId="";
     }
     if($topicTaughtId==''){
         $topicTaughtId="";
     }

    //for adding null values when not member of class
    $nullStr='<option value="NULL" selected="selected"></option>';

    for($i=0;$i<$cnt;$i++) {
        $rollNo=trim($studentRecordArray[$i]['studentId']);

        $studentRecordArray[$i]['studentName']='<a class="whiteClass" href="javascript:void(0);" onclick="changeAttendanceCode('.$i.');return false;">'.$studentRecordArray[$i]['studentName'].'</a>';
        $studentRecordArray[$i]['rollNo']='<a class="whiteClass" href="javascript:void(0);" onclick="changeAttendanceCode('.$i.');return false;">'.$studentRecordArray[$i]['rollNo'].'</a>';
        $studentRecordArray[$i]['universityRollNo']='<a class="whiteClass" href="javascript:void(0);" onclick="changeAttendanceCode('.$i.');return false;">'.$studentRecordArray[$i]['universityRollNo'].'</a>';

        $per="0.00";
        $delivered=0;
        $attended=0;
        for($m=0;$m<$previousRecordCount;$m++){ //showing previous attendance record of a student
          if($prevoiusRecordArray[$m]['studentId']==$studentRecordArray[$i]['studentId']){
          if(abs($prevoiusRecordArray[$m]['delivered'])>0){
              $per=number_format((strip_slashes(abs($prevoiusRecordArray[$m]['attended']))/strip_slashes(abs($prevoiusRecordArray[$m]['delivered'])))*100,2,'.','');
              $delivered=strip_slashes(abs($prevoiusRecordArray[$m]['delivered']));
              $attended=strip_slashes(abs($prevoiusRecordArray[$m]['attended']));
          }
         else{
              $per="0.00";
              $delivered=0;
              $attended=0;
            }
          }
        }

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
        $newAttendance = false;
		$optStr="";
        for($k=0 ; $k < $cntAttCode ; $k++){
             if($attCode[$k]['attendanceCodeId']==$attendanceRecordArray[$j]['attendanceCodeId']){
                $optStr .= '<option value="'.$attCode[$k]['attendanceCodeId'].'" selected="selected">'.$attCode[$k]['attendanceCode'].'</option>';
              }
             else{

               $optStr .= '<option value="'.$attCode[$k]['attendanceCodeId'].'" >'.$attCode[$k]['attendanceCode'].'</option>';
             }
        }

      //even if attendenceCode is not in datebase select will appended
      //$optStr .='<option value="" >SELECT ATTD. CODE</option>';
        if($attendanceRecordArray[$j]['isMemberOfClass']=="1"){ //if memberof class           //tabIndex starts from 10(for attCodes) and (10+noofrecords) for memofclasses
        $valueArray = array_merge(array('srNo' => ($records+$i+1),
        'attendanceCode'=>'<select onchange="generateAttendanceSummery();setGlobalEditFlag(1);" alt="'.$rollNo.'" name="attendanceCode" id="attendanceCode'.$i.'" size="1" class="selectfield3" tabIndex='.($i+10).' >'.$optStr.'</select>',
        'memberOfClass'=>'<input type="checkbox" name="mem" id="mem'.$i.'" checked  onclick="mocAction('.$i.');" tabIndex='.($i+$cnt+10).'  >'.'<input type="hidden" name="attendance" id="attendance" value="'.strip_slashes($attendanceRecordArray[$j]['attendanceId']).'~'.strip_slashes($studentRecordArray[$i]['studentId']).'" >',
        'delivered'=>$delivered,
        'attended'=>$attended,
        'percentage'=>$per
        //,'attendance'=>'<input type="hidden" name="attendance" id="attendance" value="'.strip_slashes($attendanceRecordArray[$j]['attendanceId']).'~'.strip_slashes($studentRecordArray[$i]['studentId']).'" >'
        ),
        $studentRecordArray[$i]);
       }
      else{   //if not memberof class
         $valueArray = array_merge(array('srNo' => ($records+$i+1),
        'attendanceCode'=>'<select onchange="generateAttendanceSummery();setGlobalEditFlag(1);" alt="'.$rollNo.'" name="attendanceCode" id="attendanceCode'.$i.'" size="1" class="selectfield3" disabled="true" tabIndex='.($i+10).'  >'.$optStr.$nullStr.'</select>',
        'memberOfClass'=>'<input type="checkbox" name="mem" id="mem'.$i.'"  onclick="mocAction('.$i.');"  tabIndex='.($i+$cnt+10).' >'.'<input type="hidden" name="attendance" id="attendance" value="'.strip_slashes($attendanceRecordArray[$j]['attendanceId']).'~'.strip_slashes($studentRecordArray[$i]['studentId']).'" >',
        'delivered'=>$delivered,
        'attended'=>$attended,
        'percentage'=>$per
        //,'attendance'=>'<input type="hidden" name="attendance" id="attendance" value="'.strip_slashes($attendanceRecordArray[$j]['attendanceId']).'~'.strip_slashes($studentRecordArray[$i]['studentId']).'" >'
        ),
        $studentRecordArray[$i]);
      }
     }
    else{ //if studentId does exist in attendance_daily table
		$newAttendance = true;
        $valueArray = array_merge(array('srNo' => ($records+$i+1),
        'attendanceCode'=>'<select onchange="generateAttendanceSummery();setGlobalEditFlag(1);" alt="'.$rollNo.'" name="attendanceCode" id="attendanceCode'.$i.'" size="1" class="selectfield3"  tabIndex='.($i+10).' >'.$optStr2.'</select>',
        'memberOfClass'=>'<input type="checkbox" name="mem" id="mem'.$i.'" checked="checked" onclick="mocAction('.$i.');" tabIndex='.($i+$cnt+10).'  >'.'<input type="hidden" name="attendance" id="attendance" value="-1'.'~'.strip_slashes($studentRecordArray[$i]['studentId']).'" >',
        'delivered'=>$delivered,
        'attended'=>$attended,
        'percentage'=>$per
        //,'attendance'=>'<input type="hidden" name="attendance" id="attendance" value="-1'.'~'.strip_slashes($studentRecordArray[$i]['studentId']).'" >'
        ),
        $studentRecordArray[$i]);
      }

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.'],"topicsId" : '.json_encode($topicsTaughtId).',"topicsComments" : '.json_encode($topicsComments).',"topicSubjectId" : '.json_encode($subjectTaughtId).',"topicTaughtId" : '.json_encode($topicTaughtId).',"attendanceCodeInfo" : '.json_encode($attCode).',"newAttendance":"'.$newAttendance.'"}';


// for VSS
// $History: ajaxInitDailyAttendanceList.php $
//
//*****************  Version 17  *****************
//User: Dipanjan     Date: 17/04/10   Time: 17:25
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Made changes in Teacher module for DAILY_TIMETABLE issues
//
//*****************  Version 16  *****************
//User: Dipanjan     Date: 17/04/10   Time: 12:39
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added "Daily Attenance" module in admin end
//
//*****************  Version 15  *****************
//User: Dipanjan     Date: 13/04/10   Time: 17:03
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Done llrit enhancements
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 3/12/09    Time: 11:02
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Made UI related changes :  Added alert for unsaved data
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 2/12/09    Time: 12:22
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Made UI changes
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 20/11/09   Time: 17:43
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Modified sorting fields logic:sort on university roll no if
//present,else sort on college roll no if present,else sort on student
//names
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 5/11/09    Time: 10:06
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Done bug fixing.
//Bug id---00001943
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 15/10/09   Time: 11:41
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Done enhancements in teacher login.
//1. Subject and groups are coming based upon selection of class
//,subjects in "Search Student" module in teacher login.Previously all
//values are coming.
//
//2.Before saving attendance data in daily attendance module,user have to
//confirm the attendance summary.Previously after saving data,this
//information is displayed.
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 8/10/09    Time: 15:01
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Fixed Query Error
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 6/10/09    Time: 16:27
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Done Bug Fixing.
//Bug ids--
// 0001663: Daily Attendance (Teacher) > Attendance Data is not
//displaying correctly in grid on “Daily Attendance” page.
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 31/07/09   Time: 16:01
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Corrected attendance sorting problem
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 21/07/09   Time: 12:08
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Done bug fixing.
//Bug ids ----0000627,0000632,0000633,0000640
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 3/24/09    Time: 6:30p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//modified as per sorting university roll no. Leet & isLett
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 3/12/09    Time: 11:49a
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//modified the files for topics taught
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
//*****************  Version 7  *****************
//User: Dipanjan     Date: 9/01/08    Time: 5:36p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/14/08    Time: 2:58p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/05/08    Time: 3:01p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/25/08    Time: 11:52a
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/24/08    Time: 7:54p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//Make modifications for having  daily and bulk attendance in a single
//table
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/19/08    Time: 10:33a
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//Created DailyAttendance Module
?>
