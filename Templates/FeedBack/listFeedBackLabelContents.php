<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR FeedBack Label LISTING 
//
// Author :Dipanjan Bhattacharjee 
// Created on : (13.11.2008)
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddFeedBackLabel',320,350);blankValues();return false; " />&nbsp;</td></tr>
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

<?php floatingDiv_Start('AddFeedBackLabel','Add Feedback Label'); ?>
<form name="AddFeedBackLabel" action="" method="post">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Name<?php echo REQUIRED_FIELD ?> </b></nobr></td>
        <td width="70%" class="padding"><b>:</b>&nbsp;<input type="text" id="labelName" name="labelName" class="inputbox" maxlength="100" /></td>
    </tr>
    <tr>    
        <td  class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Active<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td  class="contenttab_internal_rows1" ><b>&nbsp;&nbsp;:&nbsp;</b>
         <input type="radio" id="isActive" name="isActive1" value="1" />Yes&nbsp;
         <input type="radio" id="isActive" name="isActive1" value="1" />No&nbsp;
        </td>
    </tr>

    <tr>
            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Survey Type<?php echo REQUIRED_FIELD ?></b></nobr></td>
            <td class="padding" ><b>:</b>&nbsp;<select size="1" name="surveyType" id="surveyType" class="selectfield1" onchange="disableAttempts(1);">
                <option value="1">General Feedback</option>
                <option value="2">Teacher Feedback</option>
                </select>
            </td>
    </tr>

   <tr>
        <td valign="top" class="contenttab_internal_rows" ><b>&nbsp;&nbsp;&nbsp;Visible From<?php echo REQUIRED_FIELD ?></b></td>
            <td valign="top" ><b>&nbsp;:&nbsp;</b><?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('startDate','');
        ?>
        </td>
    </tr>
    <tr>
        <td valign="top" class="contenttab_internal_rows"><b>&nbsp;&nbsp;&nbsp;Visible To<?php echo REQUIRED_FIELD ?></b></td>
        <td valign="top" ><b>&nbsp;:&nbsp;</b><?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('toDate','');
            ?>
        </td>
   </tr>

   <tr>
        <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;No. of Attempts<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding"><b>:</b>&nbsp;<input type="text" id="noAttempts" name="noAttempts" class="inputbox" maxlength="100" style="width:100px"/></td>
    </tr>
    <tr>
    <td height="5px" colspan="2"></td>
    </tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddFeedBackLabel');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
    </td>
</tr>
<tr>
    <td height="5px" colspan="2"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditFeedBackLabel','Edit Feedback Label'); ?>
<form name="EditFeedBackLabel" action="" method="post">  
    <input type="hidden" name="labelId" id="labelId" value="" />
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td width="70%" class="padding"><b>:</b>&nbsp;<input type="text" id="labelName" name="labelName" class="inputbox" maxlength="100" /></td>
    </tr>
    <tr>    
        <td  class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Active<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td  class="contenttab_internal_rows1" align="left">&nbsp;<b>:</b>&nbsp;
         <input type="radio" id="isActive" name="isActive1" value="1" />Yes&nbsp;
         <input type="radio" id="isActive" name="isActive1" value="1" />No&nbsp;
        </td>
    </tr>
    <tr>
            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Survey Type<?php echo REQUIRED_FIELD ?></b></nobr></td>
            <td class="padding" tabindex="28"><b>:</b>&nbsp;<select size="1" name="surveyType" id="surveyType" class="selectfield1" >
                <option value="1">General Feedback</option>
                <option value="2">Teacher Feedback</option>
                </select>
            </td>
    </tr>

   <tr>
        <td valign="top" align="right" class="contenttab_internal_rows"><b>&nbsp;&nbsp;&nbsp;Visible From<?php echo REQUIRED_FIELD ?></b></td>
            <td valign="top" align="left" width="50%" ><b>&nbsp;:&nbsp;<b><?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('startDate1','');
        ?>
        </td>
    </tr>
    <tr>
        <td valign="top" align="right" class="contenttab_internal_rows"><b>&nbsp;&nbsp;&nbsp;Visible To<?php echo REQUIRED_FIELD ?></b></td>
        <td valign="top" align="left" width="50%" ><b>&nbsp;:&nbsp;<b><?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('toDate1','');
            ?>
        </td>
   </tr>

   <tr>
        <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;No. of Attempts<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding"><b>:</b>&nbsp;<input type="text" id="noAttempts" name="noAttempts" class="inputbox" maxlength="100" style="width:100px" /></td>
    </tr>
   <tr>
   <tr>
     <td height="5px" colspan="2"></td>
    </tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditFeedBackLabel');return false;" />
    </td>
</tr>
<tr>
    <td height="5px" colspan="2"></td></tr>
<tr>
</table>
</form>
    <?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->


<?php
// $History: listFeedBackLabelContents.php $
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
//*****************  Version 8  *****************
//User: Jaineesh     Date: 4/14/09    Time: 6:17p
//Updated in $/Leap/Source/Templates/FeedBack
//modified in feedback label & role wise graph
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 1/16/09    Time: 2:17p
//Updated in $/Leap/Source/Templates/FeedBack
//modified left alignment
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 1/12/09    Time: 2:55p
//Updated in $/Leap/Source/Templates/FeedBack
//make changes for sending sendReq() function
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 1/10/09    Time: 10:48a
//Updated in $/Leap/Source/Templates/FeedBack
//modified for left alignment
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/09/09    Time: 5:58p
//Updated in $/Leap/Source/Templates/FeedBack
//fixed the bugs
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/06/09    Time: 6:29p
//Updated in $/Leap/Source/Templates/FeedBack
//modified in code for edit no. of attempts
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/06/09    Time: 4:17p
//Updated in $/Leap/Source/Templates/FeedBack
//modified template to show new fields
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/15/08   Time: 1:41p
//Created in $/Leap/Source/Templates/FeedBack
//Created FeedBack Masters
?>