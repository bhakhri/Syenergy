<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR feed back grades 
//
//
// Author :Gurkeerat Sidhu 
// Created on : (12.1.2010 )
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img title="Add" src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddFeedbackOptions',355,250);blankValues();return false;" />&nbsp;</td></tr>
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
<!--Start Add Div-->
<?php floatingDiv_Start('AddFeedbackOptions','Add Answer Set Options'); ?>
<form name="AddFeedbackOptions" action="" method="post">  
 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
  <tr>
     <td width="5%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Answer Set<?php echo REQUIRED_FIELD ?></b></nobr></td>
     <td class="padding" >:&nbsp;
      <select name="answerSet" id="answerSet" class="inputbox" >
        <option value="">SELECT</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getFeedbackAdvOptionsData();
           ?>
      </select>
     </td>
   </tr>
   <tr>
    <td width="100%" colspan="2" style="width:670px">
    <div id="tableDiv" style="height:200px;overflow:auto;">
    <table width="100%" border="0" cellspacing="2" cellpadding="0" id="anyid">
        <tbody id="anyidBody">
            <tr class="rowheading">
                <td width="5%" class="contenttab_internal_rows"><b>S.No.</b></td>
                <td width="45%" class="contenttab_internal_rows"><b>Option Text</b></td>
                <td width="20%" class="contenttab_internal_rows"><b>Option Weight</b></td>
                <td width="20%" class="contenttab_internal_rows"><b>Print Order</b></td>
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
       <a href="javascript:addOneRow(1);" title="Add Row"><b><nobr>Add More</b></a>
    </td>
    </tr> 
  
  <tr>
    <td height="5px" colspan="2"></td></tr>
 <tr>
    <td align="center" style="padding-right:10px" colspan="4">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
       <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddFeedbackOptions');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
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
<?php floatingDiv_Start('EditFeedbackOptions','Edit Answer Set Options '); ?>
<form name="EditFeedbackOptions" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="answerSetOptionId" id="answerSetOptionId" value="" />  
     <tr>
     <td width="25%" class="contenttab_internal_rows">&nbsp;<nobr><nobr><strong>Answer Set<?php echo REQUIRED_FIELD ?></strong></nobr></td>
     <td width="25%" class="padding" colspan="3">:&nbsp;
      <select name="answerSet" id="answerSet" class="inputbox" style="width:210px;">
        <option value="">SELECT</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getFeedbackAdvOptionsData();
           ?>
      </select>
     </td>
   </tr>
   <tr>
     <td width="30%" class="contenttab_internal_rows">&nbsp;<nobr><strong>Option Text<?php echo REQUIRED_FIELD ?></strong></nobr></td>
     <td width="24%" class="padding" colspan="3">:&nbsp;
      <input type="text" id="optionLabel" name="optionLabel" class="inputbox" style="width:205px;" maxlength="100"/>
     </td>
    </tr>
    <tr> 
      <td width="22%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Option Weight<?php echo REQUIRED_FIELD ?></strong></nobr></td>
      <td width="24%" class="padding">:&nbsp;
      <input type="text" id="optionPoints" name="optionPoints"  style="width:50px" class="inputbox" maxlength="5"/>
     </td>
     <td width="22%" class="contenttab_internal_rows"><nobr>&nbsp;&nbsp;<strong>Print Order<?php echo REQUIRED_FIELD ?></strong></nobr></td>
      <td width="24%" class="padding">:&nbsp;
      <input type="text" id="printOrder" name="printOrder"  style="width:50px" class="inputbox" maxlength="5"/>
     </td>
   </tr>
  
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="4">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditFeedbackOptions');return false;" />
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
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 20/02/10   Time: 12:25
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Done bug fixing.
//Bug ids---
//0002923,0002322,0002921,0002920,0002919,
//0002918,0002917,0002916,0002915,0002914,
//0002912,0002911,0002913
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 2/06/10    Time: 6:51p
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//made enhancements under feedback module
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 25/01/10   Time: 15:52
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Made UI related changes as instructed by sachin sir
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 1/14/10    Time: 6:22p
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Resolved issue: 0002609,0002607,0002608,0002610,0002611,
//0002612,0002613
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 1/13/10    Time: 11:24a
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Fixed issue as list was not populating
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 1/12/10    Time: 5:20p
//Created in $/LeapCC/Templates/FeedbackAdvanced
//Created file under Feedback Advanced Answer Set Options Module


?>