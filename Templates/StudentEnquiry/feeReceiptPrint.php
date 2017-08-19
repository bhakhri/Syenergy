<?php 
//This file is used as printing version for payment status.
//
// Author :Rajeev Aggarwal
// Created on : 14-08-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/StudentEnquiryManager.inc.php");
	require_once(BL_PATH . '/ReportManager.inc.php');
    require_once(BL_PATH . '/NumToWord.class.php');  
    require_once(TEMPLATES_PATH . "/StudentEnquiry/admissionLetterTemplate.php");     
    
    define('MODULE','COMMON');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

    global $sessionHandler;      
    
    $errorMessage ='';
    $studentEnquiryManager = StudentEnquiryManager::getInstance();
    $reportManager = ReportManager::getInstance();
    
    $studentId  = add_slashes(trim($REQUEST_DATA['studentId']));
    
    $instituteName = $reportManager->getInstituteName(); 
    
	$candidateArr = $studentEnquiryManager->getCandidateAllDetail(" AND afr.feeReceiptId = (SELECT MAX(feeReceiptId) FROM adm_fee_receipt WHERE studentId = $studentId) "); 
    
   // Student Detail Start
      $studentName = trim(ucwords($candidateArr[0]['studentName']));
      
      if($candidateArr[0]['receiptNo']!='') {
        $receiptNos = "and Receipt No. ".$candidateArr[0]['receiptNo'];   
      }
      $name = $candidateArr[0]['studentName']."<br>";
      $mr="";  
      if($candidateArr[0]['studentGender']=='M') {
         $mr = "S/o ";
      }
      else if($candidateArr[0]['studentGender']=='F'){
         $mr = "D/o "; 
      }
      
      if($candidateArr[0]['fatherName']!=''){
          $name .= $mr.$candidateArr[0]['fatherName']."<br>";
      }
      else if($candidateArr[0]['motherName']!=''){
        $name .= $mr.$candidateArr[0]['motherName']."<br>";
      }
      else if($candidateArr[0]['guardianName']!=''){
         $name .= "C/o ".$candidateArr[0]['guardianName']."<br>";
      }    
      $name = ucwords(strtolower($name));
      
	  $degreeDuration = ucwords(strtolower($candidateArr[0]['degreeDuration']));
      $branchName = ucwords(strtolower($candidateArr[0]['branchName']));
      $totalAmountPaid =  $candidateArr[0]['totalAmountPaid'];
      
      $num = new NumberToWord($candidateArr[0]['totalAmountPaid']);
      $num1 =  ucwords(strtolower($num->word));
    
      if($candidateArr[0]['ddAmount']){
         $ddAmount = $candidateArr[0]['ddAmount'];
         $ddNo = $candidateArr[0]['ddNo'];
         $ddDate =  (UtilityManager::formatDate($candidateArr[0]['ddDate']));
         $ddBankName = ucwords(strtolower($candidateArr[0]['ddBankName']));
      }
      $periodName = ucwords(strtolower($candidateArr[0]['periodName']));
      
      $strData1 = $contentsData;    
      $strData1 = str_replace("<studentName>",$studentName,$strData1); 
      $strData1 = str_replace("<institueName1>",$instituteName,$strData1);
      $strData1 = str_replace("<degreeDuration>",$degreeDuration,$strData1); 
      $strData1 = str_replace("<branchName>",$branchName,$strData1); 
      
      
      $strData1 = str_replace("<totalAmountPaid>",$totalAmountPaid,$strData1); 
      $strData1 = str_replace("<rsTotalAmountPaid>",$num1,$strData1); 
      $strData1 = str_replace("<receiptNo>",$receiptNos,$strData1); 
      
      $strData2 ='';
      if($candidateArr[0]['ddAmount'] != '0'){  
         $strData2 = $contentsData1;      
         $strData2 = str_replace("<ddAmount>",$ddAmount,$strData2); 
         $strData2 = str_replace("<ddNo>",$ddNo,$strData2); 
         $strData2 = str_replace("<ddDate>",$ddDate,$strData2); 
         $strData2 = str_replace("<ddBankName>",$ddBankName,$strData2); 
      }
      
      
      if($candidateArr[0]['cashAmount'] != ''){  
         $strData3 = $contentsData2;     
      }
      
      $strData4 = $contentsData3;   
      $strData4 = str_replace("<periodName>",$periodName,$strData4); 
      $strData4 = str_replace("<institueName1>",trim($instituteName),$strData4);  
      
      $strData = $strData1.$strData2.$strData3.$strData4;
    // Student Detail End
    
?>
	<table border="0" cellspacing="0" cellpadding="0" width="90%" align="center">
    <tr>
        <td align="left" colspan="1" width="25%" class=""><?php echo $reportManager->showHeader();?></td>
        <th align="center" colspan="1" width="50%" <?php echo $reportManager->getReportTitleStyle();?>><?php echo $reportManager->getInstituteName(); ?></th>
        <td align="right" colspan="1" width="25%" class="">
            <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right" width="50%">Date :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo date("d-M-y");?></td>
                </tr>
                <tr>
                    <td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right">Time :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo date("h:i:s A");?></td>
                </tr>
            </table>
        </td>
    </tr>
	<tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?> align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><u>ADMISSION LETTER</u></b></th></tr>
	</table> <br>
	 	 	 	 	 	 	 
	<table border='0' cellspacing='0' width="90%" class="reportTableBorder"  align="center">
	<tr>
		<td <?php echo $reportManager->getReportDataStyle();?> style='text-align:justify;font-size:16px;font-family:Times New Roman'>
            <?php
                echo $strData; 
            ?>
		</td>
	</tr>
	</table> <br>
	<table border='0' cellspacing='0' cellpadding='0' width="90%" align="center">
	<tr>
		<td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>></td>
	</tr>
	</table>
<?php 
// for VSS
// $History: feeReceiptPrint.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 4/14/10    Time: 11:51a
//Updated in $/LeapCC/Templates/StudentEnquiry
//receiptNo. added
//
//*****************  Version 3  *****************
//User: Parveen      Date: 3/23/10    Time: 6:34p
//Updated in $/LeapCC/Templates/StudentEnquiry
//query & condition format updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 3/18/10    Time: 12:45p
//Updated in $/LeapCC/Templates/StudentEnquiry
//validation & condition updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/17/10    Time: 2:59p
//Created in $/LeapCC/Templates/StudentEnquiry
//initial checkin
?>