<?php

//The file contains data base functions for marks
//
// Author :Jaineesh
// Created on : 03.11.08
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    
	global $FE;
    require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	UtilityManager::ifParentNotLoggedIn(true);      
	UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
    $studentInformationManager = StudentInformationManager::getInstance();
    
    //$studentRecordArray = $studentInformationManager->getScStudentMarks();

	// to limit records per page    
		$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
		$records    = ($page-1)* RECORDS_PER_PAGE;
		$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
	$sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subject';
	
	$orderBy = " $sortField $sortOrderBy";

    $classId = $REQUEST_DATA['studyPeriodId'];
	
	$studentId= $sessionHandler->getSessionVariable('StudentId');
	
	$totalRecordArray = $studentInformationManager->getTotalStudentMarks($studentId,$classId);
	//$recordCount = count($totalRecordArray);
	//$totalRecords = $recordCount;

	
	$studentRecordArray = $studentInformationManager->getStudentMarks($studentId,$classId,$limit,$orderBy);

   $cnt = count($studentRecordArray);

/*	echo '<pre>';
	print_r ($studentRecordArray);
	echo '</pre>';*/
	
	$j=0;
	$k=0;

	for($i=0;$i<$cnt;$i++) {
		$marksObtained12="0.00";
		$subjectName="";
		if ($studentRecordArray[$i]['obtained'] >0 && $studentRecordArray[$i]['totalMarks'] >0) {
			$studentRecordArray[$i]['marksObtained'] = ROUND((($studentRecordArray[$i]['obtained']/$studentRecordArray[$i]['totalMarks'])*100),2);
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
			$marksObtained12="-";
		}
		if ($studentRecordArray[$i]['obtained']=='A'){
			$marksObtained12="-";
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
   echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalRecordArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
	
          
 
?>

<?php 

//$History: ajaxInitStudentMarks.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/01/09    Time: 7:00p
//Updated in $/LeapCC/Library/Parent
//issue fix formatting & functionality updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/01/09    Time: 6:40p
//Created in $/LeapCC/Library/Parent
//file added
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/09/08   Time: 5:27p
//Created in $/LeapCC/Library/Student
//new files for cc
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 11/15/08   Time: 5:37p
//Updated in $/Leap/Source/Library/ScStudent
//remove one test type
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 11/15/08   Time: 12:14p
//Updated in $/Leap/Source/Library/ScStudent
//modified code for print & csv
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 11/14/08   Time: 4:06p
//Updated in $/Leap/Source/Library/ScStudent
//modification in name
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/13/08   Time: 6:12p
//Created in $/Leap/Source/Library/ScStudent
//make new file for semester wise detail
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/03/08   Time: 1:46p
//Created in $/Leap/Source/Library/ScStudent
//make new ajax file to select attendance semester wise 
//

?>
