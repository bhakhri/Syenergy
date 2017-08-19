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
	//UtilityManager::ifStudentNotLoggedIn();
	//UtilityManager::headerNoCache();

	  require_once(MODEL_PATH . "/RegistrationForm/ScStudentRegistration.inc.php");
    $studentManager = StudentRegistration::getInstance();

    //$studentRecordArray = $studentInformationManager->getScStudentMarks();

	// to limit records per page    
		$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
		$records    = ($page-1)* RECORDS_PER_PAGE;
		$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
	$sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'sectionName';
	
	$orderBy = " $sortField $sortOrderBy";

    $studentId= $sessionHandler->getSessionVariable('StudentId');
	
	//require_once(MODEL_PATH."/CommonQueryManager.inc.php");    

	$classId = $REQUEST_DATA['semesterDetail'];
	
	
	$totalRecordArray = $studentManager->getTotalStudentSection($studentId,$classId,$orderBy);
	$recordCount = count($totalRecordArray);
	$totalRecords = $recordCount;
	
	$studentRecordArray = $studentManager->getStudentSection($studentId,$classId,$limit,$orderBy);

	$cnt = count($studentRecordArray);

/*	echo '<pre>';
	print_r ($studentRecordArray);
	echo '</pre>';*/

    for($i=0;$i<$cnt;$i++) {
		
		$valueArray = array_merge(
			array(
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

<?php 

//$History: scAjaxInitStudentCourseDetail.php $
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 11/28/08   Time: 11:39a
//Updated in $/Leap/Source/Library/ScStudent
//modified in sorting of resource detail
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/13/08   Time: 6:13p
//Created in $/Leap/Source/Library/ScStudent
//new file for semester wise detail
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/03/08   Time: 1:46p
//Created in $/Leap/Source/Library/ScStudent
//make new ajax file to select attendance semester wise 
//

?>
