<?php
//-----------------------------------------------------------------------------------------------------------------------
// Purpose: To store the records of student daily attendance in array from the database, pagination and search, delete 
// functionality
// Author : Dipanjan Bbhattacharjee
// Created on : (18.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------------------------------------
    set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','CopyAttendance');
    define('ACCESS','view');
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==2){
     UtilityManager::ifTeacherNotLoggedIn(true);
    }
    else{
    UtilityManager::ifNotLoggedIn(true);  
    }
    UtilityManager::headerNoCache();

    if(trim($REQUEST_DATA['group'])==''){
		die('Required Parameters Missing');
	}
    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    require_once(MODEL_PATH."/CommonQueryManager.inc.php");
    
    //attendanceCode from the table "attendance_code" table
    $attCode = CommonQueryManager::getInstance()->getAttendanceCode();
    $cntAttCode=count($attCode);
    
    //as this will be same for all the studentIds that are not in attendance_daily it is kept outside loop
    $optStr2="";
    for($k=0 ; $k < $cntAttCode ; $k++){
      if($optStr2 == ""){
          if($attCode[$k]['attendanceCodeId']==1){ //hardcoded  for "PRE"
            $optStr2 = '<option value="'.$attCode[$k]['attendanceCodeId'].'" selected="selected" >'.$attCode[$k]['attendanceCode'].'</option>';
          }
         else{
            $optStr2 = '<option value="'.$attCode[$k]['attendanceCodeId'].'" >'.$attCode[$k]['attendanceCode'].'</option>'; 
         }  
      }
     else{
         if($attCode[$k]['attendanceCodeId']==1){ //hardcoded  for "PRE"   
          $optStr2 .= '<option value="'.$attCode[$k]['attendanceCodeId'].'"  selected="selected"  >'.$attCode[$k]['attendanceCode'].'</option>';                     
         }
        else{
          $optStr2 .= '<option value="'.$attCode[$k]['attendanceCodeId'].'" >'.$attCode[$k]['attendanceCode'].'</option>';                        
        }  
     } 
    }
   //$optStr2 .='<option value="" >SELECT ATTD. CODE</option>';
    
    
    $teacherManager = TeacherManager::getInstance();

    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE_TEACHER;
    $filter="";

    if(trim($REQUEST_DATA['subject'])!=""){
        $filter =$filter." AND sc.subjectId=".trim($REQUEST_DATA['subject']); 
    }
    if(trim($REQUEST_DATA['group'])!=""){
        $filter =$filter." AND g.groupId=".trim($REQUEST_DATA['group']); 
    }
    if(trim($REQUEST_DATA['class'])!=""){
        $filter =$filter." AND c.classId=".trim($REQUEST_DATA['class']); 
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    //$sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'isLeet,universityRollNo';
	//$sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'isLeet,universityRollNo';
	$sortField = $REQUEST_DATA['sortField'];

	if($sortField=="studentName"){
        $sortField2="studentName";
    }
    elseif($sortField=="rollNo"){
        $sortField2="LENGTH(rollNo)+0,rollNo";
    }
    elseif($sortField=="universityRollNo"){
        $sortField2=" IF(universityRollNo='' OR universityRollNo IS NULL,IF(rollNo='' OR rollNo IS NULL,studentName,rollNo),universityRollNo)";
    }
	else {
		$sortField2=" IF(universityRollNo='' OR universityRollNo IS NULL,IF(rollNo='' OR rollNo IS NULL,studentName,rollNo),universityRollNo)";
	}
    
    $orderBy = " $sortField2 $sortOrderBy";         
    
    $totalArray            = $teacherManager->getSearchTotalStudent($filter);
    $studentRecordArray    = $teacherManager->getSearchStudentList($filter,$limit,$orderBy);  
    $attendanceRecordArray = $teacherManager->getDailyAttendanceList();
    
    if(count($attendanceRecordArray)==0){
      echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"0","page":"'.$page.'","info" : ['.$json_val.'],"topicsId" : '.json_encode($topicsTaughtId).',"topicsComments" : '.json_encode($topicsComments).',"topicSubjectId" : '.json_encode($subjectTaughtId).',"topicTaughtId" : '.json_encode($topicTaughtId).',"attendanceCodeInfo" : '.json_encode($attCode).'}'; 
      die;  
    }
    
    //*********used to fetch student's attendance till <= "From Date"****************
    if(trim($REQUEST_DATA['subject'])!=""){
        $searchStr =$searchStr." AND su.subjectId=".trim($REQUEST_DATA['subject']); 
    }
    if(trim($REQUEST_DATA['group'])!=""){
        $searchStr =$searchStr." AND att.groupId=".trim($REQUEST_DATA['group']); 
    }
    if(trim($REQUEST_DATA['class'])!=""){
        $searchStr =$searchStr." AND c.classId=".trim($REQUEST_DATA['class']); 
    }
    //$searchStr=' AND c.classId='.$REQUEST_DATA['class'].' AND su.subjectId='.$REQUEST_DATA['subject'].' AND att.groupId='.$REQUEST_DATA['group'].' AND att.fromDate <= "'.$REQUEST_DATA['forDate'].'"';
    if(trim($REQUEST_DATA['forDate'])!=""){
     $searchStr .=' AND att.fromDate <= "'.$REQUEST_DATA['forDate'].'"';
    }
    
    if(trim($REQUEST_DATA['timeTableLabelId'])!=""){
      if($sessionHandler->getSessionVariable('RoleId')!=2){  
        $searchStr .=' AND ttc.timeTableLabelId="'.trim($REQUEST_DATA['timeTableLabelId']).'"';
      }
    }
    $prevoiusRecordArray=$teacherManager->getStudentAttendanceTillDate($searchStr,' ',$orderBy);
    $previousRecordCount=count($prevoiusRecordArray);
    //*********used to fetch student's attendance till <= "From Date"****************  
	
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
    if($topicsTaughtId==''){
         $topicsTaughtId="";
     }
     if($subjectTaughtId==''){
         $subjectTaughtId="";
     }
     if($topicTaughtId==''){
         $topicTaughtId="";
     }

    //for adding null values when not member of class
    $nullStr='<option value="NULL" selected="selected"></option>';

    for($i=0;$i<$cnt;$i++) {
        $rollNo=trim($studentRecordArray[$i]['rollNo']);
        
        $per="0.00";
        $delivered=0;
        $attended=0;
        for($m=0;$m<$previousRecordCount;$m++){ //showing previous attendance record of a student
          if($prevoiusRecordArray[$m]['studentId']==$studentRecordArray[$i]['studentId']){   
          if(abs($prevoiusRecordArray[$m]['delivered'])>0){
              $per=number_format((strip_slashes(abs($prevoiusRecordArray[$m]['attended']))/strip_slashes(abs($prevoiusRecordArray[$m]['delivered'])))*100,2,'.','');
              $delivered=strip_slashes(abs($prevoiusRecordArray[$m]['delivered']));
              $attended=strip_slashes(abs($prevoiusRecordArray[$m]['attended']));
          }
         else{
              $per="0.00";
              $delivered=0;
              $attended=0;
            }
          }
        }
            
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
       
       if($fl==1){ //if studentId exist in attendance_daily table
        //echo $attendanceRecordArray[$j]['studentId']."~~".$attendanceRecordArray[$i]['attendanceCodeId']."<br>";
        $optStr="";
        for($k=0 ; $k < $cntAttCode ; $k++){
             if($attCode[$k]['attendanceCodeId']==$attendanceRecordArray[$j]['attendanceCodeId']){
                $optStr .= '<option value="'.$attCode[$k]['attendanceCodeId'].'" selected="selected">'.$attCode[$k]['attendanceCode'].'</option>';  
              }
             else{
				 
               $optStr .= '<option value="'.$attCode[$k]['attendanceCodeId'].'" >'.$attCode[$k]['attendanceCode'].'</option>';                     
             }
        }

      //even if attendenceCode is not in datebase select will appended
      //$optStr .='<option value="" >SELECT ATTD. CODE</option>';
        if($attendanceRecordArray[$j]['isMemberOfClass']=="1"){ //if memberof class           //tabIndex starts from 10(for attCodes) and (10+noofrecords) for memofclasses
        $valueArray = array_merge(array('srNo' => ($records+$i+1),
        'attendanceCode'=>'<select disabled="disabled" onchange="generateAttendanceSummery();setGlobalEditFlag(1);" alt="'.$rollNo.'" name="attendanceCode" id="attendanceCode'.$i.'" size="1" class="selectfield3" tabIndex='.($i+10).' >'.$optStr.'</select>',
        'memberOfClass'=>'<input disabled="disabled" type="checkbox" name="mem" id="mem'.$i.'" checked  onclick="mocAction('.$i.');" tabIndex='.($i+$cnt+10).'  >'.'<input type="hidden" name="attendance" id="attendance" value="'.strip_slashes($attendanceRecordArray[$j]['attendanceId']).'~'.strip_slashes($studentRecordArray[$i]['studentId']).'" >',
        'delivered'=>$delivered,
        'attended'=>$attended,
        'percentage'=>$per
        //,'attendance'=>'<input type="hidden" name="attendance" id="attendance" value="'.strip_slashes($attendanceRecordArray[$j]['attendanceId']).'~'.strip_slashes($studentRecordArray[$i]['studentId']).'" >'
        ),
        $studentRecordArray[$i]);
       }
      else{   //if not memberof class
         $valueArray = array_merge(array('srNo' => ($records+$i+1),
        'attendanceCode'=>'<select disabled="disabled" onchange="generateAttendanceSummery();setGlobalEditFlag(1);" alt="'.$rollNo.'" name="attendanceCode" id="attendanceCode'.$i.'" size="1" class="selectfield3" disabled="true" tabIndex='.($i+10).'  >'.$optStr.$nullStr.'</select>',
        'memberOfClass'=>'<input disabled="disabled" type="checkbox" name="mem" id="mem'.$i.'"  onclick="mocAction('.$i.');"  tabIndex='.($i+$cnt+10).' >'.'<input type="hidden" name="attendance" id="attendance" value="'.strip_slashes($attendanceRecordArray[$j]['attendanceId']).'~'.strip_slashes($studentRecordArray[$i]['studentId']).'" >',
        'delivered'=>$delivered,
        'attended'=>$attended,
        'percentage'=>$per
        //,'attendance'=>'<input type="hidden" name="attendance" id="attendance" value="'.strip_slashes($attendanceRecordArray[$j]['attendanceId']).'~'.strip_slashes($studentRecordArray[$i]['studentId']).'" >'
        ),
        $studentRecordArray[$i]); 
      }  
     }
    else{ //if studentId does exist in attendance_daily table

        $valueArray = array_merge(array('srNo' => ($records+$i+1),
        'attendanceCode'=>'<select disabled="disabled" onchange="generateAttendanceSummery();setGlobalEditFlag(1);" alt="'.$rollNo.'" name="attendanceCode" id="attendanceCode'.$i.'" size="1" class="selectfield3"  tabIndex='.($i+10).' >'.$optStr2.'</select>',
        'memberOfClass'=>'<input disabled="disabled" type="checkbox" name="mem" id="mem'.$i.'" checked="checked" onclick="mocAction('.$i.');" tabIndex='.($i+$cnt+10).'  >'.'<input type="hidden" name="attendance" id="attendance" value="-1'.'~'.strip_slashes($studentRecordArray[$i]['studentId']).'" >',
        'delivered'=>$delivered,
        'attended'=>$attended,
        'percentage'=>$per
        //,'attendance'=>'<input type="hidden" name="attendance" id="attendance" value="-1'.'~'.strip_slashes($studentRecordArray[$i]['studentId']).'" >'
        ),
        $studentRecordArray[$i]);         
      }  

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.'],"topicsId" : '.json_encode($topicsTaughtId).',"topicsComments" : '.json_encode($topicsComments).',"topicSubjectId" : '.json_encode($subjectTaughtId).',"topicTaughtId" : '.json_encode($topicTaughtId).',"attendanceCodeInfo" : '.json_encode($attCode).'}'; 
?>