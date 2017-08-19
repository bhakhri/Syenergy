<?php
//--------------------------------------------------------------------------------------------
// Purpose: To show the records of student resources from the database, pagination and search 
// functionality
//
// Author : Parveen Sharma
// Created on : 15-12-2008
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
	$classId  = $REQUEST_DATA['rClassId'];
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
     /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) { 
       $filter = ' AND ( description LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR resourceUrl LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR resourceName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';         
    }

    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'Desc';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'postedDate';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray          = $studentManager->getTotalStudentCourseResource($studentId,$classId,$filter);
    $resourceRecordArray = $studentManager->getStudentCourseResourceList($studentId,$classId,$filter,$orderBy,$limit);
    $cnt = count($resourceRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface
       
       //for file downloading
        $fileStr=(strip_slashes($resourceRecordArray[$i]['attachmentFile'])==-1 ? '' :'<img src="'.IMG_HTTP_PATH.'/download.gif" name="'.strip_slashes($resourceRecordArray[$i]['attachmentFile']).'" onclick="download(this.name);" title="Download File" />');    
        //for url clicking
        $urlStr=(strip_slashes($resourceRecordArray[$i]['resourceUrl'])==-1 ? '' : '<a href="'.strip_slashes($resourceRecordArray[$i]['resourceUrl']).'" target="_blank">'.trim_output(strip_slashes($resourceRecordArray[$i]['resourceUrl']),40).'</a>');
                     
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
    

// $History: ajaxStudentResource.php $
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
//User: Parveen      Date: 12/16/08   Time: 9:58a
//Created in $/LeapCC/Library/Parent
//Intial Checkin
//

?>