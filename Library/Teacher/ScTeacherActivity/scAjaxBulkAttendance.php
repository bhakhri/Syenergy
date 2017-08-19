<?php
//--------------------------------------------------------------------------------------------------------------------
// Purpose: To store the records of student attendance in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (16.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
    
    $memc =split("," , $REQUEST_DATA['memofclass']); 
    $ldel =split("," , $REQUEST_DATA['delivered']);
    $latt =split("," , $REQUEST_DATA['attended']);
    
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
               
              $insQuery="($classIds[$i],$REQUEST_DATA[sectionId],$studentIds[$i],$REQUEST_DATA[subjectId],$empId,1,NULL,NULL,'".$REQUEST_DATA['fromDate']."','".$REQUEST_DATA['toDate']."',$memc[$i],$ldel[$i],$latt[$i],$userId)";
           }
          else{
         
              $insQuery=$insQuery." , ($classIds[$i],$REQUEST_DATA[sectionId],$studentIds[$i],$REQUEST_DATA[subjectId],$empId,1,NULL,NULL,'".$REQUEST_DATA['fromDate']."','".$REQUEST_DATA['toDate']."',$memc[$i],$ldel[$i],$latt[$i],$userId)";
          }
             
        }
       else{
          //these values are already in attendance_bulk table.so update them [update funtion will be called multiple times]
           $returnStatus=$teacherManager->editBulkAttendance($attendanceIds[$i],$ldel[$i],$latt[$i],$memc[$i],$REQUEST_DATA['fromDate'],$REQUEST_DATA['toDate']);
           if($returnStatus === false) {  //tracking error in update query
                $errorMessage = FAILURE;
            }
       }     
        
    }
    
     //insert query will be execute one time but multiple insertions will be done
     if($insQuery!=""){  //check if insertion query should be performed or not
      $returnStatus=$teacherManager->addBulkAttendance($insQuery) ;
      if($returnStatus === false) {  //tracking error in insert query  
                $errorMessage = FAILURE;
       }
     }  
     
  }
//ended insert and update operations   
  
 echo   $errorMessage; 
       
// for VSS
// $History: scAjaxBulkAttendance.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScTeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/10/08    Time: 6:36p
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/09/08    Time: 5:18p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/02/08    Time: 12:37p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//Added Null for periodId and AttendanceCodeId for bulk attendance
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/24/08    Time: 7:54p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//Make modifications for having  daily and bulk attendance in a single
//table
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/18/08    Time: 1:16p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
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
