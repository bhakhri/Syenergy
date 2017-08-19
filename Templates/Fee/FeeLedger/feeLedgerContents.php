<?php 
//This file creates Html Form output for Fee Ledger
// Author :Nishu Bindal
// Created on : 21-Mar-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<form name="studentAttendanceForm" id="studentAttendanceForm"  method="post" onSubmit="return false;">   

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			 <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>   
		</td>
	</tr>
	<tr>
		<td valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<tr>
					<td valign="top" class="content">
						<!-- form table starts -->
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
							<tr>
								<td valign="top" class="contenttab_row1">
									
										<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td class="contenttab_internal_rows1" align="left" nowrap="nowrap" width="2%">
                                                   <nobr><b>Reg/Roll No.<?php echo REQUIRED_FIELD ?></b></nobr>
                                                </td>
                                                <td class="contenttab_internal_rows" nowrap="nowrap" width="2%"><b>:&nbsp;</b></td>
												<td class="contenttab_internal_rows"  nowrap="nowrap" width="2%">
                                                   <input class="inputbox" type='text' name='regRollNo' id='regRollNo'>
                                                </td>
                                                <td class="contenttab_internal_rows"  nowrap="nowrap"  width="2%" style="padding-left:20px">
                                                   <nobr>&nbsp;<input id="includeFine" name="includeFine" type="checkbox"></nobr>   
                                                </td>    
                                                <td class="contenttab_internal_rows"  nowrap="nowrap" width="2%">
                                                   <nobr>Update Late Fee Fine</nobr> 
                                                </td>
												<td class="padding" align="left" style="padding-left:20px" width="90%">
						<input type="image" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="validateAddForm(this.form);return false;" />
												</td>
											</tr> 
                                            <tr><td style="height:20px"></td></tr>
                                            <tr>
                                              <td class="contenttab_internal_rows1" align="left" colspan="10"> 
                                              	<span style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 11px; color: red;"> 
		                                           <strong>Note:<br> </strong>
                                               		 <u>Click here</u> if you want to generate automatic fine to the student. Once fine generated it won't be reversed.
                                               		                                              		
		                                         </span>                                               
                                              </td>
                                            </tr>
										</table>
								</td>
							</tr>
						</table>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr id='nameRow' style='display:none;'>
								<td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
										<tr>
											<td colspan="1" class="content_title">Student Fee Ledger :</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr id='studentDetail' style="display:none;">
								<td>
									<fieldset class="fieldset">
    										<legend class="contenttab_internal_rows1">Student Details:</legend>
    											<table width='100%' border=0 cellspacing=0 cellpadding=0>
    												<tr>
    													<td width="10%" class="contenttab_internal_rows1">
    														<strong>Student Name</strong>
    													</td>
    													<td width="1%"> <strong>:</strong></td>
    													<td  class="contenttab_internal_rows1"><span id='studentName'>---</span></td>											    														<td width="7%" class="contenttab_internal_rows1">
    													<strong>Roll No.</strong>
    													</td>
    													<td width="1%"><strong>:</strong></td>
    													<td class="contenttab_internal_rows1"><span id='rollNo'>---</span></td>
    													<td class="contenttab_internal_rows1" width="10%">
    														<strong>Father Name</strong>
    													</td>
    													<td width="1%"><strong>:</strong></td>
    													<td class="contenttab_internal_rows1"><span id='fatherName'>---</span><input type='hidden' name='hiddenClassName' id='hiddenClassName'><input type='hidden' name='hiddenClassId' id='hiddenClassId'><input type='hidden' name='studentId' id='studentId'><input type='hidden' name='hiddenFeeCycleId' id='hiddenFeeCycleId'><input type='hidden' name='hiddenFeeCycleName' id='hiddenFeeCycleName'></td> 
  
    												</tr>
    											</table>
    									</fieldset>
								</td>
							</tr>
							<tr id='resultRow' style="display:none;" >
								<td colspan='1' >
									<table width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr height="30">
											<td class="contenttab_border" height="20" style="border-right:0px;padding-left:10px;">
												<table border=0 cellspacing=0 cellpadding=0 width='100%'>
												  <tr>
													<td class="content_title">
														Student Fee Ledger
													</td>
													<td width="14%" align="right" nowrap="nowrap" style="border-left:0px;padding-top:4px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="openDebitCreditWindow();" />&nbsp;</td>
													</tr>
												</table>
											</td>
										</tr>
										<tr>
										<td class="contenttab_row" colspan="2" valign="top" ><div id="resultsDiv"></div></td>
										</tr>
										<tr>
											<td style='padding-top:7px;' colspan="2" valign="top" align='right'><input type="image" name="AddCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:resetForm('all'); return false;" /></td>
										</tr>
									</table>
								</td>
						</tr>
			</table>
		</table>
</form>

<?php floatingDiv_Start('AddDebitCredit','Add Debit/Credit'); ?>
<form name="AddDebitCreditForm" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<tr>
    <td width="21%" class="contenttab_internal_rows" valign='top'>&nbsp;<nobr><b>Fee Cycle</b></nobr></td>
    <td width="1%" valign='top' ><b>:</b></td>
    <td width="78%" valign='top' ><span id='feeCycleNameSpan' style='padding-left:5px;'></span></td>
</tr>
<tr >
    <td width="21%" class="contenttab_internal_rows" valign='top'>&nbsp;<nobr><b>Class<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="1%" valign='top' ><b>:</b></td>
     <td width="78%" valign='top' class="padding">
        <select name="currentClassId" id="currentClassId" style="width:320px" class="selectfield">
         <option value="">Select</option>
        </select>
    
    </td>
</tr>
<tr >
    <td width="21%" class="contenttab_internal_rows" valign='top'>&nbsp;<nobr><b>Ledger Type<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="1%" valign='top' ><b>:</b></td>
     <td width="78%" valign='top' class="padding">
        <select name="ledgerTypeId" id="ledgerTypeId" style="width:320px" class="selectfield">
        
	  <option value="1">Academic</option>
	   <option value="2">Transport</option>
	   <option value="3">Hostel</option>
	  
        </select>
    
    </td>
</tr>
<tr>
    <td width="21%" class="contenttab_internal_rows" valign='top'>&nbsp;<nobr><b>Particulars<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="1%" valign='top' ><b>:</b></td>
    <td width="78%" class="padding" valign='top'><textarea id="particulars" name="particulars" style="width:320px" class="inputbox" ></textarea></td>
</tr>
<tr>
    <td width="21%" class="contenttab_internal_rows" valign='top'>&nbsp;<nobr><b>Debit</b></nobr></td>
     <td width="1%" valign='top' ><b>:</b></td>
    <td width="78%" class="padding"><input type="text" id="debit" name="debit" class="inputbox" style="width:320px" maxlength="10"/></td>
</tr>
<tr>
    <td width="21%" class="contenttab_internal_rows">&nbsp;<nobr><b>Credit</b></nobr></td>
     <td width="1%" valign='top' ><b>:</b></td>
    <td width="78%" class="padding"><input type="text" id="credit" name="credit" class="inputbox" style="width:320px" maxlength="10"/></td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="right" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="addDebitCredit();return false;" />
                    <input type="image" name="AddCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddDebitCredit'); return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td>
</tr>


</table>
</form>

<?php floatingDiv_End(); ?>


<?php floatingDiv_Start('EditDebitCredit','Edit Debit/Credit'); ?>
<form name="EditDebitCreditForm" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<input type='hidden' name='feeLedgerDebitCreditId' id='feeLedgerDebitCreditId'>
<input type='hidden' name='isFine' id='isFine'>
<tr >
    <td width="21%" class="contenttab_internal_rows" valign='top'>&nbsp;<nobr><b>Class<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="1%" valign='top' ><b>:</b></td>
     <td width="78%" valign='top' class="padding">
        <select name="currentClassId" id="currentClassId" style="width:320px" class="selectfield">
         <option value="">Select</option>
        </select>
    
    </td>
</tr>
<tr >
    <td width="21%" class="contenttab_internal_rows" valign='top'>&nbsp;<nobr><b>Ledger Type<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="1%" valign='top' ><b>:</b></td>
     <td width="78%" valign='top' class="padding">
        <select name="ledgerTypeId" id="ledgerTypeId" style="width:320px" class="selectfield">
        
	  <option value="1">Academic</option>
	   <option value="2">Transport</option>
	   <option value="3">Hostel</option>
	  
        </select>
    
    </td>
</tr>
<tr>
    <td width="21%" class="contenttab_internal_rows" valign='top'>&nbsp;<nobr><b>Particulars<?php echo REQUIRED_FIELD ?></b></nobr></td>
    <td width="1%" valign='top' ><b>:</b></td>
    <td width="78%" class="padding" valign='top'><textarea id="particulars" name="particulars" style="width:320px" class="inputbox" ></textarea></td>
</tr>
<tr>
    <td width="21%" class="contenttab_internal_rows" valign='top'>&nbsp;<nobr><b>Debit</b></nobr></td>
     <td width="1%" valign='top' ><b>:</b></td>
    <td width="78%" class="padding"><input type="text" id="debit" name="debit" class="inputbox" style="width:320px" maxlength="10"/></td>
</tr>
<tr>
    <td width="21%" class="contenttab_internal_rows">&nbsp;<nobr><b>Credit</b></nobr></td>
     <td width="1%" valign='top' ><b>:</b></td>
    <td width="78%" class="padding"><input type="text" id="credit" name="credit" class="inputbox" style="width:320px" maxlength="10"/></td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="right" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="editLedger();return false;" />
                    <input type="image" name="EditCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('EditDebitCredit'); return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td>
</tr>


</table>
</form>

<?php floatingDiv_End(); ?>


