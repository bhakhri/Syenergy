<?php
//-------------------------------------------------------
// Purpose: To design the layout for Employee Information.
//
// Author : Parveen Sharma
// Created on : (24.06.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    $queryString =  $_SERVER['QUERY_STRING'];
    // echo $queryString;
?>
<form method="POST" name="addForm"  id="addForm" method="post"  style="display:inline" onSubmit="return false;">                      
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">Setup &nbsp;&raquo;&nbsp;Administrative Masters&nbsp;&raquo;&nbsp;Employee Master&nbsp;&raquo;&nbsp;Employee Detail</td>
                <td align="right">
                   <input type="image" alt="back" title="back" src="<?php echo IMG_HTTP_PATH ?>/bigback.gif" border="0" onClick='listPage("listEmployee.php?<?php echo $queryString?>&listEmployee=1");return false;'>
                    &nbsp;&nbsp;&nbsp;
                    </td>
                </td>
            </tr>                                             
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0" height="650">
             <tr>
                <td class="contenttab_border" height="20">
                    <div class="content_title">Employee Detail: </div>                
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
    <div id="dhtmlgoodies_tabView1">
     <div class="dhtmlgoodies_aTab">
      <table width="100%" border="0px" cellspacing="5px" cellpadding="0" >
                        <tr>
                            <td colspan="12" height="5"></td>
                        </tr>
                        <tr>
						    <td width="100" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Employee Name</b></nobr></td>
                            <td width="5"  class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td width="240" class="contenttab_internal_rows"><label><?php echo parseOutput($employeeArr[0]['employeeName']);?></label></td>
                            <td width="105" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Employee Abbr. </b></nobr></td>
                            <td width="5"  class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td width="250"  class="contenttab_internal_rows"><label><?php echo parseOutput($employeeArr[0]['employeeAbbreviation']);?></label>
                            </td>
                            <td colspan="6" align="center" valign="middle" rowspan="9">
                               <table border="0" width="200" height="200">
                                <tr>
                                    <td width="50"></td>
                                    <td>&nbsp;</td>
                                    <td class="field1_heading">
                                    <?php if($employeeArr[0]['employeeImage']){ 
                                        $imgSrc= IMG_HTTP_PATH.'/Employee/'.$employeeArr[0]['employeeImage'];
                                    }
                                    else{
                                        $imgSrc= IMG_HTTP_PATH."/notfound.jpg";
                                    }
                                    echo "<img src='".$imgSrc."' width='170' height='190' id='employeeImageId'/>";
                                    ?>
                                   </td>
                               </tr>
                            </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Employee Code</b></nobr></td>
                            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td class="contenttab_internal_rows"><label><?php echo parseOutput($employeeArr[0]['employeeCode']);?></label>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;User Name</b></nobr></td>
                            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td class="contenttab_internal_rows"><label type="text"><?php echo parseOutput($employeeArr[0]['userName']);?></label>
                            </td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Designation</b></nobr></td>
                            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td class="contenttab_internal_rows"><label type="text"><?php echo parseOutput($employeeArr[0]['designationName']);?></label>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Role</b></nobr></td>
                            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td class="contenttab_internal_rows"><label type="text"><?php echo parseOutput($employeeArr[0]['roleName']);?></label>
                            </td>
                        </tr>
                        <tr>    
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Department</b></nobr></td>
                            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td class="contenttab_internal_rows"><label type="text"><?php echo parseOutput($employeeArr[0]['departmentAbbr']);?></label>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Branch </b></nobr></td>
                            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td class="contenttab_internal_rows"><label><?php echo parseOutput($employeeArr[0]['branchCode']);?></label>
                            </td>
                        </tr>
                        <tr>  
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Teaching Institutes </b></nobr></td>
                            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td class="contenttab_internal_rows"><label><?php echo parseOutput($employeeArr[0]['teachingInstitutes']);?></label>
                            </td>                            
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Qualification </b></nobr></td>
                            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td class="contenttab_internal_rows"><label><?php echo parseOutput($employeeArr[0]['qualification']);?></label>
                            </td>
                        </tr>
                        <tr>   
                           <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Date of Birth </b></nobr></td>
                            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td class="contenttab_internal_rows"><?php 
                                    $dt=$employeeArr[0]['dateOfBirth'];
                                    if($dt=='0000-00-00') {
                                       echo NOT_APPLICABLE_STRING;
                                    }
                                    else {
                                        echo parseOutput(UtilityManager::formatDate(strip_slashes($dt)));
                                    }
                                    ?>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Gender </b></nobr></td>
                            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td class="contenttab_internal_rows"><label><?php if($employeeArr[0]['gender']=="M") {
                            echo parseOutput("Male");
                            }
                            else{
                                echo parseOutput("Female");
                            }
                            ?>
                            </label>
                            </td>
                        </tr>
                        <tr> 
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Marital Status </b></nobr></td>
                            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td class="contenttab_internal_rows"><label><?php 
                                if($employeeArr[0]['isMarried']==0) {
                                  echo 'No';  
                                }
                                else if($employeeArr[0]['isMarried']==1) {
                                   echo 'Yes';
                                }
                                else {
                                   echo parseOutput('');  
                                }
                                ?></label>
                            </td> 

                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Date of Marriage</b></nobr></td>
                            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td class="contenttab_internal_rows"><label><?php 
                                    $dt=$employeeArr[0]['dateOfMarriage'];
                                    if($dt=='0000-00-00') {
                                       echo NOT_APPLICABLE_STRING;
                                    }
                                    else {
                                        echo parseOutput(UtilityManager::formatDate(strip_slashes($dt)));
                                    }?>
                                    </label>
                            </td> 
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Spouse </b></nobr></td>
                            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td class="contenttab_internal_rows"><label><?php echo parseOutput($employeeArr[0]['spouseName']);?></label>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Date of Joining</b></nobr></td>
                            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td class="contenttab_internal_rows"><label><?php 
                                    $dt=$employeeArr[0]['dateOfJoining'];
                                    if($dt=='0000-00-00') {
                                       echo NOT_APPLICABLE_STRING;
                                    }
                                    else {
                                        echo parseOutput(UtilityManager::formatDate(strip_slashes($dt)));
                                    }?>
                                    </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Father's Name</b></nobr></td>
                            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                             <td class="contenttab_internal_rows">
                            <?php echo parseOutput($employeeArr[0]['fatherName']); ?></td>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Mother's Name</b></nobr></td>
                            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td class="contenttab_internal_rows">
                            <?php echo parseOutput($employeeArr[0]['motherName']); ?></td>
                            </td>
                        </tr>
                        <!--  
                        <tr>
                            <td height="5" colspan="6"></td>
                        </tr>
                      
                        <tr>
                            <td colspan="6">&nbsp;&nbsp;&nbsp;<B><U>Address</U></B></td>
                        </tr> 
                        -->
                        <tr>
                            <td class="contenttab_internal_rows" valign="top"><nobr><b>&nbsp;&nbsp;&nbsp;Address1</b></nobr></td>
                            <td class="contenttab_internal_rows" valign="top"><nobr><b>:</b></nobr></td>
                             <td class="contenttab_internal_rows" valign="top">
                            <?php echo parseOutput($employeeArr[0]['address1']); ?></td>
                            </td>
                            <td class="contenttab_internal_rows" valign="top"><nobr><b>&nbsp;&nbsp;&nbsp;Address2</b></nobr></td>
                            <td class="contenttab_internal_rows" valign="top"><nobr><b>:</b></nobr></td>
                            <td class="contenttab_internal_rows" valign="top">
                            <?php echo parseOutput($employeeArr[0]['address2']); ?></td>
                            </td>
                        </tr>
                         <tr>
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;City</b></nobr></td>
                            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                             <td class="contenttab_internal_rows">
                            <?php echo parseOutput($employeeArr[0]['cityName']); ?></td>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;State</b></nobr></td>
                            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td class="contenttab_internal_rows"  colspan="9">
                            <?php echo parseOutput($employeeArr[0]['stateName']); ?></td>
                            </td>
                        </tr>
                         <tr>
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Country</b></nobr></td>
                            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                             <td class="contenttab_internal_rows">
                            <?php echo parseOutput($employeeArr[0]['countryName']); ?></td>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Pin Code</b></nobr></td>
                            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td class="contenttab_internal_rows" colspan="9">
                            <?php echo parseOutput($employeeArr[0]['pinCode']); ?></td>
                            </td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Contact No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td class="contenttab_internal_rows">
                            <?php echo parseOutput($employeeArr[0]['contactNumber']); ?></td>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Mobile No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td class="contenttab_internal_rows" colspan="9">
                            <?php echo parseOutput($employeeArr[0]['mobileNumber']); ?></td>
                            </td>
                        </tr>
                        <tr>
                           <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Email</b></nobr></td>
                           <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                           <td class="contenttab_internal_rows"><label><?php echo parseOutput($employeeArr[0]['emailAddress']);?></label>
                           </td>
                        </tr>
                         <tr>
                            <td colspan="12" height="5"></td>
                        </tr>
                </table>
     
 <?php
 
 /*            <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                        <tr>
                            <td height="5"></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Employee Name</b></nobr></td>
                            <td class="padding">:&nbsp;<label><?php echo parseOutput($employeeArr[0]['employeeName']);?></label>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Employee Code</b></nobr></td>
                            <td class="padding">:&nbsp;<label><?php echo parseOutput($employeeArr[0]['employeeCode']);?></label>
                            </td>
                            <td colspan="4" align="center" valign="middle" rowspan="9">
                               <table border="0" width="200" height="200">
                                <tr>
                                    <td width="50"></td>
                                    <td class="field1_heading">
                                    <?php if($employeeArr[0]['employeePhoto']){ 
                                        $imgSrc= STUDENT_PHOTO_PATH."/".$employeeArr[0]['employeePhoto'];
                                    }
                                    else{
                                        $imgSrc= IMG_HTTP_PATH."/notfound.jpg";
                                    }
                                    echo "<img src='".$imgSrc."' width='170' height='190' id='employeeImageId'/>";
                                    ?>
                                   </td>
                               </tr>
                            </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Designation</b></nobr></td>
                            <td class="padding">:&nbsp;<label type="text"><?php echo parseOutput($employeeArr[0]['designationName']);?></label>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Branch </b></nobr></td>
                            <td class="padding">:&nbsp;<label><?php echo parseOutput($employeeArr[0]['branchCode']);?></label>
                            </td>
                        </tr>
                        <tr>    
                            
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Teaching Employee</b></nobr></td>
                            <td class="padding">:&nbsp;<label><?php echo parseOutput($employeeArr[0]['isTeaching']);?></label>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Qualification </b></nobr></td>
                            <td class="padding">:&nbsp;<label><?php echo parseOutput($employeeArr[0]['qualification']);?></label>
                            </td>
                        </tr>
                        <tr>    
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Date Of Birth </b></nobr></td>
                            <td class="padding">:&nbsp;<?php 
                               $dt=$employeeArr[0]['dateOfBirth'];
                               if($dt=='0000-00-00') {
                                       echo NOT_APPLICABLE_STRING;
                               }
                               else {
                                 echo parseOutput(UtilityManager::formatDate(strip_slashes($dt)));
                               }
                               ?>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Gender </b></nobr></td>
                            <td class="padding">:&nbsp;<label><?php if($employeeArr[0]['gender']=="M") {
                            echo parseOutput("Male");
                            }
                            else{
                                echo parseOutput("Female");
                            }
                            ?>
                            </label>
                            </td>
                        </tr>
                        <tr>  
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Date of Joining</b></nobr></td>
                            <td class="padding">:&nbsp;<label><?php 
                                    $dt=$employeeArr[0]['dateOfJoining'];
                                    if($dt=='0000-00-00') {
                                       echo NOT_APPLICABLE_STRING;
                                    }
                                    else {
                                        echo parseOutput(UtilityManager::formatDate(strip_slashes($dt)));
                                    }
                                    ?>
                                    </label>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Email</b></nobr></td>
                            <td class="padding">:&nbsp;<label><?php echo parseOutput($employeeArr[0]['emailAddress']);?></label>
                            </td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Father's Name</b></nobr></td>
                             <td class="padding">:&nbsp; 
                            <?php echo parseOutput($employeeArr[0]['fatherName']); ?></td>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Mother's Name</b></nobr></td>
                            <td class="padding">:&nbsp; 
                            <?php echo parseOutput($employeeArr[0]['motherName']); ?></td>
                            </td>
                        </tr>
                        <tr>
                            <td height="5"></td>
                        </tr>
                         <tr>
                            <td height="5" colspan="4">&nbsp;&nbsp;&nbsp;<B><U>Address</U></B></td>
                        </tr>
                         <tr>
                            <td height="5"></td>
                        </tr>
                        <tr>    
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Address1</b></nobr></td>
                            <td class="padding">:&nbsp;<label><?php echo parseOutput($employeeArr[0]['address1']);?></label></td>
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Address2</b></nobr></td>
                            <td class="padding" colspan="5">:&nbsp;<label><?php echo parseOutput($employeeArr[0]['address2']);?></label>
                            </td>
                        </tr>
                        </tr>    
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Pincode</b></nobr></td>
                            <td class="padding">:&nbsp;<label><?php echo parseOutput($employeeArr[0]['pinCode']);?></label></td>
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Country</b></nobr></td>
                            <td class="padding">:&nbsp; 
                            <?php echo parseOutput($employeeArr[0]['countryName']); ?>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;State</b></nobr></td>
                            <td class="padding">:&nbsp;                                  
                            <?php echo parseOutput($employeeArr[0]['stateName']); ?>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;City</b></nobr></td>
                            <td class="padding">:&nbsp;
                            <?php echo parseOutput($employeeArr[0]['cityName']); ?>
                            </td>
                        </tr>
                        <tr>    
                            <td class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Contact No</b></nobr></td>
                            <td class="padding" colspan="7">:&nbsp;<label><?php echo parseOutput($employeeArr[0]['contactNumber']);?></label>
                            </td>
                        </tr>
                         <tr>
                            <td height="5"></td>
                        </tr>
                        </table>
   */
?>
             </div>

             <!-- Lecture Details  Start -->
	<?php	if($employeeArr[0]['isTeaching']=='Yes') { ?>
           <div class="dhtmlgoodies_aTab" style="vertical-align:top;">
               <select class="selectfield" name="lectureGroupType" id="lectureGroupType" style="display:none">
                 <?php
                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                      echo HtmlFunctions::getInstance()->getGroupTypeData();
                 ?>
                </select>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
                        <tr>
                          <td valign="top" class="contenttab_row1">
                           <table align="center" border="0" cellpadding="0" width="30%">
                           <tr>
                             <td class="contenttab_internal_rows"><nobr><b>Select Time Table: </b></nobr></td>
                              <td class="padding">
                                  <select size="1" class="inputbox1" name="labelId" id="labelId" onChange="hideLectureResults(); return false; ">  
                                    <option value="">Select</option>
                                     <?php
                                       require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                       echo HtmlFunctions::getInstance()->getTimeTableLabelData('','AND isActive=1');
                                     ?>
                                    </select>
                               </td>
                               <td align="center" colspan="4" >
                                   <span style="padding-right:10px" >
                                    <td valign="middle">
                                        <input type="image" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="refreshLectureData(<?php echo $employeeArr[0]['employeeId']?>,document.getElementById('labelId').value); return false;"/>
                                   </td>     
                                  </span>
                               </td>    
                           </tr>
                       </table>  
                      <div id='resultLecture' style='display:none;'>  
                       <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr id='nameRow'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                        <tr>
                                            <td colspan="1" class="content_title">Lecture Details :</td>
                                            <td colspan="1" class="content_title" align="right">
                                                <input type="image" name="teacherPrintSubmit" value="teacherPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="lecturePrintReport()" />&nbsp;
                                                <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="lecturePrintReportCSV()"/>&nbsp;
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr id='resultRow'>
                                <td colspan='1' class='contenttab_row'>
                                    <div id="lecturerResultDiv11" style="overflow:auto; HEIGHT:460px; vertical-align:top;">
                                       <div id="lecturerResultDiv" style="HEIGHT:450px; vertical-align:top;"></div> 
                                    </div>                         
                                </td>
                            </tr>
                            <tr id='nameRow2'>
                                <td class="" height="20">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                        <tr>
                                            <td colspan="2" class="content_title" align="right">
                                                <input type="image" name="teacherPrintSubmit" value="teacherPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="lecturePrintReport()" />&nbsp;
                                                <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="lecturePrintReportCSV()"/>&nbsp;
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        </div>
                        </td>
                       </tr>
                     </table>   
                    </div>
                    </td>
                </tr>
                </table>  
             </div>
		<?php } ?>
             <!-- Lecture Details  End -->
             
             <!-- Course Topics  Start -->
		<?php	if($employeeArr[0]['isTeaching']=='Yes') { ?>
             <div class="dhtmlgoodies_aTab">
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                    <tr>
                        <td valign="top">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
                            <tr>
                              <td valign="top" class="contenttab_row1">
                               <table align="center" border="0" cellpadding="0" width="30%">
                               <tr>
                                 <td class="contenttab_internal_rows"><nobr><b>Select Time Table: </b></nobr></td>
                                  <td class="padding">
                                      <select size="1" class="inputbox1" name="timeTableLabelId" id="timeTableLabelId" onChange="document.getElementById('resultTopic').style.display='none'; return false;">  
                                        <option value="">Select</option>
                                         <?php
                                           require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                           echo HtmlFunctions::getInstance()->getTimeTableLabelData();
                                         ?>
                                        </select>
                                   </td>
                                   <td align="center" colspan="4" >
                                               <span style="padding-right:10px" >
                                   <td valign="middle">
        <input type="image" name="teacherPrintSubmit" value="teacherPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="refreshTopicwise(<?php echo $employeeArr[0]['employeeId']?>,document.getElementById('timeTableLabelId').value);return false;" />
                                 </td>     
                               </tr>
                           </table>                                                      
                           <div id='resultTopic' style='display:none;'>
                           <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td class="" height="20">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                            <tr>
                                                <td colspan="1" class="content_title">Topicwise Details :</td>
                                                <td colspan="1" class="content_title" align="right">
                                                    <input type="image" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="topicwisePrintReport()" />&nbsp;
                                                    <input type="image" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="topicwisePrintReportCSV()"/>&nbsp;
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan='1' class='contenttab_row'>
                                        <div id="resultsDivTopic11" style="overflow:auto; HEIGHT:460px; vertical-align:top;">
                                           <div id="resultsDivTopic" style="HEIGHT:450px; vertical-align:top;"></div> 
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="" height="20">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                            <tr>
                                                <td colspan="2" class="content_title" align="right">
                                                <input type="image" name="imageField1"  src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="topicwisePrintReport()" />&nbsp;
                                                <input type="image" name="imageField2" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="topicwisePrintReportCSV()"/>&nbsp;
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            </td>
                           </tr>
                         </table>   
                         </div>
                        </td>
                    </tr>
                    </table>
              </div>
			<?php } ?>
            <!-- Course Topics  End -->
</form>              
            <!-- Seminar  Start -->                
                 <div class="dhtmlgoodies_aTab">        
                      <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                        <tr>
                            <td width="100%" valign="top">
                              <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
                                <tr>
                                  <td valign="top" class="contenttab_row1">
                                     <form name='searchBox22' onSubmit="refreshSeminarData(<?php echo $employeeArr[0]['employeeId']?>);return false;">
                                      <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                        <tr>
                                            <td valign="middle" align="right" height="40">    
                                             <input type="text" name="searchboxSeminar" id="searchboxSeminar" class="inputbox" value="<?php echo $REQUEST_DATA['searchboxSeminar'];?>" style="margin-bottom: 2px;" size="30" />
                                              &nbsp;
                                              <input type="image" name="submit" src="<?php echo IMG_HTTP_PATH;?>/search.gif" title="Search" style="margin-bottom: -5px;" onClick="refreshSeminarData(<?php echo $employeeArr[0]['employeeId']?>); return false;"/>&nbsp;
                                              </td>
                                        </tr>
                                        </table>
                                        </form>
                                       </td> 
                                       </tr>
                                     <tr>
                                     <td>  
                                   <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td class="" height="20">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                                    <tr>
                                                      <td class="content_title" width="30%" valign="middle">Seminar Detail : </td>
                                                      <td class="content_title" width="48%" title="Add" valign="middle" style="text-align:right;">
                                                         <img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('SeminarActionDiv',340,250);getSeminarEmployee(<?php echo parseOutput($employeeArr[0]['employeeId']);?>); return false;" />
                                                       </td>                                                      

                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan='1' class='contenttab_row'>
                                                <div id="SeminarResultDiv11" style="overflow:auto; HEIGHT:460px; vertical-align:top;">
                                                    <div id="SeminarResultDiv" style="HEIGHT:450px; vertical-align:top;"></div> 
                                                </div>
                                            </td>
                                        </tr>
                                    <tr>
                                        <td class="" height="20">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                                <tr>
                                                    <td colspan="2" class="content_title" align="right">
                                                        <input type="image" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="seminarPrintReport()" />&nbsp;
                                                        <input type="image" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="seminarPrintReportCSV()"/>&nbsp;
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
                    <!--Start Add/Edit Div-->
                    <?php floatingDiv_Start('SeminarActionDiv','',1); ?>
                    <form name="SeminarDetail" action="" method="post">  
                        <input type="hidden" name="seminarEmployeeId" id="seminarEmployeeId" value="" />
                        <input type="hidden" name="seminarId" id="seminarId" value="" />
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
                            <tr> 
                              <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Employee Name</strong></nobr></td>    
                              <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                              <td width="65%" class="padding" align="left"><b><div id="seminarEmployeeName"></div></b></td>
                             </tr>
                             <tr>
                              <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Employee Code</strong></nobr></td>
                              <td width="4%"  class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                              <td width="65%" class="padding" align="left"><b><div id="seminarEmployeeCode"></div></b></td>
                             </tr>
                            <tr> 
                             <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Organised By</strong><?php echo REQUIRED_FIELD;?></nobr></td>                          <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                              <td width="65%" class="padding" align="left">
                          <input type="text" id="seminarOrganisedBy" name="seminarOrganisedBy" style="width:200px" class="inputbox" maxlength="150" onkeydown="return sendKeys('seminarOrganisedBy',event,this.form);"/>
                             </td>
                            </tr>
                            <tr> 
                             <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Topic</strong><?php echo REQUIRED_FIELD;?></nobr></td>                          
                             <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                              <td width="65%" class="padding" align="left">
                          <input type="text" id="seminarTopic" name="seminarTopic" style="width:200px" class="inputbox" onkeyup="return ismaxlength(this)" maxlength="2000" onkeydown="return sendKeys('seminarTopic',event,this.form);"/>
                             </td>
                            </tr>
                            <tr>
                          <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Description<?php echo REQUIRED_FIELD ?></b></nobr></td>
                                <td width="4%" class="contenttab_internal_rows" style="text-align:right" valign="middle"><nobr><strong>:</strong></nobr></td>    
                                <td width="65%" class="padding" colspan="2" valign="middle"  align="left">
                                <textarea name="seminarDescription" id="seminarDescription" cols="23" rows="3" maxlength="2000" onkeyup="return ismaxlength(this)">
                                </textarea>
                                </td>
                            </tr>                                
                            <tr>
                                <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Start Date</b></nobr></td>
                                <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                                <td width="65%" class="padding"  align="left" colspan="2">
                                <?php
                                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                      echo HtmlFunctions::getInstance()->datePicker('startDate',date('Y-m-d'));
                                ?>
                              </td>
                            </tr>
                            <tr>
                                <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<b>End Date</b></nobr></td>
                                <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                                <td width="65%" class="padding"  align="left" colspan="2">
                                <?php
                                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                      echo HtmlFunctions::getInstance()->datePicker('endDate',date('Y-m-d'));
                                ?>
                              </td>
                            </tr>
                            <tr> 
                             <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Seminar Place</strong><?php echo REQUIRED_FIELD;?></nobr></td>                          
                             <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                              <td width="65%" class="padding" align="left">
                                <input type="text" id="seminarPlace" name="seminarPlace" style="width:200px" class="inputbox" maxlength="150" onkeydown="return sendKeys('seminarPlace',event,this.form);"/>
                             </td>
                            </tr>
                             <tr> 
                             <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Fee</strong></nobr></td>                          
                             <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                              <td width="65%" class="padding" align="left">
                                <input type="text" id="seminarFee" name="seminarFee" style="width:200px" class="inputbox" maxlength="5" onkeydown="return sendKeys('seminarFee',event,this.form);"/>
                             </td>
                            </tr>
                            <tr> 
                             <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Participation</strong><?php echo REQUIRED_FIELD;?></nobr></td>                          
                             <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                              <td width="65%" class="padding" align="left">
                                <select size="1" class="inputbox1" name="participationId" id="participationId" style="width:205px">  
                                    <option value="">Select</option>
                                     <?php
                                       require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                       echo HtmlFunctions::getInstance()->getEmployeeSeminarParticipationData();
                                     ?>
                                    </select>
                             </td>
                            </tr>
                          <tr>
                            <td height="5px"></td></tr>
                         <tr>
                            <td align="center" style="padding-right:10px" colspan="4">
                               <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateSeminarAddForm(this.form,'Add');return false;" />
                               <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('SeminarActionDiv');if(flag==true){refreshSeminarData(<?php echo $employeeArr[0]['employeeId']?>);flag=false;}return false;" />
                             </td>
                        </tr>
                        <tr>
                            <td height="5px"></td></tr>
                        <tr>
                        </table>
                    </form>
                    <?php floatingDiv_End(); ?>
                    <!--End Add Div-->
                    
                    <!--Seminar Details   Start-->
                        <?php floatingDiv_Start('divSeminarInfo','Brief Description','',''); ?>
                        <div id="seminarFullDiv" style="overflow:auto; WIDTH:490px; HEIGHT:410px; vertical-align:top;"> 
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
                                <tr>
                                    <td height="5px"></td></tr>
                                <tr>
                                <tr>    
                                    <td width="89%">
                                         <div id="seminarInfo" style="width:500px; vertical-align:top;" ></div>
                                      </div>   
                                    </td>
                                </tr>
                            </table>
                         </div>   
                        <?php floatingDiv_End(); ?> 
                    <!--Seminar Details    End -->                                                                    
                 </div>            
            <!-- Seminar  End -->    
            
            
            <!-- Publisher  Start -->                                                                     
                <div class="dhtmlgoodies_aTab">     
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                        <tr>
                            <td valign="top">
                              <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
                                <tr>
                                  <td valign="top" class="contenttab_row1">
                                      <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                        <form name='searchBox1' onSubmit="refreshBookJournalsData(<?php echo $employeeArr[0]['employeeId']?>); return false;">
                                        <tr>
                                            <td valign="middle" align="right" height="40px">
                                            <input type="text" name="searchboxPublishing" id="searchboxPublishing" class="inputbox" value="<?php echo $REQUEST_DATA['searchboxPublishing'];?>" style="margin-bottom: 2px;" size="30" />
                                              &nbsp;
                                              <input type="image" name="submit" src="<?php echo IMG_HTTP_PATH;?>/search.gif" title="Search" style="margin-bottom: -5px;" onClick="refreshBookJournalsData(<?php echo $employeeArr[0]['employeeId']?>); return false;"/>&nbsp;
                                              </td>
                                        </tr>
                                        </form>
                                        </table>
                                         </td> 
                                       </tr>
                                     <tr>
                                     <td> 
                               <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="" height="20">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                              <tr>
                                                  <td class="content_title" width="30%" valign="middle">Publishing Detail : </td>
                                                  <td class="content_title" width="48%" title="Add" valign="middle" style="text-align:right;">
                                                     <img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayWindow('PublishingActionDiv',320,100);getEmployee(<?php echo parseOutput($employeeArr[0]['employeeId']);?>); return false;" />
                                                   </td>                                                      
                                                   
                                              </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan='1' class='contenttab_row'>
                                           <div id="PublishingResultDiv11" style="overflow:auto; HEIGHT:460px; vertical-align:top;">
                                              <div id="PublishingResultDiv" style="HEIGHT:450px; vertical-align:top;"></div> 
                                           </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="" height="20">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                                <tr>
                                                    <td colspan="2" class="content_title" align="right">
                                                        <input type="image" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="publisherPrintReport()" />&nbsp;
                                                        <input type="image" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="publisherPrintReportCSV()"/>&nbsp;
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
                    <!--Start Add/Edit Div-->
                        <?php floatingDiv_Start('PublishingActionDiv','',2); ?>
                        <form name="PublishingDetail" id="PublishingDetail" method="post" enctype="multipart/form-data" style="display:inline" onSubmit="return false;" >  
                        <input type="hidden" name="employeeId" id="employeeId" value="" />
                        <input type="hidden" name="publishId" id="publishId" value="" />
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
                            <tr> 
                              <td width="16%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Employee Name</strong></nobr></td>    
                              <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                              <td width="65%" class="padding" align="left"><b><div id="employeeName"></div></b></td>
                              <td width="10%" >&nbsp;</td>
                             </tr>
                             <tr>
                              <td class="contenttab_internal_rows"><nobr>&nbsp;<strong>Employee Code</strong></nobr></td>
                              <td class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                              <td class="padding" align="left"><b><div id="employeeCode"></div></b></td>
                              <td>&nbsp;</td>
                             </tr>
                            <tr> 
                             <td class="contenttab_internal_rows"><nobr>&nbsp;<strong>Type</strong><?php echo REQUIRED_FIELD;?></nobr></td>                          
                             <td class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                              <td class="padding" align="left"><input maxlength="100" type="text" id="type" name="type" style="width:200px" class="inputbox" onkeydown="return sendKeys('type',event,this.form);" />
                              <td>&nbsp;</td>
                             </td>
                            </tr>
                            <tr> 
                              <td class="contenttab_internal_rows"><nobr>&nbsp;<strong>Scope</strong><?php echo REQUIRED_FIELD; ?></nobr></td>
                              <td class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                              <td class="padding" align="left">
                                <select size="1" class="inputbox1" name="scopeId" id="scopeId" style="width:205px">  
                                    <option value="">Select</option>
                                     <?php
                                       require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                       //echo HtmlFunctions::getInstance()->getEmployeePublisherScopeData();
									    echo HtmlFunctions::getInstance()->getPublicationData();
                                     ?>
                                    </select>
                             </td>
                             <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="contenttab_internal_rows"><nobr>&nbsp;<b>Publish On</b></nobr></td>
                                <td class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                                <td class="padding" align="left">
                                <?php
                                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                      echo HtmlFunctions::getInstance()->datePicker('publishOn',date('Y-m-d'));
                                ?>
                              </td>
                              <td>&nbsp;</td>
                            </tr>
                            <tr> 
                              <td class="contenttab_internal_rows"><nobr>&nbsp;<strong>Published By </strong><?php echo REQUIRED_FIELD; ?></nobr></td>
                              <td class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                              <td  class="padding" align="left">
                              <input type="text" id="publishedBy" name="publishedBy" style="width:200px" class="inputbox" maxlength="100" onkeydown="return sendKeys('publishedBy',event,this.form);"/>
                             </td>
                             <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="contenttab_internal_rows"><nobr>&nbsp;<b>Description<?php echo REQUIRED_FIELD ?></b></nobr></td>
                                <td class="contenttab_internal_rows" style="text-align:right" valign="middle"><nobr><strong>:</strong></nobr></td>    
                                <td class="padding" valign="middle"  align="left">
                                <textarea class="inputbox1" name="description" id="description" style="width:200px" cols="37" rows="4" maxlength="2000" onkeyup="return ismaxlength(this)">
                                </textarea>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr> 
                              <td class="contenttab_internal_rows"><nobr>&nbsp;<strong>Attachment</strong></nobr></td>
                              <td class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                              <td class="padding" align="left" valign="middle">
                                <input type="file" id="publisherAttachment" name="publisherAttachment" class="inputbox" />
                              </td>
                              <td class="padding" align="left" valign="middle">
                                <span id='attachmentLink' style='display:none;'></span>
                             </td>
                            </tr>
                            <tr> 
                              <td class="contenttab_internal_rows"><nobr>&nbsp;<strong>Accp. Let.</strong></nobr></td>
                              <td class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                              <td class="padding" align="left" valign="middle">
                                <input type="file" id="publisherAccpLet" name="publisherAccpLet" class="inputbox" />
                              </td>
                              <td class="padding" align="left" valign="middle">
                                <span id='accptLink' style='display:none;'></span>
                             </td>
                            </tr>
                          <tr>
                            <td height="5px"></td></tr>
                         <tr>
                            <td align="center" style="padding-right:10px" colspan="4">
                               <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
                               <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('PublishingActionDiv');if(flag==true){refreshBookJournalsData(<?php echo $employeeArr[0]['employeeId']?>);flag=false;}return false;" />
                            </td>
                        </tr>
                        <tr>
                            <td height="5px">
                               <iframe id='fileUpload' name='fileUpload' style="width:0px;height:0px;border:0px solid #fff;"></iframe>
                               <!-- <iframe id="uploadTarget" name="uploadTarget" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe> -->
                            </td>
                        </tr>
                        </table>
                        </form>
                     <?php floatingDiv_End(); ?>
                 </div>            
                
                <!--Publsiher Details  Div-->
                <?php floatingDiv_Start('divPublisherInfo','Brief Description','',''); ?>
                <div id="publisherResultDiv" style="overflow:auto; WIDTH:490px; HEIGHT:410px; vertical-align:top;"> 
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
                        <tr>
                            <td height="5px"></td></tr>
                        <tr>
                        <tr>    
                            <td width="89%">
                                 <div id="publisherInfo" style="width:500px; vertical-align:top;" ></div>
                              </div>   
                            </td>
                        </tr>
                    </table>
                 </div>   
                <?php floatingDiv_End(); ?> 
            <!-- Publisher      End -->                                                                    
           
           <!-- Consulting  Start--> 
                <div class="dhtmlgoodies_aTab">     
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                        <tr>
                            <td valign="top">
                              <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
                                <tr>
                                  <td valign="top" class="contenttab_row1">
                                      <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                       <form name='searchBox4' onSubmit="refreshConsultingData(<?php echo $employeeArr[0]['employeeId']?>); return false;">
                                        <tr>
                                            <td valign="middle" align="right" height="40">
                                            <input type="text" name="searchboxConsulting" id="searchboxConsulting" class="inputbox" value="<?php echo $REQUEST_DATA['searchboxConsulting'];?>" style="margin-bottom: 2px;" size="30" />
                                              &nbsp;
                                              <input type="image" name="submit" src="<?php echo IMG_HTTP_PATH;?>/search.gif" title="Search" style="margin-bottom: -5px;" onClick="refreshConsultingData(<?php echo $employeeArr[0]['employeeId']?>); return false;"/>&nbsp;
                                              </td>
                                        </tr>
                                        </form>
                                        </table>
                                         </td> 
                                       </tr>
                                     <tr>
                                     <td> 
                               <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="" height="20">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                                <tr>
                                                  <td class="content_title" width="30%" valign="middle">Consulting Detail : </td>
                                                  <td class="content_title" width="48%" title="Add" valign="middle" style="text-align:right;">
                                                     <img src="<?php echo IMG_HTTP_PATH;?>/add.gif" align="right" onClick="displayWindow('ConsultingActionDiv',340,250);getConsultingEmployee(<?php echo parseOutput($employeeArr[0]['employeeId']);?>); return false;" />
                                                   </td>                                                      
                                                   
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan='1' class='contenttab_row' width="100%">
                                          <div id="ConsultingResultDiv11" style="overflow:auto; HEIGHT:460px; vertical-align:top;">
                                            <div id="ConsultingResultDiv" style="HEIGHT:450px; vertical-align:top;"></div> 
                                          </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="" height="20">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                                <tr>
                                                    <td colspan="2" class="content_title" align="right">
                                                       <input type="image" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="consultingPrintReport()" />&nbsp;
                                                       <input type="image" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="consultingPrintReportCSV()"/>&nbsp;
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
                   <!--Start Add/Edit Div-->
                            <?php floatingDiv_Start('ConsultingActionDiv','',3); ?>
                            <form name="ConsultingDetail" action="" method="post">  
                                <input type="hidden" name="consultingEmployeeId" id="consultingEmployeeId" value="" />
                                <input type="hidden" name="consultId" id="consultId" value="" />                           
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
                                <tr> 
                                  <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Employee Name</strong></nobr></td>    
                                  <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                                  <td width="65%" class="padding" align="left"><b><div id="consultingEmployeeName"></div></b></td>
                                 </tr>
                                 <tr>
                                  <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Employee Code</strong></nobr></td>
                                  <td width="4%"  class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                                  <td width="65%" class="padding" align="left"><b><div id="consultingEmployeeCode"></div></b></td>
                                 </tr>
                                <tr> 
                                 <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Project Name</strong><?php echo REQUIRED_FIELD;?></nobr></td>                          
                                 <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                                  <td width="65%" class="padding" align="left">
                              <input type="text" id="consultingProjectName" name="consultingProjectName" style="width:200px" class="inputbox" maxlength="150" onkeydown="return sendKeys('consultingProjectName',event,this.form);"/>
                                 </td>
                                </tr>
                                <tr> 
                                 <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Sponsor</strong><?php echo REQUIRED_FIELD;?></nobr></td>                          
                                 <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                                  <td width="65%" class="padding" align="left">
                                    <input type="text" id="consultingSponsor" name="consultingSponsor" style="width:200px" class="inputbox" maxlength="50" onkeydown="return sendKeys('consultingSponsor',event,this.form);"/>
                                 </td>
                                </tr>
                                <tr>
                                    <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Start Date</b></nobr></td>
                                    <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                                    <td width="65%" class="padding"  align="left" colspan="2">
                                    <?php
                                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                          echo HtmlFunctions::getInstance()->datePicker('cStartDate',date('Y-m-d'));
                                    ?>
                                  </td>
                                </tr>
                                <tr>
                                    <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<b>End Date</b></nobr></td>
                                    <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                                    <td width="65%" class="padding"  align="left" colspan="2">
                                    <?php
                                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                          echo HtmlFunctions::getInstance()->datePicker('cEndDate',date('Y-m-d'));
                                    ?>
                                  </td>
                                </tr>
                                <tr> 
                                 <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Amount Funding</strong></nobr></td>                          
                                 <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                                  <td width="65%" class="padding" align="left">
                                  <input type="text" id="consultingAmountFunding" name="consultingAmountFunding" style="width:200px" class="inputbox" maxlength="6" onkeydown="return sendKeys('consultingAmountFunding',event,this.form);"/>
                                 </td>
                                </tr>
                                <tr> 
                                 <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Remarks</strong><?php echo REQUIRED_FIELD;?></nobr></td>                          
                                 <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                                  <td width="65%" class="padding" align="left">
                                    <textarea name="consultingRemarks" id="consultingRemarks" cols="23" rows="3" maxlength="1000" onkeyup="return ismaxlength(this)">
                                    </textarea>
                                 </td>
                                </tr>
                              <tr>
                                <td height="5px"></td></tr>
                             <tr>
                                <td align="center" style="padding-right:10px" colspan="4">
                                   <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateConsultingAddForm(this.form,'Add');return false;" />
                                   <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('ConsultingActionDiv');if(flag==true){refreshConsultingData(<?php echo $employeeArr[0]['employeeId']?>);flag=false;}return false;" />
                                 </td>
                            </tr>
                            <tr>
                                <td height="5px"></td></tr>
                            <tr>
                            </table>
                            </form>
                            <?php floatingDiv_End(); ?>
                            <!--End Add Div-->
                
                            <!--Consulting Details  Div-->
                            <?php floatingDiv_Start('divConsultingInfo','Brief Description','',''); ?>
                            <div id="consultingFullResulttDiv" style="overflow:auto; WIDTH:490px; HEIGHT:410px; vertical-align:top;"> 
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
                                    <tr>
                                        <td height="5px"></td></tr>
                                    <tr>
                                    <tr>    
                                        <td width="89%">
                                             <div id="consultingInfo" style="overflow:auto; width:500px; vertical-align:top;" ></div>
                                          </div>   
                                        </td>
                                    </tr>
                                </table>
                             </div>   
                            <?php floatingDiv_End(); ?> 
                        <!-- Consulting  Div    End -->
                 </div>                   
           <!-- Consulting  End--> 
           
           <!-- Workshop  Start--> 
                <div class="dhtmlgoodies_aTab">     
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                        <tr>
                            <td valign="top">
                              <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
                                <tr>
                                  <td valign="top" class="contenttab_row1">
                                      <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                       <form name='searchbox8' onSubmit="refreshWorkshopData(<?php echo $employeeArr[0]['employeeId']?>); return false;">
                                        <tr>
                                            <td valign="middle" align="right" height="40">
                                            <input type="text" name="searchboxWorkShop" id="searchboxWorkShop" class="inputbox" value="<?php echo $REQUEST_DATA['searchboxWorkShop'];?>" style="margin-bottom: 2px;" size="30" />
                                              &nbsp;
                                              <input type="image" name="submit" src="<?php echo IMG_HTTP_PATH;?>/search.gif" title="Search" style="margin-bottom: -5px;" onClick="refreshWorkshopData(<?php echo $employeeArr[0]['employeeId']?>); return false;"/>&nbsp;
                                              </td>
                                        </tr>
                                        </form>
                                        </table>
                                         </td> 
                                       </tr>
                                     <tr>
                                     <td> 
                               <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="" height="20">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                                <tr>
                                                  <td class="content_title" width="30%" valign="middle">Workshop Details : </td>
                                                  <td class="content_title" width="48%" title="Add" valign="middle" style="text-align:right;">
                                                     <img src="<?php echo IMG_HTTP_PATH;?>/add.gif" align="right" onClick="displayWindow('WorkShopActionDiv',440,350);getWorkshopEmployee(<?php echo parseOutput($employeeArr[0]['employeeId']);?>); return false;" />
                                                   </td>                                                      
                                                  
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan='1' class='contenttab_row' width="100%">
                                          <div id="WorkShopResultDiv11" style="overflow:auto; HEIGHT:460px; vertical-align:top;">
                                            <div id="WorkShopResultDiv" style="HEIGHT:450px; vertical-align:top;"></div> 
                                          </div>                
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="" height="20">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                                <tr>
                                                    <td colspan="2" class="content_title" align="right">
                                                       <input type="image" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="workshopPrintReport()" />&nbsp;
                                                       <input type="image" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="workshopPrintReportCSV()"/>&nbsp;
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
                   <!--Start Add/Edit Div-->
                            <?php floatingDiv_Start('WorkShopActionDiv','',4); ?>
                            <form name="WorkshopDetail" action="" method="post">  
                                <input type="hidden" name="workshopEmployeeId" id="workshopEmployeeId" value="" />
                                <input type="hidden" name="workshopId" id="workshopId" value="" />                           
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
                                <tr> 
                                  <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Employee Name</strong></nobr></td>    
                                  <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                                  <td width="65%" class="padding" align="left"><b><div id="workshopEmployeeName"></div></b></td>
                                 </tr>
                                 <tr>
                                  <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Employee Code</strong></nobr></td>
                                  <td width="4%"  class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                                  <td width="65%" class="padding" align="left"><b><div id="workshopEmployeeCode"></div></b></td>
                                 </tr>
                                 <tr> 
                                 <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Topic</strong><?php echo REQUIRED_FIELD;?></nobr></td>                          
                                 <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                                  <td width="65%" class="padding" align="left">
                                    <textarea name="workshopTopic" id="workshopTopic" cols="35" rows="3" maxlength="1000" onkeyup="return ismaxlength(this)">
                                    </textarea>
                                 </td>
                                </tr>
                                <tr>
                                    <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Start Date</b></nobr></td>
                                    <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                                    <td width="65%" class="padding"  align="left" colspan="2">
                                    <?php
                                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                          echo HtmlFunctions::getInstance()->datePicker('workshopStartDate',date('Y-m-d'));
                                    ?>
                                  </td>
                                </tr>
                                <tr>
                                    <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<b>End Date</b></nobr></td>
                                    <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                                    <td width="65%" class="padding"  align="left" colspan="2">
                                    <?php
                                          require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                          echo HtmlFunctions::getInstance()->datePicker('workshopEndDate',date('Y-m-d'));
                                    ?>
                                  </td>
                                </tr>
                                <tr> 
                                 <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Sponsored<?php echo REQUIRED_FIELD ?></strong></nobr></td>                          
                                 <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                                  <td width="65%" class="padding" align="left">
                                      <select size="1" class="inputbox1" name="workshopSponsored" id="workshopSponsored" onChange="if(this.value=='Y') { document.getElementById('workshopSponsoredDetail').value=''; document.getElementById('divWorkShopSponsored').style.display= '';} else {document.getElementById('divWorkShopSponsored').style.display= 'none';}return false;">  
                                          <option value="">Select</option>
                                          <option value="Y">Yes</option>
                                          <option value="N">No</option>
                                      </select>
                                 </td>
                                </tr>
                                <tr id='divWorkShopSponsored' style='display:none;'> 
                                 <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Sponsored Detail</strong><?php echo REQUIRED_FIELD;?></nobr></td>
                                 <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>
                                  <td width="65%" class="padding" align="left">
                                    <textarea name="workshopSponsoredDetail" id="workshopSponsoredDetail" cols="35" rows="3" maxlength="1000" onkeyup="return ismaxlength(this)">
                                    </textarea>
                                 </td>
                                </tr>
                                <tr> 
                                 <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Location<?php echo REQUIRED_FIELD;?></strong></nobr></td>                          
                                 <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                                  <td width="65%" class="padding" align="left">
                                    <input type="text" id="workshopLocation" name="workshopLocation" style="width:297px" class="inputbox" maxlength="100" onkeydown="return sendKeys('workshopLocation',event,this.form,this.form);"/>  
                                 </td>
                                </tr>
                                <tr> 
                                 <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Other Speakers<?php echo REQUIRED_FIELD;?></strong></nobr></td>                          
                                 <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                                  <td width="65%" class="padding" align="left">
                                    <input type="text" id="workshopOtherSpeakers" name="workshopOtherSpeakers" style="width:297px" class="inputbox" maxlength="250" onkeydown="return sendKeys('workshopOtherSpeakers',event,this.form);"/>  
                                 </td>
                                </tr>
                                <tr> 
                                 <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Audience<?php echo REQUIRED_FIELD;?></strong></nobr></td>                          
                                 <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                                  <td width="65%" class="padding" align="left">
                                    <input type="text" id="workshopAudience" name="workshopAudience" style="width:297px" class="inputbox" maxlength="250" onkeydown="return sendKeys('workshopAudience',event,this.form);"/>  
                                 </td>
                                </tr>
                                <tr> 
                                 <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>Attendees<?php echo REQUIRED_FIELD;?></strong></nobr></td>                          
                                 <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                                  <td width="65%" class="padding" align="left">
                                    <input type="text" id="workshopAttendees" name="workshopAttendees" style="width:297px" class="inputbox" maxlength="4" onkeydown="return sendKeys('workshopAttendees',event,this.form);"/>  
                                 </td>
                                </tr>
                                
                              <tr>
                                <td height="5px"></td>
                              </tr>
                              <tr>
                                <td align="center" style="padding-right:10px" colspan="4">
                                   <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateWorkshopAddForm(this.form,'Add');return false;" />
                                   <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('WorkShopActionDiv');if(flag==true){refreshWorkshopData(<?php echo $employeeArr[0]['employeeId']?>);flag=false;}return false;" />
                                 </td>
                            </tr>
                            <tr>
                                <td height="5px"></td></tr>
                            <tr>
                            </table>
                            </form>
                            <?php floatingDiv_End(); ?>
                            <!--End Add Div-->
                            
                             <!--Workshop Details  Div-->
                            <?php floatingDiv_Start('divWorkshopInfo','Brief Description','',''); ?>
                            <div id="workshopFullResulttDiv" style="overflow:auto; WIDTH:490px; height:410px; vertical-align:top;"> 
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
                                    <tr>
                                        <td height="5px"></td></tr>
                                    <tr>
                                    <tr>    
                                        <td width="89%">
                                             <div id="workshopInfo" style="width:500px; vertical-align:top;" ></div>
                                        </td>
                                    </tr>
                                </table>
                             </div>   
                            <?php floatingDiv_End(); ?> 
                            <!--Workshop  Div    End -->
                 </div>               
           <!-- Workshop  End--> 
<!--This tab shows Qualification of an Employee -->
		    <div class="dhtmlgoodies_aTab" style="overflow:auto">
			  <form action="" method="POST" name = "qualificationFrm" onsubmit="return false;">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<div id="results" style="display:none;overflow:auto;">
					 <tr>
						<td valign="top" >   
						   <!-- <div class="searchhead_text" align="left">Add Rows:&nbsp;&nbsp;
								<a href="javascript:addOneRow(1);" title="Add One Row"><b>+</b></a>
							</div>   -->                           
								 <table class="padding" width="100%" border="0"  id="anyid_add">
									<tbody id="anyidBody_add">
									  <tr class="rowheading">
										<td class="searchhead_text" width="6%"  align="left"><nobr><b>Sr. No.</b></nobr></td>
										<td class="searchhead_text" width="20%" align="left"><nobr><b>UG Degree</b></nobr></td>
										<td class="searchhead_text" width="20%" align="left"><nobr><b>PG Degree</b></nobr></td>
										<td class="searchhead_text" width="20%" align="left"><nobr><b>Highest Qualification</b></nobr></td>
										<td class="searchhead_text" width="20%" align="left"><nobr><b>Other Qualification</b></nobr></td>
										<td class="searchhead_text" width="10%" align="center"><nobr><b>Action</b></nobr></td>
									  </tr>
									</tbody>
								 </table>               
							<div class="searchhead_text" align="left">Add Rows:&nbsp;&nbsp;
								<a href="javascript:addOneRow(1,'add');" title="Add One Row"><b>+</b></a>
							</div>   
						 </td>
					   </tr>
					   <tr>
							<!-- <td height="5"><input type="hidden" name="tempValue" id="tempValue" /></td> -->
					   </tr>
					   <tr id="trQualification" style="display:none">
						  <td  align="right" style="padding-right:5px" colspan="9">
						<!--<input type="hidden" name="listSubject" value="1"> -->
				<input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="return validateAddQualificationForm(this.form);return false;" />
				 &nbsp;&nbsp;<!--a href="#" onClick="printTimeTableReport()"><img src="<?php echo IMG_HTTP_PATH ?>/print.gif" border="0"></a-->&nbsp;
						</td>
					 </tr>
				 </div>                  
			   </table>
			  </form>
			</div>
<!--End tab -->

<!--This tab shows Experience of an Employee -->
			<div class="dhtmlgoodies_aTab" style="overflow:auto">
			  <form action="" method="POST" name = "experienceFrm" onsubmit="return false;">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					  <tr>
							<td width="65%" class="padding"  align="left" >
							<select size="1" name="experienceData" id="experienceData" style="display:none" class="selectfield">
							<?php
								  require_once(BL_PATH.'/HtmlFunctions.inc.php');
								  echo HtmlFunctions::getInstance()->getExperienceData();
							?>
						  </td>
					  </tr>

					  <tr>
							<td width="65%" class="padding"  align="left" >
							<select size="1" name="experienceCertificate" id="experienceCertificate" style="display:none" class="selectfield">
							<?php
								  require_once(BL_PATH.'/HtmlFunctions.inc.php');
								  echo HtmlFunctions::getInstance()->getExperienceCertificateData();
							?>
						  </td>
					  </tr>
					
					 <tr>
						<td valign="top" >   
						<div id="experienceResults" style="display:none;overflow:auto;">
						   <!-- <div class="searchhead_text" align="left">Add Rows:&nbsp;&nbsp;
								<a href="javascript:addOneRow(1);" title="Add One Row"><b>+</b></a>
							</div>   -->                           
								 <table class="padding" width="100%" border="0"  id="anyid1_add">
									<tbody id="anyidBody1_add">
									  <tr class="rowheading">
										<td class="searchhead_text" width="6%"  align="left"><nobr><b>Sr. No.</b></nobr></td>
										<td class="searchhead_text" width="15%" align="left"><nobr><b>From</b></nobr></td>
										<td class="searchhead_text" width="15%" align="left"><nobr><b>To</b></nobr></td>
										<td class="searchhead_text" width="15%" align="left"><nobr><b>Organization</b></nobr></td>
										<td class="searchhead_text" width="15%" align="left"><nobr><b>Designation</b></nobr></td>
										<td class="searchhead_text" width="12%" align="left"><nobr><b>Experience</b></nobr></td>
										<td class="searchhead_text" width="15%" align="left"><nobr><b>Exp. Certificate Available</b></nobr></td>
										<td class="searchhead_text" width="7%" align="center"><nobr><b>Action</b></nobr></td>
									  </tr>
									</tbody>
								 </table>               
								<div class="searchhead_text" align="left">Add Rows:&nbsp;&nbsp;
									<a href="javascript:addExperienceOneRow(1,'add');" title="Add One Row"><b>+</b></a>
								</div> 
							</div>
						 </td>
					   </tr>
					    
					   <tr id="trExperience" style="display:none">
						  <td  align="right" style="padding-right:5px" colspan="9">
						<!--<input type="hidden" name="listSubject" value="1"> -->
				<input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="return validateAddExperienceForm(this.form);return false;" />
						</td>
					 </tr>
				         
			   </table>
			  </form>
			</div>
			
			<!--This tab shows Financial Details of an Employee :: added by abhiraj for payroll-->
                <div class="dhtmlgoodies_aTab">     
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                        <tr>
                            <td valign="top">
                              <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
                                     <tr>
                                     <td> 
                               <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="" height="20">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                                <tr>
                                                  <td class="content_title" width="30%" valign="middle">Financial Info : </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan='1' class='contenttab_row' width="100%">
                                          <table width="100%" border="0px" cellspacing="5px" cellpadding="0" >
                        					<tr>
                            				<td colspan="12" height="5"></td>
                        					</tr>
												<tr>
													<td width="75" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;ESI Number</b></nobr></td>
													<td width="5"  class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
													<td width="240" class="contenttab_internal_rows"><span id="esiNumber">---</span></td>
													<td width="40" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;PAN </b></nobr></td>
													<td width="5"  class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
													<td width="250"  class="contenttab_internal_rows"><span id="pan">---</span>
													
													<td width="90" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;PF A/C Number </b></nobr></td>
													<td width="5"  class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
													<td width="250"  class="contenttab_internal_rows"><span id="pfNumber">---</span>
													</td>
												</tr>
												<tr>
													<td width="75" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Bank Name</b></nobr></td>
													<td width="5"  class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
													<td width="240" class="contenttab_internal_rows"><span id="bankName">---</span></td>
													<td width="40" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Account No.</b></nobr></td>
													<td width="5"  class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
													<td width="250"  class="contenttab_internal_rows"><span id="accountNo">---</span>
													
													<td width="90" class="contenttab_internal_rows"><nobr><b>&nbsp;&nbsp;&nbsp;Branch Name</b></nobr></td>
													<td width="5"  class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
													<td width="250"  class="contenttab_internal_rows"><span id="branchName">---</span>
													</td>
												</tr>

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
                   
                        <!-- Consulting  Div    End -->
                 </div>                   
           <!-- Consulting  End--> 



  <!-- Mdp Start-->        
                 <div class="dhtmlgoodies_aTab">       
                      <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                        <tr>
                            <td width="100%" valign="top">
                              <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
                                <tr>
                                  <td valign="top" class="contenttab_row1">
                                     <form name='searchBox22' onSubmit="refreshMdpData(<?php echo $employeeArr[0]['employeeId']?>);return false;">
                                      <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                        <tr>
                                            <td valign="middle" align="right" height="40">    
                                             <input type="text" name="searchboxMdp" id="searchboxMdp" class="inputbox" value="<?php echo $REQUEST_DATA['searchboxMdp'];?>" style="margin-bottom: 2px;" size="30" />
                                              &nbsp;
                                              <input type="image" name="submit" src="<?php echo IMG_HTTP_PATH;?>/search.gif" title="Search" style="margin-bottom: -5px;" onClick="refreshMdpData(<?php echo $employeeArr[0]['employeeId']?>); return false;"/>&nbsp;
                                              </td>
                                        </tr>
                                        </table>
                                        </form>
                                       </td> 
                                       </tr>
                                     <tr>
                                     <td>  
                                   <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td class="" height="20">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                                    <tr>
                                                      <td class="content_title" width="30%" valign="middle">MDP Detail : </td>
                                                      <td class="content_title" width="48%" title="Add" valign="middle" style="text-align:right;">
                                                         <img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="mdpBlankValues();displayWindow('MdpActionDiv',340,250); return false;" />
                                                       </td>                                                      
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan='1' class='contenttab_row'>
                                                <div id="MdpResultDiv11" style="overflow:auto; HEIGHT:460px; vertical-align:top;">
                                                    <div id="MdpResultDiv" style="HEIGHT:450px; vertical-align:top;"></div> 
                                                </div>
                                            </td>
                                        </tr>
                                    <tr>
                                        <td class="" height="20">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="">
                                                <tr>
                                                    <td colspan="2" class="content_title" align="right">
                                                        <input type="image" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onClick="mdpPrintReport()" />&nbsp;
                                                        <input type="image" src="<?php echo IMG_HTTP_PATH; ?>/excel.gif"onClick="mdpPrintReportCSV()"/>&nbsp;
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
                    <!--Start Add/Edit Div-->
		           <?php floatingDiv_Start('MdpActionDiv','',5); ?>
			
                  
                    <form name="mdpDetail" action="" method="post">  
                     <input type="hidden" name="mdpEmployeeId" id="mdpEmployeeId" value="" />
                        <input type="hidden" name="mdpId" id="mdpId" value="" />
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
                     <tr>
                              <td width="125%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>MDP Name</strong></nobr></td>    
                              <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                              <td width="65%" class="padding" align="left"><b><div id="mdpName">
							    <input type="text" id="mdpName" name="mdpName" style="width:200px" class="inputbox" maxlength="50" /></div></b></td>
                             </tr>

                        
                            <tr>
                                <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Start Date</b></nobr></td>
                                <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                                <td width="65%" class="padding"  align="left" colspan="2">
                                <?php
                                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                      echo HtmlFunctions::getInstance()->datePicker('mdpstartDate',date('Y-m-d'));
                                ?>
                              </td>
                            </tr>
                            <tr>
                                <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<b>End Date</b></nobr></td>
                                <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                                <td width="65%" class="padding"  align="left" colspan="2">
                                <?php
                                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                      echo HtmlFunctions::getInstance()->datePicker('mdpendDate',date('Y-m-d'));
                                ?>
                              </td>
                            </tr>
                           <tr> 
                             <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>MDP</strong><?php echo REQUIRED_FIELD;?></nobr></td>                          
                             <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                              <td width="65%" class="padding" align="left">
                                <select size="1" class="inputbox1" name="mdpSelectId" id="mdpSelectId" style="width:205px" onchange="getMDPValue(document.getElementById('mdpSelectId').value); return false;" >  
                                    <option value>Select</option>
									<option value="0">Attended</option>
                                    <option value="1">Conducted</option>
                                     <?php
                                       //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                       //echo HtmlFunctions::getInstance()->getEmployeeMdpData();
                                     ?>
                                    </select>
                             </td>
                            </tr>
                             <tr> 
                             <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>No. of Sessions </label></strong></nobr></td>                          
                             <td width="4%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                              <td width="65%" class="padding" align="left">
                                <input type="text" id="mdpSessionAttended" name="mdpSessionAttended" style="width:200px" class="inputbox" maxlength="50"
								onkeydown="return sendKeys('mdpSessionAttended',event,this.form);"/>
                             </td>
							 </tr>
							 <tr>
                             
                             <td width="0%" class="contenttab_internal_rows"><nobr>&nbsp;<strong>No. Of Hours</strong></nobr></td>                          
                             <td width="0%" class="contenttab_internal_rows" style="text-align:right"><nobr><strong>:</strong></nobr></td>    
                             <td width="65%" class="padding" align="left">
                                <input type="text" id="mdpHours" name="mdpHours" style="width:200px" class="inputbox" maxlength="50"onkeydown="return sendKeys('mdpHours',event,this.form);"/>
                             </td>
                            </tr>
							  <tr>
                          <td width="15%" class="contenttab_internal_rows"><nobr>&nbsp;<b>Venue<?php echo REQUIRED_FIELD ?></b></nobr></td>
                                <td width="4%" class="contenttab_internal_rows" style="text-align:right"valign="middle"><nobr><strong>:</strong></nobr></td>    
                                <td width="65%" class="padding" colspan="2" valign="middle"  align="left">
                                <textarea name="mdpVenue" id="mdpVenue"  cols="23" rows="3" maxlength="2000" onkeyup="return ismaxlength(this)"></textarea>
                                </td>
                            </tr>        
						<tr>
						
						<td width = "15%" class="contenttab_internal_rows"><nobr>&nbsp;<b>MDP Type:<?php echo REQUIRED_FIELD ?></b></nobr></td>
                                <td width="4%" class="contenttab_internal_rows" style="text-align:right"valign="middle"><nobr><b>:</b></nobr></td>  
								  
                                <td width="20%" class="padding" colspan="0" valign="left"  align="left">
								<input type="checkbox" id="mdpType" name="mdpType[]" value="1" size= class="inputbox"  />ICTP&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="checkbox" id="mdpType" name="mdpType[]" value="2" size= class="inputbox"  />EDP&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="checkbox" id="mdpType" name="mdpType[]" value="3" size= class="inputbox"/>FDP
								<br/><br/>
                                   <input type="checkbox" id="mdpType" name="mdpType[]" value="4" size= class="inputbox"/>Seminar 
                                  <input type="checkbox" id="mdpType" name="mdpType[]" value="5" size= class="inputbox"  />Workshop
								  <input type="checkbox" id="mdpType" name="mdpType[]"  value="6" size= class="inputbox" />PDP
								  </td>
								  </td>	  
					           </tr>
                              </tr>
							    <tr>
                                <td class="contenttab_internal_rows"><nobr>&nbsp;<b>Description<?php echo REQUIRED_FIELD ?></b></nobr></td>
                                <td class="contenttab_internal_rows" style="text-align:right" valign="middle"><nobr><strong>:</strong></nobr></td>    
                                <td class="padding" valign="middle"  align="left">
                                <textarea class="inputbox1" name="mdpDescription" id="mdpDescription" style="width:200px" cols="37" rows="4" maxlength="2000" onkeyup="return ismaxlength(this)"></textarea>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                          <tr>
                            <td height="5px"></td></tr>
                         <tr>
                            <td align="center" style="padding-right:10px" colspan="4">
                               <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateMdpAddForm(this.form,'Add');return false;" />
                               <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('MdpActionDiv');if(flag==true){refreshMdpData(<?php echo $employeeArr[0]['employeeId']?>);flag=false;}return false;" />
                             </td>
                        </tr>
                        <tr>
                            <td height="5px"></td></tr>
                        <tr>
                        </table>
                    </form>
                    <?php floatingDiv_End(); ?>
</div>
<!-- Details   Start-->
                        <?php floatingDiv_Start('divMdpInfo','Brief Description','',''); ?>
                        <div id="mdpFullDiv" style="overflow:auto; WIDTH:490px; HEIGHT:410px; vertical-align:top;"> 
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
                                <tr>
                                    <td height="5px"></td></tr>
                                <tr>
                                <tr>    
                                    <td width="89%">
                                         <div id="mdpInfo" style="width:500px; vertical-align:top;" ></div>
                                      </div>   
                                    </td>
                                </tr>
                            </table>
                         </div>   
                        <?php floatingDiv_End(); ?> 
                    <!--Mdp Details    End -->                                                                    
                 </div>            
            <!-- Mdp End -->    

<?php if($employeeArr[0]['isTeaching']=='Yes') { 	?>
	<script type="text/javascript">
	   initTabs('dhtmlgoodies_tabView1',Array('Personal Info','Lecture Details','Topicwise','Seminar','Publishing','Consulting','Workshop','Qualification','Experience', 'Financial Info','Mdp'),0,985,576,Array(false,false,false,false,false,false,false,false,false,false,false,false));
	</script>        
<?php
}
else {
?>
	<script type="text/javascript">
	   initTabs('dhtmlgoodies_tabView1',Array('Personal Info','Seminar','Publishing','Consulting','Workshop','Qualification','Experience', 'Financial Info','Mdp'),0,985,576,Array(false,false,false,false,false,false,false,false,false,false));
	</script>        
<?php } ?>
             </td>
          </tr>
             <tr>
                  <td height="5"></td>
             </tr>
              <tr>
                <td  align="center" style="padding-right:5px">
                    <input type="hidden" name="userId" value="<?php echo $employeeArr[0]['userId']?>">
                    <input type="hidden" name="employeeId1" id="employeeId1" value="<?php echo $employeeArr[0]['employeeId']?>">
                    <input type="hidden" name="employeeName1" id="employeeName1" value="<?php echo $employeeArr[0]['employeeName']?>">
                    <input type="hidden" name="employeeCode1" id="employeeCode1" value="<?php echo $employeeArr[0]['employeeCode']?>">
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
// $History: listEmployeeInfoContents.php $
//
//*****************  Version 36  *****************
//User: Jaineesh     Date: 4/20/10    Time: 5:55p
//Updated in $/LeapCC/Templates/Employee
//fixed bug nos. 0003312, 0003311, 0003298, 0003299
//
//*****************  Version 35  *****************
//User: Jaineesh     Date: 4/08/10    Time: 5:31p
//Updated in $/LeapCC/Templates/Employee
//fixed issue no. 3243
//
//*****************  Version 34  *****************
//User: Jaineesh     Date: 3/31/10    Time: 7:21p
//Updated in $/LeapCC/Templates/Employee
//fixed bug nos. 0003176, 0003164, 0003165, 0003166, 0003167, 0003168,
//0003169, 0003170, 0003171, 0003172, 0003173, 0003175
//
//*****************  Version 33  *****************
//User: Jaineesh     Date: 3/29/10    Time: 5:18p
//Updated in $/LeapCC/Templates/Employee
//modification in files according to add new fileds, show exp. &
//qualification
//
//*****************  Version 32  *****************
//User: Jaineesh     Date: 3/29/10    Time: 3:29p
//Updated in $/LeapCC/Templates/Employee
//changes for gap analysis in employee master
//
//*****************  Version 31  *****************
//User: Parveen      Date: 10/24/09   Time: 10:57a
//Updated in $/LeapCC/Templates/Employee
//1862 issue fix (timeTableLabelId Active check added)
//
//*****************  Version 30  *****************
//User: Parveen      Date: 10/23/09   Time: 5:47p
//Updated in $/LeapCC/Templates/Employee
//report format update lecture report (groupTypeId base checks added)
//
//*****************  Version 29  *****************
//User: Parveen      Date: 10/23/09   Time: 3:35p
//Updated in $/LeapCC/Templates/Employee
//lectureSubjectType dropdown box added 
//
//*****************  Version 28  *****************
//User: Parveen      Date: 10/22/09   Time: 4:34p
//Updated in $/LeapCC/Templates/Employee
//formatting updated (bug no. 1846)
//
//*****************  Version 27  *****************
//User: Parveen      Date: 10/20/09   Time: 5:45p
//Updated in $/LeapCC/Templates/Employee
//new filed added (bug no. 1668)
//
//*****************  Version 26  *****************
//User: Parveen      Date: 10/03/09   Time: 4:10p
//Updated in $/LeapCC/Templates/Employee
//look & fill updatd
//
//*****************  Version 25  *****************
//User: Parveen      Date: 10/01/09   Time: 10:57a
//Updated in $/LeapCC/Templates/Employee
//look & feel updated
//
//*****************  Version 24  *****************
//User: Parveen      Date: 9/25/09    Time: 5:25p
//Updated in $/LeapCC/Templates/Employee
//alignment & format updated
//
//*****************  Version 23  *****************
//User: Parveen      Date: 9/16/09    Time: 5:53p
//Updated in $/LeapCC/Templates/Employee
//search & conditions updated
//
//*****************  Version 22  *****************
//User: Gurkeerat    Date: 9/11/09    Time: 6:56p
//Updated in $/LeapCC/Templates/Employee
//resolved issue 1520
//
//*****************  Version 21  *****************
//User: Parveen      Date: 9/11/09    Time: 3:55p
//Updated in $/LeapCC/Templates/Employee
//issue fix 1519, 1518, 1517, 1473, 1442, 1451 
//validiations & formatting updated
//
//*****************  Version 20  *****************
//User: Parveen      Date: 8/31/09    Time: 3:15p
//Updated in $/LeapCC/Templates/Employee
//file upload coding updated
//
//*****************  Version 19  *****************
//User: Dipanjan     Date: 8/27/09    Time: 6:00p
//Updated in $/LeapCC/Templates/Employee
//Gurkeerat: Resolved issue 1290
//
//*****************  Version 18  *****************
//User: Parveen      Date: 8/27/09    Time: 11:26a
//Updated in $/LeapCC/Templates/Employee
//print & CSV button remove
//
//*****************  Version 17  *****************
//User: Parveen      Date: 8/12/09    Time: 4:55p
//Updated in $/LeapCC/Templates/Employee
//default date setting 
//
//*****************  Version 16  *****************
//User: Parveen      Date: 8/12/09    Time: 3:25p
//Updated in $/LeapCC/Templates/Employee
//alignment & formatting updated
//
//*****************  Version 15  *****************
//User: Parveen      Date: 8/05/09    Time: 5:59p
//Updated in $/LeapCC/Templates/Employee
//search condition updated
//
//*****************  Version 14  *****************
//User: Parveen      Date: 7/21/09    Time: 12:41p
//Updated in $/LeapCC/Templates/Employee
//new enhancement added "attachmentAcceptationLetter" in Employee
//Publisher tab 
//
//*****************  Version 13  *****************
//User: Parveen      Date: 7/17/09    Time: 2:41p
//Updated in $/LeapCC/Templates/Employee
//role permission,alignment, new enhancements added 
//
//*****************  Version 12  *****************
//User: Parveen      Date: 7/16/09    Time: 5:15p
//Updated in $/LeapCC/Templates/Employee
//new enhancements added (publisher, workshop, seminar, consulting) Div
//base show berif information browse
//
//*****************  Version 11  *****************
//User: Parveen      Date: 7/15/09    Time: 2:20p
//Updated in $/LeapCC/Templates/Employee
//alignment updated
//
//*****************  Version 10  *****************
//User: Parveen      Date: 7/15/09    Time: 1:08p
//Updated in $/LeapCC/Templates/Employee
//file system change, condition, formating & new enhancements added
//(Workshop)
//
//*****************  Version 9  *****************
//User: Parveen      Date: 7/07/09    Time: 9:48a
//Updated in $/LeapCC/Templates/Employee
//alignment, formatting, conditions updated
//
//*****************  Version 8  *****************
//User: Parveen      Date: 7/01/09    Time: 6:10p
//Updated in $/LeapCC/Templates/Employee
//search filter functionality updated
//
//*****************  Version 7  *****************
//User: Parveen      Date: 7/01/09    Time: 4:51p
//Updated in $/LeapCC/Templates/Employee
//lecture details format updated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 7/01/09    Time: 4:15p
//Updated in $/LeapCC/Templates/Employee
//alignment & formatting updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 6/30/09    Time: 5:36p
//Updated in $/LeapCC/Templates/Employee
//validation, conditions, personal information formatting updated 
//
//*****************  Version 4  *****************
//User: Parveen      Date: 6/30/09    Time: 10:37a
//Updated in $/LeapCC/Templates/Employee
//date formatting settings (date of birth, date of joining)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/24/09    Time: 3:00p
//Updated in $/LeapCC/Templates/Employee
//formatting, conditions, validations updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/17/09    Time: 11:04a
//Updated in $/LeapCC/Templates/Employee
//validation, formatting, themes base css templates changes
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/16/09    Time: 2:23p
//Created in $/LeapCC/Templates/Employee
//initial checkin
//
//*****************  Version 4  *****************
//User: Parveen      Date: 6/10/09    Time: 6:00p
//Updated in $/Leap/Source/Templates/Employee
//validation, conditions update 
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/10/09    Time: 5:33p
//Updated in $/Leap/Source/Templates/Employee
//condition, formatting & validation updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/18/09    Time: 3:27p
//Updated in $/Leap/Source/Templates/Employee
//divSeminar (alignement settings) 
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/18/09    Time: 1:13p
//Created in $/Leap/Source/Templates/Employee
//initial checkin 
//


?>