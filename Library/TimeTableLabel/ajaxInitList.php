<?php
//--------------------------------------------------------------------------------------------------------------
// Purpose: To store the records of TimeTable Labels in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (30.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','CreateTimeTableLabels');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/TimeTableLabelManager.inc.php");
    $timeTableLabelManager = TimeTableLabelManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       if(strtoupper(trim($REQUEST_DATA['searchbox']))=='YES' ){
           $active=1;
       }
       else if(strtoupper(trim($REQUEST_DATA['searchbox']))=='NO'){
           $active=0;
       }
       else{
           $active=-1;
       }
	
       $filter .=   $filter .=' AND (labelName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR ttl.isActive LIKE "'.$active.'" OR 
		 IF(ttl.timeTableType=1,"WEEKLY","DAILY")  LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
		 DATE_FORMAT(ttl.startDate,"%d-%b-%y") LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
		 DATE_FORMAT(ttl.endDate,"%d-%b-%y") LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';   
	
    }
    //echo  $filter;
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'labelName';
    
     $orderBy = "$sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $timeTableLabelManager->getTimeTableLabelList($filter,'');
	$count = COUNT($totalArray);
    $timeTableLabelRecordArray = $timeTableLabelManager->getTimeTableLabelList($filter,$limit,$orderBy);
    $cnt = count($timeTableLabelRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        if($timeTableLabelRecordArray[$i]['isActive']==1){
            $timeTableLabelRecordArray[$i]['isActive']='Yes';
        }
        else if($timeTableLabelRecordArray[$i]['isActive']==0){
            $timeTableLabelRecordArray[$i]['isActive']='No';
		}
/*	if($timeTableLabelRecordArray[$i]['timeTableType']==WEEKLY_TIMETABLE){
            $timeTableLabelRecordArray[$i]['timeTableType']='Weekly';
        }
        else if($timeTableLabelRecordArray[$i]['timeTableType']==DAILY_TIMETABLE){
            $timeTableLabelRecordArray[$i]['timeTableType']='Daily';
        } */
		
        $timeTableLabelRecordArray[$i]['startDate'] =strip_slashes($timeTableLabelRecordArray[$i]['startDate'])=='0000-00-00' ? NOT_APPLICABLE_STRING :        UtilityManager::formatDate($timeTableLabelRecordArray[$i]['startDate']);
        
        $timeTableLabelRecordArray[$i]['endDate'] = strip_slashes($timeTableLabelRecordArray[$i]['endDate'])=='0000-00-00' ? NOT_APPLICABLE_STRING :UtilityManager::formatDate($timeTableLabelRecordArray[$i]['endDate']);
        
        $valueArray = array_merge(array('action' => $timeTableLabelRecordArray[$i]['timeTableLabelId'] , 'srNo' => ($records+$i+1) ),$timeTableLabelRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$count.'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxInitList.php $
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 4/21/10    Time: 4:34p
//Updated in $/LeapCC/Library/TimeTableLabel
//done changes as per FCNS No. 1625
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 20/07/09   Time: 19:08
//Updated in $/LeapCC/Library/TimeTableLabel
//Done bug fixing.
//bug ids ---0000629 to 0000631
//
//*****************  Version 3  *****************
//User: Administrator Date: 12/06/09   Time: 19:25
//Updated in $/LeapCC/Library/TimeTableLabel
//Corrected display issues which are detected during user documentation
//preparation
//
//*****************  Version 2  *****************
//User: Parveen      Date: 3/10/09    Time: 2:35p
//Updated in $/LeapCC/Library/TimeTableLabel
//start and end date for fields added
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/TimeTableLabel
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 10/08/08   Time: 3:46p
//Updated in $/Leap/Source/Library/TimeTableLabel
//applied role level access
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/30/08    Time: 3:39p
//Updated in $/Leap/Source/Library/TimeTableLabel
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/30/08    Time: 3:34p
//Created in $/Leap/Source/Library/TimeTableLabel
//Created TimeTable Labels
?>
