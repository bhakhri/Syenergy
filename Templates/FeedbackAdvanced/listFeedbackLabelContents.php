<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR FeedBack Label LISTING 
//
// Author :Gurkeerat Sidhu 
// Created on : (08.01.2010)
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddFeedBackLabel',320,350);blankValues();return false;" />&nbsp;</td></tr>
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
    <td height="5px" colspan="3"></td>                                            
    </tr>
    <tr>
        <td width="30%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Name<?php echo REQUIRED_FIELD ?> </b></nobr></td>
        <td width="70%" class="padding"><b>:</b>
        </td>
         <td valign="top" class="padding"><input type="text" id="feedbackSurveyLabel" name="feedbackSurveyLabel" class="inputbox" maxlength="100" /></td>
    </tr>
    <tr>    
        <td  class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Active</b></nobr></td>
        <td  class="padding" ><b>:</b></td>
         <td valign="top" class="contenttab_internal_rows">
         <input type="radio" name="isActive" id="isActive1" value="1" />Yes&nbsp;
         <input type="radio" name="isActive" id="isActive1" value="0" />No&nbsp;
        </td>
    </tr>
    <tr>
          <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Time Table<?php echo REQUIRED_FIELD ?></b></nobr></td>
          <td class="padding" ><b>:</b></td>
           <td valign="top" class="padding">
             <select size="1" class="inputbox" name="timeTableLabelId" id="timeTableLabelId" style="width:184px;">
                <option value="">Select</option>
                <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getTimeTableLabelData();
                ?>
           </select>
          </td>
    </tr>
   <tr>
        <td class="contenttab_internal_rows" ><b>&nbsp;&nbsp;&nbsp;Visible From</b></td>
        <td class="padding"><b>:</b> </td>
         <td valign="top" class="padding">
            <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('startDate',Date('Y-m-d'));
        ?>
        </td>
    </tr>
    <tr>
        <td class="contenttab_internal_rows"><b>&nbsp;&nbsp;&nbsp;Visible To</b></td>
        <td class="padding" ><b>:</b></td>
         <td valign="top" class="padding">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('toDate',Date('Y-m-d'));
            ?>
        </td>
   </tr>
   <tr>
        <td class="contenttab_internal_rows"><b>&nbsp;&nbsp;&nbsp;Extend To</b></td>
        <td class="padding" ><b>:</b></td>
         <td valign="top" class="padding">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('extendDate',Date('Y-m-d'));
            ?>
        </td>
   </tr>
   <tr>    
        <td  class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Feedback By</b></nobr></td>
        <td  class="padding" ><b>:</b></td>
         <td valign="top" class="contenttab_internal_rows">
         <input type="radio" name="roleId" id="roleI1" value="1" checked="checked" />Teacher&nbsp;
         <input type="radio" name="roleId" id="roleI2" value="0" />Parent&nbsp;
         <input type="radio" name="roleId" id="roleI3" value="0" />Student&nbsp;
        </td>
    </tr>
   <tr>
        <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;No. of Attempts<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding"><b>:</b></td>
         <td valign="top" class="padding">
        <input type="text" id="noOfAttempts" name="noOfAttempts" class="inputbox" maxlength="100" style="width:50px;" /></td>
    </tr>
    <tr><td height="5px" colspan="3"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="3">
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddFeedBackLabel');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
    </td>
</tr>
<tr><td height="5px" colspan="3"></td></tr>
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
     <td height="5px" colspan="3"></td>
    </tr>
    <tr>
        <td  class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Name<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td  class="padding"><b>:</b> </td>
         <td valign="top" class="padding">
        <input type="text" id="feedbackSurveyLabel" name="feedbackSurveyLabel" class="inputbox" maxlength="100" /></td>
    </tr>
    <tr>    
        <td  class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Active</b></nobr></td>
        <td  class="padding"><b>:</b></td>
         <td valign="top" class="contenttab_internal_rows">
         <input type="radio" id="isActive1" name="isActive" value="1" />Yes&nbsp;
         <input type="radio" id="isActive2" name="isActive" value="0" />No&nbsp;
        </td>
    </tr>
    <tr>
          <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Time Table<?php echo REQUIRED_FIELD ?></b></nobr></td>
          <td class="padding" tabindex="28"><b>:</b></td>
          <td valign="top" class="padding">
            <select size="1" class="inputbox1" name="timeTableLabelId" id="timeTableLabelId">
              <option value="">Select</option>
                <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getTimeTableLabelData();
                ?>
              </select>
            </td>
    </tr>
   <tr>
        <td class="contenttab_internal_rows" ><b>&nbsp;&nbsp;&nbsp;Visible From</b></td>
        <td class="padding"><b>:</b> </td>
         <td valign="top" class="padding">
            <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('startDate1','');
        ?>
        </td>
    </tr>
  <tr>
        <td class="contenttab_internal_rows" ><b>&nbsp;&nbsp;&nbsp;Visible To</b></td>
        <td class="padding"><b>:</b> </td>
         <td valign="top" class="padding">
            <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('toDate1','');
            ?>
        </td>
   </tr>
   <tr>
        <td class="contenttab_internal_rows"><b>&nbsp;&nbsp;&nbsp;Extend To</b></td>
        <td class="padding" ><b>:</b></td>
         <td valign="top" class="padding">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('extendDate1','');
            ?>
        </td>
   </tr>
   <tr>    
        <td  class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Feedback By</b></nobr></td>
        <td  class="padding" ><b>:</b></td>
         <td valign="top" class="contenttab_internal_rows">
         <input type="radio" name="roleId" id="roleI4" value="1" />Teacher&nbsp;
         <input type="radio" name="roleId" id="roleI5" value="0" />Parent&nbsp;
         <input type="radio" name="roleId" id="roleI6" value="0" />Student&nbsp;
        </td>
    </tr>
   <tr>
        <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;No. of Attempts<?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding"><b>:</b></td>
         <td valign="top" class="padding">
        <input type="text" id="noOfAttempts" name="noOfAttempts" class="inputbox" maxlength="100" style="width:50px;"  /></td>
    </tr>
   <tr><td height="5px" colspan="3"></td></tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="3">
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditFeedBackLabel');return false;" />
    </td>
</tr>
<tr><td height="5px" colspan="3"></td></tr>
</table>
</form>
    <?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->
<!--Divs for multiple selected dds-->
<!--<div style="display:none;position:fixed;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF" id="d11"></div>
<div style="display:none;position:fixed;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF" class="inputbox" id="d22" >
  <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" >
       <tr>
          <td id="d33" width="95%" valign="middle" style="padding-left:3px;" class="contenttab_internal_rows"></td>
          <td width="5%">
          <img id="downArrawId" src="<?php echo IMG_HTTP_PATH;?>/down_arrow.gif" style="margin-bottom:0px;" onClick="popupMultiSelectDiv('activeClass1','d11','containerDiv1','d33',true,true);" />
          </td>
        </tr>
     </table>
 </div>
 
<div style="display:none;position:fixed;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF" id="d111"></div>
<div style="display:none;position:fixed;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF" class="inputbox" id="d222" >
  <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" >
       <tr>
          <td id="d333" width="95%" valign="middle" style="padding-left:3px;" class="contenttab_internal_rows"></td>
          <td width="5%">
          <img id="downArrawId" src="<?php echo IMG_HTTP_PATH;?>/down_arrow.gif" style="margin-bottom:0px;" onClick="popupMultiSelectDiv('activeClass2','d111','containerDiv11','d333',true,true);" />
          </td>
        </tr>
     </table>
 </div> -->

<?php
// $History: listFeedbackLabelContents.php $
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 3/04/10    Time: 12:37p
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Label Master Modified : One new field added ("Extend To")
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 18/02/10   Time: 15:50
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Done bug fixing.
//Bug ids---
//0002895,0002896,0002894,0002892,
//0002891,0002882,0002833
//
//*****************  Version 7  *****************
//User: Gurkeerat    Date: 2/09/10    Time: 7:33p
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Updated code to autoinsert data in teacher mapping module 
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 25/01/10   Time: 15:52
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Made UI related changes as instructed by sachin sir
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 22/01/10   Time: 13:18
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Corrected html coding
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 1/21/10    Time: 5:22p
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Updated breadcrumbs and titles
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/01/10   Time: 16:03
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Modified "Feedback Label Master(Advanced)" as two new fields are added
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 1/12/10    Time: 1:11p
//Created in $/LeapCC/Templates/FeedbackAdvanced
//created file under feedback advanced label module

?>