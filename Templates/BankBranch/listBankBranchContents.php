<?php 

//
//This file creates Html Form output in "Bank Branch" Module 
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
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
                <td valign="top">Setup&nbsp;&raquo;&nbsp;Fee Masters&nbsp;&raquo;&nbsp;Bank Branch Master</td>
                <td valign="top" align="right">
               <form action="" method="" name="searchForm">
                <input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                  &nbsp;
				  <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/search.gif" align="absbottom" style="margin-right: 5px;" onClick="sendReq(listURL,divResultName,searchFormName,'');return false;"/>
                  </form>
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
                        <td class="content_title">Bank Branch Detail : </td>
                        <td class="content_title" title="Add">
						<img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                        align="right" onClick="displayWindow('AddBankBranch',320,250);blankValues();return false;" />&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" ><div id="results">
				</div>
        </td>
    </tr>
	<tr><td class="content_title" align="right" ><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport();" title="Print" />&nbsp;<input type="image" name="printGroupSubmit" id='generateCSV' onClick="printCSV();return false;" value="printGroupSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" title="Export to Excel"/></td></tr>
    
    </table>
    </td>
    </tr>
    </table>
    <!--Start Add Div-->

<?php floatingDiv_Start('AddBankBranch','Add Bank Branch'); ?>
<form name="addBankBranch" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td class="contenttab_internal_rows" width="40%"><strong>Bank<?php echo REQUIRED_FIELD ?></strong></td>
			<td width="60%" class="padding">:&nbsp;
				<select name="bankId" id="bankId" style="width:145px">
					<option value="">select</option>
					<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getBankData();
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><strong>Branch Name<?php echo REQUIRED_FIELD ?></strong></td>
			<td width="79%" class="padding">:&nbsp;
			<input type="text" maxlength="100" id="branchName" name="branchName" style="width:142px" class="selectfield1"/>
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><strong>Abbr.<?php echo REQUIRED_FIELD ?></strong></td>
			<td width="79%" class="padding">:&nbsp;
			<input type="text" maxlength="10" id="branchAbbr" name="branchAbbr" style="width:142px" />
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><strong>Account Type<?php echo REQUIRED_FIELD ?></strong></td>
			<td width="79%" class="padding">:&nbsp;
			<input type="text" maxlength="10" id="accountType" name="accountType" style="width:142px" />
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><strong>Account Number<?php echo REQUIRED_FIELD ?></strong></td>
			<td width="79%" class="padding">:&nbsp;
			<input type="text" maxlength="15" id="accountNumber" name="accountNumber" style="width:142px" />
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><strong>Operator</strong></td>
			<td width="79%" class="padding">:&nbsp;
			<input type="text" maxlength="100" id="operator" name="operator" style="width:142px" />
			</td>
		</tr>
		<tr>
			<td height="5px" colspan="2"></td></tr>
			<tr>
			<td align="center" style="padding-right:10px" colspan="2">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
			<input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddBankBranch');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
			</td>
		</tr>
		<tr>
			<td height="5px" colspan="2"></td>
		</tr>
	</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Add Div-->
<?php floatingDiv_Start('EditBankBranch','Edit Bank Branch'); ?>
<form name="editBankBranch" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<input type="hidden" name="bankBranchId" id="bankBranchId" value="" /> 
		<tr>
			<td class="contenttab_internal_rows" width="40%"><strong>Bank<?php echo REQUIRED_FIELD ?></strong></td>
			<td width="60%" class="padding">:&nbsp;
				<select name="bankId" id="bankId" style="width:145px">
					<option value="">select</option>
					<?php
					require_once(BL_PATH.'/HtmlFunctions.inc.php');
					echo HtmlFunctions::getInstance()->getBankData();
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><strong>Branch Name<?php echo REQUIRED_FIELD ?></strong></td>
			<td width="79%" class="padding">:&nbsp;
			<input type="text" maxlength="100" id="branchName" name="branchName" style="width:142px" />
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><strong>Abbr.<?php echo REQUIRED_FIELD ?></strong></td>
			<td width="79%" class="padding">:&nbsp;
			<input type="text" maxlength="10" id="branchAbbr" name="branchAbbr" style="width:142px" />
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><strong>Account Type<?php echo REQUIRED_FIELD ?></strong></td>
			<td width="79%" class="padding">:&nbsp;
			<input type="text" maxlength="10" id="accountType" name="accountType" style="width:142px" />
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><strong>Account Number<?php echo REQUIRED_FIELD ?></strong></td>
			<td width="79%" class="padding">:&nbsp;
			<input type="text" maxlength="10" id="accountNumber" name="accountNumber" style="width:142px" />
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><strong>Operator</strong></td>
			<td width="79%" class="padding">:&nbsp;
			<input type="text" maxlength="100" id="operator" name="operator" style="width:142px" />
			</td>
		</tr>
		<tr>
			<td height="5px"></td>
		</tr>
		<tr>
			<td align="center" style="padding-right:10px" colspan="2">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
			<input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  
			onclick="javascript:hiddenFloatingDiv('EditBankBranch');return false;" />
			</td>
		</tr>
		<tr>
			<td height="5px"></td></tr>
		</tr>
</table>
</form>
<?php floatingDiv_End();
// $History: listBankBranchContents.php $
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/21/09    Time: 10:39a
//Updated in $/LeapCC/Templates/BankBranch
//fixed issues nos.0000511,  0001157, 0001154 , 0001153, 0001150
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/19/09    Time: 6:44p
//Updated in $/LeapCC/Templates/BankBranch
//Gurkeerat: resolved issue 1158,1149,1156
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/08/09    Time: 11:19a
//Updated in $/LeapCC/Templates/BankBranch
//fixed bug no.0000445
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/BankBranch
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 10/24/08   Time: 12:28p
//Updated in $/Leap/Source/Templates/BankBranch
//fixed small issue
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 8/28/08    Time: 4:53p
//Updated in $/Leap/Source/Templates/BankBranch
//fixed html errors
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 8/20/08    Time: 2:34p
//Updated in $/Leap/Source/Templates/BankBranch
//changed search button
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 8/14/08    Time: 6:17p
//Updated in $/Leap/Source/Templates/BankBranch
//Removed Width and Height on cancel button
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/02/08    Time: 11:12a
//Updated in $/Leap/Source/Templates/BankBranch
//changed account number length from 10 to 15 characters
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 7/28/08    Time: 3:07p
//Updated in $/Leap/Source/Templates/BankBranch
//Fixed bugs given by Pushpender sir
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 7/23/08    Time: 6:21p
//Updated in $/Leap/Source/Templates/BankBranch
//Fixed minor designing issue found during self-testing

?>
    <!--End: Div To Edit The Table-->