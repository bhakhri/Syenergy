<?php 
//This file creates Html Form output for student External Marks Report
//
// Author :Parveen Sharma
// Created on : 28-04-09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td valign="top" class="title">
 <?php     require_once(TEMPLATES_PATH . "/breadCrumb.php");   ?>
  </td>
 </tr>
 <tr>
  <td valign="top">
   <table width="100%" border="0" cellspacing="0" cellpadding="0" height="860">
    <tr>
     <td valign="top" class="content">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" height="860">
       <tr>
          <td class="contenttab_border" height="20">
             <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                  <tr>
                     <td class="content_title"> Upload & Download Images : </td>
                  </tr>
             </table>
          </td>
       </tr>
       <tr>
        <td valign="top" class="contenttab_row" >
         <table cellpadding="0" cellspacing="0" border="0" width="100%">
          <tr>
           <td valign="top" class="" align="center">
            <div id="dhtmlgoodies_tabView1">
             <div class="dhtmlgoodies_aTab" style="overflow:auto;">
      <form name="allDetailsForm" id="allDetailsForm" method="post" enctype="multipart/form-data" style="display:inline" onSubmit="return false;" > 
               <!--Add Student Filter-->
               <nobr>
               <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
                <tr>
                 <td valign="top" class="contenttab_row1" width="100%">
                  <table border='0' width='100%' cellspacing='0' class="">
                   <?php echo $htmlFunctions->makeStudentDefaultSearch('1'); ?>
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
                        <input type="hidden" name="degs" value="">
                        <input type="hidden" name="degsText" value="">
                        <input type="hidden" name="brans" value="">
                        <input type="hidden" name="branText" value="">
                        
                        <input type="hidden" name="subjectId" value="">
                        <input type="hidden" name="periods" value="">
                        <input type="hidden" name="periodsText" value="">

                        <input type="hidden" name="course" value="">
                        <input type="hidden" name="courseText" value="">

                        <input type="hidden" name="grps" value="">
                        <input type="hidden" name="grpsText" value="">
                        
                        <input type="hidden" name="univs" value="">
                        <input type="hidden" name="univsText" value="">

                        <input type="hidden" name="citys" value="">
                        <input type="hidden" name="citysText" value="">

                        <input type="hidden" name="states" value="">
                        <input type="hidden" name="statesText" value="">
                        
                        <input type="hidden" name="cnts" value="">
                        <input type="hidden" name="cntsText" value="">

                        <input type="hidden" name="hostels" value="">
                        <input type="hidden" name="hostelsText" value="">

                        <input type="hidden" name="buss" value="">
                        <input type="hidden" name="bussText" value="">

                        <input type="hidden" name="routs" value="">
                        <input type="hidden" name="routsText" value="">
                        <input type="hidden" name="quotaText" value="">
                        <input type="hidden" name="bloodGroupText" value="">

                        <input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateAddForm();return false;" />
                     </td>
                    </tr>
                            <tr id='nameRow' style='display:none;'>
                                <td valign='top' colspan='11' class='' > 
                                
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border">
                                        <tr>
                                            <td colspan="1" class="content_title" align="left">Upload & Download Images:</td>
                                            <td colspan="1" class="content_title" align="right">
              <input type="image" name="downloadImg" src="<?php echo IMG_HTTP_PATH;?>/download2.gif" onClick="printReport()" />&nbsp;
              <input type="image" name="uploadImg" src="<?php echo IMG_HTTP_PATH;?>/upload.gif" onClick="uploadImages()" />&nbsp;
                                            </td>
                                       </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id='resultRow' style='display:none;'>
                                <td colspan='11' class='contenttab_row' align="left">
                        <iframe id="uploadTargetEdit" name="uploadTargetEdit" style="width:0px;height:0px;border:0px solid #000;"></iframe>
                                          <div id="scroll2" style="overflow:auto;  width:98%;  height:490px; vertical-align:top;">
                                                <div id="resultsDiv" style="width:95%; vertical-align:top;"></div>
                                          </div>
                                </td>
                            </tr>
                            <tr id='nameRow2' style='display:none;'>
                                <td class="" colspan='11' height="20" align="right">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                        <tr>
                                            <td colspan="2" class="content_title" align="right">
                            <input type="image" name="downloadImg" src="<?php echo IMG_HTTP_PATH;?>/download2.gif" onClick="printReport()" />&nbsp;
                            <input type="image" name="uploadImg" src="<?php echo IMG_HTTP_PATH;?>/upload.gif" onClick="uploadImages()" />&nbsp;
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                  </td>
                 </tr>
                </table>
                </nobr>
                </form>
                 <form name="listFrm" id="listFrm">
                     <!--Do not delete-->
                     <input type="hidden" name="students" id="students" />
                     <input type="hidden" name="students" id="students" />
                     <!--Do not delete-->
                    </form>
                  
                </div>
                
                <!-- Employee Info Start -->
                   <div class="dhtmlgoodies_aTab" style="overflow:auto;"  >
                   <form name="employeeDetailsForm" id="employeeDetailsForm" method="post" enctype="multipart/form-data" style="display:inline" onSubmit="return false;" > 
               <!--Add Employee Filter-->
               <input type="hidden" name="employeesPage" id="employeesPage" value="1" />
               <input type="hidden" name="employeesSortOrderBy" id="employeesSortOrderBy" value="ASC" />
               <input type="hidden" name="employeesSortField" id="employeesSortField"  value="employeeName" />
               <nobr>
               <table width="100%" border="0" cellspacing="0" cellpadding="0" class="" >
                <tr>
                 <td valign="top" class="contenttab_row1">
                  <table border='0' width='100%' cellspacing='0'>
                    <?php echo $htmlFunctions->makeEmployeeDefaultSearch(); ?>
                    <tr height='5'></tr>
                    <?php echo $htmlFunctions->makeEmployeeAcademicSearch_feedback(false,'emp_','employeeDetailsForm'); ?>
                    <tr height='5'></tr>
                    <?php echo $htmlFunctions->makeEmployeeAddressSearch_feedback('emp_','employeeDetailsForm'); ?>
                    <tr height='5'></tr>
                    <?php echo $htmlFunctions->makeEmployeeMiscSearch_feedback('emp_'); ?>
                   <tr>
                    <td valign='top' colspan='11' class='' align='center'>
                       
<input type="image" name="empListSubmit" value="empListSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="return validateEmployeeList();return false;" />
                     </td>
                    </tr>
                 
                            <tr id='nameRow4' style='display:none;'>
                                <td colspan='11'  class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                        <tr>
                                            <td colspan="1" class="content_title" align="left">Upload & Download Images:</td>
                                            <td colspan="1" class="content_title" align="right">
                <input type="image" name="empdownImg" src="<?php echo IMG_HTTP_PATH;?>/download2.gif" onClick="empPrintReport()" />&nbsp;
                <input type="image" name="emploadImg" src="<?php echo IMG_HTTP_PATH;?>/upload.gif" onClick="empUploadImages()" />&nbsp;
                                            </td>
                                       </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id='resultRow4' style='display:none;'>
                                <td colspan='11'  class='contenttab_row' align="left">
                        <iframe id="empUploadTargetEdit" name="empUploadTargetEdit" style="width:0px;height:0px;border:0px solid #000;"></iframe>
                                     <div id="scroll4" style="overflow:auto; width:98%; height:490px; vertical-align:top;">
                                        <div id="employeeResultsDiv" style="width:95%; vertical-align:top;"></div>
                                     </div>
                                </td>
                            </tr>
                            <tr id='nameRow5' style='display:none;'>
                                <td colspan='11'  class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                        <tr>
                                            <td colspan="2" class="content_title" align="right">
                <input type="image" name="empdownImg" src="<?php echo IMG_HTTP_PATH;?>/download2.gif" onClick="empPrintReport()" />&nbsp;
                <input type="image" name="emploadImg" src="<?php echo IMG_HTTP_PATH;?>/upload.gif" onClick="empUploadImages()" />&nbsp;
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                  </td>
                 </tr>
                </table>
                </nobr>
                </form>
                 <form name="listFrm2" id="listFrm2">
                     <!--Do not delete-->
                     <input type="hidden" name="employees" id="employees" />
                     <input type="hidden" name="employees" id="employees" />
                     <!--Do not delete-->
                    </form>
                  </div>
                <!-- Employee Info End -->
                
                 </div>
                 <script type="text/javascript">
                  initTabs('dhtmlgoodies_tabView1',Array('Students','Employees'),0,970,780,Array(false,false));
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

<!--Start Topic  Div-->
<?php floatingDiv_Start('divInformation','Error(s)/Warning(s)','',''); ?>
<form name="divForm" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>
      <td valign="top" align="center">
        <div class='report'>For following student doesn't upload photo(s).</div> 
      </td>
    </tr>
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>    
        <td width="95%" align="center" valign="top">                          
          <div  style="overflow:auto; width:550px; height:350px; vertical-align:top;">
            <div id="uploadInfo" style="width:530px; height:350px; vertical-align:top;"></div>
          </div>  
        </td>
    </tr>
</table>
</form> 
<?php floatingDiv_End(); ?>

<?php 
//$History: downloadImagesContent.php $
//
//*****************  Version 12  *****************
//User: Gurkeerat    Date: 4/02/10    Time: 18:57
//Updated in $/LeapCC/Templates/AdminTasks
//resolved issues 0002650,0002620,0002098,0001602,0002788,0002785
//
//*****************  Version 11  *****************
//User: Parveen      Date: 1/15/10    Time: 2:53p
//Updated in $/LeapCC/Templates/AdminTasks
//validation format & condition updated
//
//*****************  Version 10  *****************
//User: Parveen      Date: 1/06/10    Time: 5:31p
//Updated in $/LeapCC/Templates/AdminTasks
//format updated
//
//*****************  Version 9  *****************
//User: Parveen      Date: 12/19/09   Time: 1:28p
//Updated in $/LeapCC/Templates/AdminTasks
//resultsDiv Div height remove
//
//*****************  Version 8  *****************
//User: Parveen      Date: 12/18/09   Time: 4:27p
//Updated in $/LeapCC/Templates/AdminTasks
//form name updated
//
//*****************  Version 7  *****************
//User: Parveen      Date: 12/18/09   Time: 4:26p
//Updated in $/LeapCC/Templates/AdminTasks
//form name updated
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 12/18/09   Time: 12:53p
//Updated in $/LeapCC/Templates/AdminTasks
//removed help link
//
//*****************  Version 4  *****************
//User: Parveen      Date: 12/10/09   Time: 1:36p
//Updated in $/LeapCC/Templates/AdminTasks
//download & upload button added top
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/10/09   Time: 1:16p
//Updated in $/LeapCC/Templates/AdminTasks
//file uploading condition & formatting updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/05/09   Time: 5:54p
//Updated in $/LeapCC/Templates/AdminTasks
//upload & download format & validation added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 12/04/09   Time: 6:53p
//Created in $/LeapCC/Templates/AdminTasks
//initial checkin
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/16/09    Time: 3:30p
//Updated in $/LeapCC/Templates/StudentReports
//resultDiv scrolling added
//
//*****************  Version 1  *****************
//User: Parveen      Date: 4/29/09    Time: 11:29a
//Created in $/LeapCC/Templates/StudentReports
//file added
//

?>
