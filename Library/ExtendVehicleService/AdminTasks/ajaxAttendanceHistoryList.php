<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To display attendance list
// Author : Dipanjan Bbhattacharjee
// Created on : (14.04.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','BulkAttendance');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/AdminTasksManager.inc.php");
    $adminTasksManager = AdminTasksManager::getInstance();

    /////////////////////////

    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = 'LIMIT '.$records.',100';
    //////

    /////search functionility not needed
    $fromDate=date('Y-m-d');
    $toDate=date('Y-m-d');

    $employeeId=trim($REQUEST_DATA['employeeId']);
    $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
    $callingModule=trim($REQUEST_DATA['attType']);

    if($employeeId=='' or $timeTableLabelId==''){
       echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cnt.'","page":"'.$page.'","info" : ['.$json_val.']}';
       die;
    }

    if(trim($REQUEST_DATA['subjectId'])!=""){
        $filter .= " AND att.subjectId=".trim($REQUEST_DATA['subjectId']);
        $filter1 .= " AND att.subjectId=".trim($REQUEST_DATA['subjectId']);
    }
    if(trim($REQUEST_DATA['classId'])!=""){
        $filter .= " AND att.classId=".trim($REQUEST_DATA['classId']);
        $filter1 .= " AND att.classId=".trim($REQUEST_DATA['classId']);
    }
    if(trim($REQUEST_DATA['groupId'])!=""){
        $filter .= " AND att.groupId=".trim($REQUEST_DATA['groupId']);
        $filter1 .= " AND att.groupId=".trim($REQUEST_DATA['groupId']);
    }
    if(isset($REQUEST_DATA['attType']) && trim($REQUEST_DATA['attType'])!='2'){ //if it is not coming from daily attendance page
      if(trim($REQUEST_DATA['includeDateRange'])==1){
        if(trim($REQUEST_DATA['fromDate'])!=''){
            $filter   .=' AND att.fromDate >="'.trim($REQUEST_DATA['fromDate']).'"';
            $filter1  .=' AND att.fromDate >="'.trim($REQUEST_DATA['fromDate']).'"';
            $fromDate  =  trim($REQUEST_DATA['fromDate']);
        }
        if(trim($REQUEST_DATA['toDate'])!=''){
            $filter  .=' AND att.toDate <="'.trim($REQUEST_DATA['toDate']).'"';
            $filter1 .=' AND att.toDate <="'.trim($REQUEST_DATA['toDate']).'"';
            $toDate  =  trim($REQUEST_DATA['toDate']);
        }
      }
    }
    //$filter .=" AND att.employeeId=".$employeeId." AND sub.hasAttendance = 1 AND ttl.timeTableLabelId=".$timeTableLabelId;
    $filter  .= " AND sub.hasAttendance = 1 AND ttl.timeTableLabelId=".$timeTableLabelId;
    $filter1 .= " AND att.employeeId=".$employeeId. " AND ttl.timeTableLabelId=".$timeTableLabelId;

    //fetch attendance records
    $attendanceHistoryArray=$adminTasksManager->getAttendanceHistoryOptions($filter1);
    $len=count($attendanceHistoryArray);
    if(is_array($attendanceHistoryArray) and $len>0){
        $attHistoryString='';
        for($i=0;$i<$len;$i++){
           if($attHistoryString!=''){
               $attHistoryString .=',';
           }
           $attHistoryString .="'".$attendanceHistoryArray[$i]['classId']."~".$attendanceHistoryArray[$i]['groupId']."~".$attendanceHistoryArray[$i]['subjectId']."'";
        }
       /*if($attHistoryString!=''){
           $attHistoryString =" AND CONCAT(att.classId,'~',att.groupId,'~',att.subjectId) IN (".$attHistoryString.")";
       }*/
    }
    //****if no attendance history records are found,then fetch equivalent time table records***
   if($attHistoryString==''){
    //******fetching information from TIME TABLE**********

     $classIdArray=$adminTasksManager->getTeacherAdjustedClass($fromDate,$toDate);
     if(is_array($classIdArray) and count($classIdArray)>0){
         $classIds=UtilityManager::makeCSList($classIdArray,'classId');
         $subjectIdArray=$adminTasksManager->getTeacherAdjustedSubject(' AND c.classId IN ('.$classIds.')',$fromDate,$toDate);
         if(is_array($subjectIdArray) and count($subjectIdArray)>0){
            $subjectIds=UtilityManager::makeCSList($subjectIdArray,'subjectId');
            $groupIdArray=$adminTasksManager->getAdjustedSubjectGroup($subjectIds,$classIds,$fromDate,$toDate);
            if(is_array($groupIdArray) and count($groupIdArray)>0){
                $groupIds=UtilityManager::makeCSList($groupIdArray,'groupId');
                $classArray=explode(',',$classIds);
                $c1=count($classArray);
                $subjectArray=explode(',',$subjectIds);
                $c2=count($subjectArray);
                $groupArray=explode(',',$groupIds);
                $c3=count($groupIds);
                $attHistoryString='';
                for($f1=0;$f1<$c1;$f1++){
                    for($f2=0;$f2<$c2;$f2++){
                        for($f3=0;$f3<$c3;$f3++){
                           if($attHistoryString!=''){
                               $attHistoryString .=',';
                           }
                           $attHistoryString .="'".$classArray[$f1]."~".$groupArray[$f3]."~".$subjectArray[$f2]."'";
                        }
                    }
                }
            }
            else{
                //echo ATTENDANCE_HISTORY_RESTRICTION;
                echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"0","page":"'.$page.'","info" : ['.$json_val.']}';
                die;
            }
         }
         else{
              //echo ATTENDANCE_HISTORY_RESTRICTION;
              echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"0","page":"'.$page.'","info" : ['.$json_val.']}';
              die;
         }
     }

     else{
         //echo ATTENDANCE_HISTORY_RESTRICTION;
         echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"0","page":"'.$page.'","info" : ['.$json_val.']}';
         die;
     }
     //******fetching information from TIME TABLE**********
   }

   if($attHistoryString!=''){
           $attHistoryString =" AND CONCAT(att.classId,'~',att.groupId,'~',att.subjectId) IN (".$attHistoryString.")";
   }
   else{ //if no records found in attendance & time table,then do not show attendance history
      //echo ATTENDANCE_HISTORY_RESTRICTION;
      echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"0","page":"'.$page.'","info" : ['.$json_val.']}';
      die;
   }



    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';

    $orderBy = " $sortField $sortOrderBy";

    ////////////

    $totalArray            = $adminTasksManager->getTotalAttendanceHistory($filter.$attHistoryString);
    $attendanceRecordArray = $adminTasksManager->getAttendanceHistory($filter.$attHistoryString,$limit,$orderBy);
    $cnt = count($attendanceRecordArray);

    for($i=0;$i<$cnt;$i++) {


       $attType=$attendanceRecordArray[$i]['attendanceType'];
       $fromDate=$attendanceRecordArray[$i]['fromDate'];
       $toDate=$attendanceRecordArray[$i]['toDate'];

       if($attendanceRecordArray[$i]['attendanceType']==1){
           $attendanceRecordArray[$i]['attendanceType']='Bulk';
       }
       elseif($attendanceRecordArray[$i]['attendanceType']==2){
           $attendanceRecordArray[$i]['attendanceType']='Daily';
       }

       $attendanceRecordArray[$i]['fromDate']=UtilityManager::formatDate($attendanceRecordArray[$i]['fromDate']);
       if($attendanceRecordArray[$i]['attendanceType']=='Daily'){
        $attendanceRecordArray[$i]['toDate']=NOT_APPLICABLE_STRING;
       }
       else{
           $attendanceRecordArray[$i]['toDate']=UtilityManager::formatDate($attendanceRecordArray[$i]['toDate']);
       }

       if(trim($attendanceRecordArray[$i]['topic'])==''){
           $attendanceRecordArray[$i]['topic']=NOT_APPLICABLE_STRING;
       }

       $classId=$attendanceRecordArray[$i]['classId'];
       $subjectId=$attendanceRecordArray[$i]['subjectId'];
       $groupId=$attendanceRecordArray[$i]['groupId'];
       $periodId=$attendanceRecordArray[$i]['periodId'];

       $actionStr=NOT_APPLICABLE_STRING;

       if(($callingModule==1 and $attType==1) or ($callingModule==2 and $attType==2) ){
          if($callingModule==1 and $attType==1){
             $actionStr='<a href="#" title="Edit"><img id="'.$fromDate.'~!~'.$toDate.'" src="'.IMG_HTTP_PATH.'/edit.gif" border="0" alt="Edit" onclick="editAttendance('.$classId.','.$subjectId.','.$groupId.',this.id);return false;"></a>';
          }
          else{
             $actionStr='<a href="#" title="Edit"><img id="'.$fromDate.'" src="'.IMG_HTTP_PATH.'/edit.gif" border="0" alt="Edit" onclick="editAttendance('.$classId.','.$subjectId.','.$groupId.','.$periodId.',this.id);return false;"></a>';
          }
       }
       else{
           $actionStr=NOT_APPLICABLE_STRING;
       }


       $actionStr .='&nbsp;<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" onClick="deleteAttendanceData('.$attendanceRecordArray[$i]['attendanceId'].');"/></a>';

       $valueArray = array_merge(
                        array(
                              'srNo' => ($records+$i+1),
                              'actionString'=>$actionStr,
                              "deleteAtt" => "<input type=\"checkbox\" name=\"attendance\" id=\"attendance\" value=\"".$attendanceRecordArray[$i]['employeeId'].'~'.$attendanceRecordArray[$i]['classId'].'~'.$attendanceRecordArray[$i]['subjectId'].'~'.$attendanceRecordArray[$i]['groupId'].'~'.$fromDate.'~'.$toDate.'~'.$attType.'~'.$attendanceRecordArray[$i]['periodId']."\">"
                             )
        , $attendanceRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }

    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}';

// for VSS
// $History: ajaxAttendanceHistoryList.php $
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 19/04/10   Time: 13:47
//Updated in $/LeapCC/Library/AdminTasks
//Added "Topics" column in "Attendance History Div"
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 17/12/09   Time: 11:01
//Updated in $/LeapCC/Library/AdminTasks
//Corrected coding in Attendance history display logic
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 14/12/09   Time: 12:52
//Updated in $/LeapCC/Library/AdminTasks
//Done bug fixing.
//Bug ids---
//0002259,0002258,0002257,0002256,0002255,0002252,0002251,
//0002250,0002254
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 26/11/09   Time: 11:30
//Updated in $/LeapCC/Library/AdminTasks
//Made enhancements in Attendance History : Teacher can now view other
//teachers attendance and also edit & delete them,if they have the same
//time table allocation.
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 16/11/09   Time: 13:07
//Updated in $/LeapCC/Library/AdminTasks
//Attendance History Option Enhanced :
//1.Attendance can be edited and deleted from this option.
//2.Attendance history list can be printed and also can be exported to
//excel.
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/11/09    Time: 16:50
//Created in $/LeapCC/Library/AdminTasks
//Added "Attendance History" option in bulk attendance from admin section
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 5/11/09    Time: 10:06
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Done bug fixing.
//Bug id---00001943
//
//*****************  Version 3  *****************
//User: Parveen      Date: 10/01/09   Time: 10:50a
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//condition updated hasAttendance, hasMarks & formatting updated
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Administrator Date: 5/06/09    Time: 17:06
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//Added "Attendance History" option in teacher module
//
//*****************  Version 2  *****************
//User: Administrator Date: 5/06/09    Time: 15:12
//Updated in $/LeapCC/Library/AdminTasks
//Corrected attendance deletion module's logic
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 14/04/09   Time: 17:21
//Created in $/LeapCC/Library/AdminTasks
//Created Attendance Delete Module
?>
