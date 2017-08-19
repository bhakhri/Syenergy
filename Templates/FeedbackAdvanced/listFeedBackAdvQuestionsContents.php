<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR FeedBackQuestions LISTING 
//
//
// Author :Gurkeerat Sidhu 
// Created on : (13.01.2010)
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
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-left:5px">
								<img src="<?php echo IMG_HTTP_PATH;?>/add.gif" title ='Add' onClick="displayWindow('AddFeedBackQuestions',600,250);blankValues();return false;" />
								</td>
							</tr>
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
    <!--Start Add Div-->

<?php floatingDiv_Start('AddFeedBackQuestions','Add Feedback Questions'); ?>
    <form name="AddFeedBackQuestions" action="" method="post" onsubmit="return false;">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="5%" class="contenttab_internal_rows"><nobr><b>Question Set <?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td  class="padding">:&nbsp;
        <select size="1" class="selectfield" name="questionSet" id="questionSet">
        <option value="">SELECT</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getAdvFeedBackQuestionSetData();
              ?>
        </select>
        </td>
    </tr>
  
    <tr>
        <td height="5px" colspan="2">
            <select name='answerSetHidden' id='answerSetHidden' style='display:none;'>
            
            <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->getFeedbackAdvOptionsData();
            ?>
            </select>
        </td>
    </tr> 
    <tr>
    <td width="100%" colspan="2" style="width:630px">
    <div id="tableDiv" style="height:200px;overflow:auto;">
    <table width="100%" border="0" cellspacing="2" cellpadding="0" id="anyid">
        <tbody id="anyidBody">
            <tr class="rowheading">
                <td width="5%" class="contenttab_internal_rows"><b>S.No.</b></td>
                <td width="60%" class="contenttab_internal_rows"><b>Question</b></td>
                <td width="30%" class="contenttab_internal_rows"><b>Answer Set</b></td>
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
      
 
   
<tr><td height="5px" colspan="2"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddFeedBackQuestions');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr><td height="5px" colspan="2"></td></tr>


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
        <td width="25%" class="contenttab_internal_rows">&nbsp;&nbsp;<nobr><b>Question Set  <?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td width="75%" class="padding">:&nbsp;
        <select size="1" class="selectfield" name="questionSet" id="questionSet">
        <option value="">SELECT</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getAdvFeedBackQuestionSetData();
              ?>
        </select>
        </td>
    </tr>
    <tr>    
        <td class="contenttab_internal_rows">&nbsp;&nbsp;<nobr><b>Answer Set <?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding">:&nbsp;
         <select size="1" class="selectfield" name="answerSet" id="answerSet">
         <option value="">SELECT</option>
              <?php
                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                  echo HtmlFunctions::getInstance()->getFeedbackAdvOptionsData();
              ?>
        </select>
        </td>
    </tr>
     <tr>
        <td class="contenttab_internal_rows">&nbsp;&nbsp;<nobr><b>Question <?php echo REQUIRED_FIELD ?></b></nobr></td>
        <td class="padding">:&nbsp;
         <textarea id="questionTxt" name="questionTxt" cols="30" rows="4" maxlength="5000" onkeyup="return ismaxlength(this)" style="vertical-align:middle"></textarea>
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
// $History: listFeedBackAdvQuestionsContents.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 20/02/10   Time: 12:25
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Done bug fixing.
//Bug ids---
//0002923,0002322,0002921,0002920,0002919,
//0002918,0002917,0002916,0002915,0002914,
//0002912,0002911,0002913
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 2/02/10    Time: 5:31p
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Updated code to add multiple questions at the same time
//
//*****************  Version 1  *****************
//User: Gurkeerat    Date: 1/21/10    Time: 5:39p
//Created in $/LeapCC/Templates/FeedbackAdvanced
//Created file under question master in feedback module
//

?>