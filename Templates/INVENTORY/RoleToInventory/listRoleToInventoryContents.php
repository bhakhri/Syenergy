<?php
//-------------------------------------------------------
// Purpose: to design the layout for Requistion Mapping.
//
// Author : Jaineesh
// Created on : (28 July 10)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
<tr>
	<td valign="top" colspan="2">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
			<tr>
				<td valign="top" class="content">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr height="30">
							<td class="contenttab_border" height="20" style="border-right:0px;">
								<span class="content_title">Requistion Approver Mapping Detail : </span>
								<?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
							</td>
								<!-- <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddGRN',360,250);blankValues();return false;"" />&nbsp;</td>-->
						</tr>
						<tr>
							<td class="contenttab_row" colspan="2" valign="top" >
								<table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr>
										<td valign="top">
											<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
												<tr>
													<td valign="top" class="content">
														<form action="" method="POST" name="listForm" id="listForm">
															<table width="100%" border="0" cellspacing="0" cellpadding="0">
																<tr>
																	<td class="contenttab_border2" colspan="2">
																		<table width="35%" border="0" cellspacing="0" cellpadding="0" align="center">
																			<tr>
																				<td height="10">
																				</td>
																			</tr>
																			<tr>
																				<td class="contenttab_internal_rows"><nobr><b>Requisition By Name</b></nobr>
																				</td>
																				<td class="padding"><nobr><b>:</b>&nbsp;<select size="1" class="inputbox"			name="roleId" id="roleId">
																					<option value="">Select</option>
																					<?php
																						require_once(BL_PATH.'/HtmlFunctions.inc.php');
																						echo HtmlFunctions::getInstance()->getRoleData('',"WHERE roleId >5");
																					?>
																					</select></nobr>																				</td>
																				<td  align="right" style="padding-right:5px">
																					<input type="image" name="imageField" id="imageField" title="Show List" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="getClasses(); return false;"/>
																				</td>
																			</tr>
																			<tr>
																				<td colspan="4" height="5px" class="contenttab_internal_rows"><B>Note: Select role whose users are to be mapped </B></td>
																			</tr>	
																		</table>
																	</td>
																</tr>
																<tr style="display:none" id="showTitle">
																	<td class="contenttab_border" height="20" colspan="2">
																	</td>
																</tr>
																<tr style="display:none" id="showData">
																	<td class="contenttab_row" valign="top" colspan="2"><div id="scroll" style="OVERFLOW:		auto; HEIGHT:294px; TEXT-ALIGN: justify;padding-right:10px" class="scroll"><div		id="results">  
																		</div> </div>	
																	</td>
																</tr>
																<tr>
																	<td height="10" colspan="2"></td>
																</tr>
																<tr  id = 'saveDiv1' style='display:none;'>
																	<td align="right">
																		<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="return validateAddForm();return false;" /></td>
																</tr>
															</table>
														</form>	
													</td>
												</tr>
											</table>
											<div id="results"></div>
										</td>
									</tr>
									<tr>
										<td align="right" colspan="2">
											<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
												<tr>
													<td class="content_title" valign="middle" align="right" width="20%">
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

<?php 
// $History: $
//
?>
