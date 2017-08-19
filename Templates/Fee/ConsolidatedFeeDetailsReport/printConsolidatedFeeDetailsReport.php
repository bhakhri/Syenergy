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
 	require_once($FE . "/Library/common.inc.php");
    	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();  

	require_once(MODEL_PATH . "/Fee/ConsolidatedFeeDetailsManager.inc.php");
	$ConsolidatedFeeDetailsManager = ConsolidatedFeeDetailsManager::getInstance();

	
	
	global $sessionHandler;
	$queryDescription =''; 
	$errorMessage ='';

	/*  if(!isset($REQUEST_DATA['instituteId']) || trim($REQUEST_DATA['instituteId']) == '') {
		    $errorMessage .= SELECT_INSTITUTE."\n";
	    }
	    if($errorMessage == '' && (!isset($REQUEST_DATA['studyPeriodId']) || trim($REQUEST_DATA['studyPeriodId']) == '')) {
		    $errorMessage .= SELECT_STUDYPERIOD."\n";
	    }
	    if($errorMessage == '' && (!isset($REQUEST_DATA['feeOf']) || trim($REQUEST_DATA['feeOf']) == '')) {
		    $errorMessage .= "Select Fee Of\n";
	    }
    */
    
    $instituteId = trim($REQUEST_DATA['instituteId']);
   $studyPeriodId = trim($REQUEST_DATA['studyPeriodId']);
    $feeOf = $REQUEST_DATA['feeOf']; // 1-> ACd , 2-> Hstl , 3->Transport 
    $fromDate = $REQUEST_DATA['fromDate'];
    $toDate = $REQUEST_DATA['toDate'];	
	
    $degreeId  = trim($REQUEST_DATA['degreeId']); 
    $branchId  = trim($REQUEST_DATA['branchId']); 
    $batchId  = trim($REQUEST_DATA['batchId']); 
    $classId  = trim($REQUEST_DATA['classId']);    
    $receiptNo  = htmlentities(add_slashes(trim($REQUEST_DATA['receiptNo']))); 
    $rollNo  = htmlentities(add_slashes(trim($REQUEST_DATA['rollNo']))); 
    $studentName  = htmlentities(add_slashes(trim($REQUEST_DATA['studentName']))); 
    $fatherName = htmlentities(add_slashes(trim($REQUEST_DATA['fatherName']))); 

 $frmCondition = "";

    if($instituteId!='') {
      $frmCondition .= " AND c.instituteId = '$instituteId' ";
    }
    if($degreeId!='') {
      $frmCondition .= " AND c.degreeId = '$degreeId' ";
    }
    if($branchId!='') {
      $frmCondition .= " AND c.branchId = '$branchId' ";
    }
    if($batchId!='') {
      $frmCondition .= " AND c.batchId = '$batchId' ";
    }
    if($classId!='') {
      $frmCondition .= " AND frm.feeClassId = '$classId' ";
    }
    if($rollNo!='') {
      $frmCondition .= " AND (s.rollNo LIKE '$rollNo%' OR s.regNo LIKE '$rollNo%') ";
    }
    if($studentName!='') {
      $frmCondition .= " AND (CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) LIKE '$studentName%') ";
    }
    if($fatherName!='') {
      $frmCondition .= " AND s.fatherName LIKE '$fatherName%' ";
    }
    if($receiptNo!='') {
      $frmCondition .= " AND frd.receiptNo LIKE '$receiptNo%' ";
    }
    
    
  
	// to limit records per page    
	$page       = (!UtilityManager::IsNumeric($REQUEST_DATA['page']) || UtilityManager::isEmpty($REQUEST_DATA['page']) ) ? 1 : $REQUEST_DATA['page'];
	$records    = ($page-1)* RECORDS_PER_PAGE;
	$limit      = ' LIMIT '.$records.','.RECORDS_PER_PAGE;

	if(trim($errorMessage) == ''){
		
		/*
		// fetching student Fee
		$frmCondition = '';
		if($instituteId != 'all'){
		  $frmCondition =" AND c.instituteId = '$instituteId' ";
		}

		if($studyPeriodId != ''){
		  $frmCondition .=" AND	c.studyPeriodId = '$studyPeriodId' ";
		}
		*/
		
		$frdCondition ='';
		$fhcCondition ='';
		if($fromDate !='' && $toDate !=''){
		  $frmCondition .=" AND date_format(frm.dated, '%Y-%m-%d' ) between '$fromDate' AND '$toDate'"; 
		  $frdCondition = " AND date_format(frd.receiptDate, '%Y-%m-%d' ) between '$fromDate' AND '$toDate'";
		  $fhcCondition = " AND date_format(fhc.receiptDate, '%Y-%m-%d' ) between '$fromDate' AND '$toDate'";
		  $flCondition = "  AND date_format(fl.date, '%Y-%m-%d' ) between '$fromDate' AND '$toDate'";
		}
		$feeDataArray = $ConsolidatedFeeDetailsManager->getFeeDetails($frmCondition,$frdCondition,$fhcCondition,$flCondition);
		
		$classArray = array();
		foreach($feeDataArray as $value){
			$classArray[] =  $value['classId'];
		}
		$finalFeeArray = array();
		$classArray = array_unique($classArray);
		$quotaWiseTotalFee = array();
		$uniqueArr= array();
		foreach($classArray as $value){
			$className ='';
			$totalStudents =0;
			$totalAmount =0;
			$paidStudents =0;
			$paidFees =0;
			$totalPartialPaidStudents = 0;
			$partialPaidFees =0;
			$unPaidStudents =0;
			$unpaidFees =0;
			$totalConcession =0;
			$totalFine = 0;
			$finePaid = 0;
			$transportFee = 0;
			$hostelFee = 0;
			$hostelSecurity=0;
			$leetAcademicFee = 0;
			$nonLeetAcademicFee = 0;
			$leetAndNonAcademicFee = 0;
			$migerationAcademicFee = 0;
			foreach($feeDataArray as $key => $value1){
			
				if($value == $value1['classId']){
					$className = $value1['className'];
					if($feeOf == 1){
						$totalStudents++;
						$totalAmount +=floatVal($value1['totalAcademicFees']) + floatVal($value1['academicFine'] -floatVal($value1['concession']));
						$totalConcession +=floatVal($value1['concession']);
						$totalFine += floatVal($value1['academicFine']);
						$finePaid += floatVal($value1['academicFinePaid']);
						if((floatVal($value1['totalAcademicFees']) + floatVal($value1['academicFine'])) <= (floatVal($value1['paidAcademicFee'] + $value1['concession']))){ // fully paid students
							$paidStudents++;
							$paidFees +=floatVal($value1['paidAcademicFee']);
						}
						else if(floatVal($value1['paidAcademicFee'] + $value1['concession']) == 0){ // Nt Paid Students
							$unPaidStudents++;
						}
						else if((floatVal($value1['totalAcademicFees']) + floatVal($value1['academicFine'])) > (floatVal($value1['paidAcademicFee'] + $value1['concession']))){ // partial paid Students
							$totalPartialPaidStudents++;
							$partialPaidFees +=floatVal($value1['paidAcademicFee']);
						}
						
						if(($leetAcademicFee > floatVal($value1['totalAcademicFees'])) && $value1['isLeet'] == 1){
							$leetAcademicFee = floatVal($value1['totalAcademicFees']);
						}
						else if($leetAcademicFee == '0' && $value1['isLeet'] == 1){
							$leetAcademicFee = floatVal($value1['totalAcademicFees']);
						}
					
						if(($nonLeetAcademicFee > floatVal($value1['totalAcademicFees'])) && $value1['isLeet'] == 0){
							$nonLeetAcademicFee = floatVal($value1['totalAcademicFees']);
							
						}
						else if($nonLeetAcademicFee == '0' && $value1['isLeet'] == 0){
							$nonLeetAcademicFee = floatVal($value1['totalAcademicFees']);
						}
						
						if(($leetAndNonAcademicFee > floatVal($value1['totalAcademicFees'])) && $value1['isLeet'] == 2){
							$leetAndNonAcademicFee = floatVal($value1['totalAcademicFees']);
							
						}
						else if($leetAndNonAcademicFee == '0' && $value1['isLeet'] == 2){
							$leetAndNonAcademicFee = floatVal($value1['totalAcademicFees']);
						}
						
						if(($migerationAcademicFee > floatVal($value1['totalAcademicFees'])) && $value1['isMigeration'] == 1){
							$migerationAcademicFee = floatVal($value1['totalAcademicFees']);
						}
						else if($migerationAcademicFee == '0' && $value1['isMigeration'] == 1){
							$migerationAcademicFee = floatVal($value1['totalAcademicFees']);
						}
						
					}
					else if($feeOf == 2){ // hostel Fee
						if($value1['hostelFees'] > 0){
							$totalStudents++;
							$totalAmount +=floatVal($value1['hostelFees']) + floatVal($value1['hostelFine']) + floatVal($value1['hostelSecurity']);
							$totalConcession =0;
							$totalFine += floatVal($value1['hostelFine']);
							$finePaid += floatVal($value1['hostelFinePaid']);
							$hostelSecurity = floatVal($value1['hostelSecurity']);
							if((floatVal($value1['hostelFees']) + floatVal($value1['hostelFine']) + $hostelSecurity) <= (floatVal($value1['hostelFeePaid'])+ floatVal($value1['hostelFine']) + floatVal($value1['hostelSecurityPaid']))){ // fully paid students
								$paidStudents++;
								$paidFees +=floatVal($value1['hostelFeePaid']);
							}
							else if(floatVal($value1['hostelFeePaid']) == 0){ // Nt Paid Students
								$unPaidStudents++;
							}
							else if((floatVal($value1['hostelFees']) + floatVal($value1['hostelFine']) + $hostelSecurity) > (floatVal($value1['hostelFeePaid']) + floatVal($value1['hostelFinePaid']) + floatVal($value1['hostelSecurityPaid']))){ // partial paid Students
								$totalPartialPaidStudents++;
								$partialPaidFees +=floatVal($value1['hostelFeePaid']);
							}
							
							if($hostelFee > floatVal($value1['hostelFees'])){
								$hostelFee = floatVal($value1['hostelFees']);
							}
							else if($hostelFee == 0){
								$hostelFee = floatVal($value1['hostelFees']);
							}
						}	
					}
					else if($feeOf == 3){ // Transport Fee
						if($value1['transportFees'] > 0){
							$totalStudents++;
							$transportFee = floatVal($value1['transportFees']);
							$totalAmount +=floatVal($value1['transportFees']) + floatVal($value1['tranportFine']);
							$totalConcession =0;
							$totalFine += floatVal($value1['tranportFine']);
							$finePaid += floatVal($value1['transportFinePaid']);
							if(floatVal($value1['transportFees'] + floatVal($value1['tranportFine'])) <= (floatVal($value1['transportFeePaid'])+ + floatVal($value1['transportFinePaid']))){ // fully paid students
								$paidStudents++;
								$paidFees +=floatVal($value1['transportFeePaid']);
							}
							else if(floatVal($value1['transportFeePaid']) == 0){ // Nt Paid Students
								$unPaidStudents++;
							}
							else if((floatVal($value1['transportFees']) + floatVal($value1['tranportFine'])) > (floatVal($value1['transportFeePaid']) + floatVal($value1['transportFinePaid']))){ // partial paid Students
								$totalPartialPaidStudents++;
								$partialPaidFees +=floatVal($value1['transportFeePaid']);
							}
						}
					}
				}
					
			}
			
			$unPaidFees = ($totalAmount) - ($paidFees + $partialPaidFees + $totalConcession + $finePaid);
			$finalFeeArray[] = array('className'=>"$className",'totalStudents'=>"$totalStudents",'totalAmount'=>"$totalAmount",'paidStudents'=>"$paidStudents",
						'paidFees'=>"$paidFees",'partialPaidStduents'=>"$totalPartialPaidStudents",'partialPaidFees'=>"$partialPaidFees",
						'unPaidStudents'=>"$unPaidStudents",'unPaidFees'=>"$unPaidFees",'totalConcession'=>"$totalConcession",'totalFine'=>$totalFine
						,'totalFinePaid'=>$finePaid,'leetAcdFee'=>$leetAcademicFee,'nonLeetAcdFee'=>$nonLeetAcademicFee,
						'leetAndNonLeetAcdFee'=>$leetAndNonAcademicFee,'migerationAcdFee'=>$migerationAcademicFee,'transportFee'=>$transportFee,
						'hostelFee'=>$hostelFee,'hostelSecurity'=>$hostelSecurity,'hostelFee'=>$hostelFee);
		
		}
		
		$heading = '';
		$feeCategoryHeading='';
		$rowspan= "rowspan='1'";
		$feeCategoryData ='';
		if($feeOf == 1){ // Academic Fee
			$feeCategoryHeading="<td width='20%'  valign='middle'  colspan = 4 class='searchhead_text' align='center'><strong>Fee Case's</strong></td>";
			$feeCategoryData ="<tr ><td valign='middle'  class='searchhead_text' align='center'><strong>Leet & Non Leet Fee</strong></td><td   valign='middle'  class='searchhead_text' align='center'><strong>Non Leet Fee</strong></td><td   valign='middle'  class='searchhead_text' align='center'><strong>Leet Fee</strong></td><td   valign='middle'  class='searchhead_text' align='center'><strong>Migeration Fee</strong></td></tr>";
			$rowspan = "rowspan='2'";
		}		
		
		$heading ="<table width='200%' border='1' cellspacing='0px' cellpadding='0'>
                   <tr >
                     <td width='2'  valign='middle' $rowspan class='searchhead_text' ><b>#</b></td>
                     <td width='8'  valign='middle' $rowspan class='searchhead_text' align='left'><strong>Class Name</strong></td>
                     <td width='5'  valign='middle' $rowspan class='searchhead_text' align='right' style='padding-right:8px;'><strong>Total Students</strong></td>
                     $feeCategoryHeading
                     <td width='5'  valign='middle' $rowspan class='searchhead_text' align='right' style='padding-right:8px;'><strong>Total Concession</strong></td>
                     <td width='5'  valign='middle' $rowspan class='searchhead_text' align='right' style='padding-right:8px;'><strong>Total Fine Due</strong></td>
                     <td width='5'  valign='middle' $rowspan class='searchhead_text' align='right' align='right' style='padding-right:8px;'><strong>Total Amount Due<p><font size='1px;' color='green'>(Total Fees + Total Fine Due + Misc Charges + Security - Concession)</font></strong></td>
                     <td width='5'  valign='middle' $rowspan class='searchhead_text' align='right' style='padding-right:8px;'><strong>Total Fine Paid</strong></td>
                     <td width='8'  valign='middle' $rowspan class='searchhead_text' align='right' style='padding-right:8px;'><strong>Fully Paid Students Count</strong></td>
                     <td width='10'  valign='middle' $rowspan class='searchhead_text' align='right' style='padding-right:8px;'><strong>Fully Paid Fees</strong></td>
                     <td width='10'  valign='middle' $rowspan class='searchhead_text' align='right' style='padding-right:8px;'><strong>Partial Paid
Students Count</strong></td><td width='8'  valign='middle' $rowspan class='searchhead_text' align='right' style='padding-right:8px;'><strong>Partail Paid Fees</strong></td>
<td width='10%'  valign='middle' $rowspan class='searchhead_text' align='right' style='padding-right:8px;'><strong>Unpaid
Students Count</strong></td><td width='20'  valign='middle' $rowspan class='searchhead_text' align='right' style='padding-right:8px;'><strong>Unpaid Fees<p><font size='1px;' color='green'>(Total Amount Due + Total Fine) - (Fully Paid Fees + Partial Paid Fees + Total Concession + Total Fine Paid)</font></strong></td></tr>".$feeCategoryData;
		
		if(count($finalFeeArray) == 0){
			$data = $heading."<tr><td colspan=17 class='padding_top' align='center'><strong>No Data Found</strong></td></tr>";
			reportGenerate($data,$studyPeriodName,$instituteName,$fromDate,$toDate,$feeOfName);
			die;
			
		}
		$i=0;
		$feeValueData='';
		$totalStudents =0;
                $totalConcession = 0;
                $totalFine =0;
                $totalFinePaid =0;
                $paidStudents =0;
                $paidFees =0;
                $partialPaidStduents =0;
                $partialPaidFees =0;
                $unPaidStudents =0;
                $unPaidFees =0;
                $totalAmountDue = 0;
                $feeValueData= $heading;
		foreach($finalFeeArray as $key => $value){
			$srNo = ($key+1);
			$data = '';
			if($feeOf == 1){ // Academic Fee
				$data = "<td valign='top' class='padding_top' align='right' style='padding-right:8px;' >".$value['leetAndNonLeetAcdFee']."</td>
                        		<td valign='top' class='padding_top' align='right' style='padding-right:8px;' >".$value['nonLeetAcdFee']."</td>
                        		<td valign='top' class='padding_top' align='right' style='padding-right:8px;'>".$value['leetAcdFee']."</td>
                        		<td valign='top' class='padding_top' align='right' style='padding-right:8px;' >".$value['migerationAcdFee']."</td>";
			}
			
			
			$feeValueData .="<tr>
                        <td valign='top' class='padding_top' align='left'>".$srNo."</td>
                        <td valign='top' class='padding_top' align='left'>".$value['className']."</td>
                        <td valign='top' class='padding_top' align='right' style='padding-right:8px;'>".$value['totalStudents']."</td>
                        $data
                        <td valign='top' class='padding_top' align='right' style='padding-right:8px;'>".$value['totalConcession']."</td>
                        <td valign='top' class='padding_top' align='right' style='padding-right:8px;'>".$value['totalFine']."</td>
                        <td valign='top' class='padding_top' align='right' style='padding-right:8px;'>".$value['totalAmount']."</td>
                        <td valign='top' class='padding_top' align='right' style='padding-right:8px;'>".$value['totalFinePaid']."</td>
                        <td valign='top' class='padding_top' align='right' style='padding-right:8px;'>".$value['paidStudents']."</td>
                        <td valign='top' class='padding_top' align='right' style='padding-right:8px;'>".$value['paidFees']."</td>
                        <td valign='top' class='padding_top' align='right' style='padding-right:8px;'>".$value['partialPaidStduents']."</td>
                        <td valign='top' class='padding_top' align='right' style='padding-right:8px;'>".$value['partialPaidFees']."</td>
                        <td valign='top' class='padding_top' align='right' style='padding-right:8px;'>".$value['unPaidStudents']."</td>
                        <td valign='top' class='padding_top' align='right' style='padding-right:8px;'>".$value['unPaidFees']."</td></tr>";
                        
                        $totalStudents += floatVal($value['totalStudents']);
                        $totalConcession += floatVal($value['totalConcession']);
                        $totalFine += floatVal($value['totalFine']);
                        $totalAmountDue += floatVal($value['totalAmount']);
                        $totalFinePaid += floatVal($value['totalFinePaid']);
                        $paidStudents += floatVal($value['paidStudents']);
                        $paidFees += floatVal($value['paidFees']);
                        $partialPaidStduents += floatVal($value['partialPaidStduents']);
                        $partialPaidFees += floatVal($value['partialPaidFees']);
                        $unPaidStudents += floatVal($value['unPaidStudents']);
                        $unPaidFees += floatVal($value['unPaidFees']);
                       
                        if($i == 24){
                        	reportGenerate($feeValueData,$studyPeriodName,$instituteName,$fromDate,$toDate,$feeOfName);
                        	$feeValueData= $heading;
                        	$i = -1;
                        }
                        $i++;
		}
		$blankData ='';
		if($feeOf == 1){
			$blankData = "<td valign='top' class='padding_top' align='left' colspan=4></td>";
		}
		$feeValueData .="<tr>
                        <td valign='top' class='padding_top' align='left' style='padding-top:10px'></td>
                        <td valign='top' class='padding_top' align='left' style='padding-top:10px'><strong>TOTAL</strong></td>
                        <td valign='top' class='padding_top' align='right' style='padding-right:8px;padding-top:10px'>".$totalStudents."</td>
                       $blankData
                        <td valign='top' class='padding_top' align='right' style='padding-right:8px;padding-top:10px'>".$totalConcession."</td>
                        <td valign='top' class='padding_top' align='right' style='padding-right:8px;padding-top:10px'>".$totalFine."</td>
                        <td valign='top' class='padding_top' align='right' style='padding-right:8px;padding-top:10px'>".$totalAmountDue."</td>
                        <td valign='top' class='padding_top' align='right' style='padding-right:8px;padding-top:10px;'>".$totalFinePaid."</td>
                        <td valign='top' class='padding_top' align='right' style='padding-right:8px;padding-top:10px;'>".$paidStudents."</td>
                        <td valign='top' class='padding_top' align='right' style='padding-right:8px;padding-top:10px;'>".$paidFees."</td>
                        <td valign='top' class='padding_top' align='right' style='padding-right:8px;padding-top:10px;'>".$partialPaidStduents."</td>
                        <td valign='top' class='padding_top' align='right' style='padding-right:8px;padding-top:10px;'>".$partialPaidFees."</td>
                        <td valign='top' class='padding_top' align='right' style='padding-right:8px;padding-top:10px;'>".$unPaidStudents."</td>
                        <td valign='top' class='padding_top' align='right' style='padding-right:8px;padding-top:10px;'>".$unPaidFees."</td></tr>";
		$feeValueData .="</table>";
		
		reportGenerate($feeValueData,$studyPeriodName,$instituteName,$fromDate,$toDate,$feeOfName);
	}
	else{
		echo $errorMessage; 
		die;
	}
	
	    // Report generate
    function reportGenerate($value,$studyPeriodName,$instituteName,$fromDate,$toDate,$feeOfName) {
        $reportManager = ReportManager::getInstance();
        $reportManager->setReportWidth(800);
        $reportManager->setReportHeading('Consolidated Fee Details Report');
        //$reportManager->setReportInformation($heading);
        
        $dateCheck ="Fees Details of $feeOfName Up To ".date('d-m-Y');
        if($fromDate !='' && $toDate !=''){
        	$dateCheck = "Fees Details of $feeOfName (From: $fromDate To : $toDate)";
        }
        $reportManager->setReportInformation("Institute : $instituteName $studyPeriodName $dateCheck");      
        ?>
        <div>
            <table border="0" cellspacing="0" cellpadding="0" width="100%" align="center">
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
            <tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?> align="center"><?php echo $reportManager->reportHeading; ?></th></tr>
            <tr><th colspan="3" <?php echo $reportManager->getReportInformationStyle(); ?>  align="center"><?php echo $reportManager->getReportInformation(); ?></th></tr>
            </table> <br>
            <table border='0' cellspacing='0' width="90%" class="reportTableBorder"  align="center">
                <tr>
                    <td valign="top">
                        <?php echo $value; ?>        
                    </td>
                </tr> 
            </table>       
            <br>
            <table border='0' cellspacing='0' cellpadding='0' width="100%" align="center">
            <tr>
            <td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
            </tr>
            </table>
            <br class='page'> 
        </div>
<?php        
    }
?>

                    
</html>
