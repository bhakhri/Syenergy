<?php
//----------------------------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR student Achievements/Offence Details 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (8.7.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td height="10"></td>
                </tr>
                <tr>                                                            
                    <td valign="middle">Reports&nbsp;&raquo;&nbsp;Achievements/Offense of Students</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
                <tr>
                    <td valign="top" class="content">
                        <!-- form table starts -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
                            <tr>
                                <td valign="top" class="contenttab_row1">
                                    <form name="searchForm" id="searchForm" action="" method="post" onSubmit="return false;">
                                        <table align="center" border="0" cellpadding="5px"  cellspacing="0"  width="80%">
                                            <tr>
                                                <td class="contenttab_internal_rows" colspan="1" align="right">
                                                    <strong>Class&nbsp;:&nbsp;</strong> &nbsp;
                                                </td>
                                                <td align="left">
                                                    <select size="1" class="selectfield" name="classId" id="classId" onchange="populateSubjects(this.value);">
                                                        <option value="">Select</option>
                                                        <?php
                                                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                           echo HtmlFunctions::getInstance()->getTeacherClassData();
                                                        ?>
                                                    </select>
                                                </td>
                                                <td class="contenttab_internal_rows" colspan="1" align="right">
                                                    <strong>Subject&nbsp;:&nbsp;</strong> &nbsp;
                                                </td>
                                                <td align="left">
                                                    <select size="1" class="selectfield" name="subject" id="subject" onchange="populateGroups(document.searchForm.classId.value,this.value);" >
                                                        <option value="">Select</option>
                                                     </select>
                                                </td>
                                                <td class="contenttab_internal_rows" colspan="1" align="right">
                                                    <strong>Group&nbsp;:&nbsp;</strong> &nbsp;
                                                </td>
                                                <td align="left">
                                                    <select size="1" class="selectfield" name="group" id="group" onchange="clearData(3);" >
                                                        <option value="">Select</option>
                                                    </select>               
                                                </td>
                                             </tr>
                                             <tr>   
                                                <td class="contenttab_internal_rows" colspan="1" align="right">
                                                    <strong>Roll No&nbsp;:&nbsp;</strong> &nbsp;
                                                </td>
                                                <td align="left">
                                                    <input type="text" name="rollNo" id="rollNo" class="inputbox"  style="width:180px"/>                         
                                                </td>
                                                <td align="left" colspan="4">
                                                    <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="getData();return false" />
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </td>
                            </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr id='nameRow' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                        <tr>
                                            <td colspan="1" class="content_title">Achievements/Offence of Students : </td>
                                            <td colspan="2" class="content_title" align="right">
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();return false;"/> 
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id='resultRow' style='display:none;'>
                                <td colspan='1' class='contenttab_row'>
                                    <div id = 'resultsDiv'></div>
                                </td>
                            </tr>
                            <tr id='nameRow2' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                        <tr>
                                            <td colspan="2" class="content_title" align="right">
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();return false;"/>&nbsp;
            <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();return false;"/> 
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <!-- form table ends -->
                    </td>
                </tr>
            </table>
        </table>

<!--Start Notice  Div-->
<?php floatingDiv_Start('divMessage','Brief Description '); ?>
<form name="MessageForm" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>    
        <td width="89%"><div id="message" style="overflow:auto; width:400px; height:200px" ></div></td>
    </tr>
</table>
</form> 
<?php floatingDiv_End(); ?>
        
<?php
// $History: listAchievementsOffenceContents.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/29/09    Time: 4:15p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//initial checkin 
//

?>