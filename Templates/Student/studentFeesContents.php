<?php
//-------------------------------------------------------
// Purpose: to design add fees receipt.
//
// Author : Rajeev Aggarwal
// Created on : (17.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
             <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
        </td>
    </tr>
    <tr>
        <td valign="top">
		 <form action="" method="POST" name="feeForm" id="feeForm"> 
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
			 <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
		
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
									<td class="contenttab_internal_rows" width="28%"><nobr><B>Receipt Date</B><?php echo REQUIRED_FIELD?></nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td >
									<?php
									   $todayDate = date("Y-m-d");	
									   require_once(BL_PATH.'/HtmlFunctions.inc.php');
									   echo HtmlFunctions::getInstance()->datePicker('receiptDate',$studentFeeReceiptdate);
									?></td>
								</tr>
                                <tr>    
                                    <td class="contenttab_internal_rows"><nobr><B>Receipt Number</B><?php echo REQUIRED_FIELD?></nobr></td>
                                    <td class="contenttab_internal_rows"><B>:</B></td>
                                    <td><?php //echo $studentFeeReceiptNo ?>
                                        <input type="text" id="receiptNumber" name="receiptNumber" class="inputbox1" value="<?php echo $receiptNo?>" style="width:180px" onkeypress="return sendKeys('feeType',event);"/>
                                    </td>
                                </tr>
								<tr>	
									<td class="contenttab_internal_rows"><nobr><B>Fee Type</B><?php echo REQUIRED_FIELD?></nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td><select style="width:185px" size="1" name="feeType" id="feeType"  onChange="populateValues()" class="inputbox1" onkeypress="return sendKeys('studentRoll',event);">
									  <?php
										  require_once(BL_PATH.'/HtmlFunctions.inc.php');
										  echo HtmlFunctions::getInstance()->getFeeTypeData();
									  ?>
									</select>
									</td>
								</tr>
                                <tr>    
                                    <td class="contenttab_internal_rows"><nobr><B>Fee Cycle</B><?php echo REQUIRED_FIELD?></nobr></td>
                                    <td class="contenttab_internal_rows"><B>:</B></td>
                                    <td><select style="width:185px" size="1" name="feeCycle" id="feeCycle"  onChange="populateValues()" class="inputbox1" onkeypress="return sendKeys('feeStudyPeriod',event);">
                                      <option value="">Select</option>
                                      <?php
                                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                          //echo HtmlFunctions::getInstance()->getFeeCycleData('',' AND (YEAR(CURRENT_DATE)-YEAR(fromDate))<=2');
                                          echo HtmlFunctions::getInstance()->getFeeCycleData();
                                      ?>
                                    </select>
                                    </td>
                                </tr>
                                <tr>    
                                    <td class="contenttab_internal_rows"><nobr><B>Fees Class</B><?php echo REQUIRED_FIELD?></nobr></td>
                                    <td class="contenttab_internal_rows"><B>:</B></td>
                                    <td><select size="1" name="feeStudyPeriod" id="feeStudyPeriod" onChange="populateValues()" class="inputbox1" style="width:185px">
                                      <option value="">Select</option>
                                      <?php
                                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                          echo HtmlFunctions::getInstance()->getStudyPeriod();
                                      ?>
                                    </select>
                                    <input type="hidden" name="studentId" id="studentId">
                                    <input type="hidden" name="deleteStudent" id="deleteStudent">
                                    <input type="hidden" name="studentClass" id="studentClass">
                                    <input type="hidden" name="totalFeesHidden" id="totalFeesHidden">
                                    <input type="hidden" name="hostelRoomId" id="hostelRoomId">
                                    <input type="hidden" name="busStopId" id="busStopId">
                                    </td>
                                </tr>
                                <tr>    
                                    <td class="contenttab_internal_rows" width="28%"><nobr><B>Student Roll No.</B><?php echo REQUIRED_FIELD?></nobr></td>
                                    <td class="contenttab_internal_rows"><B>:</B></td>
                                    <td width="72%"><nobr><input type="text" id="studentRoll" name="studentRoll" class="inputbox" onChange="populateValues()"  onkeypress="return sendKeys('feeCycle',event);"/></nobr></td>
                                </tr>
                                <tr>    
                                    <td class="contenttab_internal_rows" width="28%"></td>
                                    <td class="contenttab_internal_rows"></td>
                                    <td width="72%"><a href='javascript:void(0)'         
        onClick="showStudentDetails('getStudentDetail',850,100); blankValues();return false;" ><u>Search Student</u></a><td>
                                </tr> 
								</table>
								</fieldset>
								</td>
							</tr>
							<tr>
								<td valign="top">
								<fieldset class="fieldset">
								<legend>Personal Details</legend>
								<table width="100%" border="0" cellspacing="1" cellpadding="3" >
								<!--tr>	
									<td class="contenttab_internal_rows" width="34%"><nobr><B>Serial Number</B></nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td width="66%"><span id="mySerial">--</span><input type="hidden" id="serialNumber" name="serialNumber" class="inputbox" READONLY/>
									</td>
								</tr-->
								
								<tr>	
									<td class="contenttab_internal_rows" width="34%"><nobr><B>Class Name</B></nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td width="66%"><span id="myClass">--</span></td>
								</tr>
								<tr>	
									<td class="contenttab_internal_rows"><nobr><B>Name</B></nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td><span id="myFirst">--</span><input type="hidden" id="studentName" name="studentName" class="inputbox" READONLY/>&nbsp;<span id="myLast"></span><input type="hidden" id="studentLName" name="studentLName" class="inputbox"  READONLY/>
									</td>
								</tr>
								<tr>	
									<td class="contenttab_internal_rows"><nobr><B>Father Name</B></nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td><span id="myFather">--</span><input type="hidden" id="fatherName" name="fatherName" class="inputbox"  READONLY/>
									</td>
								</tr>
								<!--tr>	
									<td class="contenttab_internal_rows"><nobr><B>Curr Study Period</B></nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td><span id="myStudyPeriod">--</span> 
									<input type="hidden" name="currStudyPeriod">
									</select>
									</td>
								</tr-->
								</table>
								</fieldset>
								</td>
							</tr>
							<tr>
								<td valign="top">
								<fieldset class="fieldset">
								<legend>Remarks</legend>
								<table width="100%" border="0" cellspacing="1" cellpadding="1" >
								<tr>	
									<td class="contenttab_internal_rows" width="34%" valign="top"><nobr><B>Print Remarks</B></nobr></td>
									<td class="contenttab_internal_rows" valign="top"><B>:</B></td>
									<td><textarea type="text" id="printRemarks" name="printRemarks" class="inputbox1" rows="3" cols="30" tabindex="30"/></textarea></td>
								</tr>
								<tr>	
									<td class="contenttab_internal_rows" valign="top"><nobr><B>General Remarks</B></nobr></td>
									<td class="contenttab_internal_rows" valign="top"><B>:</B></td>
									<td><textarea type="text" id="generalRemarks" name="generalRemarks" class="inputbox1" rows="3" cols="30" tabindex="31"/></textarea></td>
								</tr>
								<tr>	
									<td class="contenttab_internal_rows" width="28%"><nobr><B>Received From</B></nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td width="72%"><input type="text" id="receivedFrom" name="receivedFrom" class="inputbox1" size="33"  tabindex="32"/>
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
								<div id="scroll" class="scroll">
								<div id="results">
									<table border="0" cellspacing="1" cellpadding="3" width="100%">
									<tr class="rowheading">
										<td valign="middle" width="3%"><B>#</B></td>
										<td valign="middle" width="89%"><B>Fee Head</B></td>
										<td valign="middle" width="4%"><B><nobr>Amount</nobr></B></td>
                                        <td valign="middle" width="4%"><B><nobr>Concession</nobr></B></td>
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
										<td valign="middle" align="right"class="contenttab_internal_rows"><B>Fine</B></td>
										<td class="contenttab_internal_rows"><B>:</B></td>
										<td valign="middle"><input type="text" id="studentFine" name="studentFine" class="inputbox2" size="14" value="0.00" onChange="calculateConcession()"  tabindex="8"/></td>
									 </tr>
									 <tr>
										<td valign="middle" align="right"class="contenttab_internal_rows"><B>Total Concess</B></td>
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
										<td valign="middle"><input type="text" id="paidAmount" name="paidAmount" class="inputbox2" size="14" value="0.00"  tabindex="11"/></td>
									 </tr>
									 <tr>
										<td valign="middle" align="right" class="contenttab_internal_rows"><B>Installments</B></td>
										<td class="contenttab_internal_rows"><B>:</B></td>
										<td class="field1_heading"><span id="myInstallment">Installment 1</span></td>
									 </tr>
									 <tr>
										<td class="contenttab_internal_rows"><nobr><B>Payable Bank</B></nobr></td>
										<td class="contenttab_internal_rows"><B>:</B></td>
										<td class="padding"><select size="1" name="payableBank" id="payableBank" class="inputbox1"  tabindex="14" style="width:100px">
										  <option value="">Select</option>
										  <?php
											  require_once(BL_PATH.'/HtmlFunctions.inc.php');
											  echo HtmlFunctions::getInstance()->getBankData();
										  ?>
										</select>
										</td>
									</tr>
									<tr>
										<td class="contenttab_internal_rows"><nobr><B>Payable Fav Branch</B></nobr></td>
										<td class="contenttab_internal_rows"><B>:</B></td>
										<td class="padding"><select size="1" name="favouringBank" id="favouringBank" class="inputbox1"  tabindex="15" style="width:100px">
										  <option value="">Select</option>
										  <?php
											  require_once(BL_PATH.'/HtmlFunctions.inc.php');
											  echo HtmlFunctions::getInstance()->getBankBranchData();
										  ?>
										</select>
										</td>
									</tr>
									<tr>	
										<td class="contenttab_internal_rows"><nobr><B>Cash Amount</B></nobr></td>
										<td class="contenttab_internal_rows"><B>:</B></td>
										<td class="padding"><input type="text" id="cashAmount" name="cashAmount"  class="inputbox2" size="14"  tabindex="16"  value="0.00" onBlur="getAmountPaid();"/></td>
									</tr>
									</table>
								</td>
							</tr>

						</table>
						</fieldset>
					</td>
					</tr>
					<tr>
						<td valign="top">
						<fieldset class="fieldset">
						<legend>Cheque/Draft Payment Detail</legend>
						<table width="100%" border="0" cellspacing="1" cellpadding="3" >
						<tr>
							<td class="contenttab_row" valign="top" >
							<div id='anyidT'>
							<table width="100%" border="0" cellspacing="2" cellpadding="0" id="anyid">
								<tbody id="anyidBody">
									<tr class="rowheading">
										<td width="2%" class="searchhead_text"><b>Sr.</b></td>
										<td class="searchhead_text"><b>Type</b></td>
										<td  class="searchhead_text"><b>Number</b></td>
										<td  class="searchhead_text"><b>Amount</b></td>
										<td  class="searchhead_text"><b>Bank</b></td>
										<td class="searchhead_text"><b>Date</b></td>
										<td class="searchhead_text"><b>Inst. Status</b></td>
										<td class="searchhead_text"><b>Receipt Status</b></td>
										<td class="searchhead_text"><b>Delete</b></td>
									</tr>
								</tbody>
							</table>
							</div>
							<input type="hidden" name="deleteFlag" id="deleteFlag" value="" />
							<div id='addRowDiv'>
							</div><h3>&nbsp;&nbsp;Add Rows:&nbsp;&nbsp;<a href="javascript:addOneRow(1);" title="Add One Row"><b>+</b></a></h3>
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
					<tr>
						<td colspan="2" align="right">
							<table>
							<tr>
								<td  align="right"><div id = "saveDiv" ></div>
								<td  align="right"><input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="return validateForm(this.form,'Add');return false;" tabindex="33"/>&nbsp;<input type="image" name="imgSubmit1" src="<?php echo IMG_HTTP_PATH;?>/save_print.gif" onclick="return validateForm(this.form,'Print');return false;" tabindex="33"/> </td>
							</tr>
							</table>
						</td>
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
	<?php floatingDiv_Start('getStudentDetail','Search Student'); ?>
	<form name="listForm" action="" method="post" id="listForm">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr>
		<td height="10"></td>
	</tr>
	<tr>	
		<td class="contenttab_internal_rows"><nobr><B>Class</B><?php echo REQUIRED_FIELD?></nobr></td>
		<td class="contenttab_internal_rows"><B>:</B></td>
		<td><select size="1" name="studentClass" id="studentClass" class="inputbox1" onChange="clearData()">
		<option value="">Select</option>
		<?php
		  require_once(BL_PATH.'/HtmlFunctions.inc.php');
		  echo HtmlFunctions::getInstance()->getClassData();
		?>
		</select>
		</td>
		<td class="contenttab_internal_rows"><nobr><B>Student Name</B></nobr></td>
		<td class="contenttab_internal_rows"><B>:</B></td>
		<td><input type="text" id="studentName" name="studentName" class="inputbox"/></td>
		<td class="contenttab_internal_rows"><nobr><B><input type="checkbox" id="deletedStudent" name="deletedStudent"/>&nbsp;Deleted Student</B></nobr></td>
		<td>&nbsp;</td>
		<td align="center" style="padding-right:20px"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="getData();return false"/></td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td valign="top" colspan="9"><div id="scroll2" style="overflow:auto; height:310px; vertical-align:top;"><div id="studentResult" style="width:97%; vertical-align:top;"></div>
		</div></td>
	</tr> 
	</table>
	</form>
	<?php floatingDiv_End(); ?>

<select name="paymentType" id="paymentType" style="display:none;">
  <?php
	  require_once(BL_PATH.'/HtmlFunctions.inc.php');
	  echo HtmlFunctions::getInstance()->getFeePaymentMode("2");
  ?>
</select>

<select size="1" name="paymentStatus" id="paymentStatus" style="display:none;">
  <?php
	  require_once(BL_PATH.'/HtmlFunctions.inc.php');
	  echo HtmlFunctions::getInstance()->getFeeReceiptPaymentStatus("3");
  ?>
</select>

<select size="1" name="receiptStatus" id="receiptStatus" style="display:none;">
  <?php
	  require_once(BL_PATH.'/HtmlFunctions.inc.php');
	  echo HtmlFunctions::getInstance()->getFeeReceiptStatus("2");
  ?>
</select>

<select name="issuingBank" id="issuingBank" style="display:none;">
  <option value="">Select</option>
  <?php
	  require_once(BL_PATH.'/HtmlFunctions.inc.php');
	  echo HtmlFunctions::getInstance()->getBankData();
  ?>
</select>
<?php 
// $History: studentFeesContents.php $
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 10-03-29   Time: 10:33a
//Updated in $/LeapCC/Templates/Student
//removed reload of page after submit 
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 10-03-26   Time: 1:17p
//Updated in $/LeapCC/Templates/Student
//updated with all the fees enhancements
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 10-03-08   Time: 11:21a
//Updated in $/LeapCC/Templates/Student
//issue received during CIET implementation
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 09-11-25   Time: 4:17p
//Updated in $/LeapCC/Templates/Student
//Fixed 2126,2127,2128,2129,2130,
//2131,2132,2133,2134,2135
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 09-11-25   Time: 1:43p
//Updated in $/LeapCC/Templates/Student
//fixed bug no 2126,2127,2128,2129,2130,2131,2132,2133,2134,2135
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 09-11-21   Time: 3:52p
//Updated in $/LeapCC/Templates/Student
//Added Student search,receipt no manual and fee type functionality in
//collect fees
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 09-11-11   Time: 6:27p
//Updated in $/LeapCC/Templates/Student
//Added issue and payable bank id as per new requirement
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 09-09-02   Time: 3:03p
//Updated in $/LeapCC/Templates/Student
//Updated with config parameter which has been removed from
//common.inc.php
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
//*****************  Version 14  *****************
//User: Rajeev       Date: 9/17/08    Time: 11:36a
//Updated in $/Leap/Source/Templates/Student
//updated the formatting and added comments
//
//*****************  Version 13  *****************
//User: Rajeev       Date: 9/10/08    Time: 12:17p
//Updated in $/Leap/Source/Templates/Student
//updated tab order
//
//*****************  Version 12  *****************
//User: Rajeev       Date: 9/08/08    Time: 3:36p
//Updated in $/Leap/Source/Templates/Student
//updated formatting
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 9/04/08    Time: 6:30p
//Updated in $/Leap/Source/Templates/Student
//updated fixes for student fees receipt
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 9/02/08    Time: 7:35p
//Updated in $/Leap/Source/Templates/Student
//updated with html validator
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 9/01/08    Time: 4:02p
//Updated in $/Leap/Source/Templates/Student
//updated with default display of student attendance, student print
//report
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 8/27/08    Time: 2:13p
//Updated in $/Leap/Source/Templates/Student
//updated fee module
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 8/18/08    Time: 7:35p
//Updated in $/Leap/Source/Templates/Student
//updated formatting
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 8/13/08    Time: 6:45p
//Updated in $/Leap/Source/Templates/Student
//updated fee receipt modifications
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 7/31/08    Time: 4:32p
//Updated in $/Leap/Source/Templates/Student
//updated the format of file
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 7/24/08    Time: 6:38p
//Updated in $/Leap/Source/Templates/Student
//updated the validations
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/24/08    Time: 12:37p
//Updated in $/Leap/Source/Templates/Student
//completed the ree receipt functionality
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/19/08    Time: 7:06p
//Updated in $/Leap/Source/Templates/Student
//intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/17/08    Time: 6:54p
//Created in $/Leap/Source/Templates/Student
//intial checkin
?>