<?php
//--------------------------------------------------------------------------------------------------------------------
// Purpose: To store the records of student attendance in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (16.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    UtilityManager::ifTeacherNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
    $teacherManager = ScTeacherManager::getInstance();
    
    $attendanceIds =split("," , $REQUEST_DATA['attendanceIds']);
    $studentIds =split("," , $REQUEST_DATA['studentIds']);
    $classIds =split("," , $REQUEST_DATA['classIds']);
    $attendanceCodeIds =split("," , $REQUEST_DATA['attendanceCodeIds']);
    $memc =split("," , $REQUEST_DATA['memofclass']); 
    
    
    $attCount=count($attendanceIds);  //count attendanceIds
        
    $insQuery="";
    $empId=$sessionHandler->getSessionVariable('EmployeeId');
    $userId=$sessionHandler->getSessionVariable('UserId');

    $errorMessage=SUCCESS;
  
  //started insert and update operations
  if($attCount > 0 && is_array($attendanceIds)){
    
    for($i=0; $i <$attCount; $i++ ){
        if($attendanceIds[$i]=="-1"){ 
         //these values are not in attendance_bulk table.so insert them
          //create multiple insert query
           if($insQuery==""){
              $insQuery="($classIds[$i],$REQUEST_DATA[sectionId],$studentIds[$i],$REQUEST_DATA[subjectId],$empId,2,$attendanceCodeIds[$i],$REQUEST_DATA[periodId],'".$REQUEST_DATA['forDate']."','".$REQUEST_DATA['forDate']."',$memc[$i],1,0,$userId)";
           }
          else{
              $insQuery=$insQuery." , ($classIds[$i],$REQUEST_DATA[sectionId],$studentIds[$i],$REQUEST_DATA[subjectId],$empId,2,$attendanceCodeIds[$i],$REQUEST_DATA[periodId],'".$REQUEST_DATA['forDate']."','".$REQUEST_DATA['forDate']."',$memc[$i],1,0,$userId)";
          }
        }
       else{
          //these values are already in attendance_bulk table.so update them [update funtion will be called multiple times]
           $returnStatus=$teacherManager->editDailyAttendance($attendanceIds[$i],$memc[$i],$attendanceCodeIds[$i]);
           if($returnStatus === false) {  //tracking error in update query
                $errorMessage = FAILURE;
            }
       }     
        
    }

    
     //insert query will be execute one time but multiple insertions will be done
     if($insQuery!=""){  //check if insertion query should be performed or not
      $returnStatus=$teacherManager->addDailyAttendance($insQuery) ;
      if($returnStatus === false) {  //tracking error in insert query  
                $errorMessage = FAILURE;
       }
     }  
     
  }
//ended insert and update operations   
  
 echo   $errorMessage; 
       
// for VSS
// $History: scAjaxDailyAttendance.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScTeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
?>
