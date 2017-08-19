<?php 
//-------------------------------------------------------
// This file generate a template Student Rank wise Report
//
// Author :Parveen Sharma
// Created on : 12-12-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------//
?>
<form name="studentRankForm" action="" method="post" onSubmit="return false;">        
  <select size="1" name="studentExamResultId" id="studentExamResultId" class='inputbox1' style='display:none' >
    <?php 
        require_once(BL_PATH.'/HtmlFunctions.inc.php');
        echo HtmlFunctions::getInstance()->getClassResult();
    ?>
  </select>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>   
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
                                        <table cellspacing="0" cellpadding="0"  border="0" >
                                        <tr>
										 <td class="contenttab_internal_rows" align="left"><nobr><strong>&nbsp;Time Table&nbsp;<?php echo REQUIRED_FIELD ?></nobr></td>
                                            <td class="contenttab_internal_rows" align="left"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>  
                                            <td class="contenttab_internal_rows" align="left">
                                                <nobr>
												
												<select size="1" class="inputbox1" name="timeTable" id="timeTable" style="width:140px" onChange="getLabelClass(); return false;">
                                                    <option value="">Select</option> 
                                                    <?php 
                                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                        echo HtmlFunctions::getInstance()->getTimeTableLabelData(); 
                                                    ?>
                                                </select>
                                                </nobr>
                                            </td>
                                            <td class="contenttab_internal_rows" align="left"><nobr><strong>&nbsp;Class&nbsp;<?php echo REQUIRED_FIELD ?></nobr></td>
                                            <td class="contenttab_internal_rows" align="left"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>  
                                            <td class="contenttab_internal_rows" align="left" >
                                                <nobr>
                                                <select size="1" name="classId" id="classId" class="inputbox1" style="width:240px"> 
                                                    <option value="">Select</option> 
                                                </select>
                                                </nobr>
                                            </td>
                                            <td class="contenttab_internal_rows" align="left"><nobr><strong>&nbsp;Exam By</nobr></td>
                                            <td class="contenttab_internal_rows" align="left"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>  
                                            <td class="contenttab_internal_rows" align="left" >
                                                <nobr>
                                                <select size="1" name="examId" id="examId" class='inputbox1' style='width:100px' >
                                                    <option value="all">All</option>
                                                    <?php 
                                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                        echo HtmlFunctions::getInstance()->getEntranceExamData();
                                                    ?>
                                                </select>
                                             </nobr>
                                            </td>
                                            <td class="contenttab_internal_rows">
                                               <nobr> 
                                               <table align="left" cellpadding="0px" cellspacing="0px" border="0" >
                                                 <tr>
                                                    <td class="contenttab_internal_rows" align="left"><nobr><strong>&nbsp;<span id='examBy'>Rank</span></nobr></td>
                                                    <td class="contenttab_internal_rows" align="left"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>  
                                                    <td class="contenttab_internal_rows" align="left" >                                                     
                                                       <select class="inputbox" name="rank" id="rank" size="1" style="width:75px" onChange="checkStatus();">
                                                         <option value="">All</option>
                                                         <option value="1">Above</option>
                                                         <option value="2">Below</option>
                                                         <option value="3">Equal</option> 
                                                       </select>
                                                    </td>   
                                                 </tr>
                                               </table>
                                               </nobr>
                                            </td>
                                            <td class="contenttab_internal_rows"><nobr>                                                          
                                              <input class="inputbox" type="text" name="rankValue" id="rankValue" style="width:60px" value="" maxlength="15"/>
                                              </nobr>
                                            </td>
                                            <td class="contenttab_internal_rows" style="padding-left:10px"><nobr>
                                                <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form); return false;" />
                                            </td>
                                         <tr>
                                        </table>
                                </td>
                            </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr id='nameRow' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                        <tr>
                                            <td colspan="1" class="content_title">Student Exam Rankwise Details : </td>
                                            <td colspan="1" class="content_title" align="right">
                                               <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
                                               <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV()" />&nbsp;
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
                                               <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
                                               <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV()" />&nbsp;
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
</form>
<?php 
//$History: studentRankWiseContents.php $
//
//*****************  Version 6  *****************
//User: Parveen      Date: 4/08/10    Time: 3:04p
//Updated in $/LeapCC/Templates/StudentReports
//checkStatus function added
//
//*****************  Version 5  *****************
//User: Parveen      Date: 4/08/10    Time: 2:42p
//Updated in $/LeapCC/Templates/StudentReports
//time table label base report format updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 4/02/10    Time: 5:33p
//Updated in $/LeapCC/Templates/StudentReports
//table format updated
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/StudentReports
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/24/09   Time: 3:24p
//Updated in $/LeapCC/Templates/StudentReports
//look & feel updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/12/09    Time: 2:14p
//Created in $/LeapCC/Templates/StudentReports
//file added
//

?>
