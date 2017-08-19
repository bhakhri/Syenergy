<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of institute notices
//
// Author : Rajeev Aggarwal
// Created on : (12.12.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(BL_PATH.'/HtmlFunctions.inc.php');
	define('MODULE','COMMON');
	define('ACCESS','view');
    
    UtilityManager::ifManagementNotLoggedIn();
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/Management/DashboardManager.inc.php");
    $dashboardManager = DashBoardManager::getInstance();
    
 
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
	 $filter ="";
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) { 
       $filter = ' AND (n.noticeText LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR n.noticeSubject LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'noticeText';
    
    $orderBy = " $sortField $sortOrderBy";         

    $totalArray =$dashboardManager->getInstituteNoticesCount();
    $noticeRecordArray = $dashboardManager->getNoticeList($filter,$limit,$orderBy);
    $cnt = count($noticeRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       
	    $attactment=strip_slashes($noticeRecordArray[$i]['noticeAttachment']);
		$att="'$attactment'";
		$pic=split('_-',strip_slashes($noticeRecordArray[$i]['noticeAttachment'])); 
		if(isset($pic[0])){ 
        
        $noticeRecordArray[$i]['noticeAttachment']='<img src="'.IMG_HTTP_PATH.'/download.gif" title="'.$pic[0].'" onclick="download('. $att.')" />';  
    } 
    else{
        $noticeRecordArray[$i]['noticeAttachment']='';
    }
        // add stateId in actionId to populate edit/delete icons in User Interface
    $valueArray = array_merge(array('srNo' => ($records+$i+1),'noticeText'      => strip_slashes(trim_output(HtmlFunctions::getInstance()->removePHPJS($noticeRecordArray[$i]['noticeText']))),
		   'noticeSubject'   => strip_slashes(trim_output(HtmlFunctions::getInstance()->removePHPJS($noticeRecordArray[$i]['noticeSubject']))),
		   'visibleFromDate' => UtilityManager::formatDate(strip_slashes($noticeRecordArray[$i]['visibleFromDate'])),
		   'departmentName' => strip_slashes($noticeRecordArray[$i]['departmentName']),
		   'visibleToDate'   => UtilityManager::formatDate(strip_slashes($noticeRecordArray[$i]['visibleToDate'])),
		   'noticeAttachment' => $noticeRecordArray[$i]['noticeAttachment'],
		   'details'   => '<img src="'.IMG_HTTP_PATH.'/zoom.gif" border="0" alt="Details" onClick="return showNoticeDetails('.$noticeRecordArray[$i]['noticeId'].',\'divNotice\',650,350);"/>'
		  )
	);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray.'","page":"'.$page.'","info" : ['.$json_val.']}'; 

// for VSS
// $History: ajaxInstituteNoticeList.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:01p
//Created in $/LeapCC/Library/Management
//Inital checkin
?>
