<?php
//-------------------------------------------------------
//  This File contains functions for marks transfer
//
//
// Author :Ajinder Singh
// Created on : 28-Dec-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
	<?php	 require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
		</td>
	</tr>
	<tr>
		<td valign="top" width='100%'>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<tr>
					<td valign="top" class="content" width="100%">
						<!-- form table starts -->
					<form name="transferMarksForm" action="" method="post" onSubmit="return false;">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
							<tr>
								<td valign="top" class="contenttab_row1">

										<table width="10%" align="center" border="0" cellspacing="0" cellpadding="0" id='mainTable'>
											<tr id='mainFormTR' style='display:'>
												<td  class="contenttab_internal_rows" nowrap>
                                                    <nobr><b>Time Table</b></nobr>
                                                </td>
                                                <td  class="contenttab_internal_rows" nowrap>
                                                    <strong>:</strong> &nbsp;
                                                </td>
												<td  class="contenttab_internal_rows" nowrap>
							<select size="1" style="width:220px" class="inputbox1" name="labelId" id="labelId" onBlur="getClassesForTransfer()">
												<option value="">Select</option>
												<?php
												  require_once(BL_PATH.'/HtmlFunctions.inc.php');
												  echo HtmlFunctions::getInstance()->getTimeTableLabelData();
												?>
												</select>
												</td>
												<td  class="contenttab_internal_rows" nowrap>
													&nbsp;&nbsp;<strong>Class</strong>
												</td>
                                                <td  class="contenttab_internal_rows" nowrap>
                                                    <strong>:</strong> &nbsp;
                                                </td>
												<td  class="contenttab_internal_rows" nowrap>
													<select  name="class1" id="class1" class="inputbox1" style="width:320px;">
														<option value="">Select</option>
														<?php
															require_once(BL_PATH.'/HtmlFunctions.inc.php');
														?>
													</select>
												</td>
												<td align="left" rowspan="1" valign="top" nowrap style="padding-left:15px">
													<input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/show_list.gif" onClick="return getClassSubjects();return false;" />
												</td>
											</tr>
											<tr style='display:;'>
                                                <td  class="contenttab_internal_rows" nowrap colspan="2">
                                                    <b><span id='currentSelectionTR'></span></b>
                                                </td>
                                                <td  class="contenttab_internal_rows" nowrap>
                                                   <span id='currentSelectionTR2'></span>
                                                </td>
                                                <td  class="contenttab_internal_rows" nowrap colspan="2">
                                                    <b><span id='currentSelectionTR3'></span></b>
                                                </td>
                                                <td  class="contenttab_internal_rows" nowrap>
                                                    <span id='currentSelectionTR4'></span>
                                                </td>
                                                <td  class="contenttab_internal_rows" nowrap >
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><span id='currentSelectionTR5'></span></b>
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
											<td colspan="1" class="content_title"><span id='headingDiv'></span></td>
											<td colspan="1" class="content_title" align="right"></td>
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
											<td colspan="2" class="content_title" align="right"></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<!-- form table ends -->
						</form>
					</td>
				</tr>
			</table>
		</table>
<?php floatingDiv_Start('testTypeDiv','Evaluation Scheme','',' '); ?>
<form name='testTypeForm' onSubmit='return false;'>
	<div id='testTypeDivDetails' style='width:700px;height:400px;overflow:auto;'>
	</div>
</form>
<?php floatingDiv_End(); ?>
<?php
// for VSS
//$History: listTransferInternalMarksAdvanced.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 12/28/09   Time: 4:43p
//Created in $/LeapCC/Templates/TransferMarksAdvanced
//initial checkin for advanced marks transfer
//
?>
