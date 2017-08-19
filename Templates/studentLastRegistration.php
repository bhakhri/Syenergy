<?php
  global $FE;
  require_once($FE . "/Library/common.inc.php");
  require_once(BL_PATH . "/UtilityManager.inc.php");  
  require_once(MODEL_PATH . "/CommonQueryManager.inc.php");

  require_once(MODEL_PATH . "/RegistrationForm/ScStudentRegistration.inc.php");
  $studentRegistration = StudentRegistration::getInstance();
  
  global $sessionHandler; 
  $roleId=$sessionHandler->getSessionVariable('RoleId');
  
  if($roleId=='3'){
    $studentId = $sessionHandler->getSessionVariable('StudentId');
  }
  else if($roleId=='4'){
    $studentId = $sessionHandler->getSessionVariable('StudentId');
  }
  else {
    if(trim($REQUEST_DATA['id'])!='') {  
      $studentId = $REQUEST_DATA['id']; 
    }
    else {
      $studentId = $REQUEST_DATA['studentId'];  
    }
  }
  if($studentId=='') {
    $studentId='0';  
  }
  $getStudentRegistrationInfo=$studentRegistration->getStudentInfo($studentId);
?>
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
            <td class="contenttab_internal_rows" valign="top"><nobr><b>City</b></nobr></td>
            <?php
            if (strip_slashes($getStudentRegistrationInfo[0]['companyCity'])!=""){
               echo  '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($getStudentRegistrationInfo[0]['companyCity']),100,"<br>&nbsp;&nbsp;&nbsp;").'</td>';
            }
            else {
            echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
            }  ?> 
        </tr>  
        <tr>
            <td class="contenttab_internal_rows" valign="top"><nobr><b>Name of HR</b></nobr></td>
            <?php
            if (strip_slashes($getStudentRegistrationInfo[0]['companyHR'])!=""){
               echo  '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($getStudentRegistrationInfo[0]['companyHR']),100,"<br>&nbsp;&nbsp;&nbsp;").'</td>';
            }
            else {
               echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
            }  ?> 
            <td class="contenttab_internal_rows" valign="top"><nobr><b>Email Id</b></nobr></td>
            <?php
            if (strip_slashes($getStudentRegistrationInfo[0]['companyEmail'])!=""){
               echo  '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($getStudentRegistrationInfo[0]['companyEmail']),100,"<br>&nbsp;&nbsp;&nbsp;").'</td>';
            }
            else {
            echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
            }  ?> 
        </tr> 
        <tr>
            <td class="contenttab_internal_rows" valign="top"><nobr><b>Contact No.</b></nobr></td>
            <?php
            if (strip_slashes($getStudentRegistrationInfo[0]['companyContactNo'])!=""){
               echo  '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($getStudentRegistrationInfo[0]['companyContactNo']),100,"<br>&nbsp;&nbsp;&nbsp;").'</td>';
            }
            else {
               echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
            }  ?> 
            <td class="contenttab_internal_rows" valign="top"><nobr><b>Project Title</b></nobr></td>
            <?php
            if (strip_slashes($getStudentRegistrationInfo[0]['companyProjectName'])!=""){
               echo  '<td class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.chunk_split(strip_slashes($getStudentRegistrationInfo[0]['companyProjectName']),100,"<br>&nbsp;&nbsp;&nbsp;").'</td>';
            }
            else {
            echo '<td valign="top" class="contenttab_internal_rows1" align="left" valign="top"><b>:</b>&nbsp;&nbsp;'.NOT_APPLICABLE_STRING.'</td>';
            }  ?> 
        </tr> 
        <tr>
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
                
                $academicRecordArrayReg = $studentRegistration->getStudentAcademicList( " WHERE sa.studentId = '".$studentId."'",'previousClassId');
                $academicCountReg = COUNT($academicRecordArrayReg);
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
                    <td valign='middle' class='contenttab_internal_rows' >".$perArrReg[$key]."</td
                    </tr>";    
                }
            }
            ?>
          </table>  
    </fieldset>        
  </td>
</tr>
</table>