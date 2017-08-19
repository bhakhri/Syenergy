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
?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">
				</td>
                <td valign="top" align="right">
                  </td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Vouchers of <?php echo $sessionHandler->getSessionVariable('CompanyName').' ('.$sessionHandler->getSessionVariable('FinancialYear').')'; ?> : </td>
                        <td class="content_title" title="Add"></td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
					<div id="results">
						<?php
							$module = 'Voucher Entry';

							if (!is_array($_SESSION['drillLink'])) {
								$_SESSION['drillLink'] = Array();
							}
							if (!stristr($_SESSION['userTrack'], $module)) {
								if(!empty($_SESSION['userTrack'])) {
									$_SESSION['userTrack'] .= ',';
								}
								$_SESSION['userTrack'] .= $module;
								$_SESSION['drillLink'][] = "Voucher";
							}

							echo '<table width="100%"><tr><td valign="top" class="title">';
							$thisValueFound = false;
							$i = 0;
							foreach($_SESSION['drillLink'] as $record) {
								if ($thisValueFound === true) {
									unset($_SESSION['drillLink'][$i]);
								}
								else {
									echo '&nbsp;&raquo;&nbsp;'.$record;
								}
								if ($record == $drillLink) {
									$thisValueFound = true;
								}
								$i++;
							}
							echo '</td></tr></table>';
						?>
						<table border='0' cellspacing='0' cellpadding='0' width='100%'>
							<tr>
								<td valign='top' colspan='1' class='' align='left'  width='50%'><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/receipt.gif" onClick='setVoucherType("<?php echo RECEIPT;?>");'/><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/payment.gif" onClick='setVoucherType("<?php echo PAYMENT;?>");'/><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/journal.gif" onClick='setVoucherType("<?php echo JOURNAL;?>");'/><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/contra.gif" onClick='setVoucherType("<?php echo CONTRA;?>");'/></td>
								<td valign='top' colspan='1' class='' align='right'><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick='printReport();'/></td>
							</tr>
						</table>
						
						
					<form name='voucherForm' method='post' action='' onsubmit='return false;' style='display:inline;'>
						<table border='0' cellspacing='0' cellpadding='0'>
							<tr>
								<td class="highlightPermission" width='20%' align='center'>
									<span id='voucherTypeSpan' style='font-weight:bold;'>
									<?php
									$voucherNo = '';
									$voucherDate = date('d-m-Y');
									$narration = '';
									$voucherId = '';
									$voucherTypeId = '';
									if (isset($REQUEST_DATA['id']) and $REQUEST_DATA['id'] != '') {
										$voucherId = $REQUEST_DATA['id'];
										require_once(MODEL_PATH . '/Accounts/VoucherManager.inc.php');
										$voucherManager = VoucherManager::getInstance();
										$voucherMasterDetailArray = $voucherManager->getVoucherMasterDetails($voucherId);
										$voucherDate = date('d-m-Y',strtotime($voucherMasterDetailArray[0]['voucherDate']));
										$voucherTypeId = $voucherMasterDetailArray[0]['voucherTypeId'];
										$voucherType = $voucherTypeArray[$voucherTypeId];
										$voucherNo = $voucherMasterDetailArray[0]['voucherNo'];
										$narration = $voucherMasterDetailArray[0]['narration'];
										echo $voucherType.' Voucher';
										$voucherTransDetailArray = $voucherManager->getVoucherTransDetails($voucherId);
									}
									else {
										echo 'Receipt Voucher';
									}
									?>
									</span>
								</td>
								<td class="contenttab_internal_rows" style='width:550px;'>&nbsp;
									Voucher No:<input type='text' name='voucherNo' value='<?php echo $voucherNo;?>' class='inputbox' onkeypress="return sendKeys('voucherDate',event);"/>&nbsp;
									Date:<input type='text' name='voucherDate' value='<?php echo $voucherDate?>' class='inputbox' onkeypress="return sendKeys('drcr_0',event);" style='width:100px;'/>&nbsp;(dd-mm-yyyy)
								</td>
							</tr>
						</table>
						<table border='0' cellspacing='1' cellpadding='0' width='100%'>
							<tr class="rowheading">
								<td  colspan='2' class='searchhead_text' width='80%' align='center'>Particulars</td>
								<td  colspan='1' class='searchhead_text' width='10%' align='right' style='padding-right:15px;'>Debit</td>
								<td  colspan='1' class='searchhead_text' width='10%'  align='right' style='padding-right:20px;'>Credit</td>
							</tr>
						</table>
						<table border='0' cellspacing='1' cellpadding='1' width='100%'>
							<?php
							$i = 0;
							$debitTotal = '0';
							$creditTotal = '0';
							$rowValue = 1;
							while ($i < VOUCHER_ENTRIES_ON_PAGE) {
								$selectedDr = '';
								$selectedCr = '';
								$voucherLedger = '';
								$debitAmount = '';
								$creditAmount = '';
								$debitEnabled = 'disabled';
								$creditEnabled = 'disabled';
								if (isset($voucherTransDetailArray[$i])) {
									$voucherLedger = $voucherTransDetailArray[$i]['ledgerName'];
									if ($voucherTransDetailArray[$i]['drAmount'] > $voucherTransDetailArray[$i]['crAmount']) {
										$selectedDr = 'selected';
										$debitAmount = $voucherTransDetailArray[$i]['drAmount'];
										$debitTotal += $debitAmount;
										$debitEnabled = '';
									}
									else {
										$selectedCr = 'selected';
										$creditAmount = $voucherTransDetailArray[$i]['crAmount'];
										$creditTotal += $creditAmount;
										$creditEnabled = '';
									}
								}
								$bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
								$rowValue = $rowValue == 0 ? 1 : 0;
							?>
								<tr <?php echo $bg;?> style='height:2px;'>
									<td  colspan='1' class='' width='5%'>
										<select name='drcr_<?php echo $i;?>' class='selectfield' style="width:43px" onkeydown="return sendKeys('voucherLedgers_<?php echo $i;?>',event);"
										onFocus='document.getElementById("voucherLedgers_<?php echo $i;?>").className="selectfieldNoBorder<?php echo $rowValue;?>";'>
											<option value='dr' <?php echo $selectedDr;?>>Dr</option>
											<option value='cr' <?php echo $selectedCr;?>>Cr</option>
										</select>
									</td>
									<td  colspan='1' class='' width='75%'>
										<input autocomplete="off" type='text' name='voucherLedgers_<?php echo $i;?>' id='voucherLedgers_<?php echo $i;?>' value='<?php echo $voucherLedger;?>' onFocus='setAutoSuggest(this.name,"<?php echo $i;?>");' class='selectfieldNoBorder<?php echo $rowValue;?>' style="width:732px" onKeyUp="return false;"/>
									</td>
									<td  colspan='1' class='' width='10%'>
										<input type='text' name='debit_<?php echo $i;?>' value='<?php echo $debitAmount;?>' <?php echo $debitEnabled;?> autocomplete="off" onFocus='fillBalance("debit",this);document.getElementById("voucherLedgers_<?php echo $i;?>").className="selectfieldNoBorder<?php echo $rowValue;?>";' onBlur='this.className="selectfieldNoBorder<?php echo $rowValue;?>";checkTotals(<?php echo $i;?>);' class='selectfieldNoBorder<?php echo $rowValue;?>' style="width:93px;text-align:right;" onkeydown="return sendKeys('drcr_<?php echo $i+1;?>',event);"/>
									</td>
									<td  colspan='1' class='' width='10%'>
										<input type='text' name='credit_<?php echo $i;?>' autocomplete="off" value='<?php echo $creditAmount;?>' <?php echo $creditEnabled;?> onFocus='fillBalance("credit",this);document.getElementById("voucherLedgers_<?php echo $i;?>").className="selectfieldNoBorder<?php echo $rowValue;?>";' onBlur='this.className="selectfieldNoBorder<?php echo $rowValue;?>";checkTotals(<?php echo $i;?>);' class='selectfieldNoBorder<?php echo $rowValue;?>' style="width:90px;text-align:right;" onkeydown="return sendKeys('drcr_<?php echo $i+1;?>',event);"/>
									</td>
								</tr>
							<?php
								$i++;
							}
							if (floatval($debitTotal) == intval($debitTotal)) {
								$debitTotal = $debitTotal.'.00';
							}
							if (floatval($creditTotal) == intval($creditTotal)) {
								$creditTotal = $creditTotal.'.00';
							}

							?>
						</table>
						<table border='0' cellspacing='1' cellpadding='1' width='100%'>
							<tr class="rowheading">
								<td  colspan='2' class='searchhead_text' width='80%' align='right' style='padding-right:15px;'>Total</td>
								<td  colspan='1' class='searchhead_text' width='10%' align='right'><span id='debitTotalSpan' style='font-weight:bold;'><?php echo $debitTotal;?></span></td>
								<td  colspan='1' class='searchhead_text' width='10%' align='right'><span id='creditTotalSpan' style='font-weight:bold;'><?php echo $creditTotal;?></span></td>
							</tr>
						</table>
						<table border='0' cellspacing='1' cellpadding='0' width='100%'>
							<tr>
								<td  colspan='1' width='6%' align='left' class="contenttab_internal_rows">Narration :</td>
								<td colspan='2' align='left' class="contenttab_internal_rows padding"><input type='hidden' name='voucherId' value='<?php echo $voucherId;?>' /><input type='hidden' name='voucherType' value='<?php echo $voucherTypeId;?>' />
								<input type='text' class='selectfield' name='narration' value='<?php echo $narration;?>' style='width:830px;' onkeydown="return sendKeys('imageFieldSave',event);"/></td>
								<td  colspan='1' class='' width='6%' align='left' class="contenttab_internal_rows"><input type="image" name="imageFieldSave" src="<?php echo IMG_HTTP_PATH;?>/save1.gif" onClick='validateAddForm();'/></td>
								</td>
							</tr>
						</table>
					</form>
					</div>
				</td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>
<?php
// $History: listVoucherContents.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 5:52p
//Created in $/LeapCC/Templates/Accounts/Voucher
//



?>