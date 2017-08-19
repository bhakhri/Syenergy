<?php 
//This file creates Html Form output for attendance report
//
// Author :Ajinder Singh
// Created on : 15-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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
									
										<table align="left" width="60%" border="0px" cellspacing="0" cellpadding="0" >
											<tr>
												<td class="contenttab_internal_rows"><nobr><strong>Time Table&nbsp;<?php echo REQUIRED_FIELD ?></strong></nobr></td>
                                        <td class="contenttab_internal_rows"><nobr><strong>:&nbsp;</strong></nobr></td>
                                        <td class="contenttab_internal_rows"><nobr><strong>
                                          <nobr><select size="1" class="inputbox1" name="labelId" id="labelId" style="width:170px" onChange="hideResults();" >
                                                    <option value="">Select</option> 
                                                    <?php 
                                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                        echo HtmlFunctions::getInstance()->getTimeTableLabelDate('','');?>
                                          </select></nobr>
                                       </td>   
                                       <td class="contenttab_internal_rows" align="left" nowrap style="padding-left:10px">
                                                    <strong>From</strong>  
                                                </td>
                                                <td class="contenttab_internal_rows" align="left" nowrap><strong>:&nbsp;</strong></td>
                                                <td class="contenttab_internal_rows" align="left" nowrap>
                                                    <?php 
                                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');        
                                                        echo HtmlFunctions::getInstance()->datePicker('fromDate',date('Y-m-d'));
                                                    ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                                </td>    
                                                <td class="contenttab_internal_rows" align="left" nowrap>         
                                                    <strong>To</strong>
                                                </td>
                                                <td class="contenttab_internal_rows" align="left" nowrap><strong>:&nbsp;</strong></td>
                                                <td class="contenttab_internal_rows" align="left" nowrap>
                                                    <?php 
                                                        require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
                                                        echo HtmlFunctions::getInstance()->datePicker('toDate',date('Y-m-d'));
                                                    ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                                </td>
										<td align="center" style="padding-left:20px">
											<input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" />
										</td>
<tr><br></tr>
<tr>

<td colspan="19" class="contenttab_internal_rows" style="padding-top:10px">
                                            
                                               <b><u>Please Note:</u></b><br>
                                                <font color="red"><b>* Percentage will be calculated on the basis of DAILY ATTENDANCE only.</font><br>
</td>
</tr>
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
											<td colspan="1" class="content_title">Stream Wise Attendance Report :</td>
											<td colspan="1" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;<input type="image" name="printGroupSubmit" id='generateCSV' onClick="printCSV();return false;" value="printGroupSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" title="Export to Excel"/></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr id='resultRow' style='display:none;'>
								<td colspan='1' class='contenttab_row'>
									<div id = 'resultsDiv'></div>
								</td>
							</tr>
							<tr id='nameRow2' style='display:none;'>
								<td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
										<tr>
											<td colspan="2" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;<input type="image" name="printGroupSubmit" id='generateCSV' onClick="printCSV();return false;" value="printGroupSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" title="Export to Excel"/></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</table>
</form>


