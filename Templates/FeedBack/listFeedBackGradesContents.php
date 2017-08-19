<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR feed back grades 
//
//
// Author :Jaineesh 
// Created on : (20.12.2008 )
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick=" displayWindow('AddFeedBackGrades',355,250);blankValues();return false; " />&nbsp;</td></tr>
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
<?php floatingDiv_Start('AddFeedBackGrades','Add Feedback Grades'); ?>
<form name="AddFeedBackGrades" action="" method="post">  
 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
     <td width="30%" class="contenttab_internal_rows">&nbsp;<nobr><strong>Grade Label<?php echo REQUIRED_FIELD ?></strong></nobr></td>
     <td width="24%" class="padding">:&nbsp;
      <input type="text" id="gradeLabel" name="gradeLabel"  style="width:50px" class="inputbox" maxlength="30"/>
     </td>
      <td width="22%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Grade Value<?php echo REQUIRED_FIELD ?></strong></nobr></td>
      <td width="24%" class="padding">:&nbsp;
      <input type="text" id="gradeValue" name="gradeValue"  style="width:50px" class="inputbox" maxlength="5"/>
     </td>
   </tr>
   <tr>
     <td class="contenttab_internal_rows"><nobr>&nbsp;<nobr><strong>Feedback Label<?php echo REQUIRED_FIELD ?></strong></nobr></td>
     <td class="padding" colspan="3">:&nbsp;
      <select name="surveyValue" id="surveyValue" class="inputbox">
        <option value="">SELECT</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getFeedBackLabelData();
           ?>
      </select>
     </td>
   </tr>
  <tr>
    <td height="5px"></td></tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="4">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddFeedBackGrades');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<?php floatingDiv_Start('EditFeedBackGrades','Edit Feedback Grades '); ?>
<form name="EditFeedBackGrades" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="feedbackGradeId" id="feedbackGradeId" value="" />  
    <tr>
     <td width="30%" class="contenttab_internal_rows">&nbsp;<nobr><strong>Grade Label<?php echo REQUIRED_FIELD ?></strong></nobr></td>
     <td width="24%" class="padding">:&nbsp;
      <input type="text" id="gradeLabel" name="gradeLabel"  style="width:50px" class="inputbox" maxlength="30"/>
     </td>
      <td width="22%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Grade Value<?php echo REQUIRED_FIELD ?></strong></nobr></td>
      <td width="24%" class="padding">:&nbsp;
      <input type="text" id="gradeValue" name="gradeValue"  style="width:50px" class="inputbox" maxlength="5"/>
     </td>
   </tr>
   <tr>
     <td width="25%" class="contenttab_internal_rows">&nbsp;<nobr><nobr><strong>Feedback Label<?php echo REQUIRED_FIELD ?></strong></nobr></td>
     <td width="25%" class="padding" colspan="3">:&nbsp;
      <select name="surveyValue" id="surveyValue" class="inputbox">
        <option value="">SELECT</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getFeedBackLabelData();
           ?>
      </select>
     </td>
   </tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="4">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditFeedBackGrades');return false;" />
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
// $History: listFeedBackGradesContents.php $
//
//*****************  Version 7  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/FeedBack
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 29/07/09   Time: 11:35
//Updated in $/LeapCC/Templates/FeedBack
//Done bug fixing.
//bug id---0000749
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 25/07/09   Time: 13:12
//Updated in $/LeapCC/Templates/FeedBack
//Done Bug Fixing.
//Bug ids---0000680 to 0000688,0000690 to 0000696
//
//*****************  Version 4  *****************
//User: Administrator Date: 13/06/09   Time: 18:59
//Updated in $/LeapCC/Templates/FeedBack
//Corredted issues which are detected during user documentation
//preparation
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
//*****************  Version 5  *****************
//User: Jaineesh     Date: 1/22/09    Time: 12:19p
//Updated in $/Leap/Source/Templates/FeedBack
//modification in width of feedback label
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 13/01/09   Time: 16:34
//Updated in $/Leap/Source/Templates/FeedBack
//Modified Code as one field is added in feedback_grade table
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/12/09    Time: 2:55p
//Updated in $/Leap/Source/Templates/FeedBack
//make changes for sending sendReq() function
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/18/08   Time: 3:04p
//Updated in $/Leap/Source/Templates/FeedBack
//Corrected labels in add and edit divs
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/15/08   Time: 1:41p
//Created in $/Leap/Source/Templates/FeedBack
//Created FeedBack Masters
?>