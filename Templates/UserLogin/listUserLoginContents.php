<?php 
//This file creates Html Form output for Fine Collection Report
//
// Author :Gurkeerat Sidhu
// Created on : 29.12.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
									<form name="UserLoginForm" id="UserLoginForm" action="" method="post" onSubmit="return false;">
                                    <input type="hidden" name="listView" id="listView" value="" />
										<table width="50%" align="center" border="0" >
                                        <tr> 
                                             <td nowrap><b>Not Logged In Report</b></td>
                                             <td><b>:</b></td>
                                             <td><input type="checkbox" name="notLoggedIn" id="notLoggedIn" onchange="vanishData();" onclick="vanishData1();"></td> </tr>
                                            <tr> <td colspan="4" nowrap>(If checked it will show list of students not logged in to application under specified date)</td>
                                             </tr>
                                         <tr>
                                                <td valign="center" align="left" ><b>Login Report Type  </b></td>
                                                <td><b>:</b></td>
                                                <td class="contenttab_internal_rows1" nowrap>
                                                <input type="radio" name="loginDetail" id="normalReport" checked="checked" onchange="vanishData();">Consolidated
                                                <input type="radio" name="loginDetail" id="detailedReport" onchange="vanishData();">Detailed
                                                </td>
                                                
                                             </tr>
                                             
											<tr>
												<td valign="center" align="left" nowrap><b>Login Date Between  </b></td>
                                                <td><b>:</b></td>
												<td valign="center" align="left" nowrap>
													<?php
													   require_once(BL_PATH.'/HtmlFunctions.inc.php');
													   echo HtmlFunctions::getInstance()->datePicker('startDate',date("Y-m-d", mktime(0, 0, 0, date('m'), date('d')-30, date('Y'))));
													?>
													&nbsp;<b>And</b>
													<?php
														   require_once(BL_PATH.'/HtmlFunctions.inc.php');
														   echo HtmlFunctions::getInstance()->datePicker('toDate',date('Y-m-d'));
													?>
												</td>
												<td>
													<span style="padding-right:10px" >
													<input type="image" name="fineCollectionReport" value="fineCollectionReport" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" />
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
											<td colspan="1" class="content_title">User Login Report :</td>
											<!--<td colspan="1" class="content_title" align="right"><input type="image" name="cleaningHistoryPrint" value="cleaningHistoryPrint" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>-->
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
                                        <td colspan="2" class="content_title" align="right">
											<input type="image" name="imageField"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;
                                           
                                            <input type="image" name="imageField"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();return false;"/>
											<!--<td colspan="2" class="content_title" align="right"><input type="image" name="cleaningHistoryPrint" value="cleaningHistoryPrint" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>-->
										</td>
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


