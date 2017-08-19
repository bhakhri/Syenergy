<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR student and message LISTING
//
//
// Author :Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
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
                        <td class="content_title">Feedback Assign Survey Report (Advanced) : </td>
                        <td class="content_title"></td>
                    </tr>
                    </table>
                </td>
             </tr>
          
          <!--student part(Common part)-->
          <tr id="studentSearchFilterRowId">
           <td colspan="1" class="contenttab_row" style="border-top:none;">
           <form name="allDetailsForm" action="" method="post" onSubmit="return false;">
               <table border='0' width='100%' cellspacing='0'>
                   <?php echo $htmlFunctions->makeStudentDefaultSearch(); ?>
                   <tr height='5'></tr>
                   <?php echo $htmlFunctions->makeStudentAcademicSearch(false); ?>
                   <tr height='5'></tr>
                   <?php echo $htmlFunctions->makeStudentAddressSearch(); ?>
                   <tr height='5'></tr>
                   <?php echo $htmlFunctions->makeStudentMiscSearch(); ?>
                    <td valign='top' colspan='10' class='' align='center'>
                     <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/show_students.gif" onClick="return validateCommonStudentList();return false;" />
                    </td>
                   </tr>
                   <tr><td class="contenttab_internal_rows" align="right" colspan="10"><b>UBA : Unblocked By Administrator</b></td>
                   <tr>
                    <td colspan="10">
                       <div id="studentResultsDiv"></div>   
                    </td>
                   </tr>
                   <tr>
                    <td colspan="10" id="printRowId" style="display:none" align="right">
                        <INPUT type="image" src="<?php echo IMG_HTTP_PATH ?>/print.gif" border="0" onClick="printReport()">&nbsp;<INPUT type="image" src="<?php echo IMG_HTTP_PATH ?>/excel.gif" border="0" onClick="javascript:printCSV();">
                    </td>
                   </tr>  
              </table>
             </form>
            </td>
           </tr>
          <!--Student List Showing-->
          
          <!--Student List Showing (Ends)-->
          </table>
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table> 
    
    
<?php floatingDiv_Start('studentUnBlockDiv',''); ?>
    <form name="studentUnBlockForm" action="" method="post" onsubmit="return false;">
    <input type="hidden" name="uId" id="uId" value="" />
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Reason for Unblocking<?php echo REQUIRED_FIELD; ?> : </b></nobr></td>
   </tr>
   <tr>
        <td width="100%" class="contenttab_internal_rows">
         <textarea name="unblockStatus" id="unblockStatus" cols="40" rows="5" maxlength="1000" onkeyup="return ismaxlength(this);"></textarea>
        </td>
   </tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="1">
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return makeStudentUnblock();return false;" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('studentUnBlockDiv');return false;" />
    </td>
</tr>
<tr><td height="5px"></td></tr>
</table>
</form>
<?php floatingDiv_End(); ?>    

<?php
// $History: listAssignSurveyReportsContents.php $
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/02/10    Time: 18:06
//Created in $/LeapCC/Templates/FeedbackAdvanced
//Created the repoort for showing student status for feedbacks
?>