 <?php 
//This file is used as printing version for display countries.
//
// Author :Parveen Sharma
// Created on : 03.08.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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

    require_once(MODEL_PATH . "/NoticeManager.inc.php");
    $noticeManager =NoticeManager::getInstance();
    
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
    $filter = ''; 
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {                         
        $filter = ' AND  (noticeSubject LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                           DATE_FORMAT(visibleFromDate,"%d-%b-%y") LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                           DATE_FORMAT(visibleToDate,"%d-%b-%y")  LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                           departmentName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR
                           noticePublishTo LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR
                           visibleMode LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" )';
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'noticeSubject';
    $orderBy = " $sortField $sortOrderBy";         

    
    $noticeRecordArray = $noticeManager->getNoticeListNew($filter,'',$orderBy);
    $cnt = count($noticeRecordArray);
  
    
    $search = add_slashes(trim($REQUEST_DATA['searchbox']));    
    
    $csvData ='';
    $csvData ="Search By,".parseCSVComments($search)."\n";
    $csvData .="#,Visible From,Visible To,Subject,Department,Mode,Publish To";
    $csvData .="\n";
   
    for($i=0;$i<$cnt;$i++) {  
       $noticeRecordArray[$i]['visibleFromDate']=UtilityManager::formatDate(strip_slashes($noticeRecordArray[$i]['visibleFromDate']));
       $noticeRecordArray[$i]['visibleToDate']=UtilityManager::formatDate(strip_slashes($noticeRecordArray[$i]['visibleToDate']));
       $noticeRecordArray[$i]['roleName']=UtilityManager::getTitleCase(strip_slashes($noticeRecordArray[$i]['roleName']));
       $noticeRecordArray[$i]['departmentName']=UtilityManager::getTitleCase(strip_slashes($noticeRecordArray[$i]['departmentName']));
       
       $csvData .= ($i+1).",";
       $csvData .= parseCSVComments($noticeRecordArray[$i]['visibleFromDate']).",".parseCSVComments($noticeRecordArray[$i]['visibleToDate']).",";
       $csvData .= parseCSVComments($noticeRecordArray[$i]['noticeSubject']).",".parseCSVComments($noticeRecordArray[$i]['departmentName']).",";
       $csvData .= parseCSVComments($noticeRecordArray[$i]['visibleMode']).",".parseCSVComments($noticeRecordArray[$i]['noticePublishTo']);
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
header('Content-Disposition: attachment; filename="noticereport.csv"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>
