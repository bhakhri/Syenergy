<?php 
//-------------------------------------------------------
//  This File contains html form for all details report
//
//
// Author :Ajinder Singh
// Created on : 13-Sep-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
?>
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
                        <form action="" method="POST" name="allDetailsForm" id="allDetailsForm" onSubmit="return false;">  
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
                                 <tr>   
                                    <td valign="top" class="contenttab_row1" align="center">
                                       <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                                        <tr>
                                            <td valign="top"  align="center">
                                            <?php
                                            $htmlFunctions=HtmlFunctions::getInstance();
                                            ?>
                                            <table border='0' width='100%' cellspacing='0'>
                                            <?php echo $htmlFunctions->makeEmployeeDefaultSearch(); ?>
                                            <tr height='5'></tr>
                                            <?php echo $htmlFunctions->makeEmployeeAcademicSearch(false,'emp_','allDetailsForm'); ?>
                                            <tr height='5'></tr>
                                            <?php echo $htmlFunctions->makeEmployeeAddressSearch('emp_','allDetailsForm'); ?>
                                            <tr height='5'></tr>
                                            <?php echo $htmlFunctions->makeEmployeeMiscSearch('emp_'); ?>
                                            <tr>
                                            <td valign='top' colspan='8' class='' align='center'>
                                                <input type="image" name="employeeListSubmit" value="employeeListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm();" />
                                            </td>
                                        </tr>
                                    </table>
                                    </td>
                                </tr>
                            </table>
                            <input type="hidden" name="printEmployeeId" id="printEmployeeId" >
                        </form>                               
                        <form action="" method="post" name="resultForm" id="resultForm" onSubmit="return false;">  
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr id='nameRow' style='display:none;'>
                                <td class="" >
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border">
                                        <tr>
                                            <td colspan="1" class="content_title" align="left">All Details Report :</td>   
                                            <td colspan="1" nowrap class="content_title" align="right"  width="25%" >
                                               <!-- <input type="image" name="studentPrintSubmit1" value="studentPrintSubmit1" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
                                                    <input type="image" name="studentPrintSubmit2" value="studentPrintSubmit2" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV()" />&nbsp;
                                               -->     
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id='resultRow' style='display:none;'>
                                <td valign='top'  colspan='1' class='contenttab_row'>
                                    <div id="scroll2" style="overflow:auto; width:1050px; height:520px; vertical-align:top;">
                                       <div id="resultsDiv" style="width:98%; vertical-align:top;"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr id='pageRow' style='display:none;'>    
                                <td valign='top' colspan='1'  class=''>
                                  <table width="98%" valign='top' border="0" class='' cellspacing="0" cellpadding="0" >
                                   <tr>
                                     <td valign='top' colspan='1'  class='' align='left'>    
                                        <span id = 'pagingDiv1' class='contenttab_row1' align='left'></span>
                                     </td>
                                     <td valign='top' colspan='1'  class='' align='right'>   
                                        <span id = 'pagingDiv' align='right'></span> 
                                     </td>
                                   </tr>
                                  </table>      
                                </td>
                            </tr>
                            <tr id='nameRow2' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                        <tr>
                                            <td colspan="2" class="content_title" align="right">
                                               <input type="image" name="saveResult" value="save" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="addEmployeeCarryLeave(this)" />&nbsp;
                                                <input type="image" name="studentPrintSubmit1" value="studentPrintSubmit1" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
                                                <input type="image" name="studentPrintSubmit2" value="studentPrintSubmit2" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV()" />&nbsp;
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            </table>
                        </form>
                        <!-- form table ends -->
                    </td>
                </tr>
            </table>
        </table>
<?php 
// $History: listEmployeeIcardContents.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/Icard
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/15/09   Time: 1:20p
//Updated in $/LeapCC/Templates/Icard
//validation & look & feel update (Employee Filter)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/01/09   Time: 3:26p
//Updated in $/LeapCC/Templates/Icard
//icard title added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 9/10/09    Time: 2:09p
//Created in $/LeapCC/Templates/Icard
//initial checkin
//
?>
