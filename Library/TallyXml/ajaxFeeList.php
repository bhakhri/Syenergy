<?php
//-------------------------------------------------------
// Purpose: To store the records of payment history in array from the database 
// Author : Nishu Bindal
// Created on : (08.April.2012 )
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
    
	require_once(BL_PATH . "/TallyXml/tallyXmlTemplate.php"); 
	
    
    $xmlFilePath = TEMPLATES_PATH."/Xml/initDownloadImages.php";
    if(!is_writable($xmlFilePath) ) {    
      logError("unable to open user activity data xml file...");
      echo  NOT_WRITEABLE_FOLDER; 
      die;
    } 
    
    // Remove Existing file
    $xmlFileName = 'feePaymentDetail.xml';
    if(file_exists(TEMPLATES_PATH."/Xml/".$xmlFileName)) {
      @unlink(TEMPLATES_PATH."/Xml/".$xmlFileName);
    }    
    
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
        
        if($fromDate!='' && $toDate!='') {
          $condition .= " AND (DATE_FORMAT(frd.receiptDate,'%Y-%m-%d') BETWEEN '$fromDate' and '$toDate') ";
        }
    }
    else {
       $studentId = $sessionHandler->getSessionVariable('StudentId');      
       $condition .= " AND s.studentId = '$studentId' "; 
    }
        
		
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
     $finalContent='';
     $studentRecordArray = $PaymentHistoryReportManager->getPaymentHistoryDetailsNew($condition,'',$sortOrderBy,$sortField);
	 $cnt = count($studentRecordArray);
     for($i=0;$i<$cnt;$i++) { 
        $feeReceiptId = $studentRecordArray[$i]['feeReceiptId'];
        $conessionFormatId  = $studentRecordArray[0]['isConessionFormat']; 
        
        $chkInstallment = $studentRecordArray[$i]['installmentCount'];
         
        $studentRecordArray[$i]['installmentNo'] = "Installment-".$studentRecordArray[$i]['installmentNo'];
  
        $printAction = "<a href='javascript:void(0)' onClick='printDetailReceipt(\"".$studentRecordArray[$i]['feeReceiptId']."\",\"".$studentRecordArray[$i]['receiptNo']."\")' title='Print'><img src=".IMG_HTTP_PATH."/print1.gif border='0' alt='Detail Print' title='Detail Print' hspace='4'></a>&nbsp;|&nbsp;<a name='Delete' onclick='deleteReceipt(\"".$studentRecordArray[$i]['feeReceiptId']."\",\"".$studentRecordArray[$i]['receiptNo']."\");return false;' title='Delete'><img src='".IMG_HTTP_PATH."/delete.gif' border='0' alt='Delete' title='Delete'></a>";

        $studentRecordArray[$i]['receiptDate'] = UtilityManager::formatDate($studentRecordArray[$i]['receiptDate']);  
        
        if($studentRecordArray[$i]['receiveDD']=='0.00') {
          $studentRecordArray[$i]['receiveDD']='';  
        }
        
        if($studentRecordArray[$i]['receiveCash']=='0.00') {
          $studentRecordArray[$i]['receiveCash']='';  
        }
        
        $contentXML='';
        if($studentRecordArray[$i]['receiveCash']!='') {
           $contentXML = $xmlContentCash;
           $contentXML = str_replace("<tallyEntryDate>",$studentRecordArray[$i]['receiptDate'],$contentXML);
           $contentXML = str_replace("<tallyReceiptNo>",$studentRecordArray[$i]['receiptNo'],$contentXML);
           $contentXML = str_replace("<tallyCollectedBy>",$studentRecordArray[$i]['employeeCodeName'],$contentXML);
           $contentXML = str_replace("<tallyAmount>",$studentRecordArray[$i]['amount'],$contentXML);
           $contentXML = str_replace("<tallyComment>",'',$contentXML); 
        }
        if($contentXML!='') {
          $finalContent .= $contentXML; 
        }
    }
    
    if($finalContent!='') {
      $strList  = $xmlHeader;  
      $strList .= $finalContent;
      $strList .= $xmlFooter;
      $fileName = TEMPLATES_PATH."/Xml/feePaymentDetail.xml"; 
      if(!is_writable($fileName)) {
         $handle = fopen($fileName, 'w');
         if(fwrite($handle, $strList) === FALSE){
           die("unable to write");
         }
         echo SUCCESS;
      }
      else {
         echo "unable to write xml file ".$fileName;   
      }
    }
    else {
       echo "Data not found"; 
    }
?>
