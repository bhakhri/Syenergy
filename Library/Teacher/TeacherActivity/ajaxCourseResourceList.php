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
    define('MODULE','CourseResourceMaster');
    define('ACCESS','view');
    UtilityManager::ifTeacherNotLoggedIn(true);
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
       $filter = ' AND (subject.subjectCode LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR description LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR DATE_FORMAT(postedDate,"%d-%b-%y") LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR resourceUrl LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR resourceName LIKE "%'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subject';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
 
	//$totalArray          = $teacherManager->getTotalResource($filter);
	$totalArray = $teacherManager->getResourceList($filter,'',$orderBy);
    $resourceRecordArray = $teacherManager->getResourceList($filter,$limit,$orderBy);
    $cnt = count($resourceRecordArray);
    
    $permissionsArray=$sessionHandler->getSessionVariable('CourseResourceMaster');
    $editPermission=0;
    $deletePermission=0;
    if($permissionsArray['edit']==1){
        $editPermission=1;
    }
    if($permissionsArray['delete']==1){
        $deletePermission=1;
    }
    
    for($i=0;$i<$cnt;$i++) {
        $actionString=NOT_APPLICABLE_STRING.'&nbsp;';
        $resourceRecordArray[$i]['postedDate'] = UtilityManager::formatDate($resourceRecordArray[$i]['postedDate']); 
        if($editPermission==1){
            $actionString='<a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif" border="0" alt="Edit" onclick="editWindow('.$resourceRecordArray[$i]['courseResourceId'].');return false;"></a>';
        }
        if($deletePermission==1){
            $actionString .='<a href="#" title="Delete"><img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" alt="Delete" onclick="deleteResource('.$resourceRecordArray[$i]['courseResourceId'].');return false;"></a>';
        }
        
       //for file downloading
        $fileStr=(strip_slashes($resourceRecordArray[$i]['attachmentFile'])==-1 ? '' :'<img src="'.IMG_HTTP_PATH.'/download.gif" name="'.strip_slashes($resourceRecordArray[$i]['attachmentFile']).'" onclick="download(this.name);" title="Download File" />');    
        //for url clicking
        $urlStr=(strip_slashes($resourceRecordArray[$i]['resourceUrl'])==-1 ? '' : '<a href="'.strip_slashes($resourceRecordArray[$i]['resourceUrl']).'" target="_blank">'.trim_output(strip_slashes($resourceRecordArray[$i]['resourceUrl']),40).'</a>');
                     
       $valueArray = array_merge(
                                 array(
                                         'srNo' => ($records+$i+1),
                                          'action' => $resourceRecordArray[$i]['courseResourceId'],
                                          'resourceLink'=>$urlStr,
                                          'attachmentLink'=>$fileStr,
                                          'actionString'=>$actionString
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
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.count($totalArray).'","page":"'.$page.'","info" : ['.$json_val.']}'; 
// for VSS
// $History: ajaxCourseResourceList.php $
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 12/04/08   Time: 11:20a
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//Created "Upload Resource" Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/05/08   Time: 2:44p
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Created CourseResource Module
?>
