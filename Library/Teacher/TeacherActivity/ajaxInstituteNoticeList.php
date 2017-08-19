<?php
//f-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of institute notices
//
// Author : Dipanjan Bbhattacharjee
// Created on : (22.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(BL_PATH.'/HtmlFunctions.inc.php');
    define('MODULE','InstituteNoticeList');
    define('ACCESS','view');
    UtilityManager::ifTeacherNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
    $teacherManager = TeacherManager::getInstance();


	$htmlManager = HtmlFunctions::getInstance();
    
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

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
     /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) { 
       $filter = ' AND (n.noticeText LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                        n.noticeSubject LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%" OR 
                        d.departmentName LIKE "'.add_slashes(trim($REQUEST_DATA['searchbox'])).'%")';
    }
    $curDate=date('Y')."-".date('m')."-".date('d');
   // $filter .=" AND ( '$curDate' >= n.visibleFromDate AND '$curDate' <= n.visibleToDate)";  
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'noticeText';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $teacherManager->getTotalNotice($filter);
    $noticeRecordArray = $teacherManager->getNoticeList($filter,$limit,$orderBy);
	//print_r($noticeRecordArray);
    $cnt = count($noticeRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
    //for notice download     

       $noticeRecordArray[$i]['noticeAttachment'] = (strip_slashes($noticeRecordArray[$i]['noticeAttachment'])=='' ? NOT_APPLICABLE_STRING :'<img src="'.IMG_HTTP_PATH.'/download.gif" name="'.strip_slashes($noticeRecordArray[$i]['noticeAttachment']).'" onClick="download(this.name);" title="Download File" />'); 
       $valueArray = array_merge(array('srNo' => ($records+$i+1),
					   'noticeText'      => html_entity_decode($noticeRecordArray[$i]['noticeText']),//trim_output($htmlManager->removePHPJS($noticeRecordArray[$i]['noticeText'],"","1")),
						   'noticeSubject'   => trim_output($noticeRecordArray[$i]['noticeSubject']),//trim_output($htmlManager->removePHPJS(html_entity_decode($noticeRecordArray[$i]['noticeSubject']),"","1")),
					   'departmentName' => strip_slashes($noticeRecordArray[$i]['departmentName']),
					   'visibleFromDate' => UtilityManager::formatDate(strip_slashes($noticeRecordArray[$i]['visibleFromDate'])),
					   'visibleToDate'   => UtilityManager::formatDate(strip_slashes($noticeRecordArray[$i]['visibleToDate'])),
					   'noticeAttachment'=>$noticeRecordArray[$i]['noticeAttachment'],
					   'details'   => '<img src="'.IMG_HTTP_PATH.'/zoom.gif" border="0" alt="Details" onClick="return showNoticeDetails('.$noticeRecordArray[$i]['noticeId'].');"/>'	
					  )
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
// $History: ajaxInstituteNoticeList.php $
//
//*****************  Version 7  *****************
//User: Parveen      Date: 2/24/10    Time: 11:52a
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Attachments displaying underneath “Attachment” field in grid. (Bug. no.
//2925)
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 3/09/09    Time: 11:37
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Done bug fixing.
//Bug ids---
//00001407,00001408,00001409,
//00001419,00001420
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 10/08/09   Time: 10:52
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Done bug fixing.
//bug ids----0000983
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/08/09    Time: 11:36
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Done bug fixing.
//bug ids---
//0000971 to 0000976,0000979
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Added Role Permission Variables
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/08/08   Time: 2:06p
//Updated in $/LeapCC/Library/Teacher/TeacherActivity
//Showing Department Name
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/TeacherActivity
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 9/09/08    Time: 4:53p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 9/09/08    Time: 1:58p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/02/08    Time: 3:40p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/25/08    Time: 1:08p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/09/08    Time: 11:24a
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/22/08    Time: 6:56p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
//Initial Checkin
?>
