<?php
//-------------------------------------------------------
// Purpose: To store the records of student result from the database, pagination and search
// functionality
//
// Author : Rajeev Aggarwal
// Created on : 22-12-2008
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','ParentStudentInfo');
    define('ACCESS','view');
    UtilityManager::ifParentNotLoggedIn(true);  
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
    
	function trim_output($str,$maxlength='250',$rep='...'){
		
		$ret=chunk_split($str,60);

		if(strlen($ret) > $maxlength){

			$ret=substr($ret,0,$maxlength).$rep; 
		}
		return $ret;  
	}
    $studentId  =  $sessionHandler->getSessionVariable('StudentId');
	$classId    =  $REQUEST_DATA['rClassId'];
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'offenseName';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray          = $studentManager->getTotalStudentOffence($studentId,$classId);
	 
    $resourceRecordArray = $studentManager->getStudentOffenceList($studentId,$classId,$orderBy,$limit);
    $cnt = count($resourceRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface
       
       $valueArray = array_merge(array('srNo' => ($records+$i+1)),$resourceRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// $History: ajaxStudentOffence.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 10/14/09   Time: 5:53p
//Updated in $/LeapCC/Library/Parent
//updated access rights
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/23/08   Time: 1:55p
//Updated in $/LeapCC/Library/Parent
//file updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/23/08   Time: 12:17p
//Created in $/LeapCC/Library/Parent
//inital checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/22/08   Time: 5:53p
//Created in $/LeapCC/Library/Student
//Intial checkin
?>