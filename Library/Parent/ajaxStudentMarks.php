<?php
//---=======================================----------------------------------------------------
// Purpose: To show the records of student marks from the database, pagination and search

// Author : Parveen Sharma
// Created on : 12-11-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifParentNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

    // to limit records per page

	$studentId  =  $sessionHandler->getSessionVariable('StudentId');
	$classId  = $REQUEST_DATA['rClassId'];


    // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////
   /*
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (ct.cityName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR ct.cityCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
    }
   */
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studyPeriod';


    $orderBy = " $sortField $sortOrderBy";
    //$sectionCondtion = " AND sct.studentId = $studentId";
	$cnt1 = 1;
	if($sessionHandler->getSessionVariable('MARKS') == 1){
		$totalArray = $studentManager->getStudentMarks($studentId,$classId,$orderBy,'');
		$studentSectionArray = $studentManager->getStudentMarks($studentId,$classId,$orderBy,$limit);
		$cnt1 = count($totalArray);
		$cnt = count($studentSectionArray);
	}
    for($i=0;$i<$cnt;$i++) {

       $studentSectionArray[$i]['testDate'] = UtilityManager::formatDate($studentSectionArray[$i]['testDate']);
       $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$studentSectionArray[$i]);

       if(trim($json_val)==''){

			$json_val = json_encode($valueArray);
       }
       else{

			$json_val .= ','.json_encode($valueArray);
       }
    }
   echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cnt1.'","page":"'.$page.'","info" : ['.$json_val.']}';

// $History: ajaxStudentMarks.php $
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 10/15/09   Time: 5:48p
//Updated in $/LeapCC/Library/Parent
//added access rights
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 10/14/09   Time: 5:53p
//Updated in $/LeapCC/Library/Parent
//updated access rights
//
//*****************  Version 4  *****************
//User: Parveen      Date: 7/02/09    Time: 5:43p
//Updated in $/LeapCC/Library/Parent
//formatting, alignment & date formatting settings
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/23/08   Time: 1:55p
//Updated in $/LeapCC/Library/Parent
//file updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/10/08   Time: 1:42p
//Updated in $/LeapCC/Library/Parent
//parent module login settings
//

?>
