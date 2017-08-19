<?php
//-------------------------------------------------------
// Purpose: to design add fees receipt.
//
// Author : Rajeev Aggarwal
// Created on : (17.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">Fee&nbsp;&raquo;&nbsp;Calculate Fees</td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <form action="" method="POST" name="feeForm" id="feeForm"> 
             
			 <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">

			 <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Student Fee: </td>
						
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
					<table cellspacing="0" cellpadding="0" border="0" width="100%">
					<tr>
						<td valign="top" width="35%">
							<table width="100%" border="0" cellspacing="0" cellpadding="0" >
							<tr>
								<td valign="top">
								<fieldset class="fieldset">
								<legend>Select Student</legend>
								<table width="100%" border="0" cellspacing="1" cellpadding="1" >
								<tr>
									<td class="contenttab_internal_rows" width="28%"><nobr><?php echo REQUIRED_FIELD?><B>Receipt Date</B></nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td >
									<?php
										   $todayDate = date("Y-m-d");	
										   require_once(BL_PATH.'/HtmlFunctions.inc.php');
										   echo HtmlFunctions::getInstance()->datePicker('receiptDate',$todayDate);
									?></td>
								</tr>
								<tr>	
									<td class="contenttab_internal_rows" width="28%"><nobr><?php echo REQUIRED_FIELD?><B>Student Roll No</B></nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td width="72%"><input type="text" id="studentRoll" name="studentRoll" class="inputbox" onChange="populateValues()"/>
									</td>
								</tr>
								<tr>	
									<td class="contenttab_internal_rows"><nobr><?php echo REQUIRED_FIELD?><B>Fee Cycle</B></nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td><select size="1" name="feeCycle" id="feeCycle"  onChange="populateValues()" class="inputbox1">
									  <option value="">Select</option>
									  <?php
										  require_once(BL_PATH.'/HtmlFunctions.inc.php');
										  echo HtmlFunctions::getInstance()->getFeeCycleData('',' AND (YEAR(CURRENT_DATE)-YEAR(fromDate))<=2');
									  ?>
									</select>
									</td>
								</tr>
								<tr>	
									<td class="contenttab_internal_rows"><nobr><?php echo REQUIRED_FIELD?><B>Fees Study Period</B> </nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td><select size="1" name="feeStudyPeriod" id="feeStudyPeriod" onChange="populateValues()" class="inputbox1">
									  <option value="">Select</option>
									  <?php
										  require_once(BL_PATH.'/HtmlFunctions.inc.php');
										  echo HtmlFunctions::getInstance()->getStudyPeriod();
									  ?>
									</select>
									<input type="hidden" name="studentId" id="studentId">
									<input type="hidden" name="studentClass" id="studentClass">
									<input type="hidden" name="totalFeesHidden" id="totalFeesHidden">
									<input type="hidden" name="hostelRoomId" id="hostelRoomId">
									<input type="hidden" name="busStopId" id="busStopId">
									</td>
								</tr>
								</table>
								</fieldset>
								</td>
							</tr>
							<tr>
								<td valign="top">
								<fieldset class="fieldset">
								<legend>Personal Details</legend>
								<table width="100%" border="0" cellspacing="1" cellpadding="3">
								<tr>	
									<td class="contenttab_internal_rows" width="34%"><nobr><B>Serial Number</B></nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td width="66%"><span id="mySerial">--</span><input type="hidden" id="serialNumber" name="serialNumber" class="inputbox" READONLY/>
									</td>
								</tr>
								<tr>	
									<td class="contenttab_internal_rows"><nobr><B>Receipt Number</B></nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td><span id="myReceipt">--</span><input type="hidden" id="receiptNumber" name="receiptNumber" class="inputbox" value="<?php echo FEE_RECEIPT_PREFIX?>" READONLY/>
									</td>
								</tr>
								<tr>	
									<td class="contenttab_internal_rows""><nobr><B>Class Name</B></nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td width="24%"><span id="myClass">--</span></td>
								</tr>
								<tr>	
									<td class="contenttab_internal_rows"><nobr><B>First Name</B></nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td><span id="myFirst">--</span><input type="hidden" id="studentName" name="studentName" class="inputbox" READONLY/>
									</td>
								</tr>
								<tr>	
									<td class="contenttab_internal_rows"><nobr><B>Last Name</B></nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td><span id="myLast">--</span><input type="hidden" id="studentLName" name="studentLName" class="inputbox"  READONLY/>
									</td>
								</tr>
								<tr>	
									<td class="contenttab_internal_rows"><nobr><B>Curr Study Period</B> </nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td><span id="myStudyPeriod">--</span> 
									<input type="hidden" name="currStudyPeriod">
									</select>
									</td>
								</tr>
								</table>
								</fieldset>
								</td>
							</tr>
							 
							</table>
						</td>
						<td valign="top">
						<table cellspacing="0" cellpadding="0" border="0" width="100%">
							<tr>
							<td valign="top">
							<fieldset class="fieldset">
							<legend>Fees Detail</legend>
							<table border="0" cellspacing="0" cellpadding="3" width="100%">
							 <tr>
								<td valign="top" width="100%">
								<div id="scroll" class="scroll1">
								<div id="results">
								<table border="0" cellspacing="0" cellpadding="3" width="100%">
								<tr class="rowheading">
									<td valign="middle" width="3%"><B>#</B></td>
									<td valign="middle" width="89%"><B>Fee Head</B></td>
									<td valign="middle" width="4%"><B>Amount</B></td>
									<td valign="middle" width="4%"><B>Concession</B></td>
								</tr>
								<tr class="row0">
									<td valign="middle" colspan="4" align="center">No detail found</td>
								</tr>
								</table>
								</div>
								</div>
								</td>
								<td valign="top" width="45%">
								<table border="0" cellspacing="1" cellpadding="1" width="100%">
								 <tr>
									<td valign="middle" align="right" class="contenttab_internal_rows"><B>Total Fees</B></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td valign="middle"><input type="text" id="totalFees" name="totalFees" class="inputbox2" size="14" value="0.00" READONLY/></td>
								 </tr>
								 <tr>
									<td valign="middle" align="right" nowrap class="contenttab_internal_rows"><B>Prev Payment</B></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td valign="middle"><input type="text" id="previousPayment" name="previousPayment" class="inputbox2" size="14" value="0.00" READONLY/></td>
								 </tr>
								 <tr>
									<td valign="middle" align="right" nowrap class="contenttab_internal_rows"><B>Prev Dues</B></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td valign="middle"><input type="text" id="previousDues" name="previousDues" class="inputbox2" size="14" value="0.00" READONLY/></td>
								 </tr>
								 <tr>
									<td valign="middle" align="right" class="contenttab_internal_rows"><B>Amt Payable</B></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td valign="middle"><input type="text" id="amountPayable" name="amountPayable" class="inputbox2" size="14" value="0.00" onChange="calculateConcession()" READONLY/></td>
								 </tr>
								 <tr>
									<td valign="middle" align="right" class="contenttab_internal_rows"><B>Fine</B></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td valign="middle"><input type="text" id="studentFine" name="studentFine" class="inputbox2" size="14" value="0.00" onChange="calculateConcession()"/></td>
								 </tr>
								 <tr>
									<td valign="middle" align="right" nowrap class="contenttab_internal_rows"><B>Total Concess</B></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td valign="middle"><input type="text" id="totalConcession" name="totalConcession" class="inputbox2" size="14" value="0.00"  READONLY/></td>
								 </tr>
								 <tr>
									<td valign="middle" align="right" nowrap class="contenttab_internal_rows"><B>Net Amt</B></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td valign="middle"><input type="text" id="netAmount" name="netAmount" class="inputbox2" size="14" value="0.00" READONLY/><input type="hidden" id="netAmount1" name="netAmount1" class="inputbox2" size="14" value="0.00" READONLY/></td>
								 </tr>
								 
								 <tr>
									<td valign="middle" align="right" nowrap class="contenttab_internal_rows"><B>Total Amt Paid</B></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td valign="middle"><input type="text" id="paidAmount" name="paidAmount" class="inputbox2" size="14" value="0.00"/></td>
								 </tr>
								 <tr>
									<td valign="middle" align="right" class="contenttab_internal_rows"><B>Installments</B></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td class="field1_heading"><span id="myInstallment">Installment 1</span></td>
								 </tr>
								 <tr>
									<td height="5"></td>
								</tr>
								</table>
								</td>
							</tr>
						</table>
						</fieldset>
					</td>
					</tr>

					<tr>
						<td height="5"></td>
					</tr>
					</table>
					</td>
					</tr>
					 
					</table>
					     
             </td>
          </tr>
         
			
          </table>
		 </form>    
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>
<?php 
// $History: calculateFeesContents.php $
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/Student
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 1/12/09    Time: 5:30p
//Updated in $/LeapCC/Templates/Student
//Updated with Required field, centralized message, left align
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Student
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 9/01/08    Time: 4:01p
//Updated in $/Leap/Source/Templates/Student
//updated as per new comments
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 8/07/08    Time: 3:01p
//Created in $/Leap/Source/Templates/Student
//intial checkin

?>
 
    


