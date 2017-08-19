<?php 
//--------------------------------------------
//  This File outputs SMS Delivery Report Form
//
// Author :Kavish Manjkhola
// Created on : 24-Mar-2011
// Copyright 2010-2011: Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------
?>
<form name="allDetailForm" id="allDetailForm" method="post" onSubmit="return false;">  
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
                                    <table width="100%" align="left" border="0" cellpadding="0px" cellspacing="0" >
                                        <tr>
                                            <td class="contenttab_internal_rows" valign="top"><nobr><b>Fee Class</b>&nbsp;<?php echo REQUIRED_FIELD ?></nobr></td>
                                            <td width="2%" valign="top"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                            <td class="contenttab_internal_rows"  valign="top"><nobr>
                                                <select name='feeClassId' id='feeClassId' class='inputbox1' style='width:340px' > 
                                                    <?php
                                                       require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                       echo HtmlFunctions::getInstance()->getAllFeeClass();
                                                    ?>
                                                 </select><br>
                                                <!--<div align="left">
                                                Select &nbsp;
                                                <a class="allReportLink" href="javascript:makeSelection('feeClassId[]','All','allDetailForm');">All</a> / 
                                                <a class="allReportLink" href="javascript:makeSelection('feeClassId[]','None','allDetailForm');">None</a>
                                                </div>--></nobr>
                                            </td>                             
                                            <td class="contenttab_internal_rows" style="padding-left:2px"><nobr><b>Reg. / Univ / Roll No.</b></nobr></td>
                                            <td class="contenttab_internal_rows" width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                            <td class="contenttab_internal_rows">
                                                <nobr><input type="text" id="rollNo" name="rollNo" class="inputbox" style="width:150px" maxlength="50"></nobr>
                                            </td>
                                            
                                        </tr>
                                        <tr>
                                        	<td class="contenttab_internal_rows" style="padding-left:2px"><nobr><b>Student Name</b></nobr></td>
                                            <td class="contenttab_internal_rows" width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                            <td class="contenttab_internal_rows">
                                                <nobr><input type="text" id="studentName" name="studentName" style="width:335px"  class="inputbox" maxlength="50"></nobr>
                                            </td>
											<td class="contenttab_internal_rows" nowrap="nowrap">  
												<nobr><b>Include Left Out Student<?php echo REQUIRED_FIELD;?></b></nobr> 
											</td>  
											<td class="contenttab_internal_rows" width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>  
											<td nowrap>
												<input name="leftOutStudent" id="leftOutStudent" value="0" onclick="" checked="checked" type="radio">No&nbsp;&nbsp;&nbsp;
												<input name="leftOutStudent" id="leftOutStudent" value="1" onclick="" type="radio">Yes
											</td>
											<a id="lk1"  class="set_default_values">Set Default Values for Report Parameters</a>
                                            <td class="contenttab_internal_rows" valign="bottom" style="padding-left:20px">
                                            	<input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm();return false;" />
                                            </td> 
	                                            <tr style='display:none'>
	                                               <td class="contenttab_internal_rows" width="2%" align="left"><nobr><br>
	                                               <input id="consolidatedId" name="consolidatedId" value="1" type="checkbox">&nbsp;Consolidated</nobr> 
	                                               </td>
	                                    		</tr>
                                        </tr>
                                     </table>
                                </td>
                            </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr id='nameRow' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                        <tr>
                                            <td colspan="1" class="content_title">Student Pending Dues Details :</td>
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
                                  <div id="scroll2" style="overflow:auto; height:510px; vertical-align:top;">
                                     <div id="resultsDiv" style="width:98%; vertical-align:top;"></div>
                                  </div>
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
            </td>
            </tr>        
        </table>
</form> 