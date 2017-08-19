<?php
//This file creates Html Form output "listAssignGroup" Module
//
// Author :Ajinder Singh
// Created on : 24-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			 <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<tr>
					<td valign="top" class="content">
					<form name="assignGroup" action="" method="post" onSubmit="return false;">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td valign="top" class="contenttab_border2">
										<table align="center" border="0">
											<tr>
												<td class="contenttab_internal_rows" colspan="1" align="right">
													<strong>Degree :</strong>
												</td>
												<td colspan="1">
													<select size="1" class="selectfield" name="degree" id="degree" style="width:250px" onBlur="hideResults();">
														<option value="">Select</option>
														<?php
															require_once(BL_PATH."/HtmlFunctions.inc.php");
															echo HtmlFunctions::getInstance()->getSessionClasses();?>
													</select>
												</td>
												<td  colspan="1" class="contenttab_internal_rows" align="right">
													<strong>Sort By :</strong>
												</td>
												<td colspan='1'>
													<select size="1" class="selectfield" name="sortBy" id="sortBy" style="width:100px">
														<option value="rollNo">RollNo</option>
														<option value="universityRollNo">U.RollNo</option>
														<option value="alphabetic">Alphabetic</option>
													</select>
												</td>
												<td align="center" valign="top" colspan="1" >
													<table border='0' cellspacing='0' cellpadding='0'>
													<tr>
														<td valign='top' colspan='1' class=''><input type="image" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" name="listBtn" value="Show List" onClick="return validateAddForm(this.form);return false;"/></td>
													</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td valign='top' colspan='5' class='contenttab_internal_rows'>
													<div id='groupRow'>

													</div>
												</td>
											</tr>
											<tr>
												<td valign='top' colspan='5' class='contenttab_internal_rows'>
													<B>Note: <br>Use Shift + Double-Click to select column </B>
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
											<td colspan="1" class="content_title">Assign Groups :</td>
											<td colspan="1" class="content_title" align="right"><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport();" title="Print" />&nbsp;<input type="image" name="printGroupSubmit" id='generateCSV' onClick="printCSV();return false;" value="printGroupSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" title="Export to Excel"/><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="saveSelectedStudents()" />&nbsp;</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr id='resultRow' style='display:none;'>
								<td colspan='1' class='contenttab_row'>
                                    <div id="scroll2" style="overflow:auto; width:1000px; height:410px; vertical-align:top;">
                                       <div id="resultsDiv" style="width:98%; vertical-align:top;"></div>
                                    </div>
								</td>
							</tr>
							<tr id='nameRow2' style='display:none;'>
								<td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
										<tr>
											<td colspan="2" class="content_title" align="right"><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport();" title="Print" />&nbsp;<input type="image" name="printGroupSubmit" id='generateCSV' onClick="printCSV();return false;" value="printGroupSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" title="Export to Excel"/><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="saveSelectedStudents()" />&nbsp;</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</form>
					</td>
				</tr>
			</table>
<?php floatingDiv_Start('groupSave','Group Assign'); ?>
<div id="groupSaveDiv" style="width:600px;height:400px;overflow:auto;"></div>
<?php floatingDiv_End(); ?>