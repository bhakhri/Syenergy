<?php
//-------------------------------------------------------
// Purpose: to design the layout for providing feedback
// Author : Dipanjan Bhattacharjee
// Created on : (19.01.2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
?>
 <form method="POST" name="addForm"  action="" id="addForm" method="post" style="display:inline" onsubmit="return false;">

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td>
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
           
            <tr>
                  <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?> 
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
             <td class="content" valign="top">
             <table width="100%" border="0" cellspacing="0" cellpadding="0" height="475">
            
             <tr>
                <td class="contenttab_row"  valign="top" style="border-bottom:0px;">
                 <table border="0" cellspacing="0" cellspacing="0">
                  <tr>
                   <td class="contenttab_internal_rows"><b>Choose the survey</b></td>
                   <td class="padding">:</td>
                   <td class="padding">
                    <select name="labelId" id="labelId" class="inputbox" onchange="vanishData();oldLableId='';">
                     <option value="">Select</option>
                     <?php
                       $userId=$sessionHandler->getSessionVariable('UserId');
                       echo $htmlFunctions->fetchMappedFeedbackLabelAdvForUsersData('',$roleId,$userId); //$roleId is in interface file
                     ?>
                    </select>  
                      <input type="image" style="margin-bottom:-6.5px;" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/show_questions.gif" onClick="fetchMappedCategories();return false;" />
                   </td>
                  </tr>
                  <tr>
                   <td class="contenttab_internal_rows"  colspan="3" id="noOfAttemptsTdId" style="display:none"></td>
                  </tr>  
                 </table>
                </td>
             </tr>   
             <tr>
                <td class="contenttab_row"  valign="top">
                <div id="dhtmlgoodies_tabView1" style="visibility:hidden;overflow:auto;">
				 <div class="dhtmlgoodies_aTab">
				  </div>
 			   </div>
		   <script type="text/javascript">
            var tabArray1=new Array('SampleTab');
            var tabArray2=new Array(false);
			initTabs('dhtmlgoodies_tabView1',tabArray1,0,980,405,tabArray2);
		   </script>
		   </td>
          </tr>
          <tr id="buttonTrId" style="display:none;">
            <td class="contenttab_row" align="center" style="border-top:0px;">
                <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="saveFeedbackData();return false;" />         
            </td>
          </tr>
          </table>
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
 </form>
 <?php 
// $History: provideFeedBackContents.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 3/02/10    Time: 15:28
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Done modification in Adv. Feedback modules and added the options of
//choosing teacher during subject wise feedback
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 22/01/10   Time: 17:06
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Made UI changes and modified images
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 22/01/10   Time: 12:17
//Created in $/LeapCC/Templates/FeedbackAdvanced
//Created "Provide Feedback" Module
?>