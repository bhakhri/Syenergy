<?php
//This file is used as printing version for student profile.
//
// Author :Harpreet
// Created on : 14-Feb-2012
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','COMMON');
    define('ACCESS','view');
    
    require_once(MODEL_PATH . "/Fee/StudentFeeManager.inc.php");
    $studentFeeManager = StudentFeeManager::getInstance();
    	
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
    
    require_once(MODEL_PATH . "/Fee/GenerateFeeManager.inc.php");
    $generateFeeManager = GenerateFeeManager::getInstance(); 

    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();

    require_once(MODEL_PATH . "/Fee/FeeHeadManager.inc.php");
    $feeHeadManager = FeeHeadManager::getInstance();
    
	 require_once(MODEL_PATH . "/Fee/GenerateStudentSlipManager.inc.php");
    $generateStudentSlipManager = GenerateStudentSlipManager::getInstance();

		
    require_once(BL_PATH . '/NumToWord.class.php'); 

	global $sessionHandler; 
    
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    if($roleId==4){
      UtilityManager::ifStudentNotLoggedIn(true);
    }
    else{
      UtilityManager::ifNotLoggedIn(true);
    }
    UtilityManager::headerNoCache(); 
	
    
    $classId = trim(add_slashes($REQUEST_DATA['classId']));
    $rollNo = trim(add_slashes($REQUEST_DATA['rollNo']));
    
    if(trim($REQUEST_DATA['classId'])==''){
       echo SELECT_FEE_CLASS;
       die;
    }
    
    if(trim($REQUEST_DATA['rollNo'])==''){
       echo ENTER_NAME_ROLLNO;
       die;
    }
  
    $condition = '';
    if($rollNo=='') {
      echo STUDENT_NOT_EXIST;  
      die; 
    }
    
    if($classId=='') {
      echo STUDENT_NOT_EXIST;  
      die; 
    }
	
    $studentId='';
    $condition = " (stu.rollNo LIKE '$rollNo' OR stu.regNo LIKE '$rollNo'  OR stu.universityRollNo LIKE '$rollNo') ";   
    $studentFeesArray = $generateStudentSlipManager->getCheckStudentId($condition,$classId);
    if(count($studentFeesArray)==1) {
      $currentClassId = $studentFeesArray[0]['currentClassId'];
      $studentId  = $studentFeesArray[0]['studentId'];   
    }
   

    // Previous Class Balance (Start)
    $prevStudentId = $studentId;
    $prevClassId = $classId;
    $isPreviousPaid =0;
    
		
		 $prevAllClassComment = '';	
		  $prevAllClassAmount = 0;
    $isCheckPrviousBalance='0';        // Check the previous class balance  1=>On, 0=>Off
    
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
    
   
    $slipCopyNameArray = array(1=>'Student Copy',2=>'Bank Copy',3=>'College Copy');
	
    $hostelDescId='';
    $transportDescId='';
    
	
    $instituteAbbr = '';
    $instituteAbbArray = $sessionHandler->getSessionVariable('InstituteAbbArray');
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $instituteAbbr =  $instituteAbbArray[$instituteId];  
	$userId = $sessionHandler->getSessionVariable('UserId');
	$payFeeOf = "all";
	
	if(($userId==''|| $payFeeOf == '') || $REQUEST_DATA['classId'] ==''){
		echo 'Required Parameters Missing';
		die;
	}
	
	$feeClassId = $REQUEST_DATA['classId'];
	$feesClassArray = $studentFeeManager->getFeeClass($feeClassId); 
	$feeClassId = $feesClassArray[0]['feeClass'];	
	$feeStudyPeriodId = $feesClassArray[0]['studyPeriodId'];
	$batchId = $feesClassArray[0]['batchId'];
	$condition = "AND stu.userId = '$userId'";
	      
    $totalFeePaid =0;	
	 
	if($payFeeOf =='all') {
	  $feeType=4;	
	}


//Fine SetUp module linkingg...
    function dateDiff($start, $end) {
       $start_ts = strtotime($start);
       $end_ts = strtotime($end);
       
       $diff = $end_ts - $start_ts;
       return round($diff / 86400);
    }
    
   
    for($y=1;$y<=3;$y++) {
            $ttFeeFineType = $y;
            $condition = " AND frd.classId = '$feeClassId' AND frd.feeType IN ('$ttFeeFineType','4') AND frd.studentId = '$studentId' ";
            $prevFeeArray = $studentFeeManager->getCheckStudentFine($condition);
		
            if(is_array($prevFeeArray) && count($prevFeeArray)==0) {  
                  // Ledger Check  
                  $ledgerCondition = "frd.classId = '$feeClassId' AND frd.studentId = '$studentId' AND frd.ledgerTypeId = '$ttFeeFineType' ";  
                  $ledgerSetUpArray = $studentFeeManager->getLedgerCheckFine($ledgerCondition);
               
                  if($ledgerSetUpArray[0]['isFine']!='2') {  
                      $fineSetUpArray = $studentFeeManager->getFineSetUpDetails($feeClassId,$ttFeeFineType);
                      if(is_array($fineSetUpArray) && count($fineSetUpArray)>0) {  
                         $serverDate = date('Y-m-d'); 
                         $dif=-1;
                         $fineCharges='0';
                         for($i=0;$i<count($fineSetUpArray);$i++) {
                            if($fineSetUpArray[$i]['fromDate']<=$serverDate) {
                              $dif = abs(dateDiff($fineSetUpArray[$i]['fromDate'],$serverDate));
                              if($dif==0) {
                                $dif=1;
                              }
                            }
                            if($dif > 0) {
                              if($fineSetUpArray[$i]['chargesFormat']=='1') { // daily
                                $fineCharges = $fineSetUpArray[$i]['charges']*$dif;
                              }
                              else if($fineSetUpArray[$i]['chargesFormat']=='2') { // fixed
                                $fineCharges = $fineSetUpArray[$i]['charges'];
                              }
                              break;
                            } 
                         }      
                      
                         if(doubleval($dif)!=-1) {
                            // Academic Fee/  Transport / Hostel
                            $isApplyFine='0';
                            if($ttFeeFineType=='1') {
                               $fineApplyArray = $studentFeeManager->applyFineAcd($studentId,$feeClassId);
						
                               if($fineApplyArray===false){
                                 echo FAILURE;
                                 die;
                               } 
                               if(count($fineApplyArray)>0){
                                 $isApplyFine='1';       
                               }  
                            } 
                            else if($ttFeeFineType=='2') {
                               $fineApplyArray = $studentFeeManager->applyFineTransport($studentId,$feeClassId);
                               if($fineApplyArray===false){
                                 echo FAILURE;
                                 die;
                               }
                               if(count($fineApplyArray)>0){
                                  $isApplyFine='1';       
                               }       
                            }
                            else if($ttFeeFineType=='3') {
                               $fineApplyArray = $studentFeeManager->applyFineHostel($studentId,$feeClassId);
                               if($fineApplyArray===false){
                                 echo FAILURE;
                                 die;
                               }
                               if(count($fineApplyArray)>0){
                                  $isApplyFine='1';       
                               }      
                            }

                            
                                $returnArray = $studentFeeManager->deleteFineLedgerData($studentId,$feeClassId,$ttFeeFineType);
                                if($returnArray===false){
                                  echo FAILURE;
                                  die;
                                }
                    
                            if($isApplyFine=='1') { 
                                if(SystemDatabaseManager::getInstance()->startTransaction()){      
                                    $returnArray = $studentFeeManager->updateFineLedgerData($studentId,$feeClassId,$ttFeeFineType,$fineCharges,$feeCycleId);
                                    if($returnArray===false){
                                      echo FAILURE;
                                      die;
                                    } 
                                    if(SystemDatabaseManager::getInstance()->commitTransaction()) {   
                                       //echo FAILURE;   
                                    }
                                }
                            }
                         }
                       }
                  }
            }
    }
//End of fIne setup Module
    
  // Fee Payment Mode 1=>Academic, 4=>All
    if($payFeeOf=='academic' || $payFeeOf =='all') {
          
           $feeAcdSearch='0';
          
           if($feeAcdSearch=='0') {
             $feeResultMessage = getGenerateFee($feeClassId,$studentId);
             if($feeResultMessage!=SUCCESS) {
               echo $feeResultMessage;
               die;  
             }
           }
        }
    
      	$receiptNo = '';
      	$feeDataArray = $studentFeeManager->getStudentFeeDetails($studentId,$currentClassId,$feeClassId);
	    if(count($feeDataArray) == 0){
      		echo "No Data Found.";
      		die;
      	}
      	$feeCycleId = $feeDataArray[0]['feeCycleId'];
      	$regNo = $feeDataArray[0]['regNo'];
      
    	
	
  
      	$feeStudyPeriodName = $feeDataArray[0]['feeStudyPeriodName'];
      	$feeReceiptId = $feeDataArray[0]['feeReceiptId'];
      	$degreeAbbr = $feeDataArray[0]['degreeAbbr'];
        $batchYear = $feeDataArray[0]['batchYear'];
      	
      	if($feeDataArray[0]['receiptGeneratedDate'] =='0000-00-00 00:00:00' || $feeDataArray[0]['receiptGeneratedDate'] == NULL){
      		$result = $studentFeeManager->insertFeeReceiptTime($feeReceiptId);
      		if(!$result){
      			echo "Some Thing Went Wrong !!!";
      			die;
      		}
      	}
       
      	// to append with Fee Receipt No to know which fee is paid by student
      	switch($payFeeOf){
      		case "all" :
      		$receiptNo .="/All";
      		break;
      		
      		case "academic" :
      		$receiptNo .="/Acd";
      		break;
      		
      		case "transport" :
      		$receiptNo .="/Tra";
      		break;
      		
      		case "hostel" :
      		$receiptNo .="/Hstl";
      		break;
      		
      		default :
      		$receiptNo .="$payFeeOf";
      		break;
      	}    
      
  $feeContent = '';
  $totalAmount =0;
  $cnt = 1;
  $chkId = "chb0";	 	
  
  	
	    

  $feeContent .="<table width='100%' border='0' cellspacing='0' cellpadding='0' align='left'> 
                      <tr>
                          <td class='content_row1' valign='top'>
                          	<input type='hidden' name='studentId' id ='studentId' value='".$studentFeesArray[0]['studentId']." '>
                            <nobr><b>Student Name&nbsp;:&nbsp;</b>".$studentFeesArray[0]['studentName']."</nobr>
                          </td>
                          <td class='content_row1' valign='top' >
                            <nobr><b>Roll No.&nbsp;:&nbsp;</b>".$studentFeesArray[0]['rollNo']."</nobr>
                          </td>
                          <td class='content_row1' valign='top' >
                            <nobr><b>Reg. No.&nbsp;:&nbsp;</b>".$studentFeesArray[0]['regNo']."</nobr>
                          </td>
                          <td class='content_row1' valign='top' >
                            <nobr><b>Father's Name&nbsp;:&nbsp;</b>".$studentFeesArray[0]['fatherName']."</nobr>
                          </td>
                       </tr> 
                       <tr><td colspan='4' height='10px'></td></tr> 
                  </table><br>";
                   
  $feeContent .="<table width='100%' border='0' cellspacing='2' cellpadding='0'>                     
                   <tr class='rowheading'>
                        <td class='searchhead_text' width='4%' align='center' ><strong><b><nobr>".$checkAll."</nobr></b></strong></td> 
                        <td class='searchhead_text' align='center' width='4%' ><b><nobr>#</nobr></b></td>
		                <td class='searchhead_text' width='52%' align='left' ><b><nobr>Fee Head</nobr></b></td> 
		                <td class='searchhead_text' width='40%' align='right' nowrap ><b><nobr>Amount</nobr></b></td>
		           </tr>";
 if($payFeeOf == 'all' || $payFeeOf == 'academic'){
 	 $bg = $bg =='trow0' ? 'trow1' : 'trow0'; 
 	$feeContent .= "<tr class='$bg'>
		                    <td class='padding_top' align='left' colspan='4' style='padding-left:10px;font-size:14px;'><b>Academic Fee</b></td>
		                 </tr>";
	for($i=0;$i<count($feeDataArray);$i++){
		
        $feeHeadName =$feeDataArray[$i]['feeHeadName'];
        $amount =$feeDataArray[$i]['amount'];
	  	$chkId = "chb".$cnt;	 		
		$checkAll = "<input type='checkbox' name='chb[]' id='$chkId' value='$feeHeadName!~!~!$cnt' >";
		
		$feeChk ="feeAmount".$cnt;
		  $feeAmount =   "<input type='text' name='feeAmount[]' id='$feeChk'  value='$amount' >";			  	
		  
		 $bg = $bg =='trow0' ? 'trow1' : 'trow0';    
         $feeContent .= "<tr class='$bg'>
		                    <td class='padding_top' align='center' ><strong>".$checkAll." </strong></td> 
                            <td class='padding_top' align='center' >".$cnt."</td>
		                    <td class='padding_top' align='left' >".$feeDataArray[$i]['feeHeadName']."</td> 
		                    <td class='padding_top' align='right' nowrap >".$feeAmount."</td>
		                 </tr>";
		$totalAmount += $feeDataArray[$i]['amount'];
		$cnt++;
		
	}
	 if($payFeeOf == 'all' || $payFeeOf == 'academic') {
               // to get Debit/Credit from ledger
               $ledgerTypeId = '1';
               $ledgerDataArray = $studentFeeManager->getLedgerData($studentId,$feeCycleId,$feeClassId,$ledgerTypeId);
            }
                
	        for($i=0;$i<count($ledgerDataArray);$i++) {
			        $comments = '';
			        $amount='';
			        $comments = substr(chunk_split(ucwords($ledgerDataArray[$i]['comments']),25,"<br/>"),0,-1);
			        if($ledgerDataArray[$i]['debit'] > 0){
				        $amount = number_format((float)$ledgerDataArray[$i]['debit'], 2, '.', '');
			        }
			        else{
				        $amount = "-".number_format((float)$ledgerDataArray[$i]['credit'], 2, '.', '');
			        }
					 $chkId = "chb".$cnt;	
					   $checkAll = "<input type='checkbox' name='chb[]' id='$chkId' value='$comments!~!~!$cnt' >";
		
					$feeChk ="feeAmount".$cnt;
		 			 $feeAmount =   "<input type='text' name='feeAmount[]' id='$feeChk'  value='$amount' >";	
					  $bg = $bg =='trow0' ? 'trow1' : 'trow0'; 
			        $feeContent .=" <tr class='$bg'>
					            <td class='padding_top' align='center' ><strong>".$checkAll." </strong></td> 
                                <td class='padding_top' align='center' >".$cnt."</td>
				              <td class='padding_top'  align='left' valign='top'>".$comments."</td>
				              <td class='padding_top'  align='right' nowrap valign='top'> ".$feeAmount."</td>
				           </tr>";
				           $totalAmount += $amount;
				           $cnt++;
			        
		    }
		    if(($feeDataArray[0]['concession'] > 0 )&& ($payFeeOf == 'all' || $payFeeOf == 'academic')){
			  $payableAmount = $totalAmount;
			  $concession = $feeDataArray[0]['concession'];
			  $totalAmount = $payableAmount - $concession;
					$concession = "-".number_format((float)$concession, 2, '.', '');	  
		              $feeHeadName ="Adjustment";
		               
						$chkId = "chb".$cnt;	
							   $checkAll = "<input type='checkbox' name='chb[]' id='$chkId' value='$feeHeadName!~!~!$cnt' >";
				
							$feeChk ="feeAmount".$cnt;
				 			 $feeAmount =   "<input type='text' name='feeAmount[]' id='$feeChk'  value='$concession' >";	
							  $bg = $bg =='trow0' ? 'trow1' : 'trow0'; 
					        $feeContent .=" <tr class='$bg'>
							            <td class='padding_top' align='center' ><strong>".$checkAll." </strong></td> 
		                                <td class='padding_top' align='center' >".$cnt."</td>
					      <td class='padding_top'  style='padding:4px 0px 0px 4px'>".$feeHeadName."</td>
					      <td class='padding_top'  align='right' nowrap style='padding-top:4px'>".$feeAmount."</td>
					   </tr>";
			  	$cnt++;	    
}	
	//previous Class Fee Content
	if($isPrevPrint ==$classId){
		if($prevAllClassAmount !='0'){
			 $bg = $bg =='trow0' ? 'trow1' : 'trow0';  
				$chkId = "chb".$cnt;
				$feeHeadName = "Prev. Class Balance <br>(".$prevAllClassComment.")";
			  $checkAll = "<input type='checkbox' name='chb[]' id='$chkId' value='$feeHeadName!~!~!$cnt' >";
			 $feeAmount =   "<input type='text' name='feeAmount[]' id='$feeChk'  value='$prevAllClassAmount' >";  
       		  $feeContent .= "<tr class='$bg'>
		                    <td class='padding_top' align='center' ><strong>".$checkAll." </strong></td> 
                            <td class='padding_top' align='center' >".$cnt."</td>
		                    <td class='padding_top' align='left' >".$feeHeadName."</td> 
		                    <td class='padding_top' align='right' nowrap >".$feeAmount."</td>
		                 </tr>";
			
			$totalAmount += $prevAllClassAmount;
			$cnt++;
		}
		
	}
  }
	if($payFeeOf == 'all' || $payFeeOf == 'hostel'){
		if($feeDataArray[0]['hostelFees'] > 0 && ($feeDataArray[0]['hostelId'] != '' && $feeDataArray[0]['hostelRoomId'] != '')){
			 $bg = $bg =='trow0' ? 'trow1' : 'trow0'; 
            $hostelDescId='1';
    		$feeContent .= "<tr class='$bg'>
		                    <td class='padding_top' align='left' colspan='4' style='padding-left:10px;font-size:14px;'><b>Hostel Fee</b></td>
		                 </tr>";	
            if($feeDataArray[0]['hostelSecurity'] > 0){
                 $feeHeadName ="Hostel Security";
                  $hostelSecurity =$feeDataArray[0]['hostelSecurity'];
            	 $chkId = "chb".$cnt;	 		
		 
		  $checkAll = "<input type='checkbox' name='chb[]' id='$chkId' value='$feeHeadName!~!~!$cnt' >";
		
		$feeChk ="feeAmount".$cnt;
		  $feeAmount =   "<input type='text' name='feeAmount[]' id='$feeChk'  value='$hostelSecurity' >";	 
		  	 $bg = $bg =='trow0' ? 'trow1' : 'trow0'; 
				$feeContent .=" <tr class='$bg'>
				                    <td class='padding_top' align='center' ><strong>".$checkAll." </strong></td> 
                                    <td class='padding_top' align='center' >".$cnt."</td>
				                    <td class='padding_top'  align='left' >Hostel Security</td> 
				                    <td class='padding_top'   align='right' nowrap >".$feeAmount."</td>
				                </tr>";
				$totalAmount += $feeDataArray[0]['hostelSecurity'];
				$cnt++;
			}
			 $chkId = "chb".$cnt;
              $feeHeadName ="Hostel Fees";
                  $hostelFees =$feeDataArray[0]['hostelFees'];	 		
		  
		    $checkAll = "<input type='checkbox' name='chb[]' id='$chkId' value='$feeHeadName!~!~!$cnt' >";
		
		$feeChk ="feeAmount".$cnt;
		  $feeAmount =   "<input type='text' name='feeAmount[]' id='$feeChk'  value='$hostelFees' >";	 
		  	 $bg = $bg =='trow0' ? 'trow1' : 'trow0'; 
			$feeContent .="  <tr class='$bg'>  
                                <td class='padding_top' align='center' ><strong>".$checkAll." </strong></td> 
                                <td class='padding_top' align='center' >".$cnt."</td>
			                    <td class='padding_top' align='left' >Hostel Fees</td> 
			                    <td class='padding_top'  align='right' nowrap >".$feeAmount."</td>
			                    </tr>";
			$totalAmount += $feeDataArray[0]['hostelFees'];
			$cnt++;
				if($payFeeOf == 'all' || $payFeeOf == 'hostel') {
               // to get Debit/Credit from ledger
               $ledgerTypeId = '3';
               $ledgerDataArray = $studentFeeManager->getLedgerData($studentId,$feeCycleId,$feeClassId,$ledgerTypeId);
            }
                
	        for($i=0;$i<count($ledgerDataArray);$i++) {
			        $comments = '';
			        $amount='';
			        $comments = substr(chunk_split(ucwords($ledgerDataArray[$i]['comments']),25,"<br/>"),0,-1);
			        if($ledgerDataArray[$i]['debit'] > 0){
				        $amount = number_format((float)$ledgerDataArray[$i]['debit'], 2, '.', '');
			        }
			        else{
				        $amount = "-".number_format((float)$ledgerDataArray[$i]['credit'], 2, '.', '');
			        }
					 $chkId = "chb".$cnt;	
					   $checkAll = "<input type='checkbox' name='chb[]' id='$chkId' value='$comments!~!~!$cnt' >";
		
					$feeChk ="feeAmount".$cnt;
		 			 $feeAmount =   "<input type='text' name='feeAmount[]' id='$feeChk'  value='$amount' >";	
					  $bg = $bg =='trow0' ? 'trow1' : 'trow0'; 
			        $feeContent .=" <tr class='$bg'>
					            <td class='padding_top' align='center' ><strong>".$checkAll." </strong></td> 
                                <td class='padding_top' align='center' >".$cnt."</td>
				              <td class='padding_top'  align='left' valign='top'>".$comments."</td>
				              <td class='padding_top'  align='right' nowrap valign='top'> ".$feeAmount."</td>
				           </tr>";
				           $totalAmount += $amount;
				           $cnt++;
			        
		    }
		    //previous Class Fee Content
		
		}

		
	}
	if($payFeeOf == 'all' || $payFeeOf == 'transport'){
		if($feeDataArray[0]['transportFees'] > 0 && ($feeDataArray[0]['busRouteId'] != '' && $feeDataArray[0]['busStopId'] != '')){
			 $bg = $bg =='trow0' ? 'trow1' : 'trow0'; 
			$feeContent .= "<tr class='$bg'>
		                    <td class='padding_top' align='left' colspan='4' style='padding-left:10px;font-size:14px;'><b>Transport Fee </b></td>
		                 </tr>";	
            $transportDescId='1';    
			$transportFee = $feeDataArray[0]['transportFees'];
			  $chkId = "chb".$cnt;
              $feeHeadName ="Transport Fees";
                          
          
		 $checkAll = "<input type='checkbox' name='chb[]' id='$chkId' value='$feeHeadName!~!~!$cnt' >";
		
		$feeChk ="feeAmount".$cnt;
		  $feeAmount =   "<input type='text' name='feeAmount[]' id='$feeChk'  value='$transportFee' >";
		  
		   $bg = $bg =='trow0' ? 'trow1' : 'trow0'; 	
			$feeContent .=" <tr class='$bg'>
			                     <td class='padding_top' align='center' ><strong>".$checkAll." </strong></td> 
                                <td class='padding_top' align='center' >".$cnt."</td>
			                    <td class='padding_top' align='left'>Transport Fees</td> 
			                    <td class='padding_top' align='right' nowrap >".$feeAmount."</td>
			                    </tr>";
			$totalAmount += $transportFee;
			$cnt++;
			if($payFeeOf == 'all' || $payFeeOf == 'transport') {
               // to get Debit/Credit from ledger
               $ledgerTypeId = '2';
               $ledgerDataArray = $studentFeeManager->getLedgerData($studentId,$feeCycleId,$feeClassId,$ledgerTypeId);
            }
                
	        for($i=0;$i<count($ledgerDataArray);$i++) {
			        $comments = '';
			        $amount='';
			        $comments = substr(chunk_split(ucwords($ledgerDataArray[$i]['comments']),25,"<br/>"),0,-1);
			        if($ledgerDataArray[$i]['debit'] > 0){
				        $amount = number_format((float)$ledgerDataArray[$i]['debit'], 2, '.', '');
			        }
			        else{
				        $amount = "-".number_format((float)$ledgerDataArray[$i]['credit'], 2, '.', '');
			        }
					 $chkId = "chb".$cnt;	
					   $checkAll = "<input type='checkbox' name='chb[]' id='$chkId' value='$comments!~!~!$cnt' >";
		
					$feeChk ="feeAmount".$cnt;
		 			 $feeAmount =   "<input type='text' name='feeAmount[]' id='$feeChk'  value='$amount' >";	
					  $bg = $bg =='trow0' ? 'trow1' : 'trow0'; 
			        $feeContent .=" <tr class='$bg'>
					            <td class='padding_top' align='center' ><strong>".$checkAll." </strong></td> 
                                <td class='padding_top' align='center' >".$cnt."</td>
				              <td class='padding_top'  align='left' valign='top'>".$comments."</td>
				              <td class='padding_top'  align='right' nowrap valign='top'> ".$feeAmount."</td>
				           </tr>";
				           $totalAmount += $amount;
				           $cnt++;
			        
		    }
		    
			//previous Class Fee Content
		
		
		}		
			
	}
	
   
      // to get Debit/Credit from ledger    END
	        
	
	$balance= 0;
	$totalFeePaid =0;
    $caption  = "Total ";
   if(($feeDataArray[0]['concession'] == 0) && ($payFeeOf == 'all' || $payFeeOf == 'academic')){
   	$caption = "Payable Amount";
   }
   $bg = $bg =='trow0' ? 'trow1' : 'trow0'; 
     $feeContent .=" <tr align='right' class='$bg'>                            	
                         	<td class='padding_top' style='padding-top:6px' align='right' width='11%' colspan='4'><strong>Total Fees : </strong>".$totalAmount."                       	                          	
                         	</td>
                         </tr>
                         ";               
       
       
  echo $feeContent;
	   
   die;
 
      

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
            $oldFeeHeadDelete = $generateFeeManager->checkStudentFeeHeadDelete($studentId,$feeClassId);
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
                if($studentArr['isMigration'] == 1 && $ttIsMigrationId == 1){
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