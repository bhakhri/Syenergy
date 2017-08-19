<?php 

//
//This file creates Html Form output in "Payroll" module for heads master 
//
// Author :Abhiraj Malhotra
// Created on : 07-April-2010
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
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
                <td valign="top">Setup&nbsp;&raquo;&nbsp;Payroll&nbsp;&raquo;&nbsp;Heads Master</td>
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
                        <td class="content_title">Salary Heads Details : </td>
                        <td class="content_title" title="Add">
						<img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                        align="right" onClick="displayWindow('AddHead',270,250);blankValues();return false;" />&nbsp;</td>
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
	</td></tr></table>
    <!--Start Add Div-->
 </form>
<?php floatingDiv_Start('AddHead','Add Heads'); ?>
<form name="addHead" action="" method="post">
	<table width="500" border="0" cellspacing="0" cellpadding="0" class="border">
		<tr>
			<td width="100" class="contenttab_internal_rows"><strong>&nbsp;&nbsp;Head Name<?php echo REQUIRED_FIELD ?></strong></td>
			<td width="5px" class="contenttab_internal_rows"><strong>:</strong></td>
		  <td class="padding"><input type="text" maxlength="30" id="headName" name="headName" style="width:242px" />		  </td>
		</tr>
		<tr>
			<td width="100" class="contenttab_internal_rows"><strong>&nbsp;&nbsp;Head Abbr. <?php echo REQUIRED_FIELD ?></strong></td>
			<td class="contenttab_internal_rows"><strong>:</strong></td>
			<td class="padding"><input type="text" maxlength="6" id="headAbbr" name="headAbbr" style="width:242px" />		  </td>
		</tr>
		<tr>
			<td width="100" class="contenttab_internal_rows"><strong>&nbsp;&nbsp;Head Type<?php echo REQUIRED_FIELD ?></strong></td>
			<td class="contenttab_internal_rows"><strong>:</strong></td>
			<td class="padding"><select id="headType" name="headType" style="width:242px" onchange="getDeductionAccounts(this.value,'add');return false;"/>
            <option value="">----- Select ------</option>
            <option value="0">Earning</option>
            <option value="1">Deduction</option>
            </select>			</td>
		</tr>
        <tr>
            <td width="100" class="contenttab_internal_rows"><strong>&nbsp;&nbsp;Deduction A/C</strong></td>
            <td class="contenttab_internal_rows"><strong>:</strong></td>
            <td class="padding"><select id="dedAccountId" name="dedAccountId" disabled="true"/>
            <option value="">----- Select ------</option>
            <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getDeductionAccounts();
              ?>
            </select>            </td>
        </tr>
        <tr>
            <td width="100" valign="top" class="contenttab_internal_rows"><strong>&nbsp;&nbsp;Head Desc.</strong></td>
            <td valign="top" class="contenttab_internal_rows"><strong>:</strong></td>
          <td valign="top" class="padding"><textarea id="headDesc" name="headDesc" style="width:242px;" rows=3 /></textarea>
            <br />
          Not more than 60 chars</td>
        </tr>
		<tr>
			<td height="5px" colspan="2"></td></tr>
			<tr>
			<td align="center" style="padding-right:10px" colspan="3">
			<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
			<input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddHead');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />			</td>
		</tr>
		<tr>
			<td height="5px" colspan="2"></td>
		</tr>
	</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<?php floatingDiv_Start('ViewHeadDesc','Heads Description'); ?>
<form name="headDescForm" action="" method="post"> 
    <table width="400" border="0" cellspacing="0" cellpadding="0" class="border">
        <tr>
            <td class="contenttab_internal_rows" width="96"><strong>&nbsp;&nbsp;Head Name</strong></td>
            <td width="11"><strong>:</strong>&nbsp;</td>
          <td width="293" class="padding"><input name="headName" type="text" id="headName2" style="width:242px; border:0px" maxlength="30" readonly /></td>
        </tr>
        <tr>
            <td class="contenttab_internal_rows" valign="top"><strong>&nbsp;&nbsp;Head Desc.</strong></td>
          <td valign="top"><strong>:&nbsp;</strong> </td>
          <td valign="top" class="padding"><textarea id="headDesc" name="headDesc" style="width:242px;" rows=3 readonly /></textarea></td>
        </tr>
        <tr>
            <td height="5px"></td>
        </tr>
    </table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Add Div-->
<?php floatingDiv_Start('EditHead','Edit Heads'); ?>
<form name="editHead" action="" method="post">
	<table width="500" border="0" cellspacing="0" cellpadding="0" class="border">
			    <input type="hidden" name="headId" id="headId" value="" /> 
			    <tr>
                <td class="contenttab_internal_rows" width="110"><strong>&nbsp;&nbsp;Head Name<?php echo REQUIRED_FIELD ?></strong></td>
                <td class="contenttab_internal_rows" width="5"><strong>:</strong></td>
                <td width="78%" class="padding"><input type="text" maxlength="40" id="headName" name="headName" style="width:242px" />                </td>
            </tr>
			<tr>
                <td width="150" class="contenttab_internal_rows"><strong>&nbsp;&nbsp;Head Abbr. <?php echo REQUIRED_FIELD ?></strong></td>
                <td width="5" class="contenttab_internal_rows"><strong>:</strong></td>
                <td width="78%" class="padding"><input type="text" maxlength="6" id="headAbbr" name="headAbbr" style="width:242px" />              </td>
            </tr>
            <tr>
                <td width="150" class="contenttab_internal_rows"><strong>&nbsp;&nbsp;Head Type<?php echo REQUIRED_FIELD ?></strong></td>
                <td width="5" class="contenttab_internal_rows"><strong>:</strong></td>
                <td class="padding"><select id="headType" name="headType" style="width:242px" onchange="getDeductionAccounts(this.value,'edit');return false;"/>
                <option value="">----- Select ------</option>
                <option value="0">Earning</option>
                <option value="1">Deduction</option>
                </select>                </td>
            </tr>
            <tr>
                <td width="150" class="contenttab_internal_rows"><strong>&nbsp;&nbsp;Deduction A/C</strong></td>
                <td width="5" class="contenttab_internal_rows"><strong>:</strong></td>
                <td class="padding"><select id="dedAccountId" name="dedAccountId" disabled="true"/>
                    <option value="">----- Select ------</option>
                    <?php
                    //populate deduction accounts dropdown
                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                    echo HtmlFunctions::getInstance()->getDeductionAccounts();
                  ?>
              </select>                </td>
            </tr>
            <tr>
                <td width="150" valign="top" class="contenttab_internal_rows"><strong>&nbsp;&nbsp;Head Desc.</strong></td>
                <td width="5" valign="top" class="contenttab_internal_rows"><strong>:</strong></td>
              <td valign="top" class="padding"><textarea id="headDesc" name="headDesc" style="width:242px;" rows=3 /></textarea>
               <br />
                Not more than 60 chars</td>
            </tr>
			<tr>
				<td height="5px" colspan="2"></td>
			</tr>
			<tr>
				<td align="center" style="padding-right:10px" colspan="3">
				<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
				<input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  
				onclick="javascript:hiddenFloatingDiv('EditHead');return false;" />				</td>
			</tr>
			<tr>
				<td height="5px" colspan="2"></td></tr>
			<tr><td colspan="2"></tr>
	</table>
</form>
    <?php floatingDiv_End(); ?>
	
        

        

    <!--End: Div To Edit The Table-->