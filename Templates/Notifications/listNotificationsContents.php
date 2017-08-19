<?php 
//---------------------------------------------------
//  This File outputs layout fot Notifications Module
//
// Author :Kavish Manjkhola
// Created on : 24-Mar-2011
// Copyright 2010-2011: syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			<?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>   
		</td>
	</tr>
	<tr>
		<td valign="top" colspan="2">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<tr>
					<td valign="top" class="content">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr height="30">
								<td class="contenttab_border" height="20" style="border-right:0px;">
									<?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
								</td>
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"></td></tr>
									<tr>
										<td class="contenttab_row" colspan="2" valign="top" ><div id="resultDiv"></div></td>
									</tr>
									<tr>
										<td align="right" colspan="2">
											<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
												<tr>
													<td class="content_title" valign="middle" align="right" width="20%">
														<!-- <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
														<input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" > -->
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>