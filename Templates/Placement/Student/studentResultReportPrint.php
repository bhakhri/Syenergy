<?php 
// This file is used as printing version for Company.
// Author :Dipanjan Bhattacharjee
// Created on : 20-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/Placement/StudentUploadManager.inc.php");
    $studentManager = StudentUploadManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	
    //////search functionility not needed  
    $placementDriveId=trim($REQUEST_DATA['placementDriveId']);
    $graceMarks=trim($REQUEST_DATA['graceMarks']);
    
    if($placementDriveId==''){
       die('Required Parameters Missing'); 
    }
    $conditions =' WHERE pd.placementDriveId='.$placementDriveId.' AND pd.instituteId='.$sessionHandler->getSessionVariable('InstituteId');
    
    //check for eligibility criteria,test,gd and interview
    $eliArray=$studentManager->getPlacementDriveCriteria($placementDriveId);

    $isTest=0;
    if($eliArray[0]['isTest']==1){
      $isTest=1;  
    }
    
    $isGD=0;
    if($eliArray[0]['groupDiscussion']==1){
      $isGD=1;  
    }
    
    $isInterview=0;
    if($eliArray[0]['individualInterview']==1){
      $isInterview=1;  
    }
    
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'studentName';
    $orderBy = " $sortField $sortOrderBy";         

    ////////////
    
    $studentRecordArray = $studentManager->getPlacementDriveStudentResultList($conditions,' ',$orderBy);
    $cnt=count($studentRecordArray);
    
    for($i=0;$i<$cnt;$i++) {
       $studentRecordArray[$i]['dob']=UtilityManager::formatDate($studentRecordArray[$i]['dob']);
       if($studentRecordArray[$i]['isSelected']==1){
           $selectionChecked='Yes';
       }
       else{
           $selectionChecked='No';
       }
       if($isTest==1){
        if($studentRecordArray[$i]['clearedTest']==1){
           $testChecked='Yes';
        }
        else{
           $testChecked='No';
        }
        $clearTestString=$testChecked;
       }
       else{
         $clearTestString=NOT_APPLICABLE_STRING;    
       }
       
       if($isGD==1){
        if($studentRecordArray[$i]['clearedGroupDiscussion']==1){
           $gdChecked='Yes';
        }
        else{
           $gdChecked='No';
        }
        $clearGDString=$gdChecked;
       }
       else{
         $clearGDString=NOT_APPLICABLE_STRING;    
       }
       
       if($isInterview==1){
        if($studentRecordArray[$i]['clearedInterview']==1){
           $intvChecked='Yes';
        }
        else{
           $intvChecked='No';
        }
        $clearIntvString=$intvChecked;
       }
       else{
         $clearIntvString=NOT_APPLICABLE_STRING;    
       }
        
       $valueArray[] = array_merge(
                                 array(
                                       'srNo' => ($records+$i+1),
                                       "clearSelection"=>$selectionChecked,
                                       "clearTest" =>$clearTestString,
                                       "clearGD" =>$clearGDString,
                                       "clearInterview" =>$clearIntvString 
                                       )
                                       ,$studentRecordArray[$i]
                                 );
    }

	$reportManager->setReportWidth(665);
	$reportManager->setReportHeading('Generate Student Result List Report');
    $reportManager->setReportInformation("Search By: Placement Drive : ".trim($REQUEST_DATA['placementDriveName']));
	 
	$reportTableHead				   =	array();
	$reportTableHead['srNo']		   =   array('#','width=2% align="left"', 'align="left"');
    $reportTableHead['studentName']    =   array('Student','width=15% align="left"', 'align="left"');
    $reportTableHead['dob']            =   array('DOB','width=5% align="center"', 'align="center"');
	$reportTableHead['college']	       =   array('College','width=10% align="left"', 'align="left"');
	$reportTableHead['clearTest']	   =   array('Cleared Test','width="10%" align="left" ', 'align="left"');
    $reportTableHead['clearGD']        =   array('Cleared G.D.','width="10%" align="left" ', 'align="left"');
    $reportTableHead['clearInterview'] =   array('Cleared Interview','width="15%" align="left" ', 'align="left"');
    $reportTableHead['clearSelection'] =   array('Selected','width="5%" align="left" ', 'align="left"');
    
	$reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();

// $History: testTypePrint.php $
?>