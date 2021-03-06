<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR FeedBackQuestions LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (15.11.2008)
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick=" displayWindow('AddFeedBackQuestions',300,250);blankValues();return false;  " />&nbsp;</td></tr>
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

<?php floatingDiv_Start('AddFeedBackQuestions','Add Feedback Questions'); ?>
    <form name="AddFeedBackQuestions" action="" method="post">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="25%" class="contenttab_internal_rows">&nbsp;&nbsp;<nobr><b>Label <?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td width="75%" class="padding">:&nbsp;
        <select size="1" class="selectfield" name="labelId" id="labelId">
        <option value="">SELECT</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getFeedBackLabelData();
              ?>
        </select>
        </td>
    </tr>
    <tr>    
        <td class="contenttab_internal_rows">&nbsp;&nbsp;<nobr><b>Category <?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding">:&nbsp;
         <select size="1" class="selectfield" name="categoryId" id="categoryId">
         <option value="">SELECT</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getFeedBackCategoryData();
              ?>
        </select>
        </td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;<nobr><b>Question <?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding"><span style="text-align:center;">:&nbsp;</span>
         <textarea id="questionTxt" name="questionTxt" cols="20" rows="4" maxlength="100" onkeyup="return ismaxlength(this)" style="vertical-align:middle" ></textarea>
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddFeedBackQuestions');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<?php floatingDiv_Start('EditFeedBackQuestions','Edit Feedback Questions '); ?>
 <form name="EditFeedBackQuestions" action="" method="post">  
 <input type="hidden" name="feedbackQuestionId" id="feedbackQuestionId" value="" /> 
 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="25%" class="contenttab_internal_rows">&nbsp;&nbsp;<nobr><b>Label <?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td width="75%" class="padding">:&nbsp;
        <select size="1" class="selectfield" name="labelId" id="labelId">
        <option value="">SELECT</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getFeedBackLabelData();
              ?>
        </select>
        </td>
    </tr>
    <tr>    
        <td class="contenttab_internal_rows">&nbsp;&nbsp;<nobr><b>Category <?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding">:&nbsp;
         <select size="1" class="selectfield" name="categoryId" id="categoryId">
         <option value="">SELECT</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getFeedBackCategoryData();
              ?>
        </select>
        </td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;<nobr><b>Question <?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding">:&nbsp;
         <textarea id="questionTxt" name="questionTxt" cols="20" rows="4" maxlength="100" onkeyup="return ismaxlength(this)" style="vertical-align:middle"></textarea>
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditFeedBackQuestions');return false;" />
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
// $History: listFeedBackQuestionsContents.php $
//
//*****************  Version 7  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/FeedBack
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 2/09/09    Time: 11:08
//Updated in $/LeapCC/Templates/FeedBack
//Done bug fixing.
//Bug ids---
//00001398,00001399,00001400,00001401
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
//User: Administrator Date: 11/06/09   Time: 11:15
//Updated in $/LeapCC/Templates/FeedBack
//Done bug fixing.
//bug ids---
//0000011,0000012,0000016,0000018,0000020,0000006,0000017,0000009,0000019
//
//*****************  Version 2  *****************
//User: Administrator Date: 21/05/09   Time: 11:14
//Updated in $/LeapCC/Templates/FeedBack
//Copied "Feedback Master Modules" from Leap to LeapCC
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/16/09    Time: 2:17p
//Updated in $/Leap/Source/Templates/FeedBack
//modified left alignment
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/12/09    Time: 2:55p
//Updated in $/Leap/Source/Templates/FeedBack
//make changes for sending sendReq() function
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/15/08   Time: 1:41p
//Created in $/Leap/Source/Templates/FeedBack
//Created FeedBack Masters
?>