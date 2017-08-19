<?php
//-------------------------------------------------------
// THIS FILE IS USED FOR MEDICAL LEAVE MODULE 
// Author : Aditi Miglani
// Created on : 5 Oct 2011
// Copyright 2011-2012: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
ini_set("memory_limit","250M");     
set_time_limit(0);
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','MedicalLeaveConflictReport');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

require_once(MODEL_PATH . "/MedicalLeaveManager.inc.php");
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
$medicalManager = MedicalLeaveManager::getInstance();


$inputString=trim($REQUEST_DATA['inputString']);
$classId=trim($REQUEST_DATA['classId']);
$rollNo=trim($REQUEST_DATA['rollNo']); 
if($inputString==''){
    echo "0!~!Required Parameters Missing";
    die;
}
if($classId==''){
   echo "classId!~!".SELECT_CLASS;
   die; 
}

    $inputArray=explode(',',$inputString);
    $cnt=count($inputArray);
    
    

    //find absent code id
    $absentCodeArray=$medicalManager->getAbsentAttendanceCodeId();
    $absentAttendanceCodeId=$absentCodeArray[0]['attendanceCodeId'];


    $updateMedicalLeave = array();
    $updateAttendance = array();

    for($i=0;$i<$cnt;$i++){
       $infoArray=explode('_',$inputArray[$i]);
       
       $studentId=$infoArray[0]; 
       $genClassId=$infoArray[1];
       $subjectId=$infoArray[2];
       $periodId=$infoArray[3];
       //$genEventId=$infoArray[4];
       $medicalLeaveDate=str_replace('~','-',$infoArray[5]);
       $isConflicted=$infoArray[6];
       $lecAttended=$infoArray[7];
       $lecDelivered=$infoArray[8];
       $medicalLeaveStatus=$infoArray[9];

       $selectedInputId=$studentId.'_'.$genClassId.'_'.$subjectId.'_'.$periodId.'_'.$infoArray[5].'_'.$isConflicted.'_'.$lecAttended.'_'.$lecDelivered;
      
       //first check  : this student belongs to this class and medical leave exists
       $studentArray1=$medicalManager->checkStudentMedicalLeaveExistence($studentId,$classId,$periodId,$medicalLeaveDate);
       if($studentArray1[0]['classId']!=$classId){
           echo $selectedInputId."!~!Records corresponding to this student does not exists in medical leave";
           die;
       }
      
       
       //second check : check medical leave status
       if(!array_key_exists($medicalLeaveStatus,$globalMedicalLeaveStatusArray)){
          echo $selectedInputId."!~!Invalid medical leave status value";
          die; 
       }

       //third check : check conflicted status
       if($isConflicted==''){
           echo $selectedInputId."!~!".SELECT_MEDICAL_LEAVE_STATUS;
           die;
       }

       
       if($isConflicted==0){ //no conflict
           if($medicalLeaveStatus==MEDICAL_LEAVE_MARK_ABSENT){
             echo $selectedInputId."!~!You can not mark absent when there is no conflict for this student";
             die;  
           }
       }
       else if($isConflicted==1){//conflict with bulk attendance
          if($medicalLeaveStatus==MEDICAL_LEAVE_MARK_ABSENT){
             echo $selectedInputId."!~!You can not mark absent when conflicted with bulk attendance";
             die;  
          }
          if($medicalLeaveStatus==MEDICAL_LEAVE_APPROVE){
              //check for lecture delivered and attended
              $studentAttendanceArray=$medicalManager->getStudentAttendanceRecord($studentId,$classId,$subjectId);
              $lectureDelivered=round($studentAttendanceArray[0]['delivered']);
              $lectureAttended=round($studentAttendanceArray[0]['attended']);
              if(($lectureAttended+1)>$lectureDelivered){
                  echo $selectedInputId."!~!".MEDICAL_LEAVE_RESTRICTION;
                  die; 
              }
          }
       }
       else if($isConflicted==2){ //conflict with daily attendance
         //no checking
       }
       else if($isConflicted==3){ //conflict with Duty Leave
         //no checking
       }
       else{
         echo $selectedInputId."!~!Invalid conflict value";
         die;  
      }      
     
      //now build the queries
      //if no-conflict or conflict with bulk attendance,then data will be modified in "medical_leave" table only
       if($isConflicted==0 or $isConflicted==1){
         //update medical_leave table 
         $updateMedicalLeave[]="$studentId,$classId,$periodId,$medicalLeaveDate,$medicalLeaveStatus";
       }
       else{// if conflicted with daily
          // if conflicted with daily,then based on selected medical leave status either attendance or medical_leave will be updated
          //or both will be updated
         if($medicalLeaveStatus==MEDICAL_LEAVE_MARK_ABSENT){
              if($absentAttendanceCodeId==''){
                echo $selectedInputId."!~!Attendance code for marking absent not found";
                die; 
              }
              //update attendance table
              $updateAttendance[] = "$studentId,$classId,$subjectId,$periodId,$medicalLeaveDate,$absentAttendanceCodeId";
            
             //now update medical_leave table 
             $updateMedicalLeave[]="$studentId,$classId,$periodId,$medicalLeaveDate,$medicalLeaveStatus";
       }
       else{
         //update only medical_leave table 
         $updateMedicalLeave[]="$studentId,$classId,$periodId,$medicalLeaveDate,$medicalLeaveStatus";
       } 
      }//end of for loop              
    }


    if(SystemDatabaseManager::getInstance()->startTransaction()) {  
        for($i=0;$i<count($updateMedicalLeave);$i++) {
           $recordArray = explode(',',$updateMedicalLeave[$i]);
           $ret=$medicalManager->updateMedicalLeave($recordArray[0],$recordArray[1],$recordArray[2],$recordArray[3],$recordArray[4]);
           if($ret==false){
             echo FAILURE;
             die; 
           }
        }    
        for($i=0;$i<count($updateAttendance);$i++) {
           $recordArray = explode(',',$updateAttendance[$i]);
           $ret=$medicalManager->updateAttendance($recordArray[0],$recordArray[1],$recordArray[2],$recordArray[3],$recordArray[4],$recordArray[5]);
           if($ret==false){
              echo FAILURE;
              die; 
           }
        }    
       
        /////////to check whether the number of medical leaves exceeds the maximum defined limit  (START) /////// 
        $medicalLeaveLimit=$sessionHandler->getSessionVariable('MEDICAL_LEAVE_LIMIT');
        $tableData = "";
        $conditions =' AND ml.classId='.$classId;
        if($rollNo!=''){
          $conditions.=" AND s.rollNo LIKE '".$rollNo."'";
        }
        $medicalLeaveArray=$medicalManager->countMedicalLeavePerSubject($conditions); 
        if(is_array($medicalLeaveArray) && count($medicalLeaveArray)>0 ){ 
            $tableData ="<table width='100%' border='0' cellspacing='2' cellpadding='1'>
                            <tr class='rowheading'>
                             <td width='3%' class='searchhead_text'>#</td>
                             <td width='17%' class='searchhead_text'>Roll No.</td>
                             <td width='42%' class='searchhead_text'>Subject</td>
                             <td width='19%' class='searchhead_text' align='center'>Allowed Medical Leaves</td>
                             <td width='19%' class='searchhead_text' align='center'>Approved Medical Leaves</td>
                           </tr>";
                for($i=0;$i<count($medicalLeaveArray);$i++) {
                    $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
                    $tableData .= "<tr class='$bg'>
                                     <td class='padding_top'>".($i+1)."</td>
                                     <td class='padding_top'>".$medicalLeaveArray[$i]['rollNo']."</td>
                                     <td class='padding_top'>".$medicalLeaveArray[$i]['subjectCode']."</td>
                                     <td class='padding_top' align='center'>".$medicalLeaveLimit."</td>
                                     <td class='padding_top' align='center'>".$medicalLeaveArray[$i]['cnt']."</td>    
                                    </tr>";    
                 }
             $tableData.= "</table>";
             echo "Medical Leave exceeding Maximum Limit!~!".json_encode($medicalLeaveArray)."!~!".$tableData;
             die;
        }
        /////////to check whether the number of medical leaves exceeds the maximum defined limit  (END) ///////   
        
        if(SystemDatabaseManager::getInstance()->commitTransaction()) {
            echo SUCCESS;
            die;
        }
        else {
           echo FAILURE;
           die;
        }
    }
?>
