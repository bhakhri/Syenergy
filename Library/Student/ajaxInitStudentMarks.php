<?php

//The file contains data base functions for marks
//
// Author :Jaineesh
// Created on : 03.11.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	global $FE;
    require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','StudentDisplayMarks');
	define('ACCESS','view');
	UtilityManager::ifStudentNotLoggedIn();
	UtilityManager::headerNoCache();


	require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
    $studentInformationManager = StudentInformationManager::getInstance();

	
	// to limit records per page
	$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
	$records    = ($page-1)* RECORDS_PER_PAGE;
	$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
	$sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'testDate';

	$orderBy = " $sortField $sortOrderBy";

    $classId = $REQUEST_DATA['studyPeriodId'];

	$studentId= $sessionHandler->getSessionVariable('StudentId');
    if($studentId=='') {
      $studentId=0;  
    }
    
    $holdStudentArray = $studentInformationManager->getHoldStudentsData($studentId);
    $holdStudentClassId='';
    for($i=0;$i<count($holdStudentArray);$i++) {
       if($holdStudentArray[$i]['holdTestMarks']=='1') { 
          if($holdStudentClassId!='') {
            $holdStudentClassId .=",";  
          }  
          $holdStudentClassId .= $holdStudentArray[$i]['classId']; 
       }
    }
    
    
    
    $totalRecords =1;
	if($sessionHandler->getSessionVariable('MARKS') == 1){
		$totalRecordArray = $studentInformationManager->getTotalStudentMarks($studentId,$classId,$holdStudentClassId);
		$studentRecordArray = $studentInformationManager->getStudentMarks($studentId,$classId,$limit,$orderBy,$holdStudentClassId);
		$cnt = count($studentRecordArray);
		$totalRecords = $totalRecordArray[0]['totalRecords'];
	}


	$j=0;
	$k=0;

	for($i=0;$i<$cnt;$i++) {
		$studentRecordArray[$i]['testDate'] = UtilityManager::formatDate($studentRecordArray[$i]['testDate']);
		$marksObtained12="0.00";
		$subjectName="";
		if ($studentRecordArray[$i]['obtained'] >0 && $studentRecordArray[$i]['totalMarks'] >0) {
			$studentRecordArray[$i]['marksObtained'] = "".ROUND((($studentRecordArray[$i]['obtained']/$studentRecordArray[$i]['totalMarks'])*100),2)."";
			$marksObtained12 = $studentRecordArray[$i]['marksObtained'];
			//echo ($marksObtained12);
		}

		$subjectName1 = $studentRecordArray[$i]['subject'];
		$j=$i+1;

		if($subjectName == $subjectName1){
				$subjectName1 = "";
				 $k++;
				$j = "";
			}
		   else{
			   $studentRecordArray[$i]['subject']=$subjectName1;
			  $j=$i-$k+1;
			}

		if ($studentRecordArray[$i]['obtained']=='Not MOC'){
			$marksObtained12=NOT_APPLICABLE_STRING;
		}
		if ($studentRecordArray[$i]['obtained']=='A'){
			$marksObtained12=NOT_APPLICABLE_STRING;
		}

		$valueArray = array_merge(
			array(
						'subject' => $subjectName1,
						'percentage' => $marksObtained12,
						'srNo' => ($records+$i+1)),
						$studentRecordArray[$i]
				 );

		 if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }

    //print_r($valueArray);
   echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalRecords.'","page":"'.$page.'","info" : ['.$json_val.']}';
?>
