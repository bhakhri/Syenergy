<?php
//-------------------------------------------------------
// Author : Parveen Sharma
// Created on : 03-12-2008
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','CoursewiseResourceReport');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn(true);
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
    $subjectId  = $REQUEST_DATA['subjectId'];
    
    if($subjectId=='') {
     $subjectId=0;   
    }
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    

//    if($subjectId != "All" ) 
//       $filter = " AND subjectCode = ".$subjectId; 
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'postedDate';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray          = $studentManager->getTotalCourseResource($subjectId);
    $resourceRecordArray = $studentManager->getCourseResourceList($subjectId,$orderBy,$limit);
    $cnt = count($resourceRecordArray);   
	for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface
       
       //for file downloading
        $fileStr=(strip_slashes($resourceRecordArray[$i]['attachmentFile'])== -1 ? NOT_APPLICABLE_STRING :'<img src="'.IMG_HTTP_PATH.'/download.gif" name="'.strip_slashes($resourceRecordArray[$i]['attachmentFile']).'" onclick="download(this.name);" title="Download File" />');    
        //for url clicking
        $urlStr=(strip_slashes($resourceRecordArray[$i]['resourceUrl'])== '-1' ? NOT_APPLICABLE_STRING : '<a href="'.strip_slashes($resourceRecordArray[$i]['resourceUrl']).'" target="_blank" class="whiteClass">'.trim_output(strip_slashes($resourceRecordArray[$i]['resourceUrl']),40).'</a>');
         $resourceRecordArray[$i]['postedDate']=UtilityManager::formatDate($resourceRecordArray[$i]['postedDate']);            
       $valueArray = array_merge(
                                 array(
                                          'srNo' => ($records+$i+1),
                                          'description'  => trim_output(strip_slashes($resourceRecordArray[$i]['description']),100), 
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
    

// $History: scAjaxCoursewiseResource.php $
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 4/02/10    Time: 12:55
//Updated in $/LeapCC/Library/StudentReports
//Done bug fixing.
//Bug ids---
//0002528,0002303,0002193,0001928,
//0001922,0001863,0001763,0001238,
//0001229,0001894,0002143
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 12/05/09   Time: 5:18p
//Updated in $/LeapCC/Library/StudentReports
//RESOLVED ISSUES 2196,2195,2194,2191,2192
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/17/09    Time: 12:30p
//Updated in $/LeapCC/Library/StudentReports
//role permission added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/17/09    Time: 12:08p
//Created in $/LeapCC/Library/StudentReports
//initial checkin
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 31/01/09   Time: 15:39
//Created in $/SnS/Library/StudentReports
//Added "Coursewise resource report" module
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/04/08   Time: 10:40a
//Updated in $/Leap/Source/Library/ScStudentReports
//html tags & formatting settings
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/03/08   Time: 4:18p
//Created in $/Leap/Source/Library/ScStudentReports
//Coursewise Resource file added
//

?>
