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
           <?php  require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
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
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Student Fine: </td>
						
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
                                    <td class="contenttab_internal_rows" width="28%"><nobr><B>Last Receipt Detail</B></nobr></td>
                                    <td class="contenttab_internal_rows"><B>&nbsp;&nbsp;:</B></td>
                                    <td class="contenttab_internal_rows" colspan="10">
                                      <label id='lastEntry'></label>
                                    </td>
                                </tr>    
                                <tr><td height="12px"></td></tr>
                                <tr>
									<td class="contenttab_internal_rows" width="28%"><nobr><B>Receipt Date</B></nobr></td>
									<td class="contenttab_internal_rows"><B>&nbsp;&nbsp;:</B></td>
									<td >
									<?php
									   $todayDate = date("Y-m-d");	
									   require_once(BL_PATH.'/HtmlFunctions.inc.php');
									   echo HtmlFunctions::getInstance()->datePicker('receiptDate',$todayDate);
									?></td>
								</tr>
								<tr>	
									<td class="contenttab_internal_rows" width="28%"><nobr><B>Student Roll No</B><?php echo REQUIRED_FIELD?></nobr></td>
									<td class="contenttab_internal_rows"><B>&nbsp;&nbsp;:</B></td>
									<td width="72%"><input type="text" id="studentRoll" name="studentRoll" class="inputbox" onChange="populateValues()" tabindex="1" onkeydown="return sendKeys('studentRoll',event);"/>
									<input type="hidden" name="studentId" id="studentId">
									<input type="hidden" name="studentClass" id="studentClass">
									<input type="hidden" name="receiptNo" id="receiptNo">
									<input type="hidden" name="totalCheck" id="totalCheck">
									
									</td>
								</tr>
								<tr>
									<td class="contenttab_internal_rows" ><nobr><B><font color='black'>Paid At</font></B><?php echo REQUIRED_FIELD?> </td>
									<td class="contenttab_internal_rows"><B>&nbsp;&nbsp;:</B></td>
									<td class="contenttab_internal_rows"><nobr>
									<select style="width:185px" id='paidAt' name='paidAt' onChange="getReceiptOn(); return false;">
									<option value="" selected="selected">Select</option>
								    <option value="2">On Accounts Desk</option>
								    <option value="1">Bank</option>
									</select>
									</td>
								</tr>

		                        <tr id='trRow2' style="display:none">
									<td class="contenttab_internal_rows" style='padding-top:7px' width='13%'><nobr><B>Bank Scroll No.</B><?php echo REQUIRED_FIELD?></nobr></td>
									<td class="contenttab_internal_rows" style='padding-top:7px' width="1%"><B>&nbsp;&nbsp;:</B></td>
									<td class="contenttab_internal_rows" style='padding-top:7px'>
									<input type='text' id='bankScrollNo' name='bankScrollNo' style='width:180px;text-align:right;'>
									</td>
								</tr>
								</table>
								</fieldset>
								</td>
							</tr>
							<tr>
								<td valign="top">
								<fieldset class="fieldset">
								<legend>Student Details</legend>
								<table width="100%" border="0" cellspacing="1" cellpadding="3" >
								<tr style="display:none">	
									<td class="contenttab_internal_rows" width="28%"><nobr><B>Receipt Number</B></nobr></td>
									<td class="contenttab_internal_rows" width="1%"><B>:</B></td>
									<td width="65%"><span id="myReceipt">--</span><input type="hidden" id="receiptNumber" name="receiptNumber" class="inputbox" value="<?php echo FEE_RECEIPT_PREFIX?>" READONLY/>
									</td>
								</tr>
                                <tr>    
                                    <td class="contenttab_internal_rows"><nobr><B>Class Name</B></nobr></td>
                                    <td class="contenttab_internal_rows"><B>:</B></td>
                                    <td width="24%"><span id="myClass">--</span></td>
                                </tr>
                               <tr>	
									<td class="contenttab_internal_rows"><nobr><B>Student Name</B></nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td><span id="myFirst">--</span></td>
								</tr>
								<tr>    
                                    <td class="contenttab_internal_rows" width="28%"><nobr><B>Father's Name</B></nobr></td>
                                    <td class="contenttab_internal_rows" width="1%"><B>:</B></td>
                                    <td width="65%"><span id="fatherName">--</span></td>
                                </tr> 
								</table>
								</fieldset>
								</td>
							</tr>
							<tr>
								<td valign="top">
								<fieldset class="fieldset">
								<legend>Remarks</legend>
								<table width="100%" border="0" cellspacing="1" cellpadding="3" >
								<tr>	
									<td class="contenttab_internal_rows" width="30%" valign="top"><nobr><B>Remarks</B></nobr></td>
									<td class="contenttab_internal_rows" valign="top"><B>:</B></td>
									<td width="70%"><textarea type="text" id="printRemarks" name="printRemarks" class="inputbox1" rows="2" cols="30" tabindex="30"/></textarea></td>
								</tr>
								 <tr style="display:none">	
									<td class="contenttab_internal_rows" width="28%"><nobr><B>Received From</B></nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td><input type="text" id="receivedFrom" name="receivedFrom" class="inputbox1" size="33"  tabindex="32" READONLY/>
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
							<legend>Fine Details</legend>
							<table border="0" cellspacing="0" cellpadding="3" width="100%">
							 <tr>
							   <td valign="top" width="100%">
								<div id="scroll" class="scroll" style='height:20px'>
								  <div id="results">
								      <table border="0" cellspacing="2px" cellpadding="2px" width="100%">
									     <tr class="rowheading">
									       <td rowspan="2" valign="middle" width="3%"><B>#</B></td>
									       <td rowspan="2" valign="middle" align="center" width="12%"><B>Fine Date</B></td>
									       <td rowspan="2" valign="middle" align="left" width="20%"><B>Fine</B></td>
                                           <td rowspan="2" valign="middle" align="right" width="15%"><B>Amount</B></td>
                                           <td rowspan="2" valign="middle" align="left" width="25%"><B>Reason</B></td>
                                           <td colspan="2" valign="middle" align="center" width="20%"><B>Previous Paid Fine</B></td>
									     </tr>
                                         <tr class="rowheading">     
                                           <td valign="middle" align="left" nowrap width="15%"><B>Receipt No.</B></td>
                                           <td valign="middle" align="right" nowrap width="15%"><B>Paid Amount</B></td>
                                         </tr>   
									     <tr class="row0">
									       <td valign="middle" colspan="7" align="center">No detail found</td>
									     </tr>
								       </table>
								     </div>
								   </div>
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
					<tr style="display:none">
						<td valign="top" colspan="2">
							<fieldset class="fieldset">
								<legend class="contenttab_internal_rows1"><font color='black'>Payment Mode</font></legend>
								<table border=0 cellspacing=0 cellpadding=0 width="75%">
								<tr>
									<td nowrap="nowrap">
										<input name="fineType" id="fineType1" value="both" onclick="paymentMode()" checked="checked" type="radio">Both&nbsp;&nbsp;&nbsp;
		        						<input name="fineType" id="fineType2" value="cash" onclick="paymentMode()" type="radio">By Cash&nbsp;&nbsp;&nbsp;
		        						<input name="fineType" id="fineType3" value="cheque" onclick="paymentMode()" type="radio">By Cheque/Draft
		    						</td>
		    					</tr>
		    					</table>	    						
	    					</fieldset>
	    				</td>
					</tr>
					<tr>
						<td height="5"></td>
					</tr>
					<tr >
						<td valign="top" colspan=2>
							<fieldset class="fieldset">
								<legend class="contenttab_internal_rows1"><font color='black'>Cash / Cheque / Draft Payment Detail</font></legend>
									<table width="100%" border="0" cellspacing="1" cellpadding="3" >
										<tr id='collectionData1'>
											<td>
												<table border=0 cellspacing=0 cellpadding=0 width="75%">
													<tr>
														<td class="contenttab_internal_rows" style='padding-top:7px;' width='12%'><nobr><B>Total Payable Amount</B></nobr></td>
														<td class="contenttab_internal_rows" width="1%" style='padding-top:7px;'><B>:</B></td>
														<td  class="contenttab_internal_rows" style='padding-top:7px;'>
														<span id='payableSpan' class="contenttab_internal_rows">0.00</span>
														<input type="hidden" id="payableAmount" name="payableAmount" maxlength="8" />
														<input type="hidden" id="netPayable" name="netPayable"    maxlength="8" /></td>
													</tr>
													<tr id='collectionData3'>
														<td class="contenttab_internal_rows" style='padding-top:7px;' width='12%'><nobr><B>Cash Amount</B></nobr></td>
														<td class="contenttab_internal_rows" width="1%" style='padding-top:7px;'><B>:</B></td>
														<td  class="contenttab_internal_rows" style='padding-top:7px;'>
														<input type="text" id="cashAmount" name="cashAmount"  onblur="checkValue(this.value,this.id);" style='width:100px;text-align:right;'  maxlength="8" /></td>
													</tr>
												</table>
											</td>
										</tr>
									            
									   
									    <tr><td height='5px'></td></tr>
										<tr id='collectionData2'>
											<td class="contenttab_internal_rows" valign="top">
												<div id='anyidT'>
													<table width="100%" border="0" cellspacing="1" cellpadding="0" id="anyid">
														<tbody id="anyidBody">
															<tr class="rowheading">
																<td width="3%" class="searchhead_text" style="padding: 0px 0px 0px 0px;"><b>#</b></td>
																<td class="searchhead_text" width='20%' style="padding: 0px 0px 0px 10px;"><b>Type</b></td>
																<td class="searchhead_text" width='20%' align='right' style="padding: 0px 10px 0px 0px;"><b>Number</b></td>
																<td class="searchhead_text" width='20%' align='right' style="padding: 0px 10px 0px 0px;"><b>Amount</b></td>
																<td class="searchhead_text" width='15%' style="padding: 0px 0px 0px 10px;"><b>Bank</b></td>
																<td class="searchhead_text" align="center" width='15%'><b>Date</b></td>
																<td class="searchhead_text" width='8%' align='center'><b>Delete</b></td>
															</tr>
														</tbody>
													</table>
												</div>
												<span id='addMore'>
													<input type="hidden" name="deleteFlag" id="deleteFlag" value="" />
													<div id='addRowDiv'>
													</div><h3>&nbsp;&nbsp;Add Rows:&nbsp;&nbsp;<a href="javascript:addOneRow(1);" title="Add One Row"><b>+</b></a></h3>
												</span>
											</td>
										</tr>
									</table>
								</fieldset>
							</td>
						</tr>

						<tr>
							<td colspan="2" align="right">
								<table>
								<tr>
									<td  align="right"><div id = "saveDiv" ></div>
									<td  align="right">
										<!--<input type="image" name="imgPrintSubmit1" src="<?php echo IMG_HTTP_PATH;?>/show_list.gif" onclick="studentFineReceipt(this.form);return false;" tabindex="33"/>-->
										<input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="return validateForm(this.form,'Add');return false;" tabindex="33"/>&nbsp;
										<input type="image" name="imgPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/save_print.gif" onclick="return validateForm(this.form,'AddandPrint');return printReport(); return false;" tabindex="33"/>
									</td>
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
<?php floatingDiv_Start('ViewReason','Reason Description'); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<form name="viewReason" action="" method="post"> 
<tr>
    <td height="5px"></td></tr>
<tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Reason</b></td>
</tr>

<tr>
	<td width="100%"  align="left" style="padding-left:10px">
	<br />
	<div id="innerReason" style="overflow:auto; width:380px;" ></div><br>
	</td>
</tr>

<tr>
    <td height="5px"></td>
</tr>

   </form>
</table>

<?php floatingDiv_End(); ?>

<select name="paymentType" id="paymentType" style="display:none;">
  <?php
	  require_once(BL_PATH.'/HtmlFunctions.inc.php');
	  echo HtmlFunctions::getInstance()->getFeePaymentMode("3","1");
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
// $History: studentFineContents.php $
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/17/09    Time: 2:19p
//Updated in $/LeapCC/Templates/Fine
//Gurkeerat: resolved issue 1019
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 8/10/09    Time: 3:04p
//Updated in $/LeapCC/Templates/Fine
//Fixed bug no 745
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/29/09    Time: 4:53p
//Updated in $/LeapCC/Templates/Fine
//fixed bugs 703,704,705,706,707,708,709,733,742,743,744,745,750,
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/07/09    Time: 4:03p
//Updated in $/LeapCC/Templates/Fine
//Updated collect fine with fine by and reason detail div pop up in
//collect fine
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/06/09    Time: 6:36p
//Created in $/LeapCC/Templates/Fine
//intial checkin
?>
