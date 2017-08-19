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
	
	date_default_timezone_set('UTC');
	require_once($FE . "/Scripts/fpdf/fpdf.php");
    
	require_once(MODEL_PATH . "/CollectStudentFineManager.inc.php");
	$fineManager = CollectStudentFineManager::getInstance();

    require_once(TEMPLATES_PATH . "/Student/studentFineReceiptPrint.php");      

    require_once(BL_PATH . '/NumToWord.class.php');      
    
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
    $ttPaidAmount =  $paidAmount;
    
    
    
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
    

    $copyReceipt = array(0=>'Bank Copy',1=>'Institute Copy',2=>'Student Copy');
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

    //echo $str;


class PDF_result extends FPDF {
	function __construct ($orientation = 'L', $unit = 'pt', $format = 'Letter', $margin = 40) {
		$this->FPDF($orientation, $unit, $format);
		$this->SetTopMargin($margin);
		$this->SetLeftMargin($margin);
		$this->SetRightMargin($margin);
		
		$this->SetAutoPageBreak(true, $margin);
	}
	
	function Header () {
	//this image function will set the image on top of the file
	     	$this->Image($FE .IMG_HTTP_PATH."/logo.jpg",42,23,94);		
		$this->Image($FE .IMG_HTTP_PATH."/logo.jpg",275,23,94);		
		$this->Image($FE .IMG_HTTP_PATH."/logo.jpg",500,23,94);
		$this->Image($FE .IMG_HTTP_PATH."/cut.jpg",256,170,14);
		$this->Image($FE .IMG_HTTP_PATH."/cut.jpg",486,170,14);

	//	$this->SetFont('Arial', 'B', 20);
	//	$this->SetFillColor(36, 96, 84);
	//	$this->SetTextColor(225);
	//	$this->Cell(0, 30, "YouHack MCQ Results", 0, 1, 'C', true);
	}
	
    function Footer(){
        //Position at 1.5 cm from bottom
       // $this->SetY(-250);
        ///Arial italic 8
        //$this->SetFont('Arial','I',7);
        //Page number
      // $this->Cell(0,10,'Computerized Generate Slip so please pay on same date',0,0,'L');
	
    }

	
    function Generate_Table($studentFineArray,$pos) {              
        
        global $totalAmount;
        global $ttPaidAmount;
        
        $paidAmount = $ttPaidAmount;
        
        $this->SetFont('Arial', 'B', 7);
        $this->SetTextColor(0);
        
	   
        	//$this->SetFillColor(94, 188, z);
        $this->SetFillColor(238);
        $this->SetLineWidth(0.7);
	    $this->Cell(20, 15, "#", 1, 0, 'L', true);
        $this->Cell(120, 15, "Particulars", 1, 0, 'L', true);
        $this->Cell(60, 15, "Amount", 1, 1, 'R', true);
	
	    $this->SetFont('Arial', '');
        $this->SetFillColor(238);
        $this->SetLineWidth(0.7);
        $fill = false;

	      $srNo=1;
   	      $fineDetail = "";
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
	      
		     if($pos=='1') {
		     
		    }
		    else if($pos=='2') {
		      $this->SetX(275);
		    }
		    else if($pos=='3') {
		      $this->SetX(510);
		    }

             $this->Cell(20, 15,$srNo, 1, 0, 'L', $fill);
             $this->Cell(120, 15, $studentFineArray[$i]['fineCategoryName'], 1, 0, 'L', $fill);
             $this->Cell(60, 15,  $fineAmount, 1, 1, 'R', $fill);
             $fill = !$fill;
		    
	              $srNo++;         
              }

            }
	    }
        
	    if($pos=='1') {
		  $this->SetFont('Arial', 'B');       		       	
      	  $this->Cell(200, 15,"Total Amount   :  ".number_format((float)$totalAmount, 2, '.', ''), 1, 2, 'R',true);
	    }
	    else if($pos=='2') {
		  $this->SetX(275);
		  $this->SetFont('Arial', 'B');       		       	
      	  $this->Cell(200, 15,"Total Amount   :  ".number_format((float)$totalAmount, 2, '.', ''), 1, 2, 'R',true);
	    }
	    else if($pos=='3') {
		  $this->SetX(510);
		  $this->SetFont('Arial', 'B');       		       	
      	  $this->Cell(200, 15,"Total Amount   :  ".number_format((float)$totalAmount, 2, '.', ''), 1, 2, 'R',true);
	    }

	
       // $this->SetX(367);
       // $this->Cell(100, 20, "Total", 1);
       // $this->Cell(100, 20,  array_sum($marks), 1, 1, 'R');
    }

	
}
$pdf = new PDF_result();
$pdf->AddPage();
  $hostelDescId='';

 $size=7;

$yPosition=320;

$pdf->SetFont('Arial', 'B',$size);
$pdf->SetY(60);
$pdf->SetFont('Arial', 'B');
$pdf->Cell(230, 13, "Date:  ".date('d-m-y'));
$pdf->Cell(230, 13, "Date:  ".date('d-m-y'));
$pdf->Cell(230, 13, "Date:  ".date('d-m-y'));

$pdf->SetFont('Arial', 'B', $size);
$pdf->SetY(69);
$pdf->SetFont('Arial', 'B');
$pdf->Cell(120, 13, "Bank Name:  ".$instituteBankName);

$pdf->SetFont('Arial', 'B', $size);
$pdf->Cell(110, 13, "A/C No.:  ".$instituteBankAccountNo);

$pdf->SetFont('Arial', 'B');
$pdf->Cell(120, 13, "Bank Name:  ".$instituteBankName);

$pdf->SetFont('Arial', 'B', $size);
$pdf->Cell(110, 13, "A/C No.:  ".$instituteBankAccountNo);

$pdf->SetFont('Arial', 'B',$size);
$pdf->Cell(120, 13, "Bank Name:  ".$instituteBankName);

$pdf->SetFont('Arial', 'B',$size);
$pdf->Cell(10, 13, "A/C No.:  ".$instituteBankAccountNo);

$pdf->SetY(80);
$pdf->SetFont('Arial', 'B',$size+2);
$pdf->Cell(70, 20, "");
$pdf->Cell(155, 13, "FINE RECEIPT");

$pdf->SetFont('Arial', 'B',$size+2);
$pdf->Cell(70, 20, "");
$pdf->Cell(155, 13, "FINE RECEIPT");

$pdf->SetFont('Arial', 'B',$size+2);
$pdf->Cell(75, 20, "");
$pdf->Cell(155, 13, "FINE RECEIPT");

$pdf->SetFont('Arial', 'B', $size+1);
$pdf->SetY(95);
$pdf->SetFont('Arial', 'B');
$pdf->Cell(65, 13, "Student Name  :");
$pdf->SetFont('Arial', '');
$pdf->Cell(169, 13, $studentFeesArray[0]['studentName']);

$pdf->SetFont('Arial', 'B');
$pdf->Cell(65, 13, "Student Name  :");
$pdf->SetFont('Arial', '');
$pdf->Cell(170, 13, $studentFeesArray[0]['studentName']);

$pdf->SetFont('Arial', 'B');
$pdf->Cell(65, 13, "Student Name  :");
$pdf->SetFont('Arial', '');
$pdf->Cell(181, 13, $studentFeesArray[0]['studentName']);

$pdf->SetFont('Arial', 'B', $size+1);
$pdf->SetY(105);
$pdf->SetFont('Arial', 'B');
$pdf->Cell(65, 13, "Father's Name  :");
$pdf->SetFont('Arial', '');
$pdf->Cell(169, 13, $studentFeesArray[0]['fatherName']);

$pdf->SetFont('Arial', 'B');
$pdf->Cell(65, 13, "Father's Name  :");
$pdf->SetFont('Arial', '');
$pdf->Cell(170, 13, $studentFeesArray[0]['fatherName']);

$pdf->SetFont('Arial', 'B');
$pdf->Cell(65, 13, "Father's Name  :");
$pdf->SetFont('Arial', '');
$pdf->Cell(50, 13, $studentFeesArray[0]['fatherName']);

$pdf->SetFont('Arial', 'B', $size+1);
$pdf->SetY(115);
$pdf->SetFont('Arial', 'B');
$pdf->Cell(65, 13, "Class Name      :");
$pdf->SetFont('Arial', '');
$pdf->Cell(169, 13, $studentFeesArray[0]['className']);

$pdf->SetFont('Arial', 'B');
$pdf->Cell(65, 13, "Class Name      :");
$pdf->SetFont('Arial', '');
$pdf->Cell(170, 13, $studentFeesArray[0]['className']);

$pdf->SetFont('Arial', 'B');
$pdf->Cell(65, 13, "Class Name      :");
$pdf->SetFont('Arial', '');
$pdf->Cell(50, 13, $studentFeesArray[0]['className']);

$pdf->SetFont('Arial', 'B', $size+1);
$pdf->SetY(125);
$pdf->SetFont('Arial', 'B');
$pdf->Cell(65, 13, "Reg No.             :");
$pdf->SetFont('Arial', '');
$pdf->Cell(169, 13, $studentFeesArray[0]['regNo']);

$pdf->SetFont('Arial', 'B');
$pdf->Cell(65, 13, "Reg No.             :");
$pdf->SetFont('Arial', '');
$pdf->Cell(170, 13, $studentFeesArray[0]['regNo']);

$pdf->SetFont('Arial', 'B');
$pdf->Cell(65, 13, "Reg No.             :");
$pdf->SetFont('Arial', '');
$pdf->Cell(50, 13, $studentFeesArray[0]['regNo']);

$pdf->SetFont('Arial', 'B', $size+1);
$pdf->SetY(135);
$pdf->SetFont('Arial', 'B');
$pdf->Cell(65, 13, "Roll No.             :");
$pdf->SetFont('Arial', '');
$pdf->Cell(169, 13, $studentFeesArray[0]['rollNo']);

$pdf->SetFont('Arial', 'B');
$pdf->Cell(65, 13, "Roll No.             :");
$pdf->SetFont('Arial', '');
$pdf->Cell(170, 13, $studentFeesArray[0]['rollNo']);

$pdf->SetFont('Arial', 'B');
$pdf->Cell(65, 13, "Roll No.             :");
$pdf->SetFont('Arial', '');
$pdf->Cell(50, 13, $studentFeesArray[0]['rollNo']);


$pdf->SetY(165);
$pdf->Generate_Table($studentFineArray,1);

$pdf->SetY(165);
$pdf->SetX(275);
$pdf->Generate_Table($studentFineArray,2);

$pdf->SetY(165);
$pdf->SetX(510);
$pdf->Generate_Table($studentFineArray,3);

$num = new NumberToWord($totalAmount);
$num1 = trim(ucwords(strtolower($num->word)));
if($num1!='') {
  $num1 .=" Only";  
}

$pdf->SetFont('Arial', 'B', $size+1);
$pdf->SetY(235);

$pdf->SetFont('Arial', 'B');
$pdf->Cell(80, 13,"Amount (in words) : ");
$pdf->SetFont('Arial', 'B');
$pdf->Cell(155, 13, $num1);

$pdf->Cell(80, 13,"Amount (in words) : "); 
$pdf->SetFont('Arial', 'B');
$pdf->Cell(155, 13, $num1);

$pdf->Cell(80, 13,"Amount (in words) : "); 
$pdf->SetFont('Arial', 'B');
$pdf->Cell(55, 13, $num1);


$pdf->SetFont('Arial', 'B', $size+1);
$pdf->SetY(265);
$pdf->SetFont('Arial', 'B');
$pdf->Cell(55, 13, "Cash/DD No.:");
$pdf->SetFont('Arial', 'B');
$pdf->Cell(180, 13,"...................................................................");

$pdf->SetFont('Arial', 'B');
$pdf->Cell(55, 13, "Cash/DD No.:");
$pdf->SetFont('Arial', 'B');
$pdf->Cell(180, 13,"...................................................................");

$pdf->SetFont('Arial', 'B');
$pdf->Cell(55, 13, "Cash/DD No.:");
$pdf->SetFont('Arial', 'B');
$pdf->Cell(167, 13,"....................................................................");


$pdf->SetFont('Arial', 'B', $size+1);
$pdf->SetY(285);
$pdf->SetFont('Arial', 'B');
$pdf->Cell(45, 13, "...........................................");
$pdf->SetFont('Arial', 'B');
$pdf->Cell(190, 13,"..........................................Dated....................");

$pdf->SetFont('Arial', 'B');
$pdf->Cell(45, 13, "...........................................");
$pdf->SetFont('Arial', 'B');
$pdf->Cell(190, 13,"..........................................Dated....................");

$pdf->SetFont('Arial', 'B');
$pdf->Cell(45, 13, "...........................................");
$pdf->SetFont('Arial', 'B');
$pdf->Cell(167, 13,"..........................................Dated....................");

$pdf->SetFont('Arial', 'B', $size+1);
$pdf->SetY(305);
$pdf->SetFont('Arial', 'B');
$pdf->Cell(45, 13, "Bank Name");
$pdf->SetFont('Arial', 'B');
$pdf->Cell(190, 13,"........................................................................");

$pdf->SetFont('Arial', 'B');
$pdf->Cell(45, 13, "Bank Name");
$pdf->SetFont('Arial', 'B');
$pdf->Cell(190, 13,".........................................................................");

$pdf->SetFont('Arial', 'B');
$pdf->Cell(45, 13, "Bank Name");
$pdf->SetFont('Arial', 'B');
$pdf->Cell(50, 13,".............................................................................");

$pdf->SetFont('Arial', 'B', $size+1);
$pdf->SetY(325);
$pdf->SetFont('Arial', 'B');
$pdf->Cell(235, 13, "............................................................................................");

$pdf->SetFont('Arial', 'B');
$pdf->Cell(235, 13, ".............................................................................................");


$pdf->SetFont('Arial', 'B');
$pdf->Cell(30, 13, "................................................................................................");


$pdf->SetFont('Arial', 'B', $size+1);
$pdf->SetY(395);
$pdf->SetFont('Arial', 'B',$size+1);
$pdf->Cell(120, 13, "Depositor's Signature");

$pdf->SetFont('Arial', 'B', $size+1);
$pdf->Cell(120, 13, "Authorised Signatory");

$pdf->SetFont('Arial', 'B',$size+1);
$pdf->Cell(120, 13, "Depositor's Signature");

$pdf->SetFont('Arial', 'B', $size+1);
$pdf->Cell(120, 13, "Authorised Signatory");

$pdf->SetFont('Arial', 'B',$size+1);
$pdf->Cell(120, 13, "Depositor's Signature");

$pdf->SetFont('Arial', 'B', $size+1);
$pdf->Cell(10, 13, "Authorised Signatory");

$pdf->SetFont('Arial', 'I', $size+1);
$pdf->SetY(415);
$pdf->SetFont('Arial', 'I');
$pdf->Cell(240, 13, "Computerized Generate Slip so please pay on same date");

$pdf->SetFont('Arial', 'I');
$pdf->Cell(240, 13, "Computerized Generate Slip so please pay on same date");

$pdf->SetFont('Arial', 'I');
$pdf->Cell(130, 13, "Computerized Generate Slip so please pay on same date");
//echo "<pre>";print_r($feeDataArray);die;

$pdf->Output($FE ."/Templates/Xml/FineSlip.pdf", 'F');

$fileName = HTTP_PATH."/Templates/Xml/FineSlip.pdf";  

header('Content-disposition: attachment; filename=fineslip.pdf');
header('Content-type: application/pdf');
readfile($fileName );

die;
?>


