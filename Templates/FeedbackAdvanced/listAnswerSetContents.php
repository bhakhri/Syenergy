<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR OFFENSE
//
//
// Author :Gurkeerat Sidhu
// Created on : (08.01.2010 )
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
									<?php 
									 $specialSearchCondition="getAnswerSetData()"; 
									 require_once(TEMPLATES_PATH . "/searchForm.php"); 
									?>
								</td>
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;">
								<img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AnswerSetActionDiv',340,250);blankValues();return false;" title="Add" />&nbsp;</td></tr>
							<tr>
								<td class="contenttab_row" colspan="2" valign="top" ><div id="AnswerSetResultDiv"></div></td>
							</tr>
             <tr>
								<td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
											<td class="content_title" valign="middle" align="right" width="20%">
												<input type="image" title ="Print"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
												<input type="image" title="Export to Excel" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" >
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

<!--Start Add/Edit Div-->
<?php floatingDiv_Start('AnswerSetActionDiv',''); ?>
<form name="AnswerSetDetail" action="" method="post" onsubmit="return false;">  
<input type="hidden" name="answerSetId" id="answerSetId" value="" />

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<tr>
    <td height="5px"></td></tr>
   <tr>
      <td width="35%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Name </strong><?php echo REQUIRED_FIELD; ?></nobr></td>
      <td width="65%" class="padding">:</td>
      <td><input type="text" id="answerSetName" name="answerSetName"  style="width:170px" class="inputbox" maxlength="20"/>
     </td>
   </tr>
   <tr>
    <td height="5px"></td></tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="4">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AnswerSetActionDiv');if(flag==true){getAnswerSetData();flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->



