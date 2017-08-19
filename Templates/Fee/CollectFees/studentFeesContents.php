<?php
//-------------------------------------------------------
// Purpose: to design add fees receipt.
// Author : Nishu Bindal
// Created on : (7.Feb.2012 )
// Copyright 2012-2013: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------

$paidStyle = "style='font-family: Verdana,Arial,Helvetica,sans-serif; font-size:12px; color: red;'";
require_once(BL_PATH.'/helpMessage.inc.php');
global $sessionHandler;  
?>

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
             <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");  ?>
        </td>
    </tr>
    <tr>
        <td valign="top">
		 <form action="" method="POST" name="feeForm" id="feeForm" onsubmit="return false;"> 
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
			 <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
			 <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Collect Fees:</td>
						<td class="content_title">
                            <table border="0" cellspacing="0" cellpadding="0" align="right" >
                              <tr>
                                <td valign='top' style='padding-left:10px;color:black'>
                                   <span style="font-size:14px;color:white"><strong>Note&nbsp; :</strong></span>
                                   </td>
                                   <td valign='top' style='padding-left:10px;color:black'><span style="font-size:12px;color:white">a) Miscellaneous Head Name's are in Blue Color.</span>
                                </td>
                              </tr>
                            </table>          
                        </td>
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
								<legend class="contenttab_internal_rows1">Select Student</legend>
								<table width="100%" border="0" cellspacing="2px" cellpadding="0px" valign="top">
				
						 
								<tr>
								    <td class="contenttab_internal_rows" colspan="3">
								       <nobr><B>Last Receipt No. and Date&nbsp;:&nbsp;</B>
								       <label id='lastEntry'></label>
								       </nobr>
								    </td>
								</tr>
								 <tr><td height="5px"></td></tr>

				<tr>
					<td class="contenttab_internal_rows"><nobr><B><font color='black'>
                                    	Paid At</font></B><?php echo REQUIRED_FIELD?></nobr>
                                    </td>
                                    <td class="contenttab_internal_rows"><B>:</B></td>
                                    <td class="contenttab_internal_rows" colspan="4"><nobr>
				     <input type="radio" name="paid" id="paid1" value="0" checked onchange="getPaidAtDetails();">None
				      <input type="radio" name="paid" id="paid2" value="2" onchange="getPaidAtDetails();">On Account Desk
				      <input type="radio" name="paid" value="1" id="paid3" onchange="getPaidAtDetails();">Bank</B>
							       </nobr>
							    </td>
							</tr>
				 <tr><td height="5px"></td></tr>	 
                                <tr>    
                                    <td class="contenttab_internal_rows"><nobr><B><font color='black'>
                                    	Reg. No. / Roll No.</font></B><?php echo REQUIRED_FIELD?></nobr>
                                    </td>
                                    <td class="contenttab_internal_rows"><B>:</B></td>
                                    <td class="contenttab_internal_rows" colspan="4"><nobr>
                                        <input type="text" id="rollNoRegNo" name="rollNoRegNo" class="inputbox1" value="" style="width:165px" onkeypress="return sendKeys('feeType',event);" onchange="resetForm('receipt');getStudentClasses();"/>&nbsp;
                                        <a href='javascript:void(0)' onClick="showStudentDetails('getStudentDetail',850,100);return false;" ><u>Search Student</u></a>
                                    </td>
                                </tr>
                                  <tr>    
                                    <td class="contenttab_internal_rows"><nobr><B><font color='black'>Class</font></B><?php echo REQUIRED_FIELD?> </td>
                                    <td class="contenttab_internal_rows"><B>:</B></td>
                                    <td class="contenttab_internal_rows" colspan="4"><nobr>
                                       <select style="width:250px" id='classId' name='classId' onchange="resetForm('class');">
                                       <option value="">Select</option>
                                   	</nobr>
                                    </select>
                                    </td>
                                </tr>
                                 <tr>    
                                    <td class="contenttab_internal_rows"><nobr><B><font color='black'>Pay Fee Of</font></B><?php echo REQUIRED_FIELD?> </td>
                                    <td class="contenthttp://localhost/LeapCC/Interface/dummy.phptab_internal_rows"><B>:</B></td>
                                    <td class="contenttab_internal_rows"><nobr>
                                       <select style="width:120px" id='feePaymentMode' name='feePaymentMode' onchange="getStudentDetails();">
                                            <option value="">Select</option>
                                            <option value="3">Hostel</option>
                                            <option value="2">Transport</option>
                                            <option value="1">Academic</option>
                                            <option value="4">All</option>
                                    </select>
                                    </td>
                                </tr>
                                <tr>    
                                    <td class="contenttab_internal_rows"><nobr><B><font color='black'>Fee Cycle</font></B></nobr></td>
                                    <td class="contenttab_internal_rows"><B>:</B></td>
                                    <td class="contenttab_internal_rows" colspan="10"><nobr>
				     <span id='feeCycle'>---</span>
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
								<legend class="contenttab_internal_rows1"><font color='black'>Personal Details</font></legend>
								<table width="100%" border="0" cellspacing="2px" cellpadding="0" >
								
								<tr>	
									<td width="5%" style='padding-top:8px;' class="contenttab_internal_rows"><nobr><B>Name</B></nobr></td>
									<td width="1%" style='padding-top:8px;' class="contenttab_internal_rows"><B>:</B></td>
									<td width="23%" style='padding-top:8px;'><span id="studentName">---</span>
									</td>
									<td width="5%" style='padding-top:8px;' class="contenttab_internal_rows"><nobr><B>Father's Name</B></nobr></td>
									<td width="1%" style='padding-top:8px;' class="contenttab_internal_rows"><B>:</B></td>
									<td width="23%" style='padding-top:8px;'><span id="fatherName">---</span>
                                        			</td>
								</tr>
								<tr>    
								    <td class="contenttab_internal_rows" style='padding-top:8px;'><nobr><B>Reg. No.</B></nobr></td>
								    <td class="contenttab_internal_rows" style='padding-top:8px;'><B>:</B></td>
								    <td style='padding-top:8px;'></nobr><span id="regNo">---</span></nobr></select>
								    </td>
								    <td class="contenttab_internal_rows" style='padding-top:8px;'><nobr><B>Roll No.</B></nobr></td>
								    <td class="contenttab_internal_rows" style='padding-top:8px;'><B>:</B></td>
								    <td style='padding-top:8px;'></nobr><span id="rollNo">---</span></nobr></select>
								    </td>
								</tr>
								<tr>	
									<td class="contenttab_internal_rows" width="5%" style='padding-top:8px;'><nobr><B>Active Class</B></nobr></td>
									<td class="contenttab_internal_rows" style='padding-top:8px;'><B>:</B></td>
									<td width="66%" colspan=4 style='padding-top:8px;'><span id="className">---</span></td>
									  <input type="hidden" name="studentId" id="studentId">
								         <input type="hidden" name="feeClassId" id="feeClassId">
								         <input type="hidden" name="receiptId" id="receiptId">
								         <input type="hidden" name="hostelId" id="hostelId">
								         <input type="hidden" name="roomId" id="roomId">
								         <input type="hidden" name="busRouteId" id="busRouteId">
								         <input type="hidden" name="busStopId" id="busStopId">
								         <input type="hidden" name="feeConcession" id="feeConcession">
								          <input type="hidden" name="hiddenFeeCycleId" id="hiddenFeeCycleId">
								          <input type="hidden" name="hostelSecurityStatus" id="hostelSecurityStatus">
								</tr>
								
								
								<tr>
									<td class="contenttab_internal_rows" style='padding-top:8px;'> <b>Bank Name.</b></td>
									<td class="contenttab_internal_rows" style='padding-top:8px;'><B>:</B></td>
									<td style='padding-top:8px;'><span id='bankName'>--- </span></td>
								
									<td class="contenttab_internal_rows" style='padding-top:8px;'> <b>Bank Acc No.</b></td>
									<td class="contenttab_internal_rows" style='padding-top:8px;'><B>:</B></td>
									<td style='padding-top:8px;'><span id='bankAccNo'>--- </span></td>
								</tr>
								
								</table>
								</fieldset>
								</td>
							</tr>
									</table>
								</span>
								</span>
								</td>
					</tr>
					
				</table>
				</fieldset>
					</td>
					</tr> 
          </table>
        </td>
    </tr>
    <tr>
    	<td valign="top">
    				<table width=100% cellspacing=0 cellpadding=0>
    					<tr>
    						<td width='35%' valign="top">
    						
							<table  width='100%' cellspaing=0 cellpadding=0 border=0>
								<tr>
									<td>
										<fieldset class="fieldset">
										<legend class="contenttab_internal_rows1"><font color='black'>Fee Collection</font></legend>
											<div style='height:210px'>
											<table width='100%' cellspacing=0 cellpadding=0 border=0>
	<tr id='trPaid' style="display:none">
		<td class="contenttab_internal_rows" ><nobr><B><font color='black'>Paid At</font></B><?php echo REQUIRED_FIELD?> </td>
		<td class="contenttab_internal_rows"  ><B>:</B></td>
		<td class="contenttab_internal_rows" ><nobr>
		    <select style="width:150px" id='paidAt' name='paidAt'  >
		        <option value="" selected="selected">Select</option>
                <option value="2">On Accounts Desk</option>
                 <option value="1">Bank</option>
		    </select>
		</td>
	</tr>
	<tr id='trPaid1' >
		<td class="contenttab_internal_rows" <?php echo $paidStyle; ?>  width='13%'><nobr><B>Paid At</td>
		<td class="contenttab_internal_rows" <?php echo $paidStyle; ?>  width="1%"><B>:</B></td>
		<td class="contenttab_internal_rows" <?php echo $paidStyle; ?>  >
		  <nobr><B><label id='paidStatus'></label></B></nobr>
        </td>
	</tr>
                                                 <tr id='trRow2' style="display:none">
													<td class="contenttab_internal_rows" style='padding-top:7px' width='13%'><nobr><B>   <label id='paidEntry'></label></B><?php echo REQUIRED_FIELD?></nobr></td>
													<td class="contenttab_internal_rows" style='padding-top:7px' width="1%"><B>:</B></td>
													<td class="contenttab_internal_rows" style='padding-top:7px'>
													<input type='text' id='bankScrollNo' name='bankScrollNo' style='width:150px;text-align:right;'>
													</td>
												</tr>
                                                <tr id='trRow1'>
                                                    <td class="contenttab_internal_rows" style='padding-top:7px' width='13%'><nobr><B>Receipt Date</B><?php echo REQUIRED_FIELD?></nobr></td>
                                                    <td class="contenttab_internal_rows" style='padding-top:7px' width="1%"><B>:</B></td>
                                                    <td class="contenttab_internal_rows" style='padding-top:7px'>
                                                     <span id='lblShowDate'><?php echo date('d-M-y'); ?></span>
                                                    </td>
                                                </td>
                                                <tr id='trRow3' style="display:none">
													<td class="contenttab_internal_rows" style='padding-top:7px' width="12%"><nobr><B>Receipt Date</B><?php echo REQUIRED_FIELD?></nobr></td>
													<td class="contenttab_internal_rows" width="1%" style='padding-top:7px'><B>:</B></td>
													<td style='padding-top:7px'>
													<?php
													   $todayDate = date("Y-m-d");	
													   require_once(BL_PATH.'/HtmlFunctions.inc.php');
													   echo HtmlFunctions::getInstance()->datePicker('receiptDate',$studentFeeReceiptdate);
													?></td>
												</tr>
												<tr>
													<td class="contenttab_internal_rows" style='padding-top:7px'><nobr><B>Installment No.</B><?php echo REQUIRED_FIELD?></nobr>
													<td class="contenttab_internal_rows" width="1%" style='padding-top:7px'><B>:</B></td>
													<td  class="contenttab_internal_rows" style='padding-top:7px'>
													<input type="text" onblur='isCorrectValue(this.value,this.id,"Installment No Should be Integer.");' id="installmentNo" name="installmentNo"   style='width:150px;text-align:right;'  maxlength="8" /></td>
												</tr>
												<!-- Will Be used later On <tr>
										  			<td class="contenttab_internal_rows"  width='13%' style="padding-top:7px;"><nobr><B>Academic Fee Paid</B></nobr></td>
													<td class="contenttab_internal_rows" width="1%" style="padding-top:7px;"><B>:</B></td>
													<td class="contenttab_internal_rows" style="padding-top:7px;">
													<input type='text' id='academicFeePaid' name='academicFeePaid' style='width:100px;text-align:right;' maxlength="8">
													</td>
												</tr>
												<tr>
													<td class="contenttab_internal_rows" style="padding-top:7px;" width='13%'><nobr><B>Hostel Fee Paid</B></nobr></td>
													<td class="contenttab_internal_rows" style="padding-top:7px;" width="1%"><B>:</B></td>
													<td class="contenttab_internal_rows" style="padding-top:7px;">
													<input type='text' id='hostelFeePaid' name='hostelFeePaid' style='width:100px;text-align:right;' maxlength="8">
													</td>
												</tr>
												<tr>
													<td class="contenttab_internal_rows" style="padding-top:7px;" width='13%'><nobr><B>Transport Fee Paid</B></nobr></td>
													<td class="contenttab_internal_rows" style="padding-top:7px;" width="1%"><B>:</B></td>
													<td class="contenttab_internal_rows" style="padding-top:7px;">
													<input type='text' id='transportFeePaid' name='transportFeePaid' style='width:100px;text-align:right;' maxlength="8">
													</td>
										  		</tr> --></div>
											</table>
										</fieldset>
									</td>
								</tr>
							</table>
							
						</td>
						
						<td width='65%' valign='top'>
							<table width='100%' cellspaing=0 cellpadding=0>
								<tr>
									<td>
										<fieldset class="fieldset">
										<legend class="contenttab_internal_rows1"><font color='black'>Fee Detail</font></legend>
											<div style='height:210px;overflow:auto;'>
											<table width='100%' cellspaing=0 cellpadding=0>
											<tr>
											  <td width='67%'>
											  	<span id="scroll1" class="scroll">
												<span id="feeDiv">
													<table border="0" cellspacing="0" cellpadding="0" width="100%">
													    <tr class="rowheading">

														    <td align="left" width="2%"><B>#</B></td>
														    <td align="left" width="20%"><B>Fee Head</B></td>
														    <td align="right" width="10%" style='padding-right:5px;'><B><nobr>Amount</nobr></B></td>
														     <td align="right" width="10%" style='padding-right:5px;'><B><nobr>Amount Paid</nobr></B></td>
													    </tr>
													    <tr class="row0">
														    <td valign="middle" colspan="5" align="center">No detail found</td>
													    </tr>
													    
													  
													</table>
													
												</span>
												
												<span id="feeDiv1">
												</span>
												</span>   
											  
											   </td> 
											   <td style='padding-left:7px;' width='33%' valign="top">
											   <span id="paidFeeData" ></span>	
											   </td>      
											</tr>
										
											</div>				
										</fieldset>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
	</td>
    	
    </tr>
    <tr>
				<td valign="top" colspan=2>
				<fieldset class="fieldset">
				<legend class="contenttab_internal_rows1"><font color='black'>Cash / Cheque / Draft Payment Detail</font></legend>
				<table width="100%" border="0" cellspacing="1" cellpadding="3" >
				 <!--  <tr id='alreadyPaidData' style='display:none;'>
				   	<td colspan = 10>
				   		<table width="100%" border="0" cellspacing="1" cellpadding="0">
								
									<tr class="rowheading">
										<td width="3%" class="searchhead_text" style="padding: 0px 0px 0px 0px;"><b>#</b></td>
										<td class="searchhead_text" width='15%' style="padding: 0px 0px 0px 10px;"><b>Type</b></td>
										<td class="searchhead_text" width='10%' align='right' style="padding: 0px 10px 0px 0px;"><b>Number</b></td>
										<td class="searchhead_text" width='10%' align='right' style="padding: 0px 10px 0px 0px;"><b>Amount</b></td>
										<td class="searchhead_text" width='15%' style="padding: 0px 0px 0px 10px;"><b>Bank</b></td>
										<td class="searchhead_text" align="center" width='13%'><b>Date</b></td>
										<td class="searchhead_text" width='10%' align="right" style="padding: 0px 0px 0px 10px;"><b>Receipt No.</b></td>
										<td class="searchhead_text" width='13%' align='center' style="padding: 0px 0px 0px 10px;"><b>Receipt Date</b></td>
										<td class="searchhead_text" width='15%' align="right" style="padding: 0px 0px 0px 10px;"><b>Installment No.</b></td>
									</tr>
									<tr>
									<td colspan=10>
										<span id='paymentData'></span>
									</td>
									</tr>
								
							</table>
				   	</td>
				   </tr>
				   <tr id='collectionData'>
				   	<td align='right'><span id='payInstallment'></span></td>
				   </tr> -->
				    <tr id='collectionData1'>
                          	<td>
                          		<table border=0 cellspacing=0 cellpadding=0 width="75%">
                          		 
                          		<tr>
						<td class="contenttab_internal_rows" style='padding-top:7px;' width='12%'><nobr><B>Total Payable Amount</B></nobr>
						<td class="contenttab_internal_rows" width="1%" style='padding-top:7px;'><B>:</B></td>
						<td  class="contenttab_internal_rows" style='padding-top:7px;'>
						<span id='payableSpan' class="contenttab_internal_rows">0.00</span>
						<input type="hidden" id="payableAmount" name="payableAmount"    maxlength="8" /><input type="hidden" id="netPayable" name="netPayable"    maxlength="8" /></td>
					</tr>
					<tr>
						<td class="contenttab_internal_rows" style='padding-top:7px;' width='12%'><nobr><B>Cash Amount</B></nobr>
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
										<td width="3%" class="searchhead_text" style="padding: 0px 0px 0px 0px;"><b>Sr.</b></td>
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
<tr><td height='5px'></td></tr>
      
  
					</table>
					</td>
					</tr>
					<tr>
						<td colspan="2" align="right">
							<table>
							<tr>
								<td  align="right"><span id='hideSave'>
                                    <input type="image" name="submit1" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="return validateForm(this.form,'Add');return false;" tabindex="33"/>&nbsp; 
                                    <input type="image" name="submit2" src="<?php echo IMG_HTTP_PATH;?>/save_print.gif" onclick="return validateForm(this.form,'Print');return false;" tabindex="33"/>&nbsp; 
                                    <input type="image" name="submit2" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onclick="return validateForm(this.form,'OldPrint');return false;" tabindex="33"/>&nbsp; 
                                    </span>
                                    <span id='hideDelete' style='display:none'>
                                    	 <input type="image" name="submit2" src="<?php echo IMG_HTTP_PATH;?>/delete_big.gif" onclick="deleteReceipt();resetForm('all');return false;" tabindex="33"/>&nbsp;
                                    </span>              
                                    <!--<input type="image" name="submit2" src="<?php echo IMG_HTTP_PATH;?>/close_big.gif" onclick="resetForm('all');return false;" tabindex="33"/>&nbsp;-->
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
	  echo HtmlFunctions::getInstance()->getFeePaymentMode("2","1");
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
 <!-- Help Div Starts -->
<?php  floatingDiv_Start('divHelpInfo','Help','12','','','1'); ?>
<div id="helpInfoDiv" style="overflow:auto; WIDTH:390px; vertical-align:top;"> 
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
        <tr>
            <td height="5px"></td></tr>
        <tr>
        <tr>    
            <td width="89%">
                <div id="helpInfo" style="vertical-align:top;" ></div> 
            </td>
        </tr>
    </table>
</div>       
<?php floatingDiv_End(); ?> 
 <!-- Help Div Ends -->
 
 
<!--Start Transport Facility Information  Div-->
<?php floatingDiv_Start('divTransportFacility','Transport Facility Information','',''); ?>
<form name="transportFacilityForm" id="transportFacilityForm" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>    
      <td width="100%" align="left" valign="top">                          
        <div id="trTransportFacilityResults"></div>
      </td>
    </tr>
    <tr id="transportFacilitySave" style='display:none'>    
       <td width="100%" align="center" valign="top">
         <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateFacilty(this.form,1);return false;" />&nbsp; 
         <input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('divTransportFacility'); return false;" />
       </td>
    </tr>     
</table>
</form> 
<?php floatingDiv_End(); ?>  

<!--Start Hostel Facility Information  Div-->
<?php floatingDiv_Start('divHostelFacility','Hostel Facility Information','',''); ?>
<form name="hostelFacilityForm" id="hostelFacilityForm"  action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>    
        <td width="100%" align="left" valign="top">                          
          <div id="trHostelFacilityResults"></div> 
        </td>
    </tr>
    <tr id="hostelFacilitySave" style='display:none'>    
       <td width="100%" align="center" valign="top">
         <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateFacilty(this.form,2);return false;" />&nbsp; 
         <input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('divHostelFacility'); return false;" />
       </td>
    </tr>
</table>
</form> 
<?php floatingDiv_End(); ?>  


<!--Start Prev Dues Information  Div-->
<?php floatingDiv_Start('divPrevDues','Prev Dues Information','',''); ?>
<form name="prevDuesForm" id="prevDuesForm"  action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>    
        <td width="100%" align="left" valign="top">                          
          <div id="trPrevDuesResults"></div> 
        </td>
    </tr>
    <tr id="prevDuesSave" style='display:none'>    
       <td width="100%" align="center" valign="top">
         <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateFacilty(this.form,3);return false;" />&nbsp; 
         <input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('divPrevDues'); return false;" />
       </td>
    </tr>
</table>
</form> 
<?php floatingDiv_End(); ?>  
