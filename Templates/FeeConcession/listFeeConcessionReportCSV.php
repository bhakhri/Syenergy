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
    define('MODULE','FeeConcessionMaster'); 
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/FeeConcessionManager.inc.php");
    $FeeConcessionManager =FeeConcessionManager::getInstance();
    
     // CSV data field Comments added 
    function parseCSVComments($comments,$extra='') {
         $comments = str_replace('"', '""', $comments);
         $comments = str_ireplace('<br/>', "\n", $comments);
         if(eregi(",", $comments) or eregi("\n", $comments)) {
           return '"'.$comments.'"'; 
         } 
         else {
            if($extra=='') { 
              return $comments.chr(160); 
            }
            else {
              return $comments;   
            }
         }
    }
    
    /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
       $filter = ' WHERE (categoryName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR 
                          categoryOrder LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%" OR
                          categoryDescription LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';   
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'categoryName';
    $orderBy = " $sortField $sortOrderBy";  
     
    $feeConcessionRecordArray = $FeeConcessionManager->getFeeConcessionList($filter,'',$orderBy); 
    $cnt = count($feeConcessionRecordArray);
    
    $search = add_slashes(trim($REQUEST_DATA['searchbox']));    
    
    $csvData ='';
    $csvData ="Search By,".parseCSVComments($search)."\n";
    $csvData.="#,Category Name,Settlement Order,Description";
    $csvData .="\n";
    
    for($i=0;$i<$cnt;$i++) {  
		  $csvData .= ($i+1).",";
		  $csvData .= parseCSVComments($feeConcessionRecordArray[$i]['categoryName']).",";
          $csvData .= parseCSVComments($feeConcessionRecordArray[$i]['categoryOrder'],"1").",";
		  $csvData .= parseCSVComments($feeConcessionRecordArray[$i]['categoryDescription'])."\n";
    }
    
UtilityManager::makeCSV($csvData,'feeConcessionMaster.csv');    
die;         
//$History : $
?>