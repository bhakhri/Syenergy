<?php
//-----------------------------------------------------------------------------------------------------------
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
    
    UtilityManager::ifTeacherNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
    $teacherManager = ScTeacherManager::getInstance();
    
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
       $filter = ' AND (n.noticeText LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR n.noticeSubject LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $curDate=date('Y')."-".date('m')."-".date('d');
    $filter .=" AND ( '$curDate' >= n.visibleFromDate AND '$curDate' <= n.visibleToDate)";  
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'noticeText';
    
     $orderBy = " n.$sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $teacherManager->getTotalNotice($filter);
    $noticeRecordArray = $teacherManager->getNoticeList($filter,$limit,$orderBy);
    $cnt = count($noticeRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       $attactment=strip_slashes($noticeRecordArray[$i]['noticeAttachment']);
    $att="'$attactment'";
    $pic=split('_-',strip_slashes($noticeRecordArray[$i]['noticeAttachment'])); 
    if(isset($pic[1])){ 
        
       $noticeRecordArray[$i]['noticeAttachment']='<img src="'.IMG_HTTP_PATH.'/download.gif" title="'.$pic[1].'" onclick="download(
    '. $att.')" />';  
    } 
    else{
        $noticeRecordArray[$i]['noticeAttachment']='';
    }
        // add stateId in actionId to populate edit/delete icons in User Interface
       $valueArray = array_merge(array('srNo' => ($records+$i+1),
                                       'noticeText'      => strip_slashes(trim_output(HtmlFunctions::getInstance()->removePHPJS($noticeRecordArray[$i]['noticeText']))),
                                       'noticeSubject'   => strip_slashes(trim_output(HtmlFunctions::getInstance()->removePHPJS($noticeRecordArray[$i]['noticeSubject']))),
                                       'visibleFromDate' => UtilityManager::formatDate(strip_slashes($noticeRecordArray[$i]['visibleFromDate'])),
                                       'visibleToDate'   => UtilityManager::formatDate(strip_slashes($noticeRecordArray[$i]['visibleToDate'])),
                                       'noticeAttachment' => $noticeRecordArray[$i]['noticeAttachment'],
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
// $History: scAjaxInstituteNoticeList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScTeacherActivity
//
//*****************  Version 3  *****************
//User: Arvind       Date: 10/07/08   Time: 12:09p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//added noticeAttachment download option
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/10/08    Time: 6:36p
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/09/08    Time: 5:18p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
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
