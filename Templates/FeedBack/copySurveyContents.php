<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR CITY LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
       <?php   require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
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
                        <td class="content_title">Copy Questions : </td>
                        <td class="content_title" ></td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" width="100%"  height="400">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" >
                 <tr>
                  <td class="contenttab_internal_rows"><b>Survey Type <?php echo REQUIRED_FIELD; ?></b></td>
                  <td class="padding">:</td>
                  <td>
                    <select name="surveyType" id="surveyType" class="selectfield" onchange="getSourceSurvey(this.value)">
                     <option value="">Select</option>
                     <option value="1">General Feedback</option>
                     <option value="2">Teacher Feedback</option>
                    </select>
                  </td>
                   
                 </tr>
                 <tr><td colspan="10" height="5px"></td></tr> 
				 <tr>
                   
                  <td class="contenttab_internal_rows" ><b>Survey Source <?php echo REQUIRED_FIELD; ?></b></td>
                  <td class="padding">:</td>
                  <td>
                    <select name="sourceSurvey" id="sourceSurvey" class="selectfield" onchange="getCopySurvey(this.value);">
                     <option value="">Select</option>
                     </select>
                  </td>
                  <td class="contenttab_internal_rows" style="padding-left:20px"><b>Copy Survey to <?php echo REQUIRED_FIELD; ?></b></td>
                  <td class="padding">:</td>
                  <td>
                    <select name="copySurvey" id="copySurvey" class="selectfield">
                     <option value="">Select</option>
                     </select>
                  </td>
                  <td class="contenttab_internal_rows" style="padding-left:20px"><b>Copy Survey Options</b></td>
                  <td class="padding">:</td>
                  <td><input type='checkbox' name='copyOption' id='copyOption' value='1'>
                  </td>
                 
				  <td style="padding-left:10px">
                   <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return getData();return false;" />
                  </td>
                 </tr>
                 <tr><td colspan="10" height="5px"></td></tr> 
                <tr>
                 <td colspan="10">
                <!--Main Data Div-->
                    <form name="listFrm" onsubmit="return false;">
                    <!--********DO NOT DELETE THIS*****-->
                         <input type="hidden" name="questions" id="questions" value="" />
                         <input type="hidden" name="questions" id="questions" value="" />
                    <!--********DO NOT DELETE THIS*****--> 
                        <div id="results">  
                        </div>
                    </form> 
                <!--Main Data Div(ends)-->
                </td>
               </tr>
               <tr id="buttonId" style="display:none">
                <td align="center" colspan="10" >
                  <input type="image" name="imageField1" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm();return false;" />
                  <input type="image" name="addCancel"  src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="hideData(3);return false;" /> 
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
<?php
// $History: copySurveyContents.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/FeedBack
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 23/06/09   Time: 14:46
//Updated in $/LeapCC/Templates/FeedBack
//Done bug fixing.
//bug ids----
//00000187,00000191,00000198,00000199,00000203,00000204,
//00000205,00000207,0000209,00000211
//
//*****************  Version 2  *****************
//User: Administrator Date: 11/06/09   Time: 11:15
//Updated in $/LeapCC/Templates/FeedBack
//Done bug fixing.
//bug ids---
//0000011,0000012,0000016,0000018,0000020,0000006,0000017,0000009,0000019
//
//*****************  Version 1  *****************
//User: Administrator Date: 21/05/09   Time: 11:15
//Created in $/LeapCC/Templates/FeedBack
//Copied "Feedback Master Modules" from Leap to LeapCC
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