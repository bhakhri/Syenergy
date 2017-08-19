<?php
//-----------------------------------------------------------------------------------------------------------
// Purpose: To fetch the records of institute events
//
// Author : Rajeev Aggarwal
// Created on : (15.10.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------------------
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
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) { 
       $filter = ' AND (e.eventTitle LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    
    $curDate=date('Y')."-".date('m')."-".date('d');
    //$filter .=" AND ( '$curDate' >= e.startDate AND '$curDate' <= e.endDate)";  
    //$filter .=" AND DATE_SUB(e.startDate,INTERVAL ".EVENT_DAY_PRIOR." DAY) <=CURDATE() AND e.endDate>=CURDATE() ";
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'eventTitle';
    
     $orderBy = " e.$sortField $sortOrderBy";         

    ////////////
    
    $totalArray = count($dashboardManager->getEventList($filter));
    $eventRecordArray = $dashboardManager->getEventList($filter,$limit,$orderBy);
    $cnt = count($eventRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface
       $valueArray = array_merge(array( 'srNo' => ($records+$i+1),
                                        'eventTitle' => strip_slashes(trim_output($eventRecordArray[$i]['eventTitle'])),
                                        'shortDescription' => strip_slashes(trim_output(HtmlFunctions::getInstance()->removePHPJS($eventRecordArray[$i]['shortDescription']))),
                                        'longDescription' => strip_slashes(trim_output(HtmlFunctions::getInstance()->removePHPJS($eventRecordArray[$i]['longDescription']))),
                                        'startDate'=>UtilityManager::formatDate(strip_slashes($eventRecordArray[$i]['startDate'])),
                                        'endDate'=>UtilityManager::formatDate(strip_slashes($eventRecordArray[$i]['endDate'])),
                                        'details'   => '<img src="'.IMG_HTTP_PATH.'/zoom.gif" border="0" alt="Details" onClick="return showEventDetails('.$eventRecordArray[$i]['eventId'].',\'divEvent\',650,350);"/>'
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
// $History: ajaxInstituteEventList.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:01p
//Created in $/LeapCC/Library/Management
//Inital checkin
?>
