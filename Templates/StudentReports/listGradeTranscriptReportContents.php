<?php 
//-------------------------------------------------------
//  This File outputs test time period Form
//
// Author :Ipta Thakur
// Created on : 12-10-2011
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<form name="allDetailsForm" action="" method="post" onSubmit="return false;">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
             <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>   
         </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
                <tr>
                    <td valign="top" class="content" align="center">
                        <!-- form table starts -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2" >
                            <tr>
                                <td valign="top" class="contenttab_row1">
                                      <table align="center" border="0" width="40%" cellspacing="0px" cellpadding="0px"   >
                                            <tr>
                                                <td align="left" valign="top" >
                                                <table border="0" cellspacing="0px" cellpadding="5px"  >
                                                <tr>
                                                    <td align="left" class="contenttab_internal_rows" >    
                                                       <nobr><strong>Batch<?php echo REQUIRED_FIELD ?></strong></nobr>
                                                    </td>   
                                                    <td align="left" class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                                    <td align="left" class="contenttab_internal_rows"><nobr>    
                                                    <select size="1" class="inputbox" name="batchId" id="batchId" style="width:250px" onChange="getDegreeData(); hideDetails1(); return false;">
                                                        <option value="">Select</option>
                                                        <?php 
                                                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                            echo HtmlFunctions::getInstance()->getBatches();
                                                        ?>
                                                    </select></nobr>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td  align="left" class="contenttab_internal_rows" >    
                                                       <nobr><strong>Degree<?php echo REQUIRED_FIELD ?></strong></nobr>
                                                    </td>   
                                                    <td align="left" class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                                    <td align="left" class="contenttab_internal_rows"><nobr>    
                                                    <select size="1" class="inputbox" name="degreeId" id="degreeId" style="width:250px" onChange="getBranchData(); hideDetails();">
                                                        <option value="">Select</option>
                                                    </select>
                                                    </nobr>
                                                </tr>  
                                                 <tr>
                                                    <td  align="left" class="contenttab_internal_rows" >    
                                                       <nobr><strong>Branch<?php echo REQUIRED_FIELD ?></strong></nobr>
                                                    </td>   
                                                    <td align="left" class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                                    <td align="left" class="contenttab_internal_rows"><nobr>    
                                                    <select size="1" class="inputbox" name="branchId" id="branchId" style="width:250px" onChange="getTrimesterData(); hideDetails();">
                                                        <option value="">Select</option>
                                                    </select>
                                                    </nobr>
                                                </tr>  
                                                <tr>
                                                    <td align="left" class="contenttab_internal_rows" >    
                                                       <nobr><strong>Roll No.</strong></nobr>
                                                    </td>   
                                                    <td align="left" class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                                    <td align="left" class="contenttab_internal_rows"><nobr>    
                                                    <input type="text" name="rollno" id="rollno" class="inputbox" maxlength="50" style="width:245px" />
                                                    </nobr>
                                                    </td>
                                                 </tr>
                                                </table>  
                                             </td>
                                                <td align="left" class="contenttab_internal_rows" valign='top' style='padding-left:10px'> 
                                                <nobr>                                                         
                                                &nbsp;<strong>Semester<?php echo REQUIRED_FIELD ?> :<br>
                         &nbsp;<select multiple name='semesterId[]' id='semesterId' size='5' class="inputbox" style='width:150px'>
                                                    <?php 
                                                        //  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                         // echo HtmlFunctions::getInstance()->getStudyPeriodData();
                                                    ?>
                                                </select><br>
                                                <div style="text-align:left;">&nbsp;Select 
                                  <a class="allReportLink" href="javascript:makeSelection('semesterId[]','All','allDetailsForm');">All</a> / 
                                   <a class="allReportLink" href="javascript:makeSelection('semesterId[]','None','allDetailsForm');">None</a>
                                                </div>
                                                </nobr>
                                                </td>
											    <td align="center" valign="top" style="padding-left:20px">
                    
                                                <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form);return false;" />
                                                           </td>
                                                         
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr id='nameRow' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                        <tr>
                                            <td colspan="1" nowrap width="30%" class="content_title">Student Final Grade Details : </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id='resultRow' style='display:none;'>
                                <td valign='top'  colspan='1' class='contenttab_row'>
                                    <div id="scroll2" style="overflow:auto; width:950; height:420px; vertical-align:top;">
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
                                               <input type="image" name="studentPrintSubmit1" value="studentPrintSubmit1" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
                                               <input type="image" name="studentPrintSubmit2" value="studentPrintSubmit2" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" onClick="printReportCSV()" />&nbsp;
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
