<?php
//-------------------------------------------------------
// Purpose: To store the records of Bank in array from the database, pagination and search, delete
// functionality
//
// Author : Ajinder Singh
// Created on : 23-July-2008
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','CreateClass');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/ClassesManager.inc.php");
	$classManager = ClassesManager::getInstance();

    /////////////////////////

       // to limit records per page
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
   //$filter = '  (degreeCode LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR batchName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR branchName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR degreeDuration LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR periodName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';
//  $filter = '  (degreeCode LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR batchName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR branchName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR studentCount LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR degreeDuration LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR periodName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';

	  $filter = '  (degreeCode LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR batchName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR branchName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR studentCount LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR degreeDuration LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR periodName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
     $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'degreeCode';

     $orderBy = "$sortField $sortOrderBy";

    $classesRecordArray = $classManager->getClassesList($filter,$limit,$orderBy);
    $totalArray = $classManager->getClassesList($filter);
	$totalRecords = count($totalArray);
    //$classesRecordArray = $classManager->getClassesList($filter,$limit,$orderBy);

    $cnt = count($classesRecordArray);

    for($i=0;$i<$cnt;$i++) {
        // add bankId in actionId to populate edit/delete icons in User Interface
        $valueArray = array_merge(array('action' => $classesRecordArray[$i]['classId'] , 'srNo' => ($records+$i+1) ),$classesRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalRecords.'","page":"'.$page.'","info" : ['.$json_val.']}';

// $History: ajaxInitList.php $
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/05/09    Time: 3:49p
//Updated in $/LeapCC/Library/Classes
//fixed bugs 901, 902
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 7/23/09    Time: 3:46p
//Updated in $/LeapCC/Library/Classes
//done the changes to fix following bug no.s:
//1. 642
//2. 625
//3. 601
//4. 573
//5. 572
//6. 570
//7. 569
//8. 301
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Classes
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/10/08   Time: 12:10p
//Updated in $/Leap/Source/Library/Classes
//add define access in module
//
//*****************  Version 1  *****************
//User: Admin        Date: 8/05/08    Time: 6:40p
//Created in $/Leap/Source/Library/Classes
//file added for listing
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 7/23/08    Time: 12:41p
//Created in $/Leap/Source/Library/classes
//File created for Bank Master


?>
