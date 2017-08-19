<?php 
//This file creates Html Form output in Notice Module 
//
// Author :Rajeev Aggarwal
// Created on : 15-10-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
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
                    <td valign="top" colspan="2">Reports&nbsp;&raquo;&nbsp; Examination Reports &nbsp;&raquo;&nbsp;Eaxm Statistics Report</td>
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
                                    <form name="examForm" id="examForm" action="" method="post" onSubmit="return false;">
                                        <table width="100%" align="center" border="0" cellpadding="0px" cellspacing="0" >
                                            <tr>
                                                <td class="padding" align="left" nowrap>
                                                    <strong>Class</strong>
                                                </td>
                                                <td class="padding" align="left" nowrap>
                                                    <strong>Teacher</strong>
                                                </td>
                                                <td class="padding" align="left" nowrap>
                                                    <strong>Subject</strong>
                                                </td>
                                                <td class="padding" align="left" nowrap>
                                                    <strong>Group</strong>
                                                </td>
                                                <td class="padding" align="left" nowrap>
                                                    <strong>Test Type Category</strong>
                                                </td>
                                             </tr>
                                             <tr>   
                                                <td align="left" class="padding" nowrap >
                                                <select size="5" multiple class="htmlElement2" name="degreeId[]" id="degreeId[]" style="width:190px;">
                                                   <?php 
                                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                        echo HtmlFunctions::getInstance()->getClassData();?>
                                                   ?>
                                                </select><br>
                                                <div align="left">
                                                Select &nbsp;
                                                <a class="allReportLink" href="javascript:makeSelection('degreeId[]','All','examForm');">All</a> / 
                                                <a class="allReportLink" href="javascript:makeSelection('degreeId[]','None','examForm');">None</a></nobr>
                                                </div>
                                               </td>
                                                <td align="left" class="padding" nowrap >
                                                <select size="5" multiple class="htmlElement2" name="teacherId[]" id="teacherId[]" style="width:190px;">
                                                   <?php 
                                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                        echo HtmlFunctions::getInstance()->getTeacher($REQUEST_DATA['teacher']);
                                                   ?>
                                                </select><br>
                                                <div align="left">
                                                Select &nbsp;
                                                <a class="allReportLink" href="javascript:makeSelection('teacherId[]','All','examForm');">All</a> / 
                                                <a class="allReportLink" href="javascript:makeSelection('teacherId[]','None','examForm');">None</a></nobr>
                                                </div>
                                               </td>
                                                <td align="left" class="padding" nowrap >
                                                <select size="5" multiple class="htmlElement2" name="subjectId[]" id="subjectId[]" style="width:190px;">
                                                   <?php 
                                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                        echo HtmlFunctions::getInstance()->getSubjectData();
                                                   ?>
                                                </select><br>
                                                <div align="left">
                                                Select &nbsp;
                                                <a class="allReportLink" href="javascript:makeSelection('subjectId[]','All','examForm');">All</a> / 
                                                <a class="allReportLink" href="javascript:makeSelection('subjectId[]','None','examForm');">None</a></nobr>
                                                </div>
                                               </td>
                                               <td align="left" class="padding" nowrap >
                                               <select size="5" multiple class="htmlElement2" name="groupId[]" id="groupId[]" style="width:190px;"> -->
                                                   <?php 
                                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                        echo HtmlFunctions::getInstance()->getGroupData();
                                                   ?>
                                                </select><br><div align="left">
                                                Select &nbsp;
                                                <a class="allReportLink" href="javascript:makeSelection('groupId[]','All','examForm');">All</a> / 
                                                <a class="allReportLink" href="javascript:makeSelection('groupId[]','None','examForm');">None</a></nobr>
                                                </div>
                                               </td>
                                                <td align="left" class="padding" nowrap >
                                                <select size="5" multiple class="htmlElement2" name="testTypeCategoryId[]" id="testTypeCategoryId[]" style="width:190px;">
                                                   <?php 
                                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                        echo HtmlFunctions::getInstance()->getTestTypeCategory();?>
                                                   ?>
                                                </select><br>
                                                <div align="left">
                                                Select &nbsp;
                                                <a class="allReportLink" href="javascript:makeSelection('testTypeCategoryId[]','All','examForm');">All</a> / 
                                                <a class="allReportLink" href="javascript:makeSelection('testTypeCategoryId[]','None','examForm');">None</a></nobr>
                                                </div>
                                               </td>
                                               <td align="left" class="padding" valign="bottom" style="padding-left:20px;"><nobr>
                                                     <input type="image" name="examSubmit" value="examSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm();return false;" />
                                                </nobr>     
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
                                            <td colspan="1" class="content_title">Exam Statistics Report :</td>
                                            <td class="content_title" align="right">
                  <input type="image" name="print" value="print" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
                  <input type="image" name="printCSV" value="printCSV" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV()" />&nbsp;
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
              <input type="image" name="print" value="print" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
              <input type="image" name="printCSV" value="printCSV" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV()" />&nbsp;
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
 
<?php 
//$History: listExamStatisticsContents.php $
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/10/10    Time: 4:23p
//Updated in $/LeapCC/Templates/Management
//function updated degree
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/10/10    Time: 4:10p
//Created in $/LeapCC/Templates/Management
//initial checkin
//

?>