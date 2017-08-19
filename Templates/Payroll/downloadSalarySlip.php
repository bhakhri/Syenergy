 <?php 
//This file is used as printing version for designations.
//
// Author :Jaineesh
// Created on : 13-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
?>

<?php
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    //require(BL_PATH."/html2pdf/html2fpdf.php");
    define('MODULE','Payroll');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache(); 

    require_once(MODEL_PATH . "/PayrollManager.inc.php");
    require_once(MODEL_PATH . "/CommonQueryManager.inc.php");
    require_once(MODEL_PATH . "/PayrollManager.inc.php");

	$empId = $REQUEST_DATA['empId'];
    $month = $REQUEST_DATA['month'];
    $year = $REQUEST_DATA['year']; 
    if($empId!="" && $month!="" && $year!="")
    {
         
            $empId=trim($REQUEST_DATA['empId']);
            $foundArray = CommonQueryManager::getInstance()->getEmployee('employeeName','and employeeId='.$empId);
            //$month=trim($REQUEST_DATA['salMonth']);
            //$year=trim($REQUEST_DATA['salYear']);   
            $cnt=count($foundArray);
            $totalEarning=0;
            $totalDeduction=0;
            $cnt_earning=0;
            $cnt_deduction=0;
            $json_val_Earning="";
            $json_val_Deduction="";
            $amount=0;
               if($cnt>0)
               {
               $department = CommonQueryManager::getInstance()->getSingleField('department','departmentName','where departmentId='.$foundArray[0]['departmentId']);
               $designation = CommonQueryManager::getInstance()->getSingleField('designation','designationName','where designationId='.$foundArray[0]['designationId']);
               $employeeArray=array('employeeName'=>$foundArray[0]['employeeName'], 'employeeCode'=>$foundArray[0]['employeeCode'], 'employeeDept'=>$department[0]['departmentName']
               , 'employeeDesignation'=>$designation[0]['designationName']);
               $conditions="where employeeId=".$empId." and MONTHNAME(withEffectFrom) like '".$month."' and YEAR(withEffectFrom) like '".$year."'";
               $foundAssignedArray = PayrollManager::getInstance()->getAssignedHeads($conditions);
               $cnt1=count($foundAssignedArray);
               if($cnt1>0)
               {
                   for($j=0;$j<$cnt1;$j++)
                   {
                       $foundHeadArray=PayrollManager::getInstance()->getHead('where headId='.$foundAssignedArray[$j]['headId']);
                       if($foundHeadArray[0]['headType']==0)
                       {
                           
                           $totalEarning=$totalEarning+$foundAssignedArray[$j]['headValue'];
                           $valueArray_earning[$cnt_earning]=array('headName'=>$foundHeadArray[0]['headName'], 'headValue'=>$foundAssignedArray[$j]['headValue']);
                           $cnt_earning++;
                       }
                       elseif($foundHeadArray[0]['headType']==1)
                       { 
                           $valueArray_deduction[$cnt_deduction]=array('headName'=>$foundHeadArray[0]['headName'], 'headValue'=>$foundAssignedArray[$j]['headValue']);
                           $totalDeduction=$totalDeduction+$foundAssignedArray[$j]['headValue'];
                           $cnt_deduction++;
                       }
                   } 
               }
               $amount=$totalEarning-$totalDeduction;
               $valueArray_total=array('totalEarning'=>$totalEarning, 'totalDeduction'=>$totalDeduction, 'net'=>$amount); 
            }
?>
<?php 
ob_start();
?>
<html>
<head>
</head>
<body>

      <table border=0 cellspacing=2 cellpadding=2 width=700 align="center">
        <tr>
          <td align=center> 
              <?php
                    global $sessionHandler;
                    echo $sessionHandler->getSessionVariable("InstituteName");
              ?><br />
          Salary slip for <?php echo $month." ".$year;?></td>
        </tr>
</table>
		<br>
		<table width=700 align=center border="0">
       
              <tr>
                <td style="padding:8px"><span class="style1">Name</span></td>
                <td colspan="3" style="padding:8px"><span class="style1" id="empName"><?php echo $employeeArray['employeeName']; ?></span></td>
              </tr>
              <tr>
                <td width="25%" valign="top" style="padding:4px 8px 4px 8px; border-top:1px solid #000;"><span class="style1">Employee Designation </span></td>
                <td width="25%" valign="top" style="padding:4px 8px 4px 8px; border-top:1px solid #000; border-right:1PX SOLID #000"><span class="style1" id="empDesignation"><?php echo $employeeArray['employeeDesignation']; ?></span></td>
                <td width="25%" valign="top" style="padding:8px; border-top:1px solid #000">Pay for the month of </td>
                <td width="25%" valign="top" style="padding:8px; border-top:1px solid #000"><span class="style1" id="salMonth"><?php echo $month;?></span></td>
              </tr>
              <tr>
                <td valign="top" style="padding:4px 8px 4px 8px; "><span class="style1">Employee Department </span></td>
                <td width="25%" valign="top" style="padding:4px 8px 4px 8px; border-right:1PX SOLID #000"><span class="style1" id="empDept"><?php echo $employeeArray['employeeDept']; ?></span></td>
                <td width="25%" valign="top" style="padding:8px; border-top:1px solid #000">Year</td>
                <td width="25%" valign="top" style="padding:8px; border-top:1px solid #000"><span class="style1" id="salYear"><?php echo $year;?></span></td>
              </tr>
              <tr>
                <td valign="top" style="padding:4px 8px 4px 8px; "><span class="style1">Employee Code </span></td>
                <td width="25%" valign="top" style="padding:4px 8px 4px 8px; border-right:1PX SOLID #000"><span class="style1" id="empCode"><?php echo $employeeArray['employeeCode']; ?></span></td>
                <td width="25%" valign="top" style="padding:8px; border-top:1px solid #000">&nbsp;</td>
                <td width="25%" valign="top" style="padding:8px; border-top:1px solid #000">&nbsp;</td>
              </tr>
</table>
			<table width="700" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #000" align="center">
              <tr>
                <td width="50%" style="border-right:1px solid #000;border-bottom:1px solid #000; padding:8px"><span class="style1">Earnings</span></td>
                <td style="padding:8px;border-bottom:1px solid #000;"><span class="style1">Deductions</span></td>
              </tr>
              <tr>
                <td style="border-right:1px solid #000; padding:0px" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td colspan="2" class="style1" style="padding:0px" valign="top"><span id="earningHeads" style="vertical-align:top">
                      <table width=100% border=0 cellspacing=0 cellpadding=0>
                      <?php
                      $headArraySize_Earning=count($valueArray_earning);
                      $headArraySize_Deduction=count($valueArray_deduction);
                      for($i=0;$i<$headArraySize_Earning;$i++)
                      {
                        echo'<tr><td width=65% style="border-right:1px solid #000;padding:8px">'.$valueArray_earning[$i]['headName'].'</td><td width=35% style="padding:8px">'.$valueArray_earning[$i]['headValue'].' INR </td></tr>';                       
                      }
                      if($headArraySize_Deduction > $headArraySize_Earning)
                      {
                         $headArraySize_Diff = $headArraySize_Deduction-$headArraySize_Earning;
                         for($i=0;$i<$headArraySize_Diff;$i++)
                         {
                            echo '<tr><td width=65% style="border-right:1px solid #000;padding:8px">&nbsp;</td><td width=35% style="padding:8px">&nbsp;</td></tr>'; 
                         } 
                      }                      
                      ?>
                      </table>
                      </span></td>
                    </tr>
                    <tr>
                      <td width="65%" class="style1" style="border-right:1px solid #000;border-top:1px solid #000; padding:5px">Total</td>
                      <td width="35%" align="right" class="style1" style="border-top:1px solid #000; padding:5px"><span id="totEarnings"><?php echo $valueArray_total['totalEarning'];?></span> INR</td>
                    </tr>
                </table></td>
                <td style="padding:0px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td colspan="2" class="style1" style="padding:0px" valign="top"><span id="deductionHeads" style="vertical-align:top">
                      <table width=100% border=0 cellspacing=0 cellpadding=0>
                      <?php
                      $headArraySize_Earning=count($valueArray_earning);
                      $headArraySize_Deduction=count($valueArray_deduction);
                      for($i=0;$i<$headArraySize_Deduction;$i++)
                      {
                        echo'<tr><td width=65% style="border-right:1px solid #000;padding:8px">'.$valueArray_deduction[$i]['headName'].'</td><td width=35% style="padding:8px">'.$valueArray_deduction[$i]['headValue'].' INR </td></tr>';                       
                      }
                      logError("Inside for".$headArraySize_Earning); 
                      logError("Inside for".$headArraySize_Deduction); 
                      if($headArraySize_Earning > $headArraySize_Deduction)
                      {
                         $headArraySize_Diff = $headArraySize_Earning-$headArraySize_Deduction;
                         for($i=0;$i<$headArraySize_Diff;$i++)
                         {
                             
                            echo '<tr><td width=65% style="border-right:1px solid #000;padding:8px">&nbsp;</td><td width=35% style="padding:8px">&nbsp;</td></tr>'; 
                         } 
                      }                      
                      ?>
                      </table>
                      </span></td>
                    </tr>
                    <tr>
                      <td width="65%" class="style1" style="border-right:1px solid #000;border-top:1px solid #000; padding:5px">Total</td>
                      <td width="35%" align="right" class="style1" style="border-top:1px solid #000; padding:5px"><span id="totDeductions"><?php echo $valueArray_total['totalDeduction'];?></span> INR</td>
                    </tr>
                </table></td>
              </tr>
          </table>
		 <table width="700" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td height="10"></td>
  </tr>
</table>
 
        <table width="700" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #000" align="center">
              <tr>
                <td width="400" style="padding:5px"></td>
                <td width="150" style="padding:5px"><span class="style1">Net Pay </span></td>
                <td width="150" align="right" style="padding:5px"><span class="style1"><span id="net"><?php echo $valueArray_total['net'];?></span> INR</span></td>
              </tr>
          </table>
      
        
      </table>
	  
    <br />
</body>
</html>
<?php
    }
   $htmlbuffer=ob_get_contents();
   ob_end_clean();
   require(BL_PATH."/html2pdf/html2fpdf.php");
   $pdf=new HTML2FPDF();
   $pdf->AddPage();
   $pdf->WriteHTML($htmlbuffer);
   $pdf->Output();
   header('Content-type: application/pdf');
   header('Content-Disposition: attachment; filename="downloaded.pdf"'); 
    ?>
    