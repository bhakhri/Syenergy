<?php 

//
//This file creates Html Form output in "Bank" Module 
//
// Author :Ajinder Singh
// Created on : 19-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>    <form onSubmit="sendReq(listURL,divResultName,searchFormName,'');return false;" name="searchForm">  
<?php
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
                                    <?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
                                </td>

                   		<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddBank',300,200);blankValues();return false;" />&nbsp;</td></tr>
                            <tr>
                                <td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
                            </tr>
                            <tr>
                                <td align="right" colspan="2">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                                        <tr>
                                            <td class="content_title" valign="middle" align="right" width="20%">
                                                <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                                                <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" >
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
    <!--Start Add Div-->
 </form>
<?php floatingDiv_Start('AddBank','Add Bank'); ?>
<form name="addBank" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td class="contenttab_internal_rows" nowrap width="2%"><strong>&nbsp;&nbsp;Bank Name<?php echo REQUIRED_FIELD ?></strong></td>
            <td class="contenttab_internal_rows" nowrap width="2%"><strong>:&nbsp;&nbsp;</strong></td>
			<td width="60%" nowrap class="padding">
			<input type="text" maxlength="100" id="bankName"  style="width:200px" name="bankName" class="inputbox" style="width:142px" />
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><strong>&nbsp;&nbsp;Abbr.<?php echo REQUIRED_FIELD ?></strong></td>
            <td class="contenttab_internal_rows" width="2%"><strong>:&nbsp;&nbsp;</strong></td>
			<td class="padding">
			<input type="text" maxlength="10" id="bankAbbr"  style="width:200px" name="bankAbbr" class="inputbox" style="width:142px" />
			</td>
		</tr>
        <tr>
            <td class="contenttab_internal_rows" valign="top" ><strong>&nbsp;&nbsp;Address</strong></td>
            <td class="contenttab_internal_rows" valign="top" width="2%"><strong>:&nbsp;&nbsp;</strong></td>
            <td class="padding" valign="top">
                <textarea class="inputbox" cols="56" rows="2" style="width:200px"  id="bankAddress" name="bankAddress" ></textarea>
            </td>
        </tr>
		<tr>
			<td height="5px"></td></tr>
			<tr>
			<td align="center" style="padding-right:10px" colspan="3">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
			<input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddBank');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
			</td>
		</tr>
		<tr>
			<td height="5px"></td>
		</tr>
	</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Add Div-->
<?php floatingDiv_Start('EditBank','Edit Bank'); ?>
<form name="editBank" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
			<input type="hidden" name="bankId" id="bankId" value="" /> 
			<tr>
                <td class="contenttab_internal_rows" nowrap width="2%"><strong>&nbsp;&nbsp;Bank Name<?php echo REQUIRED_FIELD ?></strong></td>
                <td class="contenttab_internal_rows" nowrap width="2%"><strong>:&nbsp;&nbsp;</strong></td>
                <td width="60%" nowrap class="padding">
                <input type="text" maxlength="100" id="bankName"  style="width:200px" name="bankName" class="inputbox" style="width:142px" />
                </td>
            </tr>
            <tr>
                <td class="contenttab_internal_rows"><strong>&nbsp;&nbsp;Abbr.<?php echo REQUIRED_FIELD ?></strong></td>
                <td class="contenttab_internal_rows" width="2%"><strong>:&nbsp;&nbsp;</strong></td>
                <td class="padding">
                <input type="text" maxlength="10" id="bankAbbr"  style="width:200px" name="bankAbbr" class="inputbox" style="width:142px" />
                </td>
            </tr>
            <tr>
                <td class="contenttab_internal_rows" valign="top" ><strong>&nbsp;&nbsp;Address</strong></td>
                <td class="contenttab_internal_rows" valign="top" width="2%"><strong>:&nbsp;&nbsp;</strong></td>
                <td class="padding" valign="top">
                    <textarea class="inputbox" cols="56" rows="2" style="width:200px"  id="bankAddress" name="bankAddress" ></textarea>
                </td>
            </tr>
			<tr>
				<td height="5px"></td>
			</tr>
			<tr>
				<td align="center" style="padding-right:10px" colspan="3">
				<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
				<input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  
				onclick="javascript:hiddenFloatingDiv('EditBank');return false;" />
				</td>
			</tr>
			<tr>
				<td height="5px"></td></tr>
			</tr>
	</table>
</form>
    <?php floatingDiv_End(); ?>
        

        
<!--Start Add Bank Branch Div-->
<?php floatingDiv_Start('AddBankBranch','Add Bank Branch'); ?>
<form name="addBankBranch" action="" method="post">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
        <tr>
            <td class="contenttab_internal_rows" width="40%"><strong>Bank<?php echo REQUIRED_FIELD ?></strong></td>
            <td width="60%" class="padding">:&nbsp;
                <select name="bankId" id="bankId" style="width:145px">
                    <option value="">select</option>
                    <?php
                    //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                    //echo HtmlFunctions::getInstance()->getBankData();
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
            <td class="contenttab_internal_rows"><strong>Account Number</strong></td>
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
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm1(this.form,'Add');return false;" />
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
            <td class="contenttab_internal_rows"><strong>Account Number</strong></td>
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
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm1(this.form,'Edit');return false;" />
            <input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  
            onclick="javascript:hiddenFloatingDiv('EditBankBranch');return false;" />
            </td>
        </tr>
        <tr>
            <td height="5px"></td></tr>
        </tr>
</table>
</form>
<?php floatingDiv_End(); ?>


<!--Start Bank Branch  Div-->
<?php floatingDiv_Start('divBankBranchInformation','Bank Branch Information','',''); ?>
<form name="divForm11" id="divForm11" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>    
        <td width="95%" align="center" valign="top">                          
          <div  style="overflow:auto; width:810px; height:400px; vertical-align:top;">
            <div id="resultInfo" style="width:810px; height:400px; vertical-align:top;"></div>
          </div>  
        </td>
    </tr>
</table>
</form> 
<?php floatingDiv_End(); ?>


<?php
// $History: listBankContents.php $
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 10-03-26   Time: 1:17p
//Updated in $/LeapCC/Templates/Bank
//updated with all the fees enhancements
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 12/29/09   Time: 12:58p
//Updated in $/LeapCC/Templates/Bank
//Merged Bank & BankBranch module in single module
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 9/02/09    Time: 10:32a
//Updated in $/LeapCC/Templates/Bank
//fixed bug no.0001389
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/21/09    Time: 10:39a
//Updated in $/LeapCC/Templates/Bank
//fixed issues nos.0000511,  0001157, 0001154 , 0001153, 0001150
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/20/09    Time: 10:21a
//Updated in $/LeapCC/Templates/Bank
//fixed bug nos.0001145,  0001127, 0001126, 0001125, 0001119, 0001101,
//0001110
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/08/09    Time: 1:16p
//Updated in $/LeapCC/Templates/Bank
//fixed issues nos.0000356,0000357,0000444,0000445
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Bank
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 8/28/08    Time: 4:53p
//Updated in $/Leap/Source/Templates/Bank
//fixed html errors
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 8/20/08    Time: 2:34p
//Updated in $/Leap/Source/Templates/Bank
//changed search button
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 8/14/08    Time: 6:17p
//Updated in $/Leap/Source/Templates/Bank
//Removed Width and Height on cancel button
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 7/28/08    Time: 3:07p
//Updated in $/Leap/Source/Templates/Bank
//Fixed bugs given by Pushpender sir
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 7/23/08    Time: 6:16p
//Updated in $/Leap/Source/Templates/Bank
//Fixed minor designing issue found during self-testing
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 7/23/08    Time: 12:51p
//Updated in $/Leap/Source/Templates/Bank
//applied maxlength check on textfields
	?>
    <!--End: Div To Edit The Table-->
