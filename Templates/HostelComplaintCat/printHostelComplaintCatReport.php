 <?php 
//This file is used as printing version for Hostel Complaint Category.
//
// Author :Jaineesh
// Created on : 24-Aug-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php

   global $FE;   
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
 
    require_once(MODEL_PATH . "/HostelComplaintCatManager.inc.php");
    $complaintManager = ComplaintManager::getInstance();

  
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  


	    
	/// Search filter /////  
    if(UtilityManager::notEmpty($REQUEST_DATA['searchbox'])) {
		$search = $REQUEST_DATA['searchbox'];
       $filter = ' WHERE (categoryName LIKE "'.add_slashes($REQUEST_DATA['searchbox']).'%")';         
    }
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'categoryName';
    
     $orderBy = " $sortField $sortOrderBy";         

    ////////////
    


    $complaintRecordArray = $complaintManager->getComplaintCategoryList('',$filter, $orderBy,'');
       
    $cnt = count($complaintRecordArray);
    $valueArray = array();
    
    for($i=0;$i<$cnt;$i++) {
       $valueArray[] = array('srNo' => $i+1 ,
                             'categoryName' => $complaintRecordArray[$i]['categoryName']
                            );
    }
   
    
                      
    $reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Complaint Category Report ');
    $reportManager->setReportInformation("Search By : $search");
    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']				=    array('#','width="4%" align="left"', "align='left'");
    $reportTableHead['categoryName']		=    array('Category Name ',' width=85% align="left" ','align="left" ');
    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
//
//*****************  Version 1  *****************
//User: Gurkeerat Sidhu     Date: 18/04/09   Time: 5:43p
//Updated in $/Leap/Source/Template/Hostel
//added new fields (floorTotal,hostelType,totalCapacity) 
?>
