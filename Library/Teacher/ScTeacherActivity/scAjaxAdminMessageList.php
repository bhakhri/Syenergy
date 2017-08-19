<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of admin messahes
//
// Author : Dipanjan Bbhattacharjee
// Created on : (18.08.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------  
//purpose: to trim a string and output str.. etc
//Author:Dipanjan Bhattcharjee
//Date:26.07.2008
//$str=input string,$maxlenght:no of characters to show,$rep:what to display in place of truncated string
//$mode=1 : no split after 30 chars,mode=2:split after 30 characters
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------  
function trim_output($str,$maxlength,$mode=1,$rep='...'){
   $ret=($mode==2?chunk_split($str,30):$str);

   if(strlen($ret) > $maxlength){
      $ret=substr($ret,0,$maxlength).$rep; 
   }
  return $ret;  
}

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(BL_PATH.'/HtmlFunctions.inc.php');
    
    UtilityManager::ifTeacherNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Teacher/ScTeacherManager.inc.php");
    $teacherManager = ScTeacherManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT 0,'.RECORDS_PER_PAGE;
    //////
    
     /// Search filter /////  
     
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) { 
       $filter = ' AND (adm.subject LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR adm.message LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'userName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $teacherManager->getTotalAdminMessage($filter);
    $msgRecordArray = $teacherManager->getAdminMessageList($filter,$limit,$orderBy);
    $cnt = count($msgRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface
       $valueArray = array_merge(array('srNo' =>'<a href="#"  onclick="showMessageDetails('.$msgRecordArray[$i]['messageId'].');return false;">'. ($records+$i+1).'</a>',
                                       'userName'      =>'<a href="#"  onclick="showMessageDetails('.$msgRecordArray[$i]['messageId'].');return false;">'. strip_slashes($msgRecordArray[$i]['userName']).'</a>',
                                       'subject'      => '<a href="#"  onclick="showMessageDetails('.$msgRecordArray[$i]['messageId'].');return false;">'.trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($msgRecordArray[$i]['subject'])),200,1).'</a>',
                                       'message'   => '<a href="#"  onclick="showMessageDetails('.$msgRecordArray[$i]['messageId'].');return false;">'.trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($msgRecordArray[$i]['message'])),700,1).'</a>',
                                       'dated' => '<a href="#"  onclick="showMessageDetails('.$msgRecordArray[$i]['messageId'].');return false;">'.UtilityManager::formatDate(strip_slashes($msgRecordArray[$i]['dated'])).'</a>',
                                       'details'   => '<img src="'.IMG_HTTP_PATH.'/zoom.gif" border="0" alt="Details" onClick="return showMessageDetails('.$msgRecordArray[$i]['messageId'].');"/>'
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
// $History: scAjaxAdminMessageList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScTeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/29/08    Time: 5:48p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
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
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/09/08    Time: 1:58p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/18/08    Time: 5:52p
//Updated in $/Leap/Source/Library/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/18/08    Time: 4:12p
//Created in $/Leap/Source/Library/Teacher/TeacherActivity
?>
