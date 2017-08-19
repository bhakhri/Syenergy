<?php 
//This file creates Html Form output for Fine Collection Report
//
// Author :Jaineesh
// Created on : 15.04.09
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
									<form name="FineCollectionForm" action="" method="post" onSubmit="return false;">
										<table width="50%" align="center" border="0" >
											<tr>
												<td valign="center" align="right" ><b>Fine Collect Date Between : </b></td>
												<td valign="center" align="left" >
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
											<td colspan="1" class="content_title">Category Wise Fine Collection Report :</td>
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
		</table>


<?php 
//$History: listCollectionFineContents.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/05/09   Time: 6:23p
//Updated in $/LeapCC/Templates/Fine
//fixed bug nos.0002204, 0002202, 0002201, 0002203, 0002198, 0002197,
//0002185, 0002187, 0002200, 0002199, 0002183, 0002160, 0002156, 0002157,
//0002166, 0002165, 0002164, 0002163, 0002162, 0002161, 0002176, 0002181,
//0002180, 0002179, 0002178, 0002159, 0002158
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/07/09    Time: 4:02p
//Updated in $/LeapCC/Templates/Fine
//modification in query & files
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/07/09    Time: 10:31a
//Created in $/LeapCC/Templates/Fine
//new template of fine collection
//
//
?>