<?php 
//-------------------------------------------------------
// THIS FILE Is Used As A Template For Registration Form
// Author : Ankur Aggarwal
// Created on : 25-July-2011
// Copyright 2011-2012: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();

$studentName1 = $sessionHandler->getSessionVariable('StudentName'); 
//CHECKING ALLOWED REG FROM OUTSIDE CAMPUS

require_once(MODEL_PATH. "/RegistrationForm/ScStudentRegistration.inc.php");
 $studentRegistration = StudentRegistration::getInstance();
 $regIpCheck=$sessionHandler->getSessionVariable('REGISTRATION_ONWER');
 $userIp = $_SERVER['REMOTE_ADDR'];    
?>
<form action="" method="POST" name="registrationForm" id="registrationForm" onSubmit="return false;">
<select class="inputbox" style="display:none" name="hiddenRouteStop" id="hiddenRouteStop">
   <option value=''></option>
   <?php
      require_once(BL_PATH.'/HtmlFunctions.inc.php');
      echo HtmlFunctions::getInstance()->getRouteStopRegistration();
   ?>
</select>
<?php
  $requireAIEEE='';
   
   $AIEEEROLLNOREQUIRED =   array(1 => "__CUN110",
                                  2 => "__CUN120",
                                  3 => "__CUN120",
                                  4 => "__B10001",
                                  5 => "__B10002",
                                  6 => "__B10003",
                                  7 => "__B11001",
                                  8 => "__B11002",
                                  9 => "__B11003");     
                                  
                                  

   $ACADEMIC_PLUS_TWO_NOT_REQUIRED =   array(1 => "I09901",
                                             2 => "I09902",
                                             3 => "I10901",
                                             4 => "I10902");   
                                                                                 
   $ACADEMIC_GRADUCATE_REQUIRED =   array(1 => "M09030",
                                          2 => "M10030");                                               
       
   
  $idNo = strtoupper(substr(trim($studentDataArr[0]['universityRollNo']),0,6));
 
  // Check for AIEEE Roll No.
  $requireAIEEE = '';   
  for($i=1;$i<=count($AIEEEROLLNOREQUIRED);$i++) {
     if($idNo==$AIEEEROLLNOREQUIRED[$i]) {
       $requireAIEEE = '1';    
       break; 
     }
   }
   if($requireAIEEE == '1') {
     echo '<input type="hidden" id="chkAIEEE" name="chkAIEEE" value="1">';        
     $requireAIEEE = REQUIRED_FIELD; 
   }
   else {
     echo '<input type="hidden" id="chkAIEEE" name="chkAIEEE" value="0">';          
     $requireAIEEE = ''; 
   }
  
   // Check for 10+2
   $require2=''; 
   for($i=1;$i<=count($ACADEMIC_PLUS_TWO_NOT_REQUIRED);$i++) {
     if($idNo==$ACADEMIC_PLUS_TWO_NOT_REQUIRED[$i]) {
        $require2 = '1';    
        break;
     }
   }
   
   if($require2 == '') {   
     echo '<input type="hidden" id="chkAcademic_2" name="chkAcademic_2" value="1">';        
     $require2 = REQUIRED_FIELD; 
   }
   else {
     echo '<input type="hidden" id="chkAcademic_2" name="chkAcademic_2" value="0">';          
     $require2 = ''; 
   }
  
   // Check for Graducate
   $require3=''; 
   for($i=1;$i<=count($ACADEMIC_GRADUCATE_REQUIRED);$i++) {
     if($idNo==$ACADEMIC_GRADUCATE_REQUIRED[$i]) {
       $require3 = '1';    
       break; 
     }
   }
   if($require3 == '1') { 
     echo '<input type="hidden" id="chkAcademic_3" name="chkAcademic_3" value="1">';        
     $require3 = REQUIRED_FIELD; 
   }
   else {
     echo '<input type="hidden" id="chkAcademic_3" name="chkAcademic_3" value="0">';          
     $require3 = ''; 
   }
  
?>
<input type="hidden" id="allowIpCheck" name="allowIpCheck"  value="1">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td height="10"></td>
                </tr>
                <tr>
                    <td valign="top" colspan="2">Registration Form</td>
                </tr>
            </table>
            </td>
    </tr>
     <tr>
        <td valign="top">
<?php
    //0 means outside Ip not allowed
   
  //0 means outside Ip not allowed
    $checkBit='1';
    if($regIpCheck=='0'){
       $regIpCheckValue=$studentRegistration->getAllowedIp($userIp);
	
       if($regIpCheckValue[0]['cnt']==0){
          //0 means ip is not in database which is allowed for regestration
          $errorMessage = "<br><br><br><br>Users are allowed to fill the registration form only from Institute premises.<br>You cannot access the registration form from outside institute premises.";
          $checkBit='0';			 
          echo "<script> try { document.getElementById('allowIpCheck').value='0'; } catch(e){ } </script>";
          echo "<div style='height:350px;'><h1><p><center><font color=red>$errorMessage</font></center></p></h1></div>";
       }
    }
 
   if($regIpCheck=='1' || ($checkBit=='1' && $regIpCheck=='0')  ) {
?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
                <tr>
                    <td valign="top" class="content">
                        <!-- form table starts -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="contenttab_border2">
                            <tr>  
                               <td class="contenttab_row" align="center" width="100%" cellspacing="0px" cellpadding="0px">
                                 <nobr><b>REGISTRATION FORM</b></nobr><br><br> 
                                 <table width="60%" border="0" cellspacing="0px" cellpadding="0px" class="" align="center"> 
                                 <tr>
                                   <td  class="contenttab_internal_rows"  width="9%" nowrap ><nobr><b>Date of Registration</b></nobr></td>
                                   <td  class="padding" nowrap width="1%"><nobr><b>:</b></nobr></td>
                                   <td  class="contenttab_internal_rows" width="40%" colspan="4" nowrap><nobr>&nbsp;
                                   <?php
                                        $dt = date('Y-m-d');
                                        echo UtilityManager::formatDate($dt); 
                                   ?></nobr></td> 

				       <td  class="contenttab_internal_rows" nowrap><nobr><b>Current Semester / Session</b></nobr></td>
                                       <td  class="padding" nowrap><nobr><b>:</b></nobr></td>
                                       <td  class="contenttab_internal_rows" nowrap colspan="4"><nobr>&nbsp;
                                       <?php 
                                            echo parseOutput($periodName); 
                                            echo " / ".$sessionHandler->getSessionVariable('SessionName');
                                       ?></nobr>
                                       </td> 
                                  </tr>
                 <table width="100%" border="0" cellspacing="0" cellpadding="0">
   
  	  <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
             <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="content_title">Registration Form: </td>
                     </tr>
                    </table>
                </td>
             </tr>
              <tr>
                <td class="contenttab_row" valign="top" >
<tr>
						<td valign="top">
						<fieldset class="fieldset">
						<legend>Student Details</legend>
						<table width="100%" border="0" cellspacing="0" cellpadding="0" >
						<tr>
							<td height="5"></td>
						</tr>
                        <tr>    
                            <td class="contenttab_internal_rows"><nobr><b>Date of Birth :</b><?php echo REQUIRED_FIELD?></nobr></td>
                            <td class="contenttab_internal_rows" align="left">
                                <?php  
                                    $dt='';
                                    if($studentDataArr[0]['dateOfBirth']!='0000-00-00') {
                                      $dt=$studentDataArr[0]['dateOfBirth'];  
                                    }
                                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                    echo HtmlFunctions::getInstance()->datePicker('dateOfBirth',$dt);  
                                ?>&nbsp;&nbsp;(in 10th class certificate)
                            </td>     
                        </tr>
						<tr>	
							<td class="contenttab_internal_rows"><nobr><b>Student Name :</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="padding_top"><input type="text" id="name" name="name"  maxlength="60" style='width:250px' value="<?php echo $studentDataArr[0]['firstName'].' '.$studentDataArr[0]['lastName']; ?>" readonly='true'>
						        <td width="50%"/>
							<td class="contenttab_internal_rows"><nobr><b>Father's Name : </b><?php echo REQUIRED_FIELD?></nobr></td>   
							<td class="padding_top"><input type="text" id="fatherName" name="fatherName" style='width:250px'  maxlength="60" value="<?php echo $studentDataArr[0]['fatherName']; ?>" readonly='true' />
						</tr>
						<tr>	
							<td class="contenttab_internal_rows"><nobr><b>Id No. :</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="padding_top"><input type="text" id="universityRollNo" style='width:250px' name="universityRollNo"   maxlength="60"  value="<?php echo $studentDataArr[0]['universityRollNo']; ?>" readonly="true" />
							 <td width="50%"/>
							<td class="contenttab_internal_rows"><nobr><b>Latest Email Id of the Parent :</b></nobr></td>
							<td class="padding_top">
                            <input type="text" id="parentEmail" style='width:250px' name="parentEmail"  maxlength="60" value="<?php echo $studentDataArr[0]['fatherEmail'];?>" />
                            </td>
						</tr>
						<tr>	
							<td class="contenttab_internal_rows"><nobr><b>Latest Email Id of the Student :</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="padding_top"><input type="text" style='width:250px' id="studentEmail" name="studentEmail"   maxlength="60" value="" />
							 <td width="50%"/>
							<td class="contenttab_internal_rows"><nobr><b>Latest Mobile No. of the Parent:</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="padding_top">
                            <input type="text" style='width:250px' id="parentsNumber" name="parentsNumber"  maxlength="50"  >
                            </td>
						</tr>
						<tr>	
                            <td class="contenttab_internal_rows"><nobr><b>Latest Mobile No. of the Student :</b><?php echo REQUIRED_FIELD?></nobr></td>
                            <td class="padding_top">
                            <input type="text" style='width:250px' id="studentNumber" name="studentNumber"  maxlength="10" value="<?php echo $studentDataArr[0]['studentMobileNo'];?>"/>
                            <td width="50%"/>
                            <td class="contenttab_internal_rows"><nobr><b>Latest Landline No.:</b></nobr></td>
                            <td class="padding_top"><input type="text" style='width:250px' id="landlineNo" name="landlineNo"  maxlength="50"  >
                            </td>
						</tr>
						<tr>	
							<td class="contenttab_internal_rows"><nobr><b>Blood Group :</b><?php echo REQUIRED_FIELD?></nobr></td>
						        <td class="contenttab_internal_rows" align='left'>
                                <select class="inputbox" style='width:145px' size="1"  name="bloodGroup" id="bloodGroup">
                                                        <option value="" selected="yes">Select</option>
							<option value="1" >A+</option>
							<option value="2" >A-</option>	
							<option value="3">B+</option>
							<option value="4" >B-</option>	
							<option value="5">O+</option>
							<option value="6" >O-</option>	
							<option value="7">AB+</option>
							<option value="8" >AB-</option>							
							</select></td>
                                                        <td width="50%"/>
							<td class="contenttab_internal_rows"><nobr><b>Allergies (If Any)</b></nobr></td>
							<td class="padding_top"><input type="text" style='width:250px' id="allergy" name="allergy"  maxlength="50">
</td>

						</tr>
						<tr>
						<tr>
							<td class="contenttab_internal_rows"><nobr><b>Name of Teacher Mentor :</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="padding_top"><input type="text" style='width:250px' id="mentor" name="mentor"  maxlength="60" value="<?php echo strtoupper($studentDataArr[0]['mentor']);?>" readonly=true />
                            <td></td>
						    <td  valign='top' class="contenttab_internal_rows"><nobr><b>Latest Correspondence Address : </b></nobr><?php echo REQUIRED_FIELD?></td>
							<td rowspan="2" valign='top' class="padding_top"><textarea  style='width:250px' id="address" name="address" cols="26" rows="2"  ><?php echo $studentDataArr[0]['permAddress1'];?></textarea></td>
						</tr>
						 <tr>
                                <td class="contenttab_internal_rows"><nobr><b>CGPA Till Last Term :</b>
							<?php 
                                $studentDataArr[0]['cgpa']="";
                                if (strip_slashes($studentDataArr[0]['cgpa'])!=""){
                                  echo REQUIRED_FIELD."</nobr></td>";
                                  echo "<td class='padding_top'><input type='text' style='width:250px' id='cgpa' name='cgpa' class='validate[required]' maxlength='60' value=".$studentDataArr[0]['cgpa']." readonly='true' />";
								}
								else {
                                  echo "</nobr></td>";
								  echo "<td class='padding_top'>
                                            <input type='text' style='width:250px' id='cgpa' name='cgpa' class='validate[required]' maxlength='60' value= '' />
                                            "; 
                                }?>					 	
						</tr>
                        <tr>
                            <td class="contenttab_internal_rows" width="110"><nobr><b>JEE Main/AIEEE Roll No. :</b><?php echo $requireAIEEE; ?></nobr></td>
                            <td class="contenttab_internal_rows" align='left'> 
                               <input type="text" style='width:250px' id="aieeeRollNo" name="aieeeRollNo"  maxlength="50">
                            </td>
                            <td></td>
                            <td class="contenttab_internal_rows" width="110"><nobr><b>Total Marks/AIEEE Rank :</b><?php echo $requireAIEEE; ?></nobr></td>
                            <td class="contenttab_internal_rows" align='left'> 
                               <input type="text" style='width:250px' id="aieeeRank" name="aieeeRank"  maxlength="50">
                            </td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows" width="110"><nobr><b>City (Native) :</b><?php echo REQUIRED_FIELD?></nobr></td>
                            <td class="contenttab_internal_rows" align='left'> 
                               <input type="text" style='width:250px' id="cityNative" name="cityNative"  maxlength="50">
                            </td>
                            <td></td>
                            <td class="contenttab_internal_rows" width="110"><nobr><b>State (Native) :</b><?php echo REQUIRED_FIELD?></nobr></td>
                            <td class="contenttab_internal_rows" align='left'> 
                               <select size="1" class="selectfield" name="stateNative" id="stateNative" style='width:255px'>
                                 <option value="">Select</option>
                                  <?php
                                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                     echo HtmlFunctions::getInstance()->getStatesData();
                                  ?>
                               </select>
                            </td>
                        </tr>       
                        
						<tr>
							<td class="contenttab_internal_rows" width="110"><nobr><b>Scholar Status :</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="contenttab_internal_rows" align='left'> 
                            <select class="inputbox" onchange="getStatus(); return false;" size="1" style='width:250px'  name="status" id="status">
                          		<option value="" selected="yes">Select</option>
								 <?php if($studentDataArr[0]['scholarType']==1){?><option value="1">Day Scholar</option><?php  }
									elseif($studentDataArr[0]['scholarType']==0 && strlen($studentDataArr[0]['scholarType'])>0){ ?>	
				 				<option value="2" >Hostler</option><?php } else {?>		
						        <option value="3">PG</option>
								<option value="4">Trainee</option>
								<option value="5">Other</option><?php } ?>			
							</select></td>
						</tr>
						<tr id='hostel1' style="display:none;">
						<td class="contenttab_internal_rows"><nobr><b>Hostel Name :</b><?php echo REQUIRED_FIELD?></nobr></td>
						<td class="contenttab_internal_rows" align="left">
                           <select class="inputbox" style="width:250px;" onchange="getHostelDetails(); return false;" size="1"  name="hostelName" id="hostelName">
                             <option value="" selected="yes">Select</option>
						     <?php
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getHostelRegistrationData();
                             ?>
 						   </select>
                        </td>
                      	<td width="50%"></td>
						<td class="contenttab_internal_rows"><nobr><b>Room No :</b><?php echo REQUIRED_FIELD?></nobr></td>
						<td class="padding_top"><input style="width:255px;" type="text" id="roomNo" name="roomNo"  maxlength="60"/>
                        </tr>
   			            <tr  id='hostel2' style="display:none;">
							<td class="contenttab_internal_rows"><nobr><b>Warden Name :</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="padding_top"><input type="text" style="width:250px;" id="wardenName" name="wardenName"  maxlength="60" />					
                            <td width="50%" />
							<td class="contenttab_internal_rows"><nobr><b>Contact No. of Warden :</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="padding_top"><input type="text" style="width:255px;" id="wardenContact" name="wardenContact"   maxlength="20"   /></td>
						</tr>
						<tr id="travel0" style="display:none;">	
							<td class="contenttab_internal_rows" width="110"><nobr><b>Travelling In :</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="contenttab_internal_rows" align="left">
                                <select onchange="getTravel(); return false;" style="width: 250px;" class="inputbox" size="1"  name="travel" id="travel" >
							        <option value="" selected="yes">Select</option>
							            <option value="1">University Bus</option>
							  <option value="2">Private Vehicle</option>
							<option value="3">Living in PG (Not University Bus)</option>							
							    </select>
                            </td>
						</tr>
						<tr  id='travel1' style="display:none;">
		                    <td class="contenttab_internal_rows"><nobr><b>Route No. :</b><?php echo REQUIRED_FIELD?></nobr></td>
		                    <td class="contenttab_internal_rows" align="left">
                              <select class="inputbox" onchange="getTravelDetails(); return false;" size="1"  style="width: 250px;" name="routeNo" id="routeNo">
                              </select>
	 	                    </td>
		                    <td width="50%"/>
		                    <td class="contenttab_internal_rows"><nobr><b>Pick Up:</b><?php echo REQUIRED_FIELD?></nobr></td>
		                    <td class="contenttab_internal_rows" align="left">
		                      <select class="inputbox" size="1" style="display:none; width: 255px;"  name="route1" id="route1" onchange="document.getElementById('pickUp').value=document.getElementById('route1').value;">
                              </select>
	                        </td>
						</tr>
						<tr  id='travel2' style="display:none;">
							<td class="contenttab_internal_rows"><nobr><b>Travelling From (Mention Place) :</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="padding_top"><input type="text" id="travellingPt" name="travellingPt"  maxlength="60" /> 					<td width="50%"/>	
							<td class="contenttab_internal_rows"><nobr><b>Vehicle Registration Number :</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="padding_top"><input type="text" id="vehicleRegistration" name="vehicleRegistration"  maxlength="60"  />
						</tr>
						<tr  id='travel3' style="display:none;">
						   <td class="contenttab_internal_rows"><nobr><b>Type of Vehicle :</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="padding_top"><input type="text" id="vehicleType" name="vehicleType"  maxlength="60"  />
					    </tr>
					    <tr  id='travel12' style="display:none;">                           
							<td class="contenttab_internal_rows"><nobr><b>Name of the PG Owner :</b><?php echo REQUIRED_FIELD?></nobr></td>
							 <td class="contenttab_internal_rows" align='left'> 
                               <input type="text" style='width:250px' id="PgName" name="PgName"  maxlength="50">
                            </td> <td width="50%"/>						
							<td class="contenttab_internal_rows"><nobr><b>Address of PG :</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td rowspan="2" valign='top' class="padding_top"><textarea  style='width:250px' id="address1" name="address1" cols="26" rows="2"  ></textarea></td>
						</tr>
						</tr>
						<tr  id='travel13' style="display:none;">
						     <td class="contenttab_internal_rows"><nobr><b>PG Contact No. :</b><?php echo REQUIRED_FIELD?></nobr></td>
							 <td class="contenttab_internal_rows" align='left'> 
                               <input type="text" style='width:250px' id="pgContact1" name="pgContact1"  maxlength="50">
                            </td> 	
					    </tr>
					    <tr  id='pg1' style="display:none;">
							<td class="contenttab_internal_rows"><nobr><b>Name of the Owner of PG :</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="padding_top"><input type="text" id="pgOwner" name="pgOwner" maxlength="60" style='width:250px'  />							<td width="50%" />
							<td class="contenttab_internal_rows"><nobr><b>Contact No. of Owner :</b></nobr></td>
							<td class="padding_top"><input type="text" id="pgContact" name="pgContact" style='width:250px' maxlength="20"/></td>
						
						</tr>
						<tr id='pg2' style="display:none;">
						    <td class="contenttab_internal_rows" valign='top' ><nobr><b>Address of PG :</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td colspan="2" class="contenttab_internal_rows" align="left">
                              <textarea  id="pgAddress" name="pgAddress" style='width:250px' cols="26"  ></textarea>
                            </td>	
						</tr>
					    <tr id='trainee1' style="display:none;">
							<td class="contenttab_internal_rows" valign="top">
                              <nobr><b>Name of the Company :</b><?php echo REQUIRED_FIELD?></nobr>
                            </td>
							<td class="padding_top" valign="top">
                              <input type="text" style="width:250px" id="companyName" name="companyName" maxlength="60"  />						
                            </td>
                            <td width="50%"/>
						    <td class="contenttab_internal_rows" valign="top">
                              <nobr><b>City :</b><?php echo REQUIRED_FIELD?></nobr>
                            </td>
                            <td class="padding_top" valign="top">
                              <input type="text" style="width:250px" id="companyCity" name="companyCity" maxlength="60"  />                        
                            </td>
						</tr>
                        <tr id='trainee2' style="display:none;">
                            <td class="contenttab_internal_rows" valign="top">
                              <nobr><b>Name of HR :</b><?php echo REQUIRED_FIELD?></nobr>
                            </td>
                            <td class="padding_top" valign="top">
                              <input type="text" style="width:250px" id="companyHR" name="companyHR" maxlength="60"  />                        
                            </td>
                            <td width="50%"/>
                            <td class="contenttab_internal_rows" valign="top">
                              <nobr><b>Email Id :</b><?php echo REQUIRED_FIELD?></nobr>
                            </td>
                            <td class="padding_top" valign="top">
                              <input type="text" style="width:250px" id="companyEmailId" name="companyEmailId" maxlength="60"  />                        
                            </td>                            
                        </tr> 
                        <tr id='trainee3' style="display:none;">
                            <td class="contenttab_internal_rows" valign="top">
                              <nobr><b>Contact No. :</b><?php echo REQUIRED_FIELD?></nobr>
                            </td>
                            <td class="padding_top" valign="top">
                              <input type="text" style="width:250px" id="companyContactNo" name="companyContactNo" maxlength="60"  />                        
                            </td>
                            <td width="50%"/>
                            <td class="contenttab_internal_rows" valign="top">
                              <nobr><b>Project Title :</b><?php echo REQUIRED_FIELD?></nobr>
                            </td>
                            <td class="padding_top" valign="top">
                              <input type="text" style="width:250px" id="companyProjectName" name="companyProjectName" maxlength="60"  />                        
                            </td>                            
                        </tr> 
                        <tr id='trainee4' style="display:none;">
                            <td class="contenttab_internal_rows" valign="top"><nobr><b>Address of Company :</b><?php echo REQUIRED_FIELD?></nobr></td>
                            <td class="contenttab_internal_rows">
                              <textarea rows="3" style="width:250px" id="companyAddress" name="companyAddress" cols="26"  ></textarea>
                            </td>
                        </tr>    
                     </table>   
                 </fieldset>
                 <br>
			     <fieldset class="fieldset">
                     <legend>Academic Details</legend>
                     <table width="100%" border="0" cellspacing="0" cellpadding="0" >
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
                           
                            $optionAcademic=''; 
                            global $classResults;
                            if(isset($classResults) && is_array($classResults)) {
                                $count = count($classResults);
                                foreach($classResults as $key=>$value) {
                                    $str= $value;
                                    if($value=='Matric') {
                                      $str .= REQUIRED_FIELD;  
                                    }
                                    if($value=='10+2') {
                                      $str .= $require2;  
                                    }
                                    if($value=='Graduation') {
                                      $str .= $require3;  
                                    }  
                                    $optionAcademic .="<option value='$key'>$value</option>";
                                    $bg = $bg=='row0'?'row1':'row0';
                                    echo "<tr class='$bg'>
                                    <td valign='middle' class='contenttab_internal_rows' nowrap>".$str."</td>
                                    <td valign='middle' class='contenttab_internal_rows' >
                                        <input type='text' id='session".$key."' name='session[]' class='inputbox1' maxlength='10' size='10' value='".$sessionArrReg[$key]."'>
                                    </td>
                                    <td valign='middle' class='contenttab_internal_rows' >
                                        <input type='text' id='board".$key."' name='board[]' class='inputbox' maxlength='250' value='".$boardArrReg[$key]."'>
                                    </td>
                                    <td valign='middle' class='contenttab_internal_rows' >
                                        <input type='text' id='educationStream".$key."' name='educationStream[]' class='inputbox1' style='width:80px' maxlength='50' value='".$educationArrReg[$key]."'>
                                    </td>
                                    <td valign='middle'  class='contenttab_internal_rows' align='left'>
                                        <input type='text' id='marks".$key."' name='marks[]' class='inputbox1' maxlength='6' onKeyup='calculatePercentageWithoutMsg(".$key.")' size='8'  value=".$marksArrReg[$key].">
                                        <input type='hidden' id='previousClass".$key."' name='previousClass[]' value='".$key."'/>
                                        <input type='hidden' id='previousClassId".$key."' name='previousClassId' value='".$key."'/>
                                    </td>
                                    <td valign='middle'  class='contenttab_internal_rows' align='left'>
                                        <input type='text' id='maxMarks".$key."' name='maxMarks[]' class='inputbox1' onKeyup='calculatePercentageWithoutMsg(".$key.")' maxlength='6' size='8'  value=".$maxMarksArrReg[$key].">
                                    </td>
                                    <td valign='middle' class='contenttab_internal_rows' align='left'>  
                                      <input  type='text' id='percentage".$key."' name='percentage[]' class='inputbox1' maxlength='8' size='8' readonly value=".$perArrReg[$key].">
                                    </td>
                                    </tr>";
                                }
                            }
                            ?>
                            <select name="ttAcademicClass" id="ttAcademicClass" style="display:none">
                              <?php echo $optionAcademic; ?>
                            </select>
                     </table>
                 </fieldset>     
 				<tr>
      				              <td height="10"></td>
         			       </tr>
					 <tr>
              				      <td height="10"></td>
               				 </tr>
		</table>
 
 
	
				   <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20"  class="contenttab_border">
                                        <tr>
                                            <td colspan="1" class="content_title" align="left">Course Details :</td>
                                           
                                        </tr>
					
                                    </table>
				   
                                   <div id="scroll2"  class="dhtmlgoodies_aTab" style="overflow:auto; height:410px; vertical-align:top;">
                                     <div id="subjectsDiv"  class="dhtmlgoodies_aTab" style="width:100%; vertical-align:top;"></div>
                                   </div> 
                                	
 			</table>
                
                
	</table>
		<tr>
                 <td class="contenttab_internal_rows"><nobr><b><input type="checkbox" name="undertaking" id="undertaking" value="yes" >All the details given by me in this Application Form, declaration/undertaking are true and correct to the best of my knowledge.</input></b></nobr></td>
                </tr>
		<tr>
			<td colspan="2" class="content_title" align="right">
                        <input type="image" name="studentPrintSubmit" value="studentPrintSubmit" src="<?php echo IMG_HTTP_PATH;?>/submit.gif" onclick="return validateLoginForm();return false;" />
                        </td>
		</tr>
  </table>
        <input readonly type="hidden" name="studentId"      id="studentId"      value="<?php echo $studentDataArr[0]['studentId']; ?>">
        <input readonly type="hidden" name="currentClassId" id="currentClassId" value="<?php echo $studentDataArr[0]['classId']; ?>">
        <input type="hidden" name="pickUp" id="pickUp" value="">

</form>
<?php

}
?>
