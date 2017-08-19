<?php
//This file is used as printing version for Student Detail Fine Collection Summary Report.
//
// Author :Jaineesh
// Created on : 07-July-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>

<?php
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

	require_once(MODEL_PATH . "/FineManager.inc.php");
	$fineManager = FineManager::getInstance();

	 $instituteId  = trim($REQUEST_DATA['instituteId']); 
    $degreeId  = trim($REQUEST_DATA['degreeId']); 
    $branchId  = trim($REQUEST_DATA['branchId']); 
    $batchId  = trim($REQUEST_DATA['batchId']); 
    $classId  = trim($REQUEST_DATA['classId']); 
    $fromDate  = trim($REQUEST_DATA['fromDate']); 
    $toDate  = trim($REQUEST_DATA['toDate']); 
    $receiptNo  = htmlentities(add_slashes(trim($REQUEST_DATA['receiptNo']))); 
    $rollNo  = htmlentities(add_slashes(trim($REQUEST_DATA['rollNo']))); 
    $studentName  = htmlentities(add_slashes(trim($REQUEST_DATA['studentName']))); 
    $fatherName = htmlentities(add_slashes(trim($REQUEST_DATA['fatherName']))); 
    
    $startingRecord  = htmlentities(add_slashes(trim($REQUEST_DATA['startingRecord']))); 
    $totalRecords = htmlentities(add_slashes(trim($REQUEST_DATA['totalRecords']))); 
    
    if($startingRecord=='') {
      $startingRecord = 0; 
    }
    if($startingRecord>0) {
      $startingRecord=$startingRecord-1;  
    }
    else {
      $startingRecord=0;  
    }
    if($totalRecords=='') {
       $totalRecords = 500; 
    }
    $limit  = ' LIMIT '.$startingRecord.','.$totalRecords;

    $condition = "";
    $whereCondition='';
    
    if($instituteId!='') {
      $condition .= " AND c.instituteId = '$instituteId' ";
    }
    if($degreeId!='') {
      $condition .= " AND c.degreeId = '$degreeId' ";
    }
    if($branchId!='') {
      $condition .= " AND c.branchId = '$branchId' ";
    }
    if($batchId!='') {
      $condition .= " AND c.batchId = '$batchId' ";
    }
    if($classId!='') {
      $condition .= " AND frm.feeClassId = '$classId' ";
    }
    if($rollNo!='') {
      $condition .= " AND (s.rollNo LIKE '$rollNo%' OR s.regNo LIKE '$rollNo%') ";
    }
    if($studentName!='') {
      $condition .= " AND (CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) LIKE '$studentName%') ";
    }
    if($fatherName!='') {
      $condition .= " AND s.fatherName LIKE '$fatherName%' ";
    }
    if($receiptNo!='') {
      $condition .= " AND frd.receiptNo LIKE '$receiptNo%' ";
    }
    
    if($fromDate!='' && $toDate!='') {
      $condition = " AND (DATE_FORMAT(a.receiptDate,'%Y-%m-%d') BETWEEN '$fromDate' and '$toDate') ";
    }

    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    

	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
    
     $orderBy = "$sortField $sortOrderBy";
	
/*	//$studentId = $REQUEST_DATA['studentId'];
	//$classId = $REQUEST_DATA['classId'];
	$startDate = $REQUEST_DATA['startDate'];
	$toDate = $REQUEST_DATA['toDate'];
	$rollNo = $REQUEST_DATA['rollNo'];
	$studentName = $REQUEST_DATA['studentName'];


	$filter = "AND (fr.receiptDate BETWEEN '$startDate' AND '$toDate')";
	if ($rollNo != '') {
		$filter .= " AND st.rollNo LIKE \"%$rollNo%\"";
	}
	if ($studentName != '') {
		$filter .= " AND CONCAT(st.firstName,' ',st.lastName) LIKE '%$studentName%'";
	}
		//$fineTotalCollectionArray = $fineManager->getStudentWiseFineCollectionReportDetail($conditions,'',$orderBy);
*/	$collectionFineStudentDetailRecordArray = $fineManager->getStudentDetailFineCollectionReportDetailNew($condition,$limit,$orderBy);
	$cnt = count($collectionFineStudentDetailRecordArray);

	$valueArray = array();

    for($i=0;$i<$cnt;$i++) {
		$collectionFineStudentDetailRecordArray[$i]['fineDate']  = UtilityManager::formatDate($collectionFineStudentDetailRecordArray[$i]['fineDate']);
		$collectionFineStudentDetailRecordArray[$i]['receiptDate'] = UtilityManager::formatDate($collectionFineStudentDetailRecordArray[$i]['receiptDate']);
        $valueArray[] = array_merge(array('srNo' => ($i+1) ),$collectionFineStudentDetailRecordArray[$i]);
	}

	$fineCategoryName = $collectionFineStudentDetailRecordArray[0]['fineCategoryName'];
	//$amount = $fineTotalCollectionArray[0]['amount'];
	//echo ($amount);
	//$className = $offenseArray[0]['className'];
   // $name=$REQUEST_DATA['studentName'];
    //$roll=$REQUEST_DATA['rollNo'];
    
    if($name=='') {
       $name=NOT_APPLICABLE_STRING; 
    }
    if($roll=='') {
       $roll=NOT_APPLICABLE_STRING; 
    }
    $searchCrieria .= " <B>Name:</B>".$name;
    $searchCrieria .= " <B>Roll No.:</B>".$roll; 
	$reportManager->setReportWidth(800);
	$reportManager->setReportHeading('Student Detailed Fine Collection Report');
	$reportManager->setReportInformation("Search By : $searchCrieria");

	$reportTableHead						=	array();
					//associated key				  col.label,			col. width,	  data align
	$reportTableHead['srNo']				=	array('#',					'width=4% align="left"', 'align="left"');
	$reportTableHead['rollNo']				=	array('Roll No.',		'width=12% align="left"', 'align="left"');
	$reportTableHead['studentName']			=	array('Name',		'width=12% align="left"', 'align="left"');
	$reportTableHead['fineCategoryName']	=	array('Fine Category',	'width="15%" align="left" ', 'align="left"');
	$reportTableHead['amount']				=	array('Amount',		'width="10%" align="right"', 'align="right"');
	$reportTableHead['fineDate']			=	array('Fine Date',			'width="12%" align="center"', 'align="center"');
	$reportTableHead['receiptDate']			=	array('Fine Collect Date',	'width="18%" align="center"', 'align="center"');
	$reportTableHead['employeeName']		=	array('Fined By',			'width="20%" align="left"', 'align="left"');
			
	$reportManager->setRecordsPerPage(40);
	$reportManager->setReportData($reportTableHead, $valueArray);
	$reportManager->showReport();


//$History : $
//
//
?>
