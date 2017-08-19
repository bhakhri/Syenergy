 <?php 
//This file is used as printing version for display countries.
//
// Author :Parveen Sharma
// Created on : 03.08.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
	
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MANAGEMENT_ACCESS',1);
    define('MODULE','COMMON');     
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/EventManager.inc.php");
    $eventManager =EventManager::getInstance();
    
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
    $noticeIds='';
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
    //$totalArray = $eventManager->getTotalEvent($filter);    
    $eventRecordArray = $eventManager->getEventList($filter,'',$orderBy);
    $cnt = count($eventRecordArray);   
    
    $search = add_slashes(trim($REQUEST_DATA['searchbox']));    
    
    $csvData ='';
    $csvData ="Search By,".parseCSVComments($search)."\n";
    $csvData .="#,Greeting Date,Comments,Abbreviation,Visible";
    $csvData .="\n";
     
    for($i=0;$i<$cnt;$i++) {  
       $eventRecordArray[$i]['eventWishDate']=UtilityManager::formatDate(strip_slashes($eventRecordArray[$i]['eventWishDate']));
       $eventRecordArray[$i]['comments']=UtilityManager::getTitleCase(strip_slashes($eventRecordArray[$i]['comments']));
       $eventRecordArray[$i]['abbr']=UtilityManager::getTitleCase(strip_slashes($eventRecordArray[$i]['abbr']));
       //$eventRecordArray[$i]['eventRoleName']=UtilityManager::getTitleCase(strip_slashes($eventRecordArray[$i]['eventRoleName']));
       $eventRecordArray[$i]['isStatus']=(UtilityManager::getTitleCase(strip_slashes($eventRecordArray[$i]['isStatus'])))?"Yes":"No";
       
       
       $csvData .= ($i+1).",";
       $csvData .= parseCSVComments($eventRecordArray[$i]['eventWishDate']).",".parseCSVComments($eventRecordArray[$i]['comments']).",";
       $csvData .= parseCSVComments($eventRecordArray[$i]['abbr']).",".parseCSVComments($eventRecordArray[$i]['isStatus']);
       //$csvData .= parseCSVComments($eventRecordArray[$i]['isStatus']);
       $csvData .= "\n";   
    }
    
    if($cnt==0) {
      $csvData .= ",,No Data Found";    
    }
 
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment; filename="GreetingReport.csv"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>
