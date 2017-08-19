<?php 
//This file is used as printing version for payment history.
//
// Author :Gurkeerat Sidhu
// Created on : 30-04-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	

    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(MODEL_PATH . "/EmployeeTempManager.inc.php");
    $tempEmployeeManager = TempEmployeeManager::getInstance();

    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    $conditionsArray = array();
    $qryString = "";

    //search filter
    $search = $REQUEST_DATA['searchbox'];
    $conditions = ''; 
    if (!empty($search)) {
        if(strtolower(trim($search))=='on job'){
           $sat=1;
       }
       elseif(strtolower(trim($search))=='left job'){
           $sat=2;
       }
       else{
           $sat=-1;
       } 
        $conditions =' AND (et.tempEmployeeName LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%"  OR et.address LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%"
        OR et.contactNo LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%" OR et.status LIKE "%'.$sat.'%" OR dt.designationName LIKE "%'.trim(add_slashes($REQUEST_DATA['searchbox'])).'%")';        
    }
    
    // to limit records per page
    

    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'tempEmployeeName';

     
    $orderBy = "$sortField $sortOrderBy";


    //$totalArray = $tempEmployeeManager->getTotalTempEmployee($conditions);
    $recordArray = $tempEmployeeManager->getTempEmployeeList($conditions,'',$orderBy);

    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);

    $cnt = count($recordArray);
    $valueArray = array();
    for($i=0;$i<$cnt;$i++) {
        // add stateId in actionId to populate edit/delete icons in User Interface
        $recordArray[$i]['status']=$statusArr[$recordArray[$i]['status']];
        $recordArray[$i]['dateOfJoining']=UtilityManager::formatDate($recordArray[$i]['dateOfJoining']);
        $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$recordArray[$i]);
   }

    $reportManager->setReportWidth(665);
    $reportManager->setReportHeading('Temporary Employee Report');
	if ($search != '') {
		$reportManager->setReportInformation("SearchBy: $search");
	}
     
    $reportTableHead                        =    array();
    //associated key                  col.label,            col. width,      data align
    $reportTableHead['srNo']                    =    array('#','width="3%" align="left"', 'align="left" ');
    $reportTableHead['tempEmployeeName']        =   array('Employee Name','width=18% align="left"', 'align="left"');
    $reportTableHead['address']                 =    array('Address','width="18%" align="left" ', 'align="left"');
    $reportTableHead['contactNo']               =   array('Contact No.','width="18%" align="right" ', 'align="right"');
    $reportTableHead['dateOfJoining']           =   array('Date Of Joining','width="18%" align="center" ', 'align="center"');
    $reportTableHead['status']                  =   array('Status','width="20%" align="left" ', 'align="left"');
    $reportTableHead['designationName']         =   array('Designation','width="18%" align="left" ', 'align="left"');
    $reportManager->setRecordsPerPage(30);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport();


?>