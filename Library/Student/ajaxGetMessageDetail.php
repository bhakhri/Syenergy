<?php

//-------------------------------------------------------------------------------------------------------------- 
// Purpose: To store the records of block in array from the database, pagination and search, delete 
// functionality
//
// Author : Pushpender Kumar Chauhan
// Created on : (11.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------------- 
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/SMSDetailManagers.inc.php");
    $smsDetailManagers = SMSDetailManagers::getInstance();
   
    require_once($FE . "/Library/HtmlFunctions.inc.php");
    $htmlManager  = HtmlFunctions::getInstance();

    
    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
  
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'dated';
    //echo $sortField;
    $orderBy = " $sortField $sortOrderBy";   

    $studentId= $REQUEST_DATA['studentId'];
    $classId = $REQUEST_DATA['ClassId'];     

   $filter = " AND receiverIds LIKE '%~$studentId~%' AND s.studentId IN ('$studentId') " ;
    if($classId!=0) {
      $filter .= " AND c.classId = '". $classId."'";
    }    

	//to get the message type
	if($REQUEST_DATA['messageMedium']!='All') { 
	     $filter .= " AND (a.messageType='".$REQUEST_DATA['messageMedium']."')"; 
	   }

 

	//to get the data from searchbox
	if(UtilityManager::notEmpty($REQUEST_DATA['messagebox1'])) { 
	       $filter .= 'AND ( message LIKE "%'.add_slashes(trim($REQUEST_DATA['messagebox1'])).'%" OR 
				messageType LIKE "%'.add_slashes(trim($REQUEST_DATA['messagebox1'])).'%" OR 
				userName LIKE "%'.add_slashes(trim($REQUEST_DATA['messagebox1'])).'%")';         
	    }     

	//to get sent by name

	if($REQUEST_DATA['roleType']!='0') {   
	$filter .= " AND (u.roleId='".$REQUEST_DATA['roleType']."')"; 
	   
	}




  $smsTotalArray = $smsDetailManagers-> getMessageDetail($filter);
   $totalArray = count($smsTotalArray);
 
   $smsRecordArray = $smsDetailManagers->getMessageDetail($filter,$limit,$orderBy);

   $cnt = count($smsRecordArray);
//to limit the message length
    for($i=0;$i<$cnt;$i++) {
        $msg = $htmlManager->removePHPJS($smsRecordArray[$i]['message']); 
        
        $action1 = '';
        if(strlen($msg) > 80) {
          $message1 = substr($msg,0,80)."...";  
        }
        else {
          $message1 = $msg;
        }
        
        $action1 = '<a href="" name="bubble" onclick="showMessageDetails('.$smsRecordArray[$i]['messageId'].',\'divMessage\',400,200);return false;" ><img src="'.IMG_HTTP_PATH.'/mer.gif" border="0" alt="Brief Description" title="Brief Description"></a></a>';
       //function used to formatdate 
        $smsRecordArray[$i]['dated'] = UtilityManager::formatDate($smsRecordArray[$i]['dated']);
        $smsRecordArray[$i]['message'] = $message1;  
       
        
        $valueArray = array_merge(array('action1'=>$action1,
					'srNo' => ($records+$i+1)),
					$smsRecordArray[$i] );
        if(trim($json_val)=='') {                      
            $json_val = json_encode($valueArray);
        }                                                                          
        else{
            $json_val .= ','.json_encode($valueArray);           
        }
    }
    
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$totalArray.'","page":"'.$page.'","info" : ['.$json_val.']}'; 

?>
