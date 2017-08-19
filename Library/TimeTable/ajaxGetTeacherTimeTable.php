<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To store the records of students in array from the database, pagination and search, delete 
// functionality
//
// Author : Jaineesh
// Created on : (28.10.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','MoveTeacherTimeTable');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/TimeTableManager.inc.php");
    $timeTableManager = TimeTableManager::getInstance();
    

    /////////////////////////
    $timeTableLabelId=trim($REQUEST_DATA['timeTableLabelId']);
    $classId=trim($REQUEST_DATA['classId']);
	$subjectId=trim($REQUEST_DATA['subjectId']);
	$groupId=trim($REQUEST_DATA['groupId']);
	$employeeId=trim($REQUEST_DATA['employeeId']);
	$adjustmentTypeId=trim($REQUEST_DATA['adjustmentTypeId']);

	$fromDate=trim($REQUEST_DATA['fromDate']);
    $toDate=trim($REQUEST_DATA['toDate']);

	
    if($timeTableLabelId==''){
        echo SELECT_TIME_TABLE;
        die;
    }

	if ($fromDate == "") {
		messageBox("<?php echo DATE_FROM_NOT_EMPTY; ?>");
		document.getElementById('fromDate').focus();
		return false;
   }

   if ($toDate == "") {
		messageBox("<?php echo DATE_TO_NOT_EMPTY; ?>");
		document.getElementById('toDate').focus();
		return false;
   }
   
	$fromDate = $REQUEST_DATA['fromDate'];
	$gFromDate = explode('-',$fromDate);
	$gYear = $gFromDate[0];
	$gMonth = $gFromDate[1];
	$gDate = $gFromDate[2];

	$getDay = date("w", mktime(0, 0, 0, $gMonth, $gDate, $gYear));

	$getDayName = $daysArr[$getDay];

	if ($getDay == 0) {
		$getDay = 7;//as we consider sunday as 7
	}

	$toDate = $REQUEST_DATA['toDate'];
	
	$gToDate = explode('-',$toDate);
	$gtYear = $gToDate[0];
	$gtMonth = $gToDate[1];
	$gtDate = $gToDate[2];

	$getToDay = date("w", mktime(0, 0, 0, $gtMonth, $gtDate, $gtYear));

	if ($getToDay == 0) {
		$getToDay = 7;//as we consider sunday as 7
	}

	$getToDayName = $daysArr[$getToDay];

	if($adjustmentTypeId == 2) {
		$adjustmentType = "copying";
	}

	if($adjustmentTypeId == 1) {
		$adjustmentType = "moving";
	}

	if ($classId != '' AND $subjectId == '' AND $groupId == '' AND $employeeId == '') {
		$filter=' AND ttl.isActive = 1 AND ttl.timeTableLabelId = '.$timeTableLabelId.' AND tt.daysOfWeek = '.$getDay.' AND cl.classId = '.$classId;
	}

	else if ($classId != '' AND $subjectId != '' AND $groupId == '' AND $employeeId == '') {
		$filter=' AND ttl.isActive = 1 AND ttl.timeTableLabelId = '.$timeTableLabelId.' AND tt.daysOfWeek = '.$getDay.' AND cl.classId = '.$classId.' AND tt.subjectId = '.$subjectId;
	}

	else if ($classId != '' AND $subjectId != '' AND $groupId != '' AND $employeeId == '') {
		$filter=' AND ttl.isActive=1 AND ttl.timeTableLabelId='.$timeTableLabelId.' AND tt.daysOfWeek ='.$getDay.' AND cl.classId ='.$classId.' AND tt.subjectId = '.$subjectId.' AND tt.groupId ='.$groupId;
	}

	else if ($classId != '' AND $subjectId != '' AND $groupId != '' AND $employeeId != '') {
		$filter=' AND ttl.isActive = 1 AND ttl.timeTableLabelId = '.$timeTableLabelId.' AND tt.daysOfWeek = '.$getDay.' AND cl.classId = '.$classId.' AND tt.subjectId = '.$subjectId.' AND tt.groupId = '.$groupId.' AND emp.employeeId = '.$employeeId;
	}

	if ($classId != '' AND $subjectId == '' AND $groupId == '' AND $employeeId == '') {
		$filter1=' AND ttl.isActive = 1 AND ttl.timeTableLabelId = '.$timeTableLabelId.' AND tt.daysOfWeek = '.$getToDay.' AND cl.classId = '.$classId;
	}

	else if ($classId != '' AND $subjectId != '' AND $groupId == '' AND $employeeId == '') {
		$filter1=' AND ttl.isActive = 1 AND ttl.timeTableLabelId = '.$timeTableLabelId.' AND tt.daysOfWeek = '.$getToDay.' AND cl.classId = '.$classId.' AND tt.subjectId = '.$subjectId;
	}

	else if ($classId != '' AND $subjectId != '' AND $groupId != '' AND $employeeId == '') {
		$filter1=' AND ttl.isActive=1 AND ttl.timeTableLabelId='.$timeTableLabelId.' AND tt.daysOfWeek ='.$getToDay.' AND cl.classId ='.$classId.' AND tt.subjectId = '.$subjectId.' AND tt.groupId ='.$groupId;
	}

	else if ($classId != '' AND $subjectId != '' AND $groupId != '' AND $employeeId != '') {
		$filter1=' AND ttl.isActive = 1 AND ttl.timeTableLabelId = '.$timeTableLabelId.' AND tt.daysOfWeek = '.$getToDay.' AND cl.classId = '.$classId.' AND tt.subjectId = '.$subjectId.' AND tt.groupId = '.$groupId.' AND emp.employeeId = '.$employeeId;
	}
	
	else {
		$filter=' AND ttl.isActive = 1 AND ttl.timeTableLabelId = '.$timeTableLabelId.' AND tt.daysOfWeek = '.$getDay;
		$filter1=' AND ttl.isActive = 1 AND ttl.timeTableLabelId = '.$timeTableLabelId.' AND tt.daysOfWeek = '.$getToDay;
	}
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* 1000;
    $limit      = ' LIMIT '.$records.',1000';

    //////
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField   = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'daysOfWeek';
    
    $orderBy = " ORDER BY $sortField $sortOrderBy";

    ////////////
    
    $employeeRecordArray  = $timeTableManager->getTeacherTimeTableForMoveCopy($filter,'',$orderBy);
	
    $cnt = count($employeeRecordArray);
	//echo '<pre>';
	//print_r($employeeRecordArray);
	//die;

	foreach ($employeeRecordArray as $recordArray) {
		//echo '<pre>';
		//print_r($recordArray);
		$recordArray['daysOfWeek']=$daysArr[$recordArray['daysOfWeek']];
		$alt=$recordArray['className'].",".$recordArray['subjectCode'].",".$recordArray['groupShort'].",".$recordArray['periodNumber'].",".$recordArray['daysOfWeek'].",".$recordArray['employeeName'].",".$getToDayName.",".$adjustmentType.",".$fromDate.",".$toDate.",".$getDayName;

		$timeTableAdjustment = $timeTableManager->getTeacherTimeTableForAdjustment("WHERE tta.timeTableId = ".$recordArray['timeTableId']);

		//foreach ($timeTableAdjustment as $adjustedTimeTable) {
			
			if(count($timeTableAdjustment) > 0) {
				$emp = NOT_APPLICABLE_STRING;	
				/*$timeTableAdjustmentArray = $timeTableManager->getTeacherTimeTableForAdjustment("WHERE tta.employeeId = ".$adjustedTimeTable['employeeId']." AND tta.groupId = ".$adjustedTimeTable['groupId']." AND tta.subjectId = ".$adjustedTimeTable['subjectId']." AND tta.periodId = ".$adjustedTimeTable['periodId']." AND '$toDate' BETWEEN tta.fromDate AND tta.toDate AND tta.timeTableId = ".$adjustedTimeTable['timeTableId'] );
				if(count($timeTableAdjustmentArray) > 0) {
					$emp = NOT_APPLICABLE_STRING;
				}
				else {
					$emp = "<input type=\"checkbox\" name=\"chb[]\" id=\"emps".$employeeRecordArray[$i]['timeTableId']."\" value=\"".$employeeRecordArray[$i]['timeTableId'] ."\" alt=\"".$alt."\" onclick=\"generateSuggestionDiv(this.value,this.alt,this.checked)\">";	
				}*/
			}
			else {
				$timeTableAdjustmentArray = $timeTableManager->getTeacherTimeTableForAdjustment("WHERE tta.employeeId = ".$recordArray['employeeId']." AND tta.groupId = ".$recordArray['groupId']." AND tta.subjectId = ".$recordArray['subjectId']." AND tta.periodId = ".$recordArray['periodId']." AND '$toDate' BETWEEN tta.fromDate AND tta.toDate");
				if (count($timeTableAdjustmentArray)>0) {
					$emp = NOT_APPLICABLE_STRING;	
				}
				else {
					$emp = "<input type=\"checkbox\" name=\"chb[]\" id=\"emps".$recordArray['timeTableId']."\" value=\"".$recordArray['timeTableId'] ."\" alt=\"".$alt."\" onclick=\"generateSuggestionDiv(this.value,this.alt,this.checked)\">";	
				}
			}
			//}
			

			$valueArray = array_merge(array('srNo' => ($records+$i+1),"emps" => $emp), $recordArray);
			if(trim($json_val)=='') {
			  $json_val = json_encode($valueArray);
			  }
			   else {
					$json_val .= ','.json_encode($valueArray);           
			   }
		}
	

		
		

		
		/*else {
			$emp = "<input type=\"checkbox\" name=\"chb[]\" id=\"emps".$employeeRecordArray[$i]['timeTableId']."\" value=\"".$employeeRecordArray[$i]['timeTableId'] ."\" alt=\"".$alt."\" onclick=\"generateSuggestionDiv(this.value,this.alt,this.checked)\">";
		}*/
		
		
		
		   //$k++;

	

	$fromDate = UtilityManager::formatDate($fromDate);
	$toDate = UtilityManager::formatDate($toDate);
	//var $srNo = 0;

	//echo '<pre>';
	//print_r($employeeRecordArray); 
//	die;
	
	//$k=0;
	/*for($i=0;$i<$cnt;$i++) {
		
       $employeeRecordArray[$i]['daysOfWeek']=$daysArr[$employeeRecordArray[$i]['daysOfWeek']];
	   
       //$valueArray = array_merge(array('srNo' => ($records+$i+1),"emps" => "<input type=\"checkbox\" name=\"emps\" id=\"emps\" value=\"".$employeeRecordArray[$i]['timeTableId'] ."\" onclick=\"generateSuggestionDiv(this.value,'".$employeeRecordArray[$i]['className']."','".$employeeRecordArray[$i]['subjectCode']."','".$employeeRecordArray[$i]['groupShort']."','".$employeeRecordArray[$i]['periodNumber']."','".$employeeRecordArray[$i]['daysOfWeek']."')\">"), $employeeRecordArray[$i]);
       $alt=$employeeRecordArray[$i]['className'].",".$employeeRecordArray[$i]['subjectCode'].",".$employeeRecordArray[$i]['groupShort'].",".$employeeRecordArray[$i]['periodNumber'].",".$employeeRecordArray[$i]['daysOfWeek'].",".$employeeRecordArray[$i]['employeeName'].",".$getToDayName.",".$adjustmentType.",".$fromDate.",".$toDate.",".$getDayName;
       
       //if these records are coming from adjusted table then these records cannot be selected
       /*if($employeeRecordArray[$i]['ttype']==2){
           //$disable='disabled="disabled"';
           $valueArray = array_merge(array('srNo' => ($records+$i+1),"emps" => NOT_APPLICABLE_STRING), $employeeRecordArray[$i]);
       }
       else{

	   if($employeeRecordArray[$i]['adjustType']=='' || $employeeRecordArray[$i]['adjustType'] == null) {
          $adjustTypeId1 = $employeeRecordArray[$i]['adjustType'];
	   }
	   else {
   	      $arr = explode(',',$employeeRecordArray[$i]['adjustType']);
		  $fromDate1     = $arr[0];
 		  $toDate1       = $arr[1];
  		  $adjustTypeId1 = $arr[2];
	   }
	
	  
	  if($toDate1 == $REQUEST_DATA['toDate'] ) {
		  $emp = NOT_APPLICABLE_STRING;
	   }
	   else 
	   if($adjustTypeId1 == null) {
		  $emp = NOT_APPLICABLE_STRING;
	   }
	   else
   	   if($adjustTypeId1 == '') {		 
		  $emp = NOT_APPLICABLE_STRING;
	   }
	   else {
          $emp = "<input type=\"checkbox\" name=\"chb[]\" id=\"emps".$employeeRecordArray[$i]['timeTableId']."\" value=\"".$employeeRecordArray[$i]['timeTableId'] ."\" alt=\"".$alt."\" onclick=\"generateSuggestionDiv(this.value,this.alt,this.checked)\">";
		  //$emp = $adjustTypeId1;
	   }

	   	  $emp =  $employeeRecordArray[$i]['adjustType'];


  	    $valueArray = array_merge(array('srNo' => ($k+1),"emps" => $emp), $employeeRecordArray[$i]);
	    if(trim($json_val)=='') {
		  $json_val = json_encode($valueArray);
		  }
		   else {
				$json_val .= ','.json_encode($valueArray);           
		   }
		   $k++;

	}*/
    //}
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cnt.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxGetTeacherTimeTable.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 11/13/09   Time: 6:25p
//Updated in $/LeapCC/Library/TimeTable
//Modification in code for move/copy timetable
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 11/05/09   Time: 11:50a
//Updated in $/LeapCC/Library/TimeTable
//show correct date format in div 
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 11/04/09   Time: 4:28p
//Updated in $/LeapCC/Library/TimeTable
//give link move/copy teacher time table and add new field adjustment
//type in time_table_adjustment table
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 11/02/09   Time: 11:41a
//Updated in $/LeapCC/Library/TimeTable
//send 7 for sunday as days of week
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/02/09   Time: 10:32a
//Created in $/LeapCC/Library/TimeTable
//new file for move/copy time table
//
//
?>