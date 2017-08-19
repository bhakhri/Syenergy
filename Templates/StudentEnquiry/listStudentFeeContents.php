<?php
//-------------------------------------------------------
// This file is a Template file for listing of All Program Wise Status of Candidate and Allot a Program to a Candidate
//
//
// Author :Vimal Sharma                          
// Created on : (14.04.2009)
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
<form name="studentFeeForm" action="" method="post" onSubmit="return false;">  
<input readonly type="hidden" name="currentDate" id="currentDate" value="<?php echo date('Y-m-d'); ?>" /> 
<input readonly type="hidden" id="studentId" name="studentId" class="inputbox"  />
<input readonly type="hidden" id="feeReceiptId" name="feeReceiptId" class="inputbox" />
<input readonly type="hidden" id="feeReceiptNo" name="feeReceiptNo" class="inputbox" />
<input readonly type="hidden" id="feeTotAmt" name="feeTotAmt" class="inputbox" />
<input readonly type="hidden" id="feeDoe" name="feeDoe" class="inputbox" />   
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
		<td valign="top" colspan="2">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
                <tr>
					<td valign="top" class="content">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr height="30">
                <tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
                <tr>
                    <td valign="top" class="content">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td class="" height="20" colspan="2">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                        <tr>
                                            <td colspan="1" class="content_title">Admission Fee Detail :</td>
                                            <td colspan="1" class="content_title" align="right">
                                            <!--  <input type="image" name="s1" value="s1" src="<?php echo IMG_HTTP_PATH;?>/print.gif"  onClick="printReport()" />&nbsp;
                                                  <input type="image" name="s2" value="s2" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" />
                                            -->      
                                            </td>
                                        </tr>
                                    </table>                                     
                                </td>
                            </tr>
                            <tr>
                                <td class='contenttab_row' width="58%" valign="top">
                                    <!-- Start List -->
                                    <fieldset class="fieldset">
                                       <legend>Seats Status (Admission Granted)</legend>
                                        <div id="scroll2" style="overflow:auto; height:545px; vertical-align:top;">
                                          <div id="results" style="width:98%; vertical-align:top;"></div>
                                        </div>
                                    </fieldset>            
                                    <!-- End List -->                                                                                 
                                </td>
                                <td class='contenttab_row' width="42%" valign="top" >
                                       <?php 
                                          //$disable="disabled";  
                                          $txtWidth = 'style="width:250px"';
                                       ?>
                                       <table width="100%" border="0" cellspacing="0" cellpadding="0"> 
                                       <tr> 
                                           <td width="50%" valign="top">    
                                             <!-- Start List -->
                                               <fieldset class="fieldset">
                                                   <legend>Search Candidate</legend>
                                                   <table width="100%" border="0" cellspacing="0" cellpadding="0">                       
                                                   <tr>
                                                       <td width="100%" valign="top" align="center">  
                                                         <table width="100%" border="0" cellspacing="5px" cellpadding="5px">                       
                                                         <tr>
                                                            <td class="contenttab_internal_rows"><nobr><b>Competition Exam Roll No.</b></nobr></td>
                                                            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td> 
                                                            <td class="contenttab_internal_rows"><nobr>
                                                              <input type="text" id="examRollNo" name="examRollNo" class="inputbox1" maxlength="20" style="width:150px" />
                                                              </nobr>
                                                            </td> 
                                                          </tr>
                                                          <tr>
                                                            <td class="contenttab_internal_rows"><nobr><b>Application Form No. </b></nobr></td>
                                                            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td> 
                                                            <td class="contenttab_internal_rows"><nobr>
                                                              <input type="text" id="examRollNo2" name="examRollNo2" class="inputbox1" maxlength="20" style="width:150px" />
                                                              </nobr>
                                                            </td> 
                                                          </tr>
                                                          <tr>
                                                             <td class="contenttab_internal_rows" colspan="3"><center><nobr> 
                                                               <input type="image" name="submit" src="<?php echo IMG_HTTP_PATH;?>/search.gif" title="Search"  onClick="validateForm();return false;"/>&nbsp;
                                                               <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport(3)" />&nbsp;
                                                             </nobr></center>  
                                                            </td>
                                                          </tr>
                                                          </table>   
                                                       </td>
                                                   </tr>
                                                   </table>
                                               </fieldset>
                                             <!-- End List --> 
                                           </td> 
                                       </tr>
                                       <tr>
                                          <td class="padding" height="2px"></td>
                                       </tr>
                                       <tr id='nameRow' style='display:none'>
                                               <td width="50%" valign="top">    
                                               <fieldset class="fieldset">
                                               <legend>Fee Receipt Details</legend>
                                               <table width="100%" border="0" cellspacing="0" cellpadding="0">                       
                                               <tr>
                                                  <td width="100%" valign="top">  
                                                   <table width="100%" border="0" cellspacing="2px" cellpadding="0px">                       
                                                       <tr id='feeRow2' style='display:none;'> 
                                                            <td class="contenttab_internal_rows" width="32%"><nobr><b>Receipt No.</b></nobr></td>
                                                            <td class="contenttab_internal_rows" width="2%"><b>:</b></nobr></td>
                                                            <td class="contenttab_internal_rows" width="66%"><nobr>  
                                                               <table width="100%" border="0" cellspacing="0px" cellpadding="0px"> 
                                                                 <tr>
                                                                    <td class="contenttab_internal_rows" width="30%"><nobr>
                                                                        <label id="tReceiptNos1" name="tReceiptNos1" class="noBorder"  /></nobr>
                                                                    </td>
                                                                    <td class="contenttab_internal_rows" width="20%"><nobr><b>Receipt Date</b></nobr></td>
                                                                    <td class="contenttab_internal_rows" width="2%"><nobr><b>:</b></td>
                                                                    <td class="contenttab_internal_rows" width="28%"><nobr>
                                                                        <label id="tFeeDoe1" name="tFeeDoe1" class="noBorder" /></nobr>
                                                                    </td>
                                                                 </tr>
                                                                </table>       
                                                            </nobr></td>
                                                        </tr>
                                                        <tr>    
                                                            <td class="contenttab_internal_rows" width="32%"><nobr><b>Degree<?php echo REQUIRED_FIELD ?></b></nobr></td>
                                                            <td class="contenttab_internal_rows" width="2%"><nobr><b>:</b></td>
                                                            <td class="contenttab_internal_rows" width="66%"><nobr>
                                                              <span id='degreeId1' style='display:none;'>   
                                                                  <select size="1" class="inputbox1" name="degreeId" id="degreeId" style='width:254px'>
                                                                     <option value="">Select</option>
                                                                     <?php
                                                                       require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                                       echo HtmlFunctions::getInstance()->getAdmitClassData();
                                                                     ?>
                                                                  </select>  
                                                              </span>
                                                              <span id='degreeId2' style='display:none;'>  
                                                                  <input type="text" id="tdegreeId" name="tdegreeId" class="noBorder" <?php echo $txtWidth; ?>  maxlength="50"/>
                                                              </span>
                                                             </nobr>
                                                            </td>
                                                        </tr>
                                                        <tr>    
                                                            <td class="contenttab_internal_rows"><nobr><b>Cash Amount<?php echo REQUIRED_FIELD ?></b></nobr></td>
                                                            <td class="contenttab_internal_rows"><nobr><b>:</b></td>
                                                            <td class="contenttab_internal_rows"><nobr>
                                                               <input type="text" id="cashFee" name="cashFee" class="inputbox" <?php echo $txtWidth; ?> maxlength="12" />
                                                            </nobr></td>
                                                        </tr>
                                                        <tr>    
                                                            <td class="contenttab_internal_rows"><nobr><b>DD Amount<?php echo REQUIRED_FIELD ?></b></nobr></td>
                                                            <td class="contenttab_internal_rows"><nobr><b>:</b></td>
                                                            <td class="contenttab_internal_rows"><nobr>
                                                              <input type="text" id="feeDD" name="feeDD" class="inputbox" <?php echo $txtWidth; ?> maxlength="12" />
                                                            </nobr></td>
                                                        </tr>
                                                        <tr>    
                                                            <td class="contenttab_internal_rows"><nobr><b>DD No.<?php echo REQUIRED_FIELD ?></b></nobr></td>
                                                            <td class="contenttab_internal_rows"><nobr><b>:</b></td>
                                                            <td class="contenttab_internal_rows"><nobr>
                                                              <input type="text" id="feeDDNo" name="feeDDNo" class="inputbox" <?php echo $txtWidth; ?>  maxlength="50"/>
                                                            </nobr></td>
                                                        </tr>
                                                        <tr id='feeDate1' style='display:none;'>    
                                                            <td class="contenttab_internal_rows"><nobr><b>DD Dated</b></nobr></td>
                                                            <td class="contenttab_internal_rows"><nobr><b>:</b></td>
                                                            <td class="contenttab_internal_rows"><nobr>
                                                               <?php
                                                                   require_once(BL_PATH.'/HtmlFunctions.inc.php');        
                                                                   echo HtmlFunctions::getInstance()->datePicker('feeDDDate',date('Y-m-d'));
                                                               ?>
                                                            </nobr></td>
                                                        </tr>
                                                        <tr id='feeDate2' style='display:none;'>    
                                                            <td class="contenttab_internal_rows"><nobr><b>DD Dated</b></nobr></td>
                                                            <td class="contenttab_internal_rows"><nobr><b>:</b></td>
                                                            <td class="contenttab_internal_rows"><nobr>
                                                                <input type="text" id="tfeeDDDate" name="tfeeDDDate" class="noBorder" <?php echo $txtWidth; ?>  maxlength="50"/>
                                                            </nobr></td>
                                                        </tr>
                                                        <tr>    
                                                            <td class="contenttab_internal_rows"><nobr><b>Bank Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
                                                            <td class="contenttab_internal_rows"><nobr><b>:</b></td>
                                                            <td class="contenttab_internal_rows"><nobr>
                                                              <input type="text" id="bankName" name="bankName" class="inputbox" <?php echo $txtWidth; ?> maxlength="50" /> 
                                                            </nobr></td>
                                                        </tr>
                                                        <tr>    
                                                            <td class="contenttab_internal_rows" valign="top"><nobr><b>Remarks</b></nobr></td>
                                                            <td class="contenttab_internal_rows" valign="top"><nobr><b>:</b></td>
                                                            <td class="contenttab_internal_rows" valign="top"><nobr>
                                                                <textarea class="inputbox1" name="remarks" id="remarks"  <?php echo $txtWidth; ?>  cols="37" rows="2" maxlength="500" onkeyup="return ismaxlength(this)">
                                                                </textarea>                                                          
                                                            </nobr></td>
                                                        </tr>
                                                        <tr id='nameRow2' style='display:none;'>
                                                            <td class="" height="20" colspan="3" align="center">
                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                                                    <tr>
                                                                        <td class="content_title" align="center"><nobr>
                                                                          <span id='printRow1' style='display:"";'>
                                                                             <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif"       onClick="validateAddForm1(this.form,1)" />&nbsp;
                                                                             <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/save_print.gif" onClick="validateAddForm1(this.form,2)" />&nbsp;
                                                                          </span>
                                                                          <span id='printRow2' style='display:none;'>
                                                                             <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/cancel_receipt.gif" onClick="editCancelForm(this.form,'CancelReceipt',315,250);" />&nbsp;
                                                                          </span>  
                                                                         </nobr>                                                                                                                                                        
                                                                        </td>    
                                                                    </tr>
                                                                </table>
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
                                          <td class="padding" height="5px"></td>
                                       <tr> 
                                       <tr id='resultRow' style='display:none'>
                                       <td width="50%" valign="top">     
                                               <fieldset class="fieldset">
                                               <legend>Candidate Details</legend>
                                               <table width="100%" border="0" cellspacing="0" cellpadding="0">                       
                                               <tr>
                                               <td width="100%" valign="top"> 
                                                   <table width="100%" border="0" cellspacing="0" cellpadding="0">                       
                                                        <tr>    
                                                            <td class="contenttab_internal_rows" width="32%"><nobr><b>Candidate Name</b></nobr></td>
                                                            <td class="contenttab_internal_rows" width="2%"><nobr><b>:&nbsp;</b></td>
                                                            <td class="contenttab_internal_rows" width="66%"><nobr>
                                                               <input readonly type="text" id="candidateName" name="candidateName" class="noBorder" <?php echo $txtWidth; ?> <?php echo $disable; ?> />
                                                            </nobr></td> 
                                                        </tr>
                                                        <tr>   
                                                            <td class="contenttab_internal_rows" width="22%"><nobr><b>Father's Name</b></nobr></td>
                                                            <td class="contenttab_internal_rows" width="2%"><nobr><b>:&nbsp;</b></td>
                                                            <td class="contenttab_internal_rows" width="76%" style="border:0px"><nobr>
                                                               <input readonly type="text" id="fatherName" name="fatherName" class="noBorder" <?php echo $txtWidth; ?> <?php echo $disable; ?> />
                                                            </nobr></td>
                                                        </tr>
                                                        <tr>    
                                                            <td class="contenttab_internal_rows" width="22%"><nobr><b>Application Form No.</b></nobr></td>
                                                            <td class="contenttab_internal_rows" width="2%"><nobr><b>:&nbsp;</b></td>
                                                            <td class="contenttab_internal_rows" width="76%"><nobr>
                                                               <input readonly type="text" id="applicationNo" name="applicationNo" class="noBorder" <?php echo $txtWidth; ?>  <?php echo $disable; ?> />
                                                            </nobr></td>
                                                        </tr>
                                                        <tr>    
                                                            <td class="contenttab_internal_rows" width="22%"><nobr><b>Application Submit Date</b></nobr></td>
                                                            <td class="contenttab_internal_rows" width="2%"><nobr><b>:&nbsp;</b></td>
                                                            <td class="contenttab_internal_rows" width="76%"><nobr>
                                                               <input readonly type="text" id="applicationDate" name="applicationDate" class="noBorder" <?php echo $txtWidth; ?>  <?php echo $disable; ?> />
                                                            </nobr></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="contenttab_internal_rows" ><nobr><b>Comp. Exam. By</b></nobr></td>
                                                            <td class="contenttab_internal_rows" width="2%"><nobr><b>:&nbsp;</b></td>
                                                            <td class="contenttab_internal_rows"><nobr>  
                                                              <input readonly type="text" id="compExamBy" name="compExamBy" class="noBorder" <?php echo $txtWidth; ?>  <?php echo $disable; ?> />    
                                                            </nobr></td>
                                                        </tr>   
                                                        <tr>          
                                                            <td class="contenttab_internal_rows"><nobr><b>Comp. Roll No.</b></nobr></td>
                                                            <td class="contenttab_internal_rows" width="2%"><nobr><b>:&nbsp;</b></td>
                                                            <td class="contenttab_internal_rows"><nobr>
                                                              <input readonly type="text" id="compExamRollNo" name="compExamRollNo" class="noBorder" <?php echo $txtWidth; ?> <?php echo $disable; ?> />    
                                                            </nobr></td>
                                                        </tr>    
                                                        <tr>          
                                                            <td class="contenttab_internal_rows"><nobr><b>Rank</b></nobr></td>
                                                            <td class="contenttab_internal_rows" width="2%"><nobr><b>:&nbsp;</b></td>
                                                            <td class="contenttab_internal_rows"><nobr>
                                                              <input readonly type="text" id="compExamRank" name="compExamRank" class="noBorder" <?php echo $txtWidth; ?>  <?php echo $disable; ?> />     
                                                            </nobr></td>
                                                        </tr>   
                                                        <tr>
                                                            <td class="contenttab_internal_rows"><nobr><b>Category</b></nobr></td>
                                                            <td class="contenttab_internal_rows" width="2%"><nobr><b>:&nbsp;</b></td>
                                                            <td class="contenttab_internal_rows"><nobr>
                                                               <input readonly type="text" id="categoryId" name="categoryId" class="noBorder" <?php echo $txtWidth; ?> <?php echo $disable; ?> /> 
                                                            </nobr></td>
                                                        </tr>
                                                        <tr>    
                                                            <td class="contenttab_internal_rows" width="22%"><nobr><b>Contact No.</b></nobr></td>
                                                            <td class="contenttab_internal_rows" width="2%"><nobr><b>:&nbsp;</b></td>
                                                            <td class="contenttab_internal_rows" width="76%"><nobr>
                                                               <input readonly type="text" id="contactNo" name="contactNo" class="noBorder" <?php echo $txtWidth; ?>  <?php echo $disable; ?> />
                                                            </nobr></td>
                                                        </tr>
                                                        <tr>    
                                                            <td class="contenttab_internal_rows" width="22%"><nobr><b>E-mail</b></nobr></td>
                                                            <td class="contenttab_internal_rows" width="2%"><nobr><b>:&nbsp;</b></td>
                                                            <td class="contenttab_internal_rows" width="76%"><nobr>
                                                               <input readonly type="text" id="candidateEmail" name="candidateEmail" class="noBorder"  <?php echo $txtWidth; ?> <?php echo $disable; ?> />
                                                            </nobr></td>
                                                        </tr>
                                                   </table>
                                               </td>
                                               </tr>
                                               </table>
                                               </fieldset>
                                         </tr>
                                       </table>      
                             </td>
                          </tr>
                     </table>
                     <!-- form table ends -->
                    </td>
                </tr>
            </table>
            </td>
        </tr>
    </table>
</form>



<!--Start edit Div-->
<?php floatingDiv_Start('CancelReceipt','Cancel Receipt'); ?>
<form name="cancelReceipt" action="" method="post" onsubmit="return false;">
    <table width="100%" border="0" cellspacing="2px" cellpadding="0px">    
       <input readonly type="hidden" id="cancelStudentId" name="cancelStudentId" class="inputbox" />
       <input readonly type="hidden" id="cancelFeeReceiptId" name="cancelFeeReceiptId" class="inputbox" />
         <tr>    
            <td class="contenttab_internal_rows"><nobr><b>Candidate Name</b></nobr></td>
            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
            <td class="contenttab_internal_rows"><nobr><label id="cname" name="cname" class="noBorder"  /></nobr></td>
         </tr>
         <tr>    
            <td class="contenttab_internal_rows"><nobr><b>Degree</b></nobr></td>
            <td class="contenttab_internal_rows"><nobr><b>:</b></td>
            <td class="contenttab_internal_rows"><nobr><label id="degreeName" name="degreeName" class="noBorder"  /></nobr></td>
         </tr>
         <tr>    
            <td class="contenttab_internal_rows"><nobr><b>Receipt No.</b></nobr></td>
            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
            <td class="contenttab_internal_rows"><nobr>
               <table width="100%" border="0" cellspacing="0px" cellpadding="0px"> 
                 <tr>
                    <td class="contenttab_internal_rows" width="30%"><nobr><label id="tReceiptNos" name="tReceiptNos" class="noBorder"  /></nobr></td>
                    <td class="contenttab_internal_rows" width="20%"><nobr><b>Receipt Date</b></nobr></td>
                    <td class="contenttab_internal_rows" width="2%"><nobr><b>:</b></td>
                    <td class="contenttab_internal_rows" width="28%"><nobr>
                        <label id="tFeeDoe" name="tFeeDoe" class="noBorder" /></nobr>
                    </td>
                 </tr>
                </table>       
            </nobr></td>
         </tr>
         <tr>    
            <td class="contenttab_internal_rows"><nobr><b>Paid Amount</b></nobr></td>
            <td class="contenttab_internal_rows"><nobr><b>:</b></td>
            <td class="contenttab_internal_rows"><nobr><label id="tTotalPaidAmount" name="tTotalPaidAmount" class="noBorder"  /></nobr></td>
         </tr>
         <tr>    
            <td class="contenttab_internal_rows" colspan="3" height="5px" valign="top"><nobr><hr></nobr></td>
         </tr>
         <tr>    
            <td class="contenttab_internal_rows"><nobr><b>Refund Mode<?php echo REQUIRED_FIELD ?></b></nobr></td>
            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
            <td class="contenttab_internal_rows"><nobr>
               <select size="1" class="inputbox1" name="cancelPaymentMode" id="cancelPaymentMode" onClick="getPercentageValue(); return false;" style='width:100px'>
                 <option value="1">Percentage</option>
                 <option value="2">Fixed</option>
               </select>
               &nbsp;&nbsp;<b><label><label id="tFormat" name="tFormat" class="noBorder" ></label></b>&nbsp;  
               <input type="text" id="paymentValue" name="paymentValue" onkeyup="getPercentageValue1(); return false;" class="inputbox" style="width:110px" maxlength="12" />  
            </nobr>
            </td>
        </tr>
        <tr>
            <td class="contenttab_internal_rows"><nobr><b>Refund Amount</b></nobr></td>
            <td class="contenttab_internal_rows"><nobr><b>:</b></td>
            <td class="contenttab_internal_rows">
            <label id="tPayValue" name="tPayValue" class="noBorder" /></nobr>
            </td>
        </tr>
        <!--
        <tr>    
            <td class="contenttab_internal_rows"><nobr><b>Cash Fees Paid<?php echo REQUIRED_FIELD ?></b></nobr></td>
            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
            <td class="contenttab_internal_rows"><nobr>
               <input type="text" id="cancelCashFee" name="cancelCashFee" class="inputbox" <?php echo $txtWidth; ?> maxlength="12" />
            </nobr></td>
        </tr>
        <tr>    
            <td class="contenttab_internal_rows"><nobr><b>Fees Paid By Draft<?php echo REQUIRED_FIELD ?></b></nobr></td>
            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
            <td class="contenttab_internal_rows"><nobr>
              <input type="text" id="cancelFeeDD" name="cancelFeeDD" class="inputbox" <?php echo $txtWidth; ?> maxlength="12" />
            </nobr></td>
        </tr>
        <tr>    
            <td class="contenttab_internal_rows"><nobr><b>DD No.<?php echo REQUIRED_FIELD ?></b></nobr></td>
            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
            <td class="contenttab_internal_rows"><nobr>
              <input type="text" id="cancelFeeDDNo" name="cancelFeeDDNo" class="inputbox" <?php echo $txtWidth; ?>  maxlength="50"/>
            </nobr></td>
        </tr>
        <tr>    
            <td class="contenttab_internal_rows"><nobr><b>Dated<?php echo REQUIRED_FIELD ?></b></nobr></td>
            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
            <td class="contenttab_internal_rows"><nobr>
               <span id='feeDate1' style='display:none;'>  
               <?php
                     require_once(BL_PATH.'/HtmlFunctions.inc.php');
                     echo HtmlFunctions::getInstance()->datePicker('cancelFeeDDDate',date("Y-m-d"));
               ?>
               </span>
            </nobr></td>
        </tr>
        <tr>    
            <td class="contenttab_internal_rows"><nobr><b>Bank Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
            <td class="contenttab_internal_rows"><nobr>
              <input type="text" id="cancelBankName" name="cancelBankName" class="inputbox" <?php echo $txtWidth; ?> maxlength="50" /> 
            </nobr></td>
        </tr>
        -->
        <tr>    
            <td class="contenttab_internal_rows" valign="top"><nobr><b>Remarks<?php echo REQUIRED_FIELD ?></b></nobr></td>
            <td class="contenttab_internal_rows" valign="top"><nobr><b>:</b></nobr></td>
            <td class="contenttab_internal_rows" valign="top"><nobr>
                <textarea class="inputbox1" name="cancelRemarks" id="cancelRemarks"  <?php echo $txtWidth; ?>  cols="37" rows="2" maxlength="500" onkeyup="return ismaxlength(this)">
                </textarea>                                                          
            </nobr></td>
        </tr>
        <tr>
            <td class="" height="20" colspan="3" align="center">
                 <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif"   onClick="validateCancelForm(this.form)" />&nbsp;
                 <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onClick="javascript:hiddenFloatingDiv('CancelReceipt');return false;" />&nbsp;
            </td>
        </tr>
    </table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->      

<?php 
// $History: listStudentFeeContents.php $
//
//*****************  Version 4  *****************
//User: Parveen      Date: 4/14/10    Time: 11:23a
//Updated in $/LeapCC/Templates/StudentEnquiry
//validation and format updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 3/23/10    Time: 6:34p
//Updated in $/LeapCC/Templates/StudentEnquiry
//query & condition format updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 3/18/10    Time: 12:45p
//Updated in $/LeapCC/Templates/StudentEnquiry
//validation & condition updated
//

?>


