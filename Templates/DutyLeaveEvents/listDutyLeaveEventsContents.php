<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR CITY LISTING
// Author :Dipanjan Bhattacharjee
// Created on : (12.06.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick=" displayWindow('AddEvent',315,250);blankValues();return false; " />&nbsp;</td></tr>
             <tr>
								<td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
             </tr>
<!--              <tr>
								<td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                        <tr>
                            <td class="content_title" valign="middle" align="right" width="20%">
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();" >
                            </td>
                        </tr>
                    </table>
                </td>
             </tr>
 -->          </table>
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
    <!--Start Add Div-->

<?php floatingDiv_Start('AddEvent','Add Event'); ?>
    <form name="AddEvent" action="" method="post">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="20%" class="contenttab_internal_rows"><nobr><b>Event<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="contenttab_internal_rows" align="right"><nobr><b>:</b></nobr></td>
        <td width="79%" class="padding" align="left"><input name="eventTitle" type="text" class="inputbox" id="eventTitle" maxlength="180"/></td>
    </tr>
    <tr>
        <td  class="contenttab_internal_rows"><nobr><b>Start Date<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td  class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
        <td  class="padding" align="left">
          <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->datePicker('startDate1',date('Y-m-d'));
          ?>
        </td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>End Date<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
        <td class="padding" align="left">
          <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->datePicker('endDate1',date('Y-m-d'));
          ?>
        </td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>Time Table<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
        <td class="padding" align="left">
          <select size="1" class="inputbox" name="labelId" id="labelId"  style="width:185px;" >
            <option value="" >Select</option>
            <?php
              require_once(BL_PATH.'/HtmlFunctions.inc.php');
              echo HtmlFunctions::getInstance()->getTimeTableLabelData('-1');
            ?>
            </select>
        </td>
    </tr>
<tr><td height="5px" colspan="2"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3" >
    <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" /> 
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddEvent');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr><td height="5px" colspan="2"></td></tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditEvent','Edit Event '); ?>
<form name="EditEvent" action="" method="post">
<input type="hidden" name="eventId" id="eventId" value="" />
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="20%" class="contenttab_internal_rows"><nobr><b>Event<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="contenttab_internal_rows" align="left"><b>:</b></td>
        <td width="79%" class="padding"><input type="text" id="eventTitle" name="eventTitle" class="inputbox" maxlength="180" /></td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>Start Date<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="contenttab_internal_rows" align="left"><b>:</b></td>
        <td class="padding">
          <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->datePicker('startDate2','');
          ?>
        </td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>End Date <?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="contenttab_internal_rows" align="left"><b>:</b></td>
        <td class="padding">
          <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->datePicker('endDate2','');
          ?>
        </td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows"><nobr><b>Time Table<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="contenttab_internal_rows" align="left"><b>:</b></td>
        <td width="79%" class="padding">
          <select size="1" class="inputbox" name="labelId" id="labelId"  style="width:185px;" >
            <option value="" >Select</option>
            <?php
              require_once(BL_PATH.'/HtmlFunctions.inc.php');
              echo HtmlFunctions::getInstance()->getTimeTableLabelData('-1');
            ?>
            </select>
        </td>
    </tr>
<tr><td height="5px" colspan="2"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditEvent');return false;" />
        </td>
</tr>
<tr><td height="5px" colspan="2"></td></tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End: Div To Edit The Table-->


<?php
// $History: listEventContents.php $
?>