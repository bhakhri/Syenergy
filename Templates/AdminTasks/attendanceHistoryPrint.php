<?php
//This file is used as printing version for TestType.
//
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/AdminTasksManager.inc.php");
    $adminTasksManager = AdminTasksManager::getInstance();
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    /////////////////////////

    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    //$limit      = 'LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////

    /////search functionility not needed

    $fromDate=date('Y-m-d');
    $toDate=date('Y-m-d');

    $employeeId=trim($REQUEST_DATA['employeeId']);
    $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);

    if($employeeId=='' or $timeTableLabelId==''){
        echo 'Required Parameters Missing';
        die;
    }

    $callingModule=trim($REQUEST_DATA['attType']);
    $className=trim($REQUEST_DATA['className']);
    $subjectName=trim($REQUEST_DATA['subjectName']);
    $groupName=trim($REQUEST_DATA['groupName']);

    $search =' Time Table : '.trim($REQUEST_DATA['timeTableLabelName']).' Teacher : '.trim($REQUEST_DATA['employeeName']).'<br/>';

    if($className==''){
        $className = NOT_APPLICABLE_STRING;
    }
    if($subjectName==''){
        $subjectName = NOT_APPLICABLE_STRING;
    }
    if($groupName==''){
        $groupName = NOT_APPLICABLE_STRING;
    }
    $search .=" Class : ".$className." Subject : ".$subjectName." Group : ".$groupName;

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

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Attendance History Report');
    $reportManager->setReportInformation("Search By: $search");

	$reportTableHead                     =   array();
    $reportTableHead['srNo']             =   array('#','width="2%"', "align='left' ");
    $reportTableHead['employeeName']     =   array('Employee','width=10% align="left"', 'align="left"');
    $reportTableHead['subjectCode']      =   array('Subject','width=6% align="left"', 'align="left"');
    $reportTableHead['groupShort']         =   array('Group','width=5% align="left"', 'align="left"');
    $reportTableHead['className']         =   array('Class','width="12%" align="left" ', 'align="left"');
    $reportTableHead['periodNumber']     =   array('Period','width="5%" align="left" ', 'align="left"');
    $reportTableHead['fromDate']         =   array('From','width="5%" align="center" ', 'align="center"');
    $reportTableHead['toDate']           =   array('To','width="5%" align="center" ', 'align="center"');
    //$reportTableHead['attendanceType']   =   array('Att. Type','width="6%" align="left" ', 'align="left"');
    $reportTableHead['attendanceType']   =   array('Type','width="4%" align="left" ', 'align="left"');
    //$reportTableHead['lectureDelivered'] =   array('Lec. Taken','width="8%" align="right" ', 'align="right"');
    $reportTableHead['topic']            =   array('Topics','width="12%" align="left" ', 'align="left"');
    $reportTableHead['lectureDelivered'] =   array('Lec.','width="4%" align="right" ', 'align="right"');

	$reportManager->setRecordsPerPage(30);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: attendanceHistoryPrint.php $
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