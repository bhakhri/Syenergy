 <?php 
//This file is used as printing version to display books.
//
// Author :Nancy Puri
// Created on : 04.10.2010
//
//--------------------------------------------------------
?>
<?php
	
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','BookMaster'); 
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/BookManager.inc.php");
    $bookManager = BookManager::getInstance();
    $searchBox = $REQUEST_DATA['searchbox'];
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
    if(UtilityManager::notEmpty($searchBox)) {
       $filter = ' WHERE (bookName LIKE "'.add_slashes($searchBox).'%" OR bookAuthor LIKE "'.add_slashes($searchBox).'%" OR uniqueCode LIKE "'.add_slashes($searchBox).'%" OR instituteBookCode LIKE "'.add_slashes($searchBox).'%" OR isbnCode LIKE "'.add_slashes($searchBox).'%" OR noOfBooks LIKE "'.add_slashes($searchBox).'%")';              
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'bookName';
    $orderBy = " $sortField $sortOrderBy";  
     
    $bookRecordArray = $bookManager->getBookList($filter,'',$orderBy); 
    $cnt = count($bookRecordArray);
    
    $search = add_slashes(trim($searchBox));    
    
    $csvData ='';
    $csvData ="Search By,".parseCSVComments($search)."\n";
    $csvData.="Sr. No.,Book Name,Book Author,Unique Code,Institute Book Code,ISBN Code, No. of books";
    $csvData .="\n";
    
    for($i=0;$i<$cnt;$i++) {  
  
		  $csvData .= ($i+1).",";
		  $csvData .= parseCSVComments($bookRecordArray[$i]['bookName']).",";
		  $csvData .= parseCSVComments($bookRecordArray[$i]['bookAuthor']).",";
		  $csvData .= parseCSVComments($bookRecordArray[$i]['uniqueCode']).",";
          $csvData .= parseCSVComments($bookRecordArray[$i]['instituteBookCode']).",";
          $csvData .= parseCSVComments($bookRecordArray[$i]['isbnCode']).",";
          $csvData .= parseCSVComments($bookRecordArray[$i]['noOfBooks'])."\n";  
    }
    
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment; filename="bookreport.csv"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         

?>