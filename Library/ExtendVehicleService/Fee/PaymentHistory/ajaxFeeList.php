<?php
//-------------------------------------------------------
// Purpose: To store the records of payment history in array from the database 
// Author : Nishu Bindal
// Created on : (08.April.2012 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    set_time_limit(0); 
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    global $sessionHandler;      
    $roleId = $sessionHandler->getSessionVariable('RoleId');     
    if($roleId=='3') {
      UtilityManager::ifParentNotLoggedIn(true);  
      $isCheckAdmin='0';
    }
    else if($roleId=='4') {
      UtilityManager::ifStudentNotLoggedIn(true);  
      $isCheckAdmin='0';
    }
    else  {
      UtilityManager::ifNotLoggedIn(true);
      $isCheckAdmin='1';
    }
    UtilityManager::headerNoCache();
 
    require_once(MODEL_PATH . "/Fee/PaymentHistoryReportManager.inc.php");   
    $PaymentHistoryReportManager = PaymentHistoryReportManager::getInstance();
        
	if($isCheckAdmin=='1' ) {
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
     
        $condition = "";
        
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
        }
    }
    else {
       $studentId = $sessionHandler->getSessionVariable('StudentId');      
       $condition .= " AND s.studentId = '$studentId' "; 
    }
        


    // to limit records per page    
    $page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
    $records    = ($page-1)* RECORDS_PER_PAGE;
    $limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;
    //////

               
    //$totalArray = $studentManager->getFeesHistoryListNew($filter);
    $sortOrderBy = (UtilityManager::notEmpty($REQUEST_DATA['sortOrderBy'])) ? $REQUEST_DATA['sortOrderBy'] : 'ASC';
    $sortField = (UtilityManager::notEmpty($REQUEST_DATA['sortField'])) ? $REQUEST_DATA['sortField'] : 'receiptDate';
    
    if($sortField=='undefined') {
      $sortField='receiptDate';  
    }
    
    if($sortOrderBy=='undefined') {
      $sortOrderBy='ASC';  
    }
    
    $sortField1 = $sortField;
    if($sortField=='receiptDate') {
      $sortField1 = 'frd.receiptDate';  
    }
    else if($sortField=='receiptNo') {
      $sortField1 = 'LENGTH(frd.receiptNo)+0,frd.receiptNo';  
    }
    $orderBy = "$sortField1 $sortOrderBy";   
    
    
    // Findout Student Fee Receipt (New Format)
    //$filter .= " AND fr.studentId = 1 ";
    //$totalArray = $PaymentHistoryReportManager->getPaymentHistoryCount($filter);
    //$studentRecordArray = $PaymentHistoryReportManager->getPaymentHistoryDetails($filter,$limit,$sortOrderBy,$sortField);
     $totalArray = $PaymentHistoryReportManager->getPaymentHistoryCountNew($condition);
     $studentRecordArray = $PaymentHistoryReportManager->getPaymentHistoryDetailsNew($condition,$limit,$sortOrderBy,$sortField);
	 $cnt = count($studentRecordArray);
	 
	 $cashTotal = 0;
	 $ddTotal = 0;
	 $receiptTotal = 0;
	 
     for($i=0;$i<$cnt;$i++) { 
        $feeReceiptId = $studentRecordArray[$i]['feeReceiptId'];
        $conessionFormatId  = $studentRecordArray[0]['isConessionFormat']; 
        $chkInstallment = $studentRecordArray[$i]['installmentCount'];
        $studentRecordArray[$i]['installmentNo'] = "Installment-".$studentRecordArray[$i]['installmentNo'];
  
        $link1 = '<u><a href="#" onclick="printOnlineSlip(\''.$studentRecordArray[$i]['receiptNo'].'\'); return false;">';
        $link2 = '</a></u>'; 
  
        $printAction = "<a href='javascript:void(0)' onClick='printDetailReceipt(\"".$studentRecordArray[$i]['feeReceiptId']."\",\"".$studentRecordArray[$i]['receiptNo']."\")' title='Print'><img src=".IMG_HTTP_PATH."/print1.gif border='0' alt='Detail Print' title='Detail Print' hspace='4'></a>&nbsp;|&nbsp;
        <a name='Delete' onclick='deleteReceipt(\"".$studentRecordArray[$i]['feeReceiptId']."\",\"".$studentRecordArray[$i]['receiptNo']."\");return false;' title='Delete'><img src='".IMG_HTTP_PATH."/delete.gif' border='0' alt='Delete' title='Delete'></a>";

        $studentRecordArray[$i]['receiptDate'] = UtilityManager::formatDate($studentRecordArray[$i]['receiptDate']);  
        
        if($studentRecordArray[$i]['receiveDD']=='0.00') {
          $studentRecordArray[$i]['receiveDD']='';  
        }
        
        if($studentRecordArray[$i]['receiveCash']=='0.00') {
          $studentRecordArray[$i]['receiveCash']='';  
        }
         if($studentRecordArray[$i]['onlinePayment']=='0.00') {
          $studentRecordArray[$i]['onlinePayment']='';  
        }
		
        $feeClassOnlineId =$studentRecordArray[$i]['feeClassId'];
        $cashTotal += $studentRecordArray[$i]['receiveCash'];
	    $ddTotal += $studentRecordArray[$i]['receiveDD'];
	    $receiptTotal += $studentRecordArray[$i]['amount'];
	      $onlineTotal += $studentRecordArray[$i]['onlinePayment'];    
	   
   
	    if($studentRecordArray[$i]['isOnlinePayment']=='1'){
		  $studentRecordArray[$i]['amount'] =$link1.$studentRecordArray[$i]['amount'].$link2;
          $studentRecordArray[$i]['feeTypeOf'] =$link1.$studentRecordArray[$i]['feeTypeOf'].$link2;
         $printAction = "<a href='javascript:void(0)' onclick='printOnlineSlip(\"".$studentRecordArray[$i]['receiptNo']."\"); return false;' title='Print'>
         <img src=".IMG_HTTP_PATH."/print1.gif border='0' alt='Detail Print' title='Detail Print' hspace='4'></a>
           <a name='Delete' onclick='deleteReceipt(\"".$studentRecordArray[$i]['feeReceiptId']."\",\"".$studentRecordArray[$i]['receiptNo']."\");return false;' title='Delete'><img src='".IMG_HTTP_PATH."/delete.gif' border='0' alt='Delete' title='Delete'></a>";

		}
	    	
			
			
        $valueArray = array_merge(array('printAction'=> $printAction,
                                        'srNos' => ($records+$i+1) ),
                                        $studentRecordArray[$i]);
        
        if(trim($json_val)=='') {
          $json_val = json_encode($valueArray);
        }
        else {
          $json_val .= ','.json_encode($valueArray);           
        } 
	 
    }

	 $cntTotal=0;
    	if($i>0) {
    		if($roleId=='4'){
	$valueArray1 = array_merge(array('srNos'=>'',
									'receiptDate'=>'',
									'receiptNo'=>'',
									'studentName'=>'',
									'rollNo'=>'',
									'className'=>'',
									'cycleName'=>'',
									'installmentNo'=>'',
									'feeTypeOf'=>'',																		
									'employeeCodeName'=>"<b>Total</b>",
									'receiveCash'=> "<b>".number_format($cashTotal,2,'.','')."</b>",
									'receiveDD'=>   "<b>".number_format($ddTotal,2,'.','')."</b>",
									 'ddDetail'=>'',
									  'onlinePayment'=> "<b>".number_format($onlineTotal,2,'.','')."</b>",
									'amount'=>  "<b>".number_format($receiptTotal,2,'.','')."</b>",                                       
									'printAction'=>''
                                        )); 
			}else{
			$valueArray1 = array_merge(array('srNos'=>'',
									'receiptDate'=>'',
									'receiptNo'=>'',
									'studentName'=>'',
									'rollNo'=>'',
									'className'=>'',
									'cycleName'=>'',
									'installmentNo'=>'',
									'feeTypeOf'=>'',																		
									'employeeCodeName'=>"<b>Total</b>",
									'receiveCash'=> "<b>".number_format($cashTotal,2,'.','')."</b>",
									'receiveDD'=>   "<b>".number_format($ddTotal,2,'.','')."</b>",
									 'ddDetail'=>'',
									'amount'=>  "<b>".number_format($receiptTotal,2,'.','')."</b>",                                       
									'printAction'=>''
                                        )); 	
				
				
				
				
			}
        if(trim($json_val)=='') {
          $json_val = json_encode($valueArray1);
        }
        else {
          $json_val .= ','.json_encode($valueArray1);           
        }  
         $cntTotal = count($totalArray)+1;
    	}
    echo '{"sortOrderBy":"'.$sortOrderBy.'","sortField":"'.$sortField.'","totalRecords":"'.$cntTotal.'","page":"'.$page.'","info" : ['.$json_val.']}';    
?>
