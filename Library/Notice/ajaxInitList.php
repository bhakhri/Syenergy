<?php
//-------------------------------------------------------
// Purpose: To store the records of Notice in array from the database, pagination and search, delete 
// functionality
//
// Author : Arvind Singh Rawat
// Created on : 5-July-2008
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MANAGEMENT_ACCESS',1);
    define('MODULE','COMMON');     
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/NoticeManager.inc.php");
    $noticeManager =NoticeManager::getInstance();

    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////
    
    /// Search filter ///// 
    $filter = ''; 
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {                         
        $filter = ' AND  (noticeSubject LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                           DATE_FORMAT(visibleFromDate,"%d-%b-%y") LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                           DATE_FORMAT(visibleToDate,"%d-%b-%y")  LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                           departmentName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR
                           noticePublishTo LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR
                           visibleMode LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" )';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'noticeSubject';
    $orderBy = " $sortField $sortOrderBy";         

    
    $totalArray = $noticeManager->getTotalNoticeNew($filter);    
    $noticeRecordArray = $noticeManager->getNoticeListNew($filter,$limit,$orderBy);
	$cnt = count($noticeRecordArray);
    

    //$totalArray[0]['totalRecords']=count($noticeRecordArray);
    //echo count($totalArray);
    for($i=0;$i<$cnt;$i++) {
        $noticeId =  $noticeRecordArray[$i]['noticeId'];
	    $noticeRecordArray[$i]['visibleFromDate']=UtilityManager::formatDate(strip_slashes($noticeRecordArray[$i]['visibleFromDate']));
	    $noticeRecordArray[$i]['visibleToDate']=UtilityManager::formatDate(strip_slashes($noticeRecordArray[$i]['visibleToDate']));
        $noticeRecordArray[$i]['departmentName']=UtilityManager::getTitleCase(strip_slashes($noticeRecordArray[$i]['departmentName'])); 
        $noticeRecordArray[$i]['noticeSubject'] = chunk_split($noticeRecordArray[$i]['noticeSubject'],28," "); 
        
        //for notice download     
        $noticeRecordArray[$i]['noticeAttachment'] = (strip_slashes($noticeRecordArray[$i]['noticeAttachment'])=='' ? NOT_APPLICABLE_STRING :'<img src="'.IMG_HTTP_PATH.'/download.gif" name="'.strip_slashes($noticeRecordArray[$i]['noticeAttachment']).'" onClick="download(this.name);" title="Download File" alt="Download File" />');    
        
        $viewDetail = "<img src='".IMG_HTTP_PATH."/notice_icon.gif' onClick='showMessageDetails(\"$noticeId\",\"divNoticeMessage\",600,600);return false;' title='Notice Detail' alt='Notice Detail' />";
        
        // add noticeId in actionId to populate edit/delete icons in User Interface   
        $valueArray = array_merge(array('action' => $noticeRecordArray[$i]['noticeId'] , 
                                        'srNo' => ($records+$i+1),
                                        'viewDetail' => $viewDetail),
                                        $noticeRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
  
  //$History : $  
?>