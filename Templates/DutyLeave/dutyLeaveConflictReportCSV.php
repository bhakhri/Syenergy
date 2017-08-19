<?php 
//This file is used as printing version for TestType.
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    ini_set("memory_limit","250M");      
    set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','DutyLeaveConflictReport');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/DutyLeaveManager.inc.php");
    $dutyManager = DutyLeaveManager::getInstance();
    

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
    
    function clearSpecialChar($text) {
       if($text!="") {
         $text=strtolower($text);
         $code_entities_match = array(' ',"'",'"','%','\t','    ');
         $code_entities_replace = array('','','','','','');
         $text = str_replace($code_entities_match, $code_entities_replace, $text);
       }
       return $text;
    }
         
     $valueArray = array();
    
    // to limit records per page    
    $limit = '';
    
    $labelId=trim($REQUEST_DATA['labelId']);
    $classId=trim($REQUEST_DATA['classId']);
    $subjectId=trim($REQUEST_DATA['subjectId']);
    $eventId=trim($REQUEST_DATA['eventId']);
    $rollNos =  htmlentities(add_slashes(clearSpecialChar(trim($REQUEST_DATA['rollNo']))));
    $showConflict=trim($REQUEST_DATA['showConflict']);
    $rejectedLeave =trim($REQUEST_DATA['rejectedLeave']);
    $includeLimit=trim($REQUEST_DATA['includeLimit']);
    $leaveFor=trim($REQUEST_DATA['leaveFor']);    

    if($showConflict=='1') {
      $showConflictString = "Conflicted Data";
    }
    else if($showConflict=='0') {
      $showConflictString = "Non Conflicted Data";
    }
    else {  
      $showConflictString = "Both";
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
    
    if($includeLimit=='') {
      $includeLimit='1';  
    }   
       
    if($rejectedLeave=='') {
      $rejectedLeave='0';  
    } 
    
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'dutyDate';
    
    $sortField1 = $sortField;
    //if($sortField1=='dutyDate') {
    $sortField1 = "rollNo, dutyDate, periodId, eventId ";   
    //}
    $orderBy = " $sortField1 $sortOrderBy"; 
    
    $classIds=0;
    $subjectIds=0;
    $studentIds=0;
    $dutyLeaveLimit=$sessionHandler->getSessionVariable('DUTY_LEAVE_LIMIT'); 
    $sessionId = $sessionHandler->getSessionVariable('SessionId'); 
    
     if($subjectId=='') {
       $subjectId='0';  
     }
   
     // Default Condition all duty leave fetch
     $condition ='';
     $condition .= " AND dl.classId='$classId' ";
     $condition .= " AND dl.sessionId='$sessionId' ";
     $condition .= " AND dl.subjectId IN ($subjectId) "; 
     if($leaveFor!='') {
       $condition .= " AND dl.rejected = '$leaveFor'  ";   
     } 
     if($rollNo!='') {
       $condition .= " AND s.rollNo IN ($rollNo) ";  
     }
   
    if($includeLimit=='1') {  
         // Don't Show Which Overshoot Duty Leave Limit 
         $havingCondition = " HAVING cnt <= '$dutyLeaveLimit' ";
         $dutyLeaveLimitedRecords=$dutyManager->countDutyLeavePerSubject($condition,$havingCondition); 
         $studentIds ='0';
         if(is_array($dutyLeaveLimitedRecords) && count($dutyLeaveLimitedRecords)>0) {
           for($k=0;$k<count($dutyLeaveLimitedRecords);$k++){
                if($dutyLeaveLimitedRecords[$k]['studentId']!='') {
                    $studentIds.=','.$dutyLeaveLimitedRecords[$k]['studentId'];
                }
           }
         }
         $condition .= " AND dl.studentId IN (".$studentIds.") ";
     }   
   
    
     // -1 => Empty, -2 All
     /* 
        if($eventId!="-1" AND $eventId!=-2) {
          $filter .=" AND de.eventId='$eventId' ";
        }
     */
     $ttEventId = $eventId;    
    
     
    //$dutyRecordArray = $dutyManager->dutyLeaveList($filter,$limit,$orderBy);
    $dutyRecordArray = $dutyManager->dutyLeaveAdminList($condition,$limit,$orderBy);
    
    
    $cnt = count($dutyRecordArray);
   
    $json_val='';   
    
    $findArray = array(); 

    $cc='0';
    $totalAllowed=0;
    $previousRecord='';
    for($i=0;$i<$cnt;$i++) {
    
       $isConflicted=0; //no conflict
       $excludeArray=array();
       $excludeMedicalArray=array();    
       $selectElementId='';
        
       $studentId=$dutyRecordArray[$i]['studentId'];
       $classId=$dutyRecordArray[$i]['classId'];
       $groupId=$dutyRecordArray[$i]['groupId'];
       $subjectId=$dutyRecordArray[$i]['subjectId'];
       $periodId=$dutyRecordArray[$i]['periodId'];
       $dutyDate=$dutyRecordArray[$i]['dutyDate'];
       $eventId=$dutyRecordArray[$i]['eventId'];
       $dateArray=explode(',',$dutyDate);
       $daysOfWeek=date('N',mktime(0, 0, 0, $dateArray[1]  , $dateArray[2], $dateArray[0]));
       
       $span1='';
       $span2='';
       
       $currentRecord = $studentId."~".$classId."~".$groupId."~".$subjectId."~".$periodId."~".$dutyDate;
       $nextRecord ='';
       if(($i+1)<=$cnt) {
         $nnStudentId=$dutyRecordArray[$i+1]['studentId'];
         $nnClassId=$dutyRecordArray[$i+1]['classId'];
         $nnGroupId=$dutyRecordArray[$i+1]['groupId'];
         $nnSubjectId=$dutyRecordArray[$i+1]['subjectId'];
         $nnPeriodId=$dutyRecordArray[$i+1]['periodId'];
         $nnDutyDate=$dutyRecordArray[$i+1]['dutyDate']; 
         $nextRecord = $nnStudentId."~".$nnClassId."~".$nnGroupId."~".$nnSubjectId."~".$nnPeriodId."~".$nnDutyDate; 
       }
       if($currentRecord==$nextRecord) {
         //$span1="<span style='color:blue'>";    
         // $span2="</span>";
         $span1='';
         $span2='';

         $previousRecord='1';
       }
       else {
         if($previousRecord=='1') {
           //$span1="<span style='color:blue'>";    
           //$span2="</span>";  
          $span1='';
          $span2='';
         }  
         $previousRecord='';  
       }
       
       
       
       
       
       if($includeLimit=='3') {
           $ttFind='-1';        
           for($kk=0;$kk<count($findArray);$kk++) {
             if($findArray[$kk]['studentId']==$studentId) {
               $findArray[$kk]['countRange']++;
               $ttFind=$kk;
               break;
             }
           }
           
           if($ttFind=='-1') {
             $findArray[$cc]['studentId']=$studentId;  
             $findArray[$cc]['countRange']=1;  
             $cc++;
           }
           
           
           if($ttFind!='-1') {
             if($findArray[$ttFind]['countRange'] > $dutyLeaveLimit) {
               continue;        
             } 
           }
       }
           
       if($ttEventId!="-1" AND $ttEventId!=-2) { 
          if($ttEventId!=$eventId) {
            continue;  
          }
       }
       
       if(trim($dutyRecordArray[$i]['rollNo'])==''){
         $dutyRecordArray[$i]['rollNo']=NOT_APPLICABLE_STRING;
       }
    
       $dutyRecordArray[$i]['dutyDate']=UtilityManager::formatDate($dutyRecordArray[$i]['dutyDate']);
       $dutyRecordArray[$i]['conflictedWith']='No Conflict';
       
       $selectElementId=$studentId.'_'.$classId.'_'.$subjectId.'_'.$periodId.'_'.$eventId.'_'.str_replace('-','~',$dutyDate);
       
       $lectureDelivered=0;
       $lectureAttended=0;
       //check conflict with bulk attendance
       $bulkAttendanceArray=$dutyManager->getStudentBulkAttendanceRecord($studentId,$classId,$subjectId,$dutyDate);
       if($bulkAttendanceArray[0]['totalRecords']!=0){
          $isConflicted=1;  //conflict with bulk attendance
          $dutyRecordArray[$i]['conflictedWith']='Bulk Attendance';
          $excludeArray=array(DUTY_LEAVE_MARK_ABSENT);
          //fetch student's lecture delivered and attended details
          $studentAttendanceArray=$dutyManager->getStudentAttendanceRecord($studentId,$classId,$subjectId);
          $lectureDelivered=round($studentAttendanceArray[0]['delivered']);
          $lectureAttended=round($studentAttendanceArray[0]['attended']);
          $selectElementId .='_'.$isConflicted.'_'.$lectureAttended.'_'.$lectureDelivered;
       }
       
       if($isConflicted==0){ 
            //check conflict with daily attendacen
            $dailyAttendanceArray=$dutyManager->getStudentDailyAttendanceRecord($studentId,$classId,$subjectId,$periodId,$dutyDate);
            if($dailyAttendanceArray[0]['totalRecords']!=0){
              $isConflicted=2; //conflict with daily attendance
              $selectElementId .='_'.$isConflicted.'_'.$lectureAttended.'_'.$lectureDelivered;
              $dutyRecordArray[$i]['conflictedWith']='Daily Attendance';
              $excludeArray=array();
            }
       }
      
       if($isConflicted==0){ 
            //check conflict with Medical Leave
            $dailyAttendanceArray=$dutyManager->checkStudentMedicalLeaveExistence($studentId,$classId,$subjectId,$periodId,$dutyDate);
            if($dailyAttendanceArray[0]['totalRecords']!=0){
              $isConflicted=3; //conflict with Medical Leave
              $selectElementId .='_'.$isConflicted.'_1_1';
              $dutyRecordArray[$i]['conflictedWith']='Medical Leave';
              $excludeArray=array();
            }
        }
      
      
        if($showConflict==1){ //if user wants to see conflicted results
              if($isConflicted!=0){
                 // Duty Leave 
                 $dutyRecordArray[$i]['actionString']=createDutyLeaveStatusString($selectElementId,$dutyRecordArray[$i]['rejected'],$excludeArray);
                 
             // Color Format Start      
                 $dutyRecordArray[$i]['dutyDate'] = $span1.$dutyRecordArray[$i]['dutyDate'].$span2;
                 $dutyRecordArray[$i]['periodNumber'] = $span1.$dutyRecordArray[$i]['periodNumber'].$span2;   
                 $dutyRecordArray[$i]['studentName'] = $span1.$dutyRecordArray[$i]['studentName'].$span2;   
                 $dutyRecordArray[$i]['rollNo'] = $span1.$dutyRecordArray[$i]['rollNo'].$span2;   
                 $dutyRecordArray[$i]['eventTitle'] = $span1.$dutyRecordArray[$i]['eventTitle'].$span2;   
                 $dutyRecordArray[$i]['subjectCode'] = $span1.$dutyRecordArray[$i]['subjectCode'].$span2;   
                 $dutyRecordArray[$i]['conflictedWith'] = $span1.$dutyRecordArray[$i]['conflictedWith'].$span2; 
             // Color Format End
                 
                 $valueArray[] = array_merge(array('srNo' => ($totalAllowed+1) ),$dutyRecordArray[$i]); 
                 //createJSON($valueArray);
                 $totalAllowed++;
              }
        }
        else if($showConflict==0){//if user wants to see non-conflicted results
          if($isConflicted==0){
             $excludeArray=array(DUTY_LEAVE_MARK_ABSENT);
             $selectElementId .='_'.$isConflicted.'_'.$lectureAttended.'_'.$lectureDelivered;
             $dutyRecordArray[$i]['actionString']=createDutyLeaveStatusString($selectElementId,$dutyRecordArray[$i]['rejected'],$excludeArray); 
             
             // Color Format Start      
                 $dutyRecordArray[$i]['dutyDate'] = $span1.$dutyRecordArray[$i]['dutyDate'].$span2;
                 $dutyRecordArray[$i]['periodNumber'] = $span1.$dutyRecordArray[$i]['periodNumber'].$span2;   
                 $dutyRecordArray[$i]['studentName'] = $span1.$dutyRecordArray[$i]['studentName'].$span2;   
                 $dutyRecordArray[$i]['rollNo'] = $span1.$dutyRecordArray[$i]['rollNo'].$span2;   
                 $dutyRecordArray[$i]['eventTitle'] = $span1.$dutyRecordArray[$i]['eventTitle'].$span2;   
                 $dutyRecordArray[$i]['subjectCode'] = $span1.$dutyRecordArray[$i]['subjectCode'].$span2;   
                 $dutyRecordArray[$i]['conflictedWith'] = $span1.$dutyRecordArray[$i]['conflictedWith'].$span2; 
             // Color Format End
             
             $valueArray[] = array_merge(array('srNo' => ($totalAllowed+1) ),$dutyRecordArray[$i]); 
             //createJSON($valueArray);
             $totalAllowed++;
          }
        }
        else{
            if($isConflicted==0){ 
             $excludeArray=array(DUTY_LEAVE_MARK_ABSENT); //no conflict with attendance and medical leave
             $selectElementId .='_'.$isConflicted.'_'.$lectureAttended.'_'.$lectureDelivered;
            }
            else if($isConflicted==1){
             $excludeArray=array(DUTY_LEAVE_MARK_ABSENT); //conflict with bulk attendance
            }
            else if($isConflicted==3){
             $excludeArray=array(DUTY_LEAVE_MARK_ABSENT); //conflict with medical leave
            }
            else{
             $excludeArray=array(); //conflict with daily attendance
            }
            
          $dutyRecordArray[$i]['actionString']=createDutyLeaveStatusString($selectElementId,$dutyRecordArray[$i]['rejected'],$excludeArray);
          
         // Color Format Start      
             $dutyRecordArray[$i]['dutyDate'] = $span1.$dutyRecordArray[$i]['dutyDate'].$span2;
             $dutyRecordArray[$i]['periodNumber'] = $span1.$dutyRecordArray[$i]['periodNumber'].$span2;   
             $dutyRecordArray[$i]['studentName'] = $span1.$dutyRecordArray[$i]['studentName'].$span2;   
             $dutyRecordArray[$i]['rollNo'] = $span1.$dutyRecordArray[$i]['rollNo'].$span2;   
             $dutyRecordArray[$i]['eventTitle'] = $span1.$dutyRecordArray[$i]['eventTitle'].$span2;   
             $dutyRecordArray[$i]['subjectCode'] = $span1.$dutyRecordArray[$i]['subjectCode'].$span2;   
             $dutyRecordArray[$i]['conflictedWith'] = $span1.$dutyRecordArray[$i]['conflictedWith'].$span2; 
         // Color Format End
          
          $valueArray[] = array_merge(array('srNo' => ($totalAllowed+1) ),$dutyRecordArray[$i]); 
          $totalAllowed++;
      }
    }
    

    function createDutyLeaveStatusString($selectElementId,$selectedValue='',$excludeArray=array()){
        global $globalDutyLeaveStatusArray;
        
        $selected='';
        $returnString ='';
        if($selectedValue==-1){
            $selectedValue=DUTY_LEAVE_UNRESOLVED;
        }
        foreach($globalDutyLeaveStatusArray AS $key=>$value){
            if(in_array($key,$excludeArray)){
                continue;
            }
            $selected='';
            if($key==$selectedValue){
               $selected='selected="selected"';
               $returnString = $value;
               break;
            }
        }
        
        if($returnString=='') {
          $returnString = NOT_APPLICABLE_STRING;  
        }
        
        return $returnString;
    }

    $recordCount = count($valueArray);
    
    $labelName=trim($REQUEST_DATA['labelName']);
    $className=trim($REQUEST_DATA['className']);
    $eventName=trim($REQUEST_DATA['eventName']);
    
    $search = "Time Table,".parseCSVComments($labelName)."\n";
    $search .= "Class,".parseCSVComments($className)."\n";
    $search .= "Event,".parseCSVComments($eventName);
    $search .= ",Show,".parseCSVComments($showConflictString);
  
    $csvData = $search;
    $csvData .="\n";
    $csvData .="#, Date,Period,Roll No.,Name,Subject,Event";
    if($showConflict!=0){
      $csvData .=",Conflicted with";  
    }
    $csvData .=",Status";


    $csvData .="\n";
    for($i=0;$i<$recordCount;$i++) {
	  $csvData .= ($i+1).",";
	  $csvData .= parseCSVComments($dutyRecordArray[$i]['dutyDate']).",";
	  $csvData .= parseCSVComments($dutyRecordArray[$i]['periodNumber']).",";
	  $csvData .= parseCSVComments($dutyRecordArray[$i]['rollNo']).",";
	  $csvData .= parseCSVComments($dutyRecordArray[$i]['studentName']).",";
	  $csvData .= parseCSVComments($dutyRecordArray[$i]['subjectCode']).",";
	  $csvData .= parseCSVComments($dutyRecordArray[$i]['eventTitle']).",";
          if($showConflict!=0){
	    $csvData .= parseCSVComments($dutyRecordArray[$i]['conflictedWith']).",";
          }
	  $csvData .= parseCSVComments($dutyRecordArray[$i]['actionString']);
	  $csvData .= "\n";
    }
    if($recordCount==0){
      $csvData .=",,,".NO_DATA_FOUND;
    }
    
    ob_end_clean();
    header("Cache-Control: public, must-revalidate");
    header('Content-type: application/octet-stream; charset=utf-8');
    header("Content-Length: " .strlen($csvData) );
    header('Content-Disposition: attachment;  filename="dutyLeaveConflictReport.csv"');
    header("Content-Transfer-Encoding: binary\n");
    echo $csvData;
    die;
?>

