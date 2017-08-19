<?php 
// This File calls addFunction used in Fee Paymnt Report
//author: harpreet
// Created on : 2-Feb-2013
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','FeeDetailHistoryReport');     
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);           
    UtilityManager::headerNoCache(); 
   
    require_once(MODEL_PATH . "/Fee/GenerateFeeManager.inc.php");
    $generateFeeManager = GenerateFeeManager::getInstance(); 

    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();

    require_once(MODEL_PATH . "/Fee/FeeHeadManager.inc.php");
    $feeHeadManager = FeeHeadManager::getInstance();
    
    require_once(MODEL_PATH . "/Fee/FeeDetailHistoryReportManager.inc.php");
    $feeDetailHistoryReportManager = FeeDetailHistoryReportManager::getInstance();
    
	 require_once(MODEL_PATH . "/Fee/StudentFeeManager.inc.php");
    $studentFeeManager = StudentFeeManager::getInstance();
	
    global $sessionHandler;
    
    $queryDescription =''; 
    
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $userId = $sessionHandler->getSessionVariable('UserId');
    
    $errorMessage ='';
    $classId = htmlentities(add_slashes(trim($REQUEST_DATA['classId'])));   
    $studentName = htmlentities(add_slashes(trim($REQUEST_DATA['studentName'])));   
    $fatherName = htmlentities(add_slashes(trim($REQUEST_DATA['fatherName'])));   
    $rollNo = htmlentities(add_slashes(trim($REQUEST_DATA['rollNo'])));   
    $isFeeFor = htmlentities(add_slashes(trim($REQUEST_DATA['isFeeFor'])));   
    $isPaid = htmlentities(add_slashes(trim($REQUEST_DATA['isPaid'])));   
   
  
    if($classId=='') {
      $classId=0;  
    }
   
    if($isPaid=='') {
      $isPaid=0;  
    }
    if($isFeeFor=='' || $isFeeFor=='0') {
      $isFeeFor=4;  
    }
    
    $condition = "";
   $studentCondition="";
   
    if($rollNo!='') {
      $studentCondition .= " AND (s.rollNo LIKE '$rollNo%' OR s.regNo LIKE '$rollNo%') ";
    }
    if($studentName!='') {
      $studentCondition .= " AND (CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) LIKE '$studentName%') ";
    }
    if($fatherName!='') {
      $studentCondition .= " AND s.fatherName LIKE '$fatherName%' ";
    }
    
	
	// to limit records per page    
	$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;


    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'rollNo';
    $orderBy = " $sortField $sortOrderBy";         

    
    // Total Students 
    $rowData = getTabelHeading($isFeeFor);

    //Student Count function
    $studentFeeDetailArray = $feeDetailHistoryReportManager->getStudentAllFeeCount($isFeeFor,$isPaid,$classId,$studentCondition);
	  
	for($y=0;$y<count($studentFeeDetailArray);$y++) {
        $ret = explode(',',$studentFeeDetailArray[$y]);
        $ttStudentId = $ret[0];  // Fetch StudentId 
        $ttClassId   = $ret[1];  // Fetch Class Id     
         
         $bg = $bg =='trow1' ? 'trow0' : 'trow1';
         $srNo = $y;
        // Student Detail Student Name, Roll No, Class Name
        $studentDetailsArray = $feeDetailHistoryReportManager->getStudentDetails($ttStudentId,$ttClassId,$studentCondition);
		
		$studentName =$studentDetailsArray[0]['studentName']; 
		$studentRollNo =$studentDetailsArray[0]['rollNo']; 
		
		if($studentRollNo==''){
			$studentRollNo =$studentDetailsArray[0]['regNo']; 
		}
		if($studentRollNo==''){
			$studentRollNo =$studentDetailsArray[0]['universityRollNo']; 
		}
		
		$className =$studentDetailsArray[0]['className']; 
		
		 // Previous Class Balance (Start)
       $prevStudentId = $ttStudentId;
       $prevClassId = $ttClassId;      
    
    
    $isCheckPrviousBalance='1';        // Check the previous class balance  1=>On, 0=>Off
    $isPreviousPaid =0;
    
		
 $prevAllClassComment = '';	
  $prevAllClassAmount = 0;
		  
    $prevAcademicClassAmount =0;
    $prevHostelClassAmount =0;
    $prevTransportClassAmount =0; 
    if($isCheckPrviousBalance=='1') {
        // Fetch Prev. Period Value
       	$prevFeeClass = $studentFeeManager->getFeeClassPeriodValue($prevClassId);
		
		$feeClassPeriodValue =$prevFeeClass[0]['periodValue'];
        $prevSemArray = $studentFeeManager->getPreviousPeriodValue($prevStudentId,$feeClassPeriodValue);		
		
      
        $prevAcademicClassComment='';
	    $prevAcademicClassAmount=0;  
   
		$prevHostelClassAmount =0;
		$prevHostelClassComment ='';
			
		$prevTransportClassComment='';
		$prevTransportClassAmount =0;
		
       for($i=0;$i<count($prevSemArray);$i++) {
         $academicBalance=0;
		 $hostelBalance=0;
		 $transportBalance=0;
		 
		 $prevAcademicAmount = 0;
		 $prevHostelAmount = 0;
		 $prevTransportAmount = 0;
			
		 $prevAcademicPaid = 0;  
		 $prevHostelPaid = 0; 
		 $prevTransportPaid = 0;
	         // Calculation Total Amount
	         $ttPrevClassId =$prevSemArray[$i]['classId'];
	         $prevSemFeeArray = $studentFeeManager->getPreviousTotalAmount($prevStudentId,$ttPrevClassId);			 
			 $prevAcademicAmount = $prevSemFeeArray[0]['acdemicFees'];
			 $prevHostelAmount = $prevSemFeeArray[0]['hostelFees'];
			 $prevTransportAmount = $prevSemFeeArray[0]['transportFees'];
			
			 // Calculation Paid AMOUNT 
			
			  $prevSemAcademicPaidArray = $studentFeeManager->getTotalAcademicPaidAmount($prevStudentId,$ttPrevClassId);
			   $prevAcademicPaid = $prevSemAcademicPaidArray[0]['paidAcademicAmount'];
			   
			   $prevSemHostelPaidArray = $studentFeeManager->getTotalHostelPaidAmount($prevStudentId,$ttPrevClassId);
			   $prevHostelPaid = $prevSemHostelPaidArray[0]['paidHostelAmount'];
			   
			   $prevSemTransportPaidArray = $studentFeeManager->getTotalTransportPaidAmount($prevStudentId,$ttPrevClassId);
			   $prevTransportPaid = $prevSemTransportPaidArray[0]['paidTransportAmount'];	
			   
			    $prevSemAllPaidArray = $studentFeeManager->getTotalAllPaidAmount($prevStudentId,$ttPrevClassId);
			   $prevAllPaid = $prevSemTransportPaidArray[0]['paidAmount'];			  
			  
			  if($prevAllPaid =='' || $prevAllPaid=='0'){
			  	   	$academicBalance = $prevAcademicAmount - $prevAcademicPaid;
					$hostelBalance = $prevHostelAmount - $prevHostelPaid;
					$transportBalance = $prevTransportAmount - $prevTransportPaid;
					
					 if($academicBalance!=0) {
			           if($prevAcademicClassComment!='') {
			             $prevAcademicClassComment .=",";  
			           }  
			           $prevAcademicClassComment .= $prevSemArray[$i]['periodName']."(A)";     
			         }
				  if($hostelBalance!=0) {
			           if($prevHostelClassComment!='') {
			             $prevHostelClassComment .=",";  
			           }  
			           $prevHostelClassComment .= $prevSemArray[$i]['periodName']."(H)";     
			         }
				  if($transportBalance!=0) {
			           if($prevTransportClassComment!='') {
			             $prevTransportClassComment .=",";  
			           }  
			           $prevTransportClassComment .= $prevSemArray[$i]['periodName']."(T)";     
			         }				
				
			   } else{
			  		if($prevAcademicAmount > 0){
					    $academicBalance = $prevAcademicAmount - $prevAllPaid;
					  } 
	                  if($prevHostelAmount > 0){
	                    $hostelBalance = $prevHostelAmount - $academicBalance; 
	                  }
	                  if($prevTransportAmount > 0){
	                    $transportBalance = $prevTransportAmount - $hostelBalance; 
	                  }
					 if($academicBalance!=0) {
			           if($prevAcademicClassComment!='') {
			             $prevAcademicClassComment .=",";  
			           }  
			           $prevAcademicClassComment .= $prevSemArray[$i]['periodName']."(A)";     
			         }
				  if($hostelBalance!=0) {
			           if($prevHostelClassComment!='') {
			             $prevHostelClassComment .=",";  
			           }  
			           $prevHostelClassComment .= $prevSemArray[$i]['periodName']."(H)";     
			         }
				  if($transportBalance!=0) {
			           if($prevTransportClassComment!='') {
			             $prevTransportClassComment .=",";  
			           }  
			           $prevTransportClassComment .= $prevSemArray[$i]['periodName']."(T)";     
			         }
			   } 		  
				
					  if($academicBalance !='0' || $hostelBalance !='0' || $transportBalance !='0'){
					if($isPreviousPaid=='0'){        
                    $prevAllClassAmount = $academicBalance + $hostelBalance + $transportBalance; 
					 $prevAllClassComment = $prevAcademicClassComment.",".$prevHostelClassComment.",".$prevTransportClassComment;                                                                  
                     $isPreviousPaid = '1'; 
						 $isPrevPrint=$ttPrevClassId +1;   
						 
						                     
					}
				 }  
         }
    }
    // Diference
        	
   
    // Previous Class Balance (End)
        // Academic related
        $valueAcademicArray='';
        if($isFeeFor=='1' || $isFeeFor=='4') {   
            // Generate Academic Fee
               
	  /*
           $feeResultMessage = getGenerateFee($ttClassId,$ttStudentId);
           if($feeResultMessage!=SUCCESS) {
             echo $feeResultMessage;
             die;  
           } 
		*/
		 	$academicHeadFeeArray = $feeDetailHistoryReportManager->getTotalAcademicHeadFee($ttStudentId,$ttClassId,$condition);
			
			$academicConcessionArray = $feeDetailHistoryReportManager->getTotalAcademicConcessionFee($ttStudentId,$ttClassId,$condition);
			
			$academicLedgerArray = $feeDetailHistoryReportManager->getTotalAcademicLedgerFee($ttStudentId,$ttClassId,$condition);
			
			$academicPaidFeeArray = $feeDetailHistoryReportManager->getTotalAcademicPaidFee($ttStudentId,$ttClassId,$isFeeFor,$condition);
			
			$academicFineArray = $feeDetailHistoryReportManager->getTotalAcademicPaidFine($ttStudentId,$ttClassId,$isFeeFor,$condition);
			
			  for($i=0;$i<count($academicHeadFeeArray);$i++) {
          		$valueAcademicArray[] = $academicHeadFeeArray[$i]['studentId'];
				$valueAcademicArray[] = $academicHeadFeeArray[$i]['classId'];
				$valueAcademicArray[] = $academicHeadFeeArray[$i]['academicFees'];
	        }
	        for($i=0;$i<count($academicConcessionArray);$i++) {
	         	//$valueAcademicArray[] = $academicConcessionArray[$i]['studentId'];
				//$valueAcademicArray[] = $academicConcessionArray[$i]['feeClassId'];
				$valueAcademicArray[] = $academicConcessionArray[$i]['concession'];
	        }
			for($i=0;$i<count($academicLedgerArray);$i++) {
	         	$valueAcademicArray[] = $academicLedgerArray[$i]['acdDebit'];
				$valueAcademicArray[] = $academicLedgerArray[$i]['acdCredit'];
				$valueAcademicArray[] = $academicLedgerArray[$i]['fineDebit'];
				$valueAcademicArray[] = $academicLedgerArray[$i]['fineCredit'];
	        }
	         for($i=0;$i<count($academicPaidFeeArray);$i++) {
	         	$valueAcademicArray[] = $academicPaidFeeArray[$i]['paidAmount'];				
	        }
			 for($i=0;$i<count($academicFineArray);$i++) {
	         	$valueAcademicArray[] = $academicFineArray[$i]['paidFine'];
				
	        }
			/*
	        if(count($valueAcademicArray)>0) {
	         $valueAcademicArray = array_unique($valueAcademicArray);
				
	          $valueAcademicArray = array_values($valueAcademicArray);
	        }
			 
			 */
      		 // echo "<pre>";print_r($valueAcademicArray);die;
           // Get Total Fee, Ledger, Concession, Fine, Paid, Balance
           // $academicArray = $feeDetailHistoryReportManager->getTotalAcademicPaidFine($ttStudentId,$ttClassId,$isFeeFor,$condition);
		
        }
        
        // Hostel related
        $valueHostelArray = '';
        if($isFeeFor=='3' || $isFeeFor=='4') {   
           // Get Total Fee, Ledger, Fine, Paid, Balance
             //$hostelArray = $feeDetailHistoryReportManager->getTotalHostelFee($ttStudentId,$ttClassId,$isFeeFor,$condition);
			 $hostelHeadFeeArray = $feeDetailHistoryReportManager->getTotalHostelHeadFee($ttStudentId,$ttClassId,$condition);			
			
			$hostelLedgerArray = $feeDetailHistoryReportManager->getTotalHostelLedgerFee($ttStudentId,$ttClassId,$condition);
			
			$hostelPaidFeeArray = $feeDetailHistoryReportManager->getTotalHostelPaidFee($ttStudentId,$ttClassId,$isFeeFor,$condition);
			
			$hostelFineArray = $feeDetailHistoryReportManager->getTotalHostelPaidFine($ttStudentId,$ttClassId,$isFeeFor,$condition);
			
			  for($i=0;$i<count($hostelHeadFeeArray);$i++) {
          		$valueHostelArray[] = $hostelHeadFeeArray[$i]['studentId'];
				$valueHostelArray[] = $hostelHeadFeeArray[$i]['classId'];
				$valueHostelArray[] = $hostelHeadFeeArray[$i]['hostelFees'];
				$valueHostelArray[] = $hostelHeadFeeArray[$i]['hostelSecurity'];
	        }
	        
			for($i=0;$i<count($hostelLedgerArray);$i++) {
	         	$valueHostelArray[] = $hostelLedgerArray[$i]['hostDebit'];
				$valueHostelArray[] = $hostelLedgerArray[$i]['hostCredit'];
				$valueHostelArray[] = $hostelLedgerArray[$i]['finehostDebit'];
				$valueHostelArray[] = $hostelLedgerArray[$i]['finehostCredit'];
	        }
	         for($i=0;$i<count($hostelPaidFeeArray);$i++) {
	         	$valueHostelArray[] = $hostelPaidFeeArray[$i]['paidAmount'];
				
	        }
			 for($i=0;$i<count($hostelFineArray);$i++) {
	         	$valueHostelArray[] = $hostelFineArray[$i]['paidFine'];
				
	        }
	        /*
	        if(count($valueHostelArray)>0) {
	          $valueHostelArray = array_unique($valueHostelArray);
	          //$valueAcademicArray = array_values($resultArray);
	        }
		*/
        }
        $valueTransportArray = '';
        // Transport related
      if($isFeeFor=='2' || $isFeeFor=='4') {   
           // Get Total Fee, Ledger, Fine, Paid, Balance
           // $transportArray = $feeDetailHistoryReportManager->getTotalTransportFee($ttStudentId,$ttClassId,$isFeeFor,$condition);	
            $transportHeadFeeArray = $feeDetailHistoryReportManager->getTotalTransportHeadFee($ttStudentId,$ttClassId,$condition);			
			
			$transportLedgerArray = $feeDetailHistoryReportManager->getTotalTransportLedgerFee($ttStudentId,$ttClassId,$condition);
			
			$transportPaidFeeArray = $feeDetailHistoryReportManager->getTotalTransportPaidFee($ttStudentId,$ttClassId,$isFeeFor,$condition);
			
			$transportFineArray = $feeDetailHistoryReportManager->getTotalTransportPaidFine($ttStudentId,$ttClassId,$isFeeFor,$condition);
			
			  for($i=0;$i<count($transportHeadFeeArray);$i++) {
          		$valueTransportArray[] = $transportHeadFeeArray[$i]['studentId'];
				$valueTransportArray[] = $transportHeadFeeArray[$i]['classId'];
				$valueTransportArray[] = $transportHeadFeeArray[$i]['transportFees'];
				
	        }
	        
			for($i=0;$i<count($transportLedgerArray);$i++) {
	         	$valueTransportArray[] = $transportLedgerArray[$i]['transDebit'];
				$valueTransportArray[] = $transportLedgerArray[$i]['transCredit'];
				$valueTransportArray[] = $transportLedgerArray[$i]['finetransDebit'];
				$valueTransportArray[] = $transportLedgerArray[$i]['finetransCredit'];
	        }
	         for($i=0;$i<count($transportPaidFeeArray);$i++) {
	         	$valueTransportArray[] = $transportPaidFeeArray[$i]['paidAmount'];
				
	        }
			 for($i=0;$i<count($transportFineArray);$i++) {
	         	$valueTransportArray[] = $transportFineArray[$i]['paidFine'];
				
	        }
	        /*
	        if(count($valueTransportArray)>0) {
	          $valueTransportArray = array_unique($valueTransportArray);
	          //$valueAcademicArray = array_values($resultArray);
	        }
			 
			 */
			//echo "<pre>";print_r($valueTransportArray);die;	
        }
																																			

	        
      $rowData .= getTabelContent($bg,$srNo,$studentName,$studentRollNo,$className,$valueHostelArray,$valueTransportArray,$valueAcademicArray,$isFeeFor,$prevAllClassComment,$prevAllClassAmount,$isPrevPrint,$classId);
	  
    }
  if($y == '0' ){
  	 $bg = $bg =='trow1' ? 'trow0' : 'trow1';	  
      $rowData .= "<tr class='$bg'>
                     <td valign='top' class='padding_top' colspan='10' align='center'><b>NO DATA FOUND</b></td> </tr> "; 
  
	  }  else{
	 	$bg = $bg =='trow1' ? 'trow0' : 'trow1';
     	 $rowData .= "<tr class='$bg'>
                     <td valign='top' class='padding_top' align='left'></td>  
                     <td valign='top' class='padding_top' align='left'></td>
                     <td valign='top' class='padding_top' align='left'></td>
                     <td valign='top' class='padding_top' align='right'><b>TOTAL :</b></td>"; 
  		 if($isFeeFor=='1' || $isFeeFor=='4') { //academic total			 
	      $rowData .= "<td valign='top' class='padding_top' align='right'><b>".$totalAcademicCharges."</b></td>     
	                     <td valign='top' class='padding_top' align='right'><b>".$totalAcademicDebit."</b></td>
	                     <td valign='top' class='padding_top' align='right'><b>".$totalAcademicCredit."</b></td>
	                     <td valign='top' class='padding_top' align='right'><b>".$totalConcession."</b></td> 
	                     <td valign='top' class='padding_top' align='right'><b>".$totalAcademicFine."</b></td>
	                     <td valign='top' class='padding_top' align='right'><b>".$totalAllPrevBalanceClass."</b></td> 
	                     <td valign='top' class='padding_top' align='right'><b>".$ttAcademicTotalAmount."</b></td>
	                     <td valign='top' class='padding_top' align='right'><b>".$ttAcademicPaidAmount."</b></td>     
	                     <td valign='top' class='padding_top' align='right'><b>".$totalAcademicBalance."</b></td>"; 	 
		 } 
		  if($isFeeFor=='2' || $isFeeFor=='4') { 	//transport total		 
	      $rowData .= "<td valign='top' class='padding_top' align='right'><b>".$totalTransportCharges."</b></td>     
	                     <td valign='top' class='padding_top' align='right'><b>".$totalTransportDebit."</b></td>
	                     <td valign='top' class='padding_top' align='right'><b>".$totalTransportCredit."</b></td>
	                     <td valign='top' class='padding_top' align='right'><b>".$totalTransportFine."</b></td>
	                     <td valign='top' class='padding_top' align='right'><b>".$ttTransportTotalAmount."</b></td>
	                     <td valign='top' class='padding_top' align='right'><b>".$ttTransportPaidAmount."</b></td>     
	                     <td valign='top' class='padding_top' align='right'><b>".$totalTransportBalance."</b></td>"; 	 
		 } 
		   if($isFeeFor=='3' || $isFeeFor=='4') { 	//hostel total		 
	       $rowData .= "<td valign='top' class='padding_top' align='right'><b>".$totalHostelCharges."</b></td>     
	                     <td valign='top' class='padding_top' align='right'><b>".$totalHostelDebit."</b></td>
	                     <td valign='top' class='padding_top' align='right'><b>".$totalHostelCredit."</b></td>
	                     <td valign='top' class='padding_top' align='right'><b>".$totalHostelFine."</b></td> 
	                     <td valign='top' class='padding_top' align='right'><b>".$ttHostelTotalAmount."</b></td>
	                     <td valign='top' class='padding_top' align='right'><b>".$ttHostelPaidAmount."</b></td>     
	                     <td valign='top' class='padding_top' align='right'><b>".$totalHostelBalance."</b></td>"; 
	   	 } 
	   	 
	   		                
	    if($isFeeFor=='4') {            
	 	$rowData .= "<td valign='top' class='padding_top' align='right'><b>".$ttFinalTotalValue."</b></td>     
	                     <td valign='top' class='padding_top' align='right'><b>".$ttFinalPaidValue."</b></td>
	                     <td valign='top' class='padding_top' align='right'><b>".$ttFinalBalance."</b></td>";     
		  }else{
	 		 $rowData .= " </tr>"; 
	  }
  }
	  
    $rowData .="</table>";
    
    echo $rowData;
	die;    

function getTabelContent($bg,$srNo,$studentName,$studentRollNo,$className,$valueHostelArray,$valueTransportArray,$valueAcademicArray,$isFeeFor,$prevAllClassComment,$prevAllClassAmount,$isPrevPrint,$classId){
	 
      $tableData .="";	
	   
        $allTotalAmount = 0;
	 	$allPaidAmount = 0;
	 	$allBalance = 0;
		  global $totalAcademicCharges ;
		  global $totalAcademicDebit;
		  global $totalAcademicCredit;
		  global $totalAcademicFine;
		  global $totalConcession;
		  global $ttAcademicTotalAmount;
		  global $ttAcademicPaidAmount;
		  global $totalAcademicBalance;
		  global $totalAllPrevBalanceClass;
		  
		  global $totalTransportCharges ;
		  global $totalTransportDebit;
		  global $totalTransportCredit;
		  global $totalTransportFine;
		  global $ttTransportTotalAmount;
		  global $ttTransportPaidAmount;
		  global $totalTransportBalance;
		
		  
		  global $totalHostelCharges ;
		  global $totalHostelDebit;
		  global $totalHostelCredit;
		  global $totalHostelFine;
		  global $ttHostelTotalAmount;
		  global $ttHostelPaidAmount;
		  global $totalHostelBalance;
		  
		  
		  global $ttFinalTotalValue; 
		  global $ttFinalPaidValue ; 
		  global  $ttFinalBalance ;
		 
   if($valueAcademicArray[0]!='' || $valueHostelArray[0]!='' || $valueTransportArray[0]!='') { 
      $tableData .= "<tr class='$bg'>
      				 <td valign='top' class='padding_top' align='left'>".($srNo+1)."</td>  
                     <td valign='top' class='padding_top' align='left'>".$studentRollNo."</td>
                     <td valign='top' class='padding_top' align='left'>".$studentName."</td>
                     <td valign='top' class='padding_top' align='left'>".$className."</td>"; 
  	 }
     

	 if($valueAcademicArray[0] !='' && $isFeeFor=='1' ||  $isFeeFor=='4') {
	 	//0-> studentId
	 	//1-> classId
	 	//2-> academicFees
	 	//3-> concession
	 	//4-> acdDebit
	 	//5-> acdCredit
	 	//6-> fineDebit
	 	//7-> fineCredit
	 	//8-> paidAmount
	 	//9-> academicFine
	 	
		$totalFees= $valueAcademicArray[2] - $valueAcademicArray[3] + $valueAcademicArray[4] - $valueAcademicArray[5] + $valueAcademicArray[6] - $valueAcademicArray[7] + $valueAcademicArray[9] ;
		$paidAmount= $valueAcademicArray[8];
		if($isPrevPrint == $classId){
		$totalFees = $totalFees + $prevAllClassAmount;
		}
		$balance=  $totalFees - $paidAmount;
		$fine = $valueAcademicArray[6]-$valueAcademicArray[7]+$valueAcademicArray[9];
		
		
	    $allTotalAmount +=$totalFees;
	 	$allPaidAmount +=$paidAmount;
	 	$allBalance +=	$balance;	
	 	
	 	
		if($valueAcademicArray[4]==''){
			$valueAcademicArray[4]= '0.00';
		}
		if($valueAcademicArray[5]==''){
			$valueAcademicArray[5]= '0.00';
		}
		if($paidAmount==''){
			$paidAmount= '0.00';
		}
		
	if($isPrevPrint == $classId){
			
		if($prevAllClassAmount==''){
			$prevAcademicClassDetailss='0';
		}else{
			$prevAllClassDetailss = $prevAllClassAmount."(".$prevAllClassComment.")";
		}
	}else{	
			$prevAcademicClassDetailss='0';
		}
	 $tableData .= "<td valign='top' class='padding_top' align='right'>".$valueAcademicArray[2]."</td>     
	                     <td valign='top' class='padding_top' align='right'>".$valueAcademicArray[4]."</td>
	                     <td valign='top' class='padding_top' align='right'>".$valueAcademicArray[5]."</td> 
	                     <td valign='top' class='padding_top' align='right'>".$valueAcademicArray[3]."</td>     
	                     <td valign='top' class='padding_top' align='right'>".$fine."</td>
	                     <td valign='top' class='padding_top' align='right'>".$prevAllClassDetailss."</td> 
	                     <td valign='top' class='padding_top' align='right'>".$totalFees."</td>
	                     <td valign='top' class='padding_top' align='right'>".$paidAmount."</td>     
	                     <td valign='top' class='padding_top' align='right'>".$balance."</td>"; 
	      $isTotal++;  
		  $totalAcademicCharges += $valueAcademicArray[2];
		  $totalAcademicDebit += $valueAcademicArray[4];
		  $totalAcademicCredit += $valueAcademicArray[5];
		  $totalAcademicFine += $fine;
		  $totalConcession += $valueAcademicArray[3];
		  $ttAcademicTotalAmount += $totalFees;
		  $ttAcademicPaidAmount += $paidAmount;
		  $totalAcademicBalance += $balance;
		if($isPrevPrint == $classId){	  
		  $totalAllPrevBalanceClass += $prevAllClassAmount;
		  }          
	   }else if($isFeeFor=='4'){
	   	$tableData .= "<td valign='top' class='padding_top'  colspan='8'></td> ";
	   	 	
	   }
	 if($valueTransportArray[0]!='' && $isFeeFor=='2'||  $isFeeFor=='4') {
	 		 	
	 	//0-> studentId
	 	//1-> classId
	 	//2-> transportFees
	 	//3-> transDebit
	 	//4-> transCredit
	 	//5-> finetransDebit
	 	//6-> finetransCredit
	 	//7-> paidAmount
	 	//8-> transportFine
	 	$totalFees = $valueTransportArray[2] + $valueTransportArray[3] - $valueTransportArray[4] + $valueTransportArray[5] - $valueTransportArray[6]  + $valueTransportArray[8] ;
		$paidAmount = $valueTransportArray[7];
		
		$balance =  $totalFees - $paidAmount;
		$fine = $valueTransportArray[5]-$valueTransportArray[6]+$valueTransportArray[8];
		$transportFees = $valueTransportArray[2];
		
	    $allTotalAmount +=$totalFees;
	 	$allPaidAmount +=$paidAmount;
	 	$allBalance +=	$balance;
	
		if($valueTransportArray[4]==''){
			$valueTransportArray[4]= '0.00';
		}
		if($valueTransportArray[5]==''){
			$valueTransportArray[5]= '0.00';
		}
		if($paidAmount==''){
			$paidAmount= '0.00';
		}	

			
	 	 $tableData .= "<td valign='top' class='padding_top' align='right'>".$transportFees."</td>     
	                     <td valign='top' class='padding_top' align='right'>".$valueTransportArray[3]."</td>
	                     <td valign='top' class='padding_top' align='right'>".$valueTransportArray[4]."</td>     
	                     <td valign='top' class='padding_top' align='right'>".$fine."</td> 
	                     <td valign='top' class='padding_top' align='right'>".$totalFees."</td>
	                     <td valign='top' class='padding_top' align='right'>".$paidAmount."</td>     
	                     <td valign='top' class='padding_top' align='right'>".$balance."</td>";   
	      $isTotal++; 
		   $totalTransportCharges +=$transportFees;
		  $totalTransportDebit += $valueTransportArray[3];
		  $totalTransportCredit += $valueTransportArray[4];
		  $totalTransportFine += $fine;
		  $ttTransportTotalAmount += $totalFees;
		  $ttTransportPaidAmount += $paidAmount;
		  $totalTransportBalance += $balance; 
		                 
	  }else if($isFeeFor=='4'){
	   	$tableData .= "<td valign='top' class='padding_top' colspan='8'></td> ";
	   	 	
	   }
	 
	 if($valueHostelArray[0]!='' && $isFeeFor=='3' ||  $isFeeFor=='4') {
	 	//0-> studentId
	 	//1-> classId
	 	//2-> hostelFees
	 	//3-> hostelSecurity
	 	//4-> hostDebit
	 	//5-> hostCredit
	 	//6-> finehostDebit
	 	//7-> finehostCredit
	 	//8-> paidAmount
	 	//9-> hostelFine
	 	$totalFees = $valueHostelArray[2] + $valueHostelArray[3] + $valueHostelArray[4] - $valueHostelArray[5] + $valueHostelArray[6] - $valueHostelArray[7] + $valueHostelArray[9] ;
		$paidAmount = $valueHostelArray[8];
		
		$balance =  $totalFees - $paidAmount;
		$fine = $valueHostelArray[6]-$valueHostelArray[7]+$valueHostelArray[9];
		$hostelFees = $valueHostelArray[2] + $valueHostelArray[3];
		
	    $allTotalAmount +=$totalFees;
	 	$allPaidAmount +=$paidAmount;
	 	$allBalance +=	$balance;
	
		if($valueHostelArray[4]==''){
			$valueHostelArray[4]= '0.00';
		}
		if($valueHostelArray[5]==''){
			$valueHostelArray[5]= '0.00';
		}
		if($paidAmount==''){
			$paidAmount= '0.00';
		}	
	 	
	 
	 	 $tableData .= "<td valign='top' class='padding_top' align='right'>".$hostelFees."</td>     
	                     <td valign='top' class='padding_top' align='right'>".$valueHostelArray[4]."</td>
	                     <td valign='top' class='padding_top' align='right'>".$valueHostelArray[5]."</td>     
	                     <td valign='top' class='padding_top' align='right'>".$fine."</td> 
	                     <td valign='top' class='padding_top' align='right'>".$totalFees."</td>
	                     <td valign='top' class='padding_top' align='right'>".$paidAmount."</td>     
	                     <td valign='top' class='padding_top' align='right'>".$balance."</td>";  
						
	      $isTotal++; 
		   $totalHostelCharges += $hostelFees;
		  $totalHostelDebit += $valueHostelArray[4];
		  $totalHostelCredit += $valueHostelArray[5];
		  $totalHostelFine += $fine;
		  $ttHostelTotalAmount += $totalFees;
		  $ttHostelPaidAmount += $paidAmount;
		  $totalHostelBalance += $balance;  
		              
	   }else if($isFeeFor=='4'){
	   	$tableData .= "<td valign='top' class='padding_top' colspan='8'></td> ";	   	
	 		  
	   }
	    
   if($isTotal>0 && $isFeeFor=='4') {       
      $tableData .= "<td valign='top' class='padding_top' align='right'><strong>".$allTotalAmount."</strong></td>
                      <td valign='top' class='padding_top' align='right'><strong>".$allPaidAmount."</strong></td>
                      <td valign='top' class='padding_top' align='right'><strong>".$allBalance."</strong></td>
                    ";
                
	  		 $ttFinalTotalValue +=  $allTotalAmount; 
              $ttFinalPaidValue +=  $allPaidAmount; 
			   $ttFinalBalance +=  $allBalance;   
                      
   }else{
   	
   	$tableData .= "</tr>";    
   
   }

   return $tableData;
}


function getTabelHeading($isFeeOf='') {
    
   $isTotal='0'; 
   $tableData = ''; 
   $tableData1 = ''; 
   $tableData = "<table width='100%' border='0' cellspacing='2' cellpadding='0'>
                    <tr class='rowheading'>
                      <td width='2%'  valign='middle' rowspan='2' class='searchhead_text' ><b>#</b></td>
                      <td width='5%'  valign='middle' rowspan='2' class='searchhead_text' align='left'><strong>Roll No.</strong></td>
                      <td width='10%' valign='middle' rowspan='2' class='searchhead_text' align='left'><strong>Student Name</strong></td>
                      <td width='10%' valign='middle' rowspan='2' class='searchhead_text' align='left'><strong>Class</strong></td>";
   if($isFeeOf=='1' || $isFeeOf=='4') {   // Academic Fee
      $tableData .= "<td width='10%' valign='middle' colspan='9' class='searchhead_text' align='center'><strong>Academic</strong></td>";
      $tableData1 .= "<td width='2%'  valign='middle' class='searchhead_text' align='right'><b>Fee</b></td>
                      <td width='5%'  valign='middle' class='searchhead_text' align='right'><strong>Ledger Debit</strong></td>
                      <td width='10%' valign='middle' class='searchhead_text' align='right'><strong>Ledger Credit</strong></td>
                      <td width='10%' valign='middle' class='searchhead_text' align='right'><strong>Concession</strong></td>
                      <td width='10%' valign='middle' class='searchhead_text' align='right'><strong>Fine</strong></td>
                      <td width='10%' valign='middle' class='searchhead_text' align='right'><strong>Prev. Class Balance</strong></td>
                      <td width='10%' valign='middle' class='searchhead_text' align='right'><strong>Total</strong></td>
                      <td width='10%' valign='middle' class='searchhead_text' align='right'><strong>Paid</strong></td>
                      <td width='10%' valign='middle' class='searchhead_text' align='right'><strong>Balance</strong></td>";
      $isTotal++;                
   } 

   if($isFeeOf=='2' || $isFeeOf=='4') {   // Transport Fee
      $tableData  .= "<td width='10%' valign='middle' colspan='7' class='searchhead_text' align='center'><strong>Transport</strong></td>";
      $tableData1 .= "<td width='2%'  valign='middle' class='searchhead_text' align='right'><b>Charges</b></td>
                      <td width='5%'  valign='middle' class='searchhead_text' align='right'><strong>Ledger Debit</strong></td>
                      <td width='10%' valign='middle' class='searchhead_text' align='right'><strong>Ledger Credit</strong></td>
                      <td width='10%' valign='middle' class='searchhead_text' align='right'><strong>Fine</strong></td>
                      <td width='10%' valign='middle' class='searchhead_text' align='right'><strong>Total</strong></td>
                      <td width='10%' valign='middle' class='searchhead_text' align='right'><strong>Paid</strong></td>
                      <td width='10%' valign='middle' class='searchhead_text' align='right'><strong>Balance</strong></td>";
      $isTotal++;                
   }
  
   if($isFeeOf=='3' || $isFeeOf=='4') {   // Hostel Fee
      $tableData  .= "<td width='10%' valign='middle' colspan='7' class='searchhead_text' align='center'><strong>Hostel</strong></td>";
      $tableData1 .= "<td width='2%'  valign='middle' class='searchhead_text' align='right'><b>Charges</b></td>
                      <td width='5%'  valign='middle' class='searchhead_text' align='right'><strong>Ledger Debit</strong></td>
                      <td width='10%' valign='middle' class='searchhead_text' align='right'><strong>Ledger Credit</strong></td>
                      <td width='10%' valign='middle' class='searchhead_text' align='right'><strong>Fine</strong></td>
                      <td width='10%' valign='middle' class='searchhead_text' align='right'><strong>Total</strong></td>
                      <td width='10%' valign='middle' class='searchhead_text' align='right'><strong>Paid</strong></td>
                      <td width='10%' valign='middle' class='searchhead_text' align='right'><strong>Balance</strong></td>";
      $isTotal++;                
   }
   
   
   if($isTotal>1 ) {
       $tableData .= "<td width='10%' valign='middle' rowspan='2' class='searchhead_text' align='right'><strong>Total</strong></td>
                      <td width='10%' valign='middle' rowspan='2' class='searchhead_text' align='right'><strong>Paid</strong></td>
                      <td width='10%' valign='middle' rowspan='2' class='searchhead_text' align='right'><strong>Balance</strong></td>
                      </tr>";
   }
   $tableData .= "<tr class='rowheading'>
                     $tableData1
                   </tr>";
   return $tableData;
}


    function getGenerateFee($feeClassId,$studentId) {
         
         global $generateFeeManager;
         global $commonQueryManager;
         global $feeHeadManager;
         global $sessionHandler;
         
         if($feeClassId=='') {
           $feeClassId='0';
         }
         
         if($studentId=='') {
           $studentId='0';
         }
         
         $ttFeeClassId=  $feeClassId;
         
         $userId = $sessionHandler->getSessionVariable('UserId');
         $errorMessage =''; 
         
         
         $feeCycleCondition = " classId = '$feeClassId' ";
         $feeCycleArray = $generateFeeManager->checkStudentFeeCycle($feeCycleCondition);
         if(count($feeCycleArray) > 0){
            $feeCycleId = $feeCycleArray[0]['feeCycleId'];
         }
    
         // to fetch Current class of student
         $classArray = $generateFeeManager->getClass($feeClassId);
         if(count($classArray) == 0){
           return  "Class Not Found";
        }
        
        // Fetch the all Classes 
        $classes = '';
        foreach($classArray as $key => $value){
          if($classes == ''){
            $classes = $value['classId'];
          }
          else{
            $classes .= ",".$value['classId'];
          }
        } 
        $feeStudyPeriodId = $classArray[0]['feeStudyPeriodId'];
    
    
        if(SystemDatabaseManager::getInstance()->startTransaction()){
            
                // To Delete old fee heads
                $oldFeeHeadDelete = $generateFeeManager->checkStudentFeeHeadDelete($studentId,$ttFeeClassId);
                if($oldFeeHeadDelete===false) {
                   echo FAILURE;
                   die;
                }
            
     // Fetch Migration Fee  Start
                $migrationArray = $generateFeeManager->getCheckStudentMigration($studentId);
                if(count($migrationArray) > 0 && is_array($migrationArray)) {
                  $ttIsMigrationId=$migrationArray[0]['migrationStudyPeriod'];
                }
                if($ttIsMigrationId=='') {
                  $ttIsMigrationId='0';  
                }
                
                $ttPeriodValue='-1';  
                if($ttIsMigrationId>0) {
                   $migrationPeriodArray = $generateFeeManager->getMigrationStudyPeriod($feeClassId);
                   $ttPeriodValue = $migrationPeriodArray[0]['periodValue'];
                   if($ttPeriodValue=='') {
                     $ttPeriodValue='-1';  
                   }
                }
                if($ttIsMigrationId==$ttPeriodValue) {
                  $ttIsMigrationId=1; 
                }
                else {
                  $ttIsMigrationId=0;   
                }
            // Migration Fee END  

            // to Get Student Details
            $condition1 = " AND studentId = '$studentId' ";
            $condition2 = " AND stu.studentId = '$studentId' ";
            $studentDataArray = $generateFeeManager->getStudentDetailsNew($classes,$condition1,$condition2);
            if(count($studentDataArray) == 0 || !is_array($studentDataArray)) {
               return "Students Not Found";  
            }

            $j=1;
            foreach($studentDataArray as $key =>$studentArr) {
                $currentClass = $studentArr['classId'];  
                $instituteId =  $studentArr['instituteId'];  
                $instituteAbbr =  $studentArr['instituteAbbr'];  
                
                $concession = '';
                $hostelFees='';
                $transportFees='';
                $busRouteId   = '';
                $busStopId = '';
                $feeReceiptId = '';
                $totalAcademicFee =0;
                $hostelSecurity = 0;
             // to get Student Concession                   
                $adhocCondition =" acm.feeClassId = '".$feeClassId."' AND acm.studentId = '".$studentArr['studentId']."'"; 
                $adhocArray=$generateFeeManager->getStudentAdhocConcessionNew($adhocCondition);
                $concession = $adhocArray[0]['adhocAmount']; // concession Amount
                $isMigration ='-1';
                if($studentArr['isMigration'] == 1  && $ttIsMigrationId == 1){
                  $isMigration = 3;
                }
                 // to get Student Fee Heads
                 $foundArray = $generateFeeManager->getStudentFeeHeadDetail($feeClassId,$studentArr['quotaId'],$studentArr['isLeet'],$studentArr['studentId'],$isMigration);
                 if(count($foundArray) == 0){
                    return FEE_HEAD_NOT_DEFINE;
                 }
                 
                 $feeArray = array();
                 $applicableHeadId = array();
                 $index = array();
                
                 // code to find only applicable Head Value 
                 foreach($foundArray as $key =>$subArray) {
                    if(!in_array($subArray['feeHeadId'],$applicableHeadId)){
                           $flag1 = true; // used for filtering purpose
                       }  
                    $flag= true;
                    foreach($foundArray as $key1 =>$subArray1){
                           if((($subArray['feeHeadId'] == $subArray1['feeHeadId']) && $subArray['isLeet'] == 3)&& (($subArray['quotaId'] == $studentArr['quotaId']) && $subArray['isLeet'] == $isMigration)){
                               $flag = true;
                               foreach($applicableHeadId as $key2 => $value){
                                   if($value == $subArray['feeHeadId']){
                                       $applicableHeadId[$key2] = $subArray['feeHeadId'];
                                       $index[$key2] = $key;
                                       $flag= false;
                                   }    
                               }
                               if($flag){
                                   $applicableHeadId[] = $subArray['feeHeadId'];
                                   $index[] = $key;
                               }
                               break;
                           }
                           else if((($subArray['feeHeadId'] == $subArray1['feeHeadId']) && $subArray['isLeet'] == 1) && (($subArray['quotaId'] == $studentArr['quotaId']) && ($subArray['isLeet'] == $studentArr['isLeet']))){ 
                               $flag = true;
                               foreach($applicableHeadId as $key2 => $value){
                                   if($value == $subArray['feeHeadId']){
                                       $applicableHeadId[$key2] = $subArray['feeHeadId'];
                                       $index[$key2] = $key;
                                       $flag= false;
                                   }    
                               }
                               if($flag){
                                   $applicableHeadId[] = $subArray['feeHeadId'];
                                   $index[] = $key;
                               }
                               break;
                           }
                           else if((($subArray['feeHeadId'] == $subArray1['feeHeadId']) && $flag1 == true) && (($subArray['quotaId'] == 0) && $subArray['isLeet'] == $isMigration)){ 
                               $flag = true;
                               foreach($applicableHeadId as $key2 => $value){
                                   if($value == $subArray['feeHeadId']){
                                       $applicableHeadId[$key2] = $subArray['feeHeadId'];
                                       $index[$key2] = $key;
                                       $flag= false;
                                   }    
                               }
                               if($flag){
                                   $applicableHeadId[] = $subArray['feeHeadId'];
                                   $index[] = $key;
                               }
                               break;
                           }
                           else if((($subArray['feeHeadId'] == $subArray1['feeHeadId']) && !in_array($subArray['feeHeadId'],$applicableHeadId)) && (($subArray['quotaId'] == $studentArr['quotaId']) || (($subArray['isLeet'] == $studentArr['isLeet']) || $subArray['isLeet'] == $isMigration))){ 
                               $applicableHeadId[] = $subArray['feeHeadId'];
                               $index[] = $key;
                               break;
                           
                           }
                    }              
                  }
          
                  $applicableHeadId = array_unique($applicableHeadId); 

                  // to put other heads 
                  foreach($foundArray as $key =>$subArray){
                    if(!in_array($subArray['feeHeadId'],$applicableHeadId)){
                        $feeArray[$key] = $foundArray[$key];
                    }
                  }
                  // to insert aplicable head at there place
                  $index = array_unique($index);
                  foreach($index as $key =>$value){
                    $feeArray[$value] = $foundArray[$value];
                  }
                  // this is done to mantain the order of fee it stores the key
                  $indexArr = array();
                  foreach($feeArray as $key =>$value){
                    $indexArr[] = $key;
                  }
                  sort($indexArr); // to sort the index
            
                   $studentId = $studentArr['studentId'];
                $feeReceiptId = '';
                $feeReceiptArray= $generateFeeManager->getFeeMasterId($studentId,$feeClassId);
                if(count($feeReceiptArray) > 0 ) {
                  $feeReceiptId = $feeReceiptArray[0]['feeReceiptId'];
                }
                $status = $generateFeeManager->insertIntoFeeMaster($studentId,$currentClass,$feeClassId,$feeCycleId,$concession);
                if($status === FALSE){
                    return FALIURE;
                }
                if($feeReceiptId=='') {
                  $feeReceiptId=SystemDatabaseManager::getInstance()->lastInsertId();
                }
                
                $cnt = count($indexArr);
                $instrumentValues = '';
                for($i=0;$i<$cnt; $i++){
                    //feeReceiptInstrumentId,feeReceiptId,studentId,classId,feeHeadId,feeHeadName,amount,feeStatus
                    if($feeArray[$indexArr[$i]]['feeHeadAmt'] > 0) {
                        if($instrumentValues != ''){
                            $instrumentValues .=", ";
                        }
                        $instrumentValues .="('','$feeReceiptId','$studentId','$feeClassId','".$feeArray[$indexArr[$i]]['feeHeadId']."','".ucwords($feeArray[$indexArr[$i]]['headName'])."','".$feeArray[$indexArr[$i]]['feeHeadAmt']."',0)";
                        $totalAcademicFee += floatval($feeArray[$indexArr[$i]]['feeHeadAmt']);
                        $totalAcademicFee = " ".$totalAcademicFee;
                    }
                }
        
                $status1 = $generateFeeManager->insertIntoReceiptInstrument($instrumentValues);
                if($status1 === FALSE){
                    return FALIURE;
                }
                $j++;
            }
            if(SystemDatabaseManager::getInstance()->commitTransaction()) {
                $msg = SUCCESS; 
            }
            else {
               $msg = FAILURE;
            }
        }
        else {
          $msg = FAILURE;
        }
        return $msg; 
    }
    		 
		
?>
