<?php
//-------------------------------------------------------
// THIS FILE IS USED TO EDIT A UNIVERSITY
// Author : Dipanjan Bhattacharjee
// Created on : (14.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PlacementDriveMaster');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

   $driveId=trim($REQUEST_DATA['placementDriveId']);
   
   if($driveId==''){
       die(PLACEMENT_DRIVE_NOT_EXIST);
   }
   
   //check for usage
   require_once(MODEL_PATH . "/Placement/DriveManager.inc.php");
   $foundArr=DriveManager::getInstance()->checkInPlacementResult($driveId);
   if($foundArr[0]['found']!=0){
       die(DEPENDENCY_CONSTRAINT_EDIT);
   }

    $errorMessage ='';
    if ($errorMessage == '' && (!isset($REQUEST_DATA['driveCode']) || trim($REQUEST_DATA['driveCode']) == '')) {
        $errorMessage .= ENTER_PLACEMENT_DRIVE_CODE."\n";
    }
    if (!isset($REQUEST_DATA['companyId']) || trim($REQUEST_DATA['companyId']) == '') {
        $errorMessage .= SELECT_PLACEMENT_COMPANY_NAME."\n";     
    }
    
    if (!isset($REQUEST_DATA['venue']) || trim($REQUEST_DATA['venue']) == '') {
        $errorMessage .= ENTER_PLACEMENT_DRIVE_VENUE."\n";     
    }
    
    
    $startAmPm = $REQUEST_DATA['startAmPm'];
    $endAmPm = $REQUEST_DATA['endAmPm'];
    $startTime = $REQUEST_DATA['startTime'];
    $endTime = $REQUEST_DATA['endTime'];
     
    //time checking happens when dates are same
    if(trim($REQUEST_DATA['startDate'])==trim($REQUEST_DATA['endDate'])){
      $startHours = intval(substr($startTime,0,2));
      $endHours = intval(substr($endTime,0,2));
      if($startHours > 12 or $startHours < 0) {
        echo 'Hours cannot greater than 12 or less than 0';
        die;
      }
      if($endHours > 12 or $endHours < 0) {
        echo 'Hours cannot greater than 12 or less than 0';
        die;
      }
    
      $startMins = intval(substr($startTime,3));
      $endMins = intval(substr($endTime,3));
    
      if($startAmPm == $endAmPm  and $startAmPm == 'AM') {
        if($startHours > $endHours) {
            echo 'Start Time should not be greater than End Time';
            die;
        }
        elseif($startHours == $endHours and $startMins > $endMins) {
            echo 'Start Time should not be greater than End Time';
            die;
        }
     }
     elseif ($startAmPm == 'AM'  and $endAmPm == 'PM') {
        //everything ok
     }
     elseif ($startAmPm == 'PM' and $endAmPm == 'AM') {
        echo 'Start Time should not be greater than End Time';
        die;
     }
     elseif ($startAmPm == 'PM'  and $endAmPm == 'PM') {
        if($startHours == 12) {
            if($endHours == 12) {
                if($startMins > $endMins) {
                    echo 'Start Time should not be greater than End Time';
                    die;
                }
            }
            else {
                //ok
            }
        }
        else {
            if($startHours > $endHours) {
                echo 'Start Time should not be greater than End Time';
                die;
            }
            elseif($startHours == $endHours and $startMins > $endMins) {
                echo 'Start Time should not be greater than End Time';
                die;
            }
        }
     }
     
     //***********time based check*********************
     list($startHour, $startMin, $startSec) = explode(':', trim($startTime));
     $periodStartAMPM = $startAmPm;
     if ($startHour != 12 and $periodStartAMPM == 'PM') {
        $startHour += 12;
     }
     else if ($startHour == 12 and $periodStartAMPM == 'AM') {
        $startHour = 0;
     }
     list($endHour, $endMin, $endSec) = explode(':', trim($endTime));
     $periodEndAMPM = $endAmPm;
     if ($endHour != 12 and $periodEndAMPM == 'PM') {
        $endHour += 12;
     }
     else if ($endHour == 12 and $periodEndAMPM == 'AM') {
        $endHour = 0;
     }
     
     $oStartDateTime = mktime($startHour, $startMin, $startSec, $newMonth, $newDay, $newYear);
     $oEndDateTime = mktime($endHour, $endMin, $endSec, $newMonth, $newDay, $newYear);
     if ($oStartDateTime > $oEndDateTime) {
        echo 'Start Time can not be greater than End Time';
        die;
     }
     //***********time based check*********************
     
    }
    
 if(trim($REQUEST_DATA['eligibilityCriteria'])==1){
        if(trim($REQUEST_DATA['cutOff1'])=='' or trim($REQUEST_DATA['cutOff2'])==''){
            die(ENTER_CUT_OFF_MARKS);
        }
        
        if( (!is_numeric(trim($REQUEST_DATA['cutOff1'])) or trim($REQUEST_DATA['cutOff1']) < 0) or (!is_numeric(trim($REQUEST_DATA['cutOff2'])) or trim($REQUEST_DATA['cutOff2']) < 0)) {
            die(ENTER_DECIMAL_VALUE);
        }
         
        if(trim($REQUEST_DATA['cutOff3'])!='' and trim($REQUEST_DATA['cutOff4'])!=''){
            die(ENTER_CUT_OFF_MARKS_IN_EITHER_ONE);
        }
        
        if(trim($REQUEST_DATA['cutOff3'])=='' and trim($REQUEST_DATA['cutOff4'])==''){
            die(ENTER_CUT_OFF_MARKS_IN_EITHER_ONE);
        }
        if(trim($REQUEST_DATA['cutOff3'])!=''){ 
         if(!is_numeric(trim($REQUEST_DATA['cutOff3'])) or trim($REQUEST_DATA['cutOff3']) < 0) {
            die(ENTER_DECIMAL_VALUE);
         }
        }
        if(trim($REQUEST_DATA['cutOff4'])!=''){
         if(!is_numeric(trim($REQUEST_DATA['cutOff4'])) or trim($REQUEST_DATA['cutOff3']) < 0) {
            die(ENTER_DECIMAL_VALUE);
         }
        }
        
    }
    
    if(trim($REQUEST_DATA['isTest'])==1){
        $subjectString=trim($REQUEST_DATA['subjectString']);
        if($subjectString==''){
            die(PLEASE_ENTER_ATLEAST_ONE_TEST_SUBJECT_DURATION);
        }
        $subjectStringArray=explode('!@!@!',$subjectString);
        $cnt=count($subjectStringArray);
        for($i=0;$i<$cnt;$i++){
            $subjectArray=explode('_!_@_!_@_!_',$subjectStringArray[$i]);
            //if(!is_numeric($subjectArray[0])) {
			//	die(ENTER_NUMERIC_VALUE_DURATION);
			//}
			if(trim($subjectArray[0])==''){
                die(ENTER_TEST_DURATION);
            }
            
            if(trim($subjectArray[1])==''){
                die(ENTER_TEST_SUBJECT);
            }
        }
    }
    
    if(trim($REQUEST_DATA['individualInterview'])==1){
        if(trim($REQUEST_DATA['interviewDuration'])==''){
            die(ENTER_INTERVIEW_DURATION);
        }
    }
    
    if(trim($REQUEST_DATA['groupDiscussion'])==1){
        if(trim($REQUEST_DATA['discussionDuration'])==''){
            die(ENTER_DISCUSSION_DURATION);
        }
    }
    
    //check for time clash with other placement drives
    require_once(MODEL_PATH . "/Placement/DriveManager.inc.php");
    $startDate=trim($REQUEST_DATA['startDate']);
    $endDate=trim($REQUEST_DATA['endDate']);
    $conditions=" WHERE
                       (
                        (startDate BETWEEN '$startDate' AND '$endDate') 
                        OR
                        (endDate BETWEEN '$startDate' AND '$endDate') 
                        OR
                        (startDate <= '$startDate' AND endDate>= '$endDate')
                       )
                       AND placementDriveId!=$driveId 
                ";
    $foundArray=DriveManager::getInstance()->getPlacementDrives($conditions);
    
    if(strtoupper(trim($foundArray[0]['placementDriveCode']))==strtoupper(trim($REQUEST_DATA['driveCode']))){
        die(DUPLICATE_PLACEMENT_DRIVE_CODE);
    }
    if(is_array($foundArray) and count($foundArray)>0){
        list($startHour, $startMin, $startSec) = explode(':', trim($startTime));
        $periodStartAMPM = $startAmPm;
        if ($startHour != 12 and $periodStartAMPM == 'PM') {
         $startHour += 12;
        }
        else if ($startHour == 12 and $periodStartAMPM == 'AM') {
         $startHour = 0;
        }
        list($endHour, $endMin, $endSec) = explode(':', trim($endTime));
        $periodEndAMPM = $endAmPm;
        if ($endHour != 12 and $periodEndAMPM == 'PM') {
         $endHour += 12;
        }
        else if ($endHour == 12 and $periodEndAMPM == 'AM') {
         $endHour = 0;
        }
        //calculate timestamp of values sent from client side
        $dateArr1=explode('_',$startDate);
        $startDateTimeCL = mktime($startHour, $startMin, 0, $dateArr1[1], $dateArr1[2], $dateArr1[0]);
        
        $dateArr2=explode('_',$endDate);
        $endDateTimeCL = mktime($endHour, $endMin, 0, $dateArr1[1], $dateArr1[2], $dateArr1[0]);
        
        //echo $startHour.'***'.$endHour.'--';
        
        foreach($foundArray as $found){
            list($startHour, $startMin, $startSec) = explode(':', $found['startTime']);
            $periodStartAMPM = $found['startTimeAmPm'];
            if ($startHour != 12 and $periodStartAMPM == 'PM') {
             $startHour += 12;
            }
            else if ($startHour == 12 and $periodStartAMPM == 'AM') {
             $startHour = 0;
            }
            $dateArr1=explode('_',$found['startDate']);
            $startDateTimeDB = mktime($startHour, $startMin, 0, $dateArr1[1], $dateArr1[2], $dateArr1[0]);
            
            list($endHour, $endMin, $endSec) = explode(':', trim($endTime));
            $periodEndAMPM = $found['endTimeAmPm'];
            if ($endHour != 12 and $periodEndAMPM == 'PM') {
             $endHour += 12;
            }
            else if ($endHour == 12 and $periodEndAMPM == 'AM') {
             $endHour = 0;
            }
            $dateArr2=explode('_',$found['endDate']);
            $endDateTimeDB = mktime($endHour, $endMin, 0, $dateArr2[1], $dateArr2[2], $dateArr2[0]);
           
            if ($startDateTimeDB == $startDateTimeCL) {
                $errorFound = true;
            }
            elseif ($startDateTimeDB > $startDateTimeCL and $startDateTimeDB < $endDateTimeCL) {
                $errorFound = true;
            }
            elseif ($startDateTimeDB < $startDateTimeCL and $endDateTimeDB > $startDateTimeCL) {
                $errorFound = true;
            }
            if ($errorFound == true) {
                echo PLACEMENT_DRIVE_TIME_CLASH;
                die;
            }
        }
    }
    
    if(trim($errorMessage) == '') {
      if(SystemDatabaseManager::getInstance()->startTransaction()) {
          $returnStatus = DriveManager::getInstance()->editPlacementDrive($driveId);
          if($returnStatus == false) {
           die(FAILURE);
          }
          $placementDriveId=$driveId;
      
          //delete placement test subjects
          $returnStatus = DriveManager::getInstance()->deletePlacementDriveTests($placementDriveId);
          if($returnStatus == false) {
            die(FAILURE);
          }
          //add placement drive tests
          if(trim($REQUEST_DATA['isTest'])==1){
            $subjectString=trim($REQUEST_DATA['subjectString']);
            $subjectStringArray=explode('!@!@!',$subjectString);
            $cnt=count($subjectStringArray);
            $insertString='';
            for($i=0;$i<$cnt;$i++){
                $subjectArray=explode('_!_@_!_@_!_',$subjectStringArray[$i]);
                if($insertString!=''){
                    $insertString .=',';
                }
                $insertString .=" ( $placementDriveId,'".add_slashes(trim($subjectArray[0]))."','".add_slashes(trim($subjectArray[1]))."' ) ";
            }
            if($insertString!=''){
               $returnStatus = DriveManager::getInstance()->addPlacementDriveTests($insertString);
               if($returnStatus == false) {
                die(FAILURE);
               } 
            }
         }
         
         
         //delete placement criteria
          $returnStatus = DriveManager::getInstance()->deletePlacementDriveCriteria($placementDriveId);
          if($returnStatus == false) {
            die(FAILURE);
          }
          
         //add placement drive criteria
          if(trim($REQUEST_DATA['eligibilityCriteria'])==1){
            $returnStatus = DriveManager::getInstance()->addPlacementDriveCriteria($placementDriveId,add_slashes(trim($REQUEST_DATA['cutOff1'])),add_slashes(trim($REQUEST_DATA['cutOff2'])),add_slashes(trim($REQUEST_DATA['cutOff3'])),add_slashes(trim($REQUEST_DATA['cutOff4'])));
            if($returnStatus == false) {
             die(FAILURE);
            }
         }
     
          if(SystemDatabaseManager::getInstance()->commitTransaction()) {
             die(SUCCESS);
          }
       else {
            die(FAILURE);
        }
      }
      else {
         die(FAILURE);
      } 
    }
    else {
        echo $errorMessage;
    }
// $History: ajaxInitEdit.php $
?>