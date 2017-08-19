 <?php 
//This file is used as printing version for display blockstudent.
//
// Author :Abhay Kant
// Created on : 22-June-2011
// Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','AllowIp');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/RegistrationForm/AllowIpManager.inc.php");
    $allowIpManager =AllowIpManager::getInstance();
    
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
          $filter = " WHERE allowIPNo LIKE '%".$searchBoxData."'";
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'allowIPNo';
    $orderBy = " $sortField $sortOrderBy";  
      $totalArray = $allowIpManager->getTotalIp($filter);
    $ipRecordArray = $allowIpManager->getIpList($filter,$limit,$orderBy);
    $cnt = count($ipRecordArray);
    
    
    $search = add_slashes(trim($REQUEST_DATA['searchbox']));    
    
    $csvData ='';
    //$csvData ="Search By,".parseCSVComments($search)."\n";
    $csvData.="#,Allowed Ip";
    $csvData .="\n";
    
    for($i=0;$i<$cnt;$i++) {  
	  $csvData .= ($i+1).",";
	  $csvData .= parseCSVComments($ipRecordArray[$i]['allowIPNo'])."\n";
    }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment; filename="allowedIPReport.csv"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>
