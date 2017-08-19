<?php
//-------------------------------------------------------
// Purpose: to design add fees receipt.
//
// Author : Rajeev Aggarwal
// Created on : (17.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(BL_PATH.'/helpMessage.inc.php');    

global $sessionHandler;  
$captionFine=$sessionHandler->getSessionVariable('LABEL_FINE');
$captionHostel=$sessionHandler->getSessionVariable('LABEL_HOSTEL');
$captionTransport=$sessionHandler->getSessionVariable('LABEL_TRANSPORT'); 
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
                            <table border="0" cellspacing="0" cellpadding="0" align="right" style="display:''">
                              <tr>
                                <td valign='top' style='padding-left:10px;color:black'><span style="font-size:12px;color:white">Please allow popups from ERP in the browser(s) you are using.</span>&nbsp;&nbsp;&nbsp;&nbsp;
                                   <span style="font-size:17px;color:white">*&nbsp;</span><span style="font-size:12px;color:white">These are the amounts already paid.</span>
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
								<legend>Select Student</legend>
								<table width="100%" border="0" cellspacing="1" cellpadding="1" >
                                <tr>
                                    <td class="contenttab_internal_rows" colspan="3">
                                       <nobr><B>Last Receipt No. and Date&nbsp;:&nbsp;</B>
                                       <label id='lastEntry'></label>
                                       </nobr>
                                    </td>
                                </tr>
                                <tr><td height="10px"></td></tr>
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
                                    <td class="contenttab_internal_rows"><nobr><B>Receipt No.</B><?php echo REQUIRED_FIELD;
                                    require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                                    echo HtmlFunctions::getInstance()->getHelpLink('Receipt no.',HELP_FEE_RECEIPTNO);?></nobr></td>
                                    <td class="contenttab_internal_rows"><B>:</B></td>
                                    <td><?php //echo $studentFeeReceiptNo 
                                            $receiptNo = $sessionHandler->getSessionVariable('FEE_RECEIPT_PREFIX');
                                        ?>
                                        <input type="text" id="receiptNumber" name="receiptNumber" class="inputbox1" value="<?php echo $receiptNo?>" style="width:220px" onkeypress="return sendKeys('feeType',event);"/>
                                    </td>
                                </tr>
                                 <tr>    
                                    <td class="contenttab_internal_rows" width="28%"><nobr><B>Univ./Reg./ Roll No.</B><?php echo REQUIRED_FIELD?></nobr></td>
                                    <td class="contenttab_internal_rows"><B>:</B></td>
                                    <td width="72%"><nobr>
                                        <input type="text" id="studentRoll" name="studentRoll" class="inputbox" style="width:130px" onchange="resetFeeClass(); populateValues(-1); getFeeCylceClasses(); return false;"  onkeypress="return sendKeys('feeCycle',event);"/>&nbsp;
                                        <a href='javascript:void(0)' onClick="showStudentDetails('getStudentDetail',850,100); blankValues();return false;" ><u>Search Student</u></a>
                                       </nobr>
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
                                <tr>    
                                    <td class="contenttab_internal_rows"><nobr><B>Fee Cycle</B><?php echo REQUIRED_FIELD;
                                    require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                                    echo HtmlFunctions::getInstance()->getHelpLink('Fee Cycle',HELP_FEE_CYCLE);?></nobr></td>
                                    <td class="contenttab_internal_rows"><B>:</B></td>
                                    <td><select style="width:225px" size="1" name="feeCycle" id="feeCycle"  class="inputbox1" onkeypress="return sendKeys('feeStudyPeriod',event);">
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
                                <tr height='25px' style="display:none" id='trShowPrevDues'>
                                    <td class="contenttab_internal_rows"><nobr><b></b></nobr></td>
                                    <td class="contenttab_internal_rows"><b>&nbsp;</b></td>
                                    <td width="72%" colspan="2">
                                      <!--
                                      <nobr>
                                        <input type="radio" name="includePreviousDues" id="includePreviousDues1" onclick="populateValues(-1); " checked="checked" />No&nbsp;
                                        <input type="radio" name="includePreviousDues" id="includePreviousDues2" onclick="populateValues(-1); "  />Yes
                                      </nobr>
                                      -->
                                      <nobr><a href='javascript:void(0)' onClick="getFacility(3,'divPrevDues',850,500);return false;" ><u>Dues</u></a>&nbsp;</nobr>
                                    <td>
                                </tr>
								</table>
								</fieldset>
								</td>
							</tr>
                            <tr>
								<td valign="top">
								<fieldset class="fieldset">
								<legend>Personal Details&nbsp;<b><span id="studentCurrentStatus"></span></b></legend>
								<table width="100%" border="0" cellspacing="1" cellpadding="3" >
								<!--tr>	
									<td class="contenttab_internal_rows" width="34%"><nobr><B>Serial Number</B></nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td width="66%"><span id="mySerial">--</span><input type="hidden" id="serialNumber" name="serialNumber" class="inputbox" READONLY/>
									</td>
								</tr-->
								
								<tr>	
									<td class="contenttab_internal_rows" width="34%"><nobr><B>Current Class Name</B></nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td width="66%"><span id="myClass">--</span></td>
								</tr>
                                <tr>    
                                    <td class="contenttab_internal_rows"><nobr><B>Reg. No.</B></nobr></td>
                                    <td class="contenttab_internal_rows"><B>:</B></td>
                                    <td></nobr><span id="lblRegNo">--</span></nobr></select>
                                    </td>
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
									<td><span id="myFather">--</span>
                                        <input type="hidden" id="fatherName" name="fatherName" class="inputbox"  READONLY/>
                                        <input type="hidden" id="feeHeadDetailFind" name="feeHeadDetailFind" value="0" class="inputbox"  READONLY/> 
									</td>
								</tr>
								
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
									<td class="contenttab_internal_rows" width="34%" valign="top"><nobr><B>Print Remarks</B>
                                    <?php require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                                    echo HtmlFunctions::getInstance()->getHelpLink('Print Remarks',HELP_PRINT_REMARKS);?></nobr></td>
									<td class="contenttab_internal_rows" valign="top"><B>:</B></td>
									<td><textarea type="text" id="printRemarks" name="printRemarks" class="inputbox1" rows="3" cols="30" tabindex="30"/></textarea></td>
								</tr>
								<tr>	
									<td class="contenttab_internal_rows" valign="top"><nobr><B>General Remarks</B>
                                     <?php require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                                    echo HtmlFunctions::getInstance()->getHelpLink('General Remarks',HELP_GENERAL_REMARKS);?></nobr></td>
									<td class="contenttab_internal_rows" valign="top"><B>:</B></td>
									<td><textarea type="text" id="generalRemarks" name="generalRemarks" class="inputbox1" rows="3" cols="30" tabindex="31"/></textarea></td>
								</tr>
								<tr>	
									<td class="contenttab_internal_rows" width="28%"><nobr><B>Received From</B>
                                     <?php require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                                      echo HtmlFunctions::getInstance()->getHelpLink('Received From',HELP_RECEIVED_FROM);?></nobr></td>
									<td class="contenttab_internal_rows"><B>:</B></td>
									<td width="72%"><input type="text" id="receivedFrom" name="receivedFrom" class="inputbox1" size="33"  tabindex="32"/>
									</td>
								</tr>
                                <tr><td height="10px"></td></tr>
                                <tr>    
                                    <td class="contenttab_internal_rows" colspan="3">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                                         <tr>
                                            <td ><nobr><B>Print Options&nbsp;:&nbsp;</B></nobr></td>
                                         </tr>
                                         <tr>
                                            <td>     
                                               <nobr><input type="checkbox" checked="checked" id="feeHeadChk" name="feeHeadChk" value="1"><nobr>&nbsp;Show Fee Head Details</b></nobr>
                                            </td>
                                            <td>     
                                               <nobr><input type="checkbox" checked="checked" id="paymentChk" name="paymentChk" value="1"><nobr>&nbsp;Show Payment Detail</b></nobr>
                                            </td>
                                         </tr>
                                         <tr style="display:none">
                                             <td>     
                                               <nobr><input type="checkbox" checked="checked" id="prevDueChk" name="prevDueChk" value="1"><nobr>&nbsp;Show All Prev Class Fee Dues</b></nobr>
                                            </td>
                                         </tr>
                                       </table>        
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
									<table border="0" cellspacing="2px" cellpadding="1px" width="100%" id="tdFeeBalanceDetail" style="display:none">
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
                    <tr>
                        <td valign="top">
                        <fieldset class="fieldset">
                        <legend>Amount Paid Detail</legend>
                        <table width="100%" border="0" cellspacing="2px" cellpadding="1px" >
                          <tr>
                             <td colspan="15" class="contenttab_internal_rows" width='5px'><nobr><B>Total Payable Amount&nbsp;:&nbsp;</B>
                                <label id='lblNetAmount'>0.00</label>    
                                <input type="hidden" id="netAmount" name="netAmount" class="inputbox2" size="14" value="0.00" READONLY/>
                                <input type="hidden" id="netAmount1" name="netAmount1" class="inputbox2" size="14" value="0.00" READONLY/>
                                </nobr>
                             </td>
                          </tr>
                          <tr><td colspan="20" height='5px'></td></tr>
                          <tr>
                            <td class="contenttab_internal_rows" colspan="20"><nobr> 
                                <table width="20%" border="0" cellspacing="0px" cellpadding="0px" >
                                <tr>
                                    <td class="contenttab_internal_rows" style="display:none" id='tdPrevDuesPaid1' width='5px'><nobr><B>Dues Amt Paid</b></nobr></td>
                                    <td class="contenttab_internal_rows" style="display:none"id='tdPrevDuesPaid2'><B>:</B></td>
                                    <td class="contenttab_internal_rows" style="display:none"id='tdPrevDuesPaid3'><nobr>
                                        <label id='lblDuesAmtPaid'></label>
                                        <input type="hidden" id="duesAmtPaid" name="duesAmtPaid"  class="inputbox2" style="width:80px" onblur="calculateFeePaid();" maxlength="8"  />
                                        </nobr>
                                    </td>    
                                    <td class="contenttab_internal_rows"  style="display:none;padding-left:15px" id='tdFeeAmtPaid1' width='5px'><nobr><B>Fee Amt Paid</b></nobr></td>
                                    <td class="contenttab_internal_rows"  style="display:none" id='tdFeeAmtPaid2'><B>:</B></td>
                                    <td class="contenttab_internal_rows"  style="display:none" id='tdFeeAmtPaid3'><nobr>
                                        <input type="text" id="feeAmtPaid" name="feeAmtPaid"  class="inputbox2" style="width:80px" onblur="calculateFeePaid();" maxlength="8"  />
                                        </nobr>
                                    </td>  
                                </tr>
                                <tr>    
                                    <td class="contenttab_internal_rows"  style="display:none" id='tdTransportAmtPaid1' width='5px'><nobr><B>Transport Amt Paid</b></nobr></td>
                                    <td class="contenttab_internal_rows"  style="display:none" id='tdTransportAmtPaid2'><B>:</B></td>
                                    <td class="contenttab_internal_rows"  style="display:none" id='tdTransportAmtPaid3'><nobr>
                                        <input type="text" id="transportAmtPaid" name="transportAmtPaid"  class="inputbox2" style="width:80px" onblur="calculateFeePaid();" maxlength="8"  />
                                        </nobr>
                                    </td>
                                    <td class="contenttab_internal_rows"  style="display:none;padding-left:15px" id='tdHostelAmtPaid1' width='5px'><nobr><B>Hostel Amt Paid</b></nobr></td>
                                    <td class="contenttab_internal_rows"  style="display:none" id='tdHostelAmtPaid2'><B>:</B></td>
                                    <td class="contenttab_internal_rows"  style="display:none" id='tdHostelAmtPaid3'><nobr>
                                        <input type="text" id="hostelAmtPaid" name="hostelAmtPaid"  class="inputbox2" style="width:80px" onblur="calculateFeePaid();"  maxlength="8"  />
                                        </nobr>
                                    </td>
                                </table>
                                </nobr>
                           </td>       
                        </tr>
                        <tr><td colspan="20" height='5px'></td></tr>
                        <tr>
                            <td valign="middle" align="right" class="contenttab_internal_rows"><B>Installments</B></td>
                            <td class="contenttab_internal_rows"><B>:</B></td>
                            <td class="field1_heading" align="right">
                                <span id="myInstallment"> 1</span>
                                <input type="hidden" id="installmentCount" name="installmentCount" class="inputbox2" value="1" READONLY/>
                            </td>
                            <td class="contenttab_internal_rows" id='tdFeeAmtPaid' width='5px'><nobr><B>Total Amount Paid
                           <?php require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                           echo HtmlFunctions::getInstance()->getHelpLink('Total Amount',HELP_TOTAL_AMOUNT_PAID);?></nobr></td>
                            <td class="contenttab_internal_rows"><B>:</B></td>
                            <td class="contenttab_internal_rows">
                              <label id='lblAmountPaid'>0.00</label> 
                              <input type="hidden" id="paidAmount" name="paidAmount" class="inputbox2" size="14" value="0.00"  onChange="receiveCashFee()" tabindex="11"/>
                              </nobr>
                            </td>
                            <td width="15%" nowrap class="contenttab_internal_rows" valign="middle"><B><span style="text-align:center;">Payable Branch</span></B></td>
                            <td class="contenttab_internal_rows"><B>:</B></td>
                            <td class="contenttab_internal_rows" colspan="10">
                              <select size="1" name="favouringBank" id="favouringBank" class="inputbox1"  tabindex="15" style="width:100px">
                                  <option value="">Select</option>
                                  <?php
                                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                      echo HtmlFunctions::getInstance()->getBankBranchData();
                                  ?>
                              </select>
                            </td>
                        </tr>
                        </table>
                        </fieldset>
                        </td>
                    </tr>
                    <tr><td height='15px'></td></tr>
					<tr>
						<td valign="top">
						<fieldset class="fieldset">
						<legend>Cash / Cheque / Draft Payment Detail</legend>
						<table width="100%" border="0" cellspacing="1" cellpadding="3" >
                         <tr>    
                            <td class="contenttab_internal_rows" width='5px'><nobr><B>Cash Amount&nbsp;:&nbsp;</B></nobr>
                                <span style="padding-left:5px">
                                <!-- <input type="text" id="cashAmount" name="cashAmount"  class="inputbox2" size="14"  tabindex="16" onblur="getAmountPaid('C'); return false;" value="0.00" /> -->
                                <input type="text" id="cashAmount" name="cashAmount"  class="inputbox2" style="width:80px"  maxlength="8" />
                                </span>
                            </td>
                        </tr>
                        <tr><td height='5px'></td></tr>
						<tr>
							<td class="contenttab_internal_rows" valign="top">
							<div id='anyidT'>
							<table width="100%" border="0" cellspacing="2" cellpadding="0" id="anyid">
								<tbody id="anyidBody">
									<tr class="rowheading">
										<td width="2%" class="searchhead_text"><b>Sr.</b></td>
										<td class="searchhead_text"><b>Type</b></td>
										<td class="searchhead_text"><b>Number</b></td>
										<td class="searchhead_text"><b>Amount</b></td>
										<td class="searchhead_text"><b>Bank</b></td>
                                        <td class="searchhead_text" align="center"><b>Date</b></td>
                                        <!--        
                                           <td class="searchhead_text"><b>Inst. Status</b></td>
										   <td class="searchhead_text"><b>Receipt Status</b></td> 
                                        -->
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
								<td  align="right">
                                    <input type="image" name="submit1" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="return validateForm(this.form,'Add');return false;" tabindex="33"/>&nbsp;
                                    <input type="image" name="submit2" src="<?php echo IMG_HTTP_PATH;?>/save_print.gif" onclick="return validateForm(this.form,'Print');return false;" tabindex="33"/>&nbsp; 
                                    <input type="image" name="submit2" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onclick="return validateForm(this.form,'OldPrint');return false;" tabindex="33"/>&nbsp;
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
		  echo HtmlFunctions::getInstance()->getClassDataForAlumni();
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
