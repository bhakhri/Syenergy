<?php 
//-------------------------------------------------------
//  This File outputs the balancesheet to printer
//
//
// Author :Ajinder Singh
// Created on : 10-aug-2009
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
                <td valign="top">Accounts&nbsp;&raquo;&nbsp;Setup&nbsp;&raquo;&nbsp;Company</td>
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
                        <td class="content_title">
						Company Masters
						</td>
                        <td class="content_title" title="Add">
						<img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                        align="right" onClick="displayWindow('Company',360,250);setMode('Add');blankValues();" />&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" ><div id="results"></div>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>
    <!--Start Add Div-->

<?php floatingDiv_Start('Company','Company'); ?>
<form name="companyForm" action="" method="post" onsubmit='return false;'>
<input type='hidden' name='companyId' value='' />
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td class="padding contenttab_internal_rows" width="44%"><strong>Company Name<?php echo REQUIRED_FIELD; ?></strong></td>
			<td width="56%" class="padding">:&nbsp;
			<input type="text" class="inputbox" maxlength="100" id="companyName" name="companyName" style="width:170px" valign='abstop'/>
			</td>
		</tr>
		<tr>
			<td valign="top" class="padding contenttab_internal_rows"><strong>Address<?php echo REQUIRED_FIELD; ?></strong></td>
			<td width="79%" class="padding">:&nbsp;
			<textarea class='inputbox' rows='4' name='address' cols='20' style='width:168px;vertical-align:top;'></textarea>
			</td>
		</tr>
		<tr>
			<td class="padding contenttab_internal_rows"><strong>Email<?php echo REQUIRED_FIELD; ?></strong></td>
			<td width="79%" class="padding">:&nbsp;
			<input type="text" class="inputbox" maxlength="100" id="email" name="email" style="width:170px" />
			</td>
		</tr>
		<tr>
			<td class="padding contenttab_internal_rows"><strong>Phone<?php echo REQUIRED_FIELD; ?></strong></td>
			<td width="79%" class="padding">:&nbsp;
			<input type="text" class="inputbox" maxlength="100" id="phone" name="phone" style="width:170px" />
			</td>
		</tr>
		<tr>
			<td class="padding contenttab_internal_rows"><strong>Financial Year starts from<?php echo REQUIRED_FIELD; ?></strong></td>
			<td width="79%" class="padding">:&nbsp;
			<?php 
				require_once(BL_PATH.'/HtmlFunctions.inc.php');
				echo HtmlFunctions::getInstance()->datePicker('fyearFrom',date('Y-m-d'));
			?>
			</td>
		</tr>
		<tr>
			<td height="5px" colspan="2"></td></tr>
			<tr>
			<td align="center" style="padding-right:10px" colspan="2">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form);return false;" />
			<input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('Company');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
			</td>
		</tr>
		<tr>
			<td height="5px" colspan="2"></td>
		</tr>
	</table>
</form>
<?php floatingDiv_End(); ?>
<?php
// $History: listCompanyContents.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 5:52p
//Created in $/LeapCC/Templates/Accounts/Company
//



?>