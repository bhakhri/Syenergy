<?php
//-------------------------------------------------------------------------------------
// Purpose: To show student groups in array from the database, pagination functionality
//
// Author : Parveen Sharma
// Created on : 10-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ParentStudentInfo');
    define('ACCESS','view');
    UtilityManager::ifParentNotLoggedIn(true);  
    UtilityManager::headerNoCache();

     require_once(MODEL_PATH . "/Parent/ParentManager.inc.php");
     $parentManager = ParentManager::getInstance();

    
    // to limit records per page 
	
	$studentId  =  $sessionHandler->getSessionVariable('StudentId'); 
	$classId  = $REQUEST_DATA['rClassId'];
    // to limit records per page    
    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'groupName';
    
    $orderBy = " $sortField $sortOrderBy";
    //$sectionCondtion = " AND sct.studentId = $studentId";
    
    $totalArray = $parentManager->getTotalStudentGroup($studentId,$classId,$orderBy);
    $studentSectionArray = $parentManager->getStudentGroup($studentId,$classId,$limit,$orderBy);
    $cnt = count($studentSectionArray);
    
    // $studentSectionArray = $studentManager->getStudentGroups($studentId,$classId,$orderBy,$limit);
    // $totalArray = $studentManager->getStudentGroupCount($studentId,$classId);
    // $cnt = count($studentSectionArray);
    
    for($i=0;$i<$cnt;$i++) {
       $valueArray = array_merge(array('srNo' => ($records+$i+1) ),$studentSectionArray[$i]);
       if(trim($json_val)=='') {
          $json_val = json_encode($valueArray);
       }
       else {
          $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}';

    
// $History: ajaxGroup.php $
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 10/14/09   Time: 5:53p
//Updated in $/LeapCC/Library/Parent
//updated access rights
//
//*****************  Version 5  *****************
//User: Parveen      Date: 9/24/09    Time: 10:57a
//Updated in $/LeapCC/Library/Parent
//alignment & condition format updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 9/02/09    Time: 2:15p
//Updated in $/LeapCC/Library/Parent
//attendance, course Info validation & format updated 
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/23/08   Time: 1:55p
//Updated in $/LeapCC/Library/Parent
//file updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/10/08   Time: 1:17p
//Updated in $/LeapCC/Library/Parent
//issue fix
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/10/08   Time: 12:11p
//Created in $/LeapCC/Library/Parent
//file added
//
?>