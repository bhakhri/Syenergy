<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of course resource
//
// Author : Dipanjan Bbhattacharjee
// Created on : (04.11.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(BL_PATH.'/HtmlFunctions.inc.php');  
    define('MODULE','SearchStudentDisplay');
    define('ACCESS','view');
    UtilityManager::ifTeacherNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();
    
    /////////////////////////
    
//---------------------------------------------------------------------------------------------------------------  
//purpose: to trim a string and output str.. etc
//Author:Dipanjan Bhattcharjee
//Date:2.09.2008
//$str=input string,$maxlenght:no of characters to show,$rep:what to display in place of truncated string
//$mode=1 : no split after 30 chars,mode=2:split after 30 characters
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------  
function trim_output($str,$maxlength='250',$rep='...'){
   $ret=chunk_split($str,60);

   if(strlen($ret) > $maxlength){
      $ret=substr($ret,0,$maxlength).$rep; 
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
       $filter = ' AND ( description LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR resourceUrl LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR resourceName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';         
    }

    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subject';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    if(trim($sessionHandler->getSessionVariable('rStudentId'))!=''){
     $totalArray          = $teacherManager->getTotalStudentCourseResource($sessionHandler->getSessionVariable('rStudentId'),$REQUEST_DATA['rClassId'],$filter);
     $resourceRecordArray = $teacherManager->getStudentCourseResourceList($sessionHandler->getSessionVariable('rStudentId'),$REQUEST_DATA['rClassId'],$filter,$orderBy,$limit);
    }
    $cnt = count($resourceRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface
       
       //for file downloading
        $fileStr=(strip_slashes($resourceRecordArray[$i]['attachmentFile'])==-1 ? NOT_APPLICABLE_STRING :'<img src="'.IMG_HTTP_PATH.'/download.gif" name="'.strip_slashes($resourceRecordArray[$i]['attachmentFile']).'" onclick="download(this.name);" title="Download File" />');    
        //for url clicking
        $urlStr=(strip_slashes($resourceRecordArray[$i]['resourceUrl'])==-1 ? NOT_APPLICABLE_STRING : '<a href="'.strip_slashes($resourceRecordArray[$i]['resourceUrl']).'" target="_blank">'.trim_output(strip_slashes($resourceRecordArray[$i]['resourceUrl']),40).'</a>');
        
        $resourceRecordArray[$i]['postedDate']=UtilityManager::formatDate($resourceRecordArray[$i]['postedDate']);
                     
       $valueArray = array_merge(
                                 array(
                                          'srNo' => ($records+$i+1),
                                          'resourceLink'=>$urlStr,
                                          'attachmentLink'=>$fileStr,
                                       ), 
                                 $resourceRecordArray[$i] 
                                );

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
// for VSS
// $History: ajaxCourseResourceList.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 3/11/09    Time: 12:30
//Updated in $/LeapCC/Library/Teacher/StudentActivity
//Fixed Query Error
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/10/09    Time: 14:19
//Updated in $/LeapCC/Library/Teacher/StudentActivity
//Done bug fixing.
//Bug ids---
//00001621,00001644,00001645,00001646,
//00001647,00001711
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 12/05/08   Time: 1:37p
//Created in $/LeapCC/Library/Teacher/StudentActivity
//Corrected Student Tabs
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/12/08   Time: 11:58a
//Updated in $/Leap/Source/Library/Teacher/ScStudentActivity
//Added Fully Ajax Enabled Student Tabs in Teacher Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/11/08   Time: 10:40a
//Created in $/Leap/Source/Library/Teacher/ScStudentActivity
//Added Resource Paging and sorting in student tab view
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/05/08   Time: 2:44p
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Created CourseResource Module
?>
