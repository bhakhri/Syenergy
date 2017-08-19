<?php
//-------------------------------------------------------
// Purpose: to design the layout for assign to subject.
// Author : Jaineesh
// Created on : (02.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
?>
<form method="post" name="addForm" id="addForm" action="<?php echo HTTP_LIB_PATH;?>/Student/studentFileUpload.php" method="post" enctype="multipart/form-data" style="display:inline" onsubmit="return false;"> 
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" >
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
           
            <tr>
                 <?php require_once(TEMPLATES_PATH . "/breadCrumb.php"); ?> 
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="440">
            <tr>
             <td valign="top" class="content">

             <table width="100%" border="0" cellspacing="0" cellpadding="0" height="435">
             
             <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="contenttab_row1" width="20%"><span class="content_title">Student Information :</span>
                        </td>
                        <td width="80%" class="contenttab_internal_rows1" align="right"><span class="content_title"><nobr><b>Study Period : </b></nobr></span></td>
                        <td class="padding"><select size="1" class="selectfield" name="semesterDetail" id="semesterDetail" onChange="totalFunction(this.value,tabNumber);setSubjectCode(this.value);setTeacherCode(this.value);resetDate();getResource(this.value);">
                        <option value="0" selected="selected">All</option>
                        <?php
                            $studentId = $sessionHandler->getSessionVariable('StudentId');
                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                            echo HtmlFunctions::getInstance()->getStudyPeriodName($studentId,$classId);
                        ?>
                        </select>
                        </td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" ><div id="dhtmlgoodies_tabView1"  >
                        <div class="dhtmlgoodies_aTab" style="overflow:auto">
<!-- <form method="POST" name="addForm" id="addForm"  action="<?php echo HTTP_LIB_PATH;?>/Student/studentFileUpload.php" method="post" enctype="multipart/form-data" style="display:inline"> -->
                        <table width="100%" border="0" cellspacing="5px" cellpadding="1px" >
                        <?php //DISPLAYS ALL RECORDS 
                        require_once($FE . "/Library/common.inc.php");
                        //$not_applicable_string = "---";
                        $studentCount = count($studentInformationArray);

                        if($studentCount >0 && is_array($studentInformationArray) ) {
                         
                        echo "<input type='hidden' name='studentId' id='studentId' value='".$studentInformationArray[$i]['studentId']."'>";
                        
                        for($i=0; $i<1; $i++ ) {
                         $bg = $bg =='row0' ? 'row1' : 'row0';
                        
                         $ExamBy = $results[$studentInformationArray[$i]['compExamBy']];
                         $bloodGroup = $bloodResults[$studentInformationArray[$i]['studentBloodGroup']];
                         if ( $ExamBy != '') {
                            $ExamBy = $ExamBy;
                         }
                         else {
                            $ExamBy = NOT_APPLICABLE_STRING;
                         }
                        
                        echo '<tr>';  
                        echo '<td class="contenttab_internal_rows" valign="top" width="10%"><nobr><b>First Name </b></nobr></td>
                        <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>';
                        if (strip_slashes($studentInformationArray[$i]['firstName'])!=""){
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left" width="20%">'.chunk_split(strip_slashes($studentInformationArray[$i]['firstName']),40,"<br>").'</td>'; 
                        }
                        else {
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.NOT_APPLICABLE_STRING.'</td>';
                            }
                        echo '<td class="contenttab_internal_rows" valign="top" width="10%"><nobr><b>Last Name </b></nobr></td>
                        <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['lastName'])!=""){
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" width="20%">'.chunk_split(strip_slashes($studentInformationArray[$i]['lastName']),40,"<br>").'</td>';
                        }
                        else {
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.NOT_APPLICABLE_STRING.'</td>';
                        }
                            
                        echo '<td colspan="4" align="center" valign="middle" rowspan="8">
                            <table border="0" cellpadding="0" cellspacing="0"  width="100%" height="80">
                                <tr>
                                        <td class="" colspan="3" align="center" >';
            if(file_exists(STORAGE_PATH.'/Images/Student/'.$studentInformationArray[$i]['studentPhoto'])) {
                echo "<img style='border:1px solid black;' id='studentImageId1' src=\"".STUDENT_PHOTO_PATH."/".strip_slashes($studentInformationArray[$i]['studentPhoto'])."?yy=".rand(1,100)."\" width=\"125\" height=\"120\"/>";
            }
            else
                echo "<img style='border:1px solid black;' id='studentImageId1' src=\"".IMG_HTTP_PATH."/notFound.jpg?x=".rand(1,100)."\" width=\"125\" height=\"120\"/>";
                                            
                                        echo '</td></tr>';
                                    //***************CODE FOR CHANGING STUDENT PROFILE IMAGE*************
                                    //echo "<pre>";
                                    //print_r($_SESSION);
                                    //die;
                                    $accessArray = $sessionHandler->getSessionVariable('StudentInfoDetail');
                                    $editAccess = $accessArray['edit'];
                                    if($editAccess==1){
                                      echo '<tr>
                                          <td valign="bottom" align="left" width="100%">
                                          <table border="0" cellpadding="0" cellspacing="0" width="78%">
                                          <tr>
                                           <td class="contenttab_internal_rows" align="left" nowrap><b>Select Image</b></td>
                                           <td valign="top" class="contenttab_internal_rows1" align="left" nowrap><b>:</b> 
                                           <input type="file"  id="studentPhoto" name="studentPhoto" size="10" accept="image/*" />
                                           </td>
                                           <td align="left">
                                           <input type="image"  src="'.IMG_HTTP_PATH.'/upload.gif" onClick="return initAdd();" >
                                           <iframe id="uploadTargetAdd" name="uploadTargetAdd" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
                                           </td>
                                          </tr>
                                         </table>
                                        </td></tr>'; 
                                    }    
                            echo    '</table>
                        </td>

                        </tr>';
                        echo '<tr>';    
                        echo '<td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>Roll No.</b></nobr></td>
                              <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>';
                        if (strip_slashes($studentInformationArray[$i]['rollNo'])!=""){
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentInformationArray[$i]['rollNo']),30,"<br>").'</td>';
                        }
                        else {
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.NOT_APPLICABLE_STRING.'</td>';
                        }
                        echo '<td class="contenttab_internal_rows" valign="top" ><nobr><b>Institute Reg. No.</b></nobr></td>
                              <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>';
                        if (strip_slashes($studentInformationArray[$i]['regNo'])!=""){
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentInformationArray[$i]['regNo']),15,"<br>").'</td>';
                        }
                        else {
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.NOT_APPLICABLE_STRING.'</td>';
                        }
                        echo '</tr>';

                        echo '<tr>';    
                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Univ. RNo.</b></nobr></td>
                             <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>';
                        if (strip_slashes($studentInformationArray[$i]['universityRollNo'])!=""){
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentInformationArray[$i]['universityRollNo']),30,"<br>").'</td>';
                        }
                        else {
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.NOT_APPLICABLE_STRING.'</td>';
                        }
                        
                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>University Reg. No.</b></nobr></td>
                              <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>';
                        if (strip_slashes($studentInformationArray[$i]['universityRollNo'])!=""){
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentInformationArray[$i]['universityRegNo']),30,"<br>").'</td>';
                        }
                        else {
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.NOT_APPLICABLE_STRING.'</td>';
                        }
                        echo '</tr>';
                        echo '<tr>'; 
                        
                        echo '<td class="contenttab_internal_rows" ><nobr valign="top"><b>Date of Birth </b></nobr></td>
                            <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>';
                        if (strip_slashes($studentInformationArray[$i]['dateOfBirth'])!="" && strip_slashes($studentInformationArray[$i]['dateOfBirth'])!="0000-00-00"){
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.UtilityManager::formatDate($studentInformationArray[$i]['dateOfBirth']).'</td>';
                        }
                        else {
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.NOT_APPLICABLE_STRING.'</td>';
                        }
                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Gender </b></nobr></td>
                              <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>';
                        if (strip_slashes($studentInformationArray[$i]['studentGender'])!=""){
                            if ($studentInformationArray[$i]['studentGender'] == "M") {
                                $studentInformationArray[$i]['studentGender'] = "Male";
                            }
                            else {
                                $studentInformationArray[$i]['studentGender'] = "Female";
                            }
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.strip_slashes($studentInformationArray[$i]['studentGender']).'</td>';
                        }
                        else {
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.NOT_APPLICABLE_STRING.'</td>';
                        }
                        echo '</tr>';
                        echo '<tr>';    
                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Email</b></nobr></td>
                            <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>
                            <td class="contenttab_internal_rows" valign="middle" >';  
                        if (strip_slashes($studentInformationArray[$i]['studentEmail'])!=""){
                            $emailId = strip_slashes($studentInformationArray[$i]['studentEmail']);
                        }
                        else {
                            $emailId = NOT_APPLICABLE_STRING;
                        }
?>
                    <input type="text" id="studentEmail" name="studentEmail" class="inputbox" value="<?php echo $emailId; ?>" maxlength="100" />                                      
<?php
                      echo '</td><td class="contenttab_internal_rows" valign="top"><nobr><b>Nationality </b></nobr></td>
                            <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>';
                        if (strip_slashes($studentInformationArray[$i]['nationalityName'])!=""){
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentInformationArray[$i]['nationalityName']),40,"<br>").'</td>';
                        }
                        else {
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.NOT_APPLICABLE_STRING.'</td>';
                        }
                        echo '</tr>';
                        echo '<tr>';    
                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Contact No. </b></nobr></td>
                              <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>
                              <td class="contenttab_internal_rows" valign="top" >';    
                              
                        if (strip_slashes($studentInformationArray[$i]['studentPhone'])!=""){    
                            $phone = strip_slashes($studentInformationArray[$i]['studentPhone']);
                        }
                        else {
                            $phone = NOT_APPLICABLE_STRING;
                        }
?>
                        <input type="text" id="studentNo" name="studentNo" class="inputbox" value="<?php echo $phone; ?>" maxlength="40" />
<?php                    echo '</td><td class="contenttab_internal_rows" ><nobr><b>Mobile No. </b></nobr></td>
                              <td class="contenttab_internal_rows" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>
                              <td class="contenttab_internal_rows" valign="top" >';    
                        if (strip_slashes($studentInformationArray[$i]['studentMobileNo'])!=""){    
                            $phone = strip_slashes($studentInformationArray[$i]['studentMobileNo']);
                        }
                        else {
                            $phone = NOT_APPLICABLE_STRING;
                        }
?>
                        <input type="text" id="studentMobile" name="studentMobile" class="inputbox" value="<?php echo $phone; ?>" maxlength="15" />
<?php                        
                        echo '</td></tr>';
                        echo '<tr>';    
                        echo '<td class="contenttab_internal_rows" ><nobr><b>Domicile </b></nobr></td>
                        <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>';
                        if (strip_slashes($studentInformationArray[$i]['stateName'])!=""){    
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.chunk_split(strip_slashes($studentInformationArray[$i]['stateName']),40,"<br>").'</td>';
                        }
                        else {
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.NOT_APPLICABLE_STRING.'</td>';
                        }
                        echo '<td class="contenttab_internal_rows" ><nobr><b>Category </b></nobr></td>
                        <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>';
                        if (strip_slashes($studentInformationArray[$i]['quotaName'])!=""){    
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.strip_slashes($studentInformationArray[$i]['quotaName']).'</td>';
                        }
                        else {
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.NOT_APPLICABLE_STRING.'</td>';
                        }
                        echo '</tr>';
                        echo '<tr>';    
                        echo '<td class="contenttab_internal_rows" ><nobr><b>User Name </b></nobr></td>
                            <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>';
                        if (strip_slashes($studentInformationArray[$i]['studentGender'])!=""){
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left" colspan="6">'.chunk_split(strip_slashes($studentInformationArray[$i]['userName']),40,"<br>").'</td>';
                        }
                        else {
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.NOT_APPLICABLE_STRING.'</td>';
                        }
                        echo '</tr>';

                        echo '<tr>';    
                        echo '<td class="contenttab_internal_rows" width="10%"><nobr><b>Exam </b></nobr></td>
                              <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>
                        <td class="contenttab_internal_rows1" align="left">'.$ExamBy.'</td>';
                        echo '<td class="contenttab_internal_rows" width="10%"><nobr><b>Rank </b></nobr></td>
                        <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>';
                        if (strip_slashes($studentInformationArray[$i]['compExamRank'])!=""){
                             echo '<td class="contenttab_internal_rows1" align="left">'.chunk_split(strip_slashes($studentInformationArray[$i]['compExamRank']),15,"<br>").'</td>';
                        }
                        else {
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.NOT_APPLICABLE_STRING.'</td>';
                        }
                        echo '<td class="contenttab_internal_rows" width="10%"><nobr><b>Entrance Exam Roll No.</b></nobr></td>
                        <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>';
                        if (strip_slashes($studentInformationArray[$i]['compExamRollNo'])!=""){
                            echo '<td class="contenttab_internal_rows1" align="left">'.chunk_split(strip_slashes($studentInformationArray[$i]['compExamRollNo']),15,"<br>").'</td>';
                        }
                        else {
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.NOT_APPLICABLE_STRING.'</td>';
                        }
                        echo '</tr>';
                        
                        
                        echo '<tr>';    
                        echo '<td class="contenttab_internal_rows" ><nobr><b>Highest Qualification</b></nobr></td>
                              <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>
                              <td class="contenttab_internal_rows1" align="left" colspan="7">
                                <input type="text" name="highestQualification" id="highestQualification" class="inputbox" style="width:95%" value="'.strip_slashes(trim($studentInformationArray[$i]['highestEducationalQualification'])).'">     
                              </td>';
                        echo '</tr>';
                        
                        echo '<tr>';    
                        echo '<td class="contenttab_internal_rows" ><nobr><b>Source of Program Fee</b></nobr></td>
                              <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>
                              <td class="contenttab_internal_rows1" align="left" colspan="7">
                                <select name="programFeeId" id="programFeeId" class="inputbox">
                                 <option value="">Select</option>';
                                 echo HtmlFunctions::getInstance()->getStudentProgramFeeData(' ',trim($studentInformationArray[$i]['programFeeId']));
                        echo     '</select>
                              </td>';
                        echo '</tr>';
                        
                        echo '<tr>';    
                        echo '<td class="contenttab_internal_rows" width="10%"><nobr><b>Fee Receipt No.</b></nobr></td>
                              <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>
                        <td class="contenttab_internal_rows1" align="left">'.chunk_split(strip_slashes($studentInformationArray[$i]['feeReceiptNo']),20,"<br>").'</td>';
                        echo '<td class="contenttab_internal_rows" width="10%" ><nobr><b>Sports Activity</b></nobr></td>
                        <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>';
                        if (strip_slashes($studentInformationArray[$i]['studentSportsActivity'])!=""){
                             echo '<td class="contenttab_internal_rows1" align="left">'.chunk_split(strip_slashes($studentInformationArray[$i]['studentSportsActivity']),255,"<br>").'</td>';
                        }
                        else {
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.NOT_APPLICABLE_STRING.'</td>';
                        }
                        echo '<td class="contenttab_internal_rows" width="10%"><nobr><b>Blood Group</b></nobr></td>
                        <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>';
                        if ($bloodGroup != ""){
                            echo '<td class="contenttab_internal_rows1" align="left">'.$bloodGroup.'</td>';
                        }
                        else {
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.NOT_APPLICABLE_STRING.'</td>';
                        }
                        echo '<tr>';
                        echo '<td class="contenttab_internal_rows" width="10%"><nobr><b>Current Organization</b></nobr></td>
                        <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>';
                        if (strip_slashes($studentInformationArray[0]['currentOrg'])!=""){
                          echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.strip_slashes($studentInformationArray[0]['currentOrg']).'</td>';
                        }
                        else {
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.NOT_APPLICABLE_STRING.'</td>';
                        }
                        echo '<td class="contenttab_internal_rows" width="10%"><nobr><b>Designation</b></nobr></td>
                        <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>';
                        if (strip_slashes($studentInformationArray[0]['companyDesignation'])!=""){
                          echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.strip_slashes($studentInformationArray[0]['companyDesignation']).'</td>';
                        }
                        else {
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.NOT_APPLICABLE_STRING.'</td>';
                        }
                        echo '<td class="contenttab_internal_rows" width="10%"><nobr><b>Work Email</b></nobr></td>
                        <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>';
                        if (strip_slashes($studentInformationArray[0]['workEmail'])!=""){
                          echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.strip_slashes($studentInformationArray[0]['workEmail']).'</td>';
                        }
                        else {
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.NOT_APPLICABLE_STRING.'</td>';
                        }
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td class="contenttab_internal_rows" width="10%"><nobr><b>Office Phone No.</b></nobr></td>
                        <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>';
                        if (strip_slashes($studentInformationArray[0]['officeContactNo'])!=""){
                          echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.strip_slashes($studentInformationArray[0]['officeContactNo']).'</td>';
                        }
                        else {
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.NOT_APPLICABLE_STRING.'</td>';
                        }
                        echo '<td class="contenttab_internal_rows" width="10%"><nobr><b>Role</b></nobr></td>
                        <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>';
                        if (strip_slashes($studentInformationArray[0]['role'])!=""){
                          echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.strip_slashes($studentInformationArray[0]['role']).'</td>';
                        }
                        else {
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.NOT_APPLICABLE_STRING.'</td>';
                        }
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td height = 5>';
                        echo '</tr>'; 
                        echo '</tr>';

                        echo '<tr>';    
                        echo '<td colspan="7" class="contenttab_internal_rows" width="10%" style="text-align:left"><nobr><U><b>Correspondence Address :</b></U></nobr></td></tr>';
                        echo '<tr>';
                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Address1</b></nobr></td>
                              <td class="contenttab_internal_rows" valign="top" ><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>
                              <td class="contenttab_internal_rows" valign="middle" >';
                        if (strip_slashes($studentInformationArray[$i]['corrAddress1'])!=""){
                           $corrAddress = strip_slashes($studentInformationArray[$i]['corrAddress1']);
                        }
                        else {
                           $corrAddress = '';
                        }
?>                      <textarea type="text" id="correspondeceAddress1" name="correspondeceAddress1" class="inputbox1" 
                            rows="3" cols="27" maxlength="255" onkeyup="return ismaxlength(this)"> <?php echo $corrAddress; ?></textarea>
<?php
                        echo '</td><td class="contenttab_internal_rows" valign="top" ><nobr><b>Address2</b></nobr></td>
                            <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>
                            <td class="contenttab_internal_rows" valign="middle" >';
                        if (strip_slashes($studentInformationArray[$i]['corrAddress2'])!=""){    
                            $corrAddress = strip_slashes($studentInformationArray[$i]['corrAddress2']); 
                        }
                        else {
                            $corrAddress = '';
                        }
?>   
                       <textarea type="text" id="correspondeceAddress2" name="correspondeceAddress2" class="inputbox1" 
                            rows="3" cols="27" maxlength="255" onkeyup="return ismaxlength(this)"> <?php echo $corrAddress; ?></textarea>
<?php                            
                        echo '</td><td class="contenttab_internal_rows" valign="top"><nobr><b>Pincode </b></nobr></td>
                              <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>
                              <td class="contenttab_internal_rows" valign="top" >';   
                        if (strip_slashes($studentInformationArray[$i]['corrPinCode'])!=""){    
                            $pinCode = strip_slashes($studentInformationArray[$i]['corrPinCode']);
                        }
                        else {
                            $pinCode = '';
                        }
?>                        
     <input type="text" id="correspondecePincode" name="correspondecePincode" class="inputbox"  value="<?php echo $pinCode; ?>"  maxlength="10" />
<?php
                        echo '</td></tr>';
                        echo '<tr>';    
                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Country </b></nobr></td>
                        <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>
                        <td class="contenttab_internal_rows" valign="top" >';    
                        if (strip_slashes($studentInformationArray[$i]['corrCountry'])!=""){
                            $countryId = strip_slashes($studentInformationArray[$i]['corrCountryId']);
                        }
                        else {
                            $countryId = '';
                        }
?> <select size="1" class="selectfield" name="correspondenceCountry" id="correspondenceCountry" onChange="autoPopulate(this.value,'states','Add','correspondenceStates','correspondenceCity');" >
                            <option value="" selected="selected">Select</option>
                              <?php
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getCountriesData($countryId);
                              ?>
                            </select>
<?php                        
                        echo '</td><td class="contenttab_internal_rows"><nobr><b>State </b></nobr></td>
                        <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>
                         <td class="contenttab_internal_rows" valign="top" >';    
                        if (strip_slashes($studentInformationArray[$i]['corrState'])!=""){
                            $state = strip_slashes($studentInformationArray[$i]['corrStateId']);   
                        }
                        else {
                             $state = '';  
                        }
?>                        
                        <select size="1" class="selectfield" name="correspondenceStates" id="correspondenceStates" onChange="autoPopulate(this.value,'city','Add','correspondenceStates','correspondenceCity');" >
                            <option value="">Select</option>
                            <?php
                                  //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                  //echo HtmlFunctions::getInstance()->getStatesData($studentInformationArray[$i]['corrStateId'] ," WHERE countryId=$studentInformationArray[$i]['corrCountryId']" );
                            ?>
                            </select>
<?php
                        echo '</td><td class="contenttab_internal_rows" valign="top"><nobr><b>City</b></nobr></td>
                         <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>
                         <td class="contenttab_internal_rows" valign="top" >';    
                            if(strip_slashes($studentInformationArray[$i]['corrCity'])!=""){
                              $city = strip_slashes($studentInformationArray[$i]['corrCityId']);   
                            }
                            else {
                              $city = '';  
                            }
?>
<select size="1" class="selectfield" name="correspondenceCity" id="correspondenceCity"  >
                            <option value="">Select</option>
                            <?php
        // require_once(BL_PATH.'/HtmlFunctions.inc.php');
        //echo HtmlFunctions::getInstance()->getCityData($studentInformationArray[$i]['corrCityId'] ," WHERE stateId=$studentInformationArray[$i]['corrStateId']" );
                            ?>
                            </select>
<?php                            
                        echo '</td></tr>';
                        echo '<tr>';    
                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Contact No.</b></nobr></td>
                        <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>
                         <td class="contenttab_internal_rows" valign="top" >';    
                        if (strip_slashes($studentInformationArray[$i]['corrPhone'])!=""){    
                          $contactNo = strip_slashes($studentInformationArray[$i]['corrPhone']);   
                        }
                        else {
                          $contactNo = '';  
                        }
?>
     <input type="text" id="correspondecePhone" name="correspondecePhone" class="inputbox" value="<?php echo $contactNo; ?>"  maxlength="10" />
<?php
                        echo '</td><tr>';    
                        
                        echo '<td colspan="7" class="contenttab_internal_rows" width="10%" style="text-align:left"><nobr><U><b>Permanent Address: </b></U></nobr></td></tr>';
                        echo '<tr>';    
                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Address1</b></nobr></td>
                              <td class="contenttab_internal_rows" valign="top" ><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>
                              <td class="contenttab_internal_rows" valign="middle" >';
                        if (strip_slashes($studentInformationArray[$i]['permAddress1'])!=""){
                           $corrAddress = strip_slashes($studentInformationArray[$i]['permAddress1']);
                        }
                        else {
                           $corrAddress = '';
                        }
?>                      <textarea type="text" id="permanentAddress1" name="permanentAddress1" class="inputbox1" 
                            rows="3" cols="27" maxlength="255" onkeyup="return ismaxlength(this)"> <?php echo $corrAddress; ?></textarea>
<?php
                        echo '</td><td class="contenttab_internal_rows" valign="top" ><nobr><b>Address2</b></nobr></td>
                            <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>
                            <td class="contenttab_internal_rows" valign="middle" >';
                        if (strip_slashes($studentInformationArray[$i]['permAddress2'])!=""){    
                            $corrAddress = strip_slashes($studentInformationArray[$i]['permAddress2']); 
                        }
                        else {
                            $corrAddress = '';
                        }
?>   
                       <textarea type="text" id="permanentAddress2" name="permanentAddress2" class="inputbox1" 
                            rows="3" cols="27" maxlength="255" onkeyup="return ismaxlength(this)"> <?php echo $corrAddress; ?></textarea>
<?php                            
                        echo '</td><td class="contenttab_internal_rows" valign="top"><nobr><b>Pincode </b></nobr></td>
                              <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>
                              <td class="contenttab_internal_rows" valign="top" >';   
                        if (strip_slashes($studentInformationArray[$i]['permPinCode'])!=""){    
                            $pinCode = strip_slashes($studentInformationArray[$i]['permPinCode']);
                        }
                        else {
                            $pinCode = '';
                        }
?>                        
     <input type="text" id="permanentPincode" name="permanentPincode" class="inputbox"  value="<?php echo $pinCode; ?>"  maxlength="10" />
<?php
                        echo '</td></tr>';
                        echo '<tr>';    
                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Country </b></nobr></td>
                        <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>
                        <td class="contenttab_internal_rows" valign="top" >';    
                        if (strip_slashes($studentInformationArray[$i]['permCountry'])!=""){
                            $countryId = strip_slashes($studentInformationArray[$i]['permCountryId']);
                        }
                        else {
                            $countryId = '';
                        }
?> <select size="1" class="selectfield" name="permanentCountry" id="permanentCountry" onChange="autoPopulate(this.value,'states','Add','permanentStates','permanentCity');" >
                            <option value="" selected="selected">Select</option>
                              <?php
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getCountriesData($countryId);
                              ?>
                            </select>
<?php                        
                        echo '</td><td class="contenttab_internal_rows"><nobr><b>State </b></nobr></td>
                        <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>
                         <td class="contenttab_internal_rows" valign="top" >';    
                        if (strip_slashes($studentInformationArray[$i]['permState'])!=""){
                            $state = strip_slashes($studentInformationArray[$i]['permStateId']);   
                        }
                        else {
                             $state = '';  
                        }
?>                        
                        <select size="1" class="selectfield" name="permanentStates" id="permanentStates" onChange="autoPopulate(this.value,'city','Add','permanentStates','permanentCity');" >
                            <option value="">Select</option>
                            <?php
                                  //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                  //echo HtmlFunctions::getInstance()->getStatesData($studentInformationArray[$i]['corrStateId'] ," WHERE countryId=$studentInformationArray[$i]['corrCountryId']" );
                            ?>
                            </select>
<?php
                        echo '</td><td class="contenttab_internal_rows" valign="top"><nobr><b>City</b></nobr></td>
                         <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>
                         <td class="contenttab_internal_rows" valign="top" >';    
                            if(strip_slashes($studentInformationArray[$i]['permCity'])!=""){
                              $city = strip_slashes($studentInformationArray[$i]['permCityId']);   
                            }
                            else {
                              $city = '';  
                            }
?>
<select size="1" class="selectfield" name="permanentCity" id="permanentCity"  >
                            <option value="">Select</option>
                            <?php
        // require_once(BL_PATH.'/HtmlFunctions.inc.php');
        //echo HtmlFunctions::getInstance()->getCityData($studentInformationArray[$i]['corrCityId'] ," WHERE stateId=$studentInformationArray[$i]['corrStateId']" );
                            ?>
                            </select>
<?php                            
                        echo '</td></tr>';
                        echo '<tr>';    
                         echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Contact No.</b></nobr></td>
                        <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>
                         <td class="contenttab_internal_rows" valign="top" >';    
                        if (strip_slashes($studentInformationArray[$i]['permPhone'])!=""){    
                          $contactNo = strip_slashes($studentInformationArray[$i]['permPhone']);   
                        }
                        else {
                          $contactNo = '';  
                        }
                        
?>
     <input type="text" id="permanentPhone" name="permanentPhone" class="inputbox" value="<?php echo $contactNo; ?>"  maxlength="10" />
<?php
                        echo '</td><tr>'; 
                            }
?>

<?php 
                        echo '<tr>';
                        echo '<td colspan="7" class="contenttab_internal_rows" width="10%" style="text-align:left"><nobr><U><b>Present Address: </b></U></nobr></td></tr>';
                        echo '<tr>';    
                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Address1</b></nobr></td>
                              <td class="contenttab_internal_rows" valign="top" ><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>';
                        if (strip_slashes($studentInformationArray[0]['presentAddress1'])!=""){
                           echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.strip_slashes($studentInformationArray[0]['presentAddress1']).'</td>';
                        }
                        else {
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.NOT_APPLICABLE_STRING.'</td>';
                        }
?>                     
<?php
                        echo '</td><td class="contenttab_internal_rows" valign="top" ><nobr><b>Address2</b></nobr></td>
                            <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>';
                        if (strip_slashes($studentInformationArray[0]['presentAddress2'])!=""){    
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.strip_slashes($studentInformationArray[0]['presentAddress2']).'</td>';
                        }
                        else {
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.NOT_APPLICABLE_STRING.'</td>';
                        }
?>   
<?php                            
                        echo '</td><td class="contenttab_internal_rows" valign="top"><nobr><b>Pincode </b></nobr></td>
                              <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>';  
                        if (strip_slashes($studentInformationArray[0]['presentPinCode'])!=""){    
                             echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.strip_slashes($studentInformationArray[0]['presentPinCode']).'</td>';
                        }
                        else {
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.NOT_APPLICABLE_STRING.'</td>';
                        }
?>                        
    
<?php
                        echo '</td></tr>';
                        echo '<tr>';    
                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Country </b></nobr></td>
                        <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>
                        <td class="contenttab_internal_rows" valign="top" >';    
                        if (strip_slashes($studentInformationArray[0]['presentCountryId'])!=""){
                            $countryId2 = strip_slashes($studentInformationArray[0]['presentCountryId']);
                        }
                        else {
                            $countryId2= '';
                        }
?> <select size="1" class="selectfield" name="presentCountry" id="presentCountry" onChange="autoPopulate(this.value,'states','Add','presentStates','presentCity');" >
                            <option value="" selected="selected">Select</option>
                              <?php
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getCountriesData($studentInformationArray[0]['presentCountryId']," WHERE presentCountryId=".$studentInformationArray[0]['presentCountryId']);
                              ?>
                         </select>
                           
<?php                        
                        echo '</td><td class="contenttab_internal_rows"><nobr><b>State </b></nobr></td>
                        <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>  
                         <td class="contenttab_internal_rows" valign="top" >';    
                        if (strip_slashes($studentInformationArray[0]['presentStateId'])!=""){
                            $state1 = strip_slashes($studentInformationArray[0]['presentStateId']);   
                        }
                        else {
                             $state1 = '';  
                        }
?>                        
                        <select size="1" class="selectfield" name="presentStates" id="presentStates" onChange="autoPopulate(this.value,'city','Add','presentStates','presentCity');" >
                            <option value="">Select</option>
                            <?php
                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                  echo HtmlFunctions::getInstance()->getStatesData($studentInformationArray[0]['presentStateId'] ," WHERE presentCountryId=$studentInformationArray[0]['presentCountryId']" );
                            ?>
                            </select>
                       
                     
                            <?php
                                  //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                  //echo HtmlFunctions::getInstance()->getStatesData($studentInformationArray[0]['corrStateId'] ," WHERE countryId=$studentInformationArray[0]['corrCountryId']" );
                            ?>
                            </select>
<?php
                        echo '</td><td class="contenttab_internal_rows" valign="top"><nobr><b>City</b></nobr></td>
                         <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td> 
                           <td class="contenttab_internal_rows" valign="top" >';    
                            if(strip_slashes($studentInformationArray[0]['presentCityId'])!=""){
                              $city = strip_slashes($studentInformationArray[0]['presentCityId']);   
                            }
                            else {
                              $city = '';  
                            }
?>
<select size="1" class="selectfield" name="presentCity" id="presentCity"  >
                            <option value="">Select</option>
                            <?php
        require_once(BL_PATH.'/HtmlFunctions.inc.php');
        echo HtmlFunctions::getInstance()->getCityData($studentInformationArray[0]['presentCityId'] ," WHERE presentStateId=$studentInformationArray[0]['presentStateId']" );
                            ?>
                            </select>


<?php
                        echo '<tr>';
                        echo '<td height = 5>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td colspan="7" class="contenttab_internal_rows" width="10%" style="text-align:left"><nobr><U><b>Spouse / Emergency Contact Details: </b></U></nobr></td></tr>';
                        echo '<tr>';
                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Name</b></nobr></td>
                         <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>'; 
                            if(strip_slashes($studentInformationArray[0]['spouseName'])!=""){
                             echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.strip_slashes($studentInformationArray[0]['spouseName']).'</td>';
                        }
                        else {
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.NOT_APPLICABLE_STRING.'</td>';
                        }
                         echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Relationship</b></nobr></td>
                         <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>';   
                            if(strip_slashes($studentInformationArray[0]['spouseRelation'])!=""){
                             echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.strip_slashes($studentInformationArray[0]['spouseRelation']).'</td>';
                        }
                        else {
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.NOT_APPLICABLE_STRING.'</td>';
                        }
                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Mobile Phone No.</b></nobr></td>
                         <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>';
                            if(strip_slashes($studentInformationArray[0]['spousePhone'])!=""){
                               echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.strip_slashes($studentInformationArray[0]['spousePhone']).'</td>';
                        }
                        else {
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.NOT_APPLICABLE_STRING.'</td>';
                        }
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Email</b></nobr></td>
                         <td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>:</b>&nbsp;&nbsp;</nobr></td>';   
                            if(strip_slashes($studentInformationArray[0]['spouseEmail'])!=""){
                              echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.strip_slashes($studentInformationArray[0]['spouseEmail']).'</td>';
                        }
                        else {
                            echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.NOT_APPLICABLE_STRING.'</td>';
                        }
                        echo '</tr>';
                        ?>                               
                       
                             <tr>
                                <td  align="center" style="padding-right:5px" valign="bottom" colspan="9">       
                   					<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateAll(this.form);"/>
                                </td>
                            </tr>
<?php                        
                        }
                        else {
                            echo '<tr><td colspan="5" align="center">No record found</td></tr>';
                        }
                        ?>
                        </table>
<!-- </form> -->
                        </div>
                        
                        <div class="dhtmlgoodies_aTab" style="overflow:auto">
                        <table width="100%" border="0" cellspacing="5px" cellpadding="1px">
                          <?php //DISPLAYS ALL RECORDS 
                        require_once($FE . "/Library/common.inc.php");
                        $studentCount = count($studentInformationArray);
                        
                        if($studentCount >0 && is_array($studentInformationArray) ) {
                         
                            for($i=0; $i<1; $i++ ) {
                         /*if ($studentInformationArray[$i]['fatherTitle']=="1"){
                            $title = "Mr.";
                         }
                         else if ($studentInformationArray[$i]['fatherTitle']=="2"){
                             $title = "Mrs.";
                         }
                         else if ($studentInformationArray[$i]['fatherTitle']=="3"){
                             $title = "Miss";
                         }*/

                        $title =     $titleResults[$studentInformationArray[$i]['fatherTitle']];
                         
                        $mothertitle =     $titleResults[$studentInformationArray[$i]['motherTitle']];

                        $guardianTitle =     $titleResults[$studentInformationArray[$i]['guardianTitle']];
                         
                         
                         
                         if ($studentInformationArray[$i]['fatherCountryId']=="IS NULL"){
                            $fatherCountryId = "";
                         }
                         if ($studentInformationArray[$i]['motherCountryId']=="IS NULL"){
                            $motherCountryId = "";
                         }
                         if ($studentInformationArray[$i]['guardianCountryId']=="IS NULL"){
                            $guardianCountryId = "";
                         }
                        echo '<tr>';    
                            echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Father\'s Name </b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['fatherName'])!=""){
                                echo '<td class="contenttab_internal_rows1" align="left" width="15%" valign="top"><b>:</b>&nbsp;&nbsp;'.$title.' '.chunk_split(strip_slashes($studentInformationArray[$i]['fatherName']),30,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }

                            echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Mother\'s Name </b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['motherName'])!=""){
                                echo '<td class="contenttab_internal_rows1" align="left" width="15%" valign="top"><b>:</b>&nbsp;&nbsp;'.$mothertitle.' '.chunk_split(strip_slashes($studentInformationArray[$i]['motherName']),30,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Guardian\'s Name </b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['guardianName'])!=""){    
                                echo '<td class="contenttab_internal_rows1" align="left" width="15%" valign="top"><b>:</b>&nbsp;&nbsp;'.$guardianTitle.' '.chunk_split(strip_slashes($studentInformationArray[$i]['guardianName']),30,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '</tr>';
                            echo '<tr>';    
                            echo '<td class="contenttab_internal_rows" ><nobr valign="top"><b>Occupation </b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['fatherOccupation'])!=""){    
                                echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['fatherOccupation']),30,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Occupation </b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['motherOccupation'])!=""){        
                                echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['motherOccupation']),30,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Occupation </b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['guardianOccupation'])!=""){        
                                echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['guardianOccupation']),30,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '</tr>';
                            echo '<tr>';    
                            echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Mobile No. </b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['fatherMobileNo'])!=""){
                                echo '<td class="contenttab_internal_rows1" align="left" valign="top" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['fatherMobileNo']),30,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '<td class="contenttab_internal_rows" valign="top" width="10%"><nobr><b>Mobile No. </b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['motherMobileNo'])!=""){
                                echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['motherMobileNo']),30,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Mobile No. </b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['guardianMobileNo'])!=""){
                                echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['guardianMobileNo']),30,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '</tr>';
                            echo '<tr>';    
                            echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Email</b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['fatherEmail'])!=""){
                                echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['fatherEmail']),30,"<br>").'</td>';
                            }
                            else{
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Email </b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['motherEmail'])!=""){
                                echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['motherEmail']),30,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Email</b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['guardianEmail'])!=""){
                                echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['guardianEmail']),30,"<br>").'</td></tr>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '<tr>';    
                            echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Address1</b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['fatherAddress1'])!=""){
                                echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['fatherAddress1']),30,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Address1 </b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['motherAddress1'])!=""){
                                echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['motherAddress1']),30,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" valign="top" align="left"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Address1</b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['guardianAddress1'])!=""){
                                echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['guardianAddress1']),30,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '</tr>';
                            echo '<tr>';    
                            echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Address2</b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['fatherAddress2'])!=""){
                                echo '<td class="contenttab_internal_rows1" valign="top" align="left"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['fatherAddress2']),30,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }

                            echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Address2</b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['motherAddress2'])!=""){
                                echo '<td class="contenttab_internal_rows1" valign="top" align="left"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['motherAddress2']),30,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" valign="top" align="left"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Address2</b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['guardianAddress2'])!=""){
                                echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['guardianAddress2']),30,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '</tr>';
                            echo '<tr>';    
                            echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Country</b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['fatherCountry'])!=""){    
                                echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['fatherCountry']),40,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Country</b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['motherCountry'])!=""){
                                echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['motherCountry']),40,"<br>").'</td>';    
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Country</b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['guardCountry'])!=""){
                                echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['guardCountry']),40,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '</tr>';
                            echo '<tr>';    
                            echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>State </b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['fatherState'])!=""){
                                echo '<td class="contenttab_internal_rows1" align="left" valign="top"<b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['fatherState']),40,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>State </b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['motherState'])!=""){
                                echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['motherState']),40,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>State </b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['guardState'])!=""){
                                echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['guardState']),40,"<br>").'</td></tr>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '<tr>';
                            echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>City </b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['fatherCity'])!=""){
                                echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['fatherCity']),40,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>City</b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['motherCity'])!=""){
                            echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['motherCity']),40,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }

                            echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>City </b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['guardCity'])!=""){
                                echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['guardCity']),40,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '</tr>';
                            echo '<tr>';    
                            echo '<td class="contenttab_internal_rows" width="10%"><nobr><b>Pin Code</b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['fatherPinCode'])!=""){
                                echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['fatherPinCode']),10,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Pin Code </b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['motherPinCode'])!=""){
                                echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['motherPinCode']),10,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Pin Code </b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['guardianPinCode'])!=""){
                                echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['guardianPinCode']),10,"<br>").'</td></tr>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                        
                        /*echo '<tr>';    
                            echo '<td class="contenttab_internal_rows" width="10%"><nobr><b>User Name : </b></nobr></td>
                            <td width="10%">'.strip_slashes($studentInformationArray[$i]['fatherUserName']).'</td>
                            <td class="contenttab_internal_rows" width="10%"><nobr><b>User Name : </b></nobr></td>
                            <td width="10%">'.strip_slashes($studentInformationArray[$i]['motherUserName']).'</td>
                            <td class="contenttab_internal_rows" width="10%"><nobr><b>User Name : </b></nobr></td>
                            <td width="10%">'.strip_slashes($studentInformationArray[$i]['guardianUserName']).'</td></tr>';*/
                       
                            }
                        
                        }
                        else {
                            echo '<tr><td colspan="5" align="center">No record found</td></tr>';
                        }
                        ?>
                        </table>
                        </div>
                        
                        <div class="dhtmlgoodies_aTab" style="overflow:auto">
                        <table width="100%" border="0" cellspacing="5px" cellpadding="1px">
                        <?php //DISPLAYS ALL RECORDS 
                        require_once($FE . "/Library/common.inc.php");
                        $studentCount = count($studentInformationArray);
                        $instituteName = $sessionHandler->getSessionVariable('InstituteName');
                        
                        if($studentCount >0 && is_array($studentInformationArray) ) {
                         
                            for($i=0; $i<1; $i++ ) {
                        echo '<tr>';    
                            echo '<td class="contenttab_internal_rows" width="15%" valign="top"><nobr><b>University</b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['universityName'])!=""){
                                echo '<td class="contenttab_internal_rows1" align="left" width="15%" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['universityName']),50,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Institute </b></nobr></td>
                                <td class="contenttab_internal_rows1" align="left" width="25%" valign="top"><b>:</b>&nbsp;&nbsp;'.$instituteName.'</td></tr>';
                            echo '<tr>';    
                            echo '<td class="contenttab_internal_rows" width="15%" valign="top"><nobr><b>Degree </b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['degreeName'])!=""){
                                echo '<td class="contenttab_internal_rows1" align="left" width="15%" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['degreeName']),50,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Branch </b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['branchName'])!=""){
                                echo '<td class="contenttab_internal_rows1" align="left" width="25%" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['branchName']),50,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '</tr>';
                            echo '<tr>';    
                            echo '<td class="contenttab_internal_rows" width="15%" valign="top"><nobr><b>Batch</b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['batchName'])!=""){
                                echo '<td class="contenttab_internal_rows1" align="left" width="15%" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['batchName']),50,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Current Study Period  </b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['periodName'])!=""){
                                echo '<td class="contenttab_internal_rows1" align="left" width="25%" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['periodName']),50,"<br>").'</td>';    
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '</tr>';
                            echo '<tr>';    
                            echo '<td class="contenttab_internal_rows" width="15%" valign="top"><nobr><b>Univ. RNo.</b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['universityRollNo'])!=""){
                                echo '<td class="contenttab_internal_rows1" align="left" width="15%" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['universityRollNo']),50,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '<td class="contenttab_internal_rows" width="15%" valign="top"><nobr><b>Inst. RNo. </b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['rollNo'])!=""){        
                                echo '<td class="contenttab_internal_rows1" align="left" width="15%" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['rollNo']),50,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '</tr>';
                            echo '<tr>';    
                            echo '<td class="contenttab_internal_rows" width="15%" valign="top"><nobr><b>Univ. Reg. No. : </b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['universityRegNo'])!=""){        
                                echo '<td class="contenttab_internal_rows1" align="left" width="15%" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['universityRegNo']),40,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '<td class="contenttab_internal_rows" width="15%" valign="top"><nobr><b>Inst. Reg. No. : </b></nobr></td>';
                            if (strip_slashes($studentInformationArray[$i]['universityRegNo'])!=""){        
                                echo '<td class="contenttab_internal_rows1" align="left" width="15%" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['regNo']),40,"<br>").'</td>';
                            }
                            else {
                                echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                            }
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td height=12px"></td>';
                            echo '</tr>';
                            echo '<tr>
                             <td valign="top" colspan="5"><div id="results">';
                                    ?>
                            </div>
                            </td>
                        </tr> 
                        <?php                                 
                            }    
                        }
                        else {
                            echo '<tr><td colspan="5" align="center">No record found</td></tr>';
                        }
                            ?>
                        
                        </table>
                        </div>
                        
                        <div class="dhtmlgoodies_aTab" style="overflow:auto">
                            <table width="100%" border="0" cellspacing="5px" cellpadding="1px"> 
                                <?php //DISPLAYS ALL RECORDS 
                                require_once($FE . "/Library/common.inc.php");
                                $studentCount = count($studentInformationArray);
                                
                                if($studentCount >0 && is_array($studentInformationArray) ) {
                                 
                                    for($i=0; $i<1; $i++ ) {
                                    echo '<tr>';    
                                    echo '<td class="contenttab_internal_rows" width="15%" valign="top"><nobr><b>Quota</b></nobr></td>';
                                    if (strip_slashes($studentInformationArray[$i]['quotaName'])!=""){        
                                    echo '<td class="contenttab_internal_rows1" align="left" width="85%" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['quotaName']),50,"<br>").'</td></tr>';
                                    }
                                    else {
                                        echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                    }
                                    echo '<tr>';    
                                    echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Date of Admission</b></nobr></td>';
                                    if (strip_slashes($studentInformationArray[$i]['dateOfAdmission'])!=""){        
                                        echo '<td class="contenttab_internal_rows1" align="left" width="15%" valign="top"><b>:</b>&nbsp;&nbsp;'.UtilityManager::formatDate($studentInformationArray[$i]['dateOfAdmission']).'</td>';
                                    }
                                    else {
                                        echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                    }
                                    echo '</tr>';
                                    echo '<tr>';    
                                    echo '<td class="contenttab_internal_rows" valign="top" ><nobr><b>I-Card No.</b></nobr></td>';
                                    if (strip_slashes($studentInformationArray[$i]['icardNumber'])!=""){            
                                        echo '<td class="contenttab_internal_rows1" align="left" width="15%" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['icardNumber']),50,"<br>").'</td>';   
                                    }
                                    else {
                                        echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                    }
                                    echo '</tr>';
                                    echo '<tr>';    
                                    echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Hostel Name</b></nobr></td>';
                                    if (strip_slashes($studentInformationArray[$i]['hostelName'])!=""){
                                        echo '<td class="contenttab_internal_rows1" align="left" width="15%" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['hostelName']),50,"<br>").'</td>'; 
                                    }
                                    else {
                                        echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                    }
                                    echo '</tr>';
                                    echo '<tr>';
                                    echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Hostel Room No.</b></nobr></td>';
                                    if (strip_slashes($studentInformationArray[$i]['roomName'])!=""){
                                        echo '<td class="contenttab_internal_rows1" align="left" width="25%" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['roomName']),50,"<br>").'</td>';
                                    }
                                    else {
                                        echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                    }
                                    echo '</tr>';
                                    echo '<tr>';
                                    echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Expected Checkout Date</b></nobr></td>';
                                    if (strip_slashes($studentInformationArray[$i]['roomName'])!="" and strip_slashes($studentInformationArray[$i]['hostelName'])!=""){
                                        echo '<td class="contenttab_internal_rows1" align="left" width="25%" valign="top"><b>:</b>&nbsp;&nbsp;';
                                         $studentId = $studentInformationArray[0]['studentId'];
                                         if($studentId=='') {
                                           $studentId='0';  
                                         }
                                         $hostelDateArr=CommonQueryManager::getInstance()->getExpectedCheckOutDate(" WHERE studentId='$studentId'");
                                         if($hostelDateArr[0]['possibleDateOfCheckOut']!='0000-00-00' and $hostelDateArr[0]['possibleDateOfCheckOut']!=''){
                                          echo UtilityManager::formatDate($hostelDateArr[0]['possibleDateOfCheckOut']);
                                         }
                                        else{                                                                                               
                                          echo NOT_APPLICABLE_STRING;
                                        }
                                        echo'</td>';
                                    }
                                    else {
                                        echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                    }
                                    echo '</tr>';
                                    echo '<tr>';    
                                    echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Bus Route No.</b></nobr></td>';
                                    if (strip_slashes($studentInformationArray[$i]['routeName'])!=""){
                                        echo '<td class="contenttab_internal_rows1" align="left" width="15%" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['routeName']),50,"<br>").'</td>';
                                    }
                                    else {
                                        echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                    }
                                    echo '</tr>';
                                    echo '<tr>';    
                                    echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Bus Stop No.</b></nobr></td>';
                                    if (strip_slashes($studentInformationArray[$i]['stopName'])!=""){
                                    echo '<td class="contenttab_internal_rows1" align="left" width="15%" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['stopName']),50,"<br>").'</td></tr>';
                                    }
                                    else {
                                        echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                      }
                                     }
                                    }
                                    else {
                                        echo '<tr><td colspan="5" align="center">No record found</td></tr>';
                                    }

                                    echo '<tr >';
                                    echo '<td colspan="2">';
                                    echo '<table width="95%" border="0" cellspacing="1" cellpadding="1" align="center">';
                                    echo '<tr class="row1">';
                                    echo '<td class="contenttab_internal_rows1" valign="middle" height="25" width="10%" align="left"><nobr><b>Class</b></nobr></td>';
                                    echo '<td class="contenttab_internal_rows1" valign="middle" width="10%" align="left"><nobr><b>Roll No.</b></nobr></td>';
                                    echo '<td class="contenttab_internal_rows1" valign="middle" width="10%" align="left"><nobr><b>Session</b></nobr></td>';
                                    echo '<td class="contenttab_internal_rows1" valign="middle" width="15%" align="left"><nobr><b>School/Institute/University Last Attended</b></nobr></td>';
                                    echo '<td class="contenttab_internal_rows1" valign="middle" width="10%" align="left"><nobr><b>Name of Board/University</b></nobr></td>';
                                    echo '<td class="contenttab_internal_rows1" valign="middle" width="10%" align="left"><nobr><b>Education Stream</b></nobr></td>';
                                    echo '<td class="contenttab_internal_rows1" valign="middle" align="right" width="10%"><nobr><b>Marks Obtained</b></nobr></td>';
                                    echo '<td class="contenttab_internal_rows1" valign="middle" align="right" width="10%"><nobr><b>Max. Marks</b></nobr></td>';
                                    echo '<td class="contenttab_internal_rows1" valign="middle" align="right" width="10%"><nobr><b>%age</b></nobr></td>';
                                    
                                    global $classResults;
                                    if(isset($classResults) && is_array($classResults)) {
                                        $count = count($classResults);
                                        foreach($classResults as $key=>$value) {
                                            echo '<tr class="row0">
                                                <td valign="left" nowrap align="left">'.$value.'</td>
                                                <td valign="left" align="left">'.parseOutput($rollArr[$key]).'</td>
                                                <td valign="left" align="left">'.parseOutput($sessionArr[$key]).'</td>
                                                <td valign="left" align="left">'.parseOutput($instituteArr[$key]).'</td>
                                                <td valign="left" align="left">'.parseOutput($boardArr[$key]).'</td>  
                                                <td valign="left" align="left">'.parseOutput($educationArr[$key]).'</td>
                                                <td valign="middle" align="right">'.parseOutput($marksArr[$key]).'</td>
                                                <td valign="middle" align="right">'.parseOutput($maxMarksArr[$key]).'</td>
                                                <td valign="middle" align="right">'.parseOutput($perArr[$key]).'</td>
                                                </tr>';
                                            }
                                            echo "<input type='hidden' id='countRecord' name='countRecord' value='".$count."'/>" ;
                                     }
                                     echo '</tr></table></td></tr>';
                              //if(sizeof($getStudentRegistrationInfo)>0){ // Will Display Only If Student Is Registered
                                     echo '<tr>';
                                     echo '<td colspan="2">';
                                   echo   '<table width="100%" border="0" cellspacing="5px" cellpadding="1px">
                                            <tr>
                                                <td>';
                                                require_once(TEMPLATES_PATH. "/studentLastRegistration.php");
                                   echo '</td>
                                            </tr>
                                           </table>
                                           </td></tr>';
               //} //end of if
                ?>
                        
                            </table>
                        </div>

                        <div class="dhtmlgoodies_aTab" style="overflow:auto">
                            <table width="100%" border="0" cellspacing="5px" cellpadding="1px"> 
                                <tr>
                                        <td align="right" valign="top" class="padding">
                                                <b><font size="2">Subject Code:</font></b>
                                        </td>
                <!--                        <form action="" method="post" name="searchForm" onsubmit="getResource(document.getElementById('semesterDetail').value);return false;"> -->
                                        <td>
                                            <select size="1"  name="subjects" id="subjects" class="inputbox1" style="width:100px;">
                                            <option value="">Select</option>
                                              <?php
                                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                  //echo HtmlFunctions::getInstance()->getSubjectData($REQUEST_DATA['subjects']==''? $subjectRecordArray[0]['subjectId'] : $REQUEST_DATA['subjects'] );
                                                  echo HtmlFunctions::getInstance()->getStudentAllocatedSubjectData($sessionHandler->getSessionVariable('StudentId'),$classId);
                                              ?>
                                            </select>
                                        </td>
                                        <td  valign="top" colspan="" class="padding" align="right">
                                                <b><font size="2">Type:</font></b>
                                        </td>
                                        <td>
                                            <select size="1"  name="category" id="category" class="inputbox1" style="width:140px;">
                                             <option value="">Select</option>
                                              <?php
                                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                echo HtmlFunctions::getInstance()->getResourceCategoryData();
                                              ?>
                                            </select>
                                        </td>
                                        <td align="right"  valign="bottom"  rowspan="2" >
                                            <input type="text" name="searchbox" id = "searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />&nbsp;
                                            <input type="image"  name="submit" src="<?php echo IMG_HTTP_PATH;?>/search.gif" title="Search"   style="margin-bottom: -5px;" onClick="getResource(document.getElementById('semesterDetail').value);return false;"/>&nbsp;
                                        </td>
 <!-- </form> -->
                                </tr>
                                <tr>    
                                    <td  valign="top" colspan="1" class="padding" align="right">
                                        <b><font size="2">Date:</font></b>
                                    </td>
                                    <td >
                                        <?php
                                            require_once(BL_PATH.'/HtmlFunctions.inc.php'); 
                                            echo HtmlFunctions::getInstance()->datePicker('postDate');
                                        ?>
                                        <input type="button" value="Reset" width="2%" onclick="resetDate();" />
                                    </td>
                                    <td  valign="top" colspan="1" class="padding" align="right">
                                        <b><font size="2">Teacher:</font></b>
                                    </td>
                                    <td>
                                        <select size="1"  name="teachers" id="teachers" tabindex="1" class="inputbox1" style="width:140px;">
                                        <option value="">Select</option>
                                        <?php
                                            require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                            echo HtmlFunctions::getInstance()->getTeacherDataAllocatedToClass($classId);
                                        ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top" colspan="5">
                                        <div id="resultResource"></div>
                                    </td>
                                </tr>
                     </table>                 
                   </div>

                    <div class="dhtmlgoodies_aTab" style="overflow:auto">
                        <table width="100%" border="0" cellspacing="5px" cellpadding="1px"> 
                        <tr>
                            <td valign="top"><div id="transferredResult">
                                </div>
                            </td>
                        </tr>
                      </table>                 
                   </div>
                   <div class="dhtmlgoodies_aTab" style="overflow:auto">
                           <table width="100%" border="0" cellspacing="2" cellpadding="2">
                           <tr>
                               <td><div id="offenceResultsDiv"></div></td>
                           </tr>
                           </table>                 
                       </div>
                    <div class="dhtmlgoodies_aTab" style="overflow:auto">
                        <table width="100%" border="0" cellspacing="5" cellpadding="5">
                        <?php 
                        require_once($FE . "/Library/common.inc.php");
                        $studentCount = count($studentInformationArray);
                        
                        if($studentCount >0 && is_array($studentInformationArray) ) { 
                           for($i=0; $i<$studentCount; $i++ ) {?>
                            <tr>
                                <td class="contenttab_internal_rows"><nobr><b>Religion</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><input type="text" id="religion" name="religion" class="inputbox"   value="<?php echo $studentInformationArray[$i]['religion']?>" maxlength="50" />
                                </td>
                                <td class="contenttab_internal_rows"><nobr><b>Hobbies</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><input type="text" id="hobbies" name="hobbies" class="inputbox" value="<?php echo $studentInformationArray[$i]['hobbies']?>" maxlength="60" />
                                </td>
                            </tr>
                            <tr>
                                <td class="contenttab_internal_rows"><nobr><b>Lang. Read</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><input type="text" id="toRead" name="toRead" class="inputbox"   value="<?php echo $studentInformationArray[$i]['languageRead']?>" maxlength="30" />
                                </td>
                                <td class="contenttab_internal_rows"><nobr><b>Lang. Write</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><input type="text" id="toWrite" name="toWrite" class="inputbox"   value="<?php echo $studentInformationArray[$i]['languageWrite']?>" maxlength="60" />
                                </td>
                                <td class="contenttab_internal_rows"><nobr><b>Lang. Speak</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><input type="text" id="toSpeak" name="toSpeak" class="inputbox"   value="<?php echo $studentInformationArray[$i]['languageSpeak']?>" maxlength="60" />
                                </td>
                            </tr>
                            <tr>
                                <td class="contenttab_internal_rows"><nobr><b>Education</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><select size="1" class="selectfield" name="education" id="education" onchange="getEducation();" <?php echo $disableClass?>>
                                    <option value="">Select</option>
                                    <option value="0" <?php if($studentInformationArray[$i]['education']==0) echo "SELECTED='SELECTED'";?>>Self-financed</option> 
                                    <option value="1" <?php if($studentInformationArray[$i]['education']==1) echo "SELECTED='SELECTED'";?>>Educational Loan</option>
                                </select>
                                </td>
                                <td class="contenttab_internal_rows"><nobr><b>Bank Name & Address</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><input type="text" id="bankName" name="bankName" class="inputbox" maxlength="250" value="<?php if($studentInformationArray[$i]['education']==1) {
                                    echo $studentInformationArray[$i]['bankNameAddress'];
                                }
                                else {?>"
                                    disabled="disabled"
                                "<?php }?>"/></td>
                                <td class="contenttab_internal_rows"><nobr><b>Loan Amount</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><input type="text" id="loanAmount" name="loanAmount" class="inputbox" maxlength="7" value="<?php if($studentInformationArray[$i]['education']==1) {
                                    echo $studentInformationArray[$i]['loanAmount'];
                                }
                                else {?>"
                                    disabled="disabled"
                                "<?php }?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="contenttab_internal_rows"><nobr><b>Medical Attention</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><select size="1" class="selectfield" name="medicalAttention" id="medicalAttention" onchange="getMedicalAttention();" <?php echo $disableClass?>>
                                    <option value="">Select</option>
                                    <option value="1" <?php if($studentInformationArray[$i]['ailment']==1) echo "SELECTED='SELECTED'";?>>Yes</option> 
                                    <option value="0" <?php if($studentInformationArray[$i]['ailment']==0) echo "SELECTED='SELECTED'";?>>No</option>
                                </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="contenttab_internal_rows"><nobr><b>Nature of Ailment</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><input type="text" id="natureAilment" name="natureAilment" class="inputbox" maxlength="20" value="<?php if($studentInformationArray[$i]['ailment']==1) {
                                    echo $studentInformationArray[$i]['otherAilment'];
                                } else {?>"
                                    disabled = "disabled"
                                "<?php }?>"/>
                                </td>
                                <td class="contenttab_internal_rows"><nobr><b>Family Ailment</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top">
                                <?php if($studentInformationArray[$i]['ailment']==1) { ?>
                                    <select multiple size="3" class="selectfield" name="familyAilment[]" id="familyAilment" <?php echo $disableClass?>>
                                    <?php
                                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                      echo HtmlFunctions::getInstance()->getFamilyAilment($studentInformationArray[$i]['familyAilment']);
                                    ?>
                                    </select>
                                <?php } else {?>
                                <select multiple size="3" class="selectfield" name="familyAilment[]" id="familyAilment" disabled="disabled" <?php echo $disableClass?>>
                                    <?php
                                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                      echo HtmlFunctions::getInstance()->getFamilyAilment();
                                    ?>
                                    </select>
                                <?php } ?>
                                </td>
                                <td class="contenttab_internal_rows"><nobr><b>Other Ailment</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><input type="text" id="otherAilment" name="otherAilment" class="inputbox" maxlength="20" value="<?php if($studentInformationArray[$i]['ailment']==1) {
                                    echo $studentInformationArray[$i]['familyOtherAilment'];
                                } else {?>"
                                    disabled="disabled"
                                "<?php }?>"/>
                                </td>
                            </tr>

                            <tr>
                                <td class="contenttab_internal_rows"><nobr><b>Coaching Taken</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top">
                                    <select class="selectfield" name="coachingCenter" id="coachingCenter" onchange="getCoaching();"<?php echo $disableClass?>>
                                    <option value="">Select</option>
                                    <?php
                                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                      echo HtmlFunctions::getInstance()->getCoachingCentersData($studentInformationArray[$i]['coachingName']);
                                    ?>
                                    </select>
                                </td>
                                <td class="contenttab_internal_rows"><nobr><b>Branch Manager Name</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><input type="text" id="coachingManager" name="coachingManager" class="inputbox" maxlength="50" value="<?php if($studentInformationArray[$i]['coachingName']!=0) {
                                    echo $studentInformationArray[$i]['coachingManagerName'];
                                } else {?>"
                                    disabled = "disabled"
                                "<?php }?>"/>
                                </td>
                                <td class="contenttab_internal_rows"><nobr><b>Address & Phone No.</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><input type="text" id="address" name="address" class="inputbox" maxlength="250" value="<?php if($studentInformationArray[$i]['coachingName']==1) {
                                    echo $studentInformationArray[$i]['coachingAddress'];
                                } else {?>"
                                    disabled="disabled"
                                "<?php }?>"/>
                                </td>
                            </tr>

                            <tr>
                                <td class="contenttab_internal_rows"><nobr><b>Work Experience</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><select size="1" class="selectfield" name="workExperience" id="workExperience" onchange="getWorkExperience();" <?php echo $disableClass?>>
                                    <option value="">Select</option>
                                    <option value="1" <?php if($studentInformationArray[$i]['workExperience']==1) echo "SELECTED='SELECTED'";?>>Yes</option> 
                                    <option value="0" <?php if($studentInformationArray[$i]['workExperience']==0) echo "SELECTED='SELECTED'";?>>No</option>
                                </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="contenttab_internal_rows"><nobr><b>Area/Department</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><input type="text" id="department" name="department" class="inputbox" maxlength="100" value="<?php if($studentInformationArray[$i]['workExperience']==1) {
                                    echo $studentInformationArray[$i]['departmentName'];
                                } else {?>"
                                    disabled = "disabled"
                                "<?php }?>"/>
                                </td>
                                <td class="contenttab_internal_rows"><nobr><b>Organization</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><input type="text" id="organization" name="organization" class="inputbox" maxlength="100" value="<?php if($studentInformationArray[$i]['workExperience']==1) {
                                    echo $studentInformationArray[$i]['organization'];
                                } else {?>"
                                    disabled="disabled"
                                "<?php }?>"/>
                                </td>
                                <td class="contenttab_internal_rows"><nobr><b>Place</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><input type="text" id="place" name="place" class="inputbox" maxlength="100" value="<?php if($studentInformationArray[$i]['workExperience']==1) {
                                    echo $studentInformationArray[$i]['place'];
                                } else {?>"
                                    disabled="disabled"
                                "<?php }?>"/>
                                </td>
                            </tr>

                            
                            <tr>
                                <td class="contenttab_internal_rows" colspan="4"><nobr><b><u>Payment Details of Application Fee</u>&nbsp;:</b></nobr></td>
                            </tr> 
                            <tr>
                                <td class="contenttab_internal_rows" colspan="9" width="100%">
                                 <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                  <tr>
                                   <td>
                                   <div id="tableDiv" style="height:150px;overflow:auto;">
                                    <table width="100%" border="0" cellspacing="2" cellpadding="0" id="anyid">
                                     <tbody id="anyidBody">
                                      <tr class="rowheading">
                                       <td width="3%" class="contenttab_internal_rows"><b>#</b></td>
                                       <td width="23%" class="contenttab_internal_rows"><b>DD Number</b></td>
                                       <td width="10%" class="contenttab_internal_rows"><b>Date</b></td>
                                       <td width="5%" class="contenttab_internal_rows"><b>Amount</b></td>
                                       <td width="25%" class="contenttab_internal_rows"><b>Bank</b></td>
                                       <td width="5%" class="contenttab_internal_rows1" align="center"><b>Delete</b></td>
                                      </tr>
                                      <?php
                                       if(is_array($studentPaymanetDetailsArray) and count($studentPaymanetDetailsArray)>0){
                                           $rowCnt=1;
                                           foreach($studentPaymanetDetailsArray as $key=>$value){
                                              $bg = $bg=='class="row0"' ? 'class="row1"' : 'class="row0"';   
                                              echo '<tr '.$bg.' id="row'.$rowCnt.'">';
                                              echo '<td name="srNo" align="left">'.$rowCnt.'</td>';
                                              echo '<td align="left">
                                                        <input type="text" maxlength="100" style="width:200px;" class="inputbox" name="ddNo[]" id="ddNo'.$rowCnt.'" value="'.trim($studentPaymanetDetailsArray[$key]['ddNo']).'" />
                                                   </td>';
                                              echo '<td align="left">
                                                     <input id="fromDate'.$rowCnt.'" name="fromDate[]" class="inputBox" readonly="true" value="'.trim($studentPaymanetDetailsArray[$key]['ddDate']).'" size="8" type="text"><input id="calImg" name="calImg" title="Select Date" src="'.STORAGE_HTTP_PATH.'/Images/calendar.gif" onclick="return showCalendar(\'fromDate'.$rowCnt.'\',\'%Y-%m-%d\', \'24\', true);" type="image">
                                                    </td>';
                                              echo '<td align="left">
                                                        <input type="text" maxlength="8" style="width:100px;" class="inputbox" name="ddAmt[]" id="ddAmt'.$rowCnt.'" value="'.trim($studentPaymanetDetailsArray[$key]['ddAmount']).'" />
                                                   </td>';
                                              echo '<td align="left">
                                                        <input type="text" maxlength="100" style="width:250px;" class="inputbox" name="ddBank[]" id="ddBank'.$rowCnt.'" value="'.trim($studentPaymanetDetailsArray[$key]['ddBank']).'" />
                                                   </td>';
                                             echo '<td align="center">
                                                        <a href=\'javascript:deleteRow("'.$rowCnt.'~0")\' style="cursor: pointer;" title="Delete" class="htmlElement" id="rd">X</a>
                                                    </td>';
                                              echo '</tr>';
                                              $rowCnt++;
                                           }
                                       }
                                      ?>
                                      </tbody>
                                     </table>
                                    </div
                                    </td>
                                    </tr>
                                    <tr>
                                     <td>
                                      <input type="hidden" name="deleteFlag" id="deleteFlag" value="" />
                                       <a href="javascript:addOneRow(1);" title="Add Row"><b><nobr>Add More</b></a>
                                      </td>
                                     </tr>
                                 </table>
                                </td>
                            </tr>
                            <tr>
                                 <td  align="center" style="padding-right:5px" valign="bottom" colspan="9">       
                                  <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateAll(this.form);"/>
                                </td>
                            </tr>
                                <?php }} ?>
                      </table>                 
                   </div>
                   <div class="dhtmlgoodies_aTab" style="overflow:auto">
                    <div id="uploadDocsContainerDiv">
                     <?php
                      require_once(BL_PATH.'/Student/getStudentAttachedDocuments.php');
                     ?>
                      </div> 
                   </div>
                   
                   <div class="dhtmlgoodies_aTab">
                       <table width="10%" border="0" cellspacing="5px" cellpadding="5px" align="center" style="display:none"> 
                          <tr>
                             <td width="1%" class="contenttab_internal_rows"><nobr><strong>Subject</strong></nobr></td>
                             <td width="1%" class="contenttab_internal_rows"><nobr><strong>:</strong></nobr></td>
                             <td width="1%" class="contenttab_internal_rows">
                              <nobr>
                                 <select size="1" class="inputbox1" name="attSubjectId" style="width:290px" id="attSubjectId">
                                 </select>
                              </nobr>
                            </td>
                            <td width="1%" class="contenttab_internal_rows" style="padding-left:20px">
                             <input type="image" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif"  onClick="return validateRegister();"/>  
                            </td>
                          </tr>  
                       </table>
                        <div style="overflow:auto; width:980px; height:370px; vertical-align:top;">
                           <div id="attendanceRegister" style="width:100%; vertical-align:top;"></div>
                        </div>
                   </div>
                   
                   <div class="dhtmlgoodies_aTab" style="vertical-align:top;"> 
                          <div style="overflow:auto; width:980px; height:370px; vertical-align:top;">
                              <table width="100%" border="0" cellspacing="5px" cellpadding="2px">
                                <tr>
                                    <td><div id="finalGradesDiv"></div></td>
                                </tr>
                                </table>
                            </div>
                   </div>
                   
                   <div class="dhtmlgoodies_aTab" style="vertical-align:top;"> 
                          <div style="overflow:auto; width:980px; height:370px; vertical-align:top;">
                              <table width="100%" border="0" cellspacing="5px" cellpadding="2px">
                                <tr>
                                    <td><div id="studentFineDiv"></div></td>
                                </tr>
                                <tr>
                                    <td><div id="studentTotalFineDiv"></div></td>
                                </tr>
                                <tr>
                                    <td align="right"><input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/print.gif" onclick="return studentFineReceipt();return false;" tabindex="33"/></td>&nbsp;
								</tr>
                                </table>
                            </div>
                   </div>
                   
                   <div class="dhtmlgoodies_aTab" style="vertical-align:top;"> 
                          <div style="overflow:auto; width:980px; height:370px; vertical-align:top;">
                          <?php
                            $pollArray=array(1=>'Adorable Teacher',
                                             2=>'Dedicated Teacher',
                                             3=>'Interactive Teacher',
                                             4=>'Ever-smiling Teacher',
                                             5=>'Charismatic Teacher (based on personality)');                 
                            
                          ?>
                              <table width="100%" border="0" cellspacing="5px" cellpadding="2px">
                                <tr>
                                  <td>
                                    <div id="studentPollDiv">
                                         <table width="20%" align="center" border="0" cellspacing="5px" cellpadding="2px" style="display:none" id='divPoll1'>
                                            <tr>
                                              <td class="contenttab_internal_rows" nowrap height="100px" valign="bottom">                                                     <center><strong>
                                                 <span style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 14px; color: blue;">    
                                                     Your response for the poll has already been saved.<br> 
                                                     This will help us in encouraging excellence.
                                                 </span>    
                                                 </strong>
                                                 </center>
                                              </td>
                                            </tr>
                                         </table>     
                                    	 <table width="20%" align="left" border="0" cellspacing="5px" cellpadding="2px" id='divPoll2'>  
                                    			<tr>
                                                  <td class="contenttab_internal_rows" valign="top" colspan="9" align="center">
                                                  
                                                     <strong>Poll to finalyze awards for Teacher's Day Ceremony</strong>
                                                  </td>
                                                </tr>
                                                <tr><td height="10px"></td></tr>
                                                <?php
                                                  for($i=1;$i<=count($pollArray); $i++) {
                                                     $bg = $bg =='trow1' ? 'trow0' : 'trow1'; 
                                                ?>
                                    			    <tr class="<?php echo $bg; ?>">
                                    				    <td class="contenttab_internal_rows" valign="top" nowrap><strong><?php echo $i; ?>.</strong></td>
                                    				    <td class="contenttab_internal_rows" valign="top" nowrap><strong><?php echo $pollArray[$i]; ?></strong></td>
                                    				    <td class="contenttab_internal_rows" valign="top" nowrap><strong>:</strong></td>
													    <td width="79%" class="contenttab_internal_rows" nowrap>
														    <select name="employeeId<?php echo $i; ?>" id="employeeId<?php echo $i; ?>"  class="selectfield" style="width:320px">
													    	    <option value="">Select</option>
													            <?php
													 			    require_once(BL_PATH.'/HtmlFunctions.inc.php');
													 			    echo HtmlFunctions::getInstance()->getTeacherList();
													   		    ?>
													 	    </select>
													    </td>	
                                    			    </tr>
                                    			<?php
                                                  }
                                                ?>
                                                <tr><td height="20px"></td></tr>
                                    			<tr>
                                    				<td class="contenttab_internal_rows" align="center" valign="bottom" colspan="9">
                                                       <center>
                                    					<input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/send.gif"  onClick="return refreshStudentPollData(this.form);"/>
                                                       </center>
                                    				</td>
                                    			</tr>
                                    		</table>	
                                    	</div>
                                    </td>
                                </tr>                            
                              </table>
                            </div>
                   </div>  

<div class="dhtmlgoodies_aTab" style="vertical-align:top;"> 
                          <div style="overflow:auto; width:980px; height:370px; vertical-align:top;">
                              <table width="100%" border="0" cellspacing="2px" cellpadding="2px">
                                <tr>
                                    <td><div id="feeDataDiv"></div></td>
                                </tr>
                               
                                </table>
                            </div>
                   </div>          
            </div>

 

  <script type="text/javascript">
   initTabs('dhtmlgoodies_tabView1',
            Array('Personal Details','Parents Details','Subject Details','Administrative Details','Resource Detail','Final Result','Offense/Achv','Misc. Info','Documents','Attendance Register','Grade Info','Fine','Poll','Fees'),0,990,370,
            Array(false,false,false,false,false,false,false,false,false,false,false,false,false,false));
  </script>  
   <?php 
            $show=-1;
                            if ($sessionHandler->getSessionVariable('PERSONAL_INFO')==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_0').style.display='none'; </script>"; 
                            }
                               else if($show==-1) {
                                 $show=0;
                               }
                             
                             if ($sessionHandler->getSessionVariable('PARENTS_INFO')==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_1').style.display='none'; </script>"; 
                             }
                              else if($show==-1) {
                                 $show=1;
                               }
                            
                              if ($sessionHandler->getSessionVariable('COURSE')==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_2').style.display='none'; </script>"; 
                              }
                              else if($show==-1) {
                                 $show=2;
                               }
                            
                             if ($sessionHandler->getSessionVariable('ADMINISTRATIVE')==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_3').style.display='none'; </script>"; 
                             }
                               else if($show==-1) {
                                 $show=3;
                               }
                             
                         /*    if ($sessionHandler->getSessionVariable('SCHEDULE')==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_4').style.display='none'; </script>"; 
                             }
                              else if($show==-1) {
                                 $show=4;
                               }
                                 
                             if ($sessionHandler->getSessionVariable('MARKS')==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_5').style.display='none'; </script>"; 
                             }
                              else if($show==-1) {
                                 $show=5;
                               }
                                 
                             if ($sessionHandler->getSessionVariable('ATTENDANCE')==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_6').style.display='none'; </script>"; 
                             }
                               else if($show==-1) {
                                 $show=6;
                               }
                                 
                             if ($sessionHandler->getSessionVariable('FEES')==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_7').style.display='none'; </script>"; 
                             }
                             else  if($show==-1) {
                                 $show=7;
                               }       */
                               
                              if ($sessionHandler->getSessionVariable('RESOURCE')==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_4').style.display='none'; </script>"; 
                              }
                              else if($show==-1) {
                                 $show=4;
                               }
                              
                             if ($sessionHandler->getSessionVariable('FINAL_RESULT')==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_5').style.display='none'; </script>"; 
                             }
                            else  if($show==-1) {
                                 $show=5;
                               }
                               
                              if ($sessionHandler->getSessionVariable('OFFENSE')==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_6').style.display='none'; </script>"; 
                              }
                            else  if($show==-1) {
                                 $show=6;
                            }
                            if ($sessionHandler->getSessionVariable('MISC_INFO')==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_7').style.display='none'; </script>"; 
                            }
                            else  if($show==-1) {
                                 $show=7;
                            }
                            if ($sessionHandler->getSessionVariable('STUDENT_DOCUMENTS')==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_8').style.display='none'; </script>"; 
                            }
                            else  if($show==-1) {
                               $show=8;
                            }    
                            if ($sessionHandler->getSessionVariable('ATTENDANCE_REGISTER_INFO')==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_9').style.display='none'; </script>"; 
                            }
                            else if($show==-1) {
                               $show=9;
                            }
                            
                            if ($sessionHandler->getSessionVariable('GRADE_INFO')==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_10').style.display='none'; </script>"; 
                            }
                            else if($show==-1) {
                               $show=10;
                            }
                            
                            if ($sessionHandler->getSessionVariable('FINE_INFO')==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_11').style.display='none'; </script>"; 
                            }
                            else if($show==-1) {
                               $show=11;
                            }
                            
                            if ($sessionHandler->getSessionVariable('POLL_INFO')==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_12').style.display='none'; </script>"; 
                            }
                            else if($show==-1) {
                               $show=12;
                            }
                         
				 if ($sessionHandler->getSessionVariable('FEES')==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_13').style.display='none'; </script>"; 
                             }
                             else  if($show==-1) {
                                 $show=13;
                               }   

                            echo "<script>showTab('dhtmlgoodies_tabView1',".$show.");</script>";             
                       ?>           
             </td>
          </tr>
                
          </table>
  
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>
 </form>
<?php 
// $History: studentInformationContents.php $
//
//*****************  Version 18  *****************
//User: Jaineesh     Date: 10/24/09   Time: 3:50p
//Updated in $/LeapCC/Templates/Student
//fixed bug nos. 0001883, 0001877 and modification in query
//getStudentCourseResourceList() to get courses of current class and make
//searchable course
//
//*****************  Version 17  *****************
//User: Jaineesh     Date: 10/23/09   Time: 6:32p
//Updated in $/LeapCC/Templates/Student
//fixed bug nos. 0001871,0001869,0001853,0001873,0001820,0001809,0001808,
//0001805,0001806, 0001876, 0001879, 0001878
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 10/22/09   Time: 6:37p
//Updated in $/LeapCC/Templates/Student
//fixed bug Nos.0001858, 0001872, 0001870, 0001868, 0001867, 0001865,
//0001856, 0001866
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 9/30/09    Time: 10:33a
//Updated in $/LeapCC/Templates/Student
//modified for show Mrs. in mother title
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 9/15/09    Time: 5:53p
//Updated in $/LeapCC/Templates/Student
//fixed bug nos. 0001499, 0001550
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 9/10/09    Time: 6:03p
//Updated in $/LeapCC/Templates/Student
//fixed bug nos.0001505, 0001501, 0001500
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 9/07/09    Time: 6:32p
//Updated in $/LeapCC/Templates/Student
//modification in academic record during student login
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 9/03/09    Time: 10:07a
//Updated in $/LeapCC/Templates/Student
//fixed bug nos.0001389, 0001387, 0001386, 0001380, 0001383 and export to
//excel
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 7/27/09    Time: 3:46p
//Updated in $/LeapCC/Templates/Student
//send tabNumber on totalFunction()
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 6/17/09    Time: 6:05p
//Updated in $/LeapCC/Templates/Student
//modification in student module as per requirement of sachin sir
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 5/28/09    Time: 12:32p
//Updated in $/LeapCC/Templates/Student
//put course resource of student on tab click
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 5/27/09    Time: 3:04p
//Updated in $/LeapCC/Templates/Student
//put offense/achv tab in student info
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 1/13/09    Time: 6:28p
//Updated in $/LeapCC/Templates/Student
//modified for left alignment and giving cell padding, cell spacing 1
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 1/07/09    Time: 5:23p
//Updated in $/LeapCC/Templates/Student
//modified code
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/25/08   Time: 4:41p
//Updated in $/LeapCC/Templates/Student
//modification for paging
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/09/08   Time: 5:45p
//Updated in $/LeapCC/Templates/Student
//modification in code for cc
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Student
//
//*****************  Version 21  *****************
//User: Jaineesh     Date: 9/19/08    Time: 5:20p
//Updated in $/Leap/Source/Templates/Student
//modification for showing student pic through common.inc.php
//
//*****************  Version 20  *****************
//User: Jaineesh     Date: 9/17/08    Time: 5:55p
//Updated in $/Leap/Source/Templates/Student
//
//*****************  Version 19  *****************
//User: Jaineesh     Date: 9/16/08    Time: 7:09p
//Updated in $/Leap/Source/Templates/Student
//fix bug
//
//*****************  Version 18  *****************
//User: Administrator Date: 9/15/08    Time: 10:45a
//Updated in $/Leap/Source/Templates/Student
//

//*****************  Version 17  *****************
//User: Jaineesh     Date: 9/12/08    Time: 7:16p
//Updated in $/Leap/Source/Templates/Student
//modification
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 9/10/08    Time: 7:54p
//Updated in $/Leap/Source/Templates/Student
//fix bugs
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 9/09/08    Time: 7:12p
//Updated in $/Leap/Source/Templates/Student
//modified for blank fields - to show value in blank fields
//
//*****************  Version 14  *****************
//User: Administrator Date: 9/05/08    Time: 7:25p
//Updated in $/Leap/Source/Templates/Student
//bugs fixation
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 9/05/08    Time: 11:29a
//Updated in $/Leap/Source/Templates/Student
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 9/04/08    Time: 7:48p
//Updated in $/Leap/Source/Templates/Student
//fixed the bugs
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 8/25/08    Time: 3:23p
//Updated in $/Leap/Source/Templates/Student
//modified in fields
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 8/16/08    Time: 1:24p
//Updated in $/Leap/Source/Templates/Student
//modified in spacing in student tab
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/13/08    Time: 7:01p
//Updated in $/Leap/Source/Templates/Student
//modified in tab view
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/13/08    Time: 4:24p
//Updated in $/Leap/Source/Templates/Student
//modified in dashboard
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/04/08    Time: 11:33a
//Updated in $/Leap/Source/Templates/Student
//modification for student email
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/02/08    Time: 4:52p
//Updated in $/Leap/Source/Templates/Student
//modification in template
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/24/08    Time: 12:32p
//Updated in $/Leap/Source/Templates/Student
//modified in comments
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/21/08    Time: 6:54p
//Updated in $/Leap/Source/Templates/Student
//stored design template of student
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 7/17/08    Time: 3:00p
//Updated in $/Leap/Source/Templates/Student
//updated with user validations
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 7/16/08    Time: 1:42p
//Updated in $/Leap/Source/Templates/Student
//updated student profile with student marks 
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 7/15/08    Time: 11:19a
//Updated in $/Leap/Source/Templates/Student
//added attendance module
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/12/08    Time: 5:24p
//Updated in $/Leap/Source/Templates/Student
//updated ajax based
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/11/08    Time: 6:46p
//Updated in $/Leap/Source/Templates/Student
//updated student photo module
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/07/08    Time: 12:55p
//Created in $/Leap/Source/Templates/SubjectToClass
//intial checkin
?>
