<?php
//-------------------------------------------------------
// Purpose: To get values of hostel from the database
//
// Author : DB
// Created on : (11.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ApproveGeneratedPO');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
UtilityManager::headerNoCache();
    
if(trim($REQUEST_DATA['poId'] ) != '') {
    require_once(INVENTORY_MODEL_PATH . "/POManager.inc.php");
	 require_once(BL_PATH.'/HtmlFunctions.inc.php');
    $foundArray = POManager::getInstance()->getPODetail($REQUEST_DATA['poId']); 
	
	$cnt = count($foundArray);

define('REQUIRED_FIELD', '<span class="redColorBig">* </span>');


	$string='<form name="EditPO" action="" method="post">
	         <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
			 <tr><td height="5px" ></td></tr>
			<tr>
			 <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>P.O. No.'.REQUIRED_FIELD.'</b></nobr></td>
			 <td class="padding">:&nbsp;<input name="poNo" id="poNo" class="inputbox" value="'.$foundArray[0]['poNo'].'" maxlength="40" readonly="readonly"/></td>
			</tr>
			<tr>
			 <td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Party Code'.REQUIRED_FIELD.'</b></nobr></td>
			 <td class="padding">:&nbsp;<select size="1" class="selectfield" name="partyCode" id="partyCode" onchange="getPartyName(this.value);">
			 <option value="">Select</option>';
	$string .= HtmlFunctions::getInstance()->getPartyData($foundArray[0]['partyId']);
				
	$string .= '</select></td>
			</tr>
			<tr>
			<td class="contenttab_internal_rows">&nbsp;&nbsp;&nbsp;<nobr><b>Party Name'.REQUIRED_FIELD.'</b></nobr></td>
			<td class="padding">:&nbsp;<input type="text" name="partyname" id="partyName" class="inputbox" value="'.$foundArray[0]['partyName'].'" maxlength="100" readonly="readonly"/><input type="hidden" name="poId" id="poId" class="inputbox" value="'.$foundArray[0]['poId'].'" maxlength="100" readonly="readonly"/></td>
			</tr></table>
	
	         <table class="padding" width="100%" border="0"  id="anyid1_add" cellspacing="1" cellpadding="0">';
    $string .='<tr class="rowheading searchhead_text"><td><b>Sr. No</b></td><td>Indent No</td><td>Item Category</td><td>Item Code</td><td>Quantity Required</td><td>Rate</td><td>Amount</td><td>Action</td></tr>';
    
	if($cnt==0){
		echo $string.'<tr><td colspan="8">'.NO_DATA_FOUND.'</td></tr></table></form>';
		die;
	}
    $totalAmt=0;
    for($i=0;$i<$cnt;$i++) {
	   $string .= '<tr id="tr_'.$foundArray[$i]['itemId'].'" name="itemRow">
					<td>'.($i+1).'</td>
					<td align="left"><input type="text" name="indentNo[]" id="indentNo[$i]" style = "width:70px;" class="inputbox" value="'.$foundArray[$i]['indentNo'].'"  readonly="readonly"/></td>
					<td><input type="text" name="itemCatagory[]" id="itemCatagory$i" style = "width:87px;" class="inputbox" value="'.$foundArray[$i]['categoryCode'].'" maxlength="10"  readonly="readonly"/></td>
					<td><input type="text" name="itemCode[]" id="itemCode[]" style = "width:73px;" class="inputbox" value="'.$foundArray[$i]['itemCode'].'" maxlength="10"  readonly="readonly"/></td>
					<td><input type="text" name="quantityRequired[]" id="quantityRequired['.$i.']" style = "width:115px;" class="inputbox" value="'.$foundArray[$i]['quantityRequired'].'" maxlength="10" onchange="updateTotal();" style="text-align:right"/></td>
					<td><input type="text" name="rate[]" id="rate$i" style = "width:50px;" class="inputbox" style="text-align:right" value="'.$foundArray[$i]['rate'].'" maxlength="10" onchange="updateTotal();"  /></td>
					<td><input type="text" name="amount[]" id="amount$i" style = "width:90px;" class="inputbox"  value="'.$foundArray[$i]['amount'].'" maxlength="10" style="text-align:right" readonly="readonly" /><input type="hidden" name="itemId[]" id="itemId[]" style = "width:90px;" class="inputbox"  value="'.$foundArray[$i]['itemId'].'" maxlength="10" style="text-align:right" readonly="readonly" /</td>
					<td align="center"><a href="#" title="Cancelled"><img src="'.IMG_HTTP_PATH.'/cancelled.gif" style="height:15px;" alt="Cancelled" onclick="cancellGeneratedPO('.$foundArray[0]['poId'].','.$foundArray[$i]['itemId'].');return false;" border="0"></a>&nbsp;<td>
				  </tr>';
        /* $totalAmt +=$foundArray[$i]['amount'];*/
			    
	}

		/*$grandTotal= $totalAmt - $foundArray[0]['discount'];
		$VAT =  ($foundArray[0]['vat']/ 100) * $grandTotal ;
		$grandTotal += $VAT;
		$grandTotal += $foundArray[0]['aditionalCharges'];*/


$string .= '<tr ><td class="contenttab_internal_rows1"  colspan="8" align="right" ><b>Total Amount :<b>&nbsp;<input type="text" name="totalAmount" id="totalAmount" class="inputbox" value="'.$foundArray[0]['grandTotal'].'" readonly="readonly" style="text-align:right"/></td>
	</tr>
	<tr><td class="contenttab_internal_rows1" align="right" colspan="8"><b>(-)Discount :<b>&nbsp;<input type="text" name="Discount" id="Discount" class="inputbox" value="'.$foundArray[0]['discount'].'" style="text-align:right" onchange="updateTotal();"/></td>
	</tr>
	<tr><td class="contenttab_internal_rows1" align="right" colspan="8"><b>(+)VAT(%) :<b>&nbsp;<input type="text" name="vat" id="vat" class="inputbox" value="'.$foundArray[0]['vat'].'"  style="text-align:right" onchange="updateTotal();"/></td>
	</tr>
	<tr><td class="contenttab_internal_rows1" align="right" colspan="8"><b>(+)Aditional Charges :<b>&nbsp;<input type="text" name="aditionalCharges" id="aditionalCharges" class="inputbox" value="'.$foundArray[0]['aditionalCharges'].'" style="text-align:right" onchange="updateTotal();"/><td></tr>
	<tr><td class="contenttab_internal_rows1" align="right" colspan="8"><b>Grand Total :<b>&nbsp;<input type="text" name="grandTotal" id="grandTotal" class="inputbox" value="'.$foundArray[0]['netAmount'].'" readonly="readonly" style="text-align:right"/></td>
	</tr></table></form>';


echo $string;
}
else{
    echo 0;
}

?>