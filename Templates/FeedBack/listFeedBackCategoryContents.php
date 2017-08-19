<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR FEEDBACK CATEGORY LISTING 
//
// Author :Dipanjan Bhattacharjee 
// Created on : (26.06.2008)
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddFeedBackCategory',320,250);blankValues();return false;" />&nbsp;</td></tr>
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

<?php floatingDiv_Start('AddFeedBackCategory','Add Feedback Category'); ?>
<form name="AddFeedBackCategory" action="" method="post">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Category Name<?php echo REQUIRED_FIELD ?>:</b></nobr></td>
        <td width="79%" class="padding"><input type="text" id="categoryName" name="categoryName" class="inputbox" maxlength="100" /></td>
    </tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddFeedBackCategory');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditFeedBackCategory','Edit Feedback Category'); ?>
<form name="EditFeedBackCategory" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="feedBackCategoryId" id="feedBackCategoryId" value="" />  
     <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Category Name<?php echo REQUIRED_FIELD ?>:</b></nobr></td>
        <td width="79%" class="padding"><input type="text" id="categoryName" name="categoryName" class="inputbox" maxlength="100" /></td>
    </tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
      <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditFeedBackCategory');return false;" />
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
// $History: listFeedBackCategoryContents.php $
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/FeedBack
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 29/07/09   Time: 11:35
//Updated in $/LeapCC/Templates/FeedBack
//Done bug fixing.
//bug id---0000749
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 25/07/09   Time: 13:12
//Updated in $/LeapCC/Templates/FeedBack
//Done Bug Fixing.
//Bug ids---0000680 to 0000688,0000690 to 0000696
//
//*****************  Version 3  *****************
//User: Administrator Date: 13/06/09   Time: 18:59
//Updated in $/LeapCC/Templates/FeedBack
//Corredted issues which are detected during user documentation
//preparation
//
//*****************  Version 2  *****************
//User: Administrator Date: 11/06/09   Time: 11:15
//Updated in $/LeapCC/Templates/FeedBack
//Done bug fixing.
//bug ids---
//0000011,0000012,0000016,0000018,0000020,0000006,0000017,0000009,0000019
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/FeedBack
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/15/08   Time: 1:41p
//Created in $/Leap/Source/Templates/FeedBack
//Created FeedBack Masters
?>