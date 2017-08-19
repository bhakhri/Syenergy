<?php 
//--------------------------------------------------------
//This file creates Html Form output for attendance report
//
// Author :Ajinder Singh
// Created on : 15-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
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
									<form name="attendanceMissedForm" action="" method="post" onSubmit="return false;">
										<table align="center" border="0" cellpadding="0">
											<tr>
												<td class="contenttab_internal_rows1" align="right"><nobr><b>Time Table:</b></nobr></td>
												<td class="padding"><select size="1" class="inputbox1" name="labelId" id="labelId" onChange="getTimeTableClasses();" onBlur="getTimeTableClasses();">
												<option value="" >Select</option>
												<?php
												  require_once(BL_PATH.'/HtmlFunctions.inc.php');
												  echo HtmlFunctions::getInstance()->getTimeTableLabelData();
												?>
												</select>
												</td>
												<td class="contenttab_internal_rows1" align="right">
													<strong>Degree:</strong> &nbsp;
												</td>
												<td class="padding">
													<select size="1" name="degree" id="degree" class="inputbox1" onChange="resetSubject();" style="width:250px">
														<option value="">Select</option>
													</select>
												</td>
												<td class="contenttab_internal_rows1" align="right">
													<strong>Subject:</strong>
												</td>
												<td class="padding">
													<select name="subjectId" class="selectfield" id="subjectId" style="width:100px" onChange="hideDetails();">
														<option value="">Select</option>
													</select>
												</td>
												<td align="center" >
													<span style="padding-right:10px" >
													<input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" />
												</td>
											</tr>
										</table>
									</form>
								</td>
							</tr>
						</table>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr id='nameRow' style='display:none;'>
								<td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
										<tr>
											<td colspan="1" class="content_title">Marks Status Report :</td>
											<td colspan="1" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;<input type="image"  name="printGroupSubmit" id='generateCSV' onClick="printCSV();return false;" value="printGroupSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" /></td>
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
											<td colspan="2" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;<input type="image"  name="printGroupSubmit" id='generateCSV' onClick="printCSV();return false;" value="printGroupSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" /></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<!-- form table ends -->
					</td>
				</tr>
			</table>
		</table>
<?php 
//$History:
?>
