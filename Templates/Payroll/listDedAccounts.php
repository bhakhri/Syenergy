<?php 

//
//This file creates Html Form output in "Payroll" Module Deduction accounts master 
//
// Author :Abhiraj
// Created on : 14-April-2010
// Copyright 2009-20010: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>    <form onSubmit="sendReq(listURL,divResultName,searchFormName,'');return false;" name="searchForm">  
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">Setup&nbsp;&raquo;&nbsp;Payroll&nbsp;&raquo;&nbsp;Deduction Account Master</td>
                <td valign="top" align="right">
               
                <input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                  &nbsp;
				  <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/search.gif" align="absbottom" style="margin-right: 5px;" onClick="sendReq(listURL,divResultName,searchFormName,'');return false;"/>
				
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
                        <td class="content_title">Deduction Account Details : </td>
                        <td class="content_title" title="Add">
						<img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                        align="right" onClick="displayWindow('AddAccount',270,250);blankValues();return false;" />&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
               <td class="contenttab_row" valign="top" ><div id="results"></div>
        </td>
    </tr>
    <tr><td class="content_title" align="right" ><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport();" title="Print" />&nbsp;<input type="image" name="printGroupSubmit" id='generateCSV' onClick="printCSV();return false;" value="printGroupSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" title="Export to Excel"/></td></tr>
    </table>
    </td>
    </tr>
    </table>
    <!--Start Add Div-->
 </form>
<?php floatingDiv_Start('AddAccount','Add Deduction Account'); ?>
<form name="addAccount" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td class="contenttab_internal_rows" width="40%"><strong>&nbsp;&nbsp;Account Name<?php echo REQUIRED_FIELD ?></strong></td>
			<td width="60%" class="padding">:&nbsp;
			<input type="text" maxlength="25" id="accountName" name="accountName" style="width:142px" />
			</td>
		</tr>
		<tr>
			<td class="contenttab_internal_rows"><strong>&nbsp;&nbsp;Account Number<?php echo REQUIRED_FIELD ?></strong></td>
			<td class="padding">:&nbsp;
			<input type="text" maxlength="20" id="accountNumber" name="accountNumber" style="width:142px" />
			</td>
		</tr>
		<tr>
			<td height="5px"></td></tr>
			<tr>
			<td align="center" style="padding-right:10px" colspan="2">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
			<input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddAccount');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<?php floatingDiv_Start('EditAccount','Edit Deduction Account'); ?>
<form name="editAccount" action="" method="post">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
			<input type="hidden" name="dedAccountId" id="dedAccountId" value="" /> 
			<tr>
				<td class="contenttab_internal_rows" width="40%"><strong>&nbsp;&nbsp;Account Name<?php echo REQUIRED_FIELD ?></strong></td>
				<td width="60%" class="padding">:&nbsp;
				<input type="text" maxlength="25" id="accountName" name="accountName" style="width:142px" />
				</td>
			</tr>
			<tr>
				<td class="contenttab_internal_rows"><strong>&nbsp;&nbsp;Account Number<?php echo REQUIRED_FIELD ?></strong></td>
				<td width="79%" class="padding">:&nbsp;
				<input type="text" maxlength="20" id="accountNumber" name="accountNumber" style="width:142px" />
				</td>
			</tr>
			<tr>
				<td height="5px"></td>
			</tr>
			<tr>
				<td align="center" style="padding-right:10px" colspan="2">
				<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
				<input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  
				onclick="javascript:hiddenFloatingDiv('EditAccount');return false;" />
				</td>
			</tr>
			<tr>
				<td height="5px"></td></tr>
			</tr>
	</table>
</form>
    <?php floatingDiv_End(); ?>
        

        

    <!--End: Div To Edit The Table-->