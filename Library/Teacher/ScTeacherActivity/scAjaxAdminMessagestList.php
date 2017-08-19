<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of institute events
//
// Author : Dipanjan Bbhattacharjee
// Created on : (28.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
       $filter = ' AND (e.eventTitle LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    
    $curDate=date('Y')."-".date('m')."-".date('d');
    //$filter .=" AND ( '$curDate' >= e.startDate AND '$curDate' <= e.endDate)";  
    $filter .=" AND DATE_SUB(e.startDate,INTERVAL ".EVENT_DAY_PRIOR." DAY) <=CURDATE() AND e.endDate>=CURDATE() ";
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'eventTitle';
    
     $orderBy = " e.$sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $teacherManager->getTotalEvent($filter);
    $eventRecordArray = $teacherManager->getEventList($filter,$limit,$orderBy);
    $cnt = count($eventRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface
       $valueArray = array_merge(array( 'srNo' => ($records+$i+1),
                                        'eventTitle' => strip_slashes(trim_output($eventRecordArray[$i]['eventTitle'])),
                                        'shortDescription' => strip_slashes(trim_output(HtmlFunctions::getInstance()->removePHPJS($eventRecordArray[$i]['shortDescription']))),
                                        'longDescription' => strip_slashes(trim_output(HtmlFunctions::getInstance()->removePHPJS($eventRecordArray[$i]['longDescription']))),
                                        'startDate'=>UtilityManager::formatDate(strip_slashes($eventRecordArray[$i]['startDate'])),
                                        'endDate'=>UtilityManager::formatDate(strip_slashes($eventRecordArray[$i]['endDate'])),
                                        'details'   => '<img src="'.IMG_HTTP_PATH.'/zoom.gif" border="0" alt="Details" onClick="return showEventDetails('.$eventRecordArray[$i]['eventId'].');"/>'
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
// $History: scAjaxAdminMessagestList.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/29/08    Time: 6:22p
//Created in $/Leap/Source/Library/Teacher/ScTeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/24/08    Time: 1:36p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
//Corrected date range in event showing criteria
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Library/Teacher/ScTeacherActivity
?>
