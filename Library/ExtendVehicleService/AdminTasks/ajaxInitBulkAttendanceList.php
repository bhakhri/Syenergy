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
    define('MODULE','BulkAttendance');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/AdminTasksManager.inc.php");
    $teacherManager = AdminTasksManager::getInstance();
	$newAttendance = false;
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
    if(trim($REQUEST_DATA['classId'])!=""){
        $filter =$filter." AND c.classId=".trim($REQUEST_DATA['classId']);
    }

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    //$sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'isLeet,universityRollNo';

	$sortField = $REQUEST_DATA['sortField'];

    if($sortField=="studentName"){
        $sortField2="studentName";
    }
    elseif($sortField=="rollNo"){
        $sortField2="rollNo";
    }
    elseif($sortField=="universityRollNo"){
        $sortField2=" IF(universityRollNo='' OR universityRollNo IS NULL,IF(rollNo='' OR rollNo IS NULL,studentName,rollNo),universityRollNo)";
    }
    else {
        $sortField2=" IF(universityRollNo='' OR universityRollNo IS NULL,IF(rollNo='' OR rollNo IS NULL,studentName,rollNo),universityRollNo)";
    }


    $orderBy = " $sortField2 $sortOrderBy";

    $totalArray         = $teacherManager->getSearchTotalStudent($filter);
    $studentRecordArray = $teacherManager->getStudentsList($filter,$limit,$orderBy);
    $attendanceRecordArray=$teacherManager->getBulkAttendanceList();

    //*********used to fetch student's attendance till before "From Date"****************
    $searchStr=' AND c.classId='.$REQUEST_DATA['classId'].' AND su.subjectId='.$REQUEST_DATA['subject'].' AND att.groupId='.$REQUEST_DATA['group'].' AND att.fromDate < "'.$REQUEST_DATA['startDate'].'"';
    $prevoiusRecordArray=$teacherManager->getStudentAttendanceTillDate($searchStr,' ',$orderBy);
    $previousCount=count($prevoiusRecordArray);
    //print_r($attendanceRecordArray);

	if($REQUEST_DATA['sortField'] == 'rollNo') {
		$sortField = 'rollNo';
	}

    //$letDel=$REQUEST_DATA['lectureDelivered']; //lecture delivered
    $letDel=0;


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

    for($i=0;$i<$cnt;$i++) {
        $studentId=$studentRecordArray[$i]['studentId'];
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
         $flk=0;
         for($m=0;$m<$previousCount;$m++){
             if($studentId==$prevoiusRecordArray[$m]['studentId']){
                $oldAttended = abs($prevoiusRecordArray[$m]['attended']);
                $oldAttended = "$oldAttended";
                $newLectureDeliverd = strip_slashes($attendanceRecordArray[$j]['lectureDelivered']+abs($prevoiusRecordArray[$m]['delivered']));
                $newLectureAttended = strip_slashes($attendanceRecordArray[$j]['lectureAttended']+abs($prevoiusRecordArray[$m]['attended']));
                $oldLectureDelivered= strip_slashes(abs($prevoiusRecordArray[$m]['delivered']));
                $oldLectureAttended=  strip_slashes(abs($prevoiusRecordArray[$m]['attended']));
                if(strip_slashes($attendanceRecordArray[$j]['lectureDelivered']+abs($prevoiusRecordArray[$m]['delivered'])) >0){
                 $per=number_format((strip_slashes($attendanceRecordArray[$j]['lectureAttended']+abs($prevoiusRecordArray[$m]['attended']))/strip_slashes($attendanceRecordArray[$j]['lectureDelivered']+abs($prevoiusRecordArray[$m]['delivered'])))*100,2,'.','');
                }
                else{
                    $per="0.00";
                }
                $flk=1;
                break;
             }
         }
         if(!$flk){
            $oldAttended="0";
            $newLectureDeliverd=strip_slashes($attendanceRecordArray[$j]['lectureDelivered']);
            $newLectureAttended = strip_slashes($attendanceRecordArray[$j]['lectureAttended']);
            $oldLectureDelivered= 0;
            $oldLectureAttended=  0;
            if(strip_slashes($attendanceRecordArray[$j]['lectureDelivered']) >0){
                 $per=number_format((strip_slashes($attendanceRecordArray[$j]['lectureAttended'])/strip_slashes($attendanceRecordArray[$j]['lectureDelivered']))*100,2,'.','');
            }
              else{
                $per="0.00";
            }
         }
        if($attendanceRecordArray[$j]['isMemberOfClass']=="1"){
         //$oldAttended = abs($prevoiusRecordArray[$i]['attended']);
         //$oldAttended = "$oldAttended";

         $valueArray = array_merge(array('srNo' => ($records+$i+1),
         //'delivered'=>'<input type="textbox"  style="width:50px;text-align:center;" name="ldel" id="ldel'.$i.'" value="'.strip_slashes($attendanceRecordArray[$j]['lectureDelivered']).'" disabled   onkeyup="changePercentage('.$i.',1);" tabIndex='.($i+2*$cnt+10).' >',
         'delivered'=>'<input type="textbox" class="inputbox"  style="width:50px;text-align:center;" name="ldel" id="ldel'.$i.'" value="'.$newLectureDeliverd.'"    onkeyup="changePercentage('.$i.',1);" tabIndex='.($i+2*$cnt+10).' >',
         'attended'=>'<input type="textbox" class="inputbox" style="width:50px;text-align:center;" name="latt" id="latt'.$i.'" value="'.$newLectureAttended.'" onkeyup="changePercentage('.$i.',2);" tabIndex='.($i+10).' onfocus="attAction();" >',
         'percentage'=>'<input type="textbox" class="inputbox"  style="width:60px;text-align:center;" name="lcep" id="lcep'.$i.'" value="'.$per.'%" readOnly="true" tabIndex='.($i+3*$cnt+10).' >',
         'memberOfClass'=>'<input type="checkbox" name="mem" id="mem'.$i.'" checked  onclick="mocAction('.$i.');" tabIndex='.($i+$cnt+10).'>'.
                          '<input type="hidden" name="attendance" id="attendance" value="'.strip_slashes($attendanceRecordArray[$j]['attendanceId']).'~'.strip_slashes($studentRecordArray[$i]['studentId']).'" >'.
                          '<input type="hidden" name="old_ldel" id="old_ldel'.$i.'" value="'.$oldLectureDelivered.'">'.
                          '<input type="hidden" name="old_att" id="old_att'.$i.'"   value="'.$oldLectureAttended.'">',
        'oldDelivered'=>$oldLectureDelivered,
        //'oldAttended'=>strip_slashes(abs($prevoiusRecordArray[$i]['attended']))
        'oldAttended'=>$oldLectureAttended
                          ),
         $studentRecordArray[$i]);
        }
        else{

         //$oldAttended = abs($prevoiusRecordArray[$i]['attended']);
         //$oldAttended = "$oldAttended";

         $valueArray = array_merge(array('srNo' => ($records+$i+1),
         'delivered'=>'<input type="textbox"  style="width:50px;text-align:center;" name="ldel" id="ldel'.$i.'" value="'.$newLectureDeliverd.'"  disabled   onkeyup="changePercentage('.$i.',1);" tabIndex='.($i+2*$cnt+10).' >',
         //'delivered'=>'<input type="textbox"  style="width:50px;text-align:center;" name="ldel" id="ldel'.$i.'" value="'.strip_slashes($attendanceRecordArray[$j]['lectureDelivered']).'"  readOnly="true"   onkeyup="changePercentage('.$i.',1);" tabIndex='.($i+2*$cnt+10).' >',
         'attended'=>'<input type="textbox"  class="inputbox" style="width:50px;text-align:center;" name="latt" id="latt'.$i.'" value="'.$newLectureAttended .'" disabled onkeyup="changePercentage('.$i.',2);"   tabIndex='.($i+10).' onfocus="attAction();">',
         'percentage'=>'<input type="textbox"  class="inputbox" style="width:60px;text-align:center;" name="lcep" id="lcep'.$i.'" value="'.$per.'%" readOnly="true" tabIndex='.($i+3*$cnt+10).' >',
         'memberOfClass'=>'<input type="checkbox" class="inputbox" name="mem" id="mem'.$i.'" onclick="mocAction('.$i.');" tabIndex='.($i+$cnt+10).'>'.
                          '<input type="hidden" name="attendance" id="attendance" value="'.strip_slashes($attendanceRecordArray[$j]['attendanceId']).'~'.strip_slashes($studentRecordArray[$i]['studentId']).'" >'.
                          '<input type="hidden" name="old_ldel" id="old_ldel'.$i.'" value="'.$oldLectureDelivered.'">'.
                          '<input type="hidden" name="old_att" id="old_att'.$i.'"   value="'.$oldLectureAttended.'">',
        'oldDelivered'=>$oldLectureDelivered,
        //'oldAttended'=>strip_slashes(abs($prevoiusRecordArray[$i]['attended']))
        'oldAttended'=>$oldLectureAttended
                          ),
         $studentRecordArray[$i]);
        }
       }

      else{ //if studentId does exist in attendance_bulk table

        $flk=0;
        for($m=0;$m<$previousCount;$m++){
             if($studentId==$prevoiusRecordArray[$m]['studentId']){
                $oldAttended = abs($prevoiusRecordArray[$m]['attended']);
                $oldAttended = "$oldAttended";
                $newLectureDeliverd = strip_slashes($attendanceRecordArray[$j]['lectureDelivered']+abs($prevoiusRecordArray[$m]['delivered']));
                $newLectureAttended = strip_slashes($attendanceRecordArray[$j]['lectureAttended']+abs($prevoiusRecordArray[$m]['attended']));
                $oldLectureDelivered= strip_slashes(abs($prevoiusRecordArray[$m]['delivered']));
                $oldLectureAttended=  strip_slashes(abs($prevoiusRecordArray[$m]['attended']));
                if(strip_slashes($attendanceRecordArray[$j]['lectureDelivered']+abs($prevoiusRecordArray[$m]['delivered'])) >0){
                 $per=number_format((strip_slashes($attendanceRecordArray[$j]['lectureAttended']+abs($prevoiusRecordArray[$m]['attended']))/strip_slashes($attendanceRecordArray[$j]['lectureDelivered']+abs($prevoiusRecordArray[$m]['delivered'])))*100,2,'.','');
                }
                else{
                    $per="0.00";
                }
                $flk=1;
                break;
             }
         }
         if(!$flk){
            $oldAttended="0";
            $newLectureDeliverd=strip_slashes($attendanceRecordArray[$j]['lectureDelivered']);
            $newLectureAttended = strip_slashes($attendanceRecordArray[$j]['lectureAttended']);
            $oldLectureDelivered= 0;
            $oldLectureAttended=  0;
            if(strip_slashes($attendanceRecordArray[$j]['lectureDelivered']) >0){
                 $per=number_format((strip_slashes($attendanceRecordArray[$j]['lectureAttended'])/strip_slashes($attendanceRecordArray[$j]['lectureDelivered']))*100,2,'.','');
            }
              else{
                $per="0.00";
            }
         }

        $valueArray = array_merge(array('srNo' => ($records+$i+1),
        //'delivered'=>'<input type="textbox"  style="width:50px;text-align:center;" name="ldel" id="ldel'.$i.'" value="'.$letDel.'" onkeyup="changePercentage('.$i.',1);" readOnly="true" tabIndex='.($i+2*$cnt+10).' >',
        //'delivered'=>'<input type="textbox" class="inputbox" style="width:50px;text-align:center;" name="ldel" id="ldel'.$i.'" value="'.($letDel+abs($prevoiusRecordArray[$i]['delivered'])).'" onkeyup="changePercentage('.$i.',1);"  tabIndex='.($i+2*$cnt+10).' >',
        'delivered'=>'<input type="textbox" class="inputbox" style="width:50px;text-align:center;" name="ldel" id="ldel'.$i.'" value="0" onkeyup="changePercentage('.$i.',1);"  tabIndex='.($i+2*$cnt+10).' >',
        //'attended'=>'<input type="textbox" class="inputbox" style="width:50px;text-align:center;" name="latt" id="latt'.$i.'" value="'.abs($prevoiusRecordArray[$i]['attended']).'" onkeyup="changePercentage('.$i.',2);" tabIndex='.($i+10).' onfocus="attAction();" >',
        'attended'=>'<input type="textbox" class="inputbox" style="width:50px;text-align:center;" name="latt" id="latt'.$i.'" value="0" onkeyup="changePercentage('.$i.',2);" tabIndex='.($i+10).' onfocus="attAction();" >',
        'percentage'=>'<input type="textbox" class="inputbox" style="width:60px;text-align:center;" name="lcep" id="lcep'.$i.'" value="'.$per.'%" readOnly="true" tabIndex='.($i+3*$cnt+10).' >',
        'memberOfClass'=>'<input type="checkbox" name="mem" id="mem'.$i.'" checked="checked" onclick="mocAction('.$i.');" tabIndex='.($i+$cnt+10).'>'.
                         '<input type="hidden" name="attendance" id="attendance" value="-1'.'~'.strip_slashes($studentRecordArray[$i]['studentId']).'" >'.
                         '<input type="hidden" name="old_ldel" id="old_ldel'.$i.'" value="'.$oldLectureDelivered.'">'.
                         '<input type="hidden" name="old_att" id="old_att'.$i.'"   value="'.$oldLectureAttended.'">',
        'oldDelivered'=>$oldLectureDelivered,
        //'oldAttended'=>strip_slashes(abs($prevoiusRecordArray[$i]['attended']))
        'oldAttended'=>$oldLectureAttended
                         ),
        $studentRecordArray[$i]);
		$newAttendance = true;
      }

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }
    $topicsTaughtId="".$topicsTaughtId."";
    $subjectTaughtId="".$subjectTaughtId."";
    $topicTaughtId="".$topicTaughtId."";

    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.'],"topicsId" : '.json_encode($topicsTaughtId).',"topicsComments" : '.json_encode($topicsComments).',"topicSubjectId" : '.json_encode($subjectTaughtId).',"topicTaughtId" : '.json_encode($topicTaughtId).',"newAttendance":"'.$newAttendance.'"}';

// for VSS
// $History: ajaxInitBulkAttendanceList.php $
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 20/11/09   Time: 17:43
//Updated in $/LeapCC/Library/AdminTasks
//Modified sorting fields logic:sort on university roll no if
//present,else sort on college roll no if present,else sort on student
//names
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 12/11/09   Time: 17:21
//Updated in $/LeapCC/Library/AdminTasks
//Modified logic in bulk attendance module and corrected flaws in coding
//and removed check which prevents taking attendance across months or
//years.
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 3/11/09    Time: 17:08
//Updated in $/LeapCC/Library/AdminTasks
//Fixed bugs in bulk attendance module : Mismatched Lecture Delivered and
//attended problem
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 14/10/09   Time: 18:16
//Updated in $/LeapCC/Library/AdminTasks
//Made code and logic changes to take care of optional subjects repaled
//problems
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/AdminTasks
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Administrator Date: 11/06/09   Time: 16:00
//Created in $/LeapCC/Library/AdminTasks
//Created "Bulk Attendance" modules in admin section in leapcc
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 6/04/09    Time: 16:19
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Applying "Jugaad" for ajax %k problem--referred by Mr.Ajinder Singh
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 3/04/09    Time: 17:51
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Incorporated new logic for bulk attendance
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
//*****************  Version 10  *****************
//User: Dipanjan     Date: 9/20/08    Time: 3:56p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 9/16/08    Time: 1:58p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
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
