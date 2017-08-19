<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF Country Print
//
// Author : Saurabh Thukral
// Created on : (13.8.2012 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifStudentNotLoggedIn();

	require_once(MODEL_PATH . "/CollectStudentFineManager.inc.php");
	$fineManager = CollectStudentFineManager::getInstance();

    require_once(TEMPLATES_PATH . "/Student/studentFineReceiptPrint.php");      
    
    
	$rollNo= $REQUEST_DATA['rollNo'];

    $condition = '';
    $studentId = $sessionHandler->getSessionVariable('StudentId'); 
    if($studentId=='') {
      $studentId='0';  
    }
    $rollNo =  $studentId;
    
    $condition1 .= " frd.isDelete = 0";
    $condition = '';
    if($rollNo != "") {
	  $condition = " s.studentId=fs.studentId AND s.studentId = '".$studentId."'";
	  $condition1 .= " AND frd.studentId = '".$studentId."'";
	  $rollNo1=$rollNo;
	}

    $studentFeesArray = $fineManager->getStudentDetail(" AND st.studentId='".$studentId."'");
    $instituteId = $studentFeesArray[0]['instituteId'];
    
    $bankArray = $fineManager->getBankAccountNoList($instituteId);

    $instituteBankAccountNo ='';
    $instituteBankId ='';
     $instituteBankName ='';
    for($i=0;$i<count($bankArray);$i++) {
      if($bankArray[$i]['param'] == 'INSTITUTE_ACCOUNT_NO') {
        $instituteBankAccountNo = $bankArray[$i]['value'];
      }
      if($bankArray[$i]['param'] == 'INSTITUTE_BANK_NAME') {
        $instituteBankId = $bankArray[$i]['value'];
        $instituteBankName = $bankArray[$i]['bankAbbr'];
      }
    }
    
    // To fetch the total paid fine.
    $studentFineTotalArray = $fineManager->getStudentFineTotal($condition1);
    $paidAmount=$studentFineTotalArray[0]['totalPaidAmount'];
    if($paidAmount=='') {
      $paidAmount='0';  
    }
    
    
    // To fetch student fine details 
	$studentFineArray = $fineManager->getStudentFine($condition);
    
    $srNo=1;
    $fineDetail = "";
    $totalAmount = 0;
    $count = count($studentFineArray);
    for($i=0;$i<$count;$i++) {
    	$fineAmount=$studentFineArray[$i]['amount'];
        if($fineAmount=='') {
          $fineAmount='0';  
        }
        
    	if($fineAmount>=$paidAmount){
    	  $fineAmount=$fineAmount-$paidAmount;
    	  $paidAmount=0;
    	}
		else{
		  $paidAmount=$paidAmount-$fineAmount;
		  $fineAmount=0;
		}
        
        if($studentFineArray[$i]['fineCategoryName']!='') {   
          if($fineAmount > 0) {
		      $fineDetail .="<tr>
                                <td class='dataFont' style='padding-top:4px' nowrap>&nbsp;$srNo.</td>
                                <td class='dataFont' style='padding-top:4px' nowrap>&nbsp;".$studentFineArray[$i]['fineCategoryName']."</td> 
                                <td class='dataFont' align='right' style='padding-top:4px' nowrap>&nbsp;".$fineAmount."</td> 
                             </tr>";  
              $totalAmount += $fineAmount;               
              $srNo++;         
          }
        }
	}

    $dataContent = str_replace('<date>',date('d-M-y'),$dataContent);
    $dataContent = str_replace('<studentName>',$studentFeesArray[0]['studentName'],$dataContent);  
    $dataContent = str_replace('<fatherName>',$studentFeesArray[0]['fatherName'],$dataContent);  
    $dataContent = str_replace('<studentClass>',$studentFeesArray[0]['className'],$dataContent);  	  
    $dataContent = str_replace('<regNo>',$studentFeesArray[0]['regNo'],$dataContent);  
    $dataContent = str_replace('<rollNo>',$studentFeesArray[0]['rollNo'],$dataContent);     
    $dataContent = str_replace('<acNo>',$instituteBankAccountNo,$dataContent); 
    $dataContent = str_replace('<bankName>',$instituteBankName,$dataContent); 
    $dataContent = str_replace('<FineDetail>',$fineDetail,$dataContent);
	$dataContent = str_replace('<totalAmount>',$totalAmount,$dataContent);
    

    $copyReceipt = array(0=>'Student Copy',1=>'Bank Copy',2=>'College Copy');
    $str='';
	$str.="<table border='0px' cellpadding='0px' cellspacing='0px'>
            <tr>";
	for($i=0;$i<3;$i++) {
      $dataContent1 = $dataContent;  
      $copy = "<tr class='dataFont'>
                 <td class='dataFont' align='center' ><b>$copyReceipt[$i]</b></td>  
               </tr>";  
               
      $cut = "<td>
               <img src=".IMG_HTTP_PATH."/cut.png alt='' title=''>
             </td>";
      
      $dataContent1 = str_replace('<CopyReceipt>',$copy,$dataContent1);
      if($i!=2) {
        $dataContent1 = str_replace('<CutImage>',$cut,$dataContent1);
      }
      $str.= "<td>
                $dataContent1
              </td>";
	}
    $str.="</tr></table>";

    echo $str;
?>


