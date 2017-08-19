<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR feed back grades 
// Author :Dipanjan Bhattacharjee 
// Created on : (12.1.2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------



require_once(BL_PATH.'/HtmlFunctions.inc.php');
$leaveSetString=HtmlFunctions::getInstance()->getLeaveSetAdvData(' AND isActive=1');
$leaveTypeString=HtmlFunctions::getInstance()->getLeaveTypeAdvData(' AND isActive=1');
?>
<select name="leaveTypeHidden" id="leaveTypeHidden" style="display:none;">
 <?php
  echo $leaveTypeString;
 ?>
</select>

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
          <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddLeaveSetMapping',355,250);blankValues();return false;" />&nbsp;</td></tr>
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
<?php floatingDiv_Start('AddLeaveSetMapping','Add Leave Set Mapping'); ?>
<form name="AddLeaveSetMapping" action="" method="post">  
 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
 <tr><td height="3px;"></td></tr>
  <tr>
     <td style="display:none" class="contenttab_internal_rows" style="padding-bottom:8px;"><nobr>&nbsp;<b>Leave Session<?php echo REQUIRED_FIELD ?></b></nobr><b>:</b>&nbsp;
      <select name="leaveSessionId" id="leaveSessionId" class="inputbox" style='width:180px' onchange="showLeaveSetMapping(); return false;">
        <option value="">SELECT</option>
        <?php
          require_once(BL_PATH.'/HtmlFunctions.inc.php');
          echo HtmlFunctions::getInstance()->getLeaveSessionData();
        ?>
      </select>
     </td>
     <td colspan="2" class="contenttab_internal_rows" style="padding-bottom:8px;"><nobr><b>&nbsp;Leave Set<?php echo REQUIRED_FIELD ?></b></nobr><b>:</b>&nbsp;
      <select name="leaveSet" id="leaveSet" class="inputbox"  style='width:155px'  onchange="showLeaveSetMapping(); return false;">
        <option value="">SELECT</option>
            <?php
              echo $leaveSetString;
            ?>
      </select>
     </td>
   </tr>
   <tr>
    <td width="100%" colspan="2" style="width:500px;" >
    <div id="tableDiv" style="height:190px;width:520px;overflow:auto;">
    <table width="100%" border="0" cellspacing="2" cellpadding="0" id="anyid">
        <tbody id="anyidBody">
            <tr class="rowheading">
                <td width="5%" class="contenttab_internal_rows"><b>#</b></td>
                <td width="45%" class="contenttab_internal_rows"><b>Leave Type</b></td>
                <td width="20%" class="contenttab_internal_rows"><b>Value</b></td>
                <td width="10%" class="contenttab_internal_rows"><b>Delete</b></td>
            </tr>
        </tbody>
        </table>
    </div>    
    </td>
    </tr>
   
    <tr>
    <td colspan="2">
    <input type="hidden" name="deleteFlag" id="deleteFlag" value="" />
       <a href="javascript:addOneRow(1);" title="Add Row"><font class="textClass"><b><nobr><u>Add More</u></b></font></a>
    </td>
    </tr> 
  
  <tr>
    <td height="5px" colspan="2"></td></tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="4">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddLeaveSetMapping');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
    </td>
 </tr>
<tr><td height="5px" colspan="2"></td></tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditLeaveSetMapping','Edit Leave Set Mapping '); ?>
<form name="EditLeaveSetMapping" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="leaveSetMappingId" id="leaveSetMappingId" value="" maxlength="5"/>  
     <tr>
     <td width="25%" class="contenttab_internal_rows">&nbsp;<nobr><nobr><strong>Leave Set</strong></nobr></td>
     <td width="25%" class="padding" colspan="1">:&nbsp;
      <select name="leaveSet" id="leaveSet" class="inputbox" style="width:210px;" disabled="disabled">
        <option value="">SELECT</option>
           <?php
             echo $leaveSetString;
           ?>
      </select>
     </td>
   </tr>
   <tr>
     <td width="30%" class="contenttab_internal_rows">&nbsp;<nobr><strong>Leave Type<?php echo REQUIRED_FIELD ?></strong></nobr></td>
     <td width="24%" class="padding" colspan="1">:&nbsp;
      <select name="leaveType" id="leaveType" class="inputbox" style="width:210px;">
        <option value="">SELECT</option>
           <?php
            echo $leaveTypeString;
           ?>
      </select>
     </td>
    </tr>
    <tr> 
      <td width="22%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Value<?php echo REQUIRED_FIELD ?></strong></nobr></td>
      <td width="24%" class="padding">:&nbsp;
      <input type="text" id="leaveTypeValue" name="leaveTypeValue"  style="width:50px" class="inputbox" maxlength="5"/>
     </td>
   </tr>
  
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="4">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"   onClick="return validateAddForm(this.form,'Edit');return false;" />
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditLeaveSetMapping');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>   
    <?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->


<?php
// $History: listFeedbackAdvOptionsContents.php $
?>
