<?php
//This file is used as csv version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 24.10.2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/AdminTasksManager.inc.php");
    $adminTasksManager = AdminTasksManager::getInstance();


//to parse csv values
function parseCSVComments($comments) {
 $comments = str_replace('"', '""', $comments);
 $comments = str_ireplace('<br/>', "\n", $comments);
 if(eregi(",", $comments) or eregi("\n", $comments)) {
   return '"'.$comments.'"';
 }
 else {
 return $comments;
 }

}

    $employeeId=trim($REQUEST_DATA['employeeId']);
    $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
    $fromDate=date('Y-m-d');
    $toDate=date('Y-m-d');

    if($employeeId=='' or $timeTableLabelId==''){
        echo 'Required Parameters Missing';
        die;
    }
    /////////////////////////

    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    //$limit      = 'LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////

    /////search functionility not needed

    $callingModule=trim($REQUEST_DATA['attType']);

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

    $showNoDataFlag=0; //when we dont want to show records

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
                $showNoDataFlag=1;
            }
         }
         else{
              //echo ATTENDANCE_HISTORY_RESTRICTION;
              $showNoDataFlag=1;
         }
     }
     else{
         //echo ATTENDANCE_HISTORY_RESTRICTION;
         $showNoDataFlag=1;
     }
     //******fetching information from TIME TABLE**********
   }
   if($attHistoryString!=''){
           $attHistoryString =" AND CONCAT(att.classId,'~',att.groupId,'~',att.subjectId) IN (".$attHistoryString.")";
   }
   else{ //if no records found in attendance & time table,then do not show attendance history
      //echo ATTENDANCE_HISTORY_RESTRICTION;
       $showNoDataFlag=1;
   }



    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'employeeName';

    $orderBy = " $sortField $sortOrderBy";

    ////////////

    if(!$showNoDataFlag){
     $attendanceRecordArray = $adminTasksManager->getAttendanceHistory($filter.$attHistoryString,$limit,$orderBy);
     $cnt = count($attendanceRecordArray);
    }

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $cnt = count($attendanceRecordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
       $fromDate = $attendanceRecordArray[$i]['fromDate'];
       $toDate   = $attendanceRecordArray[$i]['toDate'];
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
       $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$attendanceRecordArray[$i]);
   }

	$csvData = '';
    //$csvData .= "Sr, Employee, Subject, Group, Class, Period, From , To, Att. Type, Lec. Taken \n";
    $csvData .= "Sr, Employee, Subject, Group, Class, Period, From , To, Type, Lec., Topics \n";
    foreach($valueArray as $record) {
        $csvData .= $record['srNo'].', '.parseCSVComments($record['employeeName']).', '.parseCSVComments($record['subjectCode']).', '.parseCSVComments($record['groupShort']).', '.parseCSVComments($record['className']).','.parseCSVComments($record['periodNumber']).','.$record['fromDate'].','.$record['toDate'].','.parseCSVComments($record['attendanceType']).','.parseCSVComments($record['lectureDelivered']).','.parseCSVComments($record['topic']);
        $csvData .= "\n";
    }

	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header('Content-type: application/octet-stream; charset=utf-8');
	header("Content-Length: " .strlen($csvData) );
	header('Content-Disposition: attachment;  filename="attendanceHistory.csv"');
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;

// $History: attendanceHistoryCSV.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 19/04/10   Time: 13:47
//Updated in $/LeapCC/Templates/AdminTasks
//Added "Topics" column in "Attendance History Div"
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 17/12/09   Time: 11:01
//Updated in $/LeapCC/Templates/AdminTasks
//Corrected coding in Attendance history display logic
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/12/09    Time: 11:24
//Updated in $/LeapCC/Templates/AdminTasks
//Corrected Date Formate in CSV and column headings
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 16/11/09   Time: 13:10
//Created in $/LeapCC/Templates/AdminTasks
//Attendance History Option Enhanced :
//1.Attendance can be edited and deleted from this option.
//2.Attendance history list can be printed and also can be exported to
//excel.
?>