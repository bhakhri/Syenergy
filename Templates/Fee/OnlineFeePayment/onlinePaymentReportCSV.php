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
     require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
    
       //to parse csv values    
    function parseCSVComments($comments) {
       $comments = str_replace('"', '""', $comments);
       $comments = str_ireplace('<br/>', "\n", $comments);
       if(eregi(",", $comments) or eregi("\n", $comments)) {
         return '"'.$comments.'"'; 
       } 
       else {
         return $comments.chr(160); 
       }
    }   
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
    
  	 
	 //fetch complete information 
     $studentRecordArray = $onlineFeeManager->getOnlinePaymentHistoryDetailsNew($condition,$onlineFeeCondition,$limit,$sortOrderBy,$sortField);
	 $cnt = count($studentRecordArray);	 
	 
	 $receiptTotal = 0;
	 $isStatus='';
	 
	   $csvData .= "#,Receipt Date,Receipt No.,Name,Roll No.,Fee Detail,Transaction Status,User Status,Reason,Total Fee,Convenience Charges,Total Receipt (Rs.)";
    $csvData .= "\n";
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
        else if($studentRecordArray[$i]['isStatus']=='1' && $studentRecordArray[$i]['reciptNo'] !='--'){                           
          $isStatus ="Success";
          $printAction = "<a href='javascript:void(0)' onClick='printDetailReceipt(\"".$studentRecordArray[$i]['feeReceiptId']."\",\"".$studentRecordArray[$i]['receiptNo']."\")' title='Print'><img src=".IMG_HTTP_PATH."/print1.gif border='0' alt='Detail Print' title='Detail Print' hspace='4'></a>&nbsp;|&nbsp;<a name='Delete' onclick='deleteReceipt(\"".$studentRecordArray[$i]['feeReceiptId']."\",\"".$studentRecordArray[$i]['receiptNo']."\");return false;' title='Delete'><img src='".IMG_HTTP_PATH."/delete.gif' border='0' alt='Delete' title='Delete'></a>";
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

        
         $printAction ="";  
        if($onlineStatus=='2'){    
          $printAction =""; 
        }
      
	    $receiptTotal += $studentRecordArray[$i]['totalAmount'];
	    $feeTotal+=$studentRecordArray[$i]['totalFee'];
	    $taxTotal+=$studentRecordArray[$i]['taxAmount'];
		
        $csvData .= ($i+1).",".parseCSVComments($studentRecordArray[$i]['receiptDate']).",".parseCSVComments($studentRecordArray[$i]['receiptNo']); 
        $csvData .= ",".parseCSVComments($studentRecordArray[$i]['studentName']).",".parseCSVComments($studentRecordArray[$i]['rollNo']);
        $csvData .= ",".parseCSVComments($classIdArray); 
         $csvData .= ",".$isStatus; 
		 $csvData .= ",".parseCSVComments($studentRecordArray[$i]['totalFee']); 
	
		$csvData .= ",".parseCSVComments($studentRecordArray[$i]['taxAmount']); 
	        $csvData .= ",".parseCSVComments($studentRecordArray[$i]['totalAmount']); 
	
        $csvData .= "\n";
    }
    $csvData .= ",";
		 $csvData .= ",";
		  $csvData .= ",";
		   $csvData .= ",";
		    $csvData .= ",";
			$csvData .= ",";
            $csvData .= ",";  
            $csvData .= ",";  		  
		$csvData .= "Total,";
		$csvData .= number_format($receiptTotal , 2, '.', '').",";
		$csvData .= number_format($receiptTotal , 2, '.', '').",";		
		$csvData .= number_format($receiptTotal , 2, '.', '').",";
    if($i==0) {
      $csvData .= ",,,No Data Found \n";   
    }
    
    UtilityManager::makeCSV($csvData,'OnlineFeeReceiptReport.csv');
    die;   
?>
