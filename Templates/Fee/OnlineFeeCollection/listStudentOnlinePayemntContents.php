<?php 
//-------------------------------------------------------
//  This File contains html form for all Student Internal Reappear Contents
//
//
// Author :PArveen Sharma
// Created on : 13-Sep-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
   global $sessionHandler; 
    $StudentNameFee = $sessionHandler->getSessionVariable('StudentName');
    $RollNoFee = $sessionHandler->getSessionVariable('RollNo');
    $FatherNameFee = $sessionHandler->getSessionVariable('FatherName');
    $ClassNameFee = $sessionHandler->getSessionVariable('ClassName');
	$ClassIdFee = $sessionHandler->getSessionVariable('ClassId');
	$captionFine=$sessionHandler->getSessionVariable('LABEL_FINE');
	$captionHostel=$sessionHandler->getSessionVariable('LABEL_HOSTEL');
	$captionTransport=$sessionHandler->getSessionVariable('LABEL_TRANSPORT'); 
	$feeFavour=$sessionHandler->getSessionVariable('FEE_PAYMENT_TERMS');
	$feeFavour=substr($feeFavour,4);
	
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>   
        </td>
    </tr>
    <tr>
		<form action="" method="POST" name="feeForm" id="feeForm" onsubmit="return false;">
      <td valign="top">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
          <tr>
            <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
                    <td class="contenttab_border" height="20">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                        <tr>
                            <td class="content_title">
                              Fee Due Details: 
                              <span style="padding-left:120px;color:black;font-size:14px">
                                * Please Select on fee (Academic/Hostel/Transport/All) according to the Class for payment
                               </span>
                            </td>
                        </tr>
                        </table>
                    </td>
                 </tr>
                     <tr>
						<td colspan="3" >
						<table width="100%" border="0" cellspacing="0" cellpadding="0" style="height:400px;">
                         <tr>
                            <td class="contenttab_row" style="vertical-align:top;" width="35%" >
                              <div id="scroll2" style="overflow:auto; vertical-align:top;">
                               <div id="divFeeClassResult" style="vertical-align:top;">
								<!-- ===================================================================================================================================================== -->
                             
								<table>
									<tr>    
                                    <td class="contenttab_internal_rows"><nobr><B>Fee Class</B><?php echo REQUIRED_FIELD;
                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                                        echo HtmlFunctions::getInstance()->getHelpLink('Fee Class',HELP_FEE_CLASS);?></nobr>
                                    </td>
                                    <td class="contenttab_internal_rows"><B>:</B></td>
                                    <td>
                                        <select size="1" name="feeClassId" id="feeClassId" onChange="populateValues(-1); getFacilityList(); return false;" class="inputbox1" style="width:225px">
                                          <option value="">Select</option>
                                        </select>
                                        <input type="hidden" name="studentId" id="studentId">
                                        <input type="hidden" name="studentClass" id="studentClass">
                                        <input type="hidden" name="hostelFacility" id="hostelFacility">
                                        <input type="hidden" name="transportFacility" id="transportFacility">
                                        <input type="hidden" name="quotaId" id="quotaId"> 
                                        <input type="hidden" name="isLeet" id="isLeet"> 
                                        <input type="hidden" name="deleteStudent" id="deleteStudent">
                                        <input type="hidden" name="totalFeesHidden" id="totalFeesHidden">
                                        <input type="hidden" name="hostelRoomId" id="hostelRoomId">
                                        <input type="hidden" name="busStopId" id="busStopId">
                                        <input type="hidden" name="isConessionFormatId" id="isConessionFormatId">
                                    </td>
                                </tr>
								 <tr>	
									<td class="contenttab_internal_rows"><nobr><B>Fee Type</B><?php echo REQUIRED_FIELD;
                                    require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                                    echo HtmlFunctions::getInstance()->getHelpLink('Fee Type',HELP_FEE_TYPE);?></nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td><select style="width:225px" size="1" name="feeType" id="feeType" class="inputbox1" onChange="populateValues(-1); getFacilityList(); return sendKeys('studentRoll',event);">
									  <?php
										  require_once(BL_PATH.'/HtmlFunctions.inc.php');
										  echo HtmlFunctions::getInstance()->getFeeTypeData();
									  ?>
									</select>
									</td>
								</tr>
								<tr id='trFacilityIds' height='25px' style="display:none">    
                                    <td class="contenttab_internal_rows" width="28%"></td>
                                    <td class="contenttab_internal_rows"></td>
                                    <td width="72%">
                                      <span id='tdFacilityTransport' style="display:none">
                                        <!-- <input type="checkbox" id="transportFacilityChk" onclick="populateValues(-1);" name="transportFacilityChk" value="1">&nbsp;Transport Facility&nbsp;&nbsp;</nobr> -->
                                        <nobr><a href='javascript:void(0)' onClick="getFacility(1,'divTransportFacility',850,500);return false;" ><u>Transport Facility</u></a>&nbsp;&nbsp;</nobr>
                                      </span>
                                      <span id='tdFacilityHostel' style="display:none">
                                         <!-- <input type="checkbox" id="hostelFacilityChk" onclick="populateValues(-1);"  name="hotelFacilityChk"     value="1"><nobr>&nbsp;Hostel Facility</nobr>  -->
                                         <nobr><a href='javascript:void(0)' onClick="getFacility(2,'divHostelFacility',850,500);return false;" ><u>Hostel Facility</u></a>&nbsp;</nobr>
                                      </span>
                                    <td>
                                </tr> 
								<tr>
								<td colspan="3">
								<fieldset class="fieldset">
								<legend>Amount Paid Detail</legend>
								<table>
								<tr>							
										<td colspan="3" class="contenttab_internal_rows" width='5px'><nobr><B>Total Payable Amount&nbsp;:&nbsp;</B>
											<label id='lblNetAmount'>0.00</label>    
											<input type="hidden" id="netAmount" name="netAmount" class="inputbox2" size="14" value="0.00" READONLY/>
											<input type="hidden" id="netAmount1" name="netAmount1" class="inputbox2" size="14" value="0.00" READONLY/>
											</nobr>
										</td>
								</tr>
								<tr>
										<td class="contenttab_internal_rows" style="display:none" id='tdPrevDuesPaid1' width='5px'><nobr><B>Dues Amt Paid</b></nobr></td>
										<td class="contenttab_internal_rows" style="display:none"id='tdPrevDuesPaid2'><B>:</B></td>
										<td class="contenttab_internal_rows" style="display:none"id='tdPrevDuesPaid3'><nobr>
											<label id='lblDuesAmtPaid'></label>
											<input type="hidden" id="duesAmtPaid" name="duesAmtPaid"  class="inputbox2" style="width:80px" onblur="calculateFeePaid();" maxlength="8"  />
											</nobr>
										</td>
								</tr>
								<tr>
										<td class="contenttab_internal_rows"  style="display:none;padding-left:15px" id='tdFeeAmtPaid1' width='5px'><nobr><B>Fee Amt Paid</b></nobr></td>
                                    <td class="contenttab_internal_rows"  style="display:none" id='tdFeeAmtPaid2'><B>:</B></td>
                                    <td class="contenttab_internal_rows"  style="display:none" id='tdFeeAmtPaid3'><nobr>
                                        <input type="text" id="feeAmtPaid" name="feeAmtPaid"  class="inputbox2" style="width:80px" onblur="calculateFeePaid();" maxlength="8"  />
                                        </nobr>
                                    </td>  
                                </tr>
								<tr>
                                    <td class="contenttab_internal_rows"  style="display:none;padding-left:15px" id='tdTransportAmtPaid1' width='5px'><nobr><B>Transport Amt Paid</b></nobr></td>
                                    <td class="contenttab_internal_rows"  style="display:none" id='tdTransportAmtPaid2'><B>:</B></td>
                                    <td class="contenttab_internal_rows"  style="display:none" id='tdTransportAmtPaid3'><nobr>
                                        <input type="text" id="transportAmtPaid" name="transportAmtPaid"  class="inputbox2" style="width:80px" onblur="calculateFeePaid();" maxlength="8"  />
                                        </nobr>
                                    </td>
								</tr>
								<tr>
                                    <td class="contenttab_internal_rows"  style="display:none;padding-left:15px" id='tdHostelAmtPaid1' width='5px'><nobr><B>Hostel Amt Paid</b></nobr></td>
                                    <td class="contenttab_internal_rows"  style="display:none" id='tdHostelAmtPaid2'><B>:</B></td>
                                    <td class="contenttab_internal_rows"  style="display:none" id='tdHostelAmtPaid3'><nobr>
                                        <input type="text" id="hostelAmtPaid" name="hostelAmtPaid"  class="inputbox2" style="width:80px" onblur="calculateFeePaid();"  maxlength="8"  />
                                        </nobr>
                                    </td>
                                </tr>
								
								<tr>
									<td class="contenttab_internal_rows" id='tdFeeAmtPaid' width='5px'><nobr><B>Total Amount Paid</B></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td class="contenttab_internal_rows">
										<label id='lblAmountPaid'>0.00</label> 
										<input type="hidden" id="paidAmount" name="paidAmount" class="inputbox2" size="14" value="0.00"  onChange="receiveCashFee()" tabindex="11"/>
										</nobr>
									</td>
								</tr>
								</table>
								</fieldset >
								
								</td >
                           </tr>
						   <tr>
						   <td>
							<input type="image" src="<?php echo IMG_HTTP_PATH;?>/pay.gif"  onclick="getConfirm(); return false;">
						   </td>
						   </tr>
								
                        
                    </tr>
								</table>
								
<!-- ===================================================================================================================================================== -->
                                </div>                         
                              </div>  
                            </td>
                            <td class="contenttab_row" valign="top" width="65%" style="padding-left:10px;vertical-align:top;">  
						<!-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
						<table cellspacing="0" cellpadding="0" border="0" width="100%" id="feeDetail">
							<tr>
							<td valign="top">
							<fieldset class="fieldset">
							<legend>Fees Detail</legend>
                           <span style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 9px; color: blue; display:none;" id="showBlueIndication">
                            * Blue Color indicates for Miscellaneous Fee Heads.
                           </span>
							<table border="0" cellspacing="0" cellpadding="3" width="100%">
							 <tr>
								<td valign="top" width="100%">
								<div id="scroll" class="scroll">
								<div id="resultsDiv">
									<table border="0" cellspacing="1" cellpadding="3" width="100%">
									    <tr class="rowheading">
										    <td valign="middle" width="3%"><B>#</B></td>
										    <td valign="middle" width="89%"><B>Fee Head</B></td>
										    <td valign="middle" width="4%"><B><nobr>Amount<br>Due</nobr></B></td>
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
									<table border="0" cellspacing="2px" cellpadding="1px" width="100%" id="tdFeeBalanceDetail" >
                                     <tr><td colspan="3" align="center"><b>Total Dues</b></td></tr>
									 <tr id='chkPrevClassDues1' style='display:none'>
                                        <td valign="middle" align="right" nowrap class="contenttab_internal_rows"><B>Prev Class Dues</B></td>
                                        <td class="contenttab_internal_rows"><B>:</B></td>
                                        <td valign="middle" align='right'>
                                            <label id='lblPreviousFees'>0.00</label>
                                            <input type="hidden" id="previousFees" name="previousFees" class="inputbox2" size="14" value="0.00" READONLY/>
                                        </td>
                                     </tr>
                                     <tr id='chkPrevClassDues2' style='display:none'>
                                        <td valign="middle" align="right" nowrap class="contenttab_internal_rows"><B>Prev Class Fine</B></td>
                                        <td class="contenttab_internal_rows"><B>:</B></td>
                                        <td valign="middle" align='right'>
                                            <label id='lblPreviousFine'>0.00</label>
                                        </td>
                                     </tr>
									 <tr id='chkPrevClassDues3' style='display:none'>
										<td valign="middle" align="right" nowrap class="contenttab_internal_rows"><B>Prev Class Paid</B></td>
										<td class="contenttab_internal_rows"><B>:</B></td>
										<td valign="middle" align='right'>
                                            <label id='lblPreviousPayment'>0.00</label>
                                            <input type="hidden" id="previousPayment" name="previousPayment" class="inputbox2" size="14" value="0.00" READONLY/>
                                        </td>
									 </tr>
                                     <tr id='chkPrevClassDues4' style='display:none'>
										<td valign="middle" align="right" nowrap class="contenttab_internal_rows"><B>Prev Pending Dues</B></td>
										<td class="contenttab_internal_rows"><B>:</B></td>
										<td valign="middle" align='right'>
                                           <label id='lblPreviousDues'>0.00</label>
                                           <input type="hidden" id="previousDues" name="previousDues" class="inputbox2" size="14" value="0.00" READONLY/>
                                        </td>
									 </tr>
                                     <tr>
                                        <td valign="middle" align="right" class="contenttab_internal_rows"><B>Curr. Class Fees</B></td>
                                        <td class="contenttab_internal_rows"><B>:</B></td>
                                        <td valign="middle" align='right'>
                                            <label id='lblTotalFees'>0.00</label>
                                            <input type="hidden" id="totalFees" name="totalFees" class="inputbox2" size="14" value="0.00" READONLY/>
                                        </td>
                                     </tr>
                                      <tr>
                                        <td valign="middle" align="right"class="contenttab_internal_rows"><B>Fee Concession</B></td>
                                        <td class="contenttab_internal_rows"><B>:</B></td>
                                        <td valign="middle" align='right'>
                                            <label id='lblTotalConcession'>0.00</label>
                                            <input type="hidden" id="totalConcession" name="totalConcession" class="inputbox2" size="14" value="0.00"  READONLY/>
                                        </td>
                                     </tr>
                                     <tr>
                                        <td valign="middle" align="right" nowrap class="contenttab_internal_rows"><B>Curr. Fee Dues</B></td>
                                        <td class="contenttab_internal_rows"><B>:</B></td>
                                        <td valign="middle" align='right'>
                                            <label id='lblFeeDues'>0.00</label>
                                            <input type="hidden" id="currFeeDues" name="currFeeDues" class="inputbox2" size="14" value="0.00" READONLY/>
                                        </td>
                                     </tr>
                                     <tr>
                                        <td valign="middle" align="right" nowrap class="contenttab_internal_rows"><B><?php echo $captionFine; ?></span></B></td>
                                        <td class="contenttab_internal_rows"><B>:</B></td>
                                        <td valign="middle" align='right'>
                                            <label id='lblPreviousFineCurr'>0.00</label>
                                        </td>
                                     </tr>
									 <tr>
                                        <td valign="middle" align="right" nowrap class="contenttab_internal_rows"><B>Curr. Class Amount Paid</B></td>
                                        <td class="contenttab_internal_rows"><B>:</B></td>
                                        <td valign="middle" align='right'>
                                            <label id='lblPreviousPaymentCurr'>0.00</label>
                                            <input type="hidden" id="previousPaymentCurr" name="previousPaymentCurr" class="inputbox2" size="14" value="0.00" READONLY/>
                                        </td>
                                     </tr>
                                     
                                     <tr style="display:none">
                                        <td valign="middle" align="right" nowrap class="contenttab_internal_rows"><B>Balance Dues</B></td>
                                        <td class="contenttab_internal_rows"><B>:</B></td>
                                        <td valign="middle" align='right'>
                                            <label id='lblBalanceAmount'>0.00</label>
                                            <input type="hidden" id="balanceAmount" name="balanceAmount" class="inputbox2" size="14" value="0.00" READONLY/>
                                        </td>
                                     </tr>
									</table>
                                    <table border="0" cellspacing="2px" cellpadding="1px" width="100%" id="tdTransportBalanceDetail" style="display:none">
                                        <tr id='chkPrevClassTransportDues1' style='display:none'> 
                                            <td valign="middle" align="right" nowrap class="contenttab_internal_rows"><B>Transport Charges</B></td>
                                            <td class="contenttab_internal_rows"><B>:</B></td>
                                            <td valign="middle" align='right'>
                                                <label id='lblTransportCharges'>0.00</label>
                                            </td>
                                        </tr>
                                        <tr id='chkPrevClassTransportDues2' style='display:none'> 
                                            <td valign="middle" align="right" nowrap class="contenttab_internal_rows"><B><?php echo $captionTransport; ?></B></td>
                                            <td class="contenttab_internal_rows"><B>:</B></td>
                                            <td valign="middle" align='right'>
                                                <label id='lblTransportFine'>0.00</label>
                                            </td>
                                        </tr>
                                        <tr id='chkPrevClassTransportDues3' style='display:none'> 
                                            <td valign="middle" align="right" nowrap class="contenttab_internal_rows"><B>Transport Paid</B></td>
                                            <td class="contenttab_internal_rows"><B>:</B></td>
                                            <td valign="middle" align='right'>
                                                <label id='lblTransportPaid'>0.00</label>
                                            </td>
                                        </tr>
                                        <tr id='chkPrevClassTransportDues4' style='display:none'> 
                                            <td valign="middle" align="right" nowrap class="contenttab_internal_rows"><B>Transport Dues</B></td>
                                            <td class="contenttab_internal_rows"><B>:</B></td>
                                            <td valign="middle" align='right'>
                                               <label id='lblTransportDues'>0.00</label>
                                            </td>
                                        </tr>
                                    </table> 
                                    <table border="0" cellspacing="2px" cellpadding="1px" width="100%" id="tdHostelBalanceDetail" style="display:none">
                                        <tr id='chkPrevClassHostelDues1' style='display:none'> 
                                            <td valign="middle" align="right" nowrap class="contenttab_internal_rows"><B>Hostel Charges</B></td>
                                            <td class="contenttab_internal_rows"><B>:</B></td>
                                            <td valign="middle" align='right'>
                                                <label id='lblHostelCharges'>0.00</label>
                                            </td>
                                        </tr>
                                        <tr id='chkPrevClassHostelDues2' style='display:none'> 
                                            <td valign="middle" align="right" nowrap class="contenttab_internal_rows"><B><?php echo $captionHostel; ?></B></td>
                                            <td class="contenttab_internal_rows"><B>:</B></td>
                                            <td valign="middle" align='right'>
                                                <label id='lblHostelFine'>0.00</label>
                                            </td>
                                        </tr>
                                        <tr id='chkPrevClassHostelDues3' style='display:none'>   
                                            <td valign="middle" align="right" nowrap class="contenttab_internal_rows"><B>Hostel Paid</B></td>
                                            <td class="contenttab_internal_rows"><B>:</B></td>
                                            <td valign="middle" align='right'>
                                                <label id='lblHostelPaid'>0.00</label>
                                            </td>
                                        </tr>
                                        <tr id='chkPrevClassHostelDues4' style='display:none'>   
                                            <td valign="middle" align="right" nowrap class="contenttab_internal_rows"><B>Hostel Pending Dues</B></td>
                                            <td class="contenttab_internal_rows"><B>:</B></td>
                                            <td valign="middle" align='right'>
                                               <label id='lblHostelDues'>0.00</label>
                                            </td>
                                        </tr>
                                    </table> 
								</td>
							</tr>
						</table>
						</fieldset>
					</td>
					</tr> 
                    
                    <tr><td height='15px'>
					
						
					</td></tr>
					
					<tr>
						<td height="5"></td>
					</tr>
					</table>
					<!-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
                              <table width="100%" border="0" cellspacing="2px" cellpadding="2px">
                              <tr><td height="2px"></td></tr>
                              <tr>
                               <td>  
                                <table width="100%" border="0" cellspacing="2px" cellpadding="2px" class="border" id="tblPaymentDetail1" style="display:none;vertical-align:top;">                                  
                              <tr><td height="5px"></td></tr>
                               <tr><th style="padding-left:10px;" class="online_instrunctions"><b><u>Payment Terms</u></b></th></tr> 
                              <tr>
                                <td class="online_instrunctions" width="100%" colspan="6" style="padding-left:10px;">
                               
                                    <p>You are being re-directed to a third party site. Please acknowledge the disclaimer before proceeding further. You are about to access
										a site, the accuracy or completeness of the materials or the reliability of any advice, opinion, statement or other information displayed 
										or distributed through it, is not warranted by the <?php echo $feeFavour; ?> and shall be solely be construed to be set forth by the third party.
									</p>
									<p>You will access this site solely for the payment of fee and other charges and you acknowledge that any reliance on any opinion, advice,
										statement, memorandum, or information available on the site shall be at your sole risk and consequences. The <?php echo $feeFavour; ?> expressly disclaims
										any liability for any deficiency in the services of the service provider whose site you are about to access.
									</p>
									<p>The <?php echo $feeFavour; ?> will not be liable to or have any responsibility of any kind for any loss that you incur in the event
										of any deficiency in the services of the service provider, failure or disruption of the site of the service provider,
										or resulting from the act or omission of any other party involved in making this site or the data contained therein available to you or
										from any other cause relating to your access to, inability to access, or use of the site or these materials in accordance thereto the <?php echo $feeFavour; ?>
										stand indemnified from all proceedings or matters arising thereto.
									</p>
									<p>It is the sole responsibility of the parent until the fee paid by him/her is credited in to the <?php echo $feeFavour; ?>'s Bank Account.</p>       
                               </td>   
                              </tr> 
                            </table>                           
                           
                            <table width="100%" border="0" cellspacing="2px" cellpadding="2px" class="border" id="tblPaymentDetail2" style="display:none;vertical-align:top;">                                   
                              <tr><td height="2px"></td></tr>
                              <tr><th style="padding-left:10px;" class="online_instrunctions"><b><u>Refund & Cancellation</u></b></th></tr> 
                              <tr>
                                <td class="online_instrunctions" width="100%" colspan="6" style="padding-left:10px;">
								<p>In case of any technical issue due to which the payment gets deducted from the payer's account and does not reach the <?php echo $feeFavour; ?>’s account or a double
									payment happens and the payment gateway service clearly mentions it, the <?php echo $feeFavour; ?> will send a refund request to the payment gateway service after which
									it should take 7-10 working days for the payment to be credited back to the payer's account.
								</p>
 
								<p>In all other cases where there is a discrepancy in the fee paid, the parent has to directly contact the <?php echo $feeFavour; ?> and the <?php echo $feeFavour; ?>'s decision would be final.</p>       
                               </td>   
                              </tr> 
                            </table>                           
                          
                             <table width="100%" border="0" cellspacing="2px" cellpadding="2px" class="border" id="tblPaymentDetail3" style="display:none;vertical-align:top;">   
                               <tr><td height="2px"></td></tr>
                               <tr><th style="padding-left:10px;" class="online_instrunctions"><b><u>Privacy Policy</u></b></th></tr>
                               <tr>
                                <td class="online_instrunctions" width="100%" colspan="6" style="padding-left:10px;">
									<p><?php echo $feeFavour; ?>, respects your right to privacy.</p>
 
									<p>When you visit the <?php echo $feeFavour; ?> Official Web site, the following information may be collected from you, either voluntarily or involuntarily :</p>
									<ul>
										<li>1.Your computer or network IP address, which must be validated in order for you to access the <?php echo $feeFavour; ?> Official Web site.</li>
										<li>2.Your e-mail address and message when you communicate electronically with us.</li>
									</ul>
									<p>Information about your visit is gathered in an aggregate manner for quality control, security and improvement of our site.</p>
 
									<p>Your information is kept confidential, unaltered, and used only by <?php echo $feeFavour; ?>, to administer your request.</p>
 
									<p><?php echo $feeFavour; ?>, does not disclose or sell any personally identifiable information collected at this site to other companies or organizations.</p>
                               </td>   
                              </tr> 
                            </table>   
                           </td>
                           </tr>   
                          <tr>
                             <td colspan="3" > 
									
                               <div id="scroll2" style="overflow:auto; width:750px; vertical-align:top;">
                                  <div id="divFeeAmountResult" style="vertical-align:top;">
                                     <?php                                                                             
                                        require_once(TEMPLATES_PATH .'/Fee/OnlineFeeCollection/onlinePaymentReceiptPrint.php');
                                     ?>
                                  </div>
                               </div>
                            </td>
                         </tr>
                       </table>           
                    </td>         
                 </tr>
                 <tr>
                   <td colspan="3" >
                     <table width="100%" border="0" cellspacing="0"  class="contenttab_internal_rows" cellpadding="0">
                       <tr>
                         <td class="payment_menu" valign="top" width="65%" align="right">
                           <span id="term1"><a href="#" onclick="getPaymentTerms(1);"><font color="gray">|&nbsp;Payment Terms&nbsp;|</font></a></span>
                           <span id="term2"><a href="#" onclick="getPaymentTerms(2);"><font color="gray">&nbsp;Refund & Cancellation&nbsp;|</font></a></span>
                           <span id="term3"><a href="#" onclick="getPaymentTerms(3);"><font color="gray">&nbsp;Privacy Policy&nbsp;|&nbsp;</font></a></span>
                         </td>
                       </tr>
                       <tr><td height="1px;" >&nbsp;</td></tr>
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