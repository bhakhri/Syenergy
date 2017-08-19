<?php
//-------------------------------------------------------
// Purpose: To show medical leave conflict report for admin
// functionality
// Author : Aditi Miglani
// Created on : 18 Nov 2011
// Copyright 2011-2012: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
    ini_set("memory_limit","250M");      
    set_time_limit(0);
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','MedicalLeaveConflictAdminReport');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/MedicalLeaveManager.inc.php");
    $medicalLeaveManager = MedicalLeaveManager::getInstance();

    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    //$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
    $labelId=trim($REQUEST_DATA['labelId']);
    $classId=trim($REQUEST_DATA['classId']);
    $subjectId=trim($REQUEST_DATA['subjectId']);
    $rollNo=trim($REQUEST_DATA['rollNo']);
    $displayRecord=trim($REQUEST_DATA['displayRecord']);
    $showConflict=trim($REQUEST_DATA['showConflict']);
    
    
    if($classId=='' or $labelId==''){
       echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
       die; 
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    $orderBy = " $sortField $sortOrderBy"; 
    
    $classIds=0;
	$subjectIds=0;
	$studentIds=0;
	$groupIds=0;
	$medicalLeaveLimit=$sessionHandler->getSessionVariable('MEDICAL_LEAVE_LIMIT'); 
	$sessionId = $sessionHandler->getSessionVariable('SessionId'); 
	
	$condition =' AND classId='.$classId;
    $condition .=' AND sessionId='.$sessionId;
    
	$medicalLeaveLimitedRecords = $medicalLeaveManager->medicalLeaveCount($condition);
	
  if(is_array($medicalLeaveLimitedRecords) && count($medicalLeaveLimitedRecords)>0 ){
	
	for($k=0;$k<count($medicalLeaveLimitedRecords);$k++){
	
		//if display records above limit
		if($displayRecord=='1'){
			if($medicalLeaveLimitedRecords[$k]['cnt']>$medicalLeaveLimit){
				if($medicalLeaveLimitedRecords[$k]['subjectId']!='') {
		          $subjectIds.=','.$medicalLeaveLimitedRecords[$k]['subjectId'];
		        }
		        if($medicalLeaveLimitedRecords[$k]['studentId']!='') {
				  $studentIds.=','.$medicalLeaveLimitedRecords[$k]['studentId'];
		        }
			}
		}
		//if display records below limit
		if($displayRecord=='2'){
			if($medicalLeaveLimitedRecords[$k]['cnt']<=$medicalLeaveLimit){
				if($medicalLeaveLimitedRecords[$k]['subjectId']!='') {
		          $subjectIds.=','.$medicalLeaveLimitedRecords[$k]['subjectId'];
		        }
		        if($medicalLeaveLimitedRecords[$k]['studentId']!='') {
				  $studentIds.=','.$medicalLeaveLimitedRecords[$k]['studentId'];
		        }
			}
		}
		//if display all records limit
		if($displayRecord=='3'){
				if($medicalLeaveLimitedRecords[$k]['subjectId']!='') {
		          $subjectIds.=','.$medicalLeaveLimitedRecords[$k]['subjectId'];
		        }
		        if($medicalLeaveLimitedRecords[$k]['studentId']!='') {
				  $studentIds.=','.$medicalLeaveLimitedRecords[$k]['studentId'];
		        }
		}
	}
  
	
	$filter =' AND ml.classId='.$classId;
    if($rollNo!=''){
	  $filter.=" AND s.rollNo LIKE  '".$rollNo."'";
    }
    
    if($subjectId=='') {
      $subjectId=0;  
    }
    
    if($subjectId!='all') {
      $filter .= " AND ml.subjectId IN ($subjectId) ";     
    }
    
    if($subjectId=='all') {
    	$filter .= " AND ml.subjectId IN ($subjectIds) ";  
	}
    
    $filter .=' AND ml.studentId IN ('.$studentIds.')';
    

	$medicalLeaveRecordArray = $medicalLeaveManager->medicalLeaveAdminList($filter,$limit,$orderBy);
	$cnt = count($medicalLeaveRecordArray);

    $json_val='';    
    $totalAllowed=0;
    for($i=0;$i<$cnt;$i++) {
       
       $isConflicted=0; //no conflict
       $excludeArray=array();
       $selectElementId='';
        
       $studentId=$medicalLeaveRecordArray[$i]['studentId'];
       $classId=$medicalLeaveRecordArray[$i]['classId'];
       $groupId=$medicalLeaveRecordArray[$i]['groupId'];
       $subjectId=$medicalLeaveRecordArray[$i]['subjectId'];
       $periodId=$medicalLeaveRecordArray[$i]['periodId'];
       $medicalLeaveDate=$medicalLeaveRecordArray[$i]['medicalLeaveDate'];
       $subjectId=$medicalLeaveRecordArray[$i]['subjectId'];
       $dateArray=explode(',',$medicalLeaveDate);
       $daysOfWeek=date('N',mktime(0, 0, 0, $dateArray[1]  , $dateArray[2], $dateArray[0]));
       
       if(trim($medicalLeaveRecordArray[$i]['rollNo'])==''){
           $medicalLeaveRecordArray[$i]['rollNo']=NOT_APPLICABLE_STRING;
       }
       
       $medicalLeaveRecordArray[$i]['medicalLeaveDate']=UtilityManager::formatDate($medicalLeaveRecordArray[$i]['medicalLeaveDate']);
       $medicalLeaveRecordArray[$i]['conflictedWith']='No Conflict';
       
       
       $selectElementId=$studentId.'_'.$classId.'_'.$subjectId.'_'.$periodId.'_'.$subjectId.'_'.str_replace('-','~',$medicalLeaveDate);
       
       $lectureDelivered=0;
       $lectureAttended=0;
       
       //check conflict with bulk attendance
       $bulkAttendanceArray=$medicalLeaveManager->getStudentBulkAttendanceRecord($studentId,$classId,$subjectId,$medicalLeaveDate);
       if($bulkAttendanceArray[0]['totalRecords']!=0){
          $isConflicted=1;  //conflict with bulk attendance
          $medicalLeaveRecordArray[$i]['conflictedWith']='<b>Bulk Attendance</b>';
          $excludeArray=array(MEDICAL_LEAVE_MARK_ABSENT);
          //fetch student's lecture delivered and attended details
          $studentAttendanceArray=$medicalLeaveManager->getStudentAttendanceRecord($studentId,$classId,$subjectId);
          $lectureDelivered=round($studentAttendanceArray[0]['delivered']);
          $lectureAttended=round($studentAttendanceArray[0]['attended']);
          $selectElementId .='_'.$isConflicted.'_'.$lectureAttended.'_'.$lectureDelivered;
       }
       
      if($isConflicted==0){ 
        //check conflict with daily attendacen
        $dailyAttendanceArray=$medicalLeaveManager->getStudentDailyAttendanceRecord($studentId,$classId,$subjectId,$periodId,$medicalLeaveDate);
        if($dailyAttendanceArray[0]['totalRecords']!=0){
          $isConflicted=2; //conflict with daily attendance
          $selectElementId .='_'.$isConflicted.'_'.$lectureAttended.'_'.$lectureDelivered;
          $medicalLeaveRecordArray[$i]['conflictedWith']='<b>Daily Attendance</b>';
          $excludeArray=array();
        }
      }
      
      if($isConflicted==0){ 
        //check conflict with Duty Leave
        $dailyAttendanceArray=$medicalLeaveManager->checkStudentDutyLeaveExistence($studentId,$classId,$subjectId,$periodId,$medicalLeaveDate);
        if($dailyAttendanceArray[0]['totalRecords']!=0){
          $isConflicted=3; //conflict with Medical Leave
          $selectElementId .='_'.$isConflicted.'_1_1';
          $medicalLeaveRecordArray[$i]['conflictedWith']='<b>Duty Leave</b>';
          $excludeArray=array();
        }
      }
      
      
      if($showConflict==1){ //if user wants to see conflicted results
          if($isConflicted!=0){
             $medicalLeaveRecordArray[$i]['actionString']=createMedicalLeaveStatusString($selectElementId,$medicalLeaveRecordArray[$i]['approvedStatus'],$excludeArray);
             $valueArray = array_merge(array('srNo' => ($totalAllowed+1) ),$medicalLeaveRecordArray[$i]); 
             createJSON($valueArray);
             $totalAllowed++;
          }
      }
      else if($showConflict==0){//if user wants to see non-conflicted results
          if($isConflicted==0){
             $excludeArray=array(MEDICAL_LEAVE_MARK_ABSENT);
             $selectElementId .='_'.$isConflicted.'_'.$lectureAttended.'_'.$lectureDelivered;
             $medicalLeaveRecordArray[$i]['actionString']=createMedicalLeaveStatusString($selectElementId,$medicalLeaveRecordArray[$i]['approvedStatus'],$excludeArray); 
             $valueArray = array_merge(array('srNo' => ($totalAllowed+1) ),$medicalLeaveRecordArray[$i]); 
             createJSON($valueArray);
             $totalAllowed++;
          }
      }
      else{
            if($isConflicted==0){ 
             $excludeArray=array(MEDICAL_LEAVE_MARK_ABSENT); //no conflict with attendance
             $selectElementId .='_'.$isConflicted.'_'.$lectureAttended.'_'.$lectureDelivered;
            }
            else if($isConflicted==1){
             $excludeArray=array(MEDICAL_LEAVE_MARK_ABSENT); //conflict with bulk attendance
            }
            else if($isConflicted==3){
             $excludeArray=array(MEDICAL_LEAVE_MARK_ABSENT); //conflict with Duty leave
            }
            else{
             $excludeArray=array(); //conflict with daily attendance
            }
            
          $medicalLeaveRecordArray[$i]['actionString']=createMedicalLeaveStatusString($selectElementId,$medicalLeaveRecordArray[$i]['approvedStatus'],$excludeArray);
          $valueArray = array_merge(array('srNo' => ($totalAllowed+1) ),$medicalLeaveRecordArray[$i]); 
          createJSON($valueArray);
          $totalAllowed++;
      }


      
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalAllowed.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    }//end if
  else{
	 echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalAllowed.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
  }

    
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

function createMedicalLeaveStatusString($selectElementId,$selectedValue='',$excludeArray=array()){
    global $globalMedicalLeaveStatusArray;
    $returnString='<option value="-1">Select</option>';
    $selected='';
    if($selectedValue==-1){
        $selectedValue=MEDICAL_LEAVE_UNRESOLVED;
    }
    foreach($globalMedicalLeaveStatusArray AS $key=>$value){
        if(in_array($key,$excludeArray)){
            continue;
        }
        $selected='';
        if($key==$selectedValue){
            $selected='selected="selected"';
        }
        $returnString .='<option value="'.$key.'"  '.$selected.'>'.$value.'</option>';
    }
    return '<select name="medicalLeaveStatus" id="'.$selectElementId.'" class="inputbox" style="width:100px;" onchange="checkBulkAttendanceRestriction(this.id,this.value);">'.$returnString.'</select>';
}
    
?>
