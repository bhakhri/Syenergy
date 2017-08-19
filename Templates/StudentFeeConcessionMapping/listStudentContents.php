<?php 
//-------------------------------------------------------
// Purpose: To design the Student Fee Concession Mapping    
//
// Author :Parveen Sharma
// Created on : 04-12-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<form name="listForm" id="listForm" action="" method="post" onSubmit="return false;">
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
                                <table width="100%" border="0" align="left" cellspacing="2px" cellpadding="0px" >
                                    <tr>    
                                       <td class="contenttab_internal_rows" style="padding-left:5px"><nobr><b>Fee Class</b>&nbsp;<?php echo REQUIRED_FIELD ?></nobr></td>
                                       <td class="contenttab_internal_rows" width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                       <td class="contenttab_internal_rows">
                                         <select size="1" name="feeClassId" id="feeClassId" style="width:320px" class="selectfield" onchange="hideResults(); return false;">
                                            <option value="">Select</option>
                                            <?php
                                               require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                               echo HtmlFunctions::getInstance()->getAllFeeClass();
                                            ?>
                                         </select>
                                       </td>  
                                       <td class="contenttab_internal_rows" style="padding-left:5px"><nobr><b>Reg. / Univ / Roll No.</b></nobr></td>
                                       <td class="contenttab_internal_rows" width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                       <td class="contenttab_internal_rows">
                                        <nobr><input type="text" id="rollNo" name="rollNo" class="inputbox" style="width:120px" maxlength="50"></nobr>
                                       </td>
                                       <td class="contenttab_internal_rows" style="padding-left:5px"><nobr><b>Student Name</b></nobr></td>
                                       <td class="contenttab_internal_rows" width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                       <td class="contenttab_internal_rows">
                                        <nobr><input type="text" id="studentName" name="studentName" style="width:150px"  class="inputbox" maxlength="50"></nobr>
                                       </td>
                                       <td  align="left" style="padding-left:15px;padding-right:10px"> <nobr> 
                                          <input type="image" name="imageField" id="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="showList(); return false;"/>
                                          </nobr>
                                       </td>
                                    </tr>
                                    <tr><td height="15px"></td></tr>
                                </table>
                                </td>
                            </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr id='nameRow' style='display:none;'>
                                <td class="" >
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border">
                                        <tr>
                                            <td colspan="1" nowrap width="30%" class="content_title">Student Details : </td>
                                            <td colspan="1" nowrap class="content_title" align="right"  width="25%" ><nobr>
                                               <input type="image" name="ss1" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="saveCategory();" />&nbsp;
                                               <input type="image" name="ss2" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport();" />&nbsp;
                                               <input type="image" name="ss3" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV();" />&nbsp;
                                               </nobr>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id='nameConcessionRow' style='display:none;'>
                               <td class="contenttab_row" colspan="1">
                                  <table width="20%" border="0" align="left" cellspacing="0px" cellpadding="0px" >
                                    <tr>   
                                       <td class="contenttab_internal_rows" valign="top" style="padding-left:5px"><nobr><b>Fee Concession Category</b></nobr></td>
                                       <td class="contenttab_internal_rows" valign="top"  width="2%"><nobr><b>&nbsp;:&nbsp;</b></nobr></td>
                                       <td class="contenttab_internal_rows" valign="top" ><nobr>
                                         <select multiple name='sConcessionId[]' id='sConcessionId' size='5' class='inputbox1' style='width:300px' > 
                                            <?php
                                               require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                               echo HtmlFunctions::getInstance()->getConcessionCategory();
                                            ?>
                                         </select><br>
                                        <div align="left">
                                        Select &nbsp;
                                        <a class="allReportLink" href="javascript:makeSelection('sConcessionId[]','All','listForm');">All</a> / 
                                        <a class="allReportLink" href="javascript:makeSelection('sConcessionId[]','None','listForm');">None</a>
                                        </div></nobr>
                                       </td>  
                                       <td  align="left"  valign="bottom"  style="padding-left:15px;padding-right:10px"> <nobr> 
                                          <input type="image" name="imageField" id="imageField" src="<?php echo IMG_HTTP_PATH;?>/apply_selection.gif" onClick="getSelectConcession(); return false;"/>
                                          </nobr>
                                       </td>
                                       <td class="contenttab_internal_rows" valign="middle" style="padding-left:50px" ><nobr>
                                        <b>Note:</b> <br>
                                       <span style="color:#FF0000; font-size:10px; font-weight:bold; font-family:Verdana">
                                        Select Concession Category to Apply on selected records<br>
                                        (Bulk Operation)</nobr>
                                       </span>
                                       </td>
                                    </tr>
                                  </table>
                               </td>      
                            </tr>
                            <tr id='resultRow' style='display:none;'>
                                <td colspan='1' class='contenttab_row'>
                                     <div id="scroll2" style="overflow:auto; width:1000px; height:510px; vertical-align:top;">
                                       <div id="resultsDiv" style="width:98%; vertical-align:top;"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr id='nameRow2' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                        <tr>
                                            <td colspan="2" class="content_title" align="right"><nobr>
                                               <input type="image" name="ss1" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="saveCategory();" />&nbsp;
                                               <input type="image" name="ss2" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport();" />&nbsp;
                                               <input type="image" name="ss3" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV();" />&nbsp;
                                               </nobr>
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