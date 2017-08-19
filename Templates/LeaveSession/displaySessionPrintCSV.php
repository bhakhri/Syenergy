<?php
//-------------------------------------------------------
//  This File contains Validation and CSV function used in Leave Session Form
//
//
// Author :Parveen Sharma
// Created on : 19-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    require_once(MODEL_PATH . "/LeaveSessionsManager.inc.php");   
    $sessionsManager = LeaveSessionsManager::getInstance();
    
    define('MODULE','LeaveSessionMaster');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    
        // CSV data field Comments added 
    function parseCSVComments($comments) {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
           return '"'.$comments.'"'; 
         } 
         else {
             return $comments.chr(160); 
         }
    }
    
    $search = trim($REQUEST_DATA['searchbox']);
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = '  WHERE sessionName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                         IF(active=1,"Yes","No") LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR
                         DATE_FORMAT(sessionStartDate,"%d-%b-%y") LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                         DATE_FORMAT(sessionEndDate,"%d-%b-%y")  LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%"';  ;  
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'sessionName';
    
    $orderBy = " $sortField $sortOrderBy";

	$sessionRecordArray = $sessionsManager->getSessionList($filter,'',$orderBy);
    
	$recordCount = count($sessionRecordArray);

    $valueArray = array();

    $csvData ='';
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);       
    $csvData .= "Search By, ".parseCSVComments($search)."\n";
    //$csvData .= "As On, ".UtilityManager::formatDate($formattedDate)."\n";
    $csvData .="#,Session Name,Start Date,End Date,Active";
    $csvData .="\n";
    
    for($i=0;$i<$recordCount;$i++) {
	  $sessionRecordArray[$i]['sessionStartDate'] =strip_slashes($sessionRecordArray[$i]['sessionStartDate'])=='0000-00-00' ? NOT_APPLICABLE_STRING : UtilityManager::formatDate($sessionRecordArray[$i]['sessionStartDate']);
      $sessionRecordArray[$i]['sessionEndDate'] = strip_slashes($sessionRecordArray[$i]['sessionEndDate'])=='0000-00-00' ? NOT_APPLICABLE_STRING :UtilityManager::formatDate($sessionRecordArray[$i]['sessionEndDate']);
	  $csvData .= ($i+1).",";
	  $csvData .= parseCSVComments($sessionRecordArray[$i]['sessionName']).",";
	  $csvData .= $sessionRecordArray[$i]['sessionStartDate'].",";
	  $csvData .= $sessionRecordArray[$i]['sessionEndDate'].",";
	  $csvData .= $sessionRecordArray[$i]['active']."\n";
    }
     if($recordCount==0) {
      $csvData .= ",,No Data Found";   
    }
   UtilityManager::makeCSV($csvData,'LeaveSessionReport.csv');          
   die;         
