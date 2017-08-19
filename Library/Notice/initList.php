<?php

//This file calls Delete Function and Listing Function and creates Global Array in notice Module 
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    //Paging code goes here
    require_once(MODEL_PATH . "/NoticeManager.inc.php");
    $noticeManager = NoticeManager::getInstance();
    define('MANAGEMENT_ACCESS',1);
    define('MODULE','COMMON');     
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
    
    //Delete code goes here
    if(UtilityManager::notEmpty($REQUEST_DATA['noticeId']) && $REQUEST_DATA['act']=='del') {
            
      //  $recordArray = $countryManager->checkInCity($REQUEST_DATA['countryId']);
      //  if($recordArray[0]['found']==0) {
            if($noticeyManager->deleteNotice($REQUEST_DATA['noticeId']) ) {
                $message = DELETE;
        //    }
        }
        else {
            $message = DEPENDENCY_CONSTRAINT;
        }
    }
        
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
     $filter = ' AND (noticeSubject LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR visibleFromDate LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR visibleToDate LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';            
    }
  
    $totalArray = $noticeManager->getTotalNotice($filter);
	$totalArray[0]['totalRecords']= count($totalArray); 
    $noticeRecordArray = $noticeManager->getNoticeList($filter,$limit);  
	//$totalArray[0]['totalRecords']=count($noticeRecordArray);
    
?>

<?php 

//$History: initList.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 3/09/09    Time: 4:52p
//Updated in $/LeapCC/Library/Notice
//search condition update
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/Notice
//
//*****************  Version 5  *****************
//User: Arvind       Date: 9/17/08    Time: 10:32a
//Updated in $/Leap/Source/Library/Notice
//modified the function used for paging
//
//*****************  Version 4  *****************
//User: Arvind       Date: 9/13/08    Time: 2:51p
//Updated in $/Leap/Source/Library/Notice
//corrected paging
//
//*****************  Version 3  *****************
//User: Arvind       Date: 9/09/08    Time: 1:01p
//Updated in $/Leap/Source/Library/Notice
//added count for $noticeRecordArray
//
//*****************  Version 2  *****************
//User: Arvind       Date: 8/05/08    Time: 2:54p
//Updated in $/Leap/Source/Library/Notice
//modified filter
//
//*****************  Version 1  *****************
//User: Arvind       Date: 7/07/08    Time: 4:51p
//Created in $/Leap/Source/Library/Notice
//Added a new module   "Notice" files


?>
