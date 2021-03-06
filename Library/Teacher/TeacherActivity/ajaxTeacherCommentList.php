<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To store the records of students in array from the database, pagination and search, delete 
// functionality
//
// Author : Dipanjan Bbhattacharjee
// Created on : (21.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(BL_PATH.'/HtmlFunctions.inc.php');
    define('MODULE','ListTeacherCommentMaster');
    define('ACCESS','view');    
    UtilityManager::ifTeacherNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();

    /////////////////////////
//
//-------------------------------------------------------------------------------------------------------------
//Purpose:To trim output
//Author:Dipanjan Bhattacharjee
//Date:12.08.2008    
//
//-------------------------------------------------------------------------------------------------------------
function trim_output($value,$limit){
 if(strlen($value) > $limit){
  $ret=substr($value,0,$limit)."...";
 }
else{
    $ret=$value;
} 
 return $ret;
}    
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE tc.subject LIKE "'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%"'; 
    }           
    
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'comments';
    
     $orderBy = " tc.$sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $teacherManager->getTotalTeacherComment($filter);
    $teacherCommentRecordArray = $teacherManager->getTeacherCommentList($filter,$limit,$orderBy);
    $cnt = count($teacherCommentRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface
       $valueArray = array('srNo' => ($records+$i+1),
       'comments' => trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($teacherCommentRecordArray[$i]['comments'])),200),
       'subject' => trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($teacherCommentRecordArray[$i]['subject'])),100),
       'postedOn'=>UtilityManager::formatDate(strip_slashes($teacherCommentRecordArray[$i]['postedOn'])),
       'details'=>"<a class='redColor' style='cursor:pointer' onclick=editWindow(".$teacherCommentRecordArray[$i]['commentId'].",'CommentDiv',710,500);return false;>Details</a>");

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }

    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
    
// for VSS
// $History: ajaxTeacherCommentList.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/09/08    Time: 1:58p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/13/08    Time: 2:38p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/09/08    Time: 2:42p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/22/08    Time: 6:56p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
//Initial Checkin
?>
