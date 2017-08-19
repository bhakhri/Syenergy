<?php
//-------------------------------------------------------
// Purpose: To store the records of time table report in array from the database for subject centric
//
// Author : Rajeev Aggarwal
// Created on : (31.10.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','MessagesReport');
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
  




	$messagesDetailsArray = $smsdetailManager->getSMSDetailList($filter,$orderBy,$limit);

//print_r($UndeliveredMsgsArray);die;
   // $totalArray = $smsdetailManager->getTotalSMSDetailList($filter);  
    //$SMSDetailRecordArray = $smsdetailManager->getSMSDetailList($filter,$orderBy,$limit);
   $cnt = count($messagesDetailsArray);
	//print_r($UndeliveredMsgsArray); die;
    for($i=0;$i<$cnt;$i++) {
        
        $msg = $htmlManager->removePHPJS($messagesDetailsArray[$i]['message']); 
        
        //$msg = strip_tags(UtilityManager::getTitleCase($htmlManager->removePHPJS($SMSDetailRecordArray[$i]['message'])));
        $action1 = '';
        if(strlen($msg) > 20) {
          $message1 = substr($msg,0,20)."...";  
        }
        else {
          $message1 = $msg;
        }
		//echo $receverId;
		//echo $receverId; die;
		//print_r($UndeliveredMsgsArray); die;
		$messageId = $messagesDetailsArray[$i]['messageId'];
		$receiverType = $messagesDetailsArray[$i]['receiverType'];
		$action1 ="  <a href='' name='action' onclick='showDeliveredMessages($messageId,\"$receiverType\",\"deliverdMessages\",400,200);return false;' title='$title'><img src='".IMG_HTTP_PATH."/delivered.gif' border='0' alt='Delivered Recipients' title='Delivered Recipients'></a></a>";
        $action1 .= "&nbsp;&nbsp;<a href='' name='action' onclick='showUndeliveredMessages($messageId,\"$receiverType\",\"divmessage\",400,200);return false;' title='$title'><img src='".IMG_HTTP_PATH."/undelivered.gif' border='0' alt='Undelivered Recipients' title='Undelivered Recipients'></a></a>";
		
		//$link = "<a href='' name='message1' onclick='showMessage($messageId);return false;'>'$undeliveredMsgArray[$i]['message']'</a>";
		
        $messagesDetailsArray[$i]['dated'] = UtilityManager::formatDate($messagesDetailsArray[$i]['dated']);
        $messagesDetailsArray[$i]['message'] = $message1;  
		$messagesDetailsArray[$i]['message']= "<a href='' name='message1' alt='Brief Description' title='	
		Brief Description'onclick='showMessageDetails($messageId);return false;'>"."<font color='red'><u>".$messagesDetailsArray[$i]['message']."...</u></font></a>";
        $valueArray = array_merge(array('action1'=>$action1,'srNo' => ($records+$i+1)),$messagesDetailsArray[$i]);
		if(trim($json_val)=='') {                      
            $json_val = json_encode($valueArray);
        }                                                                          
        else{
            $json_val .= ','.json_encode($valueArray);           
        }
    }
	echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cnt.'","page":"'.$page.'","info" : ['.$json_val.']}'; 

?>
