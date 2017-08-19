<?php
//-------------------------------------------------------
// Purpose: To store the records of Subject in array from the database, pagination and search, delete 
// functionality
//
// Author : Arvind Singh Rawat
// Created on : (30.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','Subject');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    

    require_once(MODEL_PATH . "/SubjectManager.inc.php");
    $subjectManager = SubjectManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       
       $hasA ='';
       $hasM ='';
       if(strtoupper(add_slashes($REQUEST_DATA['searchbox']))=='YES') {  
          $hasA = " OR hasAttendance = 1 ";
          $hasM = " OR hasMarks = 1 ";
       }
       else
       if(strtoupper(add_slashes($REQUEST_DATA['searchbox']))=='NO') {  
          $hasA = " OR hasAttendance = 0 ";
          $hasM = " OR hasMarks = 0 ";
       }
       
     $filter .= ' AND (sub.subjectName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR sub.subjectCode LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR sub.subjectAbbreviation LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR categoryName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR subjectTypeName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%"'.$hasA.' '.$hasM.')';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subjectName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $subjectManager->getTotalSubject($filter);
	
    $subjectRecordArray = $subjectManager->getSubjectList($filter,$limit,$orderBy);
	
	
    $cnt = count($subjectRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add subjectId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $subjectRecordArray[$i]['subjectId'] , 'srNo' => ($records+$i+1) ),$subjectRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    

// $History: ajaxInitList.php $
//
//*****************  Version 7  *****************
//User: Parveen      Date: 10/06/09   Time: 11:26a
//Updated in $/LeapCC/Library/Subject
//search condition checks updated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 9/23/09    Time: 3:27p
//Updated in $/LeapCC/Library/Subject
//hasAttendance, hasMarks filed added
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/28/09    Time: 3:16p
//Updated in $/LeapCC/Library/Subject
//search condition updated
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/10/09    Time: 3:50p
//Updated in $/LeapCC/Library/Subject
//Gurkeerat: updated access defines
//
//*****************  Version 3  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Library/Subject
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/20/09    Time: 1:55p
//Updated in $/LeapCC/Library/Subject
//new enhancement categoryId (link subject_category table) new field
//added 
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Subject
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/30/08    Time: 4:42p
//Created in $/Leap/Source/Library/Subject
//added a new file responsible for table listing through ajax

  
?>
