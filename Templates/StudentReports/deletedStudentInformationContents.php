<?php
//-------------------------------------------------------
// Purpose: to design the layout for assign to subject.
//
// Author : Jaineesh
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
 <!--<form method="POST" name="addForm" id="addForm"  action="<?php echo HTTP_LIB_PATH;?>/Student/fileUpload.php" method="post" enctype="multipart/form-data" style="display:inline">-->
 
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">Deleted Student Information</td>
				<td align="right"><INPUT type="image" alt="back" title="back" src="<?php echo IMG_HTTP_PATH ?>/bigback.gif" border="0" onClick='listPage("listDeletedStudentReport.php");'>&nbsp;&nbsp;&nbsp;</td>
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
                        <td class="contenttab_row1" width="20%"><span class="content_title">Deleted Student Information :</span>
                        </td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" ><div id="dhtmlgoodies_tabView1"  >
					 <?php
                        require_once($FE . "/Library/common.inc.php");
						//$not_applicable_string = "---";
                        $studentCount = count($studentInformationArray);
								if($studentCount >0 && is_array($studentInformationArray) ) {
									echo '<input type="hidden" name="studentId" id="studentId" value="'.$studentInformationArray[0]['studentId'].'" >';
								}

					 ?>
                        <div class="dhtmlgoodies_aTab" style="overflow:auto">
                         
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                        <?php //DISPLAYS ALL RECORDS 
                        require_once($FE . "/Library/common.inc.php");
						//$not_applicable_string = "---";
                        $studentCount = count($studentInformationArray);

						if($studentCount >0 && is_array($studentInformationArray) ) {
                         
                            for($i=0; $i<$studentCount; $i++ ) {
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
						echo '<td class="contenttab_internal_rows" valign="top" width="10%"><nobr><b>First Name </b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['firstName'])!=""){
							echo '<td valign="top" class="contenttab_internal_rows1" align="left" width="25%"><b>:</b>&nbsp; '.chunk_split(strip_slashes($studentInformationArray[$i]['firstName']),40,"<br>").'</td>'; 
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
							}
						echo '<td class="contenttab_internal_rows" valign="top" width="10%"><nobr><b>Last Name </b></nobr></td>';
							if (strip_slashes($studentInformationArray[$i]['lastName'])!=""){
								echo '<td valign="top" class="contenttab_internal_rows1" align="left" width="25%"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['lastName']),40,"<br>").'</td>';
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}
							
						echo '<td colspan="2" align="center" valign="middle" rowspan="8">
							<table border="0" width="80" height="80">
								<tr>
									<td width="0"></td>
										<td class="field1_heading">';
									
											if($studentInformationArray[$i]['studentPhoto']!=""){ 
												echo "<img src=\"".STUDENT_PHOTO_PATH."/".strip_slashes($studentInformationArray[$i]['studentPhoto'])."\" width=\"80\" height=\"80\"/>";
											}
											else
												echo "<img src=\"".STUDENT_PHOTO_PATH."/notFound.jpg\" width=\"80\" height=\"80\"/>";
											
										echo '</td>
								</tr>
							</table>
						</td>

						</tr>';
                        echo '<tr>';    
						echo '<td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>Roll No.</b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['rollNo'])!=""){
							echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['rollNo']),30,"<br>").'</td>';
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}
                        echo '<td class="contenttab_internal_rows" valign="top" ><nobr><b>Institute Reg. No.</b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['regNo'])!=""){
							echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['regNo']),15,"<br>").'</td>';
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}
						echo '</tr>';

                        echo '<tr>';    
                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Univ. RNo.</b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['universityRollNo'])!=""){
						    echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['universityRollNo']),30,"<br>").'</td>';
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}
                        
						echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>University Reg. No.</b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['universityRollNo'])!=""){
							echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['universityRegNo']),30,"<br>").'</td>';
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}
						echo '</tr>';
                        echo '<tr>'; 
						
                        echo '<td class="contenttab_internal_rows" ><nobr valign="top"><b>Date of Birth </b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['dateOfBirth'])!="" && strip_slashes($studentInformationArray[$i]['dateOfBirth'])!="0000-00-00"){
							echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.UtilityManager::formatDate($studentInformationArray[$i]['dateOfBirth']).'</td>';
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}
                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Gender </b></nobr></td>';
                        if (strip_slashes($studentInformationArray[$i]['studentGender'])!=""){
							if ($studentInformationArray[$i]['studentGender'] == "M") {
								$studentInformationArray[$i]['studentGender'] = "Male";
							}
							else {
								$studentInformationArray[$i]['studentGender'] = "Female";
							}
							echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.strip_slashes($studentInformationArray[$i]['studentGender']).'</td>';
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}
						echo '</tr>';
                        echo '<tr>';    
                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Email</b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['studentEmail'])!=""){
							echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['studentEmail']),40,"<br>").'</td>';
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}

						echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Nationality </b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['nationalityName'])!=""){
							echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['nationalityName']),40,"<br>").'</td>';
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}
						echo '</tr>';
                        echo '<tr>';    
                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Contact No. </b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['studentPhone'])!=""){	
							echo '<td valign="top" class="contenttab_internal_rows1" valign="top" align="left"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['studentPhone']),40,"<br>").'</td>';
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}

						echo '<td class="contenttab_internal_rows" ><nobr><b>Mobile No. </b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['studentMobileNo'])!=""){	
							echo '<td valign="top" class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['studentMobileNo']),15,"<br>").'</td>';
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}
						echo '</tr>';
                        echo '<tr>';    
                        echo '<td class="contenttab_internal_rows" ><nobr><b>Domicile </b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['stateName'])!=""){	
							echo '<td valign="top" class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['stateName']),40,"<br>").'</td>';
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}
                        echo '<td class="contenttab_internal_rows" ><nobr><b>Category </b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['quotaName'])!=""){	
							echo '<td valign="top" class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.strip_slashes($studentInformationArray[$i]['quotaName']).'</td>';
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}
						echo '</tr>';
                        echo '<tr>';    
                        echo '<td class="contenttab_internal_rows" ><nobr><b>User Name </b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['studentGender'])!=""){
							echo '<td valign="top" class="contenttab_internal_rows1" align="left" colspan="6"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['userName']),40,"<br>").'</td>';
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}
                        echo '</tr>';

                        echo '<tr>';    
                        echo '<td class="contenttab_internal_rows" width="10%"><nobr><b>Exam </b></nobr></td>
                        <td class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.$ExamBy.'</td>';
						echo '<td class="contenttab_internal_rows" width="10%"><nobr><b>Rank </b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['compExamRank'])!=""){
                             echo '<td class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['compExamRank']),15,"<br>").'</td>';
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}
                        echo '<td class="contenttab_internal_rows" width="10%"><nobr><b>Entrance Exam Roll No.</b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['compExamRollNo'])!=""){
							echo '<td class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['compExamRollNo']),15,"<br>").'</td>';
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}
						echo '</tr>';

						echo '<tr>';    
                        echo '<td class="contenttab_internal_rows" width="10%"><nobr><b>Fee Receipt No.</b></nobr></td>
                        <td class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['feeReceiptNo']),20,"<br>").'</td>';
						echo '<td class="contenttab_internal_rows" width="10%" ><nobr><b>Sports Activity</b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['studentSportsActivity'])!=""){
                             echo '<td class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['studentSportsActivity']),255,"<br>").'</td>';
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}
                        echo '<td class="contenttab_internal_rows" width="10%"><nobr><b>Blood Group</b></nobr></td>';
						if ($bloodGroup != ""){
							echo '<td class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.$bloodGroup.'</td>';
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}
						echo '</tr>';

                        echo '<tr>';    
                        echo '<td colspan="6" class="contenttab_internal_rows" width="10%" style="text-align:left"><nobr><U><b>Correspondence Address :</b></U></nobr></td></tr>';
                        echo '<tr>';
                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Address1</b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['corrAddress1'])!=""){
							echo '<td class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['corrAddress1']),40,"<br>").'</td>';
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}
                        echo '<td class="contenttab_internal_rows" valign="top" ><nobr><b>Address2</b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['corrAddress2'])!=""){	
							echo '<td class="contenttab_internal_rows1" valign="top" align="left"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['corrAddress2']),40,"<br>").'</td>';
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}
						echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Pincode </b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['corrPinCode'])!=""){	
							echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['corrPinCode']),10,"<br>").'</td>';
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}
						echo '</tr>';
                        echo '<tr>';    
                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Country </b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['corrCountry'])!=""){
							echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['corrCountry']),40,"<br>").'</td>';
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}
                        echo '<td class="contenttab_internal_rows"><nobr><b>State </b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['corrState'])!=""){
						echo '<td class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['corrState']),40,"<br>").'</td>';
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}

                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>City</b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['corrCity'])!=""){
							echo '<td class="contenttab_internal_rows1" align="left"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['corrCity']),40,"<br>").'</td>';
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" valign="top" align="left"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}
						echo '</tr>';

                        echo '<tr>';    
                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Contact No.</b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['corrPhone'])!=""){	
							echo '<td class="contenttab_internal_rows1" valign="top" align="left"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['corrPhone']),40,"<br>").'</td>';
						}
						else{
							echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}

                        echo '<tr>';    
                        
                        echo '<td colspan="6" class="contenttab_internal_rows" width="10%" style="text-align:left"><nobr><U><b>Permanent Address: </b></U></nobr></td></tr>';
                        echo '<tr>';    
                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Address1 </b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['permAddress1'])!=""){	
							echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['permAddress1']),40,"<br>").'</td>';
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}

                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Address2 </b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['permAddress2'])!=""){	
							echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['permAddress2']),40,"<br>").'</td>';
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}

                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Pincode</b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['permPinCode'])!=""){	
							echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['permPinCode']),10,"<br>").'</td></tr>';
							}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}
						echo '</tr>';
                        echo '<tr>';    
                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Country </b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['permCountry'])!=""){	
							echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['permCountry']),40,"<br>").'</td>';
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}
                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>State </b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['permState'])!=""){
							echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['permState']),40,"<br>").'</td>';
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}
                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>City </b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['permCity'])!=""){
							echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['permCity']),40,"<br>").'</td>';
						}
						else{
							echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}
						echo '</tr>';
                        echo '<tr>';    
                        echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Contact No.</b></nobr></td>';
						if (strip_slashes($studentInformationArray[$i]['corrPhone'])!=""){
							echo '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentInformationArray[$i]['corrPhone']),20,"<br>").'</td>';
						}
						else {
							echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
						}
                        echo '</tr>';
                            }
                        
                        }
                        else {
                            echo '<tr><td colspan="5" align="center">No record found</td></tr>';
                        }
                        ?>
                        </table>
                        </div>
                        
                        <div class="dhtmlgoodies_aTab" style="overflow:auto">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <?php //DISPLAYS ALL RECORDS 
                        require_once($FE . "/Library/common.inc.php");
                        $studentCount = count($studentInformationArray);
                        
                        if($studentCount >0 && is_array($studentInformationArray) ) {
                         
                            for($i=0; $i<$studentCount; $i++ ) {
                         if ($studentInformationArray[$i]['fatherTitle']=="1"){
                            $title = "Mr.";
                         }
                         else if ($studentInformationArray[$i]['fatherTitle']=="2"){
                             $title = "Mrs.";
                         }
                         else if ($studentInformationArray[$i]['fatherTitle']=="3"){
                             $title = "Miss";
                         }
                         
                         if ($studentInformationArray[$i]['motherTitle']=="1"){
                             $mothertitle = "Mrs.";
                         }
                         else if ($studentInformationArray[$i]['motherTitle']=="2"){
                             $mothertitle = "Miss";
                         }
                         
                         if ($studentInformationArray[$i]['guardianTitle']=="1"){
                            $guardianTitle = "Mr.";
                         }
                         else if ($studentInformationArray[$i]['guardianTitle']=="2"){
                             $guardianTitle = "Mrs.";
                         }
                         else if ($studentInformationArray[$i]['guardianTitle']=="3"){
                             $guardianTitle = "Miss";
                         }
                         
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
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <?php //DISPLAYS ALL RECORDS 
                        $studentCount = count($studentInformationArray);
                        
                        if($studentCount >0 && is_array($studentInformationArray) ) {
                           for($i=0; $i<$studentCount; $i++ ) {
							echo '<tr>
							 <td valign="top" colspan="5"><div id="results">';
							  ?>
								</div>
								</td>
							</tr>
							<tr>
								<td class="content_title" title="Print" align="right" ><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printAttendanceReport();" /></td>
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
                            <table width="100%" border="0" cellspacing="1" cellpadding="1">
                                <?php //DISPLAYS ALL RECORDS 
                                require_once($FE . "/Library/common.inc.php");
                                $studentCount = count($studentInformationArray);
                                
                                if($studentCount >0 && is_array($studentInformationArray) ) {
                                 
                                    for($i=0; $i<$studentCount; $i++ ) {
										echo '<tr>
										<td valign="top" colspan="5"><div id="testMarksResults">';
										  ?>
											</div>
											</td>
										</tr>
										<tr>
										<td class="content_title" title="Print" align="right" ><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printTestMarksReport();" /></td>
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
						<table width="100%" border="0" cellspacing="5" cellpadding="5">
                        <tr>
                            <td valign="top"><div id="resultResource">
							</div>
                          </td>
                        </tr>
						<tr>
							<td class="content_title" title="Print" align="right" ><input type="image" name="print" src="<?php  echo IMG_HTTP_PATH;?>/print.gif" onClick="printFinalResultReport();" /></td>
						</tr>
                      </table>                 
                   </div>

			</div>
                        

<script type="text/javascript">
initTabs('dhtmlgoodies_tabView1',Array('Personal Details','Parents Details','Attendance Detail','Test Marks Detail','Final Result Detail'),0,990,370,Array(false,false,false,false,false));
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