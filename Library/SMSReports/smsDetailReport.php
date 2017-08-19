<?php
//-------------------------------------------------------
// Purpose: To store the records of time table report in array from the database for subject centric
//
// Author : Rajeev Aggarwal
// Created on : (31.10.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','MessagesList');
    define('ACCESS','view');
    define('MANAGEMENT_ACCESS',1);
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();
    
    require_once(MODEL_PATH . "/SMSDetailManager.inc.php");
    $smsdetailManager  = SMSDetailManager::getInstance();

    require_once($FE . "/Library/HtmlFunctions.inc.php");
    $htmlManager  = HtmlFunctions::getInstance();

    
    // to limit records per page    
   $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
   $records    = ($page-1)* RECORDS_PER_PAGE;
   $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////

    /// Search filter /////  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'DESC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'dated';
    $orderBy = " ORDER BY $sortField $sortOrderBy";
  
    $fromDate =  $REQUEST_DATA['fromDate'];
    $toDate  =   $REQUEST_DATA['toDate'];
    
    $filter = " AND (DATE_FORMAT(dated,'%Y-%m-%d') BETWEEN '$fromDate' AND '$toDate') ";
    
     
    if(UtilityManager::notEmpty($REQUEST_DATA['messageType'])) {
       if($REQUEST_DATA['messageType']!='All') {
            $filter .= " AND (messageType ='".$REQUEST_DATA['messageType']."')";         
       }
    }
    
    if(UtilityManager::notEmpty($REQUEST_DATA['receiverType'])) {
       if($REQUEST_DATA['receiverType']!='All') {
          if($REQUEST_DATA['receiverType']=='Parent') {
            $filter .= " AND (receiverType IN ('Father','Mother','Guardian'))";
          }
          else {
            $filter .= " AND (receiverType ='".$REQUEST_DATA['receiverType']."')";
          }
       } 
       
    }
    
    $searchOrder = $REQUEST_DATA['searchOrder'];
		
	$flag="'".$REQUEST_DATA['txtSearch']."%' ";
	if($searchOrder==2) {
	   $flag="'%".$REQUEST_DATA['txtSearch']."%' ";
	}
	if(UtilityManager::notEmpty($REQUEST_DATA['txtSearch'])) {
	   $filter .= " AND (a.subject LIKE  $flag OR
			     a.message LIKE  $flag OR
			     u.userName LIKE  $flag )";
	}
    
    
    $totalArray = $smsdetailManager->getTotalSMSDetailList($filter);  
    $SMSDetailRecordArray = $smsdetailManager->getSMSDetailList($filter,$orderBy,$limit);
    $cnt = count($SMSDetailRecordArray);

    for($i=0;$i<$cnt;$i++) {
        
         $msg = $htmlManager->removePHPJS($SMSDetailRecordArray[$i]['message']); 
        
        //$msg = strip_tags(UtilityManager::getTitleCase($htmlManager->removePHPJS($SMSDetailRecordArray[$i]['message'])));
        $action1 = '';
        if(strlen($msg) > 20) {
          $message1 = substr($msg,0,20)."...";  
        }
        else {
          $message1 = $msg;
        }
        
        $action1 = '<a href="" name="bubble" onclick="showMessageDetails('.$SMSDetailRecordArray[$i]['messageId'].',\'divMessage\',400,200);return false;" title="'.$title.'" ><img src="'.IMG_HTTP_PATH.'/zoom.gif" border="0" alt="Brief Description" title="Brief Description"></a></a>';
        
        $SMSDetailRecordArray[$i]['dated'] = UtilityManager::formatDate($SMSDetailRecordArray[$i]['dated']);
        $SMSDetailRecordArray[$i]['cnt'] = $SMSDetailRecordArray[$i]['cnt'];
        $SMSDetailRecordArray[$i]['message'] = $message1;  
        
        $valueArray = array_merge(array('action1'=>$action1,'srNo' => ($records+$i+1)),$SMSDetailRecordArray[$i]);
        if(trim($json_val)=='') {                      
            $json_val = json_encode($valueArray);
        }                                                                          
        else{
            $json_val .= ','.json_encode($valueArray);           
        }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray[0]['totalRecords'].'","page":"'.$page.'","info" : ['.$json_val.']}'; 
     
// for VSS
// $History: smsDetailReport.php $
//
//*****************  Version 7  *****************
//User: Parveen      Date: 11/24/09   Time: 4:47p
//Updated in $/LeapCC/Library/SMSReports
//sorting format updated (message detials)
//
//*****************  Version 6  *****************
//User: Parveen      Date: 11/16/09   Time: 3:55p
//Updated in $/LeapCC/Library/SMSReports
//date format check updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/21/09    Time: 3:58p
//Updated in $/LeapCC/Library/SMSReports
//role permission & removePHPJS  function updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 7/20/09    Time: 4:14p
//Updated in $/LeapCC/Library/SMSReports
//new enhancement added Action button perform Berif Description added 
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/19/09    Time: 2:36p
//Updated in $/LeapCC/Library/SMSReports
//code update search for & condition update
//
//*****************  Version 2  *****************
//User: Parveen      Date: 3/31/09    Time: 5:46p
//Updated in $/LeapCC/Library/SMSReports
//formatting settings
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library/SMSReports
//
//*****************  Version 7  *****************
//User: Parveen      Date: 11/28/08   Time: 5:36p
//Updated in $/Leap/Source/Library/SMSReports
//list and report formatting
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/28/08   Time: 10:45a
//Updated in $/Leap/Source/Library/SMSReports
//changed lists view format
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/27/08   Time: 5:22p
//Updated in $/Leap/Source/Library/SMSReports
//add fields messages
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/26/08   Time: 5:06p
//Updated in $/Leap/Source/Library/SMSReports
//sms details report added
//



?>
