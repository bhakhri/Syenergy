<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR CITY LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img title="Add" src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick=" displayWindow('AddCategory',315,250);blankValues();return false;
         " />&nbsp;</td></tr>
							<tr>
								<td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
							</tr>
             <tr>
								<td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
											<td class="content_title" valign="middle" align="right" width="20%">
												<input type="image" title="Print" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
												<input type="image" title="Export To Excel" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printCSV();" >
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
<?php floatingDiv_Start('AddCategory','',1); ?>
    <form name="AddCategory" action="" method="post">
    <input type="hidden" name="catId" id="catId" value="" />
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <!--
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Label<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="78%" class="padding">
         <select size="1" id="labelId" name="labelId" class="inputbox" style="width:185px;" onchange="refreshParentCategory(this.value);" >
           <option value="">Select</option>
           <?php
             //require_once(BL_PATH.'/HtmlFunctions.inc.php');
             //echo HtmlFunctions::getInstance()->getAdvFeedBackLabelData();
           ?>
         </select>
        </td>
    </tr>
    -->
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Category Name<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="78%" class="padding">
         <input type="text" id="catName" name="catName" class="inputbox" maxlength="30" />
         </td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Parent Category</b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="78%" class="padding">
         <select size="1" id="parentCat" name="parentCat" class="inputbox" style="width:185px;">
           <option value="">Select</option>
         </select>
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Relationship<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="78%" class="padding">
        <select style="width:185px;" size="1" class="inputbox" name="catRelation" id="catRelation" onchange="toggleSubjectType(this.value);">
        <option value="">Select</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getAdvFeedBackRelationshipData();
              ?>
        </select>
    </td>
   </tr>
   <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Subject Type<span id="stSpan" style="display:none;"><?php echo REQUIRED_FIELD; ?></span></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="78%" class="padding">
         <select style="width:185px;" size="1" class="inputbox" name="subjectType" id="subjectType" disabled="disabled">
         <option value="">Select</option>
           <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getSubjectTypesData();
           ?>
         </select>
    </td>
   </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Description</b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="78%" class="padding">
         <textarea class="inputbox" id="catDesc" name="catDesc" rows="3" cols="20" maxlength="5000" onkeyup="return ismaxlength(this);"></textarea>
         </td>
    </tr> 
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Print Order<?php echo REQUIRED_FIELD; ?></b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="78%" class="padding">
         <input type="text" id="printOrder" name="printOrder" class="inputbox" maxlength="10" style="width:50px;" />
         </td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Comments</b></nobr></td>
        <td width="1%" class="padding">:</td>
        <td width="78%" class="contenttab_internal_rows"><input type="checkbox" name="catComments" id="catComments" />
        </td>
    </tr> 

<tr><td height="5px" colspan="3"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('AddCategory');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
    </td>
</tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add/Edit Div-->

<?php
// $History: listFeedBackCategoryContents.php $
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 19/02/10   Time: 14:22
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Done Bug fixing.
//Bug ids---
//0002910,0002909,0002907,
//0002906,0002904,0002908,
//0002905
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 18/02/10   Time: 12:59
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Done bug fixing.
//Bug ids---
//0002900,0002899,0002898,0002897
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 1/21/10    Time: 5:22p
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Updated breadcrumbs and titles
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/01/10    Time: 18:29
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Updated "Advanced Feedback Category" module as feedbackSurveyId is
//removed from table
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/01/10    Time: 16:48
//Created in $/LeapCC/Templates/FeedbackAdvanced
//Created module "Advanced Feedback Category Module"
?>