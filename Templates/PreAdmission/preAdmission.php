<?php 
  $tdColspan="colspan='15'"; 
  $examArray = array("1"=>"10th","2"=>"10+2","3"=>"Graduation","4"=>"Diploma","5"=>"Any Other");
?>
<table width="800px" align="center" cellpadding="2px" cellspacing="2px"  border="0">
    <tr style="font-weight: bold;">
       <td class="contenttab_internal_rows" align="left" <?php echo $tdColspan; ?> valign="top" >
	      <nobr>
                <font color ="#cc0000">STUDENT DETAILS</font>
	      </nobr>
       </td>
    </tr>
    <tr>
       <td class="contenttab_internal_rows" width="2%" nowrap align="left" valign="top">
		<b>Status<?php echo REQUIRED_FIELD ?></b>
       </td>
       <td class="contenttab_internal_rows" width="98%" nowrap style="font-weight:normal" >
          <input type="radio" value="Enquiry"  checked name="status" id="status1" onclick="getAdmissionNo(); ">Enquiry&nbsp;&nbsp;
          <input type="radio" value="Admission" name="status" id="status2" onclick="getAdmissionNo(); ">Admission&nbsp;&nbsp;
          <input type="radio" value="Registered"  name="status" id="status3" onclick="getAdmissionNo();">Registered&nbsp;&nbsp;
          <input type="radio" value="Walk In"  name="status" id="status4" onclick="getAdmissionNo();">Walk In
       </td>
     </tr>
     <tr>
        <td class="contenttab_internal_rows"   align="left" valign="top">
		<b>Admin Camp <?php echo REQUIRED_FIELD ?></b>
	</td>
        <td class="contenttab_internal_rows" width="98%" nowrap>  
            <nobr>
            <select id="camp" name="camp"  style="width:200px" class="inputbox1" onchange="getAdmissionNo();">
               <option value=''>Select Admin Camp</option>
                      <?php 
                          require_once(BL_PATH.'/PreAdmissionHtmlFunctions.inc.php');
                          echo PreAdmissionHtmlFunctions::getInstance()->getPreAdmissionData('Camp');
                      ?>
             </select>
             </nobr>  
        </td>
     </tr>
     <tr>
       <td class="contenttab_internal_rows" align="left" valign="top"  >
           <b>Serial Number <?php echo REQUIRED_FIELD ?></b>
       </td>
       <td width="95%" nowrap valign="top">
           <input class="inputbox"  id="srNumber" name="srNumber" style="width:200px" maxlength="60" type="text" onkeyup="getAdmissionNo();">
       </td>
     </tr>
     <tr>
       <td class="contenttab_internal_rows" align="left" valign="top"  >
          <b>Admission Number</b>
       </td>
       <td width="95%" nowrap valign="top">
          <input readonly class="inputbox"  id="admissionNumber" name="admissionNumber" style="width:200px" maxlength="60" type="text">
       </td>
     </tr>
     <tr> 
       <td class="contenttab_internal_rows" align="left" valign="top">
      <nobr>
        <b>School and Course in which admission is sought</b>
      </nobr>
       </td>
       <td class="contenttab_internal_rows" align="left" valign="top">
      <nobr>
          <select id="school" name="school"  style="width:200px" class="inputbox1">
              <option value=''>Select Schools</option>
              <?php 
                 require_once(BL_PATH.'/PreAdmissionHtmlFunctions.inc.php');
                 echo PreAdmissionHtmlFunctions::getInstance()->getPreAdmissionData('School');
              ?>
           </select>&nbsp;
           <select id="courses" name="courses"  style="width:200px" class="inputbox1" onchange="getPreference(); return false;">  
              <option value=''>Select Course</option>
              <?php 
                require_once(BL_PATH.'/PreAdmissionHtmlFunctions.inc.php');
                echo PreAdmissionHtmlFunctions::getInstance()->getPreAdmissionData('Course');
              ?>
           </select>
      </nobr>
    </td>
    </tr>    
    <tr id="showPreference1" style="display:none">    
        <td class="contenttab_internal_rows"  align="left" valign="top" <?php echo $tdColspan; ?>  >
           <nobr>
              <b>Preference of branch applicable for admission to (B.Tech/M.Tech/M.Pharma/BBA/BCOM)</b>
           </nobr>
        </td>
    </tr>
    <tr id="showPreference2" style="display:none">    
       <td class="contenttab_internal_rows" align="left" valign="top"  <?php echo $tdColspan; ?> >
         <span id="showPreference3"></span>
       </td>
    </tr>        
    <tr><td class="contenttab_internal_rows" height="5px"></td></tr> 
    <tr>
       <td class="contenttab_internal_rows" align="left" valign="top"  >
         <b> Student Name<?php echo REQUIRED_FIELD ?></b>
       </td>
       <td width="95%" nowrap valign="top">
         <table border="0" cellspacing="0px" cellpadding="0px">
            <tr>
               <td class="contenttab_internal_rows"  align="left" valign="top">
                   <input class="inputbox"  id="studentName" name="studentName" style="width:290px" maxlength="60" type="text">
               </td> 
               
             </tr>
          </table>    
       </td>
     </tr> 
     <tr> 
        <td class="contenttab_internal_rows" align="left" valign="top">
            <b>Date of Birth</b> <span style="font-weight:normal" >(as per Class X Certification)</span>
        </td>     
        <td class="contenttab_internal_rows"  align="left" valign="top">
          <table border="0" cellspacing="0px" cellpadding="0px">
            <tr>
               <td class="contenttab_internal_rows"  align="left" valign="top">
                    <?php 
                        require_once(BL_PATH.'/HtmlFunctions.inc.php');        
                        echo HtmlFunctions::getInstance()->datePicker('dateofBirth','');
                    ?>
               </td>  
               <td valign="top" style="padding-left:20px" class="contenttab_internal_rows"   align="left" valign="top"><b>Gender</b></td>
               <td valign="top" class="contenttab_internal_rows"><input type="radio" value="Male" name="gender" id="gender1"></td>
               <td valign="top" class="contenttab_internal_rows" style="font-weight:normal">Male</td>
               <td valign="top" class="contenttab_internal_rows"><input type="radio" value="Female" name="gender" id="gender2"></td>
               <td valign="top" class="contenttab_internal_rows" style="font-weight:normal">Female</td>
               <td class="contenttab_internal_rows"  style="padding-left:20px"  align="left" valign="top">
                   <b>Blood Group&nbsp;</b>
               </td>
               <td class="contenttab_internal_rows"   align="left" valign="top">
                  <select id="blood_group" name="blood_group" style="width:100px" class="inputbox1">      
                    <option value=''>Select Blood</option>
                    <option value='O+'>O+</option>
                    <option value='A+'>A+</option>
                    <option value='B+'>B+</option>
                    <option value='O'>O-</option>
                    <option value='A-'>A-</option>
                    <option value='B-'>B-</option>
                    <option value='AB+'>AB+</option>
                    <option value='AB-'>AB-</option>
                  </select>
               </td>
             </tr>
            </table>
          </td>     
         </tr>
         <tr>
              <td class="contenttab_internal_rows"   align="left" valign="top">
                <b>Category</b> <span style="font-weight:normal" >(Attach Proof, No Proof required for General Category)</span>
              </td>
              <td class="contenttab_internal_rows"  align="left" valign="top">
                <table border="0" cellspacing="0px" cellpadding="0px">
                  <tr>
                    <td class="contenttab_internal_rows"  align="left" valign="top">
		              <select id="religion" name="religion" style="width:220px" class="inputbox1"> 
                        	<option value=''>Select Category</option>
                        <?php 
                           require_once(BL_PATH.'/PreAdmissionHtmlFunctions.inc.php');
                           echo PreAdmissionHtmlFunctions::getInstance()->getPreAdmissionData('Category');
                        ?>
                       </select>
                    </td>
                    <td valign="top" style="padding-left:20px" class="contenttab_internal_rows"   align="left" valign="top"><b>Religion&nbsp;</b></td>
                    <td class="contenttab_internal_rows"  align="left" valign="top">
                         <select id="category" name="category" style="width:165px" class="inputbox1">  
		                    <option value=''>Select Religion</option>
                            <?php 
                                require_once(BL_PATH.'/PreAdmissionHtmlFunctions.inc.php');
                                echo PreAdmissionHtmlFunctions::getInstance()->getPreAdmissionData('Religion');
                            ?>
                         </select>
                    </td>
                  </tr>
                </table>
            </td>        
         </tr>
         <tr>
             <td class="contenttab_internal_rows"  align="left" valign="top"><b>State of Domicile</b> <span style="font-weight:normal" >(Attach Proof)</span>
             </td>
              <td class="contenttab_internal_rows"   align="left" valign="top">
                 <table border="0" cellspacing="0px" cellpadding="0px">
                  <tr>
                    <td class="contenttab_internal_rows"  align="left" valign="top">
                         <select id="stateDomicile" name="stateDomicile" style="width:220px" class="inputbox1">       
                            <option value=''>Select Domicile</option>
                              <?php 
                             require_once(BL_PATH.'/PreAdmissionHtmlFunctions.inc.php');
                             echo PreAdmissionHtmlFunctions::getInstance()->getPreAdmissionData('Domicile');
                          ?>
                          </select>
                    </td>
                    <td class="contenttab_internal_rows" style="padding-left:20px"  align="left" valign="top">
                        <b>Mobile Number</b>
                    </td>
                    <td class="contenttab_internal_rows"   align="left" valign="top">
                        <input class="inputbox" id="mobileNumber" name="mobileNumber" style="width:160px" maxlength="60" type="text">
                    </td>
                  </tr>
                </table>
              </td>        
         </tr>
	<tr>
	    <td class="contenttab_internal_rows"   align="left" valign="top"  >
                        <b>Identification Number</b>
                    </td>
                    <td class="contenttab_internal_rows"   align="left" valign="top">
                        <input class="inputbox" id="identiMark" name="identiMark" style="width:350px" maxlength="60" type="text">
                    </td>
	</tr>
         <tr style="font-weight: bold;"><td  <?php echo $tdColspan; ?> height="15px"></td></tr>
         <tr style="font-weight: bold;">
           <td class="contenttab_internal_rows" align="left" valign="top"  <?php echo $tdColspan; ?>>
             <font color ="#cc0000">ADDRESS DETAILS</font>
           </td>
        </tr>
	<tr style="font-weight: bold;">
          <td class="contenttab_internal_rows" align="left" valign="top" <?php echo $tdColspan; ?> >  
                        
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                        <tr>
                            <td height="5"></td>
                        </tr>
                        <tr>
                            <td height="5" colspan="5"><B><U>Correspondence Address</U></B></td>
                        </tr>
                        <tr>
                            <td height="5"></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows" width="100"><nobr><b>Address1</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="correspondeceAddress1" name="correspondeceAddress1" class="inputbox" maxlength="255" /></td>
                            <td class="contenttab_internal_rows" width="100"><nobr><b>Address2</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="correspondeceAddress2" name="correspondeceAddress2" class="inputbox" maxlength="255" />
                            </td>
                            <td class="contenttab_internal_rows" width="110"><nobr><b>Pincode</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="correspondecePincode" name="correspondecePincode" class="inputbox" maxlength="10"/></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Country</b></nobr><b></b></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <select size="1" class="selectfield" name="correspondenceCountry" id="correspondenceCountry" onChange="autoPopulate(this.value,'states','Add','correspondenceStates','correspondenceCity');"><option value="" selected="selected">Select</option>
                            <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getCountriesData($defaultCountryId);
                            ?>
                            </select></td>
                            <td class="contenttab_internal_rows"><nobr><b>State</b></nobr><b></b></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="correspondenceStates" id="correspondenceStates" onChange="autoPopulate(this.value,'city','Add','correspondenceStates','correspondenceCity');"><option value="">Select</option>
                            <?php
                                /*require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getStatesData($defaultStateId);*/
                            ?>
                            </select></td>
                            <td class="contenttab_internal_rows"><nobr><b>City</b></nobr><b></b></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="correspondenceCity" id="correspondenceCity"><option value="">Select</option>
                            <?php
                                /*require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getCityData($defaultCityId);*/
                            ?>
                            </select></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Contact No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="correspondecePhone" name="correspondecePhone" class="inputbox" maxlength="20"/></td>
                        </tr>
                        <tr>
                            <td height="5"></td>
                        </tr>
                        <tr>
                            <td height="5" colspan="5"><B><U>Permanent Address</U></B>&nbsp;&nbsp;&nbsp;<input type="checkbox" id="sameText" name="sameText" onClick="copyText()"/>(Same as correspondence address)</td>
                        </tr>
                        <tr>
                            <td height="5"></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Address1</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="permanentAddress1" name="permanentAddress1" class="inputbox" maxlength="255" /></td>
                            <td class="contenttab_internal_rows"><nobr><b>Address2</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="permanentAddress2" name="permanentAddress2" class="inputbox" maxlength="255" />
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Pincode</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="permanentPincode" name="permanentPincode" class="inputbox" maxlength="10"/></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Country</b></nobr><b></b></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top" id="permCountry">
                            <select size="1" class="selectfield" name="permanentCountry" id="permanentCountry" onChange="autoPopulate(this.value,'states','Add','permanentStates','permanentCity');">
                            <option value="" selected="selected">Select</option>
                              <?php
                                 require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                 echo HtmlFunctions::getInstance()->getCountriesData($defaultCountryId);
                              ?>
                            </select>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>State</b></nobr><b></b></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="permanentStates" id="permanentStates" onChange="autoPopulate(this.value,'city','Add','permanentStates','permanentCity');">
                            <option value="">Select</option>
                            <?php
                                /*require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getStatesData($defaultStateId);*/
                            ?>
                            </select>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>City</b></nobr><b></b></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="permanentCity" id="permanentCity">
                            <option value="">Select</option>
                            <?php
                                /*require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getCityData($defaultCityId);*/
                            ?>
                            </select></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Contact No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="permanentPhone" name="permanentPhone" class="inputbox" maxlength="20"/></td>
                        </tr>
                        <tr>
                            <td height="5"></td>
                        </tr>
                         
                        </table>
                        
                    </td>
                    </tr>
		
       <tr style="font-weight: bold;"><td  <?php echo $tdColspan; ?> height="15px"></td></tr>
         <tr style="font-weight: bold;">
           <td class="contenttab_internal_rows" align="left" valign="top"  <?php echo $tdColspan; ?>>
             <font color ="#cc0000">ACADEMIC DETAILS</font>
           </td>
        </tr>
        <tr>
           <td class="contenttab_internal_rows" align="left" valign="top">
              <b>Admission test in which appeared</b>
           </td>
           <td class="contenttab_internal_rows" align="left" valign="top" style="font-weight:normal" >
               <?php 
                   require_once(BL_PATH.'/PreAdmissionHtmlFunctions.inc.php');
                   echo PreAdmissionHtmlFunctions::getInstance()->getPreAdmissionData('ExamTest','','','','1');
               ?>
           </td>
        </tr>
        <tr>
           <td class="contenttab_internal_rows"   align="left" valign="top"  <?php echo $tdColspan; ?>>   
                <b>Educational qualification starting from Class X or equivalent </b>
           </td>
        </tr>
        <tr>
           <td class="contenttab_internal_rows" <?php echo $tdColspan; ?>  align="left" valign="top">
               <table width="800px" align="left" cellpadding="1px" cellspacing="1px"  border="0">
                  <tr>
                      <td width="10%" valign="bottom"><b>Examination</b></td>
                      <td width="15%" valign="bottom"><b>Board/University</b></td>
                      <td width="10%" valign="bottom"><b>Year</b></td>
                      <td width="15%" valign="bottom"><b>Marks Obtained/<br>CGPA</b></td>
                      <td width="15%" valign="bottom"><b>Max Marks/CGPA</b></td>
                      <td width="15%" valign="bottom"><b>Subjects</b></td>
                      <td width="10%" valign="bottom"><b>Percentage</b></td>
                 </tr>
                 <?php 
                 $examArray = array("1"=>"10th","2"=>"10+2","3"=>"Graduation","4"=>"Diploma","5"=>"Any Other");    
                 for($i=1;$i<=count($examArray);$i++) {
                    echo "<tr>
                              <td width='10%' valign='bottom' style='padding-right:20px'>
                                <b><span>$examArray[$i]</span></b>
                                <input type='hidden' name='exam$i' id='exam$i' value='' class='inputbox1'>
                              </td>
                              <td width='15%' valign='bottom'>
                                <input type='text' name='board$i' id='board$i' value='' class='inputbox1'>
                              </td>
                              <td width='15%' valign='bottom'>
                                <input type='text' name='year$i' id='year$i' value='' class='inputbox1'>
                              </td>
                              <td width='15%' valign='bottom'>
                                <input type='text' name='marksObtained$i' id='marksObtained$i' value='' class='inputbox1'>
                              </td>
                              <td width='15%' valign='bottom'>
                                <input type='text' name='maxMarks$i' id='maxMarks$i' value='' class='inputbox1'>
                              </td>
                              <td width='15%' valign='bottom'>
                                <input type='text' name='subject$i' id='subject$i' value='' class='inputbox1'>
                              </td>
                              <td width='15%' valign='bottom'>
                                <input type='text' name='percentage$i' id='percentage$i' value='' class='inputbox1'>
                              </td>
                         </tr>"; 
                 } 
                 ?>
            
            
                </table>  
          </td>
        </tr>
	</tr>
        <tr style="font-weight: bold;"><td  <?php echo $tdColspan; ?> height="15px"></td></tr>
        <tr style="font-weight: bold;">
           <td class="contenttab_internal_rows" align="left" valign="top" <?php echo $tdColspan; ?>>
          <nobr>
            <font color ="#cc0000">HOSTEL DETAILS</font>
          </nobr>
           </td>
        </tr>
        <tr >
             <td class="contenttab_internal_rows"   align="left" valign="top"><b>Hostel Accomodation Required </b></td>
	     <td class="contenttab_internal_rows"  align="left" valign="top">
                  <select id="hostel_acc" name="hostel_acc" style="width:220px" class="inputbox1">       
                    <option value=''>Select if Hostel Required</option>
                    <option value='yes'>Yes</option>
                    <option value='no'>No</option>        
                  </select>
                    </td>
             </tr>
	 
	<tr style="font-weight: bold;"><td  <?php echo $tdColspan; ?> height="15px"></td></tr>
         <tr style="font-weight: bold;">
           <td class="contenttab_internal_rows" align="left" valign="top"  <?php echo $tdColspan; ?>>
             <font color ="#cc0000">PARENT DETAIL</font>
           </td>
        </tr>
        <tr>
       <tr style="font-weight: bold;">
          <td class="contenttab_internal_rows" align="left" valign="top" <?php echo $tdColspan; ?> >        
              <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                        <tr>
                            <td height="5"></td>
                        </tr>
                         <tr>
                            <td height="5"><B><U>Father Details</U></B></td>
                        </tr>
                         <tr>
                            <td height="5"></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows" width="100"><nobr><b>Name</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <select size="1"  class="inputbox1" name="fatherTitle" id="fatherTitle" style="display:none">
                            <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getTitleData($REQUEST_DATA['degree']==''?$REQUEST_DATA['degree'] : $REQUEST_DATA['degree']);
                            ?>
                            </select>
                            <input type="text" id="fatherName" name="fatherName" class="inputbox"  maxlength="100" /></td>
                            <td class="contenttab_internal_rows" width="100"><nobr><b>Qualification</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="fatherQualification" name="fatherQualification" class="inputbox" maxlength="100" />
                            </td>
                            <td class="contenttab_internal_rows" width="110"><nobr><b>Profession</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="fatherProfession" name="fatherProfession" class="inputbox" maxlength="100"/></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Designation</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="fatherDesignation" name="fatherDesignation" class="inputbox" maxlength="20"/>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Mobile</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="fatherMobile" name="fatherMobile" class="inputbox" maxlength="255" /></td>
                            <td class="contenttab_internal_rows"><nobr><b>Address</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="fatherAddress" name="fatherAddress" class="inputbox" maxlength="255" /></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Country</b></nobr><b></b></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <select size="1" class="selectfield" name="fatherCountry" id="fatherCountry" onChange="autoPopulate(this.value,'states','Add','fatherStates','fatherCity');">
                            <option value="" selected="selected">Select</option>
                            <?php
                                global $sessionHandler;
                                $defaultCountryId = $sessionHandler->getSessionVariable('DEFAULT_COUNTRY');

                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getCountriesData($defaultCountryId);
                            ?>
                            </select>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>State</b></nobr><b></b></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="fatherStates" id="fatherStates" onChange="autoPopulate(this.value,'city','Add','fatherStates','fatherCity');">
                            <option value="">Select</option>
                            <?php
                                /*global $sessionHandler;
                                $defaultStateId = $sessionHandler->getSessionVariable('DEFAULT_STATE');
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getStatesData($defaultStateId);*/
                            ?>
                            </select></td>
                            <td class="contenttab_internal_rows"><nobr><b>City</b></nobr><b></b></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="fatherCity" id="fatherCity">
                            <option value="">Select</option>
                            <?php
                                /*global $sessionHandler;
                                $defaultCityId = $sessionHandler->getSessionVariable('DEFAULT_CITY');

                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getCityData($defaultCityId);*/
                            ?>
                            </select></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Pincode</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="fatherPincode" name="fatherPincode" class="inputbox" maxlength="20"/></td>
                            <td class="contenttab_internal_rows"><nobr><b>Contact No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="fatherContact" name="fatherContact" class="inputbox" maxlength="20"/></td>
			    <td class="contenttab_internal_rows" width="110"><nobr><b>Email Id</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="fatherEmail" name="fatherEmail" class="inputbox" maxlength="100"/></td>
                        </tr>
                        <tr>
                            <td height="5"></td>
                        </tr>
                        <tr>
                            <td height="5"><B><U>Mother Details</U></B></td>
                            <td style="display:none" height="5" colspan="3"><input type="checkbox" id="sameFatherText" name="sameFatherText" onClick="copyFatherText()"/>(Same as Father Detail)</td>
                        </tr>
                        <tr>
                            <td height="5"></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Name</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <select size="1" class="inputbox" name="motherTitle" id="motherTitle" style="display:none">
                            <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getTitleData(2);
                            ?>
                            </select>
                            <input type="text" id="motherName" name="motherName" class="inputbox" size="28" maxlength="100" /></td>

                            <td class="contenttab_internal_rows"><nobr><b>Qualification</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="motherQualification" name="motherQualification" class="inputbox" maxlength="100" />
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Profession</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="motherProfession" name="motherProfession" class="inputbox" maxlength="100"/></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Designation</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="motherDesignation" name="motherDesignation" class="inputbox" maxlength="20"/>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Mobile</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="motherMobile" name="motherMobile" class="inputbox" maxlength="255" /></td>
                            <td class="contenttab_internal_rows"><nobr><b>Address</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="motherAddress" name="motherAddress" class="inputbox" maxlength="255" /></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Country</b></nobr><b></b></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <select size="1" class="selectfield" name="motherCountry" id="motherCountry" onChange="autoPopulate(this.value,'states','Add','motherStates','motherCity');">
                            <option value="" selected="selected">Select</option>
                              <?php
                                global $sessionHandler;
                                $defaultCountryId = $sessionHandler->getSessionVariable('DEFAULT_COUNTRY');

                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getCountriesData($defaultCountryId);
                            ?>
                            </select>
                            <script language="javascript">
                               document.getElementById('motherCountry').value='<?php echo $defaultCountryId;?>';
                            </script>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>State</b></nobr><b></b></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="motherStates" id="motherStates" onChange="autoPopulate(this.value,'city','Add','motherStates','motherCity');">
                            <option value="">Select</option>
                            <?php
                                /*require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getStatesData($defaultStateId);*/
                            ?>
                            </select></td>
                            <td class="contenttab_internal_rows"><nobr><b>City</b></nobr><b></b></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="motherCity" id="motherCity">
                            <option value="">Select</option>
                            <?php
                                /*require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getCityData($defaultCityId);*/
                            ?>
                            </select></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Pincode</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="motherPincode" name="motherPincode" class="inputbox" maxlength="20"/></td>
                            <td class="contenttab_internal_rows"><nobr><b>Contact No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="motherContact" name="motherContact" class="inputbox" maxlength="20"/>
                            </td>
			    <td class="contenttab_internal_rows" width="110"><nobr><b>Email Id</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="motherEmail" name="motherEmail" class="inputbox" maxlength="100"/></td>
                        </tr>
                        <tr>
                            <td height="5"></td>
                        </tr>
                         <tr>
                            <td height="5"><B><U>Guardian Details</U></B></td>
                            <td style="display:none" height="5" colspan="5"><input type="checkbox" id="sameFatherText1" name="sameFatherText1" onClick="copyGuardianText()"/>(Same as Father Detail)&nbsp;&nbsp;&nbsp;<input type="checkbox" id="sameMotherText" name="sameMotherText" onClick="copyGuardianMotherText()"/>(Same as Mother Detail)</td>
                        </tr>
                         <tr>
                            <td height="5"></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Name</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <select size="1" class="inputbox1" name="guardianTitle" id="guardianTitle" style="display:none">
                            <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getTitleData($REQUEST_DATA['degree']==''?$REQUEST_DATA['degree'] : $REQUEST_DATA['degree']);
                            ?>
                            </select>
                            <input type="text" id="guardianName" name="guardianName" class="inputbox" size="28" maxlength="100" /></td>
                            <td class="contenttab_internal_rows"><nobr><b>Qualification</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="guardianQualification" name="guardianQualification" class="inputbox" maxlength="100" /></td>
                            <td class="contenttab_internal_rows"><nobr><b>Profession</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="guardianProfession" name="guardianProfession" class="inputbox" maxlength="100"/></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Designation</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="guardianDesignation" name="guardianDesignation" class="inputbox" maxlength="20"/>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Mobile</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="guardianMobile" name="guardianMobile" class="inputbox" maxlength="255" /></td>
                            <td class="contenttab_internal_rows"><nobr><b>Address</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="guardianAddress" name="guardianAddress" class="inputbox" maxlength="255" /></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Country</b></nobr><b></b></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <select size="1" class="selectfield" name="guardianCountry" id="guardianCountry" onChange="autoPopulate(this.value,'states','Add','guardianStates','guardianCity');">
                            <option value="" selected="selected">Select</option>
                              <?php
                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                  echo HtmlFunctions::getInstance()->getCountriesData($defaultCountryId);
                              ?>
                            </select>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>State</b></nobr><b></b></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="guardianStates" id="guardianStates" onChange="autoPopulate(this.value,'city','Add','guardianStates','guardianCity');">
                            <option value="">Select</option>
                            <?php
                                /*require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getStatesData($defaultStateId);*/
                            ?>
                            </select>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>City</b></nobr><b>
                           </b></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <select size="1" class="selectfield" name="guardianCity" id="guardianCity">
                            <option value="">Select</option>
                            <?php
                                /*require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getCityData($defaultCityId);*/
                            ?>
                            </select></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Pincode</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="guardianPincode" name="guardianPincode" class="inputbox" maxlength="20"/></td>
                            <td class="contenttab_internal_rows"><nobr><b>Contact No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="guardianContact" name="guardianContact" class="inputbox" maxlength="20"/>
                            </td>
			    <td class="contenttab_internal_rows"><nobr><b>Email Id</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="guardianEmail" name="guardianEmail" class="inputbox" maxlength="20"/>
                            </td>
			    
                        </tr>
                        </table>         
          </td>    
       </tr>
             <td class="contenttab_internal_rows"   align="left" valign="top"><b>Total Annual Income(from all the sources)Rs</b></td>
             <td width="95%" nowrap valign="top">
                 <input class="inputbox" id="annualIncome" name="annualIncome" style="width:290px" maxlength="60" type="text">
               </td>
             </tr>
	     <tr style="font-weight: bold;"><td  <?php echo $tdColspan; ?> height="15px"></td></tr>
		 <tr style="font-weight: bold;">
		   <td class="contenttab_internal_rows" align="left" valign="top"  <?php echo $tdColspan; ?>>
		     <font color ="#cc0000">SIBLINGS DETAIL</font>
		   </td>
		</tr>
             <tr>
             <td class="contenttab_internal_rows"   align="left" valign="top" <?php echo $tdColspan; ?>>Brother/Sister who has studied in any of the schools of BUEST</td>
             </tr>
<tr>
              <td class="contenttab_internal_rows"   align="left" valign="top"><b>Name </b></td>
              <td width="215">
                 <input class="inputbox" id="siblingName" name="siblingName" style="width:290px" maxlength="60" type="text">
              </td>
            </tr>
              <tr>
              <td class="contenttab_internal_rows"   align="left" valign="top"><b>Year of admission</b></td>
              <td width="215">
                 <input class="inputbox" id="siblingYear" name="siblingYear" style="width:290px" maxlength="60" type="text">
              </td>
            </tr>
            <tr>
              <td class="contenttab_internal_rows"   align="left" valign="top"><b>Course</b></td>
              <td width="215">
                 <input class="inputbox" id="siblingCourse" name="siblingCourse" style="width:290px" maxlength="60" type="text">
              </td>
            </tr>
             <tr>
              <td class="contenttab_internal_rows"   align="left" valign="top"><b>Roll No.</b></td>
              <td width="215">
                 <input class="inputbox" id="siblingRollno" name="siblingRollno" style="width:290px" maxlength="60" type="text">
              </td>
            </tr>
         <tr style="font-weight: bold;"><td  <?php echo $tdColspan; ?> height="20px"></td></tr>      
</table>
