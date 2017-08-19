<?php
//-------------------------------------------------------
// Purpose: to design the layout for SMS.
//
// Author : Parveen Sharma
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
                                    
                                       <table align="left" style="padding-left:10px" border="0" cellpadding="0" width="80%">
                                         <tr>
    <td class="contenttab_internal_rows" align="left"><nobr><b>Time Table </b></nobr></td>
    <td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
    <td class="contenttab_internal_rows" align="left" ><nobr>
    <select style="width:320px" size="1" name="timeTableLabelId" id="timeTableLabelId" class="selectfield" onChange="getSearchValue('E');">
         <option value="">Select</option>
         <?php
          require_once(BL_PATH.'/HtmlFunctions.inc.php');
          echo HtmlFunctions::getInstance()->getTimeTableLabelData();
         ?>
        </select></nobr>
    </td>
    <td class="contenttab_internal_rows" align="left" style="padding-left:10px"><nobr><b>Teacher</b></nobr></td>
    <td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
    <td class="contenttab_internal_rows" align="left"><nobr>
        <select size="1" style="width:358px" name="employeeId" id="employeeId" class="selectfield" onChange="getSearchValue('C');" >
           <option value="">Select</option>
        </select>
        </nobr>
    </td>
</tr>        
<tr>
<td class="contenttab_internal_rows" align="left"><nobr><b>Class</b></nobr></td>
<td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
<td class="contenttab_internal_rows" align="left"><nobr>
<select size="1" style="width:320px"  class="selectfield" name="classId" id="classId" onchange="getSearchValue('S');" >
<option value="">Select</option>
</select></nobr></td>
<td class="contenttab_internal_rows" style="padding-left:10px" align="left"><nobr><b>Subject</b></nobr></td>
<td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
<td class="contenttab_internal_rows" align="left"><nobr>
 <table width="10%" border="0" cellspacing="0" cellpadding="0" align="left">  
   <tr>
      <td class="contenttab_internal_rows" align="left"><nobr>
         <select style="width:175px" align="right" size="1" class="selectfield" name="subjectId" id="subjectId" onchange="getSearchValue('G');" >
        <option value="">Select Subject</option>
        </select></nobr>
      </td>
     <td class="contenttab_internal_rows" align="left" style="padding-left:10px"><nobr><b>Group</b></nobr></td>
     <td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
     <td class="contenttab_internal_rows" align="left"><nobr>
        <select style="width:132px" size="1" class="selectfield" name="groupId" id="groupId"  onchange="getSearchValue('P');">
        <option value="">Select</option>
        </select></nobr>
      </td>
    </tr>
  </table>
</td>       
</tr>
<tr>  
    <td class="contenttab_internal_rows" align="left" nowrap>
            <nobr><b>Date Check</b></nobr></td>
        <td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
        <td class="contenttab_internal_rows" align="left" colspan="10" > 
        <table width="10%" border="0" cellspacing="0" cellpadding="0" align="left">  
        <tr>
        <td class="contenttab_internal_rows" align="left"> 
            <select size="1" style="width:155px" class="inputbox1" onchange="getDateClear(this.value);" name="searchDateFilter" id="searchDateFilter">
            <option value="">Select</option>
            <option value="4">All</option> 
            <option value="3">Added Date</option>
            <option value="1">Assign Date</option>
            <option value="2">Due Date</option>
            </select>
        </td>
        <td class="contenttab_internal_rows" align="left"> 
        <table width="10%" border="0" cellspacing="0" cellpadding="0" align="left" id="lblDt" style="display:none">  
        <tr>
         <td class="contenttab_internal_rows" align="left"><nobr>&nbsp;&nbsp;From&nbsp;</nobr></td>
         <td class="contenttab_internal_rows" align="left" id='lblDate1'><nobr>
            <?php  
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->datePicker('searchFromDate');  
            ?>
           </nobr>
         </td>
         <td class="contenttab_internal_rows" align="left" style="padding-left:10px;"><nobr>To&nbsp;&nbsp;</nobr></td>
         <td class="contenttab_internal_rows" align="left" id='lblDate2'><nobr>
             <?php 
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                echo HtmlFunctions::getInstance()->datePicker('searchToDate');  
             ?>
          </nobr> 
         </td>
        </tr>
        </table> 
        </td>
   <td style="padding-left:20px">
      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateShowList();return false;" />
   </td>
  </tr>   
                                       
                                       </table>
                                    
                                </td>
                            </tr>
                        </table>  <tr>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr id='nameRow' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                        <tr>
                                            <td colspan="1" class="content_title">Teacher Assignment Detail :</td>
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
               </td>
                </tr>
        </table>
</form>

<!--Start Add/Edit Div-->
<?php floatingDiv_Start('StudentAssignmentActionDiv','Assignment Detail'); ?>
<form name="searchForm1" id="searchForm1" style="display:inline">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td valign="top">
        <fieldset class="fieldset">
        <legend>Assignment Detail</legend>
        <table cellspacing="0" cellpadding="0" border="0" width="100%">
         <tr>
             <td width="5%" class="contenttab_internal_rows" align="left"><nobr><b>Class</b></nobr></td>
             <td width="20%" class="contenttab_internal_rows" align="left"><nobr>&nbsp;<b>:</b></td>
             <td width="20%" class="contenttab_internal_rows" align="left"><nobr>
                <div style="display:inline" name="classId1" id="classId1"></div>
             	</nobr>
             </td>
             <td width="6%" class="contenttab_internal_rows" style="padding-left:5px;"><nobr><b>Subject</b></nobr></td>
             <td width="20%" class="contenttab_internal_rows" align="left"><nobr>&nbsp;<b>:</b>
                <div style="display:inline" name="subject1" id="subject1"></div>
            	</nobr>
            </td>
            <td width="6%" class="contenttab_internal_rows" style="padding-left:5px;"><nobr><b>Group</b></nobr></td>
            <td width="20%" class="contenttab_internal_rows" align="left"><nobr>&nbsp;<b>:</b>
               <div style="display:inline" name="group1" id="group1"></div>
               </nobr>
            </td>
         </tr>
         <tr>
            <td class="contenttab_internal_rows"><nobr><b>Assigned On</b></nobr></td>
            <td class="contenttab_internal_rows"><nobr>&nbsp;<b>:</b></td>
            <td width="20%" class="contenttab_internal_rows" align="left"><nobr>
            	<div id="DateAssignedOn" style="display:inline"></div></nobr>
            </td>
            <td class="contenttab_internal_rows" style="padding-left:5px;"><nobr><b>Due Date</b></nobr></td>
            <td class="contenttab_internal_rows"><nobr>&nbsp;<b>:</b>
            <div id="DateDueOn" style="display:inline"></div></nobr></td>
            <td class="contenttab_internal_rows" style="padding-left:5px;"><nobr><b>Added On </b></nobr></td>
            <td class="contenttab_internal_rows"><nobr>&nbsp;<b>:</b>
            <div id="DateAddedOn" style="display:inline"></div></nobr></td>
        </tr>
        <tr>
            <td class="contenttab_internal_rows"><nobr><b>Visble to Students</b></nobr></td>
            <td class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<b>:</b></td>
            <td class="contenttab_internal_rows" colspan="5"><nobr>
            	<div id="isVisibleDiv" style="display:inline"></div></nobr>
            </td>
        </tr>
        <tr>
            <td class="contenttab_internal_rows" valign="top"><nobr><b>Topic</b></nobr></td>
            <td class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<b>:</b></td>
            <td class="contenttab_internal_rows" colspan="5"><nobr>
            	<div id="AssignmentTopic" style="width:670px;overflow:auto;height:40px;display:inline"></div></nobr>
            </td>
        </tr>
        <tr>
            <td valign="top" height="5"></td>
        </tr>
        <tr>
            <td class="contenttab_internal_rows"><nobr><b>Attachment</b></nobr></td>
            <td class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<b>:</b></td>
            <td class="contenttab_internal_rows" colspan="5"><nobr>
            	<div id="editLogoPlaceDetail" style="display:inline"></div></nobr>
            </td>
        </tr>
        <tr>
            <td valign="top" height="5"></td>
        </tr>
        <tr>
            <td class="contenttab_internal_rows" valign="top"><nobr><b>Description</b></nobr></td>
            <td class="contenttab_internal_rows" valign="top"><nobr>&nbsp;<b>:</b></td>
            <td class="contenttab_internal_rows" valign="top" colspan="5"><nobr>
            	<div id="noticeSubject" style="overflow:auto; width:690px; height:70px;" >
            	<div id="AssignmentDescription" style="display:inline"></div></div>
            </td>
        </tr>
        <tr>
            <td valign="top" height="5"></td>
        </tr>
        </table>
        </fieldset>
        </td>
    </tr>

    <tr>
        <td valign="top">
        <fieldset class="fieldset">
        <legend>Student Status</legend>
        <table cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td height="5px" colspan="8"><div style="OVERFLOW: auto; HEIGHT:200px; width:800px;TEXT-ALIGN: justify;"><div id="results12"></div></div></td></tr>
        </tr>
        </table>
        </fieldset>
        </td>


    <tr>
</table>
</form>
<?php floatingDiv_End(); ?>

