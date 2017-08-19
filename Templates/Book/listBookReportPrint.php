 <?php 
//This file is used as CSV version to display books.
//
// Author : Nancy Puri
// Created on : 04-Oct-2010
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
    $bookManager =BookManager::getInstance();
	
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  
    $searchBox = $REQUEST_DATA['searchbox']; 
    
    
	/// Search filter /////  
    if(UtilityManager::notEmpty($searchBox)) {
       $filter = ' WHERE (bookName LIKE "'.add_slashes($searchBox).'%" OR bookAuthor LIKE "'.add_slashes($searchBox).'%" OR uniqueCode LIKE "'.add_slashes($searchBox).'%" OR instituteBookCode LIKE "'.add_slashes($searchBox).'%" OR isbnCode LIKE "'.add_slashes($searchBox).'%" OR noOfBooks LIKE "'.add_slashes($searchBox).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'bookName';
    
    $orderBy = " $sortField $sortOrderBy";  
     

    $bookRecordArray = $bookManager->getBookList($filter,'',$orderBy);
    $cnt = count($bookRecordArray);
       

    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        
        $valueArray[] = array_merge(array('srNo'=>($i+1)),$bookRecordArray[$i]);
    }
    
    $search = add_slashes(trim($searchBox));
    
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Book Master Report Print');
	$reportManager->setReportInformation("Search by: ".$search);
	

    $reportTableHead                        =    array();
                                           //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#',                ' width="2%"  align="left"', "align='left'");
    $reportTableHead['bookName']			=    array('Book Name ',    ' width=15%   align="left" ','align="left" ');
    $reportTableHead['bookAuthor']	        =    array('Book Author',     ' width="15%" align="left" ','align="left"');
	$reportTableHead['uniqueCode']		    =    array('Unique Code',      ' width="15%" align="left" ','align="left"');
    $reportTableHead['instituteBookCode']   =    array('Institute Book Code',      ' width="15%" align="left" ','align="left"'); 
    $reportTableHead['isbnCode']            =    array('ISBN Code',      ' width="15%" align="left" ','align="left"'); 
    $reportTableHead['noOfBooks']           =    array('No. Of Books',      ' width="15%" align="right" ','align="right"'); 
    
    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

?>
