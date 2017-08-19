<?php
//-------------------------------------------------------
// Purpose: To make time table for a teacher
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Modified by : Pushpender Kumar
// Modified on : (19.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CreateTimeTable');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();

$errorMessage ='';
    if (trim($REQUEST_DATA['timeTableLabelId'])=='' || trim($REQUEST_DATA['studentGroup']) == '' || trim($REQUEST_DATA['subject']) == '' || trim($REQUEST_DATA['teacher']) == ''  )  {
        $errorMessage = 'All the fields are mandatory. Please select one option from each drop down';
    }
// had to make this function as in_array does not check value in two dimensional array    
/*function checkPeriod($period,$periodArray) {
    $len = count($periodArray);
    $flag = false;
    for($i=0;$i<$len;$i++) {
       // echo "if(".$periodArray[$i]['periodId']."==$period)";
       if($periodArray[$i]['periodId']==$period) {
           $flag = true;
           break;
       } 
    }
    return $flag;
} */
////////////////////////////////////////////    
if (trim($errorMessage) == '') {

	require_once(MODEL_PATH . "/TimeTableManager.inc.php");
	$timetableManager  = TimeTableManager::getInstance();

	$periodRecordArray = $timetableManager->getPeriodList('AND p.periodSlotId='.$REQUEST_DATA['periodSlotId']);
	$recordCount	   = count($periodRecordArray);
	$errorMessage      = '';
    $errorCount        = 0;
    $subjectErrorMessage = false;
    $successCount      = 0;
    $insertQuery       = '';
    $updateQuery       = '';
    $existingDbIds     = array();   // if records found for a teacher
    $postedDbIds       = array();  // posted time table ids
    
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
	$sessionId	 = $sessionHandler->getSessionVariable('SessionId');
    $employeeId  = $REQUEST_DATA['teacher'];
    $groupId     = $REQUEST_DATA['studentGroup'];
    $subjectId   = $REQUEST_DATA['subject'];
    $timeTableLabelId = $REQUEST_DATA['timeTableLabelId'];
/////// The below code takes care of values if teacher is removed from taking periods ////start//
/////// get hidden fields value if not empty while modifying///////////
  /*  for($i=0; $i<$recordCount; $i++) {
        for($j=1;$j<8;$j++) {
            $timeTableFieldId = "timeTableId".$periodRecordArray[$i]['periodId'].$j;
            $timeTableDbId   = $REQUEST_DATA[$timeTableFieldId];
            
            if(UtilityManager::notEmpty($timeTableDbId)) {
                array_push($postedDbIds,$timeTableDbId);
                //echo "\nposted id==$timeTableDbId";
            }
        }
    }
    // get all records as per specified teacher, subject and group, and store all the time table ids in existingDbIds array which are not found in postedDbIds array, so that those ids could be made inactive by updating toDate against these ids
    $conditions = " AND tt.employeeId=$employeeId AND tt.subjectId=$subjectId AND tt.groupId=$groupId AND tt.timeTableLabelId=$timeTableLabelId";
    $checkTeacherRecords = $timetableManager->checkIntoTimeTable($conditions);
    //print_r ($checkTeacherRecords);908
    
    $cnt = count($checkTeacherRecords);
    if(is_array($checkTeacherRecords) && $cnt >0) {
        for($i=0;$i<$cnt;$i++) {
           if(!in_array($checkTeacherRecords[$i]['timeTableId'],$postedDbIds) && count($postedDbIds) >0 && checkPeriod($checkTeacherRecords[$i]['periodId'],$periodRecordArray) ) {
              array_push($existingDbIds,$checkTeacherRecords[$i]['timeTableId']); 
           }
        }
    }    
    // update those records if teacher is removed from taking periods
    $removed = false;
    if(count($existingDbIds)>0) {
        $ids = implode(',',$existingDbIds);
        $conditions = " AND timeTableId IN ($ids) AND subjectId=$subjectId AND groupId=$groupId AND employeeId=$employeeId";
        $timetableManager->updateTimeTable($conditions);
        $removed = true;
    }
    */
//exit;
/////////////////////////////////////////////////////////////////////////////////end ////////    

    
	for($i=0; $i<$recordCount; $i++) {

		for($j=1;$j<8;$j++) {

			$roomFieldId     = "roomPeriod".$periodRecordArray[$i]['periodId'].$j;
			$roomId			 = $REQUEST_DATA[$roomFieldId];
			$daysOfWeek		 = $j;
			$periodId		 = $periodRecordArray[$i]['periodId'];
            
            if(UtilityManager::notEmpty($roomId)) {
                //echo "<br/>$roomId-$employeeId-$groupId-$subject-$daysofweek-$periodId";
                
                $conditions = " AND tt.periodId = $periodId AND tt.roomId=$roomId AND tt.daysOfWeek=$daysOfWeek AND tt.timeTableLabelId=$timeTableLabelId";
                $arr = $timetableManager->checkIntoTimeTable($conditions);
                if($arr[0]['roomId']!='') {
                    
                    // get all the values
                    $cnt = count($arr);
                    $subjectArr = array();
                    $teacherArr = array();
                    $groupArr = array();
                    for($tt=0;$tt<$cnt;$tt++) {
                       if( !in_array($arr[$tt]['employeeId'],$teacherArr) ) {
                           array_push($teacherArr,$arr[$tt]['employeeId']);
                       }
                       if( !in_array($arr[$tt]['subjectId'],$subjectArr) ) {
                           array_push($subjectArr,$arr[$tt]['subjectId']);
                           $subjectMessage .= "The subject '".$arr[$tt]['subjectCode']."' is being taught by teacher '".$arr[$tt]['employeeName']."' in room '".$arr[$tt]['roomAbbreviation']."', period '".$arr[$tt]['periodNumber']."', group '".$arr[$tt]['groupShort']."' on day '".$daysArr[$arr[$tt]['daysOfWeek']]. "'\n";
                       }
                       if( !in_array($arr[$tt]['groupId'],$groupArr) ) {
                           array_push($groupArr,$arr[$tt]['groupId']);
                       }
                                              
                    }
                     
                    if(in_array($employeeId,$teacherArr) ) {
                        if(in_array($subjectId,$subjectArr) ) {
                          // get section/group if attending the class in room other than specified
                          $conditions = " AND tt.roomId!=$roomId AND tt.daysOfWeek=$daysOfWeek AND tt.periodId=$periodId AND tt.groupId=$groupId AND tt.timeTableLabelId=$timeTableLabelId";
                          
                           $checkGroup = $timetableManager->checkIntoTimeTable($conditions);
                          
                          if($checkGroup[0]['roomId']!='') {
                              
                                // the code below will take care if teacher,subject,group,time table label is same and just the room is different than dont show conflict, just replace the old room with the new one
                                if($checkGroup[0]['employeeId']==$employeeId && $checkGroup[0]['subjectId'] == $subjectId && $checkGroup[0]['groupId'] ==$groupId && $checkGroup[0]['timeTableLabelId'] == $timeTableLabelId) {

                                  $insertQuery = ( $insertQuery=='' ? "( '$roomId', '$employeeId', '$groupId', '$instituteId', '$sessionId', '$daysOfWeek', '$periodId', '$subjectId', CURRENT_DATE, NULL, $timeTableLabelId)" : $insertQuery.",( '$roomId', '$employeeId', '$groupId', '$instituteId', '$sessionId', '$daysOfWeek', '$periodId', '$subjectId', CURRENT_DATE, NULL, $timeTableLabelId)" );                                
                                 $successCount++;
                                }
                                else {
                                  $errorMessage .= "The group '".$checkGroup[0]['groupShort']."' is already being taught by teacher '".$checkGroup[0]['employeeName']."' in room '".$checkGroup[0]['roomAbbreviation']."', period '".$checkGroup[0]['periodNumber']."' of subject '".$checkGroup[0]['subjectCode']."' on day '".$daysArr[$checkGroup[0]['daysOfWeek']]."'\n" ;
                                  $errorCount++;
                                }
                          }
                          else {    
                                    // update  previous todate
                                        //if(in_array($groupId,$groupArr) ) {
                                            //$conditions = " AND employeeId=".$employeeId." AND subjectId=$subjectId AND groupId=$groupId AND periodId=$periodId AND daysOfWeek=$daysOfWeek AND roomId=$roomId  AND timeTableLabelId=$timeTableLabelId";
                                            //$timetableManager->updateTimeTable($conditions);
                                        //}
                                        $insertQuery = ( $insertQuery=='' ? "( '$roomId', '$employeeId', '$groupId', '$instituteId', '$sessionId', '$daysOfWeek', '$periodId', '$subjectId', CURRENT_DATE, NULL,$timeTableLabelId)" : $insertQuery.",( '$roomId', '$employeeId', '$groupId', '$instituteId', '$sessionId', '$daysOfWeek', '$periodId', '$subjectId', CURRENT_DATE, NULL, $timeTableLabelId)" );
                                        $successCount++;
                                                                                
                          }
                        }
                        else {  
                            $subjectErrorMessage = true;
                            //$errorMessage .= "$subjectMessage\n";
                            $errorCount++;
                        }
                    }
                    if(!in_array($employeeId,$teacherArr) ) {
                        // check whether same subject or different
                        if(in_array($subjectId,$subjectArr) ) {
                        
                          // get section/group if attending the class in room other than specified
                          $conditions = " AND tt.roomId!=$roomId AND tt.daysOfWeek=$daysOfWeek AND tt.periodId=$periodId AND tt.groupId=$groupId AND tt.timeTableLabelId=$timeTableLabelId";
                          
                           $checkGroup = $timetableManager->checkIntoTimeTable($conditions);
                          
                          if($checkGroup[0]['roomId']!='') {
                                // the code below will take care if teacher,subject,group,time table label is same and just the room is different than dont show conflict, just replace the old room with the new one
                                if($checkGroup[0]['employeeId']==$employeeId && $checkGroup[0]['subjectId'] == $subjectId && $checkGroup[0]['groupId'] ==$groupId && $checkGroup[0]['timeTableLabelId'] == $timeTableLabelId) {

                                  $insertQuery = ( $insertQuery=='' ? "( '$roomId', '$employeeId', '$groupId', '$instituteId', '$sessionId', '$daysOfWeek', '$periodId', '$subjectId', CURRENT_DATE, NULL, $timeTableLabelId)" : $insertQuery.",( '$roomId', '$employeeId', '$groupId', '$instituteId', '$sessionId', '$daysOfWeek', '$periodId', '$subjectId', CURRENT_DATE, NULL, $timeTableLabelId)" );                                
                                 $successCount++;
                                }
                                else {
                                  $errorMessage .= "The group '".$checkGroup[0]['groupShort']."' is already being taught by teacher '".$checkGroup[0]['employeeName']."' in room '".$checkGroup[0]['roomAbbreviation']."', period '".$checkGroup[0]['periodNumber']."' of subject '".$checkGroup[0]['subjectCode']."' on day '".$daysArr[$checkGroup[0]['daysOfWeek']]."'\n" ;
                                  $errorCount++;
                                }
                          }
                          else {
                                     $conditions = " AND tt.roomId!=$roomId AND tt.daysOfWeek=$daysOfWeek AND tt.periodId=$periodId AND tt.employeeId=$employeeId AND tt.timeTableLabelId=$timeTableLabelId";
                                      
                                      $checkTeacher = $timetableManager->checkIntoTimeTable($conditions);
                                      if($checkTeacher[0]['roomId']=='') {
                                         
                                         $insertQuery = ( $insertQuery=='' ? "( '$roomId', '$employeeId', '$groupId', '$instituteId', '$sessionId', '$daysOfWeek', '$periodId', '$subjectId', CURRENT_DATE, NULL, $timeTableLabelId)" : $insertQuery.",( '$roomId', '$employeeId', '$groupId', '$instituteId', '$sessionId', '$daysOfWeek', '$periodId', '$subjectId', CURRENT_DATE, NULL, $timeTableLabelId)" );
                                         $successCount++;
                                      }
                                      else {
                                        $errorMessage .= "The teacher '".$checkTeacher[0]['employeeName']."' is taking period '".$checkTeacher[0]['periodNumber']."' of subject '".$checkTeacher[0]['subjectCode']."' and group '".$checkTeacher[0]['groupShort']."' in room '".$checkTeacher[0]['roomAbbreviation']."' on day '".$daysArr[$checkTeacher[0]['daysOfWeek']]."'\n";                                          
                                          $errorCount++;
                                      }
                                                                      
                          }
                        }
                        else {
                            //$errorMessage .="$subjectMessage,";
                            $subjectErrorMessage = true;
                            $errorCount++;
                        }
                    }

                }
                else {   // if not exist record as per room, daysOfweek, Period

                     // get Teacher if taking the class in room other than specified
                      $conditions = " AND (tt.roomId!=$roomId) AND tt.daysOfWeek=$daysOfWeek AND tt.periodId=$periodId AND tt.employeeId=$employeeId AND tt.timeTableLabelId=$timeTableLabelId";
                      
                      $checkTeacher = $timetableManager->checkIntoTimeTable($conditions);
                      
                    
                      // get section/group if attending the class in room other than specified
                      $conditions = " AND tt.roomId!=$roomId AND tt.daysOfWeek=$daysOfWeek AND tt.periodId=$periodId AND tt.groupId=$groupId AND tt.timeTableLabelId=$timeTableLabelId";
                      
                      $checkGroup = $timetableManager->checkIntoTimeTable($conditions);
                      
                      if($checkGroup[0]['roomId']!='') {
                          
                            // the code below will take care if teacher,subject,group,time table label is same and just the room is different than dont show conflict, just replace the old room with the new one
                            if($checkGroup[0]['employeeId']==$employeeId && $checkGroup[0]['subjectId'] == $subjectId && $checkGroup[0]['groupId'] ==$groupId && $checkGroup[0]['timeTableLabelId'] == $timeTableLabelId) {

                              $insertQuery = ( $insertQuery=='' ? "( '$roomId', '$employeeId', '$groupId', '$instituteId', '$sessionId', '$daysOfWeek', '$periodId', '$subjectId', CURRENT_DATE, NULL, $timeTableLabelId)" : $insertQuery.",( '$roomId', '$employeeId', '$groupId', '$instituteId', '$sessionId', '$daysOfWeek', '$periodId', '$subjectId', CURRENT_DATE, NULL, $timeTableLabelId)" );                                
                             $successCount++;
                            }
                            else {
                              $errorMessage .= "The group '".$checkGroup[0]['groupShort']."' is already being taught by teacher '".$checkGroup[0]['employeeName']."' in room '".$checkGroup[0]['roomAbbreviation']."', period '".$checkGroup[0]['periodNumber']."' of subject '".$checkGroup[0]['subjectCode']."' on day '".$daysArr[$checkGroup[0]['daysOfWeek']]."'\n" ;
                              $errorCount++;
                            }
                      }
                      else {
                         // 
                         if($checkTeacher[0]['roomId']!='') {
                               
                             // update
                             if($roomId == $checkTeacher[0]['roomId'] && $subjectId == $checkTeacher[0]['subjectId'] ) {
                                    
                                 //$conditions = " AND employeeId=$employeeId AND subjectId=$subjectId AND groupId=$groupId AND periodId=$periodId AND daysOfWeek=$daysOfWeek AND roomId=$roomId AND timeTableLabelId=$timeTableLabelId";
                                 //$timetableManager->updateTimeTable($conditions);                 
                                 
                                 $insertQuery = ( $insertQuery=='' ? "( '$roomId', '$employeeId', '$groupId', '$instituteId', '$sessionId', '$daysOfWeek', '$periodId', '$subjectId', CURRENT_DATE, NULL, $timeTableLabelId)" : $insertQuery.",( '$roomId', '$employeeId', '$groupId', '$instituteId', '$sessionId', '$daysOfWeek', '$periodId', '$subjectId', CURRENT_DATE, NULL, $timeTableLabelId)" );
                                    $successCount++;
                                 
                             }
                             else {
                                 $errorMessage .= "The teacher '".$checkTeacher[0]['employeeName']."' is taking period '".$checkTeacher[0]['periodNumber']."' of subject '".$checkTeacher[0]['subjectCode']."' and group '".$checkTeacher[0]['groupShort']."' in room '".$checkTeacher[0]['roomAbbreviation']."' on day '".$daysArr[$checkTeacher[0]['daysOfWeek']]."'\n";
                                 $errorCount++;
                             }
                         }
                         else {
                                    $insertQuery = ( $insertQuery=='' ? "( '$roomId', '$employeeId', '$groupId', '$instituteId', '$sessionId', '$daysOfWeek', '$periodId', '$subjectId', CURRENT_DATE, NULL, $timeTableLabelId)" : $insertQuery.",( '$roomId', '$employeeId', '$groupId', '$instituteId', '$sessionId', '$daysOfWeek', '$periodId', '$subjectId', CURRENT_DATE, NULL, $timeTableLabelId)" );
                                    $successCount++;
                         }
                         //////        
                      }
 
                }
                
            }
		}
	}
    
    if($errorCount>0){
        if($subjectErrorMessage) {
            $errorMessage = "\n$subjectMessage\n$errorMessage";
        }        
        echo "Time table conflicts: $errorCount\n$errorMessage\nNo entry has been saved.";
    }
    else {
       // set toDate null against all records as per condition below
        $conditions = " AND employeeId=$employeeId AND subjectId=$subjectId AND groupId=$groupId AND timeTableLabelId=$timeTableLabelId";
        //check first whether allocation exists as per the above condition if yes, then do update, else show message "select room"
        $removedAllocation = false;
        $checkArray = $timetableManager->checkIntoTimeTable(" AND tt.employeeId=$employeeId AND tt.subjectId=$subjectId AND tt.groupId=$groupId AND tt.timeTableLabelId=$timeTableLabelId");
        //echo $checkArray[0]['timeTableLabelId'];
        //exit;
        if(count($checkArray) > 0 && is_array($checkArray) ) {
            if($timetableManager->updateTimeTable($conditions)) {
                $removedAllocation = true;
            }
        }
        
       if(UtilityManager::notEmpty($insertQuery)){
            if($timetableManager->addTimeTable($insertQuery)) {
            //echo $insertQuery;
             echo SUCCESS;
            }
            else {
              echo FAILURE;
            }
        }
        else {
            if($removedAllocation) {
                echo SUCCESS;
            }
            else {
                echo 'Please select a room';
            }
        }
    }
}
else {
	echo $errorMessage;
}
 
// for VSS
// $History: ajaxInitAdd.php $
//
//*****************  Version 8  *****************
//User: Parveen      Date: 7/15/09    Time: 3:08p
//Updated in $/LeapCC/Library/TimeTable
//role permission added
//
//*****************  Version 7  *****************
//User: Pushpender   Date: 7/08/09    Time: 7:39p
//Updated in $/LeapCC/Library/TimeTable
//Same as previous comment
//
//*****************  Version 6  *****************
//User: Pushpender   Date: 7/08/09    Time: 6:58p
//Updated in $/LeapCC/Library/TimeTable
//Added the code to allow user to edit room if teacher, subject, group
//are same, earlier, user was unable to allocate the new room unless he
//deallocates the old room.
//
//*****************  Version 5  *****************
//User: Pushpender   Date: 5/14/09    Time: 5:11p
//Updated in $/LeapCC/Library/TimeTable
//The code optimized and corrected the message from "Uknown Process" to
//"Please select a room". This message comes only when User does not
//select any room and clicks on Save button,otherwise there was no issue
//functionality wise.
//
//*****************  Version 4  *****************
//User: Pushpender   Date: 3/06/09    Time: 7:40p
//Updated in $/LeapCC/Library/TimeTable
//made changes to allow different period slot
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 3/04/09    Time: 2:27p
//Updated in $/LeapCC/Library/TimeTable
//optimized and put some extra conditions to allow multiple groups with
//same teacher, period, day, room, subject
//
//*****************  Version 2  *****************
//User: Pushpender   Date: 12/17/08   Time: 4:53p
//Updated in $/LeapCC/Library/TimeTable
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/TimeTable
//
//*****************  Version 9  *****************
//User: Pushpender   Date: 10/07/08   Time: 5:43p
//Updated in $/Leap/Source/Library/TimeTable
//Added the functionality for Time Table Labels
//
//*****************  Version 8  *****************
//User: Pushpender   Date: 9/20/08    Time: 6:36p
//Updated in $/Leap/Source/Library/TimeTable
//optimized the code
//
//*****************  Version 7  *****************
//User: Pushpender   Date: 9/20/08    Time: 5:31p
//Updated in $/Leap/Source/Library/TimeTable
//optimized alert messages
//
//*****************  Version 5  *****************
//User: Pushpender   Date: 9/19/08    Time: 8:23p
//Updated in $/Leap/Source/Library/TimeTable
//added validations to allow multiple teachers in same period, same room
//and same day, also allowed multiple groups in same room, period, day
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 9/16/08    Time: 4:55p
//Updated in $/Leap/Source/Library/TimeTable
//updated files according to subject centric
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 9/02/08    Time: 8:05p
//Updated in $/Leap/Source/Library/TimeTable
//updated file with bug fixes
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 8/13/08    Time: 2:40p
//Updated in $/Leap/Source/Library/TimeTable
//updated time table validations and error messages
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 8/01/08    Time: 4:23p
//Created in $/Leap/Source/Library/TimeTable
//intial checkin
?>