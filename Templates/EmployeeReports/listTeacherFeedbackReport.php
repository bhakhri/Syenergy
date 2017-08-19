<?php 
//it contain the template of teacher survery feedback 
//
// Author :Parveen Sharma
// Created on : 02-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">Reports &nbsp;&raquo;&nbsp; Teacher Survey Feedback </td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">

            <tr>
             <td valign="top" class="content">
            
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                <td class="contenttab_border2" height="20">
                    <table width="30%" border="0" cellspacing="0" cellpadding="0" align="center">
                    <tr>
                        <td class="contenttab_internal_rows"><nobr><b>Survey: </b></nobr></td>
                        <td class="padding">
                        <select size="1" class="inputbox1" name="feedbackSurveyId" id="feedbackSurveyId" onchange="getTeacher()">
                        <?php
                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                           echo HtmlFunctions::getInstance()->getFeedbackSurvey($REQUEST_DATA['feedbackSurvey']);
                        ?>
                        </select></td>
                        <td class="contenttab_internal_rows"><nobr><b>Teacher: </b></nobr></td>
                        <td class="padding">
                        <select size="1" class="selectfield" name="teacherId" id="teacherId" onchange="getTeacherClear()">
                        
                        </select>
                        </td>
                        <td align="left"  valign="middle">
                         <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return getTeacherFeedBackData();return false;" />
                        </td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
             <td valign="top" >
             <!--Teacher Survey Feedback Data Template-->
              <div id="results">
              
              </div>
            <!--Teacher Survey Feedback Data Template Ends-->           
             </td>
          </tr>
          </table>
        </td>
    </tr>
  </table>
 
<?php
//$History: listTeacherFeedbackReport.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/23/08   Time: 3:44p
//Created in $/LeapCC/Templates/EmployeeReports
//initial checkin
//
//*****************  Version 6  *****************
//User: Parveen      Date: 12/10/08   Time: 9:29a
//Updated in $/Leap/Source/Templates/ScEmployeeReports
//
//*****************  Version 5  *****************
//User: Parveen      Date: 12/05/08   Time: 2:04p
//Updated in $/Leap/Source/Templates/ScEmployeeReports
//list name change modify (score)
//
//*****************  Version 4  *****************
//User: Parveen      Date: 12/04/08   Time: 10:40a
//Updated in $/Leap/Source/Templates/ScEmployeeReports
//html tags & formatting settings
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/02/08   Time: 5:08p
//Updated in $/Leap/Source/Templates/ScEmployeeReports
//teacher feedback update
//
//
//

?>