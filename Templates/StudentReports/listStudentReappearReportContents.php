<?php 
//it contain the template to show details of internal student re-appear 
//
// Author :Parveen Sharma
// Created on : 19-01-2009
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<form action="" method="POST" name="allDetailsForm" id="allDetailsForm" onSubmit="return false;"> 
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
                                  <table align="left" border="0" cellpadding="0px" cellspacing="0px" width="100%">
                                    <tr>
                                        <td colspan="9" class="contenttab_internal_rows">
                                           <table align="left" border="0" cellpadding="0px" cellspacing="0px" width="100%">
                                              <tr>
                                                  <td valign="top" class="contenttab_internal_rows" width="10%"><b>
                                                    <nobr>Re-appear Application Form Submitted Date From<?php echo REQUIRED_FIELD ?></b></nobr>
                                                  </td>
                                                  <td class="contenttab_internal_rows" width="1%" valign="top"><b><nobr>&nbsp;:</b></nobr></td>
                                                  <td class="contenttab_internal_rows"valign="top" width="5%"><nobr>
                                                      <?php
                                                        require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                        echo HtmlFunctions::getInstance()->datePicker('startDate',date('Y-m-d'));
                                                      ?></nobr>
                                                  </td>
                                                  <td valign="top" class="contenttab_internal_rows" width="2%">
                                                    <nobr><b>&nbsp;&nbsp;To<?php echo REQUIRED_FIELD ?></b></nobr>
                                                  </td>
                                                  <td class="contenttab_internal_rows" width="1%" valign="top"><b><nobr>&nbsp;:</b></nobr></td>
                                                  <td valign="top" class="contenttab_internal_rows" width="4%"> 
                                                    <nobr><?php
                                                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                           echo HtmlFunctions::getInstance()->datePicker('endDate',date('Y-m-d'));
                                                        ?>
                                                    </nobr>
                                                  </td>
                                                   <td valign="top" width="2%" class="contenttab_internal_rows" style="padding-left:10px;"><nobr><b>&nbsp;&nbsp;Roll No.</b></nobr></td>
                                                   <td class="contenttab_internal_rows" width="1%" valign="top"><b><nobr>&nbsp;:</b></nobr></td> 
                                                   <td valign="top" class="contenttab_internal_rows" >
                                                   <nobr>&nbsp;<input type="text" name="rollNo" id="rollNo" class="inputbox" maxlength="50" style="width:250px" ></nobr>
                                                   </td> 
                                              </tr>  
                                           </table>
                                       </td>       
                                    </tr>
                                    <tr>
    <td height="5px"></td>
</tr>
<tr>
<td class="contenttab_internal_rows" valign="top" width="5%" >
   <nobr><b>Re-appear Class<?php echo REQUIRED_FIELD ?></b></nobr>
  </td>
  <td class="contenttab_internal_rows" width="1%" valign="top"><b><nobr>&nbsp;:</b></nobr></td>
  <td class="contenttab_internal_rows" valign="top">
  <nobr>
  <div id="containerDiv">
    <select multiple class="inputbox" name="reappearClassId[]" id="reappearClassId" style="width:280px">
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->getReappearClassData();
        ?>
    </select>
    <?php
          $isIE6=HtmlFunctions::getInstance()->isIE6Browser();
          if($isIE6==1){
          ?>    
              <br>Select &nbsp;<a class="allReportLink" href='javascript:makeSelection("reappearClassId","All","allDetailsForm");'>All</a> / <a class="allReportLink" href='javascript:makeSelection("reappearClassId","None","allDetailsForm");'>None</a>
          <?php    
          }
    ?>
  </div>
  <div style="display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF" id="d1"></div>
  <div style="display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF" class="inputbox" id="d2" >
  <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" >
    <tr>
       <td id="d3" width="95%" valign="middle" style="padding-left:3px;" class="contenttab_internal_rows"></td>
       <td width="5%">
         <img id="downArrawId" src="<?php echo IMG_HTTP_PATH;?>/down_arrow.gif" style="margin-bottom:0px;" onClick="popupMultiSelectDiv('reappearClassId','d1','containerDiv','d3'); return false;" />
       </td>
    </tr>
   </table>
  </div> 
  </nobr>
  </td>
    <td valign="top" width="2%" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;Subject</b></nobr></td>
    <td class="contenttab_internal_rows" width="1%" valign="top"><b><nobr>&nbsp;:</b></nobr></td>
    <td valign="top" class="contenttab_internal_rows" width="12%" ><nobr>
  <div id="containerDiv">
        <select multiple class="inputbox" name="subjectId[]" id="subjectId" onChange="hideResults(); return false;" style="width:220px">
          <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->getReappearSubjectData();
          ?>
        </select>
     <?php
          $isIE6=HtmlFunctions::getInstance()->isIE6Browser();
          if($isIE6==1){
          ?>    
              <br>Select &nbsp;<a class="allReportLink" href='javascript:makeSelection("subjectId","All","allDetailsForm");'>All</a> / <a class="allReportLink" href='javascript:makeSelection("subjectId","None","allDetailsForm");'>None</a>
          <?php    
          }
     ?>        
  </div>
  <div style="display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF" id="d11"></div>
  <div style="display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF" class="inputbox" id="d22" >
      <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" >
           <tr>
              <td id="d33" width="95%" valign="middle" style="padding-left:3px;" class="contenttab_internal_rows"></td>
              <td width="5%">
              <img id="downArrawId" src="<?php echo IMG_HTTP_PATH;?>/down_arrow.gif" style="margin-bottom:0px;" onClick="popupMultiSelectDiv('subjectId','d11','containerDiv','d33');" />
              </td>
            </tr>
      </table>
  </div> 
</nobr>
</td>
<td valign="top" width="2%" class="contenttab_internal_rows" align="right"><b><nobr>&nbsp;&nbsp;Re-appear Status</nobr></b></td>
<td class="contenttab_internal_rows" width="1%" valign="top"><b><nobr>&nbsp;:</b></nobr></td>
<td valign="top" class="contenttab_internal_rows" width="12%" > 
<nobr>
<div id="containerDiv">
<select multiple class="selected" name="reappearStatusId[]" id="reappearStatusId" style="width:180px">
<?php
require_once(BL_PATH.'/HtmlFunctions.inc.php');
echo HtmlFunctions::getInstance()->getReappearStatusData();
?>
</select>
<?php
          $isIE6=HtmlFunctions::getInstance()->isIE6Browser();
          if($isIE6==1){
          ?>    
              <br>Select &nbsp;<a class="allReportLink" href='javascript:makeSelection("reappearStatusId","All","allDetailsForm");'>All</a> / <a class="allReportLink" href='javascript:makeSelection("reappearStatusId","None","allDetailsForm");'>None</a>
          <?php    
          }
?>
</div>
<div style="display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF" id="d111"></div>
<div style="display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF" class="inputbox" id="d222" >
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" >
<tr>
<td id="d333" width="95%" valign="middle" style="padding-left:3px;" class="contenttab_internal_rows"></td>
<td width="5%">
<img id="downArrawId" src="<?php echo IMG_HTTP_PATH;?>/down_arrow.gif" style="margin-bottom:0px;" onClick="popupMultiSelectDiv('reappearStatusId','d111','containerDiv','d333');" />
</td>
</tr>
</table>
</div> 
</nobr>
</td>
</tr>  
<tr>
    <td height="5px"></td>
</tr>
<tr>
<td class="contenttab_internal_rows" align="left" colspan="8" valign="top">
        <table border="0" cellpadding="0px" cellspacing="0px" valign="top" >
        <tr>
        <td class="contenttab_internal_rows" align="left">
        <nobr><b>Cause of Detention / Re-appear</b></nobr>
        </td>
        <td class="contenttab_internal_rows" align="left"><nobr><b>:</b></nobr></td>
        <td class="contenttab_internal_rows" align="left">
            <nobr><input class="inputbox1" type="radio" id="assignmentChk" name="value1" value="1"></nobr>
        </td>    
        <td class="contenttab_internal_rows" align="left">    
            <nobr>Assignment Work</nobr> 
        </td>    
        <td class="contenttab_internal_rows" align="left">                
            <nobr><input class="inputbox1" type="radio" id="midSemesterChk" name="value1" value="1"></nobr>
        </td>    
        <td class="contenttab_internal_rows" align="left">                
            <nobr>Mid Semester Tests</nobr>
        </td>    
        <td class="contenttab_internal_rows" align="left">                
            <nobr><input class="inputbox1" type="radio" id="attendanceChk" name="value1" value="1"></nobr>
        </td>    
        <td class="contenttab_internal_rows" align="left">                
            <nobr>Attendance</nobr>  
        </td> 
        <td class="contenttab_internal_rows" align="left" style="padding-left:8px">
        <nobr><b>Detained Student</b></notr>
        </td>
        <td class="padding" align="left"><nobr><b>:</b></nobr></td>
        <td class="contenttab_internal_rows" align="left" ><nobr>
            <nobr><input type="checkbox" id="studentDetained" name="studentDetained" value="Y"></nobr>
        </td>
        <td class="padding" align="left" style="padding-left:10px">
            <nobr>
                <input type="image" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm(); return false;" />
            </nobr>
        </td> 
       </tr> 
     </table>
    </td>   
</tr>                                              
                                        </table>
                                </td>
                            </tr>
                          
                            <tr>
                                <td height="5px"></td>
                            </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr id='nameRow' style='display:none;'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                        <tr>
                                            <td class="content_title">Display Student Internal Re-appear Details :</td>
                                            <td class="content_title" align="right">
                                                <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
                                                <input type="image"  name="printGroupSubmit" id='generateCSV' onClick="printCSV();return false;" value="printGroupSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" />
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
                                              <nobr>
                                                <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="printReport()" />&nbsp;
                                                <input type="image"  name="printGroupSubmit" id='generateCSV' onClick="printCSV();return false;" value="printGroupSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" />
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
        </table>
</form>        


                           
<?php
//$History: listStudentReappearReportContents.php $
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 16/02/10   Time: 16:49
//Updated in $/LeapCC/Templates/StudentReports
//Modified code for IE6 issues related to "New Multiple Dropdowns"
//
//*****************  Version 5  *****************
//User: Parveen      Date: 2/04/10    Time: 11:00a
//Updated in $/LeapCC/Templates/StudentReports
//look & feel updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 2/01/10    Time: 4:34p
//Updated in $/LeapCC/Templates/StudentReports
//tag name updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/01/10    Time: 4:08p
//Updated in $/LeapCC/Templates/StudentReports
//validation & format updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/19/10    Time: 12:08p
//Updated in $/LeapCC/Templates/StudentReports
//format & validation updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/18/10    Time: 4:12p
//Created in $/LeapCC/Templates/StudentReports
//initial checkin
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/15/10    Time: 2:11p
//Updated in $/LeapCC/Templates/AdminTasks
//format update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/14/10    Time: 5:16p
//Created in $/LeapCC/Templates/AdminTasks
//initial checkin

?>