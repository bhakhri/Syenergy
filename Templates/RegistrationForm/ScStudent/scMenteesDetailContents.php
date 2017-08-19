<?php
//-------------------------------------------------------
// Purpose: to design the layout for assign to subject.
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
$queryString =  $_SERVER['QUERY_STRING'];
 
?>
 <form method="POST" name="addForm"  action="<?php echo HTTP_LIB_PATH;?>/ScStudent/scFileUpload.php" id="addForm" method="post" enctype="multipart/form-data" style="display:inline">

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td class="title">
            <input type="hidden" name="classId" id="classId" value="<?php echo $studentDataArr[0]['classId']?>">
            <input type="hidden" name="studentUserId" value="<?php echo $studentDataArr[0]['userId']?>">
            <input type="hidden" name="fatherUserId" value="<?php echo $studentDataArr[0]['fatherUserId']?>">
            <input type="hidden" name="motherUserId" value="<?php echo $studentDataArr[0]['motherUserId']?>">
            <input type="hidden" name="guardianUserId" value="<?php echo $studentDataArr[0]['guardianUserId']?>">
            <input type="hidden" name="studentId" value="<?php echo $REQUEST_DATA['id']?>">
            <input type="hidden" name="updateStudent" value="1">

            <input type="hidden" name="previousCorrespondence" id="previousCorrespondence" value="<?php echo $studentDataArr[0]['correspondenceAddressVerified']?>">
            <input type="hidden" name="previousPermanent" id="previousPermanent" value="<?php echo $studentDataArr[0]['permanentAddressVerified']?>">
            <input type="hidden" name="previousFatherVerified" id="previousFatherVerified" value="<?php echo $studentDataArr[0]['fatherAddressVerified']?>">
            <input type="hidden" name="previousMotherVerified" id="previousMotherVerified" value="<?php echo $studentDataArr[0]['motherAddressVerified']?>">
            <input type="hidden" name="previousGuardianVerified" id="previousGuardianVerified" value="<?php echo $studentDataArr[0]['guardianAddressVerified']?>">
             
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                    <?php if (STUDENT_ICON == true) { ?>
                    <td width="5%">
                    <img src="<?php echo IMG_HTTP_PATH ?>/student.gif" border="0" title="Student">
                    <!--echo "<img src=\"".IMG_HTTP_PATH."/".student.gif."\" width=\"100\" height=\"100\" border=\"0\"/>";-->
                        <?php }
                    ?></td>
                <td valign="middle">Student&nbsp;&raquo;&nbsp;Search Student</td>
                <td align="right">
                    <a href="registrationReport.php?<?php echo $queryString?>&listStudent=1">
                    <img src="<?php echo IMG_HTTP_PATH ?>/back1.gif" border="0"></a>&nbsp;&nbsp;&nbsp;
                </td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
             <td class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0" height="650">
             <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="contenttab_row1"><span class="content_title">Student Detail: 
                        &nbsp;<B><U>Name:</U></B>&nbsp;<?php echo $studentDataArr[0]['firstName']." ".$studentDataArr[0]['lastName'];?>
                        &nbsp;&nbsp;<B><U>Class:</U></B>&nbsp;<?php echo $studentClassArr[0]['className'];?>
                        &nbsp;&nbsp;<B><U>Admission Date:</U></B>&nbsp;
                        <?php echo (UtilityManager::formatDate($studentDataArr[0]['dateOfAdmission']));?>&nbsp;&nbsp;</span>
                        </td>
                        <td class="contenttab_row1" align="right"><span class="content_title">Study Period:</span>
                        <select size="1" class="selectfield" name="studyPeriod" id="studyPeriod" style="width:100px" onchange="refreshStudentData('<?php echo $REQUEST_DATA[id]?>',this.value,tabNumber);">
                        <option value="0">All</option>
                        <?php
                         require_once(BL_PATH.'/HtmlFunctions.inc.php');
                         echo HtmlFunctions::getInstance()->getStudyPeriodName(add_slashes($REQUEST_DATA['id']),$studentDataArr[0]['classId']);
                        ?>
                        </select>
                        </td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top">
<div id="dhtmlgoodies_tabView1">
           <div class="dhtmlgoodies_aTab">
                 <div id="scroll2" style="overflow:auto; width:975px; height:580px; vertical-align:top;"> 
                           
  <form method="POST" name="addForm" id="addForm"  action="<?php echo HTTP_LIB_PATH;?>/ScStudent/studentFileUpload.php" method="post" enctype="multipart/form-data" style="display:inline"> 
                        
		<table width="100%" border="0" cellspacing="3" cellpadding="0" >
                        <?php //DISPLAYS ALL RECORDS 
                        require_once($FE . "/Library/common.inc.php");
						//$not_applicable_string = "---";
                        $studentCount = count($studentDataArr);
						
                        
                        if($studentCount >0 && is_array($studentDataArr) ) {
                         
                            for($i=0; $i<$studentCount; $i++ ) {
						 $bg = $bg =='row0' ? 'row1' : 'row0';
                         if ($studentDataArr[$i]['compExamBy']=="1"){
                            $ExamBy = "CET";
                         }
                         else if ($studentDataArr[$i]['compExamBy']=="2"){
                             $ExamBy = "AIEEE";
                         }
                         else if ($studentDataArr[$i]['compExamBy']=="3"){
                             $ExamBy = "CEET";
                         }
                        echo '<tr>';  
			echo '<td class="contenttab_internal_rows" valign="top" width="10%"><nobr><b>First Name </b></nobr></td>';
				if (strip_slashes($studentDataArr[$i]['firstName'])!=""){
					echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
					echo '<td valign="top" class="contenttab_internal_rows1" align="left" width="25%">'.chunk_split(strip_slashes($studentDataArr[$i]['firstName']),40,"<br>").'</td>'; 
						}
				else {
					echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
					echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.NOT_APPLICABLE_STRING.'</td>';
						}
			
			echo '<td class="contenttab_internal_rows" valign="top" width="10%"><nobr><b>Last Name </b></nobr></td>';
				if (strip_slashes($studentDataArr[$i]['lastName'])!=""){
					echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
					echo '<td valign="top" class="contenttab_internal_rows1" align="left" width="25%">'.chunk_split(strip_slashes($studentDataArr[$i]['lastName']),40,"<br>").'</td>';
						}
				else {
        				echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
					echo '<td valign="top" class="contenttab_internal_rows1" align="left" width="25%">'.NOT_APPLICABLE_STRING.'</td>';
						}
							
			echo '<td colspan="2" align="center" valign="middle" rowspan="8">
				<table border="0" cellspacing="0" cellpadding="0" width="5%" height="100">
				<tr>
					<td class="field1_heading" width="2%"><nobr>';
				if($studentDataArr[$i]['studentPhoto']!=""){ 
                                   $imgPhoto = $studentDataArr[$i]['studentPhoto']."?y=".rand(0,1000);
					echo "<img class='imgLinkRemove' id='studentImageId1'  src=\"".STUDENT_PHOTO_PATH."/".$imgPhoto."\" width=\"100\" height=\"100\"/>";
						}
				else
					echo "<img class='imgLinkRemove' id='studentImageId1'  src=\"".STUDENT_PHOTO_PATH."/notFound.jpg\" width=\"100\" height=\"100\"/>";
											
					echo '</nobr></td><td width="96%"></td>
								</tr>';
                       		 if($sessionHandler->getSessionVariable('STUDENT_CAN_CHANGE_IMAGE')==1){          
                          		 echo    '<tr>
                                    		<td colspan="2">
                                          		<table border="0" cellspacing="0" cellpadding="0" width="10%" >
                                           		<tr>
                                               <td class="contenttab_internal_rows"><nobr><b>Update Photo</b></nobr></td>
					       <td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>
                                               <td valign="top" class="contenttab_internal_rows1" align="left" nowrap><nobr> 
                                               <input type="file"  id="studentPhoto" name="studentPhoto" size="10" accept="image/*" /></nobr>
                                               </td>
                                                <td align="left"><nobr>&nbsp;
                                                 <input type="image"  src="'.IMG_HTTP_PATH.'/upload.gif" onClick="return initAdd();" >
                                                 <iframe id="uploadTargetAdd" name="uploadTargetAdd" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe></nobr>
                                               </td>
                                            </tr> 
                                          </table>   
                                       </td>   
                                   </tr>';
                        }
                                
						echo '</table>
						      </td>
                              </tr>';
                        echo '<tr>';    
						echo '<td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>Roll No.</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['rollNo'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['rollNo']),30,"<br>").'</td>';
						}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" width="25%">'.NOT_APPLICABLE_STRING.'</td>';
						}
                        
						echo '<td class="contenttab_internal_rows" valign="top" ><nobr><b>Institute Reg. No.</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['regNo'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['regNo']),15,"<br>").'</td>';
						}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" width="25%">'.NOT_APPLICABLE_STRING.'</td>';
						}
						echo '</tr>';

                        echo '<tr>';    
                       
						echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>University Roll No.</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['universityRollNo'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
		    				echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['universityRollNo']),30,"<br>").'</td>';
						}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" width="25%">'.NOT_APPLICABLE_STRING.'</td>';
						}
                        
						echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>University Reg. No.</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['universityRollNo'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['universityRegNo']),30,"<br>").'</td>';
						}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" width="25%">'.NOT_APPLICABLE_STRING.'</td>';
						}
						echo '</tr>';
                       	 			echo '<tr>'; 
                        
						echo '<td class="contenttab_internal_rows" ><nobr valign="top"><b>Date of Birth</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['dateOfBirth'])!="" && strip_slashes($studentDataArr[$i]['dateOfBirth'])!="0000-00-00"){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.UtilityManager::formatDate($studentDataArr[$i]['dateOfBirth']).'</td>';
						}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" width="25%">'.NOT_APPLICABLE_STRING.'</td>';
						}
                       				
						echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Gender</b></nobr></td>';
                        		if (strip_slashes($studentDataArr[$i]['studentGender'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.strip_slashes($studentDataArr[$i]['studentGender']).'</td>';
						}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" width="25%">'.NOT_APPLICABLE_STRING.'</td>';
						}
						echo '</tr>';
                       		 		echo '<tr>';    
                        		
						echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Email </b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['studentEmail'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['studentEmail']),40,"<br>").'</td>';
						}
						else {
echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
		echo '<td valign="top" class="contenttab_internal_rows1" align="left" width="25%">'.NOT_APPLICABLE_STRING.'</td>';
						}

						echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Nationality</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['nationalityName'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['nationalityName']),40,"<br>").'</td>';
						}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" width="25%">'.NOT_APPLICABLE_STRING.'</td>';
						}
						echo '</tr>';
                       				echo '<tr>';    
                        
						echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Contact No.</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['studentPhone'])!=""){	
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" valign="top" align="left">'.chunk_split(strip_slashes($studentDataArr[$i]['studentPhone']),40,"<br>").'</td>';
						}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" width="25%">'.NOT_APPLICABLE_STRING.'</td>';
						}

						echo '<td class="contenttab_internal_rows" ><nobr><b>Mobile No. </b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['studentMobileNo'])!=""){	
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.chunk_split(strip_slashes($studentDataArr[$i]['studentMobileNo']),15,"<br>").'</td>';
						}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" width="25%">'.NOT_APPLICABLE_STRING.'</td>';
						}
						echo '</tr>';
                        			echo '<tr>';    
                        
						echo '<td class="contenttab_internal_rows" ><nobr><b>Domicile</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['stateName'])!=""){	
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.chunk_split(strip_slashes($studentDataArr[$i]['stateName']),40,"<br>").'</td>';
						}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" width="25%">'.NOT_APPLICABLE_STRING.'</td>';
						}
                        
						echo '<td class="contenttab_internal_rows" ><nobr><b>Category : </b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['stateName'])!=""){	
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left">'.strip_slashes($studentDataArr[$i]['quotaName']).'</td>';
						}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" width="25%">'.NOT_APPLICABLE_STRING.'</td>';
						}
						echo '</tr>';
                        			echo '<tr>';    
                       
						echo '<td class="contenttab_internal_rows" ><nobr><b>User Name</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['studentGender'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" colspan="6">'.chunk_split(strip_slashes($studentDataArr[$i]['userName']),40,"<br>").'</td>';
						}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" width="25%">'.NOT_APPLICABLE_STRING.'</td>';
						}
                        			echo '</tr>';
						echo '<tr>';    
                        
						echo '<td class="contenttab_internal_rows" width="10%"><nobr><b>Exam </b></nobr></td>
						      <td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>
                                                      <td class="contenttab_internal_rows1" align="left">'.$ExamBy.'</td>';
						echo '<td class="contenttab_internal_rows" width="10%"><nobr><b>Rank </b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['compExamRank'])!=""){
                            	 		echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left">'.chunk_split(strip_slashes($studentDataArr[$i]['compExamRank']),15,"<br>").'</td>';
						}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" width="25%">'.NOT_APPLICABLE_STRING.'</td>';
						}
                        
						echo '<td class="contenttab_internal_rows" width="40"><nobr><b>Entrance Exam Roll No. </b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['compExamRollNo'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left">'.chunk_split(strip_slashes($studentDataArr[$i]['compExamRollNo']),10,"<br>").'</td>';
						}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" width="1%">'.NOT_APPLICABLE_STRING.'</td>';
						}
						echo '</tr>';
						echo '<tr>';    
                        
						echo '<td colspan="6" class="contenttab_internal_rows" width="10%" style="text-align:left"><nobr><U><b>Correspondence Address: </b></U></nobr></td></tr>';
                        			echo '<tr>';    
                        		
						echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Address1 </b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['corrAddress1'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
echo '<td class="contenttab_internal_rows1" align="left">'.chunk_split(strip_slashes($studentDataArr[$i]['corrAddress1']),20,"<br>").'</td>';
						}
					else {
echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" width="25%">'.NOT_APPLICABLE_STRING.'</td>';
						}
                        echo '<td class="contenttab_internal_rows" valign="top" ><nobr><b>Address2</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['corrAddress2'])!=""){	
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
echo '<td class="contenttab_internal_rows1" valign="top" align="left">'.chunk_split(strip_slashes($studentDataArr[$i]['corrAddress2']),15,"<br>").'</td>';
						}
					else {
echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top" width="25%">'.NOT_APPLICABLE_STRING.'</td>';
						}
						
						echo '<td class="contenttab_internal_rows" valign="top" width="40"><nobr><b>Pincode </b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['corrPinCode'])!=""){	
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['corrPinCode']),10,"<br>").'</td>';
						}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top" width="25%">'.NOT_APPLICABLE_STRING.'</td>';
						}
						echo '</tr>';
                        			echo '<tr>';    
                        
						echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Country</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['corrCountry'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['corrCountry']),40,"<br>").'</td>';
						}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top" width="25%">'.NOT_APPLICABLE_STRING.'</td>';
						}
                        
						echo '<td class="contenttab_internal_rows"><nobr><b>State</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['corrState'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['corrState']),40,"<br>").'</td>';
						}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" width="25%">'.NOT_APPLICABLE_STRING.'</td>';
						}

                        			echo '<td class="contenttab_internal_rows" valign="top" width="40"><nobr><b>City </b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['corrCity'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['corrCity']),40,"<br>").'</td>';
						}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" valign="top" align="left" width="25%">'.NOT_APPLICABLE_STRING.'</td>';
						}
						
						echo '</tr>';
						echo '<tr>';    
                       
				 		echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Contact No. </b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['corrPhone'])!=""){	
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" valign="top" align="left">'.chunk_split(strip_slashes($studentDataArr[$i]['corrPhone']),40,"<br>").'</td>';
						}
					else{
echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top" width="25%">'.NOT_APPLICABLE_STRING.'</td>';
						}

                        			echo '<tr>';    
                        			echo '<td colspan="6" class="contenttab_internal_rows" width="10%" style="text-align:left"><nobr><U><b>Permanent Address: </b></U></nobr></td></tr>';
                       	 			echo '<tr>';    
                        
						echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Address1 </b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['permAddress1'])!=""){	
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['permAddress1']),40,"<br>").'</td>';
						}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top" width="25%">'.NOT_APPLICABLE_STRING.'</td>';
						}

                        			echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Address2 </b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['permAddress2'])!=""){	
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['permAddress2']),40,"<br>").'</td>';
						}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top" width="25%">'.NOT_APPLICABLE_STRING.'</td>';
						}

                        			echo '<td class="contenttab_internal_rows" valign="top" width="40"><nobr><b>Pincode</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['permPinCode'])!=""){	
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';	
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['permPinCode']),10,"<br>").'</td></tr>';
							}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top" width="25%">'.NOT_APPLICABLE_STRING.'</td>';
						}
						echo '</tr>';
                        			echo '<tr>';
                        
						echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Country</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['permCountry'])!=""){	
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['permCountry']),40,"<br>").'</td>';
						}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top" width="25%">'.NOT_APPLICABLE_STRING.'</td>';
						}
                        
						echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>State</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['permState'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['permState']),40,"<br>").'</td>';
						}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.NOT_APPLICABLE_STRING.'</td>';
						}
                        	
						echo '<td class="contenttab_internal_rows" valign="top" width="40"><nobr><b>City</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['permCity'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';	
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['permCity']),40,"<br>").'</td>';
						}
					else{
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top" width="25%">'.NOT_APPLICABLE_STRING.'</td>';
						}
						echo '</tr>';
                       			 	echo '<tr>';    
                       
 						echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Contact No.</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['corrPhone'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['corrPhone']),20,"<br>").'</td>';
						}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top" width="25%">'.NOT_APPLICABLE_STRING.'</td>';
						}
                        			echo '</tr>';
                        			echo '<tr>'; 
			            		echo '<tr><td height="5"></td></tr>';                           
                       			}
                        	}
                        		else {
                            			echo '<tr><td colspan="5" align="center">No record found</td></tr>';
                        			}
?>
                        </table>
                        	</form>
                        		</div>
                        			</div>
 
<div class="dhtmlgoodies_aTab">
          <table width="100%" border="0" cellspacing="5" cellpadding="5">
                         <?php //DISPLAYS ALL RECORDS 
                        require_once($FE . "/Library/common.inc.php");
                        $studentCount = count($studentDataArr);
                        
                        if($studentCount >0 && is_array($studentDataArr) ) {
                         
                            for($i=0; $i<$studentCount; $i++ ) {
                         if ($studentDataArr[$i]['fatherTitle']=="1"){
                            $title = "Mr.";
                         }
                         else if ($studentDataArr[$i]['fatherTitle']=="2"){
                             $title = "Mrs.";
                         }
                         else if ($studentDataArr[$i]['fatherTitle']=="3"){
                             $title = "Miss";
                         }
                         
                         if ($studentDataArr[$i]['motherTitle']=="1"){
                            $mothertitle = "Mr.";
                         }
                         else if ($studentDataArr[$i]['motherTitle']=="2"){
                             $mothertitle = "Mrs.";
                         }
                         else if ($studentDataArr[$i]['motherTitle']=="3"){
                             $mothertitle = "Miss";
                         }
                         
                         if ($studentDataArr[$i]['guardianTitle']=="1"){
                            $guardianTitle = "Mr.";
                         }
                         else if ($studentDataArr[$i]['guardianTitle']=="2"){
                             $guardianTitle = "Mrs.";
                         }
                         else if ($studentDataArr[$i]['guardianTitle']=="3"){
                             $guardianTitle = "Miss";
                         }
                         
                         if ($studentDataArr[$i]['fatherCountryId']=="IS NULL"){
                            $fatherCountryId = "";
                         }
                         if ($studentDataArr[$i]['motherCountryId']=="IS NULL"){
                            $motherCountryId = "";
                         }
                         if ($studentDataArr[$i]['guardianCountryId']=="IS NULL"){
                            $guardianCountryId = "";
                         }
                        			echo '<tr>';    
                           			echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Father Name </b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['fatherName'])!=""){
								
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" width="15%" valign="top">'.$title.' '.chunk_split(strip_slashes($studentDataArr[$i]['fatherName']),30,"<br>").'</td>';
							}
					else {
						echo '<td class="contenttab_internal_rows" width="1%"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.NOT_APPLICABLE_STRING.'</td>';
							}

                            			echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Mother Name </b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['motherName'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';						echo '<td class="contenttab_internal_rows1" align="left" width="15%" valign="top">'.$mothertitle.' '.chunk_split(strip_slashes($studentDataArr[$i]['motherName']),30,"<br>").'</td>';
							}
					else {
echo '<td class="contenttab_internal_rows" width="1%"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.NOT_APPLICABLE_STRING.'</td>';
							}
                            			
						echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Guardian Name</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['guardianName'])!=""){	
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" width="15%" valign="top">'.$guardianTitle.' '.chunk_split(strip_slashes($studentDataArr[$i]['guardianName']),30,"<br>").'</td>';
							}
					else {
						echo '<td class="contenttab_internal_rows" width="1%"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.NOT_APPLICABLE_STRING.'</td>';
							}
						echo '</tr>';
						echo '<tr>';    
                            
						echo '<td class="contenttab_internal_rows" ><nobr valign="top"><b>Occupation</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['fatherOccupation'])!=""){	
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['fatherOccupation']),30,"<br>").'</td>';
							}
					else {
						echo '<td class="contenttab_internal_rows" width="1%"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.NOT_APPLICABLE_STRING.'</td>';
							}
                            
						echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Occupation</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['motherOccupation'])!=""){		
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['motherOccupation']),30,"<br>").'</td>';
							}
					else {
						echo '<td class="contenttab_internal_rows" width="1%"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.NOT_APPLICABLE_STRING.'</td>';
							}
                            
						echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Occupation</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['guardianOccupation'])!=""){		
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['guardianOccupation']),30,"<br>").'</td>';
							}
					else {
						echo '<td class="contenttab_internal_rows" width="1%"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.NOT_APPLICABLE_STRING.'</td>';
							}
						echo '</tr>';
						echo '<tr>';    
                            			
						echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Mobile No. </b></nobr></td>';				if (strip_slashes($studentDataArr[$i]['fatherMobileNo'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['fatherMobileNo']),30,"<br>").'</td>';
							}
					else {
						echo '<td class="contenttab_internal_rows" width="1%"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.NOT_APPLICABLE_STRING.'</td>';
							}
                            			
						echo '<td class="contenttab_internal_rows" valign="top" width="10%"><nobr><b>Mobile No. </b></nobr></td>';
                            		if (strip_slashes($studentDataArr[$i]['motherMobileNo'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['motherMobileNo']),30,"<br>").'</td>';
							}
					else {
						echo '<td class="contenttab_internal_rows" width="1%"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.NOT_APPLICABLE_STRING.'</td>';
							}
                            			
						echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Mobile No.</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['guardianMobileNo'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['guardianMobileNo']),30,"<br>").'</td>';
							}
					else {
						echo '<td class="contenttab_internal_rows" width="1%"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.NOT_APPLICABLE_STRING.'</td>';
							}
						echo '</tr>';
						echo '<tr>';    
                            
						echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Email</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['fatherEmail'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['fatherEmail']),30,"<br>").'</td>';
							}
					else{
echo '<td class="contenttab_internal_rows" width="1%"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.NOT_APPLICABLE_STRING.'</td>';
							}
						
						echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Email</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['motherEmail'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['motherEmail']),30,"<br>").'</td>';
							}
					else {
						echo '<td class="contenttab_internal_rows" width="1%"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.NOT_APPLICABLE_STRING.'</td>';
							}
                            			echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Email </b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['guardianEmail'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['guardianEmail']),30,"<br>").'</td></tr>';
							}
					else {
						echo '<td class="contenttab_internal_rows" width="1%"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.NOT_APPLICABLE_STRING.'</td>';
							}
						echo '<tr>';    
                            
						echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Address1</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['fatherAddress1'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['fatherAddress1']),30,"<br>").'</td>';
							}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.NOT_APPLICABLE_STRING.'</td>';
							}
                            
						echo '<td class="contenttab_internal_rows" valign="top" valign="top"><nobr><b>Address1</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['motherAddress1'])!=""){
	                       			echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
     						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['motherAddress1']),30,"<br>").'</td>';
							}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" valign="top" align="left">'.NOT_APPLICABLE_STRING.'</td>';
							}
						
						echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Address1</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['guardianAddress1'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['guardianAddress1']),30,"<br>").'</td>';
							}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.NOT_APPLICABLE_STRING.'</td>';
							}
						echo '</tr>';
						echo '<tr>';    
                            
						echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Address2</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['fatherAddress2'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" valign="top" align="left" >'.chunk_split(strip_slashes($studentDataArr[$i]['fatherAddress2']),30,"<br>").'</td>';
							}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.NOT_APPLICABLE_STRING.'</td>';
							}

						echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Address2</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['motherAddress2'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" valign="top" align="left">'.chunk_split(strip_slashes($studentDataArr[$i]['motherAddress2']),30,"<br>").'</td>';
							}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" valign="top" align="left">'.NOT_APPLICABLE_STRING.'</td>';
							}
                            
						echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Address2</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['guardianAddress2'])!=""){	
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['guardianAddress2']),30,"<br>").'</td>';
							}
					else {
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.NOT_APPLICABLE_STRING.'</td>';
							}
						echo '</tr>';
						echo '<tr>';    
                            
						echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Country</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['fatherCountry'])!=""){	
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['fatherCountry']),40,"<br>").'</td>';
							}
					else {
						echo '<td class="contenttab_internal_rows" width="1%"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.NOT_APPLICABLE_STRING.'</td>';
							}
                            			
						echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Country </b></nobr></td>';
                            		if (strip_slashes($studentDataArr[$i]['motherCountry'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['motherCountry']),40,"<br>").'</td>';	
							}
					else {
						echo '<td class="contenttab_internal_rows" width="1%"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.NOT_APPLICABLE_STRING.'</td>';
							}
                            
						echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Country</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['guardCountry'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['guardCountry']),40,"<br>").'</td>';
							}
					else {
						echo '<td class="contenttab_internal_rows" width="1%"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.NOT_APPLICABLE_STRING.'</td>';
							}
						echo '</tr>';
						echo '<tr>';    
                            
						echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>State </b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['fatherState'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['fatherState']),40,"<br>").'</td>';
							}
					else {
						echo '<td class="contenttab_internal_rows" width="1%"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.NOT_APPLICABLE_STRING.'</td>';
							}
							
						echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>State</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['motherState'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['motherState']),40,"<br>").'</td>';
							}
					else {
						echo '<td class="contenttab_internal_rows" width="1%"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.NOT_APPLICABLE_STRING.'</td>';
							}
                            
						echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>State</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['guardState'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['guardState']),40,"<br>").'</td></tr>';
							}
					else {
						echo '<td class="contenttab_internal_rows" width="1%"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.NOT_APPLICABLE_STRING.'</td>';
							}
						echo '<tr>';
                            
						echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>City</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['fatherCity'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['fatherCity']),40,"<br>").'</td>';
							}
					else {
						echo '<td class="contenttab_internal_rows" width="1%"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.NOT_APPLICABLE_STRING.'</td>';
							}
						echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>City </b></nobr></td>';
                            		if (strip_slashes($studentDataArr[$i]['motherCity'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['motherCity']),40,"<br>").'</td>';
							}
					else {
						echo '<td class="contenttab_internal_rows" width="1%"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.NOT_APPLICABLE_STRING.'</td>';
							}

                            
						echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>City</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['guardCity'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['guardCity']),40,"<br>").'</td>';
							}
					else {
						echo '<td class="contenttab_internal_rows" width="1%"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.NOT_APPLICABLE_STRING.'</td>';
							}
						echo '</tr>';
						echo '<tr>';    
						
						echo '<td class="contenttab_internal_rows" width="10%"><nobr><b>Pin Code</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['fatherPinCode'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['fatherPinCode']),10,"<br>").'</td>';
							}
					else {
						echo '<td class="contenttab_internal_rows" width="1%"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.NOT_APPLICABLE_STRING.'</td>';
							}
                            			
						echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Pin Code</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['motherPinCode'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['motherPinCode']),10,"<br>").'</td>';
							}
					else {
						echo '<td class="contenttab_internal_rows" width="1%"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top">'.NOT_APPLICABLE_STRING.'</td>';
							}
						
						echo '<td class="contenttab_internal_rows" width="10%" valign="top"><nobr><b>Pin Code</b></nobr></td>';
					if (strip_slashes($studentDataArr[$i]['guardianPinCode'])!=""){
						echo '<td class="contenttab_internal_rows" width="1%" valign="top"><b>:</b></td>';
						echo '<td class="contenttab_internal_rows1" align="left" valign="top">'.chunk_split(strip_slashes($studentDataArr[$i]['guardianPinCode']),10,"<br>").'</td></tr>';
							}
					else {
						echo '<td class="contenttab_internal_rows" width="1%"><b>:</b></td>';
						echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="center" >'.NOT_APPLICABLE_STRING.'</td>';
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
                        
<div class="dhtmlgoodies_aTab">
                       <table width="100%" border="0" cellspacing="5" cellpadding="5">
                        <tr>
                            <td>
                            <table width="40%" border="0" cellspacing="1" cellpadding="1" align="center">
                            <tr>
                                <td valign="middle"><B>Enter Roll Number</B></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td width="90"><input type="text" class="inputbox" name="siblingRoll" id="siblingRoll"  maxlength="20" <?php echo $inActiveClass?>></td>
                            </tr>
                            <?php 
                                if($inActiveClass==''){
                            ?>
                            <tr>
                                <td colspan="3" align="center"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/save.gif" onClick="addSibling(<?php echo $REQUEST_DATA['id']?>,document.addForm.siblingRoll.value);return false;"/></td> 
                            </tr>
                            <?php
                                }    
                            ?>
                            </table>
                            <td>
                        </tr>
                        <tr>
                            <td><div id="results1">
                            </div>
                            </td>
                        </tr>
                        </table>
                        </div>
                        <div class="dhtmlgoodies_aTab">
                        <table width="80%" border="0" cellspacing="5" cellpadding="5" align="center">
                        <tr>
                            <td width="10%" class="contenttab_internal_rows"><nobr><b>University</b></nobr></td>
                            <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                            <td width="39%" class="padding_top"><?php echo $university;?> </td>
                            <td width="10%" class="contenttab_internal_rows"><nobr><b>Institute</b></nobr></td>
                            <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                            <td width="39%" class="padding_top"><?php echo $sessionHandler->getSessionVariable('InstituteName');?></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Degree</b></nobr></td>
                            <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                            <td class="padding_top"><?php echo $degree;?></td>
                            <td width="10%" class="contenttab_internal_rows"><nobr><b>Branch</b></nobr></td>
                            <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                            <td class="padding_top"><?php echo $branch;?></td>
                        </tr> 
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Batch</b></nobr></td>
                            <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                            <td class="padding_top"><?php echo $batch?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Current Study Period</b></nobr></td>
                            <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                            <td class="padding_top"><?php echo $periodName?></td>
                        </tr> 
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Institute Roll No</b></nobr></td>
                            <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                            <td class="padding_top"><?php echo $studentDataArr[0]['rollNo']!='' ? $studentDataArr[0]['rollNo'] :"--";?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Institute Registration No</b></nobr></td>
                            <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                            <td class="padding_top"><?php echo $studentDataArr[0]['regNo']!='' ? $studentDataArr[0]['regNo'] :"--";?></td>
                        </tr> 
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>University Roll No</b></nobr></td>
                            <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                            <td class="padding_top"><?php echo $studentDataArr[0]['universityRollNo']!='' ? $studentDataArr[0]['universityRollNo'] :"--";?></td>
                            <td class="contenttab_internal_rows"><nobr><b>University Registration No</b></nobr></td>
                            <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                            <td class="padding_top"><?php echo $studentDataArr[0]['universityRegNo']!='' ? $studentDataArr[0]['universityRegNo'] :"--";?></td> 
                        </tr> 

                        <tr>
                             <td colspan="7"><div id="scroll2" style="overflow:auto; width:825px; height:380px"><div id="courseResultDiv"></div></div>
                             </td>
                        </tr> 
                        </table>
                        </div>
                    
    <div class="dhtmlgoodies_aTab" style="align:center">
                          <div id="scroll2" style="overflow:auto; width:975px; height:580px; vertical-align:top;"> 
                            <table width="25%" border="0" cellspacing="5" cellpadding="5">
                                <?php //DISPLAYS ALL RECORDS 
                                require_once($FE . "/Library/common.inc.php");
                                $studentCount = count($studentDataArr);
                                
                                if($studentCount >0 && is_array($studentDataArr) ) {
                                 
                                    for($i=0; $i<$studentCount; $i++ ) {
									echo '<tr>';    
                                    echo '<td class="contenttab_internal_rows" valign="top" width="10%"><nobr><b>Quota</b></nobr></td>';
									if (strip_slashes($studentDataArr[$i]['quotaName'])!=""){		
                                    echo '<td class="contenttab_internal_rows1" align="left" width="90%" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentDataArr[$i]['quotaName']),50,"<br>").'</td></tr>';
									}
									else {
										echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
									}
									echo '<tr>';    
                                    echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Date of Admission </b></nobr></td>';
									if (strip_slashes($studentDataArr[$i]['dateOfAdmission'])!=""){		
										echo '<td class="contenttab_internal_rows1" align="left" width="15%" valign="top"><b>:</b>&nbsp;&nbsp;'.UtilityManager::formatDate($studentDataArr[$i]['dateOfAdmission']).'</td>';
									}
									else {
										echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
									}
									echo '</tr>';
									echo '<tr>';    
                                    echo '<td class="contenttab_internal_rows" valign="top" ><nobr><b>I-Card No.</b></nobr></td>';
									if (strip_slashes($studentDataArr[$i]['icardNumber'])!=""){			
										echo '<td class="contenttab_internal_rows1" align="left" width="15%" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentDataArr[$i]['icardNumber']),50,"<br>").'</td>';   
									}
									else {
										echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
									}
									echo '</tr>';
									echo '<tr>';    
                                    echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Hostel Name </b></nobr></td>';
									if (strip_slashes($studentDataArr[$i]['hostelName'])!=""){
										echo '<td class="contenttab_internal_rows1" align="left" width="15%" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentDataArr[$i]['hostelName']),50,"<br>").'</td>'; 
									}
									else {
										echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
									}
									echo '</tr>';
									echo '<tr>';
									echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Hostel Room No.</b></nobr></td>';
									if (strip_slashes($studentDataArr[$i]['roomName'])!=""){
										echo '<td class="contenttab_internal_rows1" align="left" width="25%" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentDataArr[$i]['roomName']),50,"<br>").'</td>';
									}
									else {
										echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
									}
									echo '</tr>';
									echo '<tr>';    
                                    echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Bus Route No.</b></nobr></td>';
									if (strip_slashes($studentDataArr[$i]['routeName'])!=""){
										echo '<td class="contenttab_internal_rows1" align="left" width="15%" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentDataArr[$i]['routeName']),50,"<br>").'</td>';
									}
									else {
										echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
									}
									echo '</tr>';
									echo '<tr>';    
                                    echo '<td class="contenttab_internal_rows" valign="top"><nobr><b>Bus Stop No.</b></nobr></td>';
									if (strip_slashes($studentDataArr[$i]['stopName'])!=""){
                                    echo '<td class="contenttab_internal_rows1" align="left" width="15%" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($studentDataArr[$i]['stopName']),50,"<br>").'</td></tr>';
									}
									else {
										echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
									}
                                    echo "</tr>";
								 ?>
                            <table width="100%" border="0" cellspacing="5" cellpadding="5">
                            <tr>
                            <td height="5"></td>
                            </tr>
                            <tr>
                           
                                <td colspan="6">
                          <table width="100%" border="0" cellspacing="5" cellpadding="5">
                            <tr>
                            <td height="5"></td>
                            </tr>
                            <tr>
                            <td  height="5" colspan="4" width="10%" style="text-align:left"><nobr><U><b>Details As Per Last Registration:</b></U></nobr></td>
                            </tr>
                            <tr>
                            <td class="contenttab_internal_rows" nowrap valign="top" colspan="10">
                                     <fieldset class="fieldset">
                                     <legend>Personal Details</legend>
                                     <table width="100%" border="0" cellspacing="2px" cellpadding="2px" >
                                    <tr>
                                    <td class="contenttab_internal_rows" nowrap width="10%" valign="top"><nobr><b>Last Registration Date</b></nobr></td>
                                    <?php
                                    if (strip_slashes($getStudentRegistrationInfo[0]['registrationDate'])!=""){
                                    echo  '<td width="40%" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.UtilityManager::formatDate($getStudentRegistrationInfo[0]['registrationDate']).'</td>';
                                    }
                                    else {
                                    echo '<td width="40%" valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                    }  ?>
                                    <td class="contenttab_internal_rows" nowrap width="10%" valign="top"><nobr><b>Identification No.</b></nobr></td>
                                    <?php
                                    if (strip_slashes($getStudentRegistrationInfo[0]['universityRollNo'])!=""){
                                    echo  '<td width="40%" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes(strtoupper($getStudentRegistrationInfo[0]['universityRollNo'])),50,"<br>").'</td>';
                                    }
                                    else {
                                    echo '<td width="40%" valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                    }  ?> 
                                    </tr>
                                    <tr>
                                    <td class="contenttab_internal_rows" nowrap width="10%" valign="top"><nobr><b>Date of Birth</b></nobr></td>
                                    <?php
                                    if (strip_slashes($getStudentRegistrationInfo[0]['dateOfBirth'])!=""){
                                    echo  '<td width="40%" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.UtilityManager::formatDate($getStudentRegistrationInfo[0]['dateOfBirth']).'</td>';
                                    }
                                    else {
                                    echo '<td width="40%" valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                    }  ?>
                                    <td class="contenttab_internal_rows" nowrap width="10%" valign="top"><nobr><b>Mentor Name</b></nobr></td>
                                    <?php
                                    if (strip_slashes($getStudentRegistrationInfo[0]['mentor'])!=""){
                                    echo  '<td width="40%" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes(strtoupper($getStudentRegistrationInfo[0]['mentor'])),50,"<br>").'</td>';
                                    }
                                    else {
                                    echo '<td width="40%" valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                    }  ?> 
                                    </tr>
                                    <tr>
                                    <td class="contenttab_internal_rows" valign="top"><nobr><b>Student Mobile No</b></nobr></td>
                                    <?php
                                    if (strip_slashes($getStudentRegistrationInfo[0]['studentMobileNo'])!=""){
                                    echo  '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($getStudentRegistrationInfo[0]['studentMobileNo']),50,"<br>").'</td>';
                                    }
                                    else {
                                    echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                    }  ?>
                                    <td class="contenttab_internal_rows" valign="top"><nobr><b>Address</b></nobr></td>
                                    <?php
                                    if (strip_slashes($getStudentRegistrationInfo[0]['permAddress1'])!=""){
                                    echo  '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($getStudentRegistrationInfo[0]['permAddress1']),50,"<br>").'</td>';
                                    }
                                    else {
                                    echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                    }  ?> 
                                    </tr>
                                    <tr>
                                    <td class="contenttab_internal_rows" valign="top"><nobr><b>Father Mobile No</b></nobr></td>
                                    <?php
                                    if (strip_slashes($getStudentRegistrationInfo[0]['fatherMobile'])!=""){
                                    echo  '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($getStudentRegistrationInfo[0]['fatherMobile']),50,"<br>").'</td>';
                                    }
                                    else {
                                    echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                    }  ?>
                                    <td class="contenttab_internal_rows" valign="top"><nobr><b>Landline No</b></nobr></td>
                                    <?php
                                    if (strip_slashes($getStudentRegistrationInfo[0]['landlineNo'])!=""){
                                    echo  '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($getStudentRegistrationInfo[0]['landlineNo']),50,"<br>").'</td>';
                                    }
                                    else {
                                    echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                    }  ?> 
                                    </tr>
                                    <tr>
                                    <td class="contenttab_internal_rows" valign="top"><nobr><b>Father Email</b></nobr></td>
                                    <?php
                                    if (strip_slashes($getStudentRegistrationInfo[0]['parentEmail'])!=""){
                                    echo  '<td colspan="10" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($getStudentRegistrationInfo[0]['parentEmail']),50,"<br>").'</td>';
                                    }
                                    else {
                                    echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                    }  ?> 
                                    </tr>
                                    <tr>
                                    <td class="contenttab_internal_rows" valign="top"><nobr><b>Blood Group</b></nobr></td>
                                    <?php
                                    $bloodGroupArray=Array('1'=>'A+','2'=>'A-','3'=>'B+','4'=>'B-','5'=>'O+','6'=>'O-','7'=>'AB+','8'=>'AB-');
                                    for($i=1;$i<9;$i++){
                                    if($getStudentRegistrationInfo[0]['bloodGroup']==$i){
                                    $bloodGroup=$bloodGroupArray[$i];
                                    }        
                                    } 
                                    if (strip_slashes($getStudentRegistrationInfo[0]['bloodGroup'])!=""){
                                    echo  '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($bloodGroup),20,"<br>").'</td>';
                                    }
                                    else {
                                    echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                    }  ?>
                                    <td class="contenttab_internal_rows" valign="top"><nobr><b>Allergies</b></nobr></td>
                                    <?php
                                    if (strip_slashes($getStudentRegistrationInfo[0]['allergy'])!=""){
                                    echo  '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($getStudentRegistrationInfo[0]['allergy']),50,"<br>").'</td>';
                                    }
                                    else {
                                    echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                    }  ?> 
                                    </tr>
                                    
                                    
                                    <tr>
                                    <td class="contenttab_internal_rows" valign="top"><nobr><b>AIEEE Roll No.</b></nobr></td>
                                    <?php
                                    if (strip_slashes($getStudentRegistrationInfo[0]['aieeeRollNo'])!=""){
                                      echo  '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes(aieeeRollNo),50,"<br>").'</td>';
                                    }
                                    else {
                                    echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                    }  ?>
                                    <td class="contenttab_internal_rows" valign="top"><nobr><b>AIEEE Rank</b></nobr></td>
                                    <?php
                                    if (strip_slashes($getStudentRegistrationInfo[0]['aieeeRank'])!=""){
                                    echo  '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($getStudentRegistrationInfo[0]['aieeeRank']),50,"<br>").'</td>';
                                    }
                                    else {
                                    echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                    }  ?> 
                                    </tr>
                                    
                                    <tr>
                                     <td class="contenttab_internal_rows" valign="top"><nobr><b>City Native</b></nobr></td>
                                    <?php
                                    if (strip_slashes($getStudentRegistrationInfo[0]['cityNative'])!=""){
                                      echo  '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($getStudentRegistrationInfo[0]['cityNative']),60,"<br>").'</td>';
                                    }
                                    else {
                                    echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                    }  ?>
                                    <td class="contenttab_internal_rows" valign="top"><nobr><b>State Native</b></nobr></td>    
                                    <?php
                                           $stateCondition = " WHERE stateId = '".$getStudentRegistrationInfo[0]['stateNativeId']."'";
                                           $stateNativeArray = CommonQueryManager::getInstance()->getStates('stateName',$stateCondition); 
                                           $stateNative = $stateNativeArray[0]['stateName'];
                                            if (strip_slashes($stateNative)!=""){
                                            echo  '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.$stateNative.'</td>';
                                            }
                                            else {
                                            echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                            }
                                    ?> 
                                    </tr>
                                    
                                    <tr>
                                    <td class="contenttab_internal_rows" valign="top"><nobr><b>Hostel Name</b></nobr></td>
                                    <?php
                                    if (strip_slashes($getStudentRegistrationInfo[0]['hostelName'])!=""){
                                    echo  '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($getStudentRegistrationInfo[0]['hostelName']),50,"<br>").'</td>';
                                    }
                                    else {
                                    echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                    }  ?> 
                                    <td class="contenttab_internal_rows" valign="top"><nobr><b>Hostel Room No</b></nobr></td>
                                    <?php
                                    if (strip_slashes($getStudentRegistrationInfo[0]['roomNo'])!=""){
                                    echo  '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($getStudentRegistrationInfo[0]['roomNo']),50,"<br>").'</td>';
                                    }
                                    else {
                                    echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                    }  ?> 
                                    </tr>
                                    <tr>
                                    <td class="contenttab_internal_rows" valign="top"><nobr><b>Pick Up</b></nobr></td>
                                    <?php
                                    if (strip_slashes($getStudentRegistrationInfo[0]['pickUp'])!=""){
                                    echo  '<td colspan="10" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($getStudentRegistrationInfo[0]['pickUp']),50,"<br>").'</td>';
                                    }
                                    else {
                                    echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                    }  ?> 
                                    </tr>
                                    <tr>
                                    <td class="contenttab_internal_rows" valign="top"><nobr><b>PG Owner</b></nobr></td>
                                    <?php
                                    if (strip_slashes($getStudentRegistrationInfo[0]['pgOwner'])!=""){
                                    echo  '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($getStudentRegistrationInfo[0]['pgOwner']),100,"<br>&nbsp;&nbsp;&nbsp;").'</td>';
                                    }
                                    else {
                                    echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                    }  ?> 
                                    <td class="contenttab_internal_rows" valign="top"><nobr><b>PG Address</b></nobr></td>
                                    <?php
                                    if (strip_slashes($getStudentRegistrationInfo[0]['pgAddress'])!=""){
                                    echo  '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($getStudentRegistrationInfo[0]['pgAddress']),100,"<br>&nbsp;&nbsp;&nbsp;").'</td>';
                                    }
                                    else {
                                    echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                    }  ?> 
                                    </tr>
                                    <tr>
                                    <td class="contenttab_internal_rows" valign="top"><nobr><b>PG Contact</b></nobr></td>
                                    <?php
                                    if (strip_slashes($getStudentRegistrationInfo[0]['pgContact'])!=""){
                                    echo  '<td colspan="10" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($getStudentRegistrationInfo[0]['pgContact']),100,"<br>&nbsp;&nbsp;&nbsp;").'</td>';
                                    }
                                    else {
                                    echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                    }  ?> 
                                    </tr>
                                    <tr>
                                    <td class="contenttab_internal_rows" valign="top"><nobr><b>Company Name</b></nobr></td>
                                    <?php
                                    if (strip_slashes($getStudentRegistrationInfo[0]['companyName'])!=""){
                                    echo  '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($getStudentRegistrationInfo[0]['companyName']),100,"<br>&nbsp;&nbsp;&nbsp;").'</td>';
                                    }
                                    else {
                                    echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                    }  ?> 
                                    <td class="contenttab_internal_rows" valign="top"><nobr><b>Company Address</b></nobr></td>
                                    <?php
                                    if (strip_slashes($getStudentRegistrationInfo[0]['companyAddress'])!=""){
                                    echo  '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($getStudentRegistrationInfo[0]['companyAddress']),100,"<br>&nbsp;&nbsp;&nbsp;").'</td>';
                                    }
                                    else {
                                    echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
                                    }  ?> 
                                    </tr>    
                                  </td>
                               </table>
                             </td>
                          </tr>       
                          <tr>
                              <td valign="top" class="contenttab_internal_rows1" colspan="10">
                                <fieldset class="fieldset">
                                     <legend>Academic Details</legend>
                                     <table width="100%" border="0" cellspacing="2px" cellpadding="2px" >
                                       <tr class="row1">
                                            <td valign="middle" class="contenttab_internal_rows" height="25"><b>Class</b></td>
                                            <td valign="middle" class="contenttab_internal_rows"><b>Year of Passing</b></td>
                                            <td valign="middle" class="contenttab_internal_rows"><b>Name of Board/University</b></td>
                                            <td valign="middle" class="contenttab_internal_rows"><b>Education Stream</b></td>
                                            <td valign="middle" class="contenttab_internal_rows" align="left"><b>Marks Obtained</b></td>
                                            <td valign="middle" class="contenttab_internal_rows" align="left"><b>Max. Marks</b></td>
                                            <td valign="middle" class="contenttab_internal_rows" align="left"><b>Percentage(%)</b></td>
                                        </tr>
                                        <?php    
                                        global $classResults; 
                                        if(isset($classResults) && is_array($classResults)) {
                                            $count = count($classResults);  
                                            $studentId = $sessionHandler->getSessionVariable('StudentId'); 
                                            $studentRegistration = StudentRegistration::getInstance();    
                                            $academicRecordArrayReg = $studentRegistration->getStudentAcademicList( " WHERE sa.studentId = '".$studentId."'",'previousClassId');
                                            $academicCountReg = count($academicRecordArrayReg);
                                            for($i=0;$i<$academicCountReg;$i++){
                                                //echo "--".$academicRecordArray[$i]['previousClassId'];
                                                $rollArr[$academicRecordArrayReg[$i]['previousClassId']] = $academicRecordArrayReg[$i]['previousRollNo'];
                                                $sessionArrReg[$academicRecordArrayReg[$i]['previousClassId']] = $academicRecordArrayReg[$i]['previousSession'];
                                                $boardArrReg[$academicRecordArrayReg[$i]['previousClassId']] = $academicRecordArrayReg[$i]['previousBoard'];
                                                $marksArrReg[$academicRecordArrayReg[$i]['previousClassId']] = $academicRecordArrayReg[$i]['previousMarks'];
                                                $educationArrReg[$academicRecordArrayReg[$i]['previousClassId']] = $academicRecordArrayReg[$i]['previousEducationStream'];
                                                $maxMarksArrReg[$academicRecordArrayReg[$i]['previousClassId']] = $academicRecordArrayReg[$i]['previousMaxMarks'];
                                                $perArrReg[$academicRecordArrayReg[$i]['previousClassId']] = $academicRecordArrayReg[$i]['previousPercentage'];
                                            }
                                            foreach($classResults as $key=>$value) {
                                                $bg = $bg=='row0'?'row1':'row0';
                                                echo "<tr class='$bg'>
                                                <td valign='middle' class='contenttab_internal_rows' nowrap>".$value."</td>
                                                <td valign='middle' class='contenttab_internal_rows' >".$sessionArrReg[$key]."</td>
                                                <td valign='middle' class='contenttab_internal_rows' >".$boardArrReg[$key]."</td>
                                                <td valign='middle' class='contenttab_internal_rows' >".$educationArrReg[$key]."</td>
                                                <td valign='middle' class='contenttab_internal_rows' >".$marksArrReg[$key]."</td>
                                                <td valign='middle' class='contenttab_internal_rows' >".$maxMarksArrReg[$key]."</td>
                                                <td valign='middle' class='contenttab_internal_rows' >".$perArrReg[$key]."</td>
                                                
                                                </tr>";    
                                            }
                                        }
                                        ?>
                                      </table>  
                                </fieldset>        
                              </td>
                          </tr>
                          </table>
                         <?php
                                    }
                                }
                                else {
                                    echo '<tr><td colspan="5" align="center">No record found</td></tr>';
                                }
                              
                                    ?>
                            </table>
                 </div>                         
              </div>
                        <div class="dhtmlgoodies_aTab">
                            <div id="timeTableResultDiv"></div>  
                        </div>
                        <div class="dhtmlgoodies_aTab" style="overflow:auto">
                            <table width="100%" border="0" cellspacing="5" cellpadding="5">
                            <tr>
                                <td><div id="gradeResultDiv"></div></td>
                            </tr>
                            <tr id = 'saveDiv1' style='display:none'>
                                <td colspan='1' align='right' valign="middle"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printMarksReport();return false;"/></td>
                            </tr>
                            </table>
                        </div>
                        <div class="dhtmlgoodies_aTab">
                        <div id="scroll2" style="overflow:auto;HEIGHT:510px">
                        <table width="100%" border="0" cellspacing="1" cellpadding="1">
                        <tr class="row0">
                            <td>
                            <table border="0" cellspacing="1" cellpadding="0" align="center">
                            <tr>
                                <td><b>From Date</b></td>
                                <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                                <td>
                                <?php
                                   require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                   echo HtmlFunctions::getInstance()->datePicker('startDate2','');
                                ?></td>
                                <td width="20"></td>
                                <td><b>To Date</b></td>
                                <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                                <td> 
                                <?php
                                   require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                   echo HtmlFunctions::getInstance()->datePicker('endDate2','');
                                ?></td>    
                                <td width="5"></td>
                                <td><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/showlist.gif" onClick="getAttendance(<?php echo $REQUEST_DATA['id']?>,document.getElementById('startDate2').value,document.getElementById('endDate2').value);return false;"/></td>
                            </tr>
                            </table>
                            <td>
                        </tr>
                        <tr>
                            <td style="padding-right:10px"><div id="attendanceResultDiv"></div></td>
                        </tr>
                        <tr id = 'printDiv2' style='display:none'>
                                <td colspan='1' align='right' valign="middle"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printAttendanceReport();return false;"/></td>
                        </tr>
                        </table>
                        </div>
                        </div>
                        <div class="dhtmlgoodies_aTab" style="overflow:auto">
                        <table width="100%" border="0" cellspacing="5" cellpadding="5">
                         
                        <tr>
                            <td><div id="feesResultsDiv"></div>
                            </td>
                        </tr>
                        </table>
                        </div>
                    
                    <div class="dhtmlgoodies_aTab">
                    <table width="100%" border="0" cellspacing="2" cellpadding="2">
                        <tr class="row0">
                            <td>
                            <table border="0" cellspacing="1" cellpadding="0" align="right">
                            <tr>
                                <td><input type="text" id="searchbox" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" /></td>    
                                <td width="5"></td>
                                <td><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/search.gif" onClick="refreshResourceData(<?php echo $REQUEST_DATA['id']?>,document.addForm.studyPeriod.value);return false;"/></td>
                            </tr>
                            </table>
                            <td>
                        </tr> 
                        <tr>
                            <td valign="top"><div id="resourceResultsDiv" style="overflow:auto;HEIGHT:510px"></div></td>
                        </tr>
                      </table>                 
                   </div>    
                   <div class="dhtmlgoodies_aTab" style="overflow:auto">
                    <table width="100%" border="0" cellspacing="2" cellpadding="2">
                        <tr>
                            <td><div id="finalResultsDiv"></div></td>
                        </tr>
                        <tr id='printDiv3'>
                            <td colspan='1' align='right' valign="middle"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printFinalResultReport();return false;"/></td>
                        </tr>
                      </table>                 
                   </div>
                   <div class="dhtmlgoodies_aTab" style="overflow:auto">
                    <table width="100%" border="0" cellspacing="2" cellpadding="2">
                        <tr>
                            <td><div id="finalGradesDiv"></div></td>
                        </tr>
                        <tr>
                            <td align="right"><div id="showCGPA"></div></td>
                        </tr>
                        <tr id='printDiv4'>
                            <td colspan='1' align='right' valign="middle"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printGradeReport();return false;"/>&nbsp;<input type="image"  name="printGradeSubmit" id='generateCSV' onClick="printGradeCSV();return false;" value="printGradeSubmit" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" /></td>
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
                
                 <!--mentor comments-->
                 <div class="dhtmlgoodies_aTab" style="vertical-align:top;">
                    <div style="overflow:auto; width:100%; height:550px; vertical-align:top;">
                        <div id="mentorCommentsDiv"></div>
                    </div>
                 </div>        
                          

 <div class="dhtmlgoodies_aTab">

  <?php
  require_once(MODEL_PATH . "/DealManager.inc.php");
    $dealManager = DealManager::getInstance();
    $studentId= $REQUEST_DATA['id'];
    $condition= " studentId = '$studentId' ";
    $studentArray = $dealManager->getStudentDetails($condition);
 ?>
    <input type="hidden" name="dealStudentName" id="dealStudentName" value= "<?php echo $studentArray[0]['studentName']?>"</br>
    <input type="hidden" name="dealStudentEmail" id="dealStudentEmail" value= "<?php echo $studentArray[0]['studentEmail']?>"</br>
    <input type="hidden" name="dealStudentGender" id="dealStudentGender" value= "<?php echo $studentArray[0]['studentGender']?>"</br>
    <input type="hidden" name="dealStudentMobileNo" id="dealStudentMobileNo" value= "<?php echo $studentArray[0]['studentMobileNo']?>"</br>
    
     <div id='divDeal' style="overflow:auto; width:970px; height:580px; vertical-align:top;">
        <div id="dealInformationDiv" style="width:98%; vertical-align:top;"></div>
     </div>        
</div>    

                 
 <div class="dhtmlgoodies_aTab" style="overflow:auto">
              <form name='messageFrm' id='messageFrm'>
                           <table width="100%" border="0" cellspacing="2" cellpadding="2">
                           <tr>
                               <td>
                   
                   <div id="messageCorrespondenceResultDiv"></div>
                   </td>
               </tr>     
                           </table>
              </form>    
                       </div>

                 
                       
                       
<?php floatingDiv_Start('divMessage','Brief Description','',''); ?>
<form name="MessageForm" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
    <tr>
    <tr>
        <td width="89%" style="padding-left:5px">
            <div id="scroll2" style="overflow:auto; width:350px; height:200px; vertical-align:top;">
                <div id="message" style="width:98%; vertical-align:top;"></div>
            </div>
        </td>
    </tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<?php floatingDiv_Start('divAcademic','Academic','',''); ?>
<form name="AcademicForm" action="" method="post" onsubmit="return false;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<tr>
    <td class="contenttab_internal_rows" height="3px"><b>Class:&nbsp;</b></td>
    <td width="100%" style="padding-left:5px">
        <select id='acdClass' name='acdClass' class="" onchange=" populateValues(); return false;">
        <?php
         foreach($classResults as $key=>$value) {   
            echo "<option value='".$key."'>".$value."</option>";    
         }
        ?>
        </select>
    </td>
</tr>
<tr>
    <td width="100%" colspan="2" style="width:500px;" >
        <div id="tableDiv" style="height:190px;width:520px;overflow:auto;">
<table width="100%" border="0" cellspacing="2" cellpadding="0" id="anyid">
        <tbody id="anyidBody">
         <tr class="rowheading">
     <td width="5%" class="contenttab_internal_rows" ><b>#</b></td>
      <td width="45%" class="contenttab_internal_rows"><b>Subjects</b></td>
      <td width="20%" class="contenttab_internal_rows"><b>Marks Obtained</b></td>
     <td width="20%" class="contenttab_internal_rows"><b>Max.Marks </b></td>
       <td width="10%" class="contenttab_internal_rows"><b>Delete</b></td>
        </tr>
        </tbody>
</table>
        </div>
    </td>
</tr>
<tr>
    <td colspan="2">
        <input type="hidden" name="deleteFlag" id="deleteFlag" value="" />
       <a href="javascript:addOneRow(1);" title="Add Row"><font class="textClass"><b><nobr><u>Add More</u></b></font></a>
    </td>
</tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="4">
    <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="validateAddForm(this.form,'Add');return false;" />
    <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('divAcademic');return false;" />
    </td>
</tr>
</table>
</form>
<?php floatingDiv_End(); 

          $showDeal='';     
          $showDeal1='';     
              if($sessionHandler->getSessionVariable('ALLOW_VIEW_DEALS') == 1) {
             require_once(MODEL_PATH .'/DealManager.inc.php');
             $dealManager = DealManager::getInstance();
             $condition = " classId = '".$studentDataArr[0]['classId']."'";    
             $resultArray = $dealManager->getDealDetails($condition);
             if(is_array($resultArray) && count($resultArray)>0 ) {
                $showDeal=",'Deals&nbsp;<img src=".IMG_HTTP_PATH."/new_tri_deal.gif>'";
            $showDeal1=',false'; 
             }
        }
?>
        

              <script type="text/javascript">
                    initTabs('dhtmlgoodies_tabView1',
Array('Personal Info','Parents Info','Sibling','Course','Administrative','Schedule','Marks','Attendance','Fees','Resource','Final Result','Grades','Offense/Achv','Mentor Comments'<?php echo $showDeal; ?>),0,985,580,
Array(false,false,false,false,false,false,false,false,false,false,false,false,false<?php echo $showDeal1; ?>));
                   </script>
    <?php 
            
               global $sessionHandler;
               $roleId = $sessionHandler->getSessionVariable('RoleId');    
                  
                   if($roleId!=1) {         
    
                        
                         $studentManager = StudentManager::getInstance();
                        
                           $blockedTab = $studentManager->getBlockedTab("Student Details");
                            
     
                    for($i=0;$i<count($blockedTab);$i++){
                    ?>
                         <script type="text/javascript">
            deleteTab("<?php echo $blockedTab[$i]['frameName']; ?>");                       
            </script>
            <?php    
            }
                 }
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
