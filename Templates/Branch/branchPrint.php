 <?php 
//This file is used as printing version for Country Listing
//
// Author :Arvind Singh Rawat
// Created on : 13-Oct-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/BranchManager.inc.php");
    $branchManager = BranchManager::getInstance();
    
    
     /// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
    //  $filter = ' AND (branchName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR branchCode LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")';   
       $filter = ' AND (branchName LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR branchCode LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR studentCount LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%" OR miscReceiptPrefix LIKE "%'.add_slashes($REQUEST_DATA['searchbox']).'%")'; 
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'branchName';
    $orderBy = " $sortField $sortOrderBy";
    
    
    //$totalArray = $sectionManager->getTotalSection(); 
    //$totalArray = $branchManager->getTotalBatch($filter);
    $branchRecordArray = $branchManager->getBranchList($filter,'',$orderBy);
    $recordCount = count($branchRecordArray); 
     if($recordCount >0 && is_array($branchRecordArray) ) {
        for($i=0; $i<$recordCount; $i++ ){
            $branchRecordArray[$i]['srNo']=$i+1;
        } 
    }   
    
    $search = $REQUEST_DATA['searchbox'];
                    
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Branch Report');
    $reportManager->setReportInformation("Search By: $search");
    
    $reportTableHead                        =    array();
                    //associated key                  col.label,        col. width,      data align        
    $reportTableHead['srNo']                =    array('#',             'width="4%" align="left"', "align='left'"); 
    $reportTableHead['branchName']          =    array('Name ',         'width=40% align="left" ','align="left" ');
    $reportTableHead['branchCode']          =    array('Abbr.',         'width="30%" align="left" ','align="left"');
    $reportTableHead['miscReceiptPrefix']          =    array('Misc.Receipt Prefix',         'width="30%" align="left" ','align="left"');
    $reportTableHead['studentCount']        =    array('Student',         'width="40%" align="left" ','align="right"');  
    
    
    $reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
    $reportManager->setReportData($reportTableHead, $branchRecordArray);
    $reportManager->showReport(); 
                         
?> 
                             
                             

                            
<?php
//$History : $
?>
