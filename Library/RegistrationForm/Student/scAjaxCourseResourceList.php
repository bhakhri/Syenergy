<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of course resource
//
// Author : Jaineesh
// Created on : (08.11.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(BL_PATH.'/HtmlFunctions.inc.php');  
    define('MODULE','COMMON');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);    
    UtilityManager::ifStudentNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/RegistrationForm/ScStudentRegistration.inc.php");
    $studentManager = StudentRegistration::getInstance();


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

    $studentId= $sessionHandler->getSessionVariable('StudentId');
    $classId = $REQUEST_DATA['semesterDetail']; 
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
    
     /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) { 
       $filter = ' AND ( subjectCode  LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                         description  LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                         resourceUrl  LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                         resourceName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';         
    }

    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'subjectCode';
    $orderBy = " $sortField $sortOrderBy";         

    
    $totalArray          = $studentManager->getTotalStudentCourseResource($studentId,$classId,$filter);
    $resourceRecordArray = $studentManager->getStudentCourseResourceList($studentId,$classId,$filter,$orderBy,$limit);
    $cnt = count($resourceRecordArray);
	
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface
       
       //for file downloading
       $fileStr=(strip_slashes($resourceRecordArray[$i]['attachmentFile'])==-1 ? '' :'<img src="'.IMG_HTTP_PATH.'/download1.gif" name="'.strip_slashes($resourceRecordArray[$i]['attachmentFile']).'" onclick="download(this.name);" title="Download File" width="70px" height="30px" />');    
       //for url clicking
       $urlStr=(strip_slashes($resourceRecordArray[$i]['resourceUrl'])==-1 ? '' : '<a href="'.strip_slashes($resourceRecordArray[$i]['resourceUrl']).'" target="_blank">'.trim_output(strip_slashes($resourceRecordArray[$i]['resourceUrl']),40).'</a>');
       $resourceRecordArray[$i]['postedDate'] = UtilityManager::formatDate($resourceRecordArray[$i]['postedDate']);        
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
// $History: scAjaxCourseResourceList.php $
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 3/21/09    Time: 2:41p
//Updated in $/Leap/Source/Library/ScStudent
//modified course resourse through classId 
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 1/24/09    Time: 12:57p
//Updated in $/Leap/Source/Library/ScStudent
//changed date_format function in resource listing
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 1/15/09    Time: 5:57p
//Updated in $/Leap/Source/Library/ScStudent
//use student, dashboard, sms, email icons
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 12/25/08   Time: 4:30p
//Updated in $/Leap/Source/Library/ScStudent
//modified in query of course resourse for paging
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 12/23/08   Time: 5:36p
//Updated in $/Leap/Source/Library/ScStudent
//modified for attachment file in course resourse
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/23/08   Time: 3:34p
//Updated in $/Leap/Source/Library/ScStudent
//put " " in fileResource
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/05/08   Time: 5:59p
//Updated in $/Leap/Source/Library/ScStudent
//modified in comment
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/05/08   Time: 5:57p
//Created in $/Leap/Source/Library/ScStudent
//add new file for resource detail
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
