<?php
//-------------------------------------------------------
// THIS FILE IS USED FOR DUTY LEAVE MODULE 
// Author : Dipanjan Bhattacharjee
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
ini_set("memory_limit","250M");     
set_time_limit(0);
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DutyLeaveConflictReport');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/DutyLeaveManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
$dutyManager = DutyLeaveManager::getInstance();

 
    function clearSpecialChar($text) {
       if($text!="") {
         $text=strtolower($text);
         $code_entities_match = array(' ',"'",'"','%','\t','    ');
         $code_entities_replace = array('','','','','','');
         $text = str_replace($code_entities_match, $code_entities_replace, $text);
       }
       return $text;
    }

    $inputString=trim($REQUEST_DATA['inputString']);
    $classId=trim($REQUEST_DATA['classId']);
    $eventId=trim($REQUEST_DATA['eventId']);
    $rollNos =  htmlentities(add_slashes(clearSpecialChar(trim($REQUEST_DATA['rollNo']))));
    if($inputString==''){
        echo "0!~!Required Parameters Missing";
        die;
    }
    if($classId==''){
       echo "classId!~!".SELECT_CLASS;
       die; 
    }
    if($eventId==''){
       echo "eventId!~!Invalid Event";
       die; 
    }
    if($eventId=='-1'){
       echo "eventId!~!".SELECT_DUTY_LEAVE_EVENT;
       die; 
    }

    if($eventId==-2) {
      $eventId='';
    }


    $rollNo='';
    if($rollNos!='') {
       $rollNoArray = explode(',',$rollNos);  
       for($i=0;$i<count($rollNoArray);$i++) {
          if(trim($rollNoArray[$i])!='') {
             if($rollNo=='') {
               $rollNo = "'".$rollNoArray[$i]."'";  
             } 
             else {
               $rollNo .= ",'".$rollNoArray[$i]."'";    
             }
          }  
       }
    }
    
    

    $inputArray=explode(',',$inputString);
    $cnt=count($inputArray);
    
    

    //find absent code id
    $absentCodeArray=$dutyManager->getAbsentAttendanceCodeId();
    $absentAttendanceCodeId=$absentCodeArray[0]['attendanceCodeId'];


    $updateDutyLeave = array();
    $updateAttendance = array();

    for($i=0;$i<$cnt;$i++){
       $infoArray=explode('_',$inputArray[$i]);
       
       $studentId=$infoArray[0]; 
       $genClassId=$infoArray[1];
       $subjectId=$infoArray[2];
       $periodId=$infoArray[3];
       $genEventId=$infoArray[4];
       $dutyDate=str_replace('~','-',$infoArray[5]);
       $isConflicted=$infoArray[6];
       $lecAttended=$infoArray[7];
       $lecDelivered=$infoArray[8];
       $dutyLeaveStatus=$infoArray[9];

       $selectedInputId=$studentId.'_'.$genClassId.'_'.$subjectId.'_'.$periodId.'_'.$genEventId.'_'.$infoArray[5].'_'.$isConflicted.'_'.$lecAttended.'_'.$lecDelivered;
       
      
       //first check  : this student belongs to this class and duty leave exists
       $studentArray1=$dutyManager->checkStudentDutyLeaveExistence($studentId,$classId,$periodId,$dutyDate,$genEventId);
       if($studentArray1[0]['classId']!=$classId){
           echo $selectedInputId."!~!Records corresponding to this student does not exists in duty leave";
           die;
       }
      
       
       //second check : check duty leave status
       if(!array_key_exists($dutyLeaveStatus,$globalDutyLeaveStatusArray)){
          echo $selectedInputId."!~!Invalid duty leave status value";
          die; 
       }

       //third check : check conflicted status
       if($isConflicted==''){
           echo $selectedInputId."!~!".SELECT_DUTY_LEAVE_STATUS;
           die;
       }

       
       if($isConflicted==0){ //no conflict
           if($dutyLeaveStatus==DUTY_LEAVE_MARK_ABSENT){
             echo $selectedInputId."!~!You can not mark absent when there is no conflict for this student";
             die;  
           }
       }
       else if($isConflicted==1){//conflict with bulk attendance
          if($dutyLeaveStatus==DUTY_LEAVE_MARK_ABSENT){
             echo $selectedInputId."!~!You can not mark absent when conflicted with bulk attendance";
             die;  
          }
          if($dutyLeaveStatus==DUTY_LEAVE_APPROVE){
              //check for lecture delivered and attended
              $studentAttendanceArray=$dutyManager->getStudentAttendanceRecord($studentId,$classId,$subjectId);
              $lectureDelivered=round($studentAttendanceArray[0]['delivered']);
              $lectureAttended=round($studentAttendanceArray[0]['attended']);
              if(($lectureAttended+1)>$lectureDelivered){
                  echo $selectedInputId."!~!".DUTY_LEAVE_RESTRICTION;
                  die; 
              }
          }
       }
       else if($isConflicted==2){ //conflict with daily attendance
         //no checking
       }
       else if($isConflicted==3){ //conflict with Medical Leave
         //no checking
       }
       else{
         echo $selectedInputId."!~!Invalid conflict value";
         die;  
      }      
     
      //now build the queries
      //if no-conflict or conflict with bulk attendance,then data will be modified in "duty_leave" table only
       if($isConflicted==0 or $isConflicted==1){
         //update duty_leave table 
         $updateDutyLeave[]="$studentId,$classId,$periodId,$genEventId,$dutyDate,$dutyLeaveStatus";
       }
       else{// if conflicted with daily
          // if conflicted with daily,then based on selected duty leave status either attendance or duty_leave will be updated
          //or both will be updated
         if($dutyLeaveStatus==DUTY_LEAVE_MARK_ABSENT){
              if($absentAttendanceCodeId==''){
                echo $selectedInputId."!~!Attendance code for marking absent not found";
                die; 
              }
              //update attendance table
              $updateAttendance[] = "$studentId,$classId,$subjectId,$periodId,$dutyDate,$absentAttendanceCodeId";
            
             //now update duty_leave table 
             $updateDutyLeave[]="$studentId,$classId,$periodId,$genEventId,$dutyDate,$dutyLeaveStatus";
       }
       else{
         //update only duty_leave table 
         $updateDutyLeave[]="$studentId,$classId,$periodId,$eventId,$dutyDate,$dutyLeaveStatus";
       } 
      }//end of for loop              
    }


    if(SystemDatabaseManager::getInstance()->startTransaction()) {  
        for($i=0;$i<count($updateDutyLeave);$i++) {
           $recordArray = explode(',',$updateDutyLeave[$i]);
           $ret=$dutyManager->updateDutyLeave($recordArray[0],$recordArray[1],$recordArray[2],$recordArray[3],$recordArray[4],$recordArray[5]);
           if($ret==false){
             echo FAILURE;
             die; 
           }
        }
        if(SystemDatabaseManager::getInstance()->commitTransaction()) {
        }
    }
        
    if(SystemDatabaseManager::getInstance()->startTransaction()) {      
        for($i=0;$i<count($updateAttendance);$i++) {
           $recordArray = explode(',',$updateAttendance[$i]);
           $ret=$dutyManager->updateAttendance($recordArray[0],$recordArray[1],$recordArray[2],$recordArray[3],$recordArray[4],$recordArray[5]);
           if($ret==false){
              echo FAILURE;
              die; 
           }
        }    
        if(SystemDatabaseManager::getInstance()->commitTransaction()) {
        }
    }
    
    /////////to check whether the number of duty leaves exceeds the maximum defined limit  (START) /////// 
    $dutyLeaveLimit=$sessionHandler->getSessionVariable('DUTY_LEAVE_LIMIT');
    $tableData = "";
    $conditions  = "AND dl.rejected='".DUTY_LEAVE_APPROVE."' AND dl.classId=".$classId;
    $conditions .= " AND dl.subjectId IN ($subjectId) "; 
    if($rollNo!=''){
      $conditions.=" AND s.rollNo IN (".$rollNo.")";
    }
    $havingCondition='HAVING cnt > '.$dutyLeaveLimit;       
    
    $dutyLeaveArray=$dutyManager->countDutyLeavePerSubject($conditions,$havingCondition); 
    if(is_array($dutyLeaveArray) && count($dutyLeaveArray)>0 ){ 
        $tableData ="<table width='100%' border='0' cellspacing='2' cellpadding='1'>
                        <tr class='rowheading'>
                         <td width='3%' class='searchhead_text'>#</td>
                         <td width='17%' class='searchhead_text'>Roll No.</td>
                         <td width='42%' class='searchhead_text'>Subject</td>
                         <td width='19%' class='searchhead_text' align='center'>Allowed Duty Leaves</td>
                         <td width='19%' class='searchhead_text' align='center'>Approved Duty Leaves</td>
                       </tr>";
            for($i=0;$i<count($dutyLeaveArray);$i++) {
                $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
                $tableData .= "<tr class='$bg'>
                                 <td class='padding_top'>".($i+1)."</td>
                                 <td class='padding_top'>".$dutyLeaveArray[$i]['rollNo']."</td>
                                 <td class='padding_top'>".$dutyLeaveArray[$i]['subjectCode']."</td>
                                 <td class='padding_top' align='center'>".$dutyLeaveLimit."</td>
                                 <td class='padding_top' align='center'>".$dutyLeaveArray[$i]['cnt']."</td>    
                                </tr>";    
             }
         $tableData.= "</table>";
         echo "Duty Leave exceeding Maximum Limit!~!".json_encode($dutyLeaveArray)."!~!".$tableData;
         die;
    }
    /////////to check whether the number of duty leaves exceeds the maximum defined limit  (END) ///////   
    echo SUCCESS;
?>
