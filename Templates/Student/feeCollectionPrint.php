<?php 
//This file is used as printing version for fee collection.
//
// Author :Rajeev Aggarwal
// Created on : 14-08-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	 
	if(UtilityManager::notEmpty($REQUEST_DATA['univId'])) {
	    
	   $filter .= ' AND (cls.universityId IN ('.$REQUEST_DATA['univId'].'))';         
	}
	
	if(UtilityManager::notEmpty($REQUEST_DATA['instsId'])) {
	    
	   $filter .= ' AND (cls.instituteId IN ('.$REQUEST_DATA['instsId'].'))';         
	}
	
	if(UtilityManager::notEmpty($REQUEST_DATA['degsId'])) {
	    
	   $filter .= ' AND (cls.degreeId IN ('.$REQUEST_DATA['degsId'].'))';         
	}
	
	if(UtilityManager::notEmpty($REQUEST_DATA['bransId'])) {
	    
	   $filter .= ' AND (cls.branchId IN ('.$REQUEST_DATA['bransId'].'))';         
	}
	
	if(UtilityManager::notEmpty($REQUEST_DATA['semsId'])) {
	    
	   $filter .= ' AND (cls.studyPeriodId IN ('.$REQUEST_DATA['semsId'].'))';         
	}
	
	if(UtilityManager::notEmpty($REQUEST_DATA['feesId'])) {
	   
	   $feeCycleArr = explode(",", $REQUEST_DATA['feesId']);
	   //$filter .= ' AND (fr.feeCycleId IN ('.$REQUEST_DATA['feesId'].'))';         
	}
	
	if(UtilityManager::notEmpty($REQUEST_DATA['fromDate'])) {
	   $filter .= " AND (fr.receiptDate >='".add_slashes($REQUEST_DATA['fromDate'])."')";         
	}

	if(UtilityManager::notEmpty($REQUEST_DATA['toDate'])) {
	   $filter .= " AND (fr.receiptDate <='".add_slashes($REQUEST_DATA['toDate'])."')";         
	}
	
	
    

	//$totalArray = $studentManager->getTotalFeesStudent($filter);
    //$recordArray = $studentManager->getFeesHistoryList($filter,$limit='',$orderBy,$classId);
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
	<tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?> align="center">Fee Collection Report</th></tr>
	 
	</table> <br>
	 	 	 	 	 	 	 
	<table border='1' cellspacing='0' width="90%" class="reportTableBorder"  align="center">
	<tr>
		<td width="3%" align="left" class = 'headingFont'><b>&nbsp;#</b>
		<td valign="middle" align="left" width="55%" <?php echo $reportManager->getReportDataStyle()?>><b>Fee Cycle</b></td>
		<td valign="middle" align="right" width="8%" <?php echo $reportManager->getReportDataStyle()?>><b>Cash(Rs)</b></td>
		<td valign="middle" align="right" width="10%" <?php echo $reportManager->getReportDataStyle()?>><b>Cheque(Rs)</b></td>
		<td valign="middle" align="right" width="8%" <?php echo $reportManager->getReportDataStyle()?>><b>Draft(Rs)</b></td>
		<td valign="middle" align="right" width="8%" <?php echo $reportManager->getReportDataStyle()?>><b>Total(Rs)</b></td>
	</tr>
   <?php
    $cnt = count($feeCycleArr); 
	$j=0;
	$k=0;
	$filterPrev = $filter; 
	if($cnt >0 && is_array($feeCycleArr) ) { 
	for($i=0;$i<$cnt;$i++){
       $filter1 = '';	
	   //$filter1 = " AND fr.feeCycleId =".$feeCycleArr[$i];
       $filter1 = " AND fr.feeCycleId =".$feeCycleArr[$i]." AND fr.isNew = 1 AND fr.receiptStatus NOT IN (3,4) "; 
	   $filter  = $filterPrev.' '.$filter1;
	   
		
	   $studentRecordArray = $studentManager->getFeeCollectionList($filter,$limit,$orderBy);
       $cont = count($studentRecordArray);
	    
	   $filter1 = '';
	   $totalCash = '';
	   $totalCheque = '';
	   $totalDraft = '';
	   $feeCycle = '';
	   for($j=0;$j<$cont;$j++) {
		  $feeReceiptId = $studentRecordArray[$j]['feeReceiptId'];
          $totalCash +=$studentRecordArray[$j]['cashAmount'];    
              
          $conditions = " WHERE feeReceiptId = $feeReceiptId AND paymentInstrument =2";
          $resultArray = $studentManager->getSingleField('fee_payment_detail',' SUM(instrumentAmount) AS totalAmountPaid',$conditions);  
          $totalCheque +=$resultArray[0]['totalAmountPaid'];  
          
          $conditions = " WHERE feeReceiptId = $feeReceiptId AND paymentInstrument =3";
          $resultArray = $studentManager->getSingleField('fee_payment_detail',' SUM(instrumentAmount) AS totalAmountPaid',$conditions); 
          $totalDraft +=$resultArray[0]['totalAmountPaid'];   
           
          if($studentRecordArray[$j]['paymentInstrument']=="1")
          {
             $totalCash +=$studentRecordArray[$j]['totalAmountPaid'];
          }
          if($studentRecordArray[$j]['paymentInstrument']=="2")
          {
             $totalCheque +=$studentRecordArray[$j]['totalAmountPaid'];
          }
          if($studentRecordArray[$j]['paymentInstrument']=="3")
          {
             $totalDraft +=$studentRecordArray[$j]['totalAmountPaid'];
          }
		  $feeCycle = $studentRecordArray[$j]['feeCycleId'];
	   }

	   $feeArr = $studentManager->getFieldValue("fee_cycle","cycleName",$feeCycleArr[$i],"feeCycleId");
	   $feeCycle=	$feeArr[0]['cycleName'];

	   
	   $totalCash = ($totalCash!='') ? $totalCash : "-- ";
	   $totalCash =  number_format($totalCash,'2','.','');
	   
	   $totalCheque = ($totalCheque!='') ? $totalCheque : "-- ";
	   $totalCheque =  number_format($totalCheque,'2','.','');

	   $totalDraft = ($totalDraft!='') ? $totalDraft : "-- ";
	   $totalDraft =  number_format($totalDraft,'2','.','');

	   $totalAmount = $totalCash+$totalCheque+$totalDraft;
	   $totalAmount =  number_format($totalAmount,'2','.','');

	   $grandAmount = $grandAmount+$totalAmount;
	   $grandAmount =  number_format($grandAmount,'2','.','');

	   echo '<tr>
			<td valign="top"'.$reportManager->getReportDataStyle().' align="left">'.($i+1).'</td>
			<td valign="top"'.$reportManager->getReportDataStyle().' nowrap>'.$feeCycle.'</td>
			<td valign="top" '.$reportManager->getReportDataStyle().' align="right">'.$totalCash.'</td>
			<td valign="top" '.$reportManager->getReportDataStyle().' align="right">'.$totalCheque.'</td>
			<td valign="top" '.$reportManager->getReportDataStyle().' align="right">'.$totalDraft.'</td>
			<td valign="top" '.$reportManager->getReportDataStyle().' align="right">'.$totalAmount.'</td>
		</tr>';
		}
	}
	else {
		echo '<tr><td colspan="5" align="center" '.$reportManager->getReportDataStyle().'>No record found</td></tr>';
	}
	 ?>        
	</table>
	<table cellspacing='0' width="90%" class="reportTableBorder"  align="center">
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td valign="middle" align="right" <?php echo $reportManager->getReportDataStyle()?> colspan="4"><b>Grand Total(Rs)</b></td>
		<td valign="middle" align="right" width="9%" <?php echo $reportManager->getReportDataStyle()?>><?php echo $grandAmount;?></td>
	</tr>
	</table>
	<br>
	<table border='0' cellspacing='0' cellpadding='0' width="90%" align="center">
	<tr>
		<td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
	</tr>
	</table>
<?php
// for VSS
// $History: feeCollectionPrint.php $
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 09-09-03   Time: 12:40p
//Updated in $/LeapCC/Templates/Student
//fixed 0001421,0001422,0001428,0001430,0001434,0001435
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Student
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 9/16/08    Time: 1:43p
//Updated in $/Leap/Source/Templates/Student
//updated the query
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 9/16/08    Time: 1:22p
//Updated in $/Leap/Source/Templates/Student
//updated filter for different fee cycle
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/16/08    Time: 11:29a
//Created in $/Leap/Source/Templates/Student
//intial checkin 
?>