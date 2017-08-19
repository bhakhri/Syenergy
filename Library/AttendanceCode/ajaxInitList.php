<?php
//-------------------------------------------------------
// Purpose: To store the records of AttendanceCOde in array from the database, pagination and search, delete 
// functionality
//
// Author : Arvind Singh Rawat
// Created on : (30.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
     define('MODULE','AttendanceCodesMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/AttendanceCodeManager.inc.php");
    $attendanceCodeManager = AttendanceCodeManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
      if(strtoupper(add_slashes($REQUEST_DATA['searchbox']))=='YES')  {
        $filter = ' WHERE (attendanceCodeName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR attendanceCode LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR attendanceCodePercentage LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR showInLeaveType = 1 )';
      }
      else
      if(strtoupper(add_slashes($REQUEST_DATA['searchbox']))=='NO')  {
        $filter = ' WHERE (attendanceCodeName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR attendanceCode LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR attendanceCodePercentage LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR showInLeaveType = 0 )';                  
      }
      else {
        $filter = ' WHERE (attendanceCodeName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR attendanceCode LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR attendanceCodePercentage LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';                  
      }        
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'attendanceCodeName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $attendanceCodeManager->getTotalAttendanceCode($filter);
    $attendanceCodeRecordArray = $attendanceCodeManager->getAttendanceCodeList($filter,$limit,$orderBy);
	
    $cnt = count($attendanceCodeRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add attendanceCodeId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $attendanceCodeRecordArray[$i]['attendanceCodeId'] , 'srNo' => ($records+$i+1) ),$attendanceCodeRecordArray[$i]);

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
//*****************  Version 2  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Library/AttendanceCode
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/AttendanceCode
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/06/08   Time: 10:19a
//Updated in $/Leap/Source/Library/AttendanceCode
//Added Module, Access
//
//*****************  Version 2  *****************
//User: Arvind       Date: 7/21/08    Time: 4:04p
//Updated in $/Leap/Source/Library/AttendanceCode
//Added a attendanceCodePercentage in $filter
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/30/08    Time: 4:04p
//Created in $/Leap/Source/Library/AttendanceCode
//added a new file for ajax table listing and pagination

  
?>
