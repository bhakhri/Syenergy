<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR TEMPORARY EMPLOYEE LISTING
//
//
// Author :Gurkeerat Sidhu
// Created on : (29.04.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


           




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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddTempEmployee',330,250);blankValues();return false;"/>&nbsp;</td></tr>
							<tr>
								<td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
							</tr>
             <tr>
								<td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
										
                    <tr>
								 <td class="content_title" align="right" ><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport();" title="Print" />&nbsp;<input type="image" name="printGroupSubmit" id='generateCSV' onClick="printCSV();return false;" value="printGroupSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" title="Export to Excel"/></td></tr>
                                 </td></tr>

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

<?php floatingDiv_Start('AddTempEmployee','Add Temporary Employee'); ?>
<form name="AddTempEmployee" action="" method="post">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>&nbsp;Safaiwala<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td class="padding"><nobr>:&nbsp;&nbsp;<input type="text" id="tempEmployeeName" name="tempEmployeeName" class="inputbox" maxlength="30" onkeydown="return sendKeys(1,'tempEmployeeName',event);" /></nobr></td>
    </tr>
    <tr>
      <td class="contenttab_internal_rows" valign="top"><nobr><strong>&nbsp;Address<?php echo REQUIRED_FIELD;?></strong></nobr></td>
      <td class="padding" valign="top">:&nbsp;
		<textarea class="inputbox" name="address" id="address" cols="20" rows="4" style="vertical-align:top;"></textarea>
	  </td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>&nbsp;Contact No.<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td class="padding"><nobr>:&nbsp;
        <input type="text" id="contactNo" name="contactNo" class="inputbox" maxlength="20" onkeydown="return sendKeys(1,'inputbox',event);" /></nobr></td>
    </tr>
    <tr>

        <td valign="center" align="right" class="contenttab_internal_rows"><b>&nbsp;Date Of Joining</b></td>
        <td valign="center" align="left" ><b>&nbsp;&nbsp;:&nbsp;</b>
        <?php
          require_once(BL_PATH.'/HtmlFunctions.inc.php');
          echo HtmlFunctions::getInstance()->datePicker('joiningDate1',date('Y-m-d'));
        ?>
        </td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>&nbsp;Status<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td class="padding" ><nobr>:&nbsp;
        <select size="1" class="selectfield" name="status" id="status">
        <option value="" selected="selected">SELECT</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getTempEmployeeStatus();
              ?>
        </select></nobr>
    </td>
  </tr>
     <tr>
        <td class="contenttab_internal_rows"><nobr><b>&nbsp;Designation<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td class="padding" ><nobr>:&nbsp;
        <select size="1" class="selectfield" name="designationName" id="designationName">
        <option value="" selected="selected">SELECT</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getEmpDesignation();
              ?>
        </select></nobr>
    </td>
  </tr>
	<tr>
		<td height="5px"></td>
	</tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancell"  src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddTempEmployee');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px" colspan="3"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditTempEmployee','Edit Temporary Employee'); ?>
<form name="EditTempEmployee" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="tempEmployeeId" id="tempEmployeeId" value="" />
   <tr>
        <td class="contenttab_internal_rows"><nobr><b>&nbsp;Safaiwala<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td class="padding"><nobr>:&nbsp;<input type="text" id="tempEmployeeName" name="tempEmployeeName" class="inputbox" maxlength="30" onkeydown="return sendKeys(2,'tempEmployeeName',event);" /></nobr></td>
    </tr>
    <tr>
      <td class="contenttab_internal_rows" valign="top"><nobr><strong>&nbsp;Address<?php echo REQUIRED_FIELD;?></strong></nobr></td>
      <td class="padding" valign="top">:&nbsp;
		<textarea class="inputbox" name="address" id="address" cols="20" rows="4" style="vertical-align:top;"></textarea>
     </td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>&nbsp;Contact No.<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td class="padding"><nobr>:&nbsp;
			<input type="text" id="contactNo" name="contactNo" class="inputbox" maxlength="20" onkeydown="return sendKeys(2,'inputbox',event);"/></nobr>
		</td>
    </tr>
    <tr>
        <td valign="center" align="right" class="contenttab_internal_rows"><b>&nbsp;Date Of Joining</b></td>
        <td valign="center" align="left" ><b>&nbsp;:&nbsp;</b>
        <?php
          require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->datePicker('joiningDate2',date('Y-m-d'));
        ?>
        </td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>&nbsp;Status<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td class="padding"><nobr>:&nbsp;
        <select size="1" class="selectfield" name="status" id="status">
        <option value="" selected="selected">SELECT</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getTempEmployeeStatus();
              ?>
        </select></nobr>
    </td>
  </tr>
     <tr>
        <td class="contenttab_internal_rows"><nobr><b>&nbsp;Designation<?php echo REQUIRED_FIELD;?></b></nobr></td>
        <td class="padding"><nobr>:&nbsp;
        <select size="1" class="selectfield" name="designationName" id="designationName">
        <option value="" selected="selected">SELECT</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getEmpDesignation();
              ?>
        </select></nobr>
    </td>
  </tr>
<tr>
    <td height="5px" colspan="3"></td>
</tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
      <input type="image" name="editCancell" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditTempEmployee');return false;" />
        </td>
</tr>
<tr>
    <td height="5px" colspan="3"></td></tr>
<tr>
</table>
</form>
    <?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->


