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
                                <td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddBroadcastMessage',315,250);blankValues();return false;" />&nbsp;</td></tr>
             <tr>
                                <td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
             </tr>
             <!--
             <tr>
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
             -->
          </table>
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
    <!--Start Add Div-->

<?php floatingDiv_Start('AddBroadcastMessage','Add Message'); ?>
 <form name="AddBroadcastMessage" action="" method="post">  
  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
   <tr>
        <td width="20%" class="contenttab_internal_rows" valign="top"><nobr><b>Message Text<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding" valign="top">:</td>
        <td width="79%" class="padding">
         <textarea name="megText" id="msgText" class="inputbox" style="width:300px;"></textarea>
        </td>
   </tr>
   <tr>    
        <td width="20%" class="contenttab_internal_rows"><nobr><b>Date<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:</td>
        <td width="79%" class="padding">
         <?php
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->datePicker('msgDate1','');
         ?>
        </td>
    </tr>
   <tr><td height="5px"></td></tr>
   <tr>
      <td align="center" style="padding-right:10px" colspan="3">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
        <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddBroadcastMessage');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
      </td>
   </tr>
   <tr><td height="5px"></td></tr>
 </table>
 </form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditBroadcastMessage','Edit Message '); ?>
<form name="EditBroadcastMessage" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="msgId" id="msgId" value="" />  
    <tr>
        <td width="20%" class="contenttab_internal_rows" valign="top"><nobr><b>Message Text<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding" valign="top">:</td>
        <td width="79%" class="padding">
         <textarea name="megText" id="msgText" class="inputbox" style="width:300px;"></textarea>
        </td>
   </tr>
   <tr>    
        <td width="20%" class="contenttab_internal_rows"><nobr><b>Date<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td class="padding">:</td>
        <td width="79%" class="padding">
         <?php
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->datePicker('msgDate2','');
         ?>
        </td>
    </tr>
   <tr><td height="5px"></td></tr>
   <tr>
     <td align="center" style="padding-right:10px" colspan="3">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditBroadcastMessage');return false;" />
     </td>
   </tr>
   <tr><td height="5px"></td></tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End: Div To Edit The Table-->