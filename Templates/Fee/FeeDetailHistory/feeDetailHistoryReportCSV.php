 <?php 
//--------------------------------------------------------
//This file is used as printing CSV OF Fee Detail Report 
// Created on : 7-May-12
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
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

   
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
    
    $orderBy = " $sortField $sortOrderBy";
    
	  $dt = UtilityManager::formatDate(date('Y-m-d'));
    $csvData  ='';
    $csvData .= ",,,,Fee Detail History Report\n";
    $csvData .= ",,,,As on $dt\n";
    $csvData .="\n";
	        
    $csvData .="#,Student Name,Roll No.,Class,Pay Fee Of,Total Fees, Debit, Credit,Concession,Fine,Total Fees, Paid,Unpaid";
    $csvData .="\n";
	
  
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
				
								$remarks = '';
					
					$csvData .= ($i+1).",";
					$csvData .= $totalPaidFeeArray[$i]['studentName'].",";
					$csvData .= $totalPaidFeeArray[$i]['rollNo'].",";
					$csvData .= $totalPaidFeeArray[$i]['className'].",";
					$csvData .= $totalPaidFeeArray[$i]['feeTypeOf'].",";
					$csvData .= $totalPaidFeeArray[$i]['totalFees'].",";
					$csvData .= $totalPaidFeeArray[$i]['ledgerDebit'].",";
					$csvData .= $totalPaidFeeArray[$i]['ledgerCredit'].",";
					$csvData .= $totalPaidFeeArray[$i]['concession'].",";
					$csvData .= $totalPaidFeeArray[$i]['fine'].",";
					$csvData .= $totalPaidFeeArray[$i]['paidAmount'].",";		
					$csvData .=  number_format($unPaidFees, 2, '.', '').",";
					$csvData .= "\n";		
				
			 
			    if($cnt == 0){
					$csvData .=" No Data Found ";
				}
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
			
			$valueArray[]= array_merge(array('srNo'=>($records+$i+1),
							                'unPaidFees'=>$unPaidFees,
							                'concession'=>$concession
							                ),
                                      $totalUnPaidFeeArray[$i]);
		  
		$csvData .= ($i+1).",";
		$csvData .= $totalUnPaidFeeArray[$i]['studentName'].",";
		$csvData .= $totalUnPaidFeeArray[$i]['rollNo'].",";
		$csvData .= $totalUnPaidFeeArray[$i]['className'].",";
		$csvData .= $totalUnPaidFeeArray[$i]['feeTypeOf'].",";
		$csvData .= $totalUnPaidFeeArray[$i]['totalFees'].",";
		$csvData .= $totalUnPaidFeeArray[$i]['ledgerDebit'].",";
		$csvData .= $totalUnPaidFeeArray[$i]['ledgerCredit'].",";
		$csvData .= $totalUnPaidFeeArray[$i]['concession'].",";
		$csvData .= $totalUnPaidFeeArray[$i]['fine'].",";
		$csvData .= $totalUnPaidFeeArray[$i]['paidAmount'].",";		
		$csvData .=  number_format($unPaidFees, 2, '.', '').",";
		$csvData .= "\n";		
	
 
    if($cnt == 0){
		$csvData .=" No Data Found ";
	}
			
			
		}
		      			
		
    	 } 

    
  

   
			
	
	
 //print_r($csvData);
ob_end_clean();
header("Cache-Control: public, must-revalidate");
// We'll be outputting a PDF
header('Content-type: application/octet-stream');
header("Content-Length: " .strlen($csvData) );
// It will be called downloaded.pdf
header('Content-Disposition: attachment;  filename="'.'feeDetailHistoryReport.csv'.'"');
// The PDF source is in original.pdf
header("Content-Transfer-Encoding: binary\n");
echo $csvData;  
die;         
//$History : $
?>
