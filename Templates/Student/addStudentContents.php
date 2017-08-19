<?php
//-------------------------------------------------------
// Purpose: to design add student.
//
// Author : Rajeev Aggarwal
// Created on : (05.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
	require_once($FE . "/Library/common.inc.php");
    global $sessionHandler;
    $admitStudentRequiredField = $sessionHandler->getSessionVariable('ADMIT_STUDENT_REQUIRED_FIELD');
    $admitOptionalField = $sessionHandler->getSessionVariable('INSTITUTE_ADMIT_STUDENT_OPTIONAL_FIELD');           
    
    $studentRequiredField = REQUIRED_FIELD;  
    if($admitStudentRequiredField==0) {
      $studentRequiredField = '';  
    }
    
    $newTd="style=display:none";
    $styleNewOld="style=display:''";
    if($admitOptionalField==1) {
      $styleNewOld='style="display:none"';
      $newTd="style=display:''";
    }
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
  
        <td valign="top" class="title">
             <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?>   
        </td>
    </tr>
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
                        <td class="content_title">Admit Student: </td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <form name="addForm" id="addForm" method="post" enctype="multipart/form-data" style="display:inline" onSubmit="return false;" >
                
                    <input type='hidden' name='admitOptionalField' id='admitOptionalField' value="<?php echo  $admitOptionalField; ?>" readonly="readonly">
                    
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                    <tr>
                        <td valign="top">
                        <fieldset class="fieldset">
                        <legend>Select Case</legend>
                        <table border="0" cellspacing="0" cellpadding="0" >
                        <tr>
                            <td height="5"></td>
                        </tr>
                        <tr>
                            <td class="padding_top"><input type="radio" name="formCase" value='upper' onClick="currentValue(this.value)" CHECKED></td>
                            <td class="contenttab_internal_rows" width="91"><nobr><b>Upper Case</b></nobr></td>
                            <td class="padding_top"><input type="radio" name="formCase" value='lower' onClick="currentValue(this.value)" ></td>
                            <td class="contenttab_internal_rows" width="90"><nobr><b>Lower Case</b></nobr></td>
                            <td class="padding_top"><input type="radio" name="formCase" value='mixed' onClick="currentValue(this.value)" ></td>
                            <td class="contenttab_internal_rows"><nobr><b>Mixed Case</b></nobr></td>
                        </tr>
                         <tr>
                            <td height="5"></td>
                        </tr>
                        </table>
                        </fieldset>
                    </td>
                    </tr>
                    <tr>
                        <td height="5"></td>
                    </tr>
                    <tr>
                        <td valign="top">
                        <fieldset class="fieldset">
                        <legend>Select Class</legend>
                        <table border="0" cellspacing="0" cellpadding="0" >
                        <tr>
                            <td height="5"></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows" width="115"><nobr><b>Institute</b><?php echo REQUIRED_FIELD?></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top" width='212'><select size="1"  style="width:150px" class="inputbox" name="studentInstitute" id="studentInstitute" onChange="getInstituteClass(this.value)">
                            <option value="">Select</option>
                            <?php
                                  global $sessionHandler;
                                  $defaultInstituteId = $sessionHandler->getSessionVariable('InstituteId');

                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                  echo HtmlFunctions::getInstance()->getInstituteData($defaultInstituteId);
                            ?>
                            </select></td>
                            <td class="contenttab_internal_rows" width="95"><nobr><b>&nbsp;Class</b><?php echo REQUIRED_FIELD?></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="inputbox1" name="degree" id="degree" style='width:270px'>
                            <option value="">Select</option>
                            <?php
                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                  echo HtmlFunctions::getInstance()->getAdmitClassData();
                            ?>
                            </select></td>
                            <td class="contenttab_internal_rows" width="85"><nobr><b>&nbsp;Branch</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td><select size="1" class="inputbox1" name="branch" id="branch" >
                                <?php
                                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                    echo HtmlFunctions::getInstance()->getBranchData();
                                ?>
                            </select>
                            </td>
                            <td class="contenttab_internal_rows" nowrap width='120'><nobr><b>&nbsp;&nbsp;<?php echo REG_NO ?></b><?php echo REQUIRED_FIELD?></nobr>
                            <b><?php require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                            echo HtmlFunctions::getInstance()->getHelpLink('FindStudent',REGISTRATION_DETAIL);?></b></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="collegeRegNo" name="collegeRegNo" class="inputbox1" maxlength="60" size="28" value="<?php echo $genratedRegNo?>"  /></td>
                        </tr>
                         <tr>
                            <td height="5"></td>
                        </tr>
                        </table>
                        </fieldset>
                    </td>
                    </tr>
                    <tr>
                        <td height="5"></td>
                    </tr>
                    <tr>
                        <td valign="top">
                        <fieldset class="fieldset">
                        <legend>Personal Details</legend>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                        <tr>
                            <td height="5"></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Student Photo</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                                <input type="file" id="studentPhoto" name="studentPhoto" class="inputbox1" />
                                <input type="hidden" name="hiddenFile" id="hiddenFile" class="inputbox1">
                                <iframe id="uploadTargetAdd" name="uploadTargetAdd" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
                            </td>
                        </tr>
                        <tr <?php echo $styleNewOld; ?> > 
                            <td class="contenttab_internal_rows"><nobr><b>Exam</b><?php echo $studentRequiredField; ?></nobr>
                           <b><?php require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                            echo HtmlFunctions::getInstance()->getHelpLink('FindStudent',EXAM_DETAIL);?></b>
                            </td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="entranceExam" id="entranceExam">
                            <option value="">Select</option>
                            <?php

                              global $sessionHandler;
                              $defaultExamId = $sessionHandler->getSessionVariable('DEFAULT_EXAM');

                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getEntranceExamData($defaultExamId);
                            ?>
                            </select></td>
                            <td class="contenttab_internal_rows"><nobr><b>Rank</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="studentRank" name="studentRank" class="inputbox" maxlength="10"/></td>

                            <td class="contenttab_internal_rows"><nobr><b>Exam Roll No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="studentEntranceRole" name="studentEntranceRole" class="inputbox" maxlength="30"/></td>
                           </tr>
                           <tr>

                            <td class="contenttab_internal_rows"><nobr><b>Date Of Admission</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <select size="1" name="admissionDate" id="admissionDate" class="inputbox1">
                            <option value="">Date</option>
                              <?php
                                  $curDate = date('Y-m-d');
                                  $curDate = explode('-',$curDate);
                                  $currentYear = $curDate[0];
                                  $currentMonth = $curDate[1];
                                  $currentDate = $curDate[2];

                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                  echo HtmlFunctions::getInstance()->getBirthDate($currentDate);
                              ?>
                            </select>&nbsp;
                            <select size="1" name="admissionMonth" id="admissionMonth" class="inputbox1">
                            <option value="">Month</option>
                             <?php
                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                  echo HtmlFunctions::getInstance()->getBirthMonth($currentMonth);
                              ?>
                            </select>&nbsp;
                            <select size="1" name="admissionYear" id="admissionYear" class="inputbox1">
                              <option value="">Year</option>
                              <?php
                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                  echo HtmlFunctions::getInstance()->getEmployeeBirthYear($currentYear);
                              ?>
                            </select>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Class Roll No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="studentClassRole" name="studentClassRole" class="inputbox" maxlength="30"/></td>
                            <td class="contenttab_internal_rows"><nobr><b><?php echo UNIV_ROLL_NO ?></b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="studentUniversityRole" name="studentUniversityRole" class="inputbox" maxlength="30"/></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>First Name</b><?php echo REQUIRED_FIELD?></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="studentName" name="studentName" class="inputbox" maxlength="60" onBlur="changeCase(this.name)"/>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Last Name</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="studentLName" name="studentLName" class="inputbox" maxlength="60"  onBlur="changeCase(this.name)"/>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Category</b><?php echo $studentRequiredField;?></nobr>
                            <b><?php require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                            echo HtmlFunctions::getInstance()->getHelpLink('FindStudent',CATEGORY_DETAIL);?></b></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="studentCategory" id="studentCategory">
                            <option value="">Select</option>
                            <?php
                              global $sessionHandler;
                              $defaultCategoryId = $sessionHandler->getSessionVariable('DEFAULT_CATEGORY');
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getCurrentCategories($defaultCategoryId,' WHERE parentQuotaId=0 ',$showParentCat='1');
                            ?>
                            </select></td>
                            <script language="javascript">
                              document.getElementById('studentCategory').value='<?php echo $defaultCategoryId; ?>';
                            </script>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Date Of Birth</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <select size="1" name="studentDate" id="studentDate" class="inputbox1">
                            <option value="">Date</option>
                              <?php
                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                  echo HtmlFunctions::getInstance()->getBirthDate('1');
                              ?>
                            </select>&nbsp;
                            <select size="1" name="studentMonth" id="studentMonth" class="inputbox1">
                            <option value="">Month</option>
                             <?php
                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                  echo HtmlFunctions::getInstance()->getBirthMonth('4');
                              ?>
                            </select>&nbsp;
                            <select size="1" name="studentYear" id="studentYear" class="inputbox1">
                              <option value="">Year</option>
                              <?php
                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                  echo HtmlFunctions::getInstance()->getEmployeeBirthYear('1992');
                              ?>
                            </select>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Gender</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="radio" id="genderRadio" name="genderRadio" value="M" checked/>Male&nbsp;&nbsp;<input type="radio" id="genderRadio" name="genderRadio" value="F"/>Female
                            </td>
                            <td class="contenttab_internal_rows"  <?php echo $styleNewOld; ?> ><nobr><b>Religion</b></nobr></td>
                            <td class="contenttab_internal_rows" <?php echo $styleNewOld; ?> ><b>:</b></td>
                            <td class="padding_top" <?php echo $styleNewOld; ?> ><input type="text" id="religion" name="religion" class="inputbox" maxlength="50" onBlur="changeCase(this.name)"/>
                            </td>
                            <td <?php echo $newTd; ?> colspan="3" class="contenttab_internal_rows"><nobr></nobr></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Email</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="studentEmail" name="studentEmail" class="inputbox" maxlength="100"/>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Alternate Email</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="alternateEmail" name="alternateEmail" class="inputbox" maxlength="100"/>
                            </td>
                            <td <?php echo $styleNewOld; ?> class="contenttab_internal_rows"><nobr><b>Hobbies</b></nobr></td>
                            <td <?php echo $styleNewOld; ?> class="contenttab_internal_rows"><b>:</b></td>
                            <td <?php echo $styleNewOld; ?> class="padding_top"><input type="text" id="hobbies" name="hobbies" class="inputbox" maxlength="100" onBlur="changeCase(this.name)"/></td>
                            <td <?php echo $newTd; ?> colspan="3" class="contenttab_internal_rows"><nobr></nobr></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Contact No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="studentNo" name="studentNo" class="inputbox"  maxlength="40" onblur="copyContactNo(this.value);"/></td>
                            <td class="contenttab_internal_rows"><nobr><b>Mobile No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="studentMobile" name="studentMobile" class="inputbox"  maxlength="15"/></td>
                            <td class="contenttab_internal_rows"><nobr><b>Nationality</b><?php echo $studentRequiredField; ?></nobr>
                            <b><?php require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                            echo HtmlFunctions::getInstance()->getHelpLink('FindStudent',NATIONALITY_DETAIL);?></b></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="country" id="country">
                            <option value="">Select</option>
                            <?php
                              global $sessionHandler;
                              $defaultNationalityId = $sessionHandler->getSessionVariable('DEFAULT_NATIONALITY');

                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getNationalityData($defaultNationalityId);
                            ?>
                            </select></td>
                        </tr>
                        <tr>
                            <td  <?php echo $styleNewOld; ?> class="contenttab_internal_rows"><nobr><b>Sports Activity</b></nobr></td>
                            <td  <?php echo $styleNewOld; ?> class="contenttab_internal_rows"><b>:</b></td>
                            <td  <?php echo $styleNewOld; ?> class="padding_top"><input type="text" id="sportsActivity" name="sportsActivity" class="inputbox" maxlength="250" onBlur="changeCase(this.name)"/></td>
                            <td  class="contenttab_internal_rows"><nobr><b>Fee receipt No.</b></nobr></td>
                            <td  class="contenttab_internal_rows"><b>:</b></td>
                            <td  class="padding_top"><input type="text" id="feeReceiptNo" name="feeReceiptNo" class="inputbox"  maxlength="15" onBlur="changeCase(this.name)"/></td>
                            <td  <?php echo $styleNewOld; ?>  class="contenttab_internal_rows"><nobr><b>Blood Group</b></nobr></td>
                            <td  <?php echo $styleNewOld; ?>  class="contenttab_internal_rows"><b>:</b></td>
                            <td  <?php echo $styleNewOld; ?>  class="padding_top"><select size="1" class="inputbox1" name="bloodGroup" id="bloodGroup">
                            <option value="">Select</option>
                            <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getBloodGroupData();
                            ?>
                            </select></td>
                            <?php 
                                global $sessionHandler;
                                if( $sessionHandler->getSessionVariable('INSTITUTE_ADMIT_STUDENT_OPTIONAL_FIELD') == 1) {
                            ?>
                                    <td class="contenttab_internal_rows"><nobr><b>Domicile</b><?php echo $studentRequiredField; ?></nobr></td>
                                    <td class="contenttab_internal_rows"><b>:</b></td>

                                    <td class="padding_top"><select size="1" class="selectfield" name="studentDomicile" id="studentDomicile">
                                    <option value="">Select</option>
                                    <?php
                                      global $sessionHandler;
                                      $defaultDomicileId = $sessionHandler->getSessionVariable('DEFAULT_DOMICILE');

                                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                      echo HtmlFunctions::getInstance()->getStatesData($defaultDomicileId);
                                    ?>
                                    </select>
                                    </td>
                            <?php } ?>
                            <td <?php echo $newTd; ?> colspan="3" class="contenttab_internal_rows"><nobr></nobr></td>
                        </tr>
                        <tr>
                            <?php 
                                global $sessionHandler;
                                if( $sessionHandler->getSessionVariable('INSTITUTE_ADMIT_STUDENT_OPTIONAL_FIELD') != 1) {
                            ?>
                            <td class="contenttab_internal_rows"><nobr><b>Domicile</b><?php echo $studentRequiredField; ?></nobr>
                         <b><?php require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                            echo HtmlFunctions::getInstance()->getHelpLink('Domicile detail',DOMICILE_DETAIL);?></b></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="studentDomicile" id="studentDomicile">
                            <option value="">Select</option>
                            <?php
                              global $sessionHandler;
                              $defaultDomicileId = $sessionHandler->getSessionVariable('DEFAULT_DOMICILE');

                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getStatesData($defaultDomicileId);
                            ?>
                            </select></td>
                             <?php } ?>    
                             
                            <td  <?php echo $styleNewOld; ?> class="contenttab_internal_rows"><nobr><b>Mgmt Category</b></nobr></td>
                            <td  <?php echo $styleNewOld; ?> class="contenttab_internal_rows"><b>:</b></td>
                            <td  <?php echo $styleNewOld; ?> class="padding_top"><input type="checkbox" id="isMgmt" name="isMgmt" value="1" /></td>
                            <td  <?php echo $styleNewOld; ?> class="contenttab_internal_rows"><nobr><b>Mgmt Reference</b></nobr></td>
                            <td  <?php echo $styleNewOld; ?> class="contenttab_internal_rows"><b>:</b></td>
                            <td  <?php echo $styleNewOld; ?> class="padding_top"><input type="text" id="studentReference" name="studentReference" class="inputbox"   maxlength="100" onBlur="changeCase(this.name)"/></td>
                            <td <?php echo $newTd; ?> colspan="6" class="contenttab_internal_rows"><nobr></nobr></td>
                        </tr>

                        <tr  <?php echo $styleNewOld; ?>>
                            <td class="contenttab_internal_rows" colspan="3"><nobr><b>Hostel facility  availed?</b>
                            <b>:</b>
                             <input type="radio" id="hostelFacility1" name="hostelFacility" value="1"  /><b>Yes</b> &nbsp;
                             <input type="radio" id="hostelFacility2" name="hostelFacility" value="0" checked="checked"/><b>No</b> &nbsp;
                            </td>
                            <td class="contenttab_internal_rows" colspan="3"><nobr><b>Transportation facility availed?</b>
                            <b>:</b>
                             <input type="radio" id="transportFacility1" name="transportFacility" value="1" /><b>Yes</b> &nbsp;
                             <input type="radio" id="transportFacility2" name="transportFacility" value="0" checked="checked" /><b>No</b> &nbsp;			
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Is LEET&nbsp;&nbsp;:&nbsp;
				<input type="checkbox" id="isLeet" name="isLeet" value="1"/>				
				</b></nobr>
			    </td>
                            <td class="contenttab_internal_rows"></td>
                            <td class="contenttab_internal_rows">
				<table border="1" align="right">
				<nobr><b>Is Migrated&nbsp;&nbsp;:&nbsp;</b>
				
				<input type="checkbox" id="isMigration" name="isMigration" value="1" onclick="getShowStudyPeriod();" />
				</nobr>
			    <div id ="divMigStudy" name ="divMigStudy" style="display:none;">
				<nobr><b>Study-Period</b>
				<b>:</b>
				<select id="migratedStudyPeriod" name="migratedStudyPeriod">
				  <option value="0">Select</option>
				 <?php 
				  for($i=1;$i<=20;$i++) {?>				  
				 <option value="<?php echo $i;?>"><?php echo $i ;?></option>
				<?php  
				} ?>
				</select>
				</nobr></div>
			    </td>
							
                          </table>
                        </tr>
						
                        <tr  <?php echo $styleNewOld; ?>>
                            <td class="contenttab_internal_rows" colspan="3"><nobr><b>Have you ever stayed in hostel?</b>
                            <b>:</b>
                             <input type="radio" id="everStayedInHostelYes" name="everStayedInHostel" value="1" onclick="getHostelFacility();" /><b>Yes</b> &nbsp;
                             <input type="radio" id="everStayedInHostelNo" name="everStayedInHostel" value="0" checked="checked" onclick="getHostelFacilityNo();"/><b>No</b> &nbsp;
                            </td>
                            <td class="contenttab_internal_rows" colspan="3"><nobr><b>If yes, how many years? :</b>&nbsp;
                            <input type="text" id="yearsInHostel" name="yearsInHostel" class="inputbox"  maxlength="2" disabled="disabled"/></td>
                        </tr>
                        <tr  <?php echo $newTd; ?>>      
                            <td class="contenttab_internal_rows" ><nobr><b>Current Organization</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="currentOrg" name="currentOrg" class="inputbox"  maxlength="150" onblur="copyContactNo(this.value);"/></td>
                            <td class="contenttab_internal_rows"><nobr><b>Designation</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="companyDesignation" name="companyDesignation" class="inputbox"  maxlength="100"/></td>
                            <td class="contenttab_internal_rows"><nobr><b>Office Contact No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="officeContactNo" name="officeContactNo" class="inputbox"  maxlength="100"/></td>
                        </tr>
                        <tr  <?php echo $newTd; ?>>      
                            <td class="contenttab_internal_rows"><nobr><b>Work Email</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="workEmail" name="workEmail" class="inputbox"  maxlength="150" onblur="copyContactNo(this.value);"/></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows" colspan="3"><nobr><b>Education</b>
                            <b>:</b>
                             <input type="radio" id="educationYes" name="education" value="0" onclick="getEducationYes();" checked="checked"/><b>Self-financed</b>&nbsp;
                             <input type="radio" id="educationNo" name="education" value="1" onclick="getEducationNo();"/><b>Educational Loan</b> &nbsp;
                            </td>
                            <td class="contenttab_internal_rows" colspan="3"><nobr><b>Bank Name and Address :</b>&nbsp;
                            <input type="text" id="bankName" name="bankName" class="inputbox"  maxlength="250" disabled="disabled" onBlur="changeCase(this.name)"/>
                            <td class="contenttab_internal_rows"><nobr><b>Loan Amount</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="loanAmount" name="loanAmount" class="inputbox" maxlength="7" disabled="disabled"/></td>
                        </tr>
                        <tr  <?php echo $styleNewOld; ?>> 
                            <td class="contenttab_internal_rows" colspan="10"><nobr><b>Languages Known (use comma seperator for eg.Hindi, English)</b></td>
                        </tr>
                        <tr  <?php echo $styleNewOld; ?>>
                            <td class="contenttab_internal_rows"><nobr><b>To Read</b></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="toRead" name="toRead" class="inputbox" maxlength="100" onBlur="changeCase(this.name)"/>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>To Write</b></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="toWrite" name="toWrite" class="inputbox" maxlength="100" onBlur="changeCase(this.name)"/>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>To Speak</b></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="toSpeak" name="toSpeak" class="inputbox" maxlength="100" onBlur="changeCase(this.name)"/>
                            </td>
                        </tr>
                         <tr>
                            <td height="5"></td>
                        </tr>
                        </table>
                        </fieldset>
                    </td>
                    </tr>
                    <tr>
                        <td height="5"></td>
                    </tr>
                    <tr>
                        <td valign="top">
                        <fieldset class="fieldset">
                        <legend>Previous Academic Record</legend>
                        <table border="0" cellspacing="1" cellpadding="1" width='100%'>
                        <tr  <?php echo $styleNewOld; ?>>  
                            <td colspan="3">Have you completed graduation :</td>
                            <td><input type="radio" id="completedGraduationYes" name="completedGraduation" checked="checked" value="1" onclick="getCheckedGraduation();">Yes
                            <input type="radio" id="completedGraduationNo" name="completedGraduation" value="0" onclick="getUnCheckedGraduation();">No</td>
                        </tr>
                        <tr  <?php echo $styleNewOld; ?>>  
                            <td colspan="3">If no, have you written the final exam :</td>
                            <td><input type="radio" id="writtenFinalExamYes" name="writtenFinalExam" value="1" disabled="disabled">Yes
                            <input type="radio" id="writtenFinalExamNo" name="writtenFinalExam" value="0" disabled="disabled">No</td>
                        </tr>
                        <tr  <?php echo $styleNewOld; ?>>  
                            <td colspan="3">If yes, when is the result due :</td>
                            <td><?php
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->datePicker('resultDue',date(''));
                            ?></td>
                        </tr>

                        <tr class='row1'>
                            <td valign="middle" height='25'><B>Class</B></td>
                            <td valign="middle"><B>Roll Number</B></td>
                            <td valign="middle"><B>Session</B></td>
                            <td valign="middle"><B>School/Institute/University Last Attended/Address</B></td>
                            <td valign="middle"><B>Name of Board/University</B></td>
                            <td valign="middle"><B>Education Stream</B></td>
                            <td valign="middle" align='left'><B>Maximum Marks</B></td>
                            <td valign="middle" align='left'><B>Marks Obtained</B></td>
                            <td valign="middle" align='left'><B>Percentage</B></td>
                        </tr>
                        <?php
                        global $classResults;
                        if(isset($classResults) && is_array($classResults)) {
                            $count = count($classResults);
                             foreach($classResults as $key=>$value) {


                            echo "<tr class='row0'>
                                <td valign='middle'>".$value."</td>
                                <td valign='middle'><input type='text' id='rollNo".$key."' name='rollNo[]' class='inputbox1' maxlength='20' size='15' onBlur='changeCase(this.id)'/></td>
                                <td valign='middle'><input type='text' id='session".$key."' name='session[]' class='inputbox1' maxlength='10' size='10' onBlur='changeCase(this.id)'/></td>
                                <td valign='middle'><input type='text' id='institute".$key."' name='institute[]' class='inputbox1' size='40' maxlength='250' onBlur='changeCase(this.id)'/></td>
                                <td valign='middle'><input type='text' id='board".$key."' name='board[]' class='inputbox' maxlength='250' onBlur='changeCase(this.id)'/></td>
                                <td valign='middle'><input type='text' id='educationStream".$key."' name='educationStream[]' class='inputbox1' style='width:80px' maxlength='50' onBlur='changeCase(this.id)'/></td>
                                <td valign='middle' align='left'><input type='text' id='maxMarks".$key."' name='maxMarks[]' class='inputbox1'  maxlength='6' size='6' onBlur='calculatePercentage(".$key.")'/></td>
                                <td valign='middle' align='left'><input type='text' id='marks".$key."' name='marks[]' class='inputbox1'  maxlength='6' size='6' onBlur='calculatePercentage(".$key.")' /></td>
                                <td valign='middle' align='left'><input type='text' id='percentage".$key."' name='percentage[]' class='inputbox1' maxlength='6' size='6' readonly/><input type='hidden' id='previousClass".$key."' name='previousClass[]' value='".$key."'/></td>
                                </tr>";
                            }
                            echo "<input type='hidden' id='countRecord' name='countRecord' value='".$count."'/>";

                        }
                        ?>


                        </table>
                        </fieldset>
                    </td>
                    </tr>
                    <tr>
                        <td height="5"></td>
                    </tr>
                    <tr  <?php echo $styleNewOld; ?>>    
                        <td valign="top">
                        <fieldset class="fieldset">
                        <legend>Parent Details</legend>
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
                            <input type="text" id="fatherName" name="fatherName" class="inputbox"  maxlength="100" onBlur="changeCase(this.name)"/></td>
                            <td class="contenttab_internal_rows" width="100"><nobr><b>Occupation</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="fatherOccupation" name="fatherOccupation" class="inputbox" maxlength="100" onBlur="changeCase(this.name)"/>
                            </td>
                            <td class="contenttab_internal_rows" width="110"><nobr><b>Email</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="fatherEmail" name="fatherEmail" class="inputbox" maxlength="100"/></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Mobile</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="fatherMobile" name="fatherMobile" class="inputbox" maxlength="20"/>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Address1</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="fatherAddress1" name="fatherAddress1" class="inputbox" maxlength="255" onBlur="changeCase(this.name)"/></td>
                            <td class="contenttab_internal_rows"><nobr><b>Address2</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="fatherAddress2" name="fatherAddress3" class="inputbox" maxlength="255" onBlur="changeCase(this.name)"/></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Country</b></nobr><b><?php require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                            echo HtmlFunctions::getInstance()->getHelpLink('FindStudent',COUNTRY_DETAIL);?></b></td>
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
                            <td class="contenttab_internal_rows"><nobr><b>State</b></nobr><b><?php require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                            echo HtmlFunctions::getInstance()->getHelpLink('FindStudent',STATE_DETAIL);?></b></td>
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
                            <td class="contenttab_internal_rows"><nobr><b>City</b></nobr><b><?php require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                            echo HtmlFunctions::getInstance()->getHelpLink('FindStudent',CITY_DETAIL);?></b></td>
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
                        </tr>
                        <tr>
                            <td height="5"></td>
                        </tr>
                        <tr>
                            <td height="5"><B><U>Mother Details</U></B></td>
                            <td height="5" colspan="3"><input type="checkbox" id="sameFatherText" name="sameFatherText" onClick="copyFatherText()"/>(Same as Father Detail)</td>
                        </tr>
                        <tr>
                            <td height="5"></td>
                        </tr>
                        <!--tr>
                            <td class="contenttab_internal_rows"><nobr><b>Name</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <select size="1" class="inputbox" name="motherTitle" id="motherTitle" style="display:none">
                            <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getTitleData(2);
                            ?>
                            </select>
                            <input type="text" id="motherName" name="motherName" class="inputbox" size="28" maxlength="100" onBlur="changeCase(this.name)"/></td>
                             <td class="contenttab_internal_rows" colspan="3"><input type="checkbox" name="dipanjan1" onclick="makeMotherFieldsToggle(this.checked);"><b>Not applicable other fields in this frame</b></td>
                        </tr-->
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
                            <input type="text" id="motherName" name="motherName" class="inputbox" size="28" maxlength="100" onBlur="changeCase(this.name)"/></td>

                            <td class="contenttab_internal_rows"><nobr><b>Occupation</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="motherOccupation" name="motherOccupation" class="inputbox" maxlength="100" onBlur="changeCase(this.name)"/>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Email</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="motherEmail" name="motherEmail" class="inputbox" maxlength="100"/></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Mobile</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="motherMobile" name="motherMobile" class="inputbox" maxlength="20"/>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Address1</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="motherAddress1" name="motherAddress1" class="inputbox" maxlength="255" onBlur="changeCase(this.name)"/></td>
                            <td class="contenttab_internal_rows"><nobr><b>Address2</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="motherAddress2" name="motherAddress2" class="inputbox" maxlength="255" onBlur="changeCase(this.name)"/></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Country</b></nobr><b><?php require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                            echo HtmlFunctions::getInstance()->getHelpLink('FindStudent',COUNTRY_DETAIL);?></b></td>
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
                            <td class="contenttab_internal_rows"><nobr><b>State</b></nobr><b><?php require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                            echo HtmlFunctions::getInstance()->getHelpLink('FindStudent',STATE_DETAIL);?></b></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="motherStates" id="motherStates" onChange="autoPopulate(this.value,'city','Add','motherStates','motherCity');">
                            <option value="">Select</option>
                            <?php
                                /*require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getStatesData($defaultStateId);*/
                            ?>
                            </select></td>
                            <td class="contenttab_internal_rows"><nobr><b>City</b></nobr><b><?php require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                            echo HtmlFunctions::getInstance()->getHelpLink('FindStudent',CITY_DETAIL);?></b></td>
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
                        </tr>
                        <tr>
                            <td height="5"></td>
                        </tr>
                         <tr>
                            <td height="5"><B><U>Guardian Details</U></B></td>
                            <td height="5" colspan="5"><input type="checkbox" id="sameFatherText1" name="sameFatherText1" onClick="copyGuardianText()"/>(Same as Father Detail)&nbsp;&nbsp;&nbsp;<input type="checkbox" id="sameMotherText" name="sameMotherText" onClick="copyGuardianMotherText()"/>(Same as Mother Detail)</td>
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
                            <input type="text" id="guardianName" name="guardianName" class="inputbox" size="28" maxlength="100" onBlur="changeCase(this.name)"/></td>
                            <td class="contenttab_internal_rows"><nobr><b>Occupation</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="guardianOccupation" name="guardianOccupation" class="inputbox" maxlength="100" onBlur="changeCase(this.name)"/></td>
                            <td class="contenttab_internal_rows"><nobr><b>Email</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="guardianEmail" name="guardianEmail" class="inputbox" maxlength="100"/></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Mobile</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="guardianMobile" name="guardianMobile" class="inputbox" maxlength="20"/>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Address1</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="guardianAddress1" name="guardianAddress1" class="inputbox" maxlength="255" onBlur="changeCase(this.name)"/></td>
                            <td class="contenttab_internal_rows"><nobr><b>Address2</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="guardianAddress2" name="guardianAddress2" class="inputbox" maxlength="255" onBlur="changeCase(this.name)"/></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Country</b></nobr><b><?php require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                            echo HtmlFunctions::getInstance()->getHelpLink('FindStudent',COUNTRY_DETAIL);?></b></td>
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
                            <td class="contenttab_internal_rows"><nobr><b>State</b></nobr><b><?php require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                            echo HtmlFunctions::getInstance()->getHelpLink('FindStudent',STATE_DETAIL);?></b></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="guardianStates" id="guardianStates" onChange="autoPopulate(this.value,'city','Add','guardianStates','guardianCity');">
                            <option value="">Select</option>
                            <?php
                                /*require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getStatesData($defaultStateId);*/
                            ?>
                            </select>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>City</b></nobr><b><?php require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                            echo HtmlFunctions::getInstance()->getHelpLink('FindStudent',CITY_DETAIL);?></b></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="guardianCity" id="guardianCity">
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
                        </tr>
                        </table>
                        </fieldset>
                    </td>
                    </tr>
                    <tr>
                        <td height="5"></td>
                    </tr>
                    <tr>
                        <td valign="top">
                        <fieldset class="fieldset">
                        <legend>Contact Details</legend>
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
                            <td class="padding_top"><input type="text" id="correspondeceAddress1" name="correspondeceAddress1" class="inputbox" maxlength="255" onBlur="changeCase(this.name)"/></td>
                            <td class="contenttab_internal_rows" width="100"><nobr><b>Address2</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="correspondeceAddress2" name="correspondeceAddress2" class="inputbox" maxlength="255" onBlur="changeCase(this.name)"/>
                            </td>
                            <td class="contenttab_internal_rows" width="110"><nobr><b>Pincode</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="correspondecePincode" name="correspondecePincode" class="inputbox" maxlength="10"/></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Country</b></nobr><b><?php require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                            echo HtmlFunctions::getInstance()->getHelpLink('FindStudent',COUNTRY_DETAIL);?></b></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <select size="1" class="selectfield" name="correspondenceCountry" id="correspondenceCountry" onChange="autoPopulate(this.value,'states','Add','correspondenceStates','correspondenceCity');"><option value="" selected="selected">Select</option>
                            <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getCountriesData($defaultCountryId);
                            ?>
                            </select></td>
                            <td class="contenttab_internal_rows"><nobr><b>State</b></nobr><b><?php require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                            echo HtmlFunctions::getInstance()->getHelpLink('FindStudent',STATE_DETAIL);?></b></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="correspondenceStates" id="correspondenceStates" onChange="autoPopulate(this.value,'city','Add','correspondenceStates','correspondenceCity');"><option value="">Select</option>
                            <?php
                                /*require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getStatesData($defaultStateId);*/
                            ?>
                            </select></td>
                            <td class="contenttab_internal_rows"><nobr><b>City</b></nobr><b><?php require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                            echo HtmlFunctions::getInstance()->getHelpLink('FindStudent',CITY_DETAIL);?></b></td>
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
                            <td class="padding_top"><input type="text" id="permanentAddress1" name="permanentAddress1" class="inputbox" maxlength="255" onBlur="changeCase(this.name)"/></td>
                            <td class="contenttab_internal_rows"><nobr><b>Address2</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="permanentAddress2" name="permanentAddress2" class="inputbox" maxlength="255" onBlur="changeCase(this.name)"/>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Pincode</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="permanentPincode" name="permanentPincode" class="inputbox" maxlength="10"/></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Country</b></nobr><b><?php require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                            echo HtmlFunctions::getInstance()->getHelpLink('FindStudent',COUNTRY_DETAIL);?></b></td>
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
                            <td class="contenttab_internal_rows"><nobr><b>State</b></nobr><b><?php require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                            echo HtmlFunctions::getInstance()->getHelpLink('FindStudent',STATE_DETAIL);?></b></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="permanentStates" id="permanentStates" onChange="autoPopulate(this.value,'city','Add','permanentStates','permanentCity');">
                            <option value="">Select</option>
                            <?php
                                /*require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getStatesData($defaultStateId);*/
                            ?>
                            </select>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>City</b></nobr><b><?php require_once(BL_PATH.'/HtmlFunctions.inc.php');   
                            echo HtmlFunctions::getInstance()->getHelpLink('FindStudent',CITY_DETAIL);?></b></td>
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
                         <tr  <?php echo $newTd; ?>> 
                            <td height="5" colspan="10"><B><U>Present Address</U></B></td>
                        </tr>
                         <tr  <?php echo $newTd; ?>> 
                            <td height="5"></td>
                        </tr>
                         <tr  <?php echo $newTd; ?>> 
                            <td class="contenttab_internal_rows"><nobr><b>Address1</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="presentAddress1" name="presentAddress1" class="inputbox" maxlength="255" onBlur="changeCase(this.name)"/></td>
                            <td class="contenttab_internal_rows"><nobr><b>Address2</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="presentAddress2" name="presentAddress2" class="inputbox" maxlength="255" onBlur="changeCase(this.name)"/>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Pincode</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="presentPincode" name="presentPincode" class="inputbox" maxlength="10"/></td>
                        </tr>
                         <tr  <?php echo $newTd; ?>> 
                            <td class="contenttab_internal_rows"><nobr><b>Country</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top" id="permCountry">
                            <select size="1" class="selectfield" name="presentCountry" id="presentCountry" onChange="autoPopulate(this.value,'states','Add','presentStates','presentCity');">
                            <option value="" selected="selected">Select</option>
                              <?php
                                 require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                 echo HtmlFunctions::getInstance()->getCountriesData($defaultCountryId);
                              ?>
                            </select>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>State</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="presentStates" id="presentStates" onChange="autoPopulate(this.value,'city','Add','presentStates','presentCity');">
                            <option value="">Select</option>
                            <?php
                                /*require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getStatesData($defaultStateId);*/
                            ?>
                            </select>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>City</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="presentCity" id="presentCity">
                            <option value="">Select</option>
                            <?php
                                /*require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getCityData($defaultCityId);*/
                            ?>
                            </select></td>
                        </tr>
                        <tr  <?php echo $newTd; ?>> 
                            <td class="contenttab_internal_rows"><nobr><b>Contact No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="presentPhone" name="presentPhone" class="inputbox" maxlength="20"/></td>
                        </tr>
                        </table>
                        </fieldset>
                    </td>
                    </tr>
                    <tr>
                        <td height="5"></td>
                    </tr>
                    <tr  <?php echo $styleNewOld; ?>> 
                        <td valign="top">
                            <fieldset class="fieldset">
                            <legend>Ailment</legend>
                                <table width="65%" border="0" cellspacing="0" cellpadding="0" >
                                    <tr>
                                        <td class="contenttab_internal_rows" colspan="3"><nobr><b>Do you suffer from any ailment that requires medical attention on a regular basis</b>
                                        <b>:</b>
                                        <input type="radio" id="regularAilmentYes" name="regularAilment" value="1" onclick="getAilmentYes();"/><b>Yes</b> &nbsp;
                                        <input type="radio" id="regularAilmentNo" name="regularAilment" value="0" checked="checked" onclick="getAilmentNo();"/><b>No</b>
                                        </td>
                                    </tr>
                                    <tr><td>If yes, please specify the nature of ailment</td>
                                        <td class="contenttab_internal_rows" style="width:10px"><b>:</b></td>
                                        <td class="padding_top"><input type="text" id="natureAilment" name="natureAilment" class="inputbox" maxlength="20" onBlur="changeCase(this.name)" disabled="disabled"/></td>
                                    </tr>
                                    <tr><td>Do any of your family members suffer from any of the following ailments</td>
                                        <td class="contenttab_internal_rows" style="width:10px"><b>:</b></td>
                                        <td>
                                            <select multiple id="familyAilment" name="familyAilment[]" style="vertical-align:middle" size="3" class="selectfield" disabled="disabled">
                                            <?php
                                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                echo HtmlFunctions::getInstance()->getFamilyAilmentData();
                                            ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr><td>Any other (please specify)</td>
                                        <td class="contenttab_internal_rows"><b>:</b></td>
                                        <td class="padding_top"><input type="text" id="otherAilment" name="otherAilment" class="inputbox" maxlength="20" onBlur="changeCase(this.name)" disabled="disabled"/></td>
                                    </tr>
                                </table>
                            </fieldset>
                          </td>
                    </tr>
                    <tr  <?php echo $styleNewOld; ?>>  
                        <td height="5"></td>
                    </tr>
                    <tr  <?php echo $styleNewOld; ?>>  
                        <td valign="top">
                            <fieldset class="fieldset">
                            <legend>Miscellaneous</legend>
                                <table width="75%" border="0" cellspacing="0" cellpadding="0" >
                                    <tr>
                                        <td class="contenttab_internal_rows"><nobr><b>Do you have taken Coaching</b></td>
                                        <td class="contenttab_internal_rows" style="width:10px"><b>:</b></td>
                                        <td>
                                            <select id="coachingCenter" name="coachingCenter" style="vertical-align:middle" class="selectfield" onchange="changeCoaching();">
                                            <option value="">Select</option>
                                                <?php
                                                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                                    echo HtmlFunctions::getInstance()->getCoachingCentersData();
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr><td class="contenttab_internal_rows">Coaching Class Branch Manager Name</td>
                                        <td class="contenttab_internal_rows"><b>:</b></td>
                                        <td class="padding_top"><input type="text" id="coachingManager" name="coachingManager" class="inputbox" maxlength="50" onBlur="changeCase(this.name)" disabled="disabled"/></td>

                                        <td class="contenttab_internal_rows">Address & Phone No.</td>
                                        <td class="contenttab_internal_rows" style="width:10px"><b>:</b></td>
                                        <td class="padding_top"><input type="text" id="address" name="address" class="inputbox" maxlength="250" onBlur="changeCase(this.name)" disabled="disabled"/></td>
                                    </tr>
                                </table>

                                <table width="88%" border="0" cellspacing="0" cellpadding="0" >
                                    <tr>
                                        <td class="contenttab_internal_rows" colspan="3"><nobr><b>Do you have work experience</b>
                                        <b>:</b>
                                         <input type="radio" id="workExperienceYes" name="workExperience" value="1" onclick="getWorkExperienceYes();"/><b>Yes</b> &nbsp;
                                         <input type="radio" id="workExperienceNo" name="workExperience" value="0" checked="checked" onclick="getWorkExperienceNo();"/><b>No</b> &nbsp;
                                        </td>
                                    </tr>
                                    <tr><td class="contenttab_internal_rows">If yes, Area/Department</td>
                                        <td class="contenttab_internal_rows" style="width:10px"><b>:</b></td>
                                        <td class="padding_top"><input type="text" id="department" name="department" class="inputbox" maxlength="100" onBlur="changeCase(this.name)" disabled="disabled"/></td>
                                        <td class="contenttab_internal_rows">Organization</td>
                                        <td class="contenttab_internal_rows" style="width:10px"><b>:</b></td>&nbsp;
                                        <td>
                                            <td class="padding_top"><input type="text" id="organization" name="organization" class="inputbox" maxlength="100" onBlur="changeCase(this.name)" disabled="disabled"/></td>
                                        </td>
                                        <td class="contenttab_internal_rows">Place</td>
                                        <td class="contenttab_internal_rows" style="width:10px"><b>:</b></td>&nbsp;
                                        <td>
                                            <td class="padding_top"><input type="text" id="place" name="place" class="inputbox" maxlength="100" onBlur="changeCase(this.name)" disabled="disabled"/></td>
                                        </td>
                                    </tr>
                               </table>
                            </fieldset>
                        </td>
                    </tr>
                    <tr  <?php echo $newTd; ?>>
                        <td valign="top">
                        <fieldset class="fieldset">
                        <legend>Spouse / Emergency Contact Details</legend>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                        <tr>
                            <td height="5"></td>
                        </tr>
                         <tr>
                            <td height="5" colspan="10"><B><U>Spouse / Emergenency Contact Details</U></B></td>
                        </tr>
                         <tr>
                            <td height="5"></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows" width="100"><nobr><b>Name</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                               <input type="text" id="spouseName" name="spouseName" class="inputbox"  maxlength="100" />
                            </td>
                            <td class="contenttab_internal_rows" width="100"><nobr><b>Relation</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="spouseRelation" name="spouseRelation" class="inputbox" maxlength="100"/></td>
                            <td class="contenttab_internal_rows" width="100"><nobr><b>Email</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="spouseEmail" name="spouseEmail" class="inputbox" maxlength="100"/></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Address1</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="spouseAddress1" name="spouseAddress1" class="inputbox" maxlength="255"/></td>
                            <td class="contenttab_internal_rows"><nobr><b>Address2</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="spouseAddress2" name="spouseAddress2" class="inputbox" maxlength="255"/>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Pincode</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="spousePincode" name="spousePincode" class="inputbox" maxlength="10"/></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Country</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top" id="permCountry">
                            <select size="1" class="selectfield" name="spouseCountry" id="spouseCountry" onChange="autoPopulate(this.value,'states','Add','spouseStates','spouseCity');">
                            <option value="" selected="selected">Select</option>
                              <?php
                                 require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                 echo HtmlFunctions::getInstance()->getCountriesData($defaultCountryId);
                              ?>
                            </select>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>State</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="spouseStates" id="spouseStates" onChange="autoPopulate(this.value,'city','Add','spouseStates','spouseCity');">
                            <option value="">Select</option>
                            <?php
                                /*require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getStatesData($defaultStateId);*/
                            ?>
                            </select>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>City</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="spouseCity" id="spouseCity">
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
                            <td class="padding_top"><input type="text" id="spousePhone" name="spousePhone" class="inputbox" maxlength="20"/></td>
                        </tr>
                        </table>
                        </fieldset>
                    </td>
                    </tr> 
                    <tr>
                        <td height="5"></td>
                    </tr>
                    <tr>
                        <td valign="top">
                        <fieldset class="fieldset">
                        <legend>Student Remarks</legend>
                        <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td height="5"></td>
                        </tr>

                        <tr>
                            <td class="contenttab_internal_rows" width="100" valign="top"><nobr><b>Remarks</b></nobr></td>
                            <td class="contenttab_internal_rows" valign="top"><b>:</b></td>
                            <td class="padding_top"><textarea type="text" id="studentRemarks" name="studentRemarks" class="inputbox1" rows="5" cols="50" onBlur="changeCase(this.name)"/></textarea></td>
                            <td width='100'></td>
                            <td class="contenttab_internal_rows" width="215" valign="top"><nobr><b>Reference number/ Reference name</b></nobr></td>
                            <td class="contenttab_internal_rows" valign="top"><b>:</b></td>
                            <td class="padding_top" valign="top"><input type="text" id="referenceName" name="referenceName" class="inputbox" maxlength="200" onBlur="changeCase(this.name)"/></td>
                        </tr>

                        </table>
                        </fieldset>
                    </td>
                    </tr>

                    <tr>
                        <td height="5"></td>
                    </tr>
                    <tr>
                        <td  align="right" style="padding-right:5px">
                        <input type="hidden" name="listSubject" value="1"><input type="image" name="imgSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onclick="return validateLoginForm(event);return false;" />&nbsp;&nbsp;<input type="image" name="imgReset" src="<?php echo IMG_HTTP_PATH;?>/reset.gif" onclick="return resetLoginForm();return false;" /> </td>
                    </tr>
                    <tr>
                        <td height="5"></td>
                    </tr>
                    </table>
                    </form>
             </td>
          </tr>
          </table>
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
<!--Daily Attendance Help  Details  Div-->
<?php  floatingDiv_Start('divHelpInfo','Help','12','','','1'); ?>
<div id="helpInfoDiv" style="overflow:auto; WIDTH:390px; vertical-align:top;"> 
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
        <tr>
            <td height="5px"></td></tr>
        <tr>
        <tr>    
            <td width="89%">
                <div id="helpInfo" style="vertical-align:top;" ></div> 
            </td>
        </tr>
    </table>
</div>       
<?php floatingDiv_End(); ?> 
<!-- help icon!-->
<?php  floatingDiv_Start('divHelpInfo','Help','12','','','1'); ?>
<div id="helpInfoDiv" style="overflow:auto; WIDTH:390px; vertical-align:top;"> 
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
        <tr>
            <td height="5px"></td></tr>
        <tr>
        <tr>    
            <td width="89%">
                <div id="helpInfo" style="vertical-align:top;" ></div> 
            </td>
        </tr>
    </table>
</div>       
<?php floatingDiv_End(); ?> 
<!--Daily Attendance Help  Details  End -->   




<?php
// $History: addStudentContents.php $
//
//*****************  Version 23  *****************
//User: Rajeev       Date: 10-02-23   Time: 3:46p
//Updated in $/LeapCC/Templates/Student
//updated admit student with config setting for registration number
//
//*****************  Version 22  *****************
//User: Rajeev       Date: 10-02-11   Time: 6:23p
//Updated in $/LeapCC/Templates/Student
//      0002822: Admit Student – Admin > Select Class, Personal Details,
//Parent Details... field should be properly aligned on the page
//
//*****************  Version 21  *****************
//User: Rajeev       Date: 09-11-11   Time: 3:50p
//Updated in $/LeapCC/Templates/Student
//added validations on domicile and nationality
//
//*****************  Version 20  *****************
//User: Rajeev       Date: 09-09-18   Time: 3:26p
//Updated in $/LeapCC/Templates/Student
//updated student roll no length to 30
//
//*****************  Version 19  *****************
//User: Rajeev       Date: 8/07/09    Time: 7:07p
//Updated in $/LeapCC/Templates/Student
//Changed Label "Degree" to "Class"
//
//*****************  Version 18  *****************
//User: Rajeev       Date: 7/20/09    Time: 4:02p
//Updated in $/LeapCC/Templates/Student
//Fixed bugs and enhancements 0000616-0000620
//
//*****************  Version 17  *****************
//User: Rajeev       Date: 7/14/09    Time: 10:59a
//Updated in $/LeapCC/Templates/Student
//Updated with config parameters to be default selected
//
//*****************  Version 16  *****************
//User: Rajeev       Date: 7/13/09    Time: 12:20p
//Updated in $/LeapCC/Templates/Student
//Updated with Onkeyup event to onblur event in previous academic
//
//*****************  Version 15  *****************
//User: Rajeev       Date: 7/11/09    Time: 10:35a
//Updated in $/LeapCC/Templates/Student
//Updated with respect to new enhancement of exchanging max marks and
//marks obtained
//
//*****************  Version 14  *****************
//User: Rajeev       Date: 6/24/09    Time: 5:32p
//Updated in $/LeapCC/Templates/Student
//Updated with default institute and degree
//
//*****************  Version 13  *****************
//User: Rajeev       Date: 6/22/09    Time: 4:27p
//Updated in $/LeapCC/Templates/Student
//Made Enhancements:
//1) Changing Case as per requirements
//2) N/A for mother name to be removed
//
//
//*****************  Version 12  *****************
//User: Rajeev       Date: 6/15/09    Time: 7:22p
//Updated in $/LeapCC/Templates/Student
//Enhanced "Admin Student" module as mailed by Puspender Sir.
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 6/02/09    Time: 11:39a
//Updated in $/LeapCC/Templates/Student
//Fixed bugs  1104-1110  and enhanced with student previous academics
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 5/28/09    Time: 3:30p
//Updated in $/LeapCC/Templates/Student
//Added blood group, reference name, sports activity, student previous
//academic, in print report as well as find student tab
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 5/27/09    Time: 6:59p
//Updated in $/LeapCC/Templates/Student
//added reference name,blood group, fee receipt no,institute wise search
//for class student previous academic in admit student
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 3/18/09    Time: 12:28p
//Updated in $/LeapCC/Templates/Student
//Updated with default country, state,city config settings
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 3/09/09    Time: 10:54a
//Updated in $/LeapCC/Templates/Student
//Increased the student contact info maxlength to 40
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 1/12/09    Time: 5:30p
//Updated in $/LeapCC/Templates/Student
//Updated with Required field, centralized message, left align
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 1/10/09    Time: 11:05a
//Updated in $/LeapCC/Templates/Student
//removed “Date of Birth, Email, Contact Number, Nationality, Domicile
//and Titles”  validations
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 1/06/09    Time: 12:40p
//Updated in $/LeapCC/Templates/Student
//removed last name validations
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 12/23/08   Time: 2:16p
//Updated in $/LeapCC/Templates/Student
//Updated with single class selection dropdown and REQUIRED_FIELD
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/22/08   Time: 1:39p
//Updated in $/LeapCC/Templates/Student
//Updated as per CC functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Student
//
//*****************  Version 12  *****************
//User: Rajeev       Date: 9/17/08    Time: 2:47p
//Updated in $/Leap/Source/Templates/Student
//updated with asterix on mandatory fields
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 9/04/08    Time: 6:53p
//Updated in $/Leap/Source/Templates/Student
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 9/01/08    Time: 12:07p
//Updated in $/Leap/Source/Templates/Student
//removed the gaps in between controls
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 8/27/08    Time: 3:23p
//Updated in $/Leap/Source/Templates/Student
//updated formatting
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 8/25/08    Time: 5:31p
//Updated in $/Leap/Source/Templates/Student
//updated student detail functions
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 8/06/08    Time: 3:51p
//Updated in $/Leap/Source/Templates/Student
//updated query and formatting
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 8/05/08    Time: 6:29p
//Updated in $/Leap/Source/Templates/Student
//remove all the demo issues
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 7/17/08    Time: 11:55a
//Updated in $/Leap/Source/Templates/Student
//updated date of birth check
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/10/08    Time: 5:53p
//Updated in $/Leap/Source/Templates/Student
//made the student admit module ajax based
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/09/08    Time: 3:14p
//Updated in $/Leap/Source/Templates/Student
//updated admint student with domicile, mgmt category and management
//reference fields
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/07/08    Time: 4:16p
//Created in $/Leap/Source/Templates/Student
//intial checkin
?>
