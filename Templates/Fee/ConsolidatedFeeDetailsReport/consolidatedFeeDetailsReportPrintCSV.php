 <?php 
//This file is used as printing CSV OF Fee Details  Report 
// Created on : 7-May-12
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
 
?>
<?php 
	require_once(MODEL_PATH . "/Fee/ConsolidatedFeeDetailsManager.inc.php");
	$ConsolidatedFeeDetailsManager = ConsolidatedFeeDetailsManager::getInstance();
	
	function parseCSVComments($comments) {
         	$comments = str_replace('"', '""', $comments);
         	$comments = str_ireplace('<br/>', "\n", $comments);
         	if(eregi(",", $comments) or eregi("\n", $comments)) {
           		return '"'.$comments.'"'; 
         	} 
         	else {
           	return chr(160).$comments;    
         	}
    	}
    	
	
	global $sessionHandler;
	$queryDescription =''; 
	$errorMessage ='';

	/*  if(!isset($REQUEST_DATA['instituteId']) || trim($REQUEST_DATA['instituteId']) == '') {
		    $errorMessage .= SELECT_INSTITUTE."\n";
	    }
	    if($errorMessage == '' && (!isset($REQUEST_DATA['studyPeriodId']) || trim($REQUEST_DATA['studyPeriodId']) == '')) {
		    $errorMessage .= SELECT_STUDYPERIOD."\n";
	    }
	    if($errorMessage == '' && (!isset($REQUEST_DATA['feeOf']) || trim($REQUEST_DATA['feeOf']) == '')) {
		    $errorMessage .= "Select Fee Of\n";
	    }
    */
    
    $instituteId = trim($REQUEST_DATA['instituteId']);
   $studyPeriodId = trim($REQUEST_DATA['studyPeriodId']);
    $feeOf = $REQUEST_DATA['feeOf']; // 1-> ACd , 2-> Hstl , 3->Transport 
    $fromDate = $REQUEST_DATA['fromDate'];
    $toDate = $REQUEST_DATA['toDate'];	
	
    $degreeId  = trim($REQUEST_DATA['degreeId']); 
    $branchId  = trim($REQUEST_DATA['branchId']); 
    $batchId  = trim($REQUEST_DATA['batchId']); 
    $classId  = trim($REQUEST_DATA['classId']);    
    $receiptNo  = htmlentities(add_slashes(trim($REQUEST_DATA['receiptNo']))); 
    $rollNo  = htmlentities(add_slashes(trim($REQUEST_DATA['rollNo']))); 
    $studentName  = htmlentities(add_slashes(trim($REQUEST_DATA['studentName']))); 
    $fatherName = htmlentities(add_slashes(trim($REQUEST_DATA['fatherName']))); 

 $frmCondition = "";

    if($instituteId!='') {
      $frmCondition .= " AND c.instituteId = '$instituteId' ";
    }
    if($degreeId!='') {
      $frmCondition .= " AND c.degreeId = '$degreeId' ";
    }
    if($branchId!='') {
      $frmCondition .= " AND c.branchId = '$branchId' ";
    }
    if($batchId!='') {
      $frmCondition .= " AND c.batchId = '$batchId' ";
    }
    if($classId!='') {
      $frmCondition .= " AND frm.feeClassId = '$classId' ";
    }
    if($rollNo!='') {
      $frmCondition .= " AND (s.rollNo LIKE '$rollNo%' OR s.regNo LIKE '$rollNo%') ";
    }
    if($studentName!='') {
      $frmCondition .= " AND (CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) LIKE '$studentName%') ";
    }
    if($fatherName!='') {
      $frmCondition .= " AND s.fatherName LIKE '$fatherName%' ";
    }
    if($receiptNo!='') {
      $frmCondition .= " AND frd.receiptNo LIKE '$receiptNo%' ";
    }
    
    
  
	// to limit records per page    
	$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
	$records    = ($page-1)* RECORDS_PER_PAGE;
	$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

	if(trim($errorMessage) == ''){
		
		/*
		// fetching student Fee
		$frmCondition = '';
		if($instituteId != 'all'){
		  $frmCondition =" AND c.instituteId = '$instituteId' ";
		}

		if($studyPeriodId != ''){
		  $frmCondition .=" AND	c.studyPeriodId = '$studyPeriodId' ";
		}
		*/
		
		$frdCondition ='';
		$fhcCondition ='';
		if($fromDate !='' && $toDate !=''){
		  $frmCondition .=" AND date_format(frm.dated, '%Y-%m-%d' ) between '$fromDate' AND '$toDate'"; 
		  $frdCondition = " AND date_format(frd.receiptDate, '%Y-%m-%d' ) between '$fromDate' AND '$toDate'";
		  $fhcCondition = " AND date_format(fhc.receiptDate, '%Y-%m-%d' ) between '$fromDate' AND '$toDate'";
		  $flCondition = "  AND date_format(fl.date, '%Y-%m-%d' ) between '$fromDate' AND '$toDate'";
		}
		$feeDataArray = $ConsolidatedFeeDetailsManager->getFeeDetails($frmCondition,$frdCondition,$fhcCondition,$flCondition);
		
		$classArray = array();
		foreach($feeDataArray as $value){
			$classArray[] =  $value['classId'];
		}
		
		$finalFeeArray = array();
		$classArray = array_unique($classArray);
		$quotaWiseTotalFee = array();
		$uniqueArr= array();
		foreach($classArray as $value){
			$className ='';
			$totalStudents =0;
			$totalAmount =0;
			$paidStudents =0;
			$paidFees =0;
			$totalPartialPaidStudents = 0;
			$partialPaidFees =0;
			$unPaidStudents =0;
			$unpaidFees =0;
			$totalConcession =0;
			$totalFine = 0;
			$finePaid = 0;
			$transportFee = 0;
			$hostelFee = 0;
			$hostelSecurity=0;
			$leetAcademicFee = 0;
			$nonLeetAcademicFee = 0;
			$leetAndNonAcademicFee = 0;
			$migerationAcademicFee = 0;
			foreach($feeDataArray as $key => $value1){
			
				if($value == $value1['classId']){
					$className = $value1['className'];
					if($feeOf == 1){
						$totalStudents++;
						$totalAmount +=floatVal($value1['totalAcademicFees']) + floatVal($value1['academicFine'] -floatVal($value1['concession']));
						$totalConcession +=floatVal($value1['concession']);
						$totalFine += floatVal($value1['academicFine']);
						$finePaid += floatVal($value1['academicFinePaid']);
						if((floatVal($value1['totalAcademicFees']) + floatVal($value1['academicFine'])) <= (floatVal($value1['paidAcademicFee'] + $value1['concession']))){ // fully paid students
							$paidStudents++;
							$paidFees +=floatVal($value1['paidAcademicFee']);
						}
						else if(floatVal($value1['paidAcademicFee'] + $value1['concession']) == 0){ // Nt Paid Students
							$unPaidStudents++;
						}
						else if((floatVal($value1['totalAcademicFees']) + floatVal($value1['academicFine'])) > (floatVal($value1['paidAcademicFee'] + $value1['concession']))){ // partial paid Students
							$totalPartialPaidStudents++;
							$partialPaidFees +=floatVal($value1['paidAcademicFee']);
						}
						
						// code to find Fees According to Fee Cases
						
						if(($leetAcademicFee > floatVal($value1['totalAcademicFees'])) && $value1['isLeet'] == 1){
							$leetAcademicFee = floatVal($value1['totalAcademicFees']);
						}
						else if($leetAcademicFee == '0' && $value1['isLeet'] == 1){
							$leetAcademicFee = floatVal($value1['totalAcademicFees']);
						}
					
						if(($nonLeetAcademicFee > floatVal($value1['totalAcademicFees'])) && $value1['isLeet'] == 0){
							$nonLeetAcademicFee = floatVal($value1['totalAcademicFees']);
							
						}
						else if($nonLeetAcademicFee == '0' && $value1['isLeet'] == 0){
							$nonLeetAcademicFee = floatVal($value1['totalAcademicFees']);
						}
						
						if(($leetAndNonAcademicFee > floatVal($value1['totalAcademicFees'])) && $value1['isLeet'] == 2){
							$leetAndNonAcademicFee = floatVal($value1['totalAcademicFees']);
							
						}
						else if($leetAndNonAcademicFee == '0' && $value1['isLeet'] == 2){
							$leetAndNonAcademicFee = floatVal($value1['totalAcademicFees']);
						}
						
						if(($migerationAcademicFee > floatVal($value1['totalAcademicFees'])) && $value1['isMigeration'] == 1){
							$migerationAcademicFee = floatVal($value1['totalAcademicFees']);
						}
						else if($migerationAcademicFee == '0' && $value1['isMigeration'] == 1){
							$migerationAcademicFee = floatVal($value1['totalAcademicFees']);
						}
						
					}
					else if($feeOf == 2){ // hostel Fee
						if($value1['hostelFees'] > 0){
							$totalStudents++;
							$totalAmount +=floatVal($value1['hostelFees']) + floatVal($value1['hostelFine']) + floatVal($value1['hostelSecurity']);
							$totalConcession =0;
							$totalFine += floatVal($value1['hostelFine']);
							$finePaid += floatVal($value1['hostelFinePaid']);
							$hostelSecurity = floatVal($value1['hostelSecurity']);
							if((floatVal($value1['hostelFees']) + floatVal($value1['hostelFine']) + $hostelSecurity) <= (floatVal($value1['hostelFeePaid'])+ floatVal($value1['hostelFine']) + floatVal($value1['hostelSecurityPaid']))){ // fully paid students
								$paidStudents++;
								$paidFees +=floatVal($value1['hostelFeePaid']);
							}
							else if(floatVal($value1['hostelFeePaid']) == 0){ // Nt Paid Students
								$unPaidStudents++;
							}
							else if((floatVal($value1['hostelFees']) + floatVal($value1['hostelFine']) + $hostelSecurity) > (floatVal($value1['hostelFeePaid']) + floatVal($value1['hostelFinePaid']) + floatVal($value1['hostelSecurityPaid']))){ // partial paid Students
								$totalPartialPaidStudents++;
								$partialPaidFees +=floatVal($value1['hostelFeePaid']);
							}
							
							if($hostelFee > floatVal($value1['hostelFees'])){
								$hostelFee = floatVal($value1['hostelFees']);
							}
							else if($hostelFee == 0){
								$hostelFee = floatVal($value1['hostelFees']);
							}
						}	
					}
					else if($feeOf == 3){ // Transport Fee
						if($value1['transportFees'] > 0){
							$totalStudents++;
							$transportFee = floatVal($value1['transportFees']);
							$totalAmount +=floatVal($value1['transportFees']) + floatVal($value1['tranportFine']);
							$totalConcession =0;
							$totalFine += floatVal($value1['tranportFine']);
							$finePaid += floatVal($value1['transportFinePaid']);
							if(floatVal($value1['transportFees'] + floatVal($value1['tranportFine'])) <= (floatVal($value1['transportFeePaid'])+ + floatVal($value1['transportFinePaid']))){ // fully paid students
								$paidStudents++;
								$paidFees +=floatVal($value1['transportFeePaid']);
							}
							else if(floatVal($value1['transportFeePaid']) == 0){ // Nt Paid Students
								$unPaidStudents++;
							}
							else if((floatVal($value1['transportFees']) + floatVal($value1['tranportFine'])) > (floatVal($value1['transportFeePaid']) + floatVal($value1['transportFinePaid']))){ // partial paid Students
								$totalPartialPaidStudents++;
								$partialPaidFees +=floatVal($value1['transportFeePaid']);
							}
						}
					}
				}
					
			}
			
			$unPaidFees = ($totalAmount) - ($paidFees + $partialPaidFees + $totalConcession + $finePaid);
			$finalFeeArray[] = array('className'=>"$className",'totalStudents'=>"$totalStudents",'totalAmount'=>"$totalAmount",'paidStudents'=>"$paidStudents",
						'paidFees'=>"$paidFees",'partialPaidStduents'=>"$totalPartialPaidStudents",'partialPaidFees'=>"$partialPaidFees",
						'unPaidStudents'=>"$unPaidStudents",'unPaidFees'=>"$unPaidFees",'totalConcession'=>"$totalConcession",'totalFine'=>$totalFine
						,'totalFinePaid'=>$finePaid,'leetAcdFee'=>$leetAcademicFee,'nonLeetAcdFee'=>$nonLeetAcademicFee,
						'leetAndNonLeetAcdFee'=>$leetAndNonAcademicFee,'migerationAcdFee'=>$migerationAcademicFee,'transportFee'=>$transportFee,
						'hostelFee'=>$hostelFee,'hostelSecurity'=>$hostelSecurity,'hostelFee'=>$hostelFee);
		
		}
		
		$data ='';
		$nextLineData ='';
		$nextLineData='';
		if($feeOf == 1){
			$data =",Fee Case's,,,";
			$nextLineData =",,,Leet & Non Leet Fee,Non Leet Fee,Leet Fee,Migeration Fee\n";
		}
		
		$feeValueData='';
		$totalStudents =0;
                $totalConcession = 0;
                $totalFine =0;
                $totalFinePaid =0;
                $paidStudents =0;
                $paidFees =0;
                $partialPaidStduents =0;
                $partialPaidFees =0;
                $unPaidStudents =0;
                $unPaidFees =0;
                $totalAmountDue = 0;
                $dateCheck =' up To '.date('d-m-Y');
                if($fromDate !='' && $toDate !=''){
                	$dateCheck =" From $fromDate To $toDate";
                }
		$csvData  ='';
		$csvData .= ",,,,,,,Consolidated Fee Details Report\n";
		$csvData .= ",,,,,,,Institute: $instituteName $dateCheck\n";
		$csvData .=",,,,,,,Fee Of : ".$REQUEST_DATA['feeOfName']."\n\n";
		$csvData .="#,Class Name,Total Students $data,Total Concession,Total Fine Due,Total Amount Due (Total Fees + Total Fine Due + Misc Charges + Security - Concession),Total Fine Paid,Fully Paid Students Count,Fully Paid Fees,Partial Paid Students Count,Partail Paid Fees,Unpaid Students Count,Unpaid Fees(Total Amount Due + Total Fine)- (Fully Paid Fees + Partial Paid Fees + Total Concession + Total Fine Paid)\n".$nextLineData;
		if(count($finalFeeArray) == 0){
			$csvData .=",,,,,,,,,NO Data Found";
			UtilityManager::makeCSV($csvData,'ConsolidatedFeeDetailsReport.csv');
			die;
		}                    
		foreach($finalFeeArray as $key =>$value){
			$csvData .=parseCSVComments($key +1);
			$csvData .=",".parseCSVComments($value['className']);
			$csvData .=",".parseCSVComments($value['totalStudents']);
                        if($feeOf == 1){
                        	$csvData .=",".parseCSVComments($value['leetAndNonLeetAcdFee']);
		                $csvData .=",".parseCSVComments($value['nonLeetAcdFee']);
		                $csvData .=",".parseCSVComments($value['leetAcdFee']);
		                $csvData .=",".parseCSVComments($value['migerationAcdFee']);
                        }
                        $csvData .=",".parseCSVComments($value['totalConcession']);
                        $csvData .=",".parseCSVComments($value['totalFine']);
                        $csvData .=",".parseCSVComments($value['totalAmount']);
                        $csvData .=",".parseCSVComments($value['totalFinePaid']);
                        $csvData .=",".parseCSVComments($value['paidStudents']);
                        $csvData .=",".parseCSVComments($value['paidFees']);
                        $csvData .=",".parseCSVComments($value['partialPaidStduents']);
                        $csvData .=",".parseCSVComments($value['partialPaidFees']);
                        $csvData .=",".parseCSVComments($value['unPaidStudents']);
                        $csvData .=",".parseCSVComments($value['unPaidFees'])."\n";
                        
                        $totalStudents += floatVal($value['totalStudents']);
                        $totalConcession += floatVal($value['totalConcession']);
                        $totalFine += floatVal($value['totalFine']);
                        $totalAmountDue += floatVal($value['totalAmount']);
                        $totalFinePaid += floatVal($value['totalFinePaid']);
                        $paidStudents += floatVal($value['paidStudents']);
                        $paidFees += floatVal($value['paidFees']);
                        $partialPaidStduents += floatVal($value['partialPaidStduents']);
                        $partialPaidFees += floatVal($value['partialPaidFees']);
                        $unPaidStudents += floatVal($value['unPaidStudents']);
                        $unPaidFees += floatVal($value['unPaidFees']);
		}
			$csvData .="\n,TOTAL";
                        $csvData .=",".parseCSVComments($totalStudents);
                        if($feeOf == 1){
                        	$csvData .=",,,,";
                        }
                        $csvData .=",".parseCSVComments($totalConcession);
                        $csvData .=",".parseCSVComments($totalFine);
                        $csvData .=",".parseCSVComments($totalAmountDue);
                        $csvData .=",".parseCSVComments($totalFinePaid);
                        $csvData .=",".parseCSVComments($paidStudents);
                        $csvData .=",".parseCSVComments($paidFees);
                        $csvData .=",".parseCSVComments($partialPaidStduents);
                        $csvData .=",".parseCSVComments($partialPaidFees);
                        $csvData .=",".parseCSVComments($unPaidStudents);
                        $csvData .=",".parseCSVComments($unPaidFees);

		UtilityManager::makeCSV($csvData,'ConsolidatedFeeDetailsReport.csv');
		die;
		
	}
	else{
		UtilityManager::makeCSV($errorMessage,'ConsolidatedFeeDetailsReport.csv');
		die;
	}
?>
