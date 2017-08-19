<?php
//----------------------------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR class wise grade template
//
//
// Author :Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <?php
				if ($sessionHandler->getSessionVariable('RoleId') == 1) {
					require_once(TEMPLATES_PATH . "/breadCrumb.php");
				}
				else {
				?>
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
           
            <tr>
			<?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?>
	<tr>
		<td valign="top" colspan="2">
			
				<?php
				}

				?>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
            
             <tr>
             <td class="contenttab_border1" align="center" >
             <form action="" method="" name="searchForm">
                   <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                    <tr>
                      <td width="3%" class="contenttab_internal_rows" align="left" width="5%"><nobr><b>Time Table</b></nobr></td>
                        <td class="padding" width="1%">:</td>
                        <td width="10%" class="padding" align="left" width="5%"><nobr>
                        <select style="width:190px;" size="1" class="selectfield" name="timeTableId" id="timeTableId" onchange="populateClasses(this.value);" >
                        <option value="">Select</option>
                        <?php
                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          echo HtmlFunctions::getInstance()->getTimeTableLabelData('0');
                        ?>
                      </select></nobr></td>
                      <td class="contenttab_internal_rows" width="5%"><nobr><b>Class</b></nobr></td>
                      <td class="padding" width="1%">:</td>
                      <td class="padding" align="left" width="10%"><nobr>
                        <select size="1" style="width:240px;" class="selectfield" name="classId" id="classId"  onchange="populateSubjectTypes(document.searchForm.timeTableId.value,this.value);populateGroups(document.searchForm.timeTableId.value,this.value);" />
                        <option value="">Select</option>
                        </select></nobr>
                      </td>
                      <td class="contenttab_internal_rows" width="5%"><nobr><b>Group</b></nobr></td>
                      <td class="padding" width="1%">:</td>
                      <td class="padding" align="left" width="10%"><nobr>
                        <select size="1" style="width:190px;" class="selectfield" name="groupId" id="groupId" onchange="vanishData();" />
                        <option value="">Select</option>
                        </select></nobr>
                      </td>
                     </tr>
                     <tr> 
                      <td class="contenttab_internal_rows"><nobr><b>Subject Type</b></nobr></td>
                      <td class="padding" width="1%">:</td>
                      <td class="padding" align="left"><nobr>
                        <select size="1" style="width:190px;" class="selectfield" name="subjectType" id="subjectType" onchange="populateSubject(document.searchForm.timeTableId.value,this.value,document.searchForm.classId.value);" />
                        <option value="">Select</option>
                        </select></nobr>
                      </td>
                      <td width="4%" class="contenttab_internal_rows" style="padding-top:8px" valign="top"><nobr><b>Subject</b></nobr></td>
                        <td class="padding" width="1%" valign="top">:</td>
                        <td width="5%" class="contenttab_internal_rows" style="padding-top:5px;padding-left:7px;" align="left"><nobr>
                        <div id="containerDiv">
                        <select class="selectfield" style="width:236px;" name="subjectId" id="subjectId" size="5" multiple="multiple" onchange="vanishData();" >
                        </select>
                        <?php
                            $isIE6=HtmlFunctions::getInstance()->isIE6Browser();
                            if($isIE6==1){
                        ?>
                        <br>Select &nbsp;<a class="allReportLink" href='javascript:makeSelection("subjectId","All","searchForm");'>All</a> / <a class="allReportLink" href='javascript:makeSelection("subjectId","None","searchForm");'>None</a>
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
                                  <img id="downArrawId" src="<?php echo IMG_HTTP_PATH;?>/down_arrow.gif" style="margin-bottom:0px;" onClick="popupMultiSelectDiv('subjectId','d1','containerDiv','d3');" />
                                  </td>
                                </tr>
                             </table>
                          </div>
                         </nobr>
                      </td>
                      <td class="contenttab_internal_rows" valign="top" style="padding-top:5px;"><nobr><b>Show Grace Marks</b></nobr></td>
                       <td class="padding" width="1%" valign="top">:</td>
                       <td class="contenttab_internal_rows" valign="top" colspan="1" style="padding-top:3px">
                        <input type="radio" name="showGraceMarks" value="1" onclick="graceMarksToggle(this.value);"><b>Yes</b>&nbsp;
                        <input type="radio" name="showGraceMarks" value="0" onclick="graceMarksToggle(this.value);" checked="checked"><b>No</b>
                       </td>
                      </tr>
                      <tr> 
                      <td class="contenttab_internal_rows" valign="top" style="padding-top:5px;"><b>Range</b></td>
                      <td class="padding" width="1%" valign="top">:</td>
                      <td class="padding" colspan="1" align="left" valign="top">
                         <select size="1" style="width:190px;" class="selectfield" name="rangeType" id="rangeType" disabled="disabled" onchange="changeCriteriaString(document.searchForm.subjectType.value,this.value);">
                          <option value="1">Percentage</option>
                          <option value="2">Absolute</option>
                         </select>
                      </td>
                      
                        <td class="contenttab_internal_rows" valign="top" style="padding-top:4px"><nobr><b>Define Range &nbsp;<div id="rtDiv" style="display:inline">in&nbsp;&nbsp;%</div></b><br/>(1-9,10-19...)</nobr></td>
                        <td class="padding" width="1%" valign="top">:</td>
                        <td class="padding" align="left" valign="top" colspan="4"><nobr>
                         <input type="text" id="testMarksRange" name="testMarksRange" class="inputbox" style="width:558px" onchange ="vanishData();return false;" />
                         </nobr>
                        </td>
                      </tr>
                      <tr>  
                        <td class="contenttab_internal_rows" valign="top" style="padding-top:4px"><b>Graph Type</b></td>
                        <td class="padding" width="1%" valign="top">:</td>
                        <td class="padding" valign="top" align="left">
                          <select name="chartTypeId" id="chartTypeId" style="width:190px;" class="selectfield" onchange="cleanUpData();">
                           <option value="1">Column Chart</option>
                           <!-- <option value="2">3D Stacked Column Chart</option> -->
                           <option value="3">3D Stacked Row Chart</option>
                          </select>
                        </td>
                        <td class="contenttab_internal_rows"><b>Exam Type</b></td>
                       <td class="padding" width="1%" valign="top">:</td>
                       <td class="contenttab_internal_rows" colspan="4">
                        <table border="0" cellpadding="0" cellspacing="0">
                         <tr>
                          <td>
                           <input type="radio" name="examType" value="1" checked="checked" onclick="vanishData();">
                          </td>
                          <td class="contenttab_internal_rows" style="padding-top:5px;">
                           <b>All</b>
                          </td>
                          <td>
                           <input type="radio" name="examType" value="2" onclick="vanishData();">
                          </td>
                          <td class="contenttab_internal_rows" style="padding-top:5px;">
                           <b>Internal</b>
                          </td>
                          <td>
                           <input type="radio" name="examType" value="3" onclick="vanishData();">
                          </td>
                          <td class="contenttab_internal_rows" style="padding-top:5px;">
                           <b>External</b>
                          </td>
                          <td align="left" style="padding-left:60px">
                            <input type="image" name="imageField" onClick="getGraphData();return false" src="<?php echo IMG_HTTP_PATH;?>/show_graph.gif" />
                          </td>
                         </tr>
                         </table>
                       </td>
                      </tr>
                    </table>
                    </form>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >&nbsp;
                 <div id="resultsDiv1">
                </div>
             </td>
          </tr>
          <tr><td height="5px"></td></tr>
          <tr>
           <td align="center" id="saveDiv" style="display:none">
            <input type="image" name="saveGraphSubmit" value="saveGraphSubmit" src="<?php echo IMG_HTTP_PATH;?>/save_graph.gif" onClick="exportImage();return false;" />&nbsp;
            <input type="image" name="imageField" onClick="printReport();return false" src="<?php echo IMG_HTTP_PATH;?>/print.gif" />&nbsp;
            <input type="image" name="imageField" onClick="printCSV();return false" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" />
           </td>
         </tr>
          </table>
     <fieldset>
<font size='1.5px'><u>Note</u> :</font>
	<font color="red" size='1.5px'>This Subject Wise Performance Report (Post Transfer) is ONLY applicable after <u>Transfer</u> is done</font><br>
   </fieldset>
        </td>
    </tr>

    </table>
    </td>
    </tr>
    </table>
<?php
// $History: listSubjectWisePerformanceContents.php $
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 15/02/10   Time: 17:43
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Modified javascript and html coding for "New Multiple Selected
//Dropdowns" as these are not working in IE6.
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 4/01/10    Time: 13:17
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Made UI Changes
//
//*****************  Version 7  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 2/12/09    Time: 11:08
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Modified "Subject Wise Performance Graph"---Added the option of
//include/exclude grace marks in this report
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 1/12/09    Time: 13:20
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Done bug fixing.
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 26/11/09   Time: 17:37
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Done enhancements in "Subject Wise Performance" report
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 24/11/09   Time: 18:45
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Made some litte UI changes---graph legends and field names
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 24/11/09   Time: 17:43
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Modified "Image" name
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 24/11/09   Time: 17:15
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//created "Subject Wise Performance" report
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 28/10/09   Time: 10:51
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Corrected breadcrumb text
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 27/10/09   Time: 16:50
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Corrected javascript code
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 24/08/09   Time: 11:54
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Corrected look and feel of teacher module logins
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 29/07/09   Time: 17:23
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Done the enhancement: subjects are populated corresponding to the class
//selected
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 29/06/09   Time: 11:32
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//Added name & roll no wise search in display attendance and marks
//display in teacher login
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/18/09    Time: 10:37a
//Updated in $/LeapCC/Templates/Teacher/TeacherActivity
//modified to show group by selecting subject
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher/TeacherActivity
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/29/08    Time: 12:22p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/18/08    Time: 7:09p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/07/08    Time: 4:52p
//Created in $/Leap/Source/Templates/Teacher/TeacherActivity
?>
