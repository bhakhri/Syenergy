
<?php
 
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();  

	require_once(MODEL_PATH . "/Fee/ConsolidateFeeReportManager.inc.php");
	$ConsolidateFeeReportManager = ConsolidateFeeReportManager::getInstance();

	$valueArray = array();
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
    $paidFee = trim($REQUEST_DATA['paidFee']); //used to print fee-academic wise-1,hostel,wise-2,transport wise-3,all-4.
    
    
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
         if($paidFee=='1') {
      $condition .= " AND frd.feeType IN(1)";
   		 }else if($paidFee=='2') {
      $condition .= " AND frd.feeType IN(2)";
   		 }else if($paidFee=='3') {
      $condition .= " AND frd.feeType IN(3) ";
   		 }else if($paidFee=='4') {
      $condition .= " AND frd.feeType IN(4) ";
   		 }
    
    
	
    if($fromDate!='' && $toDate!='') {
      $condition .= " AND (DATE_FORMAT(frd.receiptDate,'%Y-%m-%d') BETWEEN '$fromDate' and '$toDate') ";
      $whereCondition = " WHERE (DATE_FORMAT(a.receiptDate,'%Y-%m-%d') BETWEEN '$fromDate' and '$toDate') ";
    }
   
	$sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
	$sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';

	$orderBy = " $sortField $sortOrderBy";
    
	$feeDataArray = $ConsolidateFeeReportManager->getFeeDetailsNew($condition,$whereCondition,$limit,$REQUEST_DATA['sortOrderBy'],$REQUEST_DATA['sortField']);
	//$feeDataArray  = $ConsolidateFeeReportManager->getFeeDetailsPrint($fromDate,$toDate,$condition,$orderBy);
	
	$recordCount = count($feeDataArray);
	
			$netTotal1=0;
			$netTotal2 =0;
			$netTotal3 =0;
			$netTotal4 =0;
	if($recordCount >0 && is_array($feeDataArray)){
		for($i=0; $i<$recordCount; $i++ ) {
			$totalReceipt = 0;
			$totalReceipt = ($feeDataArray[$i]['DDAmount'] + $feeDataArray[$i]['checkAmount'] + $feeDataArray[$i]['cashAmount']);
			$feeDataArray[$i]['DDAmount'] = number_format($feeDataArray[$i]['DDAmount'], 2, '.', '');
			$feeDataArray[$i]['checkAmount'] = number_format($feeDataArray[$i]['checkAmount'], 2, '.', '');
			$feeDataArray[$i]['cashAmount'] = number_format($feeDataArray[$i]['cashAmount'], 2, '.', '');
			$totalReceipt = number_format($totalReceipt, 2, '.', '');
			$valueArray[] = array_merge(array('srNo'=>($records+$i+1),'totalReceipt'=>$totalReceipt),$feeDataArray[$i]);
		
			$netTotal1 += $feeDataArray[$i]['cashAmount'];
			$netTotal2 += $feeDataArray[$i]['DDAmount'];
			$netTotal3 += $feeDataArray[$i]['checkAmount'];
			 $concession += $feeDataArray[$i]['concession'];
			$netTotal4 += $totalReceipt;
				$fine += $feeDataArray[$i]['fine'];				
		}
			$valueArray[] = array_merge(array('srNo'=>'',
                                        'instituteAbbr'=>"<b>Grand Total</b>",
                                          'concession'=>  "<b>".number_format($concession,2,'.','')."</b>",
                                             'fine'=>  "<b>".number_format($fine,2,'.','')."</b>",
                                        'cashAmount'=>  "<b>".number_format($netTotal1,2,'.','')."</b>",
                                        'DDAmount'=>   "<b>".number_format($netTotal2,2,'.','')."</b>",
                                        'checkAmount'=>  "<b>".number_format($netTotal3,2,'.','')."</b>",
                                        'totalReceipt'=>  "<b>".number_format($netTotal4,2,'.','')."</b>"
                                        )); 
       
	}
                    
    $dt = UtilityManager::formatDate(date('Y-m-d'));
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Consolidated Fee Collection Report');
    $reportManager->setReportInformation("As on $dt");
                   
    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']		=    	array('#','width="2%" align="left"', "align='left'");
    $reportTableHead['instituteAbbr']	=    	array('Institute Abbr.',' width=7% align="left" ','align="left" ');
	 $reportTableHead['concession']	=    	array('Concession',' width=7% align="right" ','align="right" ');
	  $reportTableHead['fine']	=    	array('Fine',' width=7% align="right" ','align="right" ');	
    $reportTableHead['cashAmount']	=    	array('Cash Amount',' width="9%" align="right" ','align="right"');
    $reportTableHead['DDAmount']	=    	array('DD Amount',' width="8%" align="right" ','align="right"');
    $reportTableHead['checkAmount']	=    	array('cheque Amount',' width="10%" align="right" ','align="right"');
    $reportTableHead['totalReceipt']	=    	array('Total Amount',' width="10%" align="right" ','align="right"');
        
    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
