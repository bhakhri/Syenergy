<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<style type="text/css" media="print">
@page port {size: portrait;}
@page land {size: landscape;}
.portrait {page: port;}
.landscape {page: land;}
</style>
</head>
<body>

<?php
//This file is used as printing version for student profile.
//
// Author :Nishu Bindal
// Created on : 14-Feb-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
         // recursive function to get receipt Number
      // not In used 
    /* function getReceiptNo($currentClassId,$feeClassId,$studentFeeManager,$instituteAbbr,$degreeAbbr,$batchYear,$feeStudyPeriodName){               
		$receiptNoArray = $studentFeeManager->getCountOfReceiptNo();
			$countOfReceipt = ($receiptNoArray[0]['noOfReceipt'] + 1);
		if( strlen($countOfReceipt) == 1){
			$countOfReceipt = "000$countOfReceipt";
		}
		else if(strlen($countOfReceipt) == 2){
			$countOfReceipt = "00$countOfReceipt";
		}
		else if(strlen($countOfReceipt) == 3){
			$countOfReceipt = "0$countOfReceipt";
		}
		
		//$receiptNo =  $instituteAbbr.'/'.$degreeAbbr.'/'.$batchYear.'/'.$feeStudyPeriodName.'/'.$countOfReceipt;
		$receiptNo = $countOfReceipt;
		$dataArray = $studentFeeManager->checkReceiptNo($receiptNo); // check for already exists
		
		if($dataArray[0]['cnt'] > 0){ 
			getReceiptNo($currentClassId,$classId,$studentFeeManager,$instituteAbbr,$degreeAbbr,$batchYear,$feeStudyPeriodName);
		}
		else{
			return $receiptNo;
		}
	}*/
      		
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
    
    
    $slipCopyNameArray = array(1=>'Student Copy',2=>'Bank Copy',3=>'College Copy');
	
    $hostelDescId='';
    $transportDescId='';
    
    $instituteAbbr = '';
    $instituteAbbArray = $sessionHandler->getSessionVariable('InstituteAbbArray');
    $instituteId = $sessionHandler->getSessionVariable('InstituteId');
    $instituteAbbr =  $instituteAbbArray[$instituteId];  
	$userId = $sessionHandler->getSessionVariable('UserId');
	$payFeeOf = $REQUEST_DATA['fee'];
	if(($userId==''|| $payFeeOf == '') || $REQUEST_DATA['feeClassId'] ==''){
		echo 'Required Parameters Missing';
		die;
	}
	$feeClassId = $REQUEST_DATA['feeClassId'];
	$feesClassArray = $studentFeeManager->getFeeClass($feeClassId); 
	$feeClassId = $feesClassArray[0]['feeClass'];	
	$feeStudyPeriodId = $feesClassArray[0]['studyPeriodId'];
	$batchId = $feesClassArray[0]['batchId'];
	$condition = "AND stu.userId = '$userId'";
	
    if($roleId==4) {    
      $currentClassId = $_SESSION['ClassId'];
      $studentId = $_SESSION['StudentId'];
    }
    else {
      $currentClassId = $REQUEST_DATA['currentClassId'];
      $studentId  = $REQUEST_DATA['studentId'];   
    }
    
    // Previous Class Balance (Start)
   
    $isCheckPrviousBalance='0';        // Check the previous class balance  1=>On, 0=>Off
    $isPreviousPaid =0;
    
		
		 $prevAllClassComment = '';	
		  $prevAllClassAmount = 0;
    $prevAcademicClassAmount =0;
    $prevHostelClassAmount =0;
    $prevTransportClassAmount =0; 
    
    if($isCheckPrviousBalance=='1') {
        
        $prevStudentId = $studentId;
        $prevClassId = $feeClassId;
       
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
         $isPrevPrint = 0; 
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
			   } 
               else {
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
// Previous Class Balance (End)
    
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
    
    
   
    $totalFeePaid =0;

	if($payFeeOf=='academic'){
	  $feeType=1;
	}
	
	if($payFeeOf=='transport'){
 	  $feeType=2;
 	}

	if($payFeeOf=='hostel'){
	  $feeType=3;
	}
	 
	if($payFeeOf =='all'){
	  $feeType=4;	
	}



   
   
  // Fee Payment Mode 1=>Academic, 4=>All
    if($payFeeOf=='academic' || $payFeeOf =='all') {
           /*
           $feeAcdSearch='0';
           $feeCycleCondition = " studentId = '$studentId' AND classId = '$feeClassId' ";
           $feeCycleArray = $generateFeeManager->checkStudentFeeGenerate($feeCycleCondition);
           if(count($feeCycleArray)> 0){
              $feeAcdSearch='1';
           }
           */
           $feeAcdSearch='0';
           /* 
              $feeCycleCondition = " AND studentId = '$studentId' AND classId = '$feeClassId' ";
              $feeCycleArray = $generateFeeManager->checkStudentFeeDetail($feeCycleCondition);
              if(is_array($feeCycleArray) && count($feeCycleArray)>0 ) {    
                $feeAcdSearch='1';
              }
              $feeAcdSearch='0';
           */
           
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
        
        
       $hostelDesc = " <u><b>Hostel Detail</b></u>
                       <table width='100%' border='1px' cellpadding='0px' cellspacing='0px' >
                        <tr>
                           <td class='dataFont' align='left' ><b>Hostel</b></td>
                           <td class='dataFont' align='center' ><b>Check In</b></td>
                           <td class='dataFont' align='center' ><b>Check Out</b></td>
                        </tr>   
                        <tr>
                           <td class='dataFont' align='left' >".$feeDataArray[0]['hostelName']." (".$feeDataArray[0]['roomName'].")</td>
                           <td align='center' class='dataFont'>".UtilityManager::formatDate($feeDataArray[0]['dateOfCheckIn'])."</td>
                           <td align='center' class='dataFont'>".UtilityManager::formatDate($feeDataArray[0]['dateOfCheckOut'])."</td>
                        </tr>
                       </table>";
        
      $transportDesc = " <u><b>Transport Detail</b></u>
                       <table width='100%' border='1px' cellpadding='0px' cellspacing='0px' >
                        <tr>
                           <td class='dataFont' align='left' ><b>Route</b></td>
                           <td class='dataFont' align='center' ><b>Valid From</b></td>
                           <td class='dataFont' align='center' ><b>Valid To</b></td>
                        </tr>   
                        <tr>
                           <td class='dataFont' align='left' >".$feeDataArray[0]['routeName']." (".$feeDataArray[0]['cityName'].")</td>
                           <td align='center' class='dataFont'>".UtilityManager::formatDate($feeDataArray[0]['validFrom'])."</td>
                           <td align='center' class='dataFont'>".UtilityManager::formatDate($feeDataArray[0]['validTo'])."</td>
                        </tr>
                       </table>";
         $t1 = $feeDataArray[0]['routeName']." (".$feeDataArray[0]['cityName'].")";
         $h1 = $feeDataArray[0]['hostelName']." (".$feeDataArray[0]['roomName'].")";              
      	
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
    
        $paddingLeft = "style='padding-left:45px'";
    
 $showPrevPayment = "<tr>
                        <td class='dataFont' $paddingLeft ><b>Prev. Payment</b></td>
                        <td class='dataFont'><b>&nbsp;:&nbsp;</b></td>
                        <td class='dataFont' align='right' ><PrevPayment></td>
                     </tr>";
                     
$showBalanceAdvance = "<tr>
                        <td class='dataFont' $paddingLeft ><b><BalanceAdvanceText></b></td>
                        <td class='dataFont'><b>&nbsp;:&nbsp;</b></td>
                        <td class='dataFont' align='right' ><BalanceAdvance></td>
                     </tr>";                     

$showPrevFine = "<tr>
                        <td class='dataFont' $paddingLeft ><b>Prev. Fine</b></td>
                        <td class='dataFont'><b>&nbsp;:&nbsp;</b></td>
                        <td class='dataFont' align='right' ><PrevFine></td>
                     </tr>";



  $feeContent = '';
  $totalAmount =0;
  $cnt = 1;
  if($payFeeOf == 'all' || $payFeeOf == 'academic'){
	  for($i = 0;$i<count($feeDataArray);$i++){ 
		$feeContent .=" <tr>
		<td class='dataFont' align='center'  style='padding-top:4px'>".$cnt."</td>
		<td class='dataFont'  style='padding-top:4px' >".$feeDataArray[$i]['feeHeadName']."</td> 
		<td class='dataFont'  style='padding-top:4px' align='right' nowrap >".$feeDataArray[$i]['amount']."</td>
		</tr>";
		$totalAmount += $feeDataArray[$i]['amount'];
		$cnt++;
	}
	//previous Class Fee Content
	if($isPrevPrint==$feeClassId){
		if($prevAllClassAmount !='0' ){
			$feeContent .=" <tr>
			<td class='dataFont' align='center'  style='padding-top:4px'>".$cnt."</td>
			<td class='dataFont'  style='padding-top:4px' >Prev. Class Balance <br>(".$prevAllClassComment.")</td> 
			<td class='dataFont'  style='padding-top:4px' align='right' nowrap >".$prevAllClassAmount."</td>
			</tr>";  
			$totalAmount += $prevAllClassAmount;
			$cnt++;
			$isPrevPrint=1;
	   }
	}
  }
	if($payFeeOf == 'all' || $payFeeOf == 'hostel'){
		if($feeDataArray[0]['hostelFees'] > 0 && ($feeDataArray[0]['hostelId'] != '' && $feeDataArray[0]['hostelRoomId'] != '')){
			
            $hostelDescId='1';
    
            if($feeDataArray[0]['hostelSecurity'] > 0){
				$feeContent .=" <tr>
				<td class='dataFont' align='center'  style='padding-top:4px'>".$cnt."</td>
				<td class='dataFont'  style='padding-top:4px' >Hostel Security</td> 
				<td class='dataFont'  style='padding-top:4px' align='right' nowrap >".number_format((float)$feeDataArray[0]['hostelSecurity'], 2, '.', '')."</td>
				</tr>";
				$totalAmount += $feeDataArray[0]['hostelSecurity'];
				$cnt++;
			}
			
			$feeContent .=" <tr>
			<td class='dataFont' align='center'  style='padding-top:4px'>".$cnt."</td>
			<td class='dataFont'  style='padding-top:4px' >Hostel Fees</td> 
			<td class='dataFont'  style='padding-top:4px' align='right' nowrap >".number_format((float)$feeDataArray[0]['hostelFees'], 2, '.', '')."</td>
			</tr>";
			$totalAmount += $feeDataArray[0]['hostelFees'];
			$cnt++;
			
			
		}
		if($totalAmount==0){
			echo "No Data Found"; die;
			}
	}
	if($payFeeOf == 'all' || $payFeeOf == 'transport'){
		if($feeDataArray[0]['transportFees'] > 0 && ($feeDataArray[0]['busRouteId'] != '' && $feeDataArray[0]['busStopId'] != '')){
            $transportDescId='1';    
			$transportFee = $feeDataArray[0]['transportFees'];
			$feeContent .=" <tr>
			<td class='dataFont' align='center'  style='padding-top:4px'>".$cnt."</td>
			<td class='dataFont'  style='padding-top:4px' >Transport Fees</td> 
			<td class='dataFont'  style='padding-top:4px' align='right' nowrap >".number_format((float)$transportFee, 2, '.', '')."</td>
			</tr>";
			$totalAmount += $transportFee;
			$cnt++;
			
			
		}
			if($totalAmount==0){
			echo "No Data Found"; die;
			}
	}
	
    // to get Debit/Credit from ledger    Start
            if($payFeeOf == 'academic') {
               $ledgerTypeId = '1';
               $ledgerDataArray = $studentFeeManager->getLedgerData($studentId,$feeCycleId,$feeClassId,$ledgerTypeId);
            }
            else if($payFeeOf == 'transport') {
               // to get Debit/Credit from ledger
               $ledgerTypeId = '2';
               $ledgerDataArray = $studentFeeManager->getLedgerData($studentId,$feeCycleId,$feeClassId,$ledgerTypeId);
            }
            else if($payFeeOf == 'hostel') {
               // to get Debit/Credit from ledger
               $ledgerTypeId = '3';
               $ledgerDataArray = $studentFeeManager->getLedgerData($studentId,$feeCycleId,$feeClassId,$ledgerTypeId);
            }
            else if($payFeeOf == 'all') {
               // to get Debit/Credit from ledger
               $ledgerTypeId = '1,2,3';
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
			        $feeContent .=" <tr>
					        <td class='dataFont' align='center'  style='padding-top:4px' valign='top'>".$cnt."</td>
				              <td class='dataFont'  style='padding:4px 0px 0px 4px' valign='top'>".$comments."</td>
				              <td class='dataFont'  align='right' nowrap style='padding-top:4px' valign='top'> ".$amount."</td>
				           </tr>";
				           $totalAmount += $amount;
				           $cnt++;
			        
		    }
      // to get Debit/Credit from ledger    END
	        
		
		
	$balance= 0;
	$totalFeePaid =0;
    $caption  = "Total ";
   if(($feeDataArray[0]['concession'] == 0) && ($payFeeOf == 'all' || $payFeeOf == 'academic')){
   	$caption = "Payable Amount";
   }
 
  $feeContent .=" <tr>
                      <td class='dataFont' colspan = 2  style='padding:4px 0px 0px 4px' ><strong>$caption</strong></td> 
                      <td class='dataFont'  style='padding-top:4px' align='right' nowrap >".number_format((float)$totalAmount, 2, '.', '')."</td>
                   </tr>";
                   
	 $feeAllType='';
	if($payFeeOf == 'academic'){
		$feeAllType ="AND frd.feeType=1"  ;
	}
	if($payFeeOf == 'transport'){
		$feeAllType ="AND frd.feeType=2"  ;
	}
	if($payFeeOf == 'hostel'){
		$feeAllType ="AND frd.feeType=3"  ;
	}

	if($payFeeOf == 'all'){
		$feeAllType = "AND frd.feeType IN (1,2,3,4)"  ;
	}
	
	
	$condition = " AND frm.studentId = '$studentId'  AND frm.feeClassId = '$feeClassId' $feeAllType";
	
	$prevFeeDetailArray = $studentFeeManager->getStudentPreviousFeeDetails($condition);
	
	
	
	

	for($i=0;$i<count($prevFeeDetailArray);$i++) {
			
$totalFeePaid += ($prevFeeDetailArray[$i]['DDAmount'] + $prevFeeDetailArray[$i]['checkAmount'] + $prevFeeDetailArray[$i]['cashAmount']);
	}
	
        if($totalFeePaid>0 && $totalAmount!=0){
		 $feeContent .=" <tr>
                      <td class='dataFont' colspan = 2  style='padding:4px 0px 0px 4px' ><strong>Prev. Paid Amount</strong></td> 
                      <td class='dataFont'  style='padding-top:4px' align='right' nowrap >".number_format((float)$totalFeePaid, 2, '.', '')."</td>
                   </tr>";
	
	$balance = $totalAmount - $totalFeePaid;
	$totalAmount = $balance;
	$feeContent .=" <tr>
                      <td class='dataFont' colspan = 2  style='padding:4px 0px 0px 4px' ><strong>Balance</strong></td> 
                      <td class='dataFont'  style='padding-top:4px' align='right' nowrap >".number_format((float)$totalAmount, 2, '.', '')."</td>
                   </tr>";
			} 
	 
	$logoPath = '';
	$logo = $_SESSION['InstituteLogo'];

	if($logo != ''){
		$logoPath =IMG_HTTP_PATH."/Institutes/".$logo."?yy=".rand(0,1000);
	}
	else{
		$logoPath = IMG_HTTP_PATH."/logo.gif";
	}
                        
if(($feeDataArray[0]['concession'] > 0 )&& ($payFeeOf == 'all' || $payFeeOf == 'academic')){
	  $payableAmount = $totalAmount;
	  $concession = $feeDataArray[0]['concession'];
	  $payableAmount = $payableAmount - $concession;

	 $feeContent .=" <tr>
			      <td class='dataFont' colspan = 2  style='padding:4px 0px 0px 4px'>Adjustment</td>
			      <td class='dataFont'  align='right' nowrap style='padding-top:4px'> -".number_format((float)$concession, 2, '.', '')."</td>
			   </tr>";
	  	
		       
	  $feeContent .=" <tr>
			      <td class='dataFont' colspan = 2 style='padding:4px 0px 0px 4px'><strong>Payable Amount</strong></td>
			      <td class='dataFont'  align='right' nowrap style='padding-top:4px'>".number_format((float)$payableAmount, 2, '.', '')
	."</td>
			   </tr>";
	  $totalAmount = $payableAmount;
	
}
	$num = new NumberToWord($totalAmount);
            $num1 = trim(ucwords(strtolower($num->word)));
            if($num1!='') {
              $num1 .=" Only";  
            }
 if($feeDataArray[0]['bankAddress'] ==''){
 	$feeDataArray[0]['bankAddress'] ='---';
 }
 $receiptData="<table width='100%' border='0px' cellpadding='0px' cellspacing='0px' >
 		        <tr class='dataFont'>
                    <td class='dataFont' colspan=2 style='padding-top:4px'><b>Date&nbsp;:</b>&nbsp;".date('d-m-y')." 
                        <span style='display:none;float:right'><b>Receipt / Scroll No</b>.......................</span>
                        <span style='float:right;'>
                        <b>&nbsp;<SlipCopyName></b></span>
                    </td>                
                 </tr>
                  <tr class='dataFont'>
                    <td class='dataFont' colspan=2 style='padding-top:4px'>
                        <b>Bank Name&nbsp;:&nbsp;</b>".$feeDataArray[0]['bankAbbr']."<span style='float:right'>
                        <b>A/C No.</b>&nbsp;".$feeDataArray[0]['instituteBankAccountNo']."</span></td> 
                    </tr>
                  
                   <tr class='dataFont' style='display:none'>
                    <td class='dataFont' colspan=2 style='padding-top:4px'>
                        <table width='100%' border=0 cellspacing=0 cellpading=0>
                           <tr>
                             <td valign='top' width='70px'><b>Bank Addr.&nbsp;:&nbsp;</b></td><td valign='top' >".$feeDataArray[0]['bankAddress']."</td>
                           </tr>
                        </table>
                    </td> 
                   </tr>
                   

                <tr class='dataFont'>
                     <td align='left' colspan='2' style='padding-top:10px'>
                        <table width='100%' border='0px' cellpadding='0px' cellspacing='0px' >
                          <tr>
                             <td align='left'>
                               <img src='$logoPath' width='200' height='60' border=0>       
                             </td> 
                          </tr>  
                        </table>
                     </td>
                 </tr> 
                 <tr class='dataFont'>
                     <td colspan='2' align='center' style='padding-top:10px'><b>FEE RECEIPT</b></td>
                 </tr>
                 <tr>
                    <td class='dataFont' style='padding-top:4px;width:35%' valign='top' nowrap> 
                       <b>Student Name</b>
                    </td>
                    <td class='dataFont' style='padding-top:4px;width:65%' nowrap valign='top'><b>:</b>&nbsp;".$feeDataArray[0]['studentName']."</td>
                 </tr>
                 <tr>
                 	<td class='dataFont' style='padding-top:4px' valign='top'> 
                 		<b>Father's Name</b>
                 	</td>
                 	<td class='dataFont' style='padding-top:4px' valign='top'><b>:&nbsp;</b>".$feeDataArray[0]['fatherName']."</td>
                 </tr>
                 <tr>
                    <td class='dataFont' align='left' style='padding-top:4px' valign='top'>
                        <b>Class Name</b></td>
                     <td class='dataFont' nowrap style='padding-top:4px' valign='top'><b>:</b>&nbsp;".$feeDataArray[0]['className']."</td>
                 </tr>
                 <tr>
                    <td class='dataFont' style='padding-top:4px' nowrap align='left'>
                      <b>Reg No.</b>
                    </td>
                    <td class='dataFont' style='padding-top:4px' nowrap><b>:</b>&nbsp;".$feeDataArray[0]['regNo']."</td>
                 </tr>
                 <tr>
                    <td class='dataFont' style='padding-top:4px' nowrap align='left'>
                      <b>Roll No.</b>
                    </td>
                    <td class='dataFont' style='padding-top:4px' nowrap><b>:</b>&nbsp;".$feeDataArray[0]['rollNo']."</td>
                 </tr>";
                 
                 if($hostelDescId=='1') {
                    $receiptData .="<tr>
                                    <td class='dataFont' style='padding-top:4px' nowrap align='left'>
                                      <b>Hostel</b>
                                    </td>
                                    <td class='dataFont' style='padding-top:4px' colspan='2' nowrap><b>:</b>&nbsp;".$h1."</td>
                                 </tr>";
                 }
                 
                 if($transportDescId=='1') {
                        $receiptData .="<tr>
                                    <td class='dataFont' style='padding-top:4px' nowrap align='left'>
                                      <b>Route</b>
                                    </td>
                                    <td class='dataFont' style='padding-top:4px' colspan='2' nowrap><b>:</b>&nbsp;".$t1."</td>
                                 </tr>";
                 }          
                 
                 
       $receiptData .="<tr>
                        <td colspan='2' style='padding-top:8px'>   
                          <table width='100%' border='1px' cellpadding='1px' cellspacing='0px'> 
                           <tr>
                               <td class='dataFont' align='center' width='5%'><strong>#</strong></td>
                               <td class='dataFont'  width='60%'><strong>Particulars</strong></td>
                               <td class='dataFont' align='right' width='35%'><strong>Amount</strong></td>
                           </tr> 
                            ".$feeContent." 
                         
                        </table>
                     </td>
                 </tr>"; 

	

	$receiptData .="<tr>	
				<td class='dataFont'  nowrap align='left'>
                                      <b>Amount (in words)</b>
                                    </td>
                               <td class='dataFont' align='left' width='5%' nowrap colspan='2'><strong> :".$num1."</strong></td>
                          
                 	</tr>"; 
                 /*                
                 if($hostelDescId=='1') {
                    $receiptData .="<tr><td class='dataFont' colspan='2' height='16px'></td></tr>
                                    <tr><td class='dataFont' colspan='2' valign='top'>$hostelDesc</td></tr>";
                 }
                 
                 if($transportDescId=='1') {
                     $receiptData .="<tr><td class='dataFont' colspan='2' height='16px'></td></tr> 
                                     <tr><td class='dataFont' colspan='2' valign='top'>$transportDesc</td></tr>";
                 }
                 */
                 $receiptData .="<tr><td class='dataFont' colspan='2' height='6px'></td></tr>
                 <tr><td class='dataFont' colspan='2' align='left'><b><u></u></b></td></tr>
                 <tr><td class='dataFont' colspan='2' height='6px'></td></tr>   
                  
                 <tr>
                    <td class='dataFont' colspan='3'><b>Cash&nbsp;/&nbsp;DD No.&nbsp;</b>............................................................</td>
                 </tr>
                  <tr>
                    <td class='dataFont' colspan='3' style='padding-top:5px'>..................................................<b>&nbsp;Dated&nbsp;</b>.......................</td>
                 </tr>
                  <tr>
                    <td class='dataFont' colspan='3' style='padding-top:5px'><b>Bank Name&nbsp;</b>................................................................</td>
                 </tr>
                 <tr>
                    <td class='dataFont' colspan='3' style='padding-top:5px'>......................................................................................</td>
                 </tr>     

                 <tr>
                   <td  valign='bottom' class='dataFont' colspan=3 style='padding-top:40px'><b>Depositor's Singnature</b> <span  style='float:right'>  <b>Authorised Signatory</b></span></td> 
                 </tr>                       
                 <tr>
                    <td  valign='bottom' class='dataFont' colspan=3 style='padding-top:10px;font-weight: normal; font-size: 9px; FONT-FAMILY: Arial, Helvetica, sans-serif; '>
                     <b><i>Computerized Generate Slip so please pay on same date</i></b>
                    </td>
                 </tr>
              </table>";

  
                  
	echo $paymentReceiptPrint = "<table width='98%' border='0px' cellpadding='0px' cellspacing='0px' align='center'>
               <tr>
                 <td width='30%'>".str_replace('<SlipCopyName>',$slipCopyNameArray[1],$receiptData)."</td>
                 <td width='5%' align='center'><img src=".STORAGE_HTTP_PATH."/Images/cut.png alt= ''></td>
                 <td width='30%'>".str_replace('<SlipCopyName>',$slipCopyNameArray[2],$receiptData)."</td>
                <td width='5%' align='center'><img src=".STORAGE_HTTP_PATH."/Images/cut.png alt ='' ></td>
                 <td width='30%'>".str_replace('<SlipCopyName>',$slipCopyNameArray[3],$receiptData)."</td>
               </tr>
               <tr>
               	<td height=20></td>
               </tr>
               <tr>
               <td colspan=5 align='right'><span id='hidePrint'><input type='image'  src=".IMG_HTTP_PATH.'/print.gif'." onClick=printout(); title=Print></span></td>
               </tr>
               </table>";

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
        
        </body>
        </html>
