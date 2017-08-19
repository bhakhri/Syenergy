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
   <table width="100%" border="0" cellspacing="0" cellpadding="0" height="450">
    <tr>
     <td valign="top" class="content">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
       <tr>
        <td class="contenttab_border" height="20">
         <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
          <tr>
           <td class="content_title">
            Assign Survey :
           </td>
           <td class="content_title" >
            &nbsp;
           </td>
          </tr>
         </table>
        </td>
       </tr>
       <!--Assign -->
       <tr>
        <td class="contenttab_row" style="border-bottom:0px">
         <table border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td><b>Survey:</b></td>
            <td style="padding-left:5px">
            <select id="surveyId" name="surveyId" class="inputbox" onchange="getAssignedSurveyList(this.value);">
            <option value="" selected="selected">Select</option>
             <?php
              echo $htmlFunctions->getFeedBackLabelData('',' WHERE isActive=1');
             ?>
            </select>
            </td>
            <td style="padding-left:5px"><b>Summary:</b></td>
            <td>&nbsp;
              <div id="studentSummeryDiv"  style="display:inline"></div>
              <div id="parentSummeryDiv"   style="display:inline"></div>
              <div id="employeeSummeryDiv" style="display:inline"></div>
            </td>
          </tr>
         </table>
        </td>
       </tr>
       <!--Assign -->
       <tr>
        <td valign="top" class="contenttab_row" height="440">
         <table cellpadding="0" cellspacing="0" border="0" width="100%">
          <tr>
           <td>
            <div id="dhtmlgoodies_tabView1">
             <div class="dhtmlgoodies_aTab" style="overflow:auto" >
              <form name="allDetailsForm" action="" method="post" onSubmit="return false;">
               <!--Add Student Filter-->
               <nobr>
               <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2" >
                <tr>
                 <td valign="top"  align="center">
                  <table border='0' width='100%' cellspacing='0'>
                   <?php echo $htmlFunctions->makeStudentDefaultSearch(); ?>
                   <tr height='5'>
                   </tr>
                   <?php echo $htmlFunctions->makeStudentAcademicSearch(false); ?>
                   <tr height='5'>
                   </tr>
                   <?php echo $htmlFunctions->makeStudentAddressSearch(); ?>
                   <tr height='5'>
                   </tr>
                   <?php echo $htmlFunctions->makeStudentMiscSearch(); ?>
                   <tr>
                    <td valign='top' colspan='11' class='' align='center'>
                     <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateStudentList();return false;" />
                     </td>
                    </tr>
                   </table>
                  </td>
                 </tr>
                </table>
                </nobr>
                 <input type="hidden" name="selectedStudent" id="selectedStudent" value="" />
               </form>
                <br />
                <div id="showList" style="display:none">
                 <table cellpadding="0" cellspacing="0" border="0" width="99%">
                  <tr>
                   <td>
                    <b>
                     Total Records :
                    </b>
                    <label id="totalStudentsRecordsId">
                    </label>
                   </b>
                   &nbsp;&nbsp;
                   <input type="checkbox" id="sendToAllStudentChk" value="sendToAllChk" onclick="sdaStudent(this.checked);" />
                   <label for="sendToAllStudentChk" id="studentLabel">
                    <b>
                     Assign to All Students
                    </b>
                   </b>
                   </label>

                   </td>
                  </tr>
                  <tr>
                   <td>
                    <form name="listFrm" id="listFrm">
                     <!--Do not delete-->
                     <input type="hidden" name="students" id="students" />
                     <input type="hidden" name="students" id="students" />
                     <!--Do not delete-->
                     <div id="studentSearchDiv">
                     </div>
                    </form>
                   </td>
                  </tr>
                  <tr>
                   <td height="5px">
                   </td>
                  </tr>
                  <tr>
                   <td align="center">
                    <div id="divButton1" style="display:none">
                     <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateStudentForm();return false;" />
                      <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="hide_div('showList',2);resetForm1();return false;" />
                      </div>
                     </td>
                    </tr>
                    <tr>
                     <td height="5px">
                     </td>
                    </tr>
                   </table>
                  </div>

                </div>
                <!--Parent Info -->
                <div class="dhtmlgoodies_aTab" style="overflow:auto" >
              <form name="parentDetailsForm" action="" method="post" onSubmit="return false;">
               <!--Add Parent Filter-->
               <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2" >
                <tr>
                 <td valign="top"  align="center" >
				 <table border='0' width='100%' cellspacing='0'>
                   <?php echo $htmlFunctions->makeStudentDefaultSearch(); ?>
                   <tr height='5'></tr>
                   <?php echo $htmlFunctions->makeParentAcademicSearch(false,'parentDetailsForm'); ?>
                   <tr height='5'></tr>
                   <?php echo $htmlFunctions->makeParentAddressSearch('parentDetailsForm'); ?>
                   <tr height='5'></tr>
                   <?php echo $htmlFunctions->makeParentMiscSearch('parentDetailsForm'); ?>
                   <tr>
                    <td valign='top' colspan='10' class='' align='center'>
                     <input type="image" name="parentListSubmit" value="parentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateParentList();return false;" />
                     </td>
                    </tr>
                   </table>
                  </td>
                 </tr>
                </table>
                <input type="hidden" name="selectedParent" id="selectedParent" value="" />
               </form>
                <br />
                <div id="showList1" style="display:none">
                 <table cellpadding="0" cellspacing="0" border="0" width="99%">
                  <tr>
                   <td>
                    <b>
                     Total Records :
                    </b>
                    <label id="totalParentsRecordsId">
                    </label>
                   </b>
                   &nbsp;&nbsp;
                   <input type="checkbox" id="sendToAllParentChk" value="sendToAllChk" onclick="sdaParent(this.checked);" />
                   <label for="sendToAllParentChk" id="parentLabel" >
                    <b>
                     Assign to All Parents
                    </b>
                   </b>
                   <label>
                   </td>
                  </tr>
                  <tr>
                   <td>
                    <form name="listFrm1" id="listFrm1">
                     <!--Do not delete-->
                     <input type="hidden" name="parents" id="parents" />
                     <input type="hidden" name="parents" id="parents" />
                     <!--Do not delete-->
                     <div id="parentSearchDiv">
                     </div>
                    </form>
                   </td>
                  </tr>
                  <tr>
                   <td height="5px">
                   </td>
                  </tr>
                  <tr>
                   <td align="center">
                    <div id="divButton2" style="display:none">
                     <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateParentForm();return false;" />
                      <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="hide_div('showList1',2);resetForm2();return false;" />
                      </div>
                     </td>
                    </tr>
                    <tr>
                     <td height="5px">
                     </td>
                    </tr>
                   </table>
                  </div>
                 </div>
                 <!--Employee Info -->
                <div class="dhtmlgoodies_aTab" style="overflow:auto" >
              <form name="employeeDetailsForm" action="" method="post" onSubmit="return false;">
               <!--Add Employee Filter-->
               <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2" >
                <tr>
                 <td valign="top"  align="center">
                  <table border='0' width='100%' cellspacing='0'>
                   <?php echo $htmlFunctions->makeEmployeeDefaultSearch(); ?>
                   <tr height='5'></tr>
                   <?php echo $htmlFunctions->makeEmployeeAcademicSearch_feedback(false,'emp_','employeeDetailsForm'); ?>
                   <tr height='5'></tr>
                   <?php echo $htmlFunctions->makeEmployeeAddressSearch_feedback('emp_','employeeDetailsForm'); ?>
                   <tr height='5'></tr>
                   <?php echo $htmlFunctions->makeEmployeeMiscSearch_feedback('emp_'); ?>
                   <tr>
                    <td valign='top' colspan='8' class='' align='center'>
                     <input type="image" name="employeeListSubmit" value="employeeListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateEmployeeList();return false;" />
                     </td>
                    </tr>
                   </table>
                  </td>
                 </tr>
                </table>
                <input type="hidden" name="selectedEmp" id="selectedEmp" value="" />
               </form>
                <br />
                <div id="showList2" style="display:none">
                 <table cellpadding="0" cellspacing="0" border="0" width="99%">
                  <tr>
                   <td>
                    <b>
                     Total Records :
                    </b>
                    <label id="totalEmployeesRecordsId">
                    </label>
                   </b>
                   &nbsp;&nbsp;
                   <input type="checkbox" id="sendToAllEmployeeChk" value="sendToAllChk" onclick="sdaEmployee(this.checked);" />
                   <label for="sendToAllEmployeeChk" id="employeeLabel">
                    <b>
                     Assign  to All Employees
                    </b>
                   </b>
                   <label>
                   </td>
                  </tr>
                  <tr>
                   <td>
                    <form name="listFrm2" id="listFrm2">
                     <!--Do not delete-->
                     <input type="hidden" name="employees" id="employees" />
                     <input type="hidden" name="employees" id="employees" />
                     <!--Do not delete-->
                     <div id="employeeSearchDiv">
                     </div>
                    </form>
                   </td>
                  </tr>
                  <tr>
                   <td height="5px">
                   </td>
                  </tr>
                  <tr>
                   <td align="center">
                    <div id="divButton3" style="display:none">
                     <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateEmployeeForm();return false;" />
                      <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="hide_div('showList2',2);resetForm3();return false;" />
                      </div>
                     </td>
                    </tr>
                    <tr>
                     <td height="5px">
                     </td>
                    </tr>
                   </table>
                  </div>
                 </div>
                 </div>
                 <script type="text/javascript">
                  initTabs('dhtmlgoodies_tabView1',Array('Students','Parents','Employees'),0,970,400,Array(false,false,false));
                 </script>
                </td>
               </tr>
              </table>
             </td>
            </tr>
           </table>
          </td>
         </tr>
        </table>
       </td>
      </tr>
     </table>
     <?php
     // $History: scListAssignSurveyContents.php $
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 12/17/09   Time: 10:19a
//Updated in $/LeapCC/Templates/ScAssignSurvey
//Updated breadcrumb according to the new menu structure
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 23/06/09   Time: 14:46
//Updated in $/LeapCC/Templates/ScAssignSurvey
//Done bug fixing.
//bug ids----
//00000187,00000191,00000198,00000199,00000203,00000204,
//00000205,00000207,0000209,00000211
//
//*****************  Version 2  *****************
//User: Administrator Date: 11/06/09   Time: 11:15
//Updated in $/LeapCC/Templates/ScAssignSurvey
//Done bug fixing.
//bug ids---
//0000011,0000012,0000016,0000018,0000020,0000006,0000017,0000009,0000019
//
//*****************  Version 1  *****************
//User: Administrator Date: 21/05/09   Time: 14:42
//Created in $/LeapCC/Templates/ScAssignSurvey
//Copied "Assign Survey" module from Leap to LeapCC
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 1/24/09    Time: 11:59a
//Updated in $/Leap/Source/Templates/ScAssignSurvey
//fixed bugs in survey assign for student, parent, employee can not
//delete the record if exist in another table
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 1/20/09    Time: 6:25p
//Updated in $/Leap/Source/Templates/ScAssignSurvey
//fixed the font-changing bug in css and removed unwanted code from file
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 19/01/09   Time: 12:04
//Updated in $/Leap/Source/Templates/ScAssignSurvey
//Modified default font size
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 19/01/09   Time: 12:00
//Updated in $/Leap/Source/Templates/ScAssignSurvey
//Corrected Colspan and common filters
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 13/01/09   Time: 12:29
//Updated in $/Leap/Source/Templates/ScAssignSurvey
//Fixed bugs related to "Assign Survey" module
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 10/01/09   Time: 17:34
//Updated in $/Leap/Source/Templates/ScAssignSurvey
//Corrected Image Path
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/01/09    Time: 18:34
//Updated in $/Leap/Source/Templates/ScAssignSurvey
//Corrected spelling mistake and javascript logic
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/01/09    Time: 16:58
//Updated in $/Leap/Source/Templates/ScAssignSurvey
//Corrected survey label display condition
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/01/09    Time: 13:26
//Updated in $/Leap/Source/Templates/ScAssignSurvey
//Crrected pagination related problem
//[maintained checkbox state in pagination]
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 5/01/09    Time: 18:08
//Created in $/Leap/Source/Templates/ScAssignSurvey
//Created "Assign Survey" module
     ?>