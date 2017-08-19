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
    define('MODULE','StudentInfoDetail');
    define('ACCESS','view');
	UtilityManager::ifStudentNotLoggedIn(true);
	UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/Student/StudentInformationManager.inc.php");
    $studentInformationManager = StudentInformationManager::getInstance();

	
    //$studentRecordArray = $studentInformationManager->getScStudentMarks();

	// to limit records per page    
		$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
		$records    = ($page-1)* RECORDS_PER_PAGE;
		$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
	$sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'periodName';
	
	$orderBy = " $sortField $sortOrderBy";

    $studentId= $sessionHandler->getSessionVariable('StudentId');
	
	require_once(MODEL_PATH."/CommonQueryManager.inc.php");    

	$classId = $REQUEST_DATA['semesterDetail'];
	
	$studentRecordArray = $studentInformationManager->getStudentFinalResultListAdv($studentId,$classId,$orderBy,'');
	$totalRecords = COUNT($studentRecordArray);
	
	$studentRecordArray = $studentInformationManager->getStudentFinalResultListAdv($studentId,$classId,$orderBy,$limit);
	$cnt = count($studentRecordArray);

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

//$History: ajaxInitStudentTransferredMarks.php $
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 2/03/10    Time: 3:13p
//Updated in $/LeapCC/Library/Student
//modification in code and query to show final result of student
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 10/22/09   Time: 4:02p
//Updated in $/LeapCC/Library/Student
//updated access defines
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 10/21/09   Time: 12:29p
//Updated in $/LeapCC/Library/Student
//added access defines
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/09/08   Time: 5:27p
//Created in $/LeapCC/Library/Student
//new files for cc
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/21/08   Time: 1:27p
//Created in $/Leap/Source/Library/ScStudent
//used for to get student transferred marks, sorting & paging
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
