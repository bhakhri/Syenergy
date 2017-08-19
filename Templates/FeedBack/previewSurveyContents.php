<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR CITY LISTING 
//
//
// Author :Rajeev Aggarwal
// Created on : (19.05.2009)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
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
                        <td class="content_title">Preview Survey: </td>
                        <td class="content_title" ></td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" width="100%"  height="400">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" >
                 <tr>
                  <td class="contenttab_internal_rows" style="padding-left:5px"><b>Survey Type</b><?php echo REQUIRED_FIELD; ?></td>
                  <td class="padding">:</td>
                  <td>
                    <select name="surveyType" id="surveyType" class="selectfield" onchange="getSourceSurvey(this.value)">
                     <option value="">Select</option>
                     <option value="1">General Feedback</option>
                     <option value="2">Teacher Feedback</option>
                    </select>
                  </td>
                   
                 
                   
                  <td class="contenttab_internal_rows" style="padding-left:20px"><b>Feedback Survey</b><?php echo REQUIRED_FIELD; ?></td>
                  <td class="padding">:</td>
                  <td>
                    <select name="sourceSurvey" id="sourceSurvey" class="inputbox1" style="width:400px">
                     <option value="">Select</option>
                     </select>
                  </td>
                   
                 
				  <td style="padding-left:10px">
                   <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm();return false;" />
                  </td>
                 </tr>
                 <tr><td colspan="10" height="5px"></td></tr> 
                 
               
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
<?php
// $History: previewSurveyContents.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/FeedBack
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 6/24/09    Time: 11:28a
//Updated in $/LeapCC/Templates/FeedBack
//0000271: Preview Survey -Admin > Mandatory signs must be mentioned
//against each field. 
//
//*****************  Version 2  *****************
//User: Administrator Date: 11/06/09   Time: 11:15
//Updated in $/LeapCC/Templates/FeedBack
//Done bug fixing.
//bug ids---
//0000011,0000012,0000016,0000018,0000020,0000006,0000017,0000009,0000019
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/21/09    Time: 6:34p
//Created in $/LeapCC/Templates/FeedBack
//Intial checkin
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/19/09    Time: 4:51p
//Created in $/Leap/Source/Templates/FeedBack
//Added Preview survey related function.
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 5/19/09    Time: 10:47a
//Updated in $/Leap/Source/Templates/FeedBack
//Added functionality to add question options(grades) along with feedback
//question
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 15/04/09   Time: 17:26
//Updated in $/Leap/Source/Templates/FeedBack
//Corrected bugs
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 15/04/09   Time: 14:53
//Updated in $/Leap/Source/Templates/FeedBack
//Modified title
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 15/04/09   Time: 14:45
//Created in $/Leap/Source/Templates/FeedBack
//Created "Copy Survey" Module
?>