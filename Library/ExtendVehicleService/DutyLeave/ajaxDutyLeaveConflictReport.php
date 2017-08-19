<?php
//-------------------------------------------------------
// Purpose: To show duty leave conflict report
// functionality
// Author : Aditi Miglani
// Created on : 18 Nov 2011
// Copyright 2011-2012: Chalkpad Technologies Pvt. Ltd.
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

    
    function clearSpecialChar($text) {
       if($text!="") {
         $text=strtolower($text);
         $code_entities_match = array(' ',"'",'"','%','\t','    ');
         $code_entities_replace = array('','','','','','');
         $text = str_replace($code_entities_match, $code_entities_replace, $text);
       }
       return $text;
    }
         
    
    
    // to limit records per page    
    $limit = '';
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    //$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
    $labelId=trim($REQUEST_DATA['labelId']);
    $classId=trim($REQUEST_DATA['classId']);
    $subjectId=trim($REQUEST_DATA['subjectId']);
    $eventId=trim($REQUEST_DATA['eventId']);
    $rollNos =  htmlentities(add_slashes(clearSpecialChar(trim($REQUEST_DATA['rollNo']))));
    $showConflict=trim($REQUEST_DATA['showConflict']);
    $rejectedLeave =trim($REQUEST_DATA['rejectedLeave']);
    $includeLimit=trim($REQUEST_DATA['includeLimit']);
    $leaveFor=trim($REQUEST_DATA['leaveFor']);
    
    
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
       
    if($leaveFor!='') {
      $rejectedLeave="$leaveFor";  
    } 
    
    
    if($classId=='' or $labelId==''){
       echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
       die; 
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
         $span1="<span style='color:blue'>";    
         $span2="</span>";
         $previousRecord='1';
       }
       else {
         if($previousRecord=='1') {
           $span1="<span style='color:blue'>";    
           $span2="</span>";  
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
          $dutyRecordArray[$i]['conflictedWith']='<b>Bulk Attendance</b>';
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
              $dutyRecordArray[$i]['conflictedWith']='<b>Daily Attendance</b>';
              $excludeArray=array();
            }
       }
      
       if($isConflicted==0){ 
            //check conflict with Medical Leave
            $dailyAttendanceArray=$dutyManager->checkStudentMedicalLeaveExistence($studentId,$classId,$subjectId,$periodId,$dutyDate);
            if($dailyAttendanceArray[0]['totalRecords']!=0){
              $isConflicted=3; //conflict with Medical Leave
              $selectElementId .='_'.$isConflicted.'_1_1';
              $dutyRecordArray[$i]['conflictedWith']='<b>Medical Leave</b>';
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
                 
                 $valueArray = array_merge(array('srNo' => ($totalAllowed+1) ),$dutyRecordArray[$i]); 
                 createJSON($valueArray);
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
             
             $valueArray = array_merge(array('srNo' => ($totalAllowed+1) ),$dutyRecordArray[$i]); 
             createJSON($valueArray);
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
          
          $valueArray = array_merge(array('srNo' => ($totalAllowed+1) ),$dutyRecordArray[$i]); 
          createJSON($valueArray);
          $totalAllowed++;
      }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalAllowed.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
  
  
    //this functin is used to format JSON data
    function createJSON($valueArray){
       global $json_val;
       if($json_val==''){
           $json_val=json_encode($valueArray);
       }
       else{
           $json_val .= ','.json_encode($valueArray);
       } 
    }

    function createDutyLeaveStatusString($selectElementId,$selectedValue='',$excludeArray=array()){
        global $globalDutyLeaveStatusArray;
        $returnString='<option value="-1">Select</option>';
        $selected='';
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
            }
            $returnString .='<option value="'.$key.'"  '.$selected.'>'.$value.'</option>';
        }
        return '<select name="dutyLeaveStatus" id="'.$selectElementId.'" class="inputbox" style="width:100px;" onchange="checkBulkAttendanceRestriction(this.id,this.value);">'.$returnString.'</select>';
    }
  
    
// for VSS
// $History: ajaxInitList.php $
?>
