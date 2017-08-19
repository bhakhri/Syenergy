<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR student and message LISTING
//
//
// Author :Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
         <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
             <tr>
                <td class="contenttab_border" height="20">
                
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Please use the following screen to choose a particular survey and assign it to students/teachers : </td>
                        <td class="content_title"></td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
              <td class="contenttab_row" valign="top" width="100%" >
               <table border="0" cellpadding="0" cellspacing="0" width="100%" >
               <!--header part-->
                <tr>
                <td class="contenttab_internal_rows" width="3%"><nobr><b>Time Table  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp</b></nobr></td>
                 <td class="padding" width="1%">:&nbsp; </td>
                 <td class="contenttab_internal_rows" width="3%" colspan="7" style="padding:3px 5px 3px 5px;">
                  <select style="width:155px;" name="timeTableLabelId" id="timeTableLabelId" class="inputbox" onchange="fetchMappedSurveyLabels(this.value);" >
                     <option value="">Select</option>
                     <?php
                       require_once(BL_PATH.'/HtmlFunctions.inc.php');
                       echo HtmlFunctions::getInstance()->getTimeTableLabelData(-1);
                     ?>
                   </select>
                   (for which the survey is to be conducted)
                 </td>
                </tr>
                <tr> 
                 <td class="contenttab_internal_rows" width="3%"><nobr><b>Survey Name</b></nobr></td>
                 <td class="padding" width="1%">:</td>
                 <td class="padding" width="3%"><nobr>
                  <select style="width:155px;" name="labelId" id="labelId" class="inputbox" onchange="fetchMappedCategories(document.getElementById('timeTableLabelId').value,this.value);">
                     <option value="">Select</option>
                   </select>
                   &nbsp;Feedback by&nbsp;:&nbsp;<div id="applicableToTDId" style="display:inline"><?php echo $blank_string; ?></div>
                   </nobr>
                 </td>
                 <td class="contenttab_internal_rows" width="3%" style="padding-left:5px;"><b>Category</b></td>
                 <td class="padding" width="1%">:</td>
                 <td class="padding" width="5%"><nobr>
                  <select style="width:160px;" name="catId" id="catId" class="inputbox" onchange="getCategoryRelation(this.value);fetchMappedQuestionSet(document.getElementById('timeTableLabelId').value,document.getElementById('labelId').value,this.value);">
                     <option value="">Select</option>
                   </select>
                  &nbsp;Question Category :&nbsp;<div id="relationshipToTDId" style="display:inline"><?php echo $blank_string; ?></div> 
                  </nobr>
                 </td>
                 <td class="contenttab_internal_rows" width="3%" style="padding-left:5px;"><nobr><b>Question Set</b></nobr></td>
                 <td class="padding" width="1%">:</td>
                 <td class="padding" width="5%">
                  <select style="width:155px;" name="questionSetId" id="questionSetId" class="inputbox" onchange="vanishData();getFilters(this.value);">
                     <option value="">Select</option>
                   </select>
                 </td>
               </tr>
              <!-- 
               <tr>
                <td class="padding" colspan="15" align="center">
                  <input type="image" name="imageField1" src="<?php echo IMG_HTTP_PATH;?>/show_list.gif"  onClick="return getAssigningFilters();return false;" />
                </td>
               </tr>
              --> 
               </table>
             </td>
          </tr>
          <!--header part-->
          
          <!--employee part-->
          <tr id="employeeSearchFilterRowId" style="display:none;">
           <td colspan="1" class="contenttab_row" style="border-top:none;">
           <form name="employeeDetailsForm" action="" method="post" onSubmit="return false;">
               <table border='0' width='100%' cellspacing='0'>
                   <tr height='10'></tr> 
                   <?php echo $htmlFunctions->makeEmployeeDefaultSearch(); ?>
                   <tr height='5'></tr>
                   <?php echo $htmlFunctions->makeEmployeeAcademicSearch_feedback(false,'emp_','employeeDetailsForm'); ?>
                   <tr height='5'></tr>
                   <?php echo $htmlFunctions->makeEmployeeAddressSearch_feedback('emp_','employeeDetailsForm'); ?>
                   <tr height='5'></tr>
                   <?php echo $htmlFunctions->makeEmployeeMiscSearch_feedback('emp_'); ?>
                   <tr>
                    <td valign='top' colspan='10' class='' align='center'>
                     <input type="image" name="employeeListSubmit" value="employeeListSubmit" src="<?php echo IMG_HTTP_PATH;?>/show_employees.gif" onClick="return validateEmployeeList();return false;" />
                    </td>
                   </tr>
              </table>
              <input type="hidden" name="selectedEmp" id="selectedEmp" value="" />
             </form>
            </td>
           </tr>
           <tr id="employeeDisplayRowId" style="display:none;">
            <td colspan="1" class="contenttab_row" style="border-top:none;">   
             <div id="empShowList" style="display:none">
               <table cellpadding="0" cellspacing="0" border="0" width="99%">
                  <tr>
                   <td><b>Total Records :</b>
                    <label id="totalEmployeesRecordsId"></label>
                    &nbsp;&nbsp;
                   <input type="checkbox" id="sendToAllEmployeeChk" value="sendToAllChk" onclick="sdaEmployee(this.checked);" />
                    <label for="sendToAllEmployeeChk" id="employeeLabel">
                     <b>Assign  to All Employees</b>
                    <label>
                    &nbsp;&nbsp;<div id="employeeSummeryDiv"  style="display:inline"></div>
                   </td>
                  </tr>
                  <tr>
                   <td>
                    <form name="empListFrm" id="empListFrm">
                     <div id="employeeResultsDiv"></div>
                    </form>
                   </td>
                  </tr>
                  <tr>
                   <td align="center">
                     <div id="empDivButton" style="display:none">
                        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateEmployeeForm();return false;" />
                     </div>
                    </td>
                    </tr>
                    </tr>
                   </table>
                </div>    
           </td>
          </tr>
          <!--employee part-->
          
          
          <!--student part(Common part)-->
          <tr id="studentSearchFilterRowId" style="display:none;">
           <td colspan="1" class="contenttab_row" style="border-top:none;">
           <form name="allDetailsForm" action="" method="post" onSubmit="return false;">
               <table border='0' width='100%' cellspacing='0'>
                   <tr height='10'></tr> 
                   <?php echo $htmlFunctions->makeStudentDefaultSearch(); ?>
                   <tr height='5'></tr>
                   <?php echo $htmlFunctions->makeStudentAcademicSearch(false); ?>
                   <tr height='5'></tr>
                   <?php echo $htmlFunctions->makeStudentAddressSearch(); ?>
                   <tr height='5'></tr>
                   <?php echo $htmlFunctions->makeStudentMiscSearch(); ?>
                    <td valign='top' colspan='10' class='' align='center'>
                     <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/show_students.gif" onClick="return validateCommonStudentList();return false;" />
                    </td>
                   </tr>
              </table>
              <input type="hidden" name="selectedStudent" id="selectedStudent" value="" />
             </form>
            </td>
           </tr>
           
          <!--student part(Common)-->
          
          <!--student part(Hostel part)-->
          <!--
          <tr id="studentHostelSearchFilterRowId" style="display:none;">
           <td colspan="1" class="contenttab_row" style="border-top:none;">
           <form name="studentHostelDetailsForm" action="" method="post" onSubmit="return false;">
               <table border='0' width='100%' cellspacing='0' class="contenttab_border2">
                   <tr>
                    <td class="contenttab_internal_rows" width="1%" valign="top"><b>Hostel</b></td>
                    <td class="padding" width="1%" valign="top">:</td>
                    <td class="padding" width="5%" valign="top">
                     <div id="studentHostelContainerDiv">
                        <select multiple name="hostelId[]" id="studentHostelId" size="5" style="width:196px">
                         <?php
                           //echo $htmlFunctions->getHostelName();
                         ?>
                        </select>
                        </div>
                        <div style="display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF" id="studentHostelD1"></div>
                        <div style="display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF" class="inputbox" id="studentHostelD2" >
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" >
                        <tr>
                         <td id="studentHostelD3" width="95%" valign="middle" style="padding-left:3px;" class="contenttab_internal_rows"></td>
                         <td width="5%">
                          <img id="downArrawId" src="<?php echo IMG_HTTP_PATH;?>/down_arrow.gif" style="margin-bottom:0px;" onClick="popupMultiSelectDiv('studentHostelId','studentHostelD1','studentHostelContainerDiv','studentHostelD3');" />
                         </td>
                         </tr>
                        </table>
                        </div> 
                    </td>
                    <td class="contenttab_internal_rows" width="1%" valign="top"><nobr><b>Room Type</b></nobr></td>
                    <td class="padding" width="1%" valign="top">:</td>
                    <td class="padding" width="1%">
                     <div id="studentHostelRoomTypeDiv">
                        <select multiple name="studentHostelRoomTypeId[]" id="studentHostelRoomTypeId" size="5" style="width:196px" >
                        <?php
                           //echo $htmlFunctions->getHostelRoomTypeData();
                        ?>
                        </select>
                    </div>
                    <div style="display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF" id="studentHostelRoomTypeD1"></div>
                    <div style="display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF" class="inputbox" id="studentHostelRoomTypeD2" >
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" >
                        <tr>
                         <td id="studentHostelRoomTypeD3" width="95%" valign="middle" style="padding-left:3px;" class="contenttab_internal_rows"></td>
                         <td width="5%">
                          <img id="downArrawId" src="<?php echo IMG_HTTP_PATH;?>/down_arrow.gif" style="margin-bottom:0px;" onClick="popupMultiSelectDiv('studentHostelRoomTypeId','studentHostelRoomTypeD1','studentHostelRoomTypeContainerDiv','studentHostelRoomTypeD3');" />
                         </td>
                         </tr>
                        </table>
                     </div> 
                    </td>
                    <td class="contenttab_internal_rows" width="1%" valign="top"><b>Room</b></td>
                    <td class="padding" width="1%" valign="top">:</td>
                    <td class="padding" width="1%">
                     <div id="studentHostelRoomContainerDiv">
                        <select multiple name="studentHostelRoomId[]" id="studentHostelRoomId" size="5"  style="width:197px" >
                        <?php
                          //echo $htmlFunctions->getHostelRoomData();
                        ?>
                        </select>
                    </div>
                    <div style="display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF"    id="studentHostelRoomD1"></div>
                    <div style="display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF" class="inputbox" id="studentHostelRoomD2" >
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" >
                        <tr>
                         <td id="studentHostelRoomD3" width="95%" valign="middle" style="padding-left:3px;" class="contenttab_internal_rows"></td>
                         <td width="5%">
                          <img id="downArrawId" src="<?php echo IMG_HTTP_PATH;?>/down_arrow.gif" style="margin-bottom:0px;" onClick="popupMultiSelectDiv('studentHostelRoomId','studentHostelRoomD1','studentHostelRoomContainerDiv','studentHostelRoomD3');" />
                         </td>
                         </tr>
                        </table>
                     </div> 
                    </td>
                    <td class="padding" valign="top">
                     <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateHostelStudentList();return false;" />
                    </td>
                   </tr>
              </table>
             </form>
            </td>
           </tr>
           -->
          <!--student part(Hostel)-->
        
          <!--student part(Transport part)-->
          <!--
          <tr id="studentTransportSearchFilterRowId" style="display:none;">
           <td colspan="1" class="contenttab_row" style="border-top:none;">
           <form name="studentTransportDetailsForm" action="" method="post" onSubmit="return false;">
               <table border='0' width='100%' cellspacing='0' class="contenttab_border2">
                   <tr>
                    <td valign="top" colspan="1" class="" style="text-align:left;width:8%" >&nbsp;<b>Bus Stop&nbsp; </b></td>
                    <td valign="top" colspan="1" class="padding" style="width:1%">:</td>
                    <td valign="top" colspan="1" class="" style="text-align:left;padding-left:2px;width:200px" >
                    <div id="studentBusStopContainerDiv">
                        <select multiple name="studentBusStopId[]" id="studentBusStopId" size="5" style="width:196px" >
                        <?php
                         //echo $htmlFunctions->getBusStopName();
                        ?>
                        </select>
                    </div>
                    <div style="display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF" id="studentBusStopD1"></div>
                    <div style="display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF" class="inputbox" id="studentBusStopD2" >
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" >
                        <tr>
                         <td id="studentBusStopD3" width="95%" valign="middle" style="padding-left:3px;" class="contenttab_internal_rows"></td>
                         <td width="5%">
                          <img id="downArrawId" src="<?php echo IMG_HTTP_PATH;?>/down_arrow.gif" style="margin-bottom:0px;" onClick="popupMultiSelectDiv('studentBusStopId','studentBusStopD1','studentBusStopContainerDiv','studentBusStopD3');" />
                         </td>
                         </tr>
                        </table>
                     </div>    
                    </td>
                    <td valign="top" colspan="1" class="" style="text-align:left;width:8%" nowrap>&nbsp;<b>Bus Route&nbsp; </b></td>
                    <td valign="top" colspan="1" class="padding" style="width:1%">:</td>
                    <td valign="top" colspan="1" class="" style="text-align:left;width:200px" >
                    <div id="studentBusRouteContainerDiv">
                        <select multiple name="studentBusRouteId[]" id="studentBusRouteId" size="5" style="width:197px" >
                        <?php
                          //echo $htmlFunctions->getBusRouteName();
                        ?>
                        </select>
                    </div>
                    <div style="display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF"    id="studentBusRouteD1"></div>
                    <div style="display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF" class="inputbox" id="studentBusRouteD2" >
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" >
                        <tr>
                         <td id="studentBusRouteD3" width="95%" valign="middle" style="padding-left:3px;" class="contenttab_internal_rows"></td>
                         <td width="5%">
                          <img id="downArrawId" src="<?php echo IMG_HTTP_PATH;?>/down_arrow.gif" style="margin-bottom:0px;" onClick="popupMultiSelectDiv('studentBusRouteId','studentBusRouteD1','studentBusRouteContainerDiv','studentBusRouteD3');" />
                         </td>
                         </tr>
                        </table>
                     </div>    
                    </td>
                   <td class="padding" valign="top">
                     <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateTransportStudentList();return false;" />
                    </td>
                   </tr>
              </table>

             </form>
            </td>
           </tr>
           -->
          <!--student part(Transport)-->
          
          <!--student part(Subject part)-->
          <tr id="studentSubjectSearchFilterRowId" style="display:none;">
           <td colspan="1" class="contenttab_row" style="border-top:none;">
           <form name="studentSubjectDetailsForm" action="" method="post" onSubmit="return false;">
               <!--<table border='0' width='100%' cellspacing='0' class="contenttab_border2">-->
               <table border='0' width='100%' cellspacing='0'>
                   <tr>
                    <td class="contenttab_internal_rows" width="1%"><b>Class</b></td>
                    <td class="padding" width="1%">:</td>
                    <td class="padding" width="5%">
                     <select name="degreeId" id="selectedClassId" class="inputbox" onchange="fetchSubjectTypeSubjects(document.getElementById('timeTableLabelId').value,document.getElementById('catId').value,this.value);">
                      <option value="">Select</select>
                     </select>
                    </td>
                    <td class="contenttab_internal_rows" width="1%"><b>Subject</b></td>
                    <td class="padding" width="1%">:</td>
                    <td class="" width="1%" style="padding-left:7px;">
                    <div id="studentSubjectContainerDiv">
                     <select multiple="multiple" size="5" name="courseId[]" id="selectedSubjectId" style="width:200px;">
                     </select>
                     </div>
                     <div style="display:none;position:absolute;overflow:auto;border:1px solid #C6C6C6;overflow-x:hidden;background-color:#FFFFFF"    id="studentSubjectD1"></div>
                     <div style="display:none;position:absolute;text-align:middle;border:1px solid #C6C6C6;background-color:#FFFFFF" class="inputbox" id="studentSubjectD2" >
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" >
                        <tr>
                         <td id="studentSubjectD3" width="95%" valign="middle" style="padding-left:3px;" class="contenttab_internal_rows"></td>
                         <td width="5%">
                          <img id="downArrawId" src="<?php echo IMG_HTTP_PATH;?>/down_arrow.gif" style="margin-bottom:0px;" onClick="popupMultiSelectDiv('selectedSubjectId','studentSubjectD1','studentSubjectContainerDiv','studentSubjectD3');" />
                         </td>
                         </tr>
                        </table>
                     </div>
                    </td>
                    <td class="padding" valign="top" >
                     <input style="margin-bottom:-2px" type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/show_students.gif" onClick="return validateSubjectStudentList();return false;" />
                    </td>
                   </tr>
              </table>
              <input type="hidden" name="selectedStudent" id="selectedStudent2" value="" />
             </form>
            </td>
           </tr> 
          <!--student part(Transport)-->
          <!--Student List Showing-->
          <tr id="studentDisplayRowId" style="display:none;">
            <td colspan="1" class="contenttab_row" style="border-top:none;">   
             <div id="studentShowList" style="display:none">
               <table cellpadding="0" cellspacing="0" border="0" width="99%">
                  <tr>
                   <td><b>Total Records :</b>
                    <label id="totalStudentsRecordsId"></label>
                    &nbsp;&nbsp;
                   <input type="checkbox" id="sendToAllStudentChk" value="sendToAllChk" onclick="sdaStudent(this.checked);" />
                    <label for="sendToAllStudentChk" id="studentLabel">
                     <b>Assign  to All Students</b>
                    <label>
                    &nbsp;&nbsp;<div id="studentSummeryDiv"  style="display:inline"></div>
                   </td>
                  </tr>
                  <tr>
                   <td>
                    <form name="studentListFrm" id="studentListFrm">
                     <div id="studentResultsDiv"></div>
                    </form>
                   </td>
                  </tr>
                  <tr>
                   <td align="center">
                    <div id="studentDivButton" style="display:none">
                      <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateStudentForm();return false;" />
                      </div>
                     </td>
                    </tr>
                    </tr>
                   </table>
                </div>    
           </td>
          </tr>
          <!--Student List Showing (Ends)-->
          </table>
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table> 

<?php
   // $History: listAssignSurveyContentsAdv.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 23/01/10   Time: 16:13
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Corrected label text
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 22/01/10   Time: 17:06
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Made UI changes and modified images
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 1/21/10    Time: 5:22p
//Updated in $/LeapCC/Templates/FeedbackAdvanced
//Updated breadcrumbs and titles
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 19/01/10   Time: 13:05
//Created in $/LeapCC/Templates/FeedbackAdvanced
//Created "Assign Survey (Adv)" module
?>
