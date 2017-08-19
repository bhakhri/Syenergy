 <?php 
//This file is used as CSV version for display countries.
//
// Author :Parveen Sharma
// Created on : 13-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');     
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/EventManager.inc.php");
    $eventManager =EventManager::getInstance();
	
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

	/// Search filter /////  
     if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
        $filter = '';
        /*  $roleName = '  OR t.eventRoleName ';
            $arr = explode(",",add_slashes($REQUEST_DATA['searchbox']));  
            $cnt = count($arr);
            if($cnt>=2) {
              $roleName .= " IN ('".trim(add_slashes($arr[0]))."'"; 
              for($i=1; $i<$cnt; $i++) {
                  $roleName .= ", '".trim(add_slashes($arr[$i]))."'";
              }
              $roleName .= ")"; 
            }
            else {
              $roleName .= " LIKE  '%".add_slashes($REQUEST_DATA['searchbox'])."%' ";
            }
       */ 
        $filter = ' WHERE (DATE_FORMAT(eventWishDate,"%d-%b-%y") LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                          comments LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                          IF(isStatus=1,"Yes","No") LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                          abbr LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%"'.$roleName.')';
    }

    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'eventWishDate';
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    //$totalArray = $eventManager->getTotalNotice($filter);
    $eventRecordArray = $eventManager->getEventList($filter,'',$orderBy);
    $cnt = count($eventRecordArray);
    $eventIds='';
    for($i=0;$i<$cnt;$i++) {
       if($eventIds=='') {  
         $eventIds  = $eventRecordArray[$i]['userWishEventId'];
       }
       else {
         $eventIds .= ",".$eventRecordArray[$i]['userWishEventId'];
       }
    }  
    if($eventIds=='') {
      $eventIds=0;  
    }
    
    $filter = " WHERE t.userWishEventId IN ($eventIds)";
   // $totalArray = $eventManager->getTotalEvent($filter);    
    $eventRecordArray = $eventManager->getEventList($filter,'',$orderBy);
    $cnt = count($eventRecordArray);   

    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        $fileName = IMG_HTTP_PATH.'/Event/'.$eventRecordArray[$i]['eventPhoto'];
        $eventRecordArray[$i]['eventWishDate']=UtilityManager::formatDate(strip_slashes($eventRecordArray[$i]['eventWishDate']));
        $eventRecordArray[$i]['isStatus']=(UtilityManager::getTitleCase(strip_slashes($eventRecordArray[$i]['isStatus'])))?"Yes":"No";
        $eventRecordArray[$i]['eventPhoto'] = (strip_slashes($eventRecordArray[$i]['eventPhoto'])=='' ? NOT_APPLICABLE_STRING :
            '<img src="'.$fileName.'" name="'.strip_slashes($eventRecordArray[$i]['eventPhoto']).'" height="40px" width="50px" />');  
        
        // add stateId in actionId to populate edit/delete icons in User Interface   
        $valueArray[] = array_merge(array('srNo'=>($i+1)),$eventRecordArray[$i]);
    }
    
    $search = add_slashes(trim($REQUEST_DATA['searchbox']));
    
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Greeting Master Report Print');
	$reportManager->setReportInformation("Search by: ".$search);
	
    $reportTableHead                  =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']		  =    array('#', ' width="2%"  align="left"', "align='left'");
    $reportTableHead['eventPhoto']    =    array('Photo',  ' width="12%" align="center" ','align="center"');
    $reportTableHead['eventWishDate'] =    array('Greeting Date',  ' width="12%" align="center" ','align="center"');
    $reportTableHead['comments']      =    array('Comments',    ' width="35%" align="left" ','align="left"');
    $reportTableHead['abbr']		  =    array('Abbreviation ',      ' width=20%   align="left" ','align="left" ');
    //$reportTableHead['eventRoleName']     =    array('Role ',      ' width=20%   align="left" ','align="left" ');
    $reportTableHead['isStatus']	  =    array('Visible',    ' width="10%" align="center" ','align="center"');
    
    
    

    $reportManager->setRecordsPerPage(30);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
