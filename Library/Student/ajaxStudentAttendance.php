<?php

//The file contains functions to get student attendance
//
// Author :Jaineesh
// Created on : 04.12.08
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    
    require_once($FE . "/Library/common.inc.php"); //for studentId 
    require_once(MODEL_PATH."/CommonQueryManager.inc.php");    
	require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','StudentDisplayAttendance');
    define('ACCESS','view');
	UtilityManager::ifStudentNotLoggedIn(true);
	UtilityManager::headerNoCache();
	
	require_once(MODEL_PATH."/CommonQueryManager.inc.php");    
	$commonQueryManager = CommonQueryManager::getInstance(); 
    
    global $sessionHandler;
    
	$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
	$records    = ($page-1)* RECORDS_PER_PAGE;
	$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
		
	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
	$sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'className';
		
	$orderBy = " $sortField $sortOrderBy";  

    $studentId= trim($sessionHandler->getSessionVariable('StudentId'));
	$attendance = trim($sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD'));
	$fromDate = trim($REQUEST_DATA['startDate']);
	$toDate = trim($REQUEST_DATA['toDate']);
	$classId = trim($REQUEST_DATA['studyPeriodId']);
    
    if($classId=='') {
      $classId = 0;  
    }

    if($studentId=='') {
      $studentId = 0;  
    }
    
	//$classIdArray = CommonQueryManager::getInstance()->getStudyPeriodData($studentId);
	//$classId = $classIdArray[0]['classId'];

	if($fromDate) {
			$where = " AND fromDate BETWEEN '$fromDate' AND '$toDate'";
	}
	if($toDate) {
			$where .= " AND toDate BETWEEN '$fromDate' AND '$toDate'";
	}

	if($where != "") {
      $where .= " AND su.hasAttendance = 1 ";
      if($REQUEST_DATA['consolidatedView']==0){ //if consolidated display is required
         $totalRecordArray = CommonQueryManager::getInstance()->countConsolidatedStudentAttendance($studentId,$classId,$where);
         $totalRecord = count($totalRecordArray);
         $studentInformationArray = CommonQueryManager::getInstance()->getConsolidatedStudentAttendance($studentId,$classId,$limit,$where,"ORDER BY $orderBy");            
      }
     else{ //if group wise display is required
         $totalRecordArray = CommonQueryManager::getInstance()->countStudentAttendance($studentId,$classId,$where);
         $totalRecord = count($totalRecordArray);
         $studentInformationArray = CommonQueryManager::getInstance()->getStudentAttendance($studentId,$classId,$limit,$where,"$orderBy");
     }
    }
    else {
      $where .= " AND su.hasAttendance = 1 ";
     if($REQUEST_DATA['consolidatedView']==0){ //if consolidated display is required 
         $totalRecordArray = CommonQueryManager::getInstance()->countConsolidatedStudentAttendance($studentId,$classId,$where);
         $totalRecord = count($totalRecordArray);
         $studentInformationArray = CommonQueryManager::getInstance()->getConsolidatedStudentAttendance($studentId,$classId,$limit,$where,"ORDER BY $orderBy");
     }
     else{//if group wise display is required 
         $totalRecordArray = CommonQueryManager::getInstance()->countStudentAttendance($studentId,$classId,$where);
         $totalRecord = count($totalRecordArray);
         $studentInformationArray = CommonQueryManager::getInstance()->getStudentAttendance($studentId,$classId,$limit,$where,"$orderBy");
     }
    }

	$cnt = count($studentInformationArray);
    
    for($i=0;$i<$cnt;$i++) {
		if ($studentInformationArray[$i]['studentName'] != '-1') {
			$studentInformationArray[$i]['Percentage'] = "0.00";
		}
		else {
			$studentInformationArray[$i]['Percentage'] = NOT_APPLICABLE_STRING;
			$studentInformationArray[$i]['Percentage'] = "<span style='color:#0081D7'><strong>".$studentInformationArray[$i]['Percentage']."</strong></span>";
		}


		if($studentInformationArray[$i]['studentName'] != '-1') {
			$studentInformationArray[$i]['fromDate'] = UtilityManager::formatDate($studentInformationArray[$i]['fromDate']);	
			$studentInformationArray[$i]['toDate'] = UtilityManager::formatDate($studentInformationArray[$i]['toDate']);
		}
        
		if($studentInformationArray[$i]['delivered'] > 0 ) {
			if ($studentInformationArray[$i]['dutyLeave'] != '') {
				$studentInformationArray[$i]['attended1'] = "".$studentInformationArray[$i]['attended'] + $studentInformationArray[$i]['dutyLeave']."";
				$studentInformationArray[$i]['Percentage']="".ROUND((($studentInformationArray[$i]['attended1'] /  $studentInformationArray[$i]['delivered'])*100),2)."";
			}
			else {
				$studentInformationArray[$i]['Percentage']="".ROUND((($studentInformationArray[$i]['attended'] /  $studentInformationArray[$i]['delivered'])*100),2)."";
			}
			
		}

		if ($studentInformationArray[$i]['dutyLeave'] == 'null' || $studentInformationArray[$i]['dutyLeave'] == '') {
			$studentInformationArray[$i]['dutyLeave'] = NOT_APPLICABLE_STRING;
		}
		else {
			$studentInformationArray[$i]['dutyLeave'] = "<span style='color:#000000'><strong>".$studentInformationArray[$i]['dutyLeave']."</strong></span>";
		}
		
        // add subjectId in actionId to populate edit/delete icons in User Interface   
		if ($studentInformationArray[$i]['Percentage'] <= $attendance ) {
			if ($studentInformationArray[$i]['studentName'] != '-1') {
				$percentage = "<span class='padding_right'><span style='color:red'>".$studentInformationArray[$i]['Percentage']."</span></span>";
			}
			else {
				$percentage = "<span class='padding_right'><span style='color:black'>".$studentInformationArray[$i]['Percentage']."</span></span>";
			}
		}
		else {
			$percentage = $studentInformationArray[$i]['Percentage'];
		}
		
        $valueArray = array_merge(array('per' => $percentage,'srNo' => ($records+$i+1) ),$studentInformationArray[$i]);
        
         if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
	//print_r($studentInformationArray);
   echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalRecord.'","page":"'.$page.'","info" : ['.$json_val.']}';
	
          
 
?>

<?php 

//$History: ajaxStudentAttendance.php $
//
//*****************  Version 12  *****************
//User: Parveen      Date: 3/10/10    Time: 10:33a
//Updated in $/LeapCC/Library/Student
//query format updated
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 11/26/09   Time: 11:03a
//Updated in $/LeapCC/Library/Student
//show leave taken in diffent color in attendance tab
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 11/23/09   Time: 3:24p
//Updated in $/LeapCC/Library/Student
//Show duty in attendance during student login
//
//*****************  Version 9  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 12:29p
//Updated in $/LeapCC/Library/Student
//added access defines
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 7/10/09    Time: 15:52
//Updated in $/LeapCC/Library/Student
//Added Detailed(group wise) and Consolidated view(irrespective of groups
//of a subject) of attendance records in student tabs .Now user can
//choose whether to view complete or just consolidated attendance of a
//student.This is also done in print & export to excel version of these
//reports as applicable.
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/28/09    Time: 10:37a
//Updated in $/LeapCC/Library/Student
//fixed bugs 
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/26/09    Time: 10:22a
//Updated in $/LeapCC/Library/Student
//fixed bug nos.0001235, 0001233, 0001230, 0001234 and put time table in
//reports
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/25/09    Time: 10:27a
//Updated in $/LeapCC/Library/Student
//fixed bug nos. 0001218, 0001217
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/13/09    Time: 6:28p
//Updated in $/LeapCC/Library/Student
//modified for left alignment and giving cell padding, cell spacing 1
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/09/09    Time: 11:48a
//Updated in $/LeapCC/Library/Student
//modification in width
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/25/08   Time: 4:39p
//Updated in $/LeapCC/Library/Student
//modified code for attendance threshold
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/09/08   Time: 5:28p
//Created in $/LeapCC/Library/Student
//new file for cc student attendance
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 11/14/08   Time: 4:03p
//Updated in $/Leap/Source/Library/ScStudent
//modification in name
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 11/14/08   Time: 4:02p
//Updated in $/Leap/Source/Library/ScStudent
//modification in name
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 11/13/08   Time: 5:54p
//Updated in $/Leap/Source/Library/ScStudent
//make ajax function for semester wise detail
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 11/11/08   Time: 12:38p
//Updated in $/Leap/Source/Library/ScStudent
//modified for study period
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/03/08   Time: 1:46p
//Created in $/Leap/Source/Library/ScStudent
//make new ajax file to select attendance semester wise 
//

?>
