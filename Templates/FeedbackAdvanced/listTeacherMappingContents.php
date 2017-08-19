<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR CITY LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
            <tr>
		<td valign="top" colspan="2">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr height="30">
								<td class="contenttab_border" height="20" style="border-right:0px;">
									<?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
								</td>
								<td width="14%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;">
                                   <!-- <img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddTeacher',315,250);blankValues();return false; " />&nbsp; -->
                                   <img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('AddMappingTeacher',315,250);blankValuesNew();getSurveyClass();return false; " />&nbsp;
                                </td>
                            </tr>
							<tr>
								<td class="contenttab_row" colspan="2" valign="top" ><div id="results"></div></td>
							</tr>
            <!-- <tr>
								<td align="right" colspan="2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
											<td class="content_title" valign="middle" align="right" width="20%">
												<input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
												<input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();" >
											</td>
                    </tr>
                    </table>
                </td>
             </tr>-->
          </table>
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>

<!--Start Add/Edit Div-->
<?php floatingDiv_Start('AddTeacher','',1); ?>
<form name="AddTeacher" action="" method="post" onsubmit="return false;">  
<input type="hidden" name="mappingId" id="mappingId" value="" />
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
   <tr>
    <td class="contenttab_internal_rows"><b>Time Table</b></td>
    <td class="padding">:</td>
    <td class="padding">
      <select style="width:220px;" name="timeTableLabelId" id="timeTableLabelId" class="inputbox" onchange="fetchClass(this.value);fetchMappedSurveyLabels(this.value);">
      <option value="">Select</option>
      <?php
        require_once(BL_PATH.'/HtmlFunctions.inc.php');
        echo HtmlFunctions::getInstance()->getTimeTableLabelData('-1');
      ?>
      </select>
    </td>
    <td rowspan="5" valign="top" class="contenttab_internal_rows" style="padding-top:5px;"><b>Teacher</b></td>
    <td rowspan="5" valign="top" class="padding">:</td>
    <td rowspan="5" valign="top" class="padding">
      <select name="employeeId" id="employeeId" class="selectfield" size="9" multiple="multiple">
       <?php
        require_once(BL_PATH.'/HtmlFunctions.inc.php');
        echo HtmlFunctions::getInstance()->getTeacher('-1');
      ?>
      </select>
      <!--<br/>Select &nbsp;<a class='allReportLink' href='javascript:makeSelection("employeeId","All","AddTeacher");'>All</a> / <a class='allReportLink' href='javascript:makeSelection("employeeId","None","AddTeacher");'>None</a>-->
    </td>
    </tr>
    <tr>
    <td class="contenttab_internal_rows"><b>Survey</b></td>
    <td class="padding">:</td>
    <td class="padding">
      <select style="width:220px;" name="surveyId" id="surveyId" class="inputbox" onchange="resetEmps();">
      <option value="">Select</option>
      </select>
    </td>
   </tr>
    <tr>
    <td class="contenttab_internal_rows"><b>Class</b></td>
    <td class="padding">:</td>
    <td class="padding">
      <select style="width:220px;" name="classId" id="classId" class="inputbox" onchange="fetchGroup(document.AddTeacher.timeTableLabelId.value,this.value);fetchSubject(document.AddTeacher.timeTableLabelId.value,this.value);">
      <option value="">Select</option>
      </select>
    </td>
   </tr>
    <tr>
     <td class="contenttab_internal_rows"><b>Group</b>
     <td class="padding">:</td>
     <td class="padding">
      <select style="width:220px;" name="groupId" id="groupId" class="inputbox" onchange="resetEmps();fetchTimeTableTeachers();">
       <option value="">Select</option>
      </select>
     </td>
    </tr>
    <tr>
     <td class="contenttab_internal_rows"><b>Subject</b>
     <td class="padding">:</td>
     <td class="padding">
      <select style="width:220px;" name="subjectId" id="subjectId" class="inputbox" onchange="resetEmps();fetchTimeTableTeachers();">
       <option value="">Select</option>
      </select>
     </td>
    </tr>
   <tr>
    <td align="center" style="padding-right:10px" colspan="6">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddTeacher');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
     </td>
  </tr> 
  </table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add/Edit Div-->


<!--Start Add/Edit Div-->
<?php floatingDiv_Start('AddMappingTeacher','Feedback Class Mapping'); ?>
<form name="AddMappingTeacher" action="" method="post" onsubmit="return false;">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
   <tr>
    <td class="contenttab_internal_rows"><b>Time Table</b></td>
    <td class="padding">:</td>
    <td class="padding">
      <select style="width:320px;" name="timeTableLabelId" id="timeTableLabelId" class="inputbox" onchange="getSurveyClass(); return false;">
          <option value="">Select</option>
          <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->getTimeTableLabelData();    
          ?>
      </select>
    </td>
    </tr>
    <tr>
    <td class="contenttab_internal_rows"><b>Survey</b></td>
    <td class="padding">:</td>
    <td class="padding">
      <select style="width:320px;" name="surveyId" id="surveyId" class="inputbox">
        <option value="">Select</option>
      </select>
    </td>
   </tr>
    <tr>
        <td class="contenttab_internal_rows" valign="top"><b>Class</b></td>
        <td class="padding" valign="top">:</td>
        <td class="padding" valign="top"><nobr>
          <select  multiple size="10" name='classId[]' id='classId' class="inputbox1" style="width:320px">
          </select><br>
            <div align="left">
            Select &nbsp;
            <a class="allReportLink" href="javascript:makeSelection('classId[]','All','AddMappingTeacher');">All</a> / 
            <a class="allReportLink" href="javascript:makeSelection('classId[]','None','AddMappingTeacher');">None</a>
            </div></nobr>
        </td>
   </tr>
   <tr><td>&nbsp;</td></tr> 
   <tr>
    <td align="center" style="padding-right:10px" colspan="6">
       <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return addTimeTableClasses(this.form);return false;" />&nbsp;
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddMappingTeacher');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
     </td>
  </tr> 
  </table>
</form>
<?php floatingDiv_End(); ?>
<!--End Add/Edit Div-->


<?php floatingDiv_Start('TeacherMappingDetail','Feedback Teacher Mapping Detail'); ?>
  <form name="teacherMappingDetail" id="teacherMappingDetail" method="post" onsubmit="return false;">  
  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
  <tr>
    <td class="contenttab_internal_rows" valign="top" >  
        <b><div id="divTeacherMappingDetailMsg"></div></b>
    </td>
  <tr>
  <tr><td height="5px"></td></tr>
  <tr>
    <td class="contenttab_internal_rows" valign="top" >  
       <div style="overflow:auto; width:700px;height:410px; vertical-align:top;">  
           <div id="divTeacherMappingDetail"></div>
        </div>
     </td>
  </tr>     
  </table>
</form>
<?php floatingDiv_End(); ?>