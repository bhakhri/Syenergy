<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR CITY LISTING 
// Author :Dipanjan Bhattacharjee 
// Created on : (11.01.2010)
// Copyright 2008-2010: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
      <?php    require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                <td class="contenttab_border" height="20">
                
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Feedback Questions Mapping Detail : </td>
                        <td class="content_title"></td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <form name="QuestionFrm" method="post" onsubmit="return false;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                 <tr>
                  <td class="contenttab_internal_rows" width="3%" nowrap><b>Time Table</b></td>
                  <td class="padding" width="1%">:</td>
                  <td class="padding" width="10%">
                   <select name="timeTableLabelId" id="timeTableLabelId" class="inputbox" onchange="getSurveyLabel();">
                     <option value="">Select</option>
                     <?php
                     require_once(BL_PATH.'/HtmlFunctions.inc.php');
                     echo HtmlFunctions::getInstance()->getTimeTableLabelData();
                     ?>
                   </select>
                  </td>
                  </tr>
                  <tr>
                  <td class="contenttab_internal_rows" width="3%"><b>Label</b></td>
                  <td class="padding" width="1%">:</td>
                  <td class="padding" width="10%">
                   <select name="labelId" id="labelId" class="inputbox" onchange="vanishData(1);">
                     <option value="">Select</option>
                     <?php
                       //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                       //echo HtmlFunctions::getInstance()->getAdvFeedBackLabelData();
                     ?>
                   </select>
                  </td>
                  <td class="contenttab_internal_rows" width="3%"><b>Category</b></td>
                  <td class="padding" width="1%">:</td>
                  <td class="padding" width="10%">
                   <select name="catId" id="catId" class="inputbox" onchange="vanishData(1);">
                     <option value="">Select</option>
                     <?php
                       require_once(BL_PATH.'/HtmlFunctions.inc.php');
                       echo HtmlFunctions::getInstance()->getAdvFeedBackCategoryData();
                     ?>
                   </select>
                  </td>
                  <td class="contenttab_internal_rows" width="8%"><b>Question Set</b></td>
                  <td class="padding" width="1%">:</td>
                  <td class="padding" width="10%">
                   <select name="questionSetId" id="questionSetId" class="inputbox" onchange="vanishData(1);">
                     <option value="">Select</option>
                     <?php
                       require_once(BL_PATH.'/HtmlFunctions.inc.php');
                       echo HtmlFunctions::getInstance()->getAdvFeedBackQuestionSetData();
                     ?>
                   </select>
                  </td>
                  <td class="padding">
                    <input type="image" name="imageField1" src="<?php echo IMG_HTTP_PATH;?>/show_list.gif"  onClick="return getQuestionData();return false;" />
                  </td>
               </tr>
               <tr>
                <td colspan="13" valign="top">
                    <div id="results"></div>
                </td>
              </tr>
              <tr id="saveTrId" style="display:none;">
                <td colspan="13" valign="top" align="center">
                    <input type="image" name="imageField2" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateAddForm();return false;" />
                </td>
              </tr>
          </table>
          </form>
        </td>
    </tr>
   </table>
  </td>
 </tr>
</table>

<?php
// $History: listFeedBackQuestionMappingAdvContents.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 1/21/10    Time: 5:22p
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Updated breadcrumbs and titles
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 19/01/10   Time: 15:24
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Corrected validation message
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 1/18/10    Time: 1:14p
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Added timetable label dropdown
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 12/01/10   Time: 10:55
//Created in $/LeapCC/Templates/FeedbackAdvanced
//Created module "Feedback Question Mapping (Advanced)"
?>