<?php
//-------------------------------------------------------
//  This File outputs the balancesheet to printer
//
//
// Author :Ajinder Singh
// Created on : 10-aug-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(BL_PATH.'/HtmlFunctions.inc.php');
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top" class="title">
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td valign="top" colspan="2">Accounts&nbsp;&raquo;&nbsp;Reports&nbsp;&raquo;&nbsp;Ledger</td>
				</tr>
			</table>
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
									<form name="ledgerForm" action="" method="post" onSubmit="return false;">
										<table align="center" border="0" cellpadding="0">
											<tr>
												<td  align="left" nowrap><strong>Ledger :</strong></td>
												<td >
													<select name='ledgerId' class='selectfield'>
													<?php 
														require_once(MODEL_PATH . '/Accounts/LedgerManager.inc.php');
														$ledgerManager = LedgerManager::getInstance();
														$ledgerArray = $ledgerManager->getLedger();
														echo HtmlFunctions::getInstance()->makeSelectBox($ledgerArray, 'ledgerId', 'ledgerName', $REQUEST_DATA['id']);
													?>
													</select>
												</td>
												<td colspan='1' class='' align="left">
												<strong>As On:</strong>&nbsp;
												<?php 
												require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
												if(isset($REQUEST_DATA['date']) and $REQUEST_DATA['date'] != '') {
													echo HtmlFunctions::getInstance()->datePicker('tillDate',$REQUEST_DATA['date']);
												}
												else {
													echo HtmlFunctions::getInstance()->datePicker('tillDate',date('Y-m-d'));
												}
												?>
												</td>
												<td align="center" colspan="4" >
													<span style="padding-right:10px" >
													&nbsp;&nbsp;<input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" />
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
											<td colspan="1" class="content_title">Ledger :</td>
											<td colspan="1" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>
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
											<td colspan="2" class="content_title" align="right"><input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;</td>
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
// $History: listLedgerDisplayContents.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 5:52p
//Created in $/LeapCC/Templates/Accounts/Ledger
//



?>