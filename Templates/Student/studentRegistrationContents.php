 <?php 
//-------------------------------------------------------
// THIS FILE Is Used As A Template For Registration Form
// Author : Ankur Aggarwal
// Created on : 25-July-2011
// Copyright 2011-2012: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>


<form action="" method="POST" name="registrationForm" id="registrationForm" onSubmit="return false;">
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
                                            echo $studentInformationArray[0]['periodName'];
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
                <td class="contenttab_row" valign="top" ><form action="" method="POST" name="addForm" id="addForm">
					
<tr>
						<td valign="top">
						<fieldset class="fieldset">
						<legend>Student Details</legend>
						<table width="100%" border="0" cellspacing="0" cellpadding="0" >
						<tr>
							<td height="5"></td>
						</tr>
						<tr>	
							<td class="contenttab_internal_rows"><nobr><b>Student Name :</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="padding_top"><input type="text" id="name" name="name" class="validate[required]" maxlength="60"  value="<?php echo $studentInformationArray[0]['firstName'].' '.$studentInformationArray[0]['lastName']; ?>" readonly='true'>
						        <td width="50%"/>
							<td class="contenttab_internal_rows"><nobr><b>Father's Name : </b><?php echo REQUIRED_FIELD?></nobr></td>   
							<td class="padding_top"><input type="text" id="fatherName" name="fatherName"  class="validate[required]" maxlength="60" value="<?php echo $studentInformationArray[0]['fatherName']; ?>" readonly='true' />


							
						</tr>

						<tr>	
							<td class="contenttab_internal_rows"><nobr><b>Identification No. :</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="padding_top"><input type="text" id="rollNo" name="rollNo"  class="validate[required]" maxlength="60"  value="<?php echo $studentInformationArray[0]['rollNo']; ?>" readonly="true" />
							 <td width="50%"/>
							<td class="contenttab_internal_rows"><nobr><b>Latest Email Id of The Parent :</b></nobr></td>
							<td class="padding_top"><input type="text" id="parentEmail" name="parentEmail" class="validate[custom[email]]" maxlength="60" 
value="<?php echo $studentInformationArray[0]['fatherEmail'];?>" />


							
						</tr>


						<tr>	
							<td class="contenttab_internal_rows"><nobr><b>Latest Email Id of The Student :</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="padding_top"><input type="text" id="studentEmail" name="studentEmail"  class="validate[required,custom[email]]" maxlength="60" value="<?php echo $studentInformationArray[0]['studentEmail'];?>" />
							 <td width="50%"/>
							<td class="contenttab_internal_rows"><nobr><b>Latest Mobile No. of The Parent:</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="padding_top"><input type="text" id="parentsNumber" name="parentsNumber" class="validate[required,custom[phone]]" maxlength="50"  >
</td>


							
						</tr>

						<tr>	
							<td class="contenttab_internal_rows"><nobr><b>Latest Mobile No. of The Student :</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="padding_top"><input type="text" id="studentNumber" name="studentNumber" class="validate[required,custom[phone]]" maxlength="10" value="<?php echo $studentInformationArray[0]['studentMobileNo'];?>"/>
 <td width="50%"/>
							<td class="contenttab_internal_rows"><nobr><b>Latest Landline No:</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="padding_top"><input type="text" id="landlineNo" name="landlineNo" class="validate[required,custom[phone]]" maxlength="50"  >
</td>

						</tr>
						<tr>	
							<td class="contenttab_internal_rows"><nobr><b>Blood Group :</b><?php echo REQUIRED_FIELD?></nobr></td>
						        <td class="padding_top" width='212'><select class="inputbox" size="1"  name="bloodGroup" id="bloodGroup">
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
							<td class="padding_top"><input type="text" id="allergy" name="allergy"  maxlength="50">
</td>

						</tr>
						<tr>
						<tr>
							<td class="contenttab_internal_rows"><nobr><b>Name of Teacher Mentor :</b></nobr></td>
							<td class="padding_top"><input type="text" id="mentor" name="mentor" maxlength="60" />
<td width="50%"/>
						        <td rowspan="2" class="contenttab_internal_rows"><nobr><b>Please Mention The Change In Address<br> If Any Since Last Registration Process  : </b></nobr></td>
							<td rowspan=2 class="padding_top"><textarea  id="address" name="address" cols="26" class="validate[required]" ><?php echo $studentInformationArray[0]['permAddress1'];?></textarea></td>
						


							
						</tr>
						 <tr>
							<td class="contenttab_internal_rows"><nobr><b>CGPA Till Last Trimester :</b></nobr></td>
							<td class="padding_top"><input type="text" name="cgpa" id="cgpa"  maxlength="60"/>
														
						</tr>

						<tr>
							<td class="contenttab_internal_rows" width="110"><nobr><b>Scholar Status :</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="padding_top" width='212'><select class="inputbox" onchange="getStatus(); return false;" size="1"  name="status" id="status">
                                                       <option value="" selected="yes">Select</option>
							<option value="1">Day Scholar</option>
				  			<option value="2" >Hostler</option>		
							</select></td>
						</tr>
						<tr id='hostel1' style="display:none;">
						        <td class="contenttab_internal_rows"><nobr><b>Hostel Name :</b><?php echo REQUIRED_FIELD?></nobr></td>
						        <td class="padding_top" width='212'><input type="text" id="hostelName" name="hostelName"  class="validate[required]" maxlength="60"/>
                      					<td width="50%" />
							<td class="contenttab_internal_rows"><nobr><b>Room No :</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="padding_top" width='212'><input type="text" id="roomNo" name="roomNo" class="validate[required]" maxlength="60"/>
                                                </tr>
						
					        <tr  id='hostel2' style="display:none;">
							<td class="contenttab_internal_rows"><nobr><b>Warden Name :</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="padding_top" width='212'><input type="text" id="wardenName" name="wardenName"  maxlength="60" />					<td width="50%" />
							<td class="contenttab_internal_rows"><nobr><b>Contact No. of Warden :</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="padding_top" width='212'><input type="text" id="wardenContact" name="wardenContact"  maxlength="20"   /></td>
						
						</tr>
						<tr id="travel0" style="display:none;">	
							<td class="contenttab_internal_rows" width="110"><nobr><b>Travelling In University Bus :</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="padding_top" width='212'><select onchange="getTravel(); return false;" class="inputbox" size="1"  name="travel" id="travel" >
							<option value="" selected="yes">Select</option>
							<option value="1">Yes</option>
							<option value="2">No</option>							
							</select></td>
						</tr>
						<tr  id='travel1' style="display:none;">
						        <td class="contenttab_internal_rows"><nobr><b>Route No :</b><?php echo REQUIRED_FIELD?></nobr></td>
						        <td class="padding_top" width='212'>
                                                        <input type="text" id="routeNo" name="routeNo"  class="validate[required]" maxlength="60" /></td>
						        <td width="50%"/>
							<td class="contenttab_internal_rows"><nobr><b>Pick Up:</b><?php echo REQUIRED_FIELD?></nobr></td>
						        <td class="padding_top" width='212'>
							<input type="text" id="pickUp" name="pickUp" class="validate[required]" maxlength="60" /></td></td>


							
						</tr>
						<tr  id='travel2' style="display:none;">
							<td class="contenttab_internal_rows"><nobr><b>Travelling From (Mention Place) :</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="padding_top"><input type="text" id="travellingPt" name="travellingPt" class="validate[required]" maxlength="60" /> 					<td width="50%"/>	
							<td class="contenttab_internal_rows"><nobr><b>Vehicle Registration Number :</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="padding_top"><input type="text" id="vehicleRegistration" name="vehicleRegistration" class="validate[required]" maxlength="60"  />
						   
                                                </tr>
						<tr  id='travel3' style="display:none;">
						   <td class="contenttab_internal_rows"><nobr><b>Type of Vehicle :</b><?php echo REQUIRED_FIELD?></nobr></td>
							<td class="padding_top"><input type="text" id="vehicleType" name="vehicleType" class="validate[required]" maxlength="60"  />
       							  

							
						
							
					    </tr>
						
                                       </fieldset>	

			</table>
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
				
                                   <div>
                                     <div id="subjectsDiv"   style="width:100%; vertical-align:top;"></div>
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
        <input readonly type="hidden" name="studentId"      id="studentId"      value="<?php echo $studentInformationArray[0]['studentId']; ?>">
        <input readonly type="hidden" name="currentClassId" id="currentClassId" value="<?php echo $studentInformationArray[0]['classId']; ?>">


</form>
