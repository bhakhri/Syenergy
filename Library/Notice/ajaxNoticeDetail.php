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

    $noticeId = trim($REQUEST_DATA['noticeId']);
    
    if($noticeId=='') {
      $noticeId=0;  
    }
    
    
    // Fetch Notice Detail
    $filter = " AND n.noticeId = '$noticeId' ";
    $noticeRecordArray =  $noticeManager->getNoticeListNew($filter); 
    
    
    // Fetch Notice Role
    $filter = " AND n.noticeId = '$noticeId' ";
    $noticeRoleArray = $noticeManager->getNoticeRole($filter); 
    
    
    // Fetch Notice Institute
    $filter = " AND n.noticeId = '$noticeId' ";
    $noticeInstituteArray = $noticeManager->getNoticeInstitute($filter); 
    
     
    // Fetch Notice Class
    $filter = " AND n.noticeId = '$noticeId' ";
    $noticeClassArray = $noticeManager->getNoticeClass($filter); 
    
    
    $roleName ='';
    for($i=0;$i<count($noticeRoleArray);$i++) {
      if($roleName !='') {
        $roleName .=', ';
      }  
      $roleName .= $noticeRoleArray[$i]['roleName'];
    }
    
    $instituteCode ='';
    for($i=0;$i<count($noticeInstituteArray);$i++) {
      if($instituteCode !='') {
        $instituteCode .=', ';
      }  
      $instituteCode .= $noticeInstituteArray[$i]['instituteCode'];
    }
    
    $className ='';
    for($i=0;$i<count($noticeClassArray);$i++) {
      if($className !='') {
        $className .=', ';
      }  
      $className .= $noticeClassArray[$i]['className'];
    }
    $noticeRecordArray[0]['noticeSubject'] = html_entity_decode($noticeRecordArray[0]['noticeSubject']);
    $noticeRecordArray[0]['noticeText'] = html_entity_decode($noticeRecordArray[0]['noticeText']);
    $valueArray[] = array_merge(array('roleName' => $roleName, 
                                    'instituteCode' => $instituteCode,
                                    'className' => $className),
                                    $noticeRecordArray[0]);
    
    
    if(is_array($valueArray) && count($valueArray)>0 ) {  
      echo json_encode($valueArray[0]);
    }
    else {
      echo 0;
    }
?>
