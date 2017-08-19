<?php 
//This file creates Html Form output for Fine Collection Report
//
// Author :Jaineesh
// Created on : 15.04.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<form name="allDetailsForm" action="" method="post" onSubmit="return false;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			 <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>   
		</td>
	</tr>
	<tr>
		<td valign="top">
			<table width="100%" border="0" cellspacing="0" cellpadding="5" height="405">
				<tr>
					<td valign="top" class="content">
						<!-- form table starts -->
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
							<tr>
							   <td class="contenttab_border2" align="center">
							     <div style="height:15px"></div>  
							     <?php require_once(TEMPLATES_PATH . "/listFeeFineReportContents.php");?>   
							   </td>
							</tr>  		
							<tr id='nameRow' style='display:none;'>
								<td class="" height="20">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
										<tr>
											<td colspan="1" class="content_title">Student Wise Fine Collection Summary Report :</td>
											<td colspan="1" class="content_title" align="right">
												<input type="image" name="cleaningHistoryPrint" value="cleaningHistoryPrint" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
											</td>
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
											
											<!--<td colspan="2" class="content_title" align="right"><input type="image" name="cleaningHistoryPrint" value="cleaningHistoryPrint" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>-->
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<!-- form table ends -->
						
					</td>
				</tr>
			</table>
		</form>
		</table>
