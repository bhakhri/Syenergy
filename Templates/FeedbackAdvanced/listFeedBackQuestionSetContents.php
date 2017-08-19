<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR CITY LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;">
								<img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick=" document.getElementById('multiRow').style.display='';document.getElementById('singleRow').style.display='none';document.getElementById('addMoreRow').style.display=''; displayWindow('AddQuestionSet',315,250);blankValues();return false;" title="Add"  />&nbsp;</td></tr>
							<tr>
								<td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
							</tr>
             <tr>
								<td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
											<td class="content_title" valign="middle" align="right" width="20%">
												<input type="image" title="Print" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
												<input type="image"  title="Export To Excel" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" >
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
<?php floatingDiv_Start('AddQuestionSet','',1); ?>
    <form name="AddQuestionSet" action="" method="post" onsubmit="return false;">
    <input type="hidden" name="setId" id="setId" value="" />
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr id="singleRow" style="display:none;">
      <td class="contenttab_internal_rows"><b>Question Set Name</b><?php echo REQUIRED_FIELD;?></td>
      <td class="padding">:
		  <input name="setName" id="setName" class="inputbox" style="width:190px" maxlength="30">
      </td>
    </tr>  
    <tr id="multiRow" style="display:none;">
    <td width="100%" colspan="2" style="width:280px">
    <div id="tableDiv" style="height:200px;overflow:auto;">
    <table width="100%" border="0" cellspacing="2" cellpadding="0" id="anyid">
        <tbody id="anyidBody">
            <tr class="rowheading">
                <td width="5%" class="contenttab_internal_rows"><b>S.No.</b></td>
                <td width="85%" class="contenttab_internal_rows"><b>Question Set Name</b></td>
                <td width="10%" class="contenttab_internal_rows"><b>Delete</b></td>
            </tr>
        </tbody>
    </table>
    </div>    
    </td>
    </tr>
   
    <tr id="addMoreRow" style="display:none;">
    <td colspan="2">
    <input type="hidden" name="deleteFlag" id="deleteFlag" value="" />
       <a href="javascript:addOneRow(1);" title="Add Row"><b><nobr>Add More</b></a>
    </td>
    </tr>
<tr><td height="5px" colspan="3"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddQuestionSet');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
    </td>
</tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add/Edit Div-->

<?php
// $History: listFeedBackQuestionSetContents.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 19/02/10   Time: 14:22
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Done Bug fixing.
//Bug ids---
//0002910,0002909,0002907,
//0002906,0002904,0002908,
//0002905
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 18/02/10   Time: 18:30
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Modified UI design: Now users can add multiple records at a time.
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 1/21/10    Time: 5:22p
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Updated breadcrumbs and titles
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 12/01/10   Time: 12:30
//Created in $/LeapCC/Templates/FeedbackAdvanced
//Created  "Question Set Master"  module
?>