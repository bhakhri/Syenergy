<?php
//-------------------------------------------------------
// Purpose: To show online payment records
// Author :harpreet kaur
// Created on : 13-may-2013
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
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
   
      UtilityManager::ifNotLoggedIn(true);
      UtilityManager::headerNoCache();
 
    require_once(MODEL_PATH . "/Fee/OnlineFeeManager.inc.php");   
    $onlineFeeManager = OnlineFeeManager::getInstance();
    
		$rollNo='';
		$studentName ='';
        $fromDate  = trim($REQUEST_DATA['fromDate']); 
        $toDate  = trim($REQUEST_DATA['toDate']); 
        $receiptNo  = htmlentities(add_slashes(trim($REQUEST_DATA['receiptNo']))); 
        $rollNo  = htmlentities(add_slashes(trim($REQUEST_DATA['rollNo']))); 
	    $studentName  = htmlentities(add_slashes(trim($REQUEST_DATA['studentName']))); 
	    $fatherName = htmlentities(add_slashes(trim($REQUEST_DATA['fatherName']))); 
        $onlineStatus = trim($REQUEST_DATA['onlineStatus']); //used to print fee-academic wise-1,hostel,wise-2,transport wise-3,all-4.
     
        $condition = "";
        $onlineFeeCondition ="";
       
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
        
		 if($onlineStatus=='2') { //failed
     	   $condition .= " AND ot.isStatus IN (0,1) AND frd.isDelete=1 ";
   		 }
		 if($onlineStatus=='1') {//sucessfull transactions
   		 	$condition .= " AND ot.isStatus =1 AND frd.isDelete=0  ";
   		 }
          
    
		
        if($fromDate!='' && $toDate!='') {
          $condition .= " AND (DATE_FORMAT(frd.receiptDate,'%Y-%m-%d') BETWEEN '$fromDate' and '$toDate') ";
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
  	//fetch total records
     $totalArray = $onlineFeeManager->getOnlinePaymentHistoryCountNew($condition,$onlineFeeCondition);
	 
	 //fetch complete information 
     $studentRecordArray = $onlineFeeManager->getOnlinePaymentHistoryDetailsNew($condition,$onlineFeeCondition,$limit,$sortOrderBy,$sortField);
	 $cnt = count($studentRecordArray);	 
	 
	 $receiptTotal = 0;
	 $feeTotal = 0;
	 $taxTotal = 0;
	 
	 $isStatus='';
     for($i=0;$i<$cnt;$i++) { 
        $feeReceiptId = $studentRecordArray[$i]['feeReceiptId'];
       
        
        $chkInstallment = $studentRecordArray[$i]['installmentCount'];
         
        $studentRecordArray[$i]['installmentNo'] = "Installment-".$studentRecordArray[$i]['installmentNo'];
  
        $printAction = "<a href='javascript:void(0)' onClick='printDetailReceipt(\"".$studentRecordArray[$i]['feeReceiptId']."\",\"".$studentRecordArray[$i]['receiptNo']."\")' title='Print'><img src=".IMG_HTTP_PATH."/print1.gif border='0' alt='Detail Print' title='Detail Print' hspace='4'></a>&nbsp;|&nbsp;<a name='Delete' onclick='deleteReceipt(\"".$studentRecordArray[$i]['feeReceiptId']."\",\"".$studentRecordArray[$i]['receiptNo']."\");return false;' title='Delete'><img src='".IMG_HTTP_PATH."/delete.gif' border='0' alt='Delete' title='Delete'></a>";

        $studentRecordArray[$i]['receiptDate'] = UtilityManager::formatDate($studentRecordArray[$i]['receiptDate']);
		  
		if($studentRecordArray[$i]['receiptDate']=='--'){
         $studentRecordArray[$i]['receiptDate'] = UtilityManager::formatDate($studentRecordArray[$i]['insertDate']); 
        
        }
					
        $feeArray = $studentRecordArray[$i]['feePaymentHistory'];
		//fee history(classid~feeType,..)
        $classIdArray='';
       
          $retArray = explode('~',$feeArray);
          $classId = $retArray[0];   
          $feeType = $retArray[1];
          $amout = $retArray[2];          
          
          
         
          if($feeType=='1'){
            $feeTypeDetail ="Academic";
          }
          if($feeType=='2'){
            $feeTypeDetail ="Transport";
          }
          if($feeType=='3'){
            $feeTypeDetail ="Hostel";
          }
          $classIdArray =$studentRecordArray[$i]['className']."<br>(".$feeTypeDetail.")";
        $isStatus = "";
     
        if($studentRecordArray[$i]['isDelete']=='1'){              
          if($studentRecordArray[$i]['isStatus']=='1') {
             $isStatus ="Success<br>"; 
          }   
		 $studentRecordArray[$i]['reason'] =  $studentRecordArray[$i]['reason']."<br>(".$studentRecordArray[$i]['employeeCodeName'].")";	
		  $printAction="";		   
   		}
        else if($studentRecordArray[$i]['isStatus']=='1' && $studentRecordArray[$i]['receiptNo'] !='--'){    			           
          $isStatus ="Success";
          //$printAction = "<a href='javascript:void(0)' onClick='printDetailReceipt(\"".$studentRecordArray[$i]['feeReceiptId']."\",\"".$studentRecordArray[$i]['receiptNo']."\")' title='Print'><img src=".IMG_HTTP_PATH."/print1.gif border='0' alt='Detail Print' title='Detail Print' hspace='4'></a>&nbsp;|&nbsp;<a name='Delete' onclick='deleteReceipt(\"".$studentRecordArray[$i]['feeReceiptId']."\",\"".$studentRecordArray[$i]['receiptNo']."\");return false;' title='Delete'><img src='".IMG_HTTP_PATH."/delete.gif' border='0' alt='Delete' title='Delete'></a>";
        }
        else if($studentRecordArray[$i]['isStatus']=='0'){
          $isStatus ="Failed";
		  $printAction="";
        }
        else{
          $isStatus ="Failed";
		  $printAction="";
        }
		  
	    $receiptTotal += $studentRecordArray[$i]['totalAmount'];
	    $feeTotal+=$studentRecordArray[$i]['totalFee'];
	    $taxTotal+=$studentRecordArray[$i]['taxAmount'];

        
		$printAction = "<a href='javascript:void(0)' onclick='printOnlineSlip(\"".$studentRecordArray[$i]['receiptNo']."\"); return false;' title='Print'>
                          <img src=".IMG_HTTP_PATH."/print1.gif border='0' alt='Detail Print' title='Detail Print' hspace='4'></a>&nbsp;
                        <a name='Delete' onclick='deleteReceipt(\"".$studentRecordArray[$i]['feeReceiptId']."\",\"".$studentRecordArray[$i]['receiptNo']."\");return false;' title='Delete'>
                           <img src='".IMG_HTTP_PATH."/delete.gif' border='0' alt='Delete' title='Delete'>
                        </a>";
        if($onlineStatus=='2'){    
          $printAction = NOT_APPLICABLE_STRING;  
        }
		
        $valueArray = array_merge(array('printAction'=> $printAction,
        								'isTransactionStatus' => $isStatus,
        								'feeClassDetails' => $classIdArray,
                                        'srNos' => ($records+$i+1)),
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
	$valueArray1 = array_merge(array('srNos'=>'',
									'receiptDate'=>'',
									'receiptNo'=>'',
									'studentName'=>'',
									'rollNo'=>'',
									'feeClassDetails'=>'',				
									'emailId'=>'',	
                                    'isTransactionStatus'=>'',    
                                    'userStatus'=>'',    																	
									'reason'=>"<center><b>Total</b></center>",
									'taxAmount'=>  "<b>".number_format($taxTotal,2,'.','')."</b>",
									'totalFee'=>"<b>".number_format($feeTotal,2,'.','')."</b>",
									'totalAmount'=>"<b>".number_format($receiptTotal,2,'.','')."</b>",
									'printAction'=>''									
                                        )); 
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
