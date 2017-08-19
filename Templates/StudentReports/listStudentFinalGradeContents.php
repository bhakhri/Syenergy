<?php 
//-------------------------------------------------------
//  This File outputs test time period Form
//
// Author :Parveen Sharma
// Created on : 04-12-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<form name="studentAttendanceForm" action="" method="post" onSubmit="return false;">

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
                                  <table width="65%" border="0" align="left" cellspacing="2px" cellpadding="2px" >
                                    <tr>
                                        <td class="contenttab_internal_rows"><nobr><strong>Time Table&nbsp;<?php echo REQUIRED_FIELD ?></strong></nobr></td>
                                        <td class="contenttab_internal_rows"><nobr><strong>:&nbsp;</strong></nobr></td>
                                        <td class="contenttab_internal_rows"><nobr><strong>
                                          <nobr><select size="1" class="inputbox1" name="timeTable" id="timeTable" style="width:170px" onChange="getLabelClass(); return false;">
                                                    <option value="">Select</option> 
                                                    <?php 
                                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                        echo HtmlFunctions::getInstance()->getTimeTableLabelDate('','');?>
                                          </select></nobr>
                                       </td>    
                                       <td class="contenttab_internal_rows"><nobr><strong>&nbsp;Class&nbsp;<?php echo REQUIRED_FIELD ?></strong></nobr></td>
                                        <td class="contenttab_internal_rows"><nobr><strong>:&nbsp;</strong></nobr></td>
                                        <td class="contenttab_internal_rows" colspan="2"><nobr><strong>
<!-- <select size="1" class="htmlElement" name="degreeId" id="degreeId" style="width:260px;" onchange="hideResults(); getSubjectClasses(); ">  -->
<select size="1" class="htmlElement" name="degreeId" id="degreeId" style="width:350px;" onchange="getSubjectClasses(); ">
                                       <option value="">Select</option>      
                                    </select></nobr>
                                       </td>
                                       <td class="contenttab_internal_rows"><nobr><strong>Subject&nbsp;<?php echo REQUIRED_FIELD ?></strong><nobr></td>
                                       <td class="contenttab_internal_rows"><nobr><strong>:&nbsp;</strong></nobr></td>
                                       <td class="contenttab_internal_rows"><nobr><strong>
                                        <select size="1" class="htmlElement" style="width:220px;" name="subjectId" id="subjectId" >
                                        <option value="">Select</option>      
                                        </select>
                                        </td>
                                     </tr>  
                                     <tr>                                            
                                        <td class="contenttab_internal_rows" id='sortingFormat1' align="right"><nobr><strong>&nbsp;Sort</strong></nobr></td>
                                        <td class="contenttab_internal_rows" id='sortingFormat2' align="left" ><nobr><b>&nbsp;:&nbsp;</nobr></b></td>  
                                        <td class="contenttab_internal_rows" id='sortingFormat3' align="left" colspan="5">
                                          <nobr>
                                            <table width="10%" cellspacing="0px" cellpadding="5px" border="0" align="left">      
                                             <tr>
                                                 <td class="contenttab_internal_rows" align="left"><nobr>
                                                    <select size="1" class="inputbox1" name="sortField1" id="sortField1" style="width:125px">
                                                        <option value="rollNo">Roll No.</option>
                                                        <option value='studentName'>Namewise</option> 
                                                    </select>
                                                    </nobr>
                                                  </td>  
                                                  <td class="contenttab_internal_rows" align="left" style="padding-left:10px"><nobr><strong>Order</strong></nobr></td>
                                                  <td class="contenttab_internal_rows"><nobr><strong>&nbsp;:&nbsp;</strong></nobr></td>
                                                  <td class="contenttab_internal_rows" align="left"><nobr>  
                                                    <input type="radio" name="sortOrderBy1" id="sortOrderBy1" value="ASC"  checked="checked" onclick="hideResults();" />Asc&nbsp;
                                                    <input type="radio" name="sortOrderBy1" id="sortOrderBy2" value="DESC" onclick="hideResults();" />Desc
                                                    </nobr>
                                                  </td>
                                                  <td class="contenttab_internal_rows" style="padding-left: 20px;"><nobr>
                                                     <input id="incMarksPer" name="incMarksPer" checked="checked" value="0" type="checkbox">
                                                     </nobr>
                                                  </td>
                                                  <td class="contenttab_internal_rows" align="left"><nobr>&nbsp;Include Marks and %age Details</nobr></td>
                                                  <td class=""  valign="bottom" style="padding-left:30px"><nobr><strong>
                                                    <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(this.form); return false;" />
                                                  </td>
                                               </tr> 
                                              </table>
                                           </nobr>
                                        </td> 
                                    </tr> 
                                    <tr id='showSubjectEmployeeList' style='display:none;'>  
                                          <td class="contenttab_internal_rows" align="left" colspan="20">
                                              <table width="100%">
                                                <tr>
                                                  <td class="contenttab_internal_rows" colspan="20" >
                                                    <b><a href="" class="link" onClick="getShowDetail(); return false;" >
                                                       <Label id='idSubjects'>Show Subject & Teacher Details</label></b></a>
                                                       <img id="showInfo" src="<?php echo IMG_HTTP_PATH;?>/arrow-down.gif" onClick="getShowDetail(); return false;" />
                                                  </td>
                                                 </tr> 
                                                 <tr>
                                                  <td class="contenttab_internal_rows" colspan="20" id='showSubjectEmployeeList11'>
                                                    <nobr><span id='subjectTeacherInfo'></span></nobr>
                                                  </td>
                                                 </tr> 
                                              </table>
                                          </td>
                                     </tr>
                                  </table> 
                              </td>
                           </tr>
                        </table>    
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr id='nameRow' style='display:none;'>
                                <td class="" >
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border">
                                        <tr>
                                            <td colspan="1" nowrap width="30%" class="content_title">Student Final Grade Details : </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id='resultRow' style='display:none;'>
                                <td valign='top'  colspan='1' class='contenttab_row' width="100%">
                                    <div id="scroll2" style="overflow:auto; width:99%; height:420px; vertical-align:top;">
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
