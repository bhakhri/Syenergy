<?php
//-------------------------------------------------------
// Purpose: To display the records of display Notices in Parents in array from the database, pagination and search  functionality
//
// Author : Arvind Singh Rawat
// Created on : 14-07-2008
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(BL_PATH . "/HtmlFunctions.inc.php");
    define('MODULE','ParentInstituteNotices');
    define('ACCESS','view');
    UtilityManager::ifParentNotLoggedIn(true);
    UtilityManager::headerNoCache();

     require_once(MODEL_PATH . "/Parent/ParentManager.inc.php");
    $parentManager = ParentManager::getInstance();

    /////////////////////////
    
    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    
    //////
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' AND (noticeSubject LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR noticeText LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR visibleFromDate LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR visibleToDate LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'visibleFromDate';
    
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $totalArray = $parentManager->getTotalNotices($filter);
    $parentRecordArray = $parentManager->getNoticesList($filter,$limit,$orderBy);   
   
    $cnt = count($parentRecordArray);

    for($i=0;$i<$cnt;$i++) {
      // $parentRecordArray[$i]['noticeSubject'] = HtmlFunctions::getInstance()->removePHPJS($parentRecordArray[$i]['noticeSubject'],'','1');
       //$parentRecordArray[$i]['noticeText']   = HtmlFunctions::getInstance()->removePHPJS($parentRecordArray[$i]['noticeText'],'','1');   
	   $parentRecordArray[$i]['noticeSubject'] = trim($parentRecordArray[$i]['noticeSubject']);
	   $parentRecordArray[$i]['noticeText'] = html_entity_decode($parentRecordArray[$i]['noticeText']);
       if(strlen($parentRecordArray[$i]['noticeSubject']) >=25) {
         $parentRecordArray[$i]['noticeSubject'] = "<span>".substr($parentRecordArray[$i]['noticeSubject'],0,25)."...</span>";
       }
       
       $parentRecordArray[$i]['noticeText'] = "<span>".substr($parentRecordArray[$i]['noticeText'],0,45);
       if(strlen($parentRecordArray[$i]['noticeText']) >=45) { 
			$parentRecordArray[$i]['noticeText'] .= "...";   
       }
       $parentRecordArray[$i]['noticeText'] .= "</span>";
       $parentRecordArray[$i]['visibleFromDate'] = UtilityManager::formatDate($parentRecordArray[$i]['visibleFromDate']);
       $parentRecordArray[$i]['visibleToDate'] = UtilityManager::formatDate($parentRecordArray[$i]['visibleToDate']);
       
       if($parentRecordArray[$i]['noticeAttachment']=='') {
          $parentRecordArray[$i]['noticeAttachment']=NOT_APPLICABLE_STRING; 
       }
       else {
          $parentRecordArray[$i]['noticeAttachment']='<a href="'.IMG_HTTP_PATH.'/Notice/'.$parentRecordArray[$i]['noticeAttachment'].'" target="_blank"><img src="'.IMG_HTTP_PATH.'/download.gif"></a>';
       }
       // add countryId in actionId to populate edit/delete icons in User Interface   
       $valueArray = array_merge(array('Edit'=>'<a href="#" title="View Details"><img src="'.IMG_HTTP_PATH.'/detail.gif"  border="0" onClick="editWindow('.$parentRecordArray[$i]['noticeId'].',\'ViewNotices\',520,400); return false;"/></a>','action' => $parentRecordArray[$i]['noticeId'] , 'srNo' => ($records+$i+1) ),$parentRecordArray[$i]);

       if(trim($json_val)=='') {
            $json_val = json_encode($valueArray);
       }
       else {
            $json_val .= ','.json_encode($valueArray);           
       }
	
    }
	//print_r($parentRecordArray);
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
  
  //$History : $  
?>
