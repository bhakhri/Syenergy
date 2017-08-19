<?php 

//
//This file creates Html Form output in "Leave_Session" Module 
//
// Author :Parveen Sharma   
// Created on : 19-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddSession',350,250);blankValues();return false;" />&nbsp;</td></tr>
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

<?php floatingDiv_Start('AddSession','Add Session'); ?>
<form name="addSession" action="" method="post">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
        <tr>
            <td width="45%" class="contenttab_internal_rows"><strong>Name<?php echo REQUIRED_FIELD ?></strong></td>
            <td width="2%" class="padding"><b>:</b></td>
            <td class="padding">
                <input type="text" class="inputbox" id="sessionName" name="sessionName" maxlength="20" onkeydown="return sendKeys(1,'sessionName',event);"/>
            </td>
        </tr>
        <tr>
            <td class="contenttab_internal_rows" valign="middle"><strong>Session Start Date<?php echo REQUIRED_FIELD ?></strong>&nbsp;</td>
            <td width="2%" class="padding"><b>:</b></td>
            <td class="padding">
            <?php 
            require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
            echo HtmlFunctions::getInstance()->datePicker('fromDate');  
            ?></td>
        </tr>    
        <tr>
            <td class="contenttab_internal_rows" valign="middle"><strong>Session End Date<?php echo REQUIRED_FIELD ?></strong>&nbsp;</td>
            <td width="2%" class="padding"><b>:</b></td>
            <td class="padding">
            <?php 
            require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
            echo HtmlFunctions::getInstance()->datePicker('toDate');  
            ?></td>
         </tr> 
        <tr>    
            <td class="contenttab_internal_rows"><strong>Active</strong>&nbsp;</td>
            <td class="contenttab_internal_rows1 padding" align="left">:</td>
             <td class="padding">
              <input type="radio" id="Active" name="Active" value="1"  checked="checked" />Yes&nbsp;
              <input type="radio" id="Active" name="Active" value="1" />No&nbsp;
            </td>
        </tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
     <input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddSession');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
</tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Add Div-->
<?php floatingDiv_Start('EditSession','Edit Session'); ?>
<form name="editSession" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<input type="hidden" name="sessionId" id="sessionId" value="" /> 
<tr>
  <td width="45%" class="contenttab_internal_rows"><strong>Name<?php echo REQUIRED_FIELD ?></strong></td>
  <td width="2%" class="padding"><b>:</b></td>
            <td class="padding">
   <input type="text" id="sessionName" name="sessionName" class="inputbox" maxlength="20" onkeydown="return sendKeys(2,'sessionName',event);"/></td>
</tr>
<tr>
    <td class="contenttab_internal_rows"><strong>Session Start Date<?php echo REQUIRED_FIELD ?></strong>&nbsp;</td>
    <td width="2%" class="padding"><b>:</b></td>
            <td class="padding">                                    
    <?php 
    require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
    echo HtmlFunctions::getInstance()->datePicker('fromDate1'); 
    ?></td>
</tr>    
<tr>
    <td class="contenttab_internal_rows"><strong>Session End Date<?php echo REQUIRED_FIELD ?></strong>&nbsp;</td>
    <td width="2%" class="padding"><b>:</b></td>
            <td class="padding">                                    
    <?php 
    require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
    echo HtmlFunctions::getInstance()->datePicker('toDate1'); 
    ?></td>
</tr>   
 <tr>    
        <td class="contenttab_internal_rows"><strong>Active</strong>&nbsp;</td>
        <td  class="contenttab_internal_rows1 padding" align="left">:&nbsp;</td>
          <td class="padding">
         <input type="radio" id="Active" name="Active" value="1" />Yes&nbsp;
         <input type="radio" id="Active" name="Active" value="1" checked="checked" />No&nbsp;
        </td>
</tr>
<tr>
    <td height="5px"></td>
</tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
                    <input type="image" name="Cancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  
                    onclick="javascript:hiddenFloatingDiv('EditSession');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!-- End: Div To Edit The Table -->