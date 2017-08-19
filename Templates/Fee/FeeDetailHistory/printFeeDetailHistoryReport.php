 <?php 
//--------------------------------------------------------
// This file is used as printing version for  Fee Detail Report.
// Created on : 7-May-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
?>
<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','FeeDetailHistoryReport');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
    
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();  

 require_once(MODEL_PATH . "/Fee/FeeDetailHistoryReportManager.inc.php");
    $feeDetailHistoryReportManager = FeeDetailHistoryReportManager::getInstance();
    

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
	 $paidReport=trim($REQUEST_DATA['paidReport']); 
    $feeReport=trim($REQUEST_DATA['feeReport']); 
  
    $condition = "";
    $having='';
    
   

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
    
    if($rollNo!='') {
      $condition .= " AND (s.rollNo LIKE '$rollNo%' OR s.regNo LIKE '$rollNo%') ";
    }
    if($studentName!='') {
      $condition .= " AND (CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) LIKE '$studentName%') ";
    }
    if($fatherName!='') {
      $condition .= " AND s.fatherName LIKE '$fatherName%' ";
    }
   
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
    
    $orderBy = " $sortField $sortOrderBy";
    
	
		$concession=0;
    	if($paidReport==0){//report for all paid fee student list
				 if($classId!='') {
				  $condition .= " AND frd.classId = '$classId' ";
				  }
				if($feeReport==1){//academic Paid Fee
					$condition .="AND frd.feeType IN(1,4)";
					$feeTypeCondition ="AND fl.ledgerTypeId = 1" ;
					$totalPaidFeeArray =$feeDetailHistoryReportManager->getTotalAcademicPaidFee($condition,$having,$limit,$orderBy);
				 }else if($feeReport==2){//transport Paid Fee 
				     			
					$totalPaidFeeArray =$feeDetailHistoryReportManager->getTotalTransportPaidFee($condition,$having,$limit,$orderBy);
				}else if($feeReport==3){//hostel Paid Fee
					$condition .= " AND IFNULL(frd.feeReceiptId,'') != ''   ";  //paid  
												
					$totalPaidFeeArray =$feeDetailHistoryReportManager->getTotalHostelPaidFee($condition,$having,$limit,$orderBy);
				} else{    				
					$totalPaidFeeArray =$feeDetailHistoryReportManager->getTotalPaidFee($condition,$having,$limit,$orderBy);						
				}			
				 $cnt1 = count($totalPaidFeeArray);
				$cnt = count($totalPaidFeeArray);
				for($i=0;$i<$cnt;$i++) {
				
				 $unPaidFees = 0;
				$remarks = '';
				$unPaidFees = ($totalPaidFeeArray[$i]['totalFees'] - $totalPaidFeeArray[$i]['paidAmount']);
				$unPaidFees = number_format($unPaidFees, 2, '.', '');
				$valueArray[] = array_merge(array('srNo'=>($records+$i+1),
								                'unPaidFees'=>$unPaidFees,
												'concession'=> $concession),
	                                      $totalPaidFeeArray[$i]);
				
				
			}
			      			
    	 } else if($paidReport==1) {//report for all un-paid fee student list
	    	 $concession=0;
	    	   if($classId!='') {
			      $condition .= " AND frm.feeClassId = '$classId' ";
			    }
    	
    	 		if($feeReport==1){//academic
    			  $totalUnPaidFeeArray =$feeDetailHistoryReportManager->getPendingAcademicFee($condition,$limit,$orderBy);
			
    			}else if($feeReport==2){//transport Un-paid Fee 
    			
    			 $having .= " HAVING 
                       (brsm.routeCharges) <> paidAmount ";
    			  $totalUnPaidFeeArray =$feeDetailHistoryReportManager->getTotalTransportPaidFee($condition,$having,$limit,$orderBy);
			
    			}else if($feeReport==3){//hostel Un-paid Fee     		 	  
				   
     			   $having .= " HAVING 
                       (hs.hostelCharges+hs.securityAmount) <> paidAmount ";
			
    			  $totalUnPaidFeeArray =$feeDetailHistoryReportManager->getTotalHostelPaidFee($condition,$having,$limit,$orderBy);
			
    			} else if($feeReport==0){    				
				$totalUnPaidFeeArray =$feeDetailHistoryReportManager->getUnPaidFee($condition,$limit,$orderBy);
    			}
			  
			   $cnt1 = count($totalUnPaidFeeArray);
			$cnt = count($totalUnPaidFeeArray);
			for($i=0;$i<$cnt;$i++) {				
			
			 $unPaidFees = 0;
			$remarks = '';
			$unPaidFees = ($totalUnPaidFeeArray[$i]['totalFees'] - $totalUnPaidFeeArray[$i]['paidAmount']);
			
			$unPaidFees = number_format($unPaidFees, 2, '.', '');
			
			$valueArray[] = array_merge(array('srNo'=>($records+$i+1),
							                'unPaidFees'=>$unPaidFees,
							                'concession'=>$concession
							                ),
                                      $totalUnPaidFeeArray[$i]);
			
			
		}
		      			
		
    	 } 
    	
    $dt = UtilityManager::formatDate(date('Y-m-d'));               
    $reportManager->setReportWidth(800);
    $reportManager->setReportHeading('Fee Detail History Report');
    //$reportManager->setReportInformation("class : $className");
    $reportManager->setReportInformation("As on $dt");

	 
                   
    $reportTableHead                        =    array();
                    //associated key                  col.label,            col. width,      data align        
    $reportTableHead['srNo']		=    	array('#','width="2%" align="left"', "align='left'");
    $reportTableHead['studentName']	=    	array('Student Name','width="6%" align="left" ','align="left"');
    $reportTableHead['rollNo']		=    	array('Roll No.','width="5%" align="left" ','align="left"');
    $reportTableHead['className']		=    	array('Class',' width="12%" align="left" ','align="left"'); 
     $reportTableHead['feeTypeOf']		=    	array('Pay Fee Of',' width="7%" align="left" ','align="left"'); 
	  $reportTableHead['totalFees']		=    	array('Total Fees',' width="7%" align="left" ','align="left"'); 
     $reportTableHead['ledgerDebit']	=    	array('Debit',' width="5%" align="right" ','align="right"');
      $reportTableHead['ledgerCredit']	=    	array('Credit',' width="5%" align="right" ','align="right"');
     $reportTableHead['concession']	=    	array('Concession',' width="5%" align="right" ','align="right"');
    $reportTableHead['fine']	=    	array('Fine',' width="7%" align="right" ','align="right"');
    $reportTableHead['paidAmount']	=    	array('Paid',' width="7%" align="right" ','align="right"');
    $reportTableHead['unPaidFees']	=    	array('Unpaid',' width="7%" align="right" ','align="right"');
        
    $reportManager->setRecordsPerPage(40);
    $reportManager->setReportData($reportTableHead, $valueArray);
    $reportManager->showReport(); 

//$History : $
?>
