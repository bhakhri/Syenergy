<?php
//-------------------------------------------------------
// Purpose: to design the layout for student detail.
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
$queryString =  $_SERVER['QUERY_STRING'];
$visibleTabArray = array();
$count=0;
 global $sessionHandler;
$optionalField = $sessionHandler->getSessionVariable('INSTITUTE_ADMIT_STUDENT_OPTIONAL_FIELD');



    $admitStudentRequiredField = $sessionHandler->getSessionVariable('ADMIT_STUDENT_REQUIRED_FIELD');
 
    
    $studentRequiredField = REQUIRED_FIELD;  
    if($admitStudentRequiredField==0) {
      $studentRequiredField = '';  
    }
?>
 <form method="POST" name="addForm"  action="<?php echo HTTP_LIB_PATH;?>/Student/fileUpload.php" id="addForm" method="post" enctype="multipart/form-data" style="display:inline">
      <input type='hidden' name='admitOptionalField' id='admitOptionalField' value="<?php echo  $admitStudentRequiredField; ?>" readonly="readonly">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td class="title">
            <input type="hidden" name="classId" value="<?php echo $studentDataArr[0]['classId']?>">
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
                <td>Student&nbsp;&raquo;&nbsp;Search Student</td>
                <td align="right"><INPUT type="image" alt="back" title="back" src="<?php echo IMG_HTTP_PATH ?>/bigback.gif" border="0" onClick='listPage("searchStudent.php?<?php echo $queryString?>&listStudent=1");return false;'>&nbsp;&nbsp;&nbsp;</td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
             <td class="content" valign="top">
             <table width="100%" border="0" cellspacing="0" cellpadding="0" height="695">
             <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="contenttab_row1"><span class="content_title">Student Detail:</span>
                        &nbsp;<B><U>Name:</U></B>&nbsp;<?php echo $studentDataArr[0]['firstName']." ".$studentDataArr[0]['lastName'];?>
                        &nbsp;&nbsp;<B><U>Class:</U></B>&nbsp;<?php echo $studentClassArr[0]['className'];?>
                        &nbsp;&nbsp;<B><U>Admission Date:</U></B>&nbsp;
                        <?php echo (UtilityManager::formatDate($studentDataArr[0]['dateOfAdmission']));?>&nbsp;&nbsp;<span class="content_title">Study Period:</span>
                        <select size="1" class="selectfield" name="studyPeriod" id="studyPeriod" style="width:100px" onchange="refreshStudentData('<?php echo $REQUEST_DATA[id]?>',this.value,tabNumber);">
                        <option value="0">All</option>
                        <?php
                         require_once(BL_PATH.'/HtmlFunctions.inc.php');
                         echo HtmlFunctions::getInstance()->getStudyPeriodName(add_slashes($_REQUEST['id']),$studentDataArr[0]['classId']);
                        ?>
                        </select>
                        </td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row"  valign="top">
<div id="dhtmlgoodies_tabView1">     
<div class="dhtmlgoodies_aTab">
    <div id="personalScroll1" style="overflow:auto; width:970px; height:620px">    
       <div id="personalScroll2" style="width:98%;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                <tr>
                    <td height="5"></td>
                </tr>
                <tr>
                    <td class="contenttab_internal_rows"><nobr><b>First Name</b><?php echo REQUIRED_FIELD?></nobr></td>
                    <td class="contenttab_internal_rows"><b>:</b></td>
                    <td class="padding_top" width='450'><input type="text" id="studentName" name="studentName" class="inputbox" value="<?php echo $studentDataArr[0]['firstName']?>" maxlength="100" <?php echo $inActiveClass?>/>
                    </td>
                    <td class="contenttab_internal_rows" width='150'><nobr><b>Last Name </b></nobr></td>
                    <td class="contenttab_internal_rows"><b>:</b></td>
                    <td class="padding_top" width='450'><input type="text" id="studentLName" name="studentLName" class="inputbox" value="<?php echo $studentDataArr[0]['lastName']?>" maxlength="100" <?php echo $inActiveClass?>/>
                    </td>
                    <td colspan="4" align="center" valign="middle" rowspan="9">
                    <table border="0" width="200" height="200">
                    <tr>
                        <td width="50"></td>
                        <td class="field1_heading">
                        <?php if($studentDataArr[0]['studentPhoto']){


                            $imgSrc= STUDENT_PHOTO_PATH."/".$studentDataArr[0][studentPhoto];
                        }
                        else{
                            $imgSrc= IMG_HTTP_PATH."/notfound.jpg";

                        }
                        echo "<img src='".$imgSrc."' width='170' height='190' id='studentImageId'/>";
                        ?>
                        </td>
                            </tr>
                            </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b><?php echo ROLL_NO ?></b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="studentRoll" name="studentRoll" class="inputbox"   value="<?php echo $studentDataArr[0]['rollNo']?>" maxlength="30" <?php echo $inActiveClass?>/>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b><?php echo REG_NO ?></b><?php echo REQUIRED_FIELD?></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="studentReg" name="studentReg" class="inputbox"   value="<?php echo $studentDataArr[0]['regNo']?>" maxlength="60" <?php echo $inActiveClass?>/>
                            </td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b><?php echo UNIV_ROLL_NO ?></b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="studentUniversityNo" name="studentUniversityNo" class="inputbox"  value="<?php echo $studentDataArr[0]['universityRollNo']?>"  maxlength="30" <?php echo $inActiveClass?>/>
                            </td>
                            
                            <?php
                            global $sessionHandler;
                            if($optionalField == 0){
                            ?>
                            <td class="contenttab_internal_rows"><nobr><b><?php echo UNIV_REG_NO ?></b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="studentUniversityRegNo" name="studentUniversityRegNo" class="inputbox"  value="<?php echo $studentDataArr[0]['universityRegNo']?>" maxlength="30" <?php echo $inActiveClass?>/>
                            </td>
                            <?php } ?>
                        </tr>
                        
                        <?php
                            global $sessionHandler;
                            if($optionalField == 1){
                            ?>
                        <tr>
                            <!--<td class="contenttab_internal_rows"><nobr><b>Batch</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="batch" name="batch" class="inputbox"  value="" maxlength="30" />
                            </td>-->
                            <td class="contenttab_internal_rows"><nobr><b>Current Organization</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <input type="text" id="currentOrg" name="currentOrg" class="inputbox" maxlength="100"  value="<?php echo $studentDataArr[0]['currentOrg']?>"/>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Designation</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <input type="text" id="companyDesignation" name="companyDesignation" class="inputbox" maxlength="100"   value="<?php echo $studentDataArr[0]['companyDesignation']?>" />
                            </td>
                            </tr>
                            <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Work Email</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="workEmail" name="workEmail" class="inputbox"  value="<?php echo $studentDataArr[0]['workEmail']?>" maxlength="100" />
                            </td>
                             <td class="contenttab_internal_rows"><nobr><b>Office Phone No</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="officeContactNo" name="officeContactNo" class="inputbox"  value="<?php echo $studentDataArr[0]['officeContactNo']?>" maxlength="100" />
                            </td>
                            
                            <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Role</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="role" name="role" class="inputbox"  />
                            </td>
                        </tr>
                    
                        <?php } ?>
                            <td class="contenttab_internal_rows"><nobr><b>Date Of Birth</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top" nowrap>
                            <select size="1" name="studentDate" id="studentDate" <?php echo $disableClass?> class="inputbox1">
                            <option value="">Date</option>
                              <?php
                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                  echo HtmlFunctions::getInstance()->getBirthDate($date);
                              ?>
                            </select>

                            <select size="1" name="studentMonth" id="studentMonth"  <?php echo $disableClass?> class="inputbox1">
                            <option value="">Month</option>
                              <?php
                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                  echo HtmlFunctions::getInstance()->getBirthMonth($month);
                              ?>
                            </select>

                            <select size="1" name="studentYear" id="studentYear" <?php echo $disableClass?> class="inputbox1">
                            <option value="">Year</option>
                              <?php
                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                  echo HtmlFunctions::getInstance()->getEmployeeBirthYear($year);
                              ?>
                            </select>

                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Gender</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="radio" id="genderRadio" name="genderRadio" value="M" <?php if($studentDataArr[0]['studentGender']=="M") echo "checked";?>  <?php echo $disableClass?>/>Male&nbsp;&nbsp;<input type="radio" id="genderRadio" name="genderRadio" value="F" <?php if($studentDataArr[0]['studentGender']=="F") echo "checked";?>  <?php echo $disableClass?>/>Female
                            </td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Email</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="studentEmail" name="studentEmail" class="inputbox" value="<?php echo $studentDataArr[0]['studentEmail']?>" maxlength="100" <?php echo $inActiveClass?>/>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Alternate Email</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="alternateEmail" name="alternateEmail" class="inputbox" value="<?php echo $studentDataArr[0]['alternateStudentEmail']?>" maxlength="100" <?php echo $inActiveClass?>/>
                            </td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Contact No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="studentNo" name="studentNo" class="inputbox" value="<?php echo $studentDataArr[0]['studentPhone']?>" maxlength="40" <?php echo $inActiveClass?>/></td>
                            <td class="contenttab_internal_rows"><nobr><b>Mobile No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="studentMobile" name="studentMobile" class="inputbox" value="<?php echo $studentDataArr[0]['studentMobileNo']?>" maxlength="15" <?php echo $inActiveClass?>/> </td>
                        </tr>
                            <td class="contenttab_internal_rows"><nobr><b>Domicile</b><?php echo $studentRequiredField; ?></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="studentDomicile" id="studentDomicile" <?php echo $disableClass?>>
                             <option value="">Select</option>
                              <?php
                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                 echo HtmlFunctions::getInstance()->getStatesData($studentDataArr[0]['domicileId']);
                              ?>
                            </select>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Category</b><?php echo $studentRequiredField; ?></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="studentCategory" id="studentCategory" <?php echo $disableClass?>>
                            <option value="">Select</option>
                            <?php
                              global $sessionHandler;
                              $defaultCategoryId = '';
                              if($studentDataArr[0]['quotaId']!='') {
                                $defaultCategoryId = $studentDataArr[0]['quotaId'];
                              }
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getCurrentCategories($defaultCategoryId,' WHERE parentQuotaId=0 ',$showParentCat='1');
                              //echo HtmlFunctions::getInstance()->getCategoryClassData($studentDataArr[0]['quotaId']==''?$studentDataArr[0]['quotaId'] : $studentDataArr[0]['quotaId']);
                            ?>
                            </select></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>User Name</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="studentUser" name="studentUser" class="inputbox" value="<?php echo $studentUserDataArr[0]['userName']?>" <?php if($studentUserDataArr[0]['userName']) echo "READONLY";?>  maxlength="30"/>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Password</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="password" id="studentPassword" name="studentPassword" class="inputbox" <?php if($studentUserDataArr[0]['userName']) echo "value='1****1'";?> maxlength="10" <?php echo $inActiveClass?>/> <br>
<span style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 9px; color: red;">(Maximum allowed length is 10 characters)</span>
                            </td>
                        </tr>
                        <tr>
                        
                        <?php
                            global $sessionHandler;
                            if($optionalField == 0){
                            ?>
                            <td class="contenttab_internal_rows"><nobr><b>Exam</b><?php echo $studentRequiredField; ?></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="entranceExam" id="entranceExam" <?php echo $disableClass?>>
                            <option value="">Select</option>
                            <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getEntranceExamData($REQUEST_DATA['entranceExam']==''?$studentDataArr[0]['compExamBy'] : $studentDataArr[0]['compExamBy']);
                            ?>
                            </select></td>
                            <td class="contenttab_internal_rows"><nobr><b>Rank</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="studentRank" name="studentRank" class="inputbox" value="<?php echo $studentDataArr[0]['compExamRank']?>"  maxlength="10" <?php echo $inActiveClass?>/></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Ent. Exam Roll No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="entranceRoll" name="entranceRoll" class="inputbox" value="<?php echo $studentDataArr[0]['compExamRollNo']?>"  maxlength="15" <?php echo $inActiveClass?>/></td>
                            <?php } ?>
                            <td class="contenttab_internal_rows"><nobr><b>Student Photo</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="file" id="studentPhoto" name="studentPhoto" class="inputbox1" <?php echo $disableClass?>/><iframe id="uploadTargetAdd" name="uploadTargetAdd" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
                            </td>
                        </tr>

                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Fee Receipt No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td><input type="text" id="feeReceiptNo" name="feeReceiptNo" class="inputbox"  maxlength="15" value="<?php echo $studentDataArr[0]['feeReceiptNo']?>"/></td>
                            
                            <?php
                            global $sessionHandler;
                            if($optionalField == 0){
                            ?>
                            <td class="contenttab_internal_rows"><nobr><b>Sports Activity</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td><input type="text" id="sportsActivity" name="sportsActivity" class="inputbox" maxlength="250" value="<?php echo $studentDataArr[0]['studentSportsActivity']?>"/></td>
                            <td class="contenttab_internal_rows"><nobr><b>Blood Group</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td><select size="1" class="inputbox1" name="bloodGroup" id="bloodGroup">
                            <option value="">Select</option>
                            <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getBloodGroupData($studentDataArr[0]['studentBloodGroup']);
                            ?>
                            </select></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Date Of Admission</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td><select size="1" name="studentAdmissionDate" id="studentAdmissionDate" <?php echo $disableClass?> class="inputbox1">
                            <option value="">Date</option>
                              <?php
                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                  echo HtmlFunctions::getInstance()->getBirthDate($admissionDate);
                              ?>
                            </select>

                            <select size="1" name="studentAdmissionMonth" id="studentAdmissionMonth"  <?php echo $disableClass?> class="inputbox1">
                            <option value="">Month</option>
                              <?php
                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                  echo HtmlFunctions::getInstance()->getBirthMonth($admissionMonth);
                              ?>
                            </select>

                            <select size="1" name="studentAdmissionYear" id="studentAdmissionYear" <?php echo $disableClass?> class="inputbox1">
                            <option value="">Year</option>
                              <?php
                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                  echo HtmlFunctions::getInstance()->getEmployeeBirthYear($admissionYear);
                              ?>
                            </select>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Branch</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td><select size="1" class="selectfield" name="branch" id="branch" >
                                <?php
                                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                    echo HtmlFunctions::getInstance()->getBranchData($studentDataArr[0]['branchId']);
                                ?>
                            </select>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Nationality</b><?php echo $studentRequiredField; ?></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="country" id="country" <?php echo $disableClass?>>
                            <option value="">Select</option>
                            <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getNationalityData($studentDataArr[0]['nationalityId']);
                            ?>
                            </select></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Student Status</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td><input type="checkbox" id="isActive" name="isActive" value="1" <?php if($studentDataArr[0]['studentStatus']) echo "checked";?> <?php echo $disableClass?>/></td>
                            
                            <?php
                            global $sessionHandler;
                            if($optionalField == 0){
                            ?>
                            <td class="contenttab_internal_rows"><nobr><b>Is LEET</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td><input type="checkbox" id="isLeet" name="isLeet" value="1" <?php if($studentDataArr[0]['isLeet']) echo "CHECKED";?> <?php echo $disableClass?>/> </b></td>
                            <?php } ?>
                             <?php
                            global $sessionHandler;
                            if($optionalField == 0){
                            ?>
                            <td class="contenttab_internal_rows"><nobr><b>Is Migrated</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td><table><tr><td><input type="checkbox" id="isMigration"  name="isMigration" value="1" <?php if($studentDataArr[0]['isMigration']) echo "CHECKED";?> <?php echo $disableClass?>/> </b>
                            <?php } ?><td>  <!--This field was not in template file on live server so this file has to be uploaded again -->
				<td class="contenttab_internal_rows" id ="divMigStudy" name ="divMigStudy" >
				<nobr><b>Study Period</b>
				<b>:</b>
				<select id="migratedStudyPeriod" name="migratedStudyPeriod">
				 <option value="0">Select</option>
				<?php 
				for($i=1;$i<=20;$i++) {
				  if($studentDataArr[0]['migrationStudyPeriod']==$i){?>
				    <option value="<?php echo $i;?>" selected="selected"><?php echo $i;?></option>
				 <?php }else{?>
				 <option value="<?php echo $i;?>" ><?php echo $i;?></option>
				<?php  }
				} ?>
				</select>
				</nobr></div></td></tr></table>
                        </tr>
                        <tr>
                            <td height="5"></td>
                        </tr>
                         <tr>
                            <td height="5" colspan="4"><B><U>Correspondence Address</U></B></td>
                        </tr>
                         <tr>
                            <td height="5"></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Address1</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="correspondeceAddress1" name="correspondeceAddress1" class="inputbox"  value="<?php echo  $corrAddress1;?>"  maxlength="255" <?php echo $inActiveClass?>/></td>
                            <td class="contenttab_internal_rows"><nobr><b>Address2</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="correspondeceAddress2" name="correspondeceAddress2" class="inputbox"  value="<?php echo $corrAddress2;?>"  maxlength="255" <?php echo $inActiveClass?>/>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Pincode</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="correspondecePincode" name="correspondecePincode" class="inputbox"  value="<?php echo $studentDataArr[0]['corrPinCode']?>"  maxlength="10" <?php echo $inActiveClass?>/></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Country</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <select size="1" class="selectfield" name="correspondenceCountry" id="correspondenceCountry" onChange="autoPopulate(this.value,'states','Add','correspondenceStates','correspondenceCity');"  <?php echo $disableClass?>>
                            <option value="" selected="selected">Select</option>
                            <?php
                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                  echo HtmlFunctions::getInstance()->getCountriesData($REQUEST_DATA['correspondenceCountry']==''? $studentDataArr[0]['corrCountryId'] : $REQUEST_DATA['correspondenceCountry'] );
                            ?>
                            </select>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>State</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="correspondenceStates" id="correspondenceStates" onChange="autoPopulate(this.value,'city','Add','correspondenceStates','correspondenceCity');"  <?php echo $disableClass?>>
                            <option value="">Select</option>
                            <?php
                                  //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                  //echo HtmlFunctions::getInstance()->getStatesData($studentDataArr[0]['corrStateId'] ," WHERE countryId=$studentDataArr[0]['corrCountryId']" );
                            ?>
                            </select>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>City</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="correspondenceCity" id="correspondenceCity"  <?php echo $disableClass?>>
                            <option value="">Select</option>
                            <?php
                                 // require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                  //echo HtmlFunctions::getInstance()->getCityData($studentDataArr[0]['corrCityId'] ," WHERE stateId=$studentDataArr[0]['corrStateId']" );
                            ?>
                            </select></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Contact No</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="correspondecePhone" name="correspondecePhone" class="inputbox" value="<?php echo $studentDataArr[0]['corrPhone']?>"  maxlength="10" <?php echo $inActiveClass?>/>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Address Verified</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="checkbox" id="correspondeceVerified" name="correspondeceVerified" value="1" <?php if($studentDataArr[0]['correspondenceAddressVerified']) echo "CHECKED";?> <?php echo $disableClass?>/>
                            </td>
                        </tr>
                         <tr>
                            <td height="5"></td>
                        </tr>
                         <tr>
                            <td height="5" colspan="4"><B><U>Permanent Address</U></B>&nbsp;&nbsp;&nbsp;<input type="checkbox" id="sameText" name="sameText" onClick="copyText()" <?php echo $disableClass?>/>(Same as above)</td>
                        </tr>
                         <tr>
                            <td height="5"></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Address1</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="permanentAddress1" name="permanentAddress1" class="inputbox" value="<?php echo $permAddress1;?>"  maxlength="255" <?php echo $inActiveClass?>/></td>
                            <td class="contenttab_internal_rows"><nobr><b>Address2</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="permanentAddress2" name="permanentAddress2" class="inputbox" value="<?php echo $permAddress2;?>"  maxlength="255" <?php echo $inActiveClass?>/>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Pincode</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="permanentPincode" name="permanentPincode" class="inputbox" value="<?php echo $studentDataArr[0]['permPinCode']?>"  maxlength="10" <?php echo $inActiveClass?>/></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Country</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <select size="1" class="selectfield" name="permanentCountry" id="permanentCountry" onChange="autoPopulate(this.value,'states','Add','permanentStates','permanentCity');" <?php echo $disableClass?>>
                            <option value="" selected="selected">Select</option>
                            <?php
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getCountriesData($REQUEST_DATA['country']==''? $studentDataArr[0]['permCountryId'] : $REQUEST_DATA['country'] );
                            ?>
                            </select>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>State</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="permanentStates" id="permanentStates" onChange="autoPopulate(this.value,'city','Add','permanentStates','permanentCity');" <?php echo $disableClass?>>
                            <option value="">Select</option>
                            <?php
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getStatesData($studentDataArr[0]['permStateId'] ," WHERE countryId=$studentDataArr[0]['permCountryId']" );
                            ?>
                            </select>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>City</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="permanentCity" id="permanentCity" <?php echo $disableClass?>>
                            <option value="">Select</option>
                            <?php
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getCityData($studentDataArr[0]['permCityId'] ," WHERE stateId=$studentDataArr[0]['permStateId']" );
                            ?>
                            </select></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Contact No</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="permanentPhone" name="permanentPhone" class="inputbox" value="<?php echo $studentDataArr[0]['permPhone']?>"  maxlength="10" <?php echo $inActiveClass?>/></td>
                            <td class="contenttab_internal_rows"><nobr><b>Address Verified</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="checkbox" id="correspondeceVerified" name="correspondeceVerified" value="1" <?php if($studentDataArr[0]['correspondenceAddressVerified']) echo "CHECKED";?> <?php echo $disableClass?>/>
                            </td>
                        </tr>
                        <tr>
                            <td height="5"></td>
                        </tr>

                        <?php
                            global $sessionHandler;
                            if($optionalField == 1){
                        ?>
                    
                        <tr> 
                            <td height="5" colspan="10"><B><U>Present Address</U></B></td>
                        </tr>
                         <tr > 
                            <td height="5"></td>
                        </tr>
                         <tr> 
                            <td class="contenttab_internal_rows"><nobr><b>Address1</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="presentAddress1" name="presentAddress1"  value="<?php echo $studentDataArr[0]['presentAddress1']?>" class="inputbox" maxlength="255" onBlur="changeCase(this.name)"/></td>
                            <td class="contenttab_internal_rows"><nobr><b>Address2</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="presentAddress2" name="presentAddress2" value="<?php echo $studentDataArr[0]['presentAddress2']?>" class="inputbox" maxlength="255" onBlur="changeCase(this.name)"/>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Pincode</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="presentPincode" name="presentPincode" value="<?php echo $studentDataArr[0]['presentPinCode']?>" class="inputbox" maxlength="10"/></td>
                        </tr>
                         <tr  > 
                           <td class="contenttab_internal_rows"><nobr><b>Country</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <select size="1" class="selectfield" name="presentCountry" id="presentCountry" onChange="autoPopulate(this.value,'states','Add','presentStates','presentCity');" <?php echo $disableClass?>>
                            <option value="" selected="selected">Select</option>
                            <?php
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getCountriesData($REQUEST_DATA['country']==''? $studentDataArr[0]['presentCountryId'] : $REQUEST_DATA['country'] );
                            ?>
                            </select>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>State</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="presentStates" id="presentStates" onChange="autoPopulate(this.value,'city','Add','presentStates','presentCity');" <?php echo $disableClass?>>
                            <option value="">Select</option>
                            <?php
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getStatesData($studentDataArr[0]['presentStateId'] ," WHERE countryId=".$studentDataArr[0]['presentCountryId'] );
                            ?>
                            </select>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>City</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="presentCity" id="presentCity" <?php echo $disableClass?>>
                            <option value="">Select</option>
                            <?php
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getCityData($studentDataArr[0]['presentCityId'] ," WHERE stateId=".$studentDataArr[0]['presentStateId'] );
                            ?>
                            </select></td>
                        </tr>
                        <?php } ?>
                    
                        
                         <tr>
                        <td height="5"></td>
                        </tr>
                         <tr>
                        <td height="5"></td>
                        </tr>
                    <?php
                            global $sessionHandler;
                            if($optionalField == 1){
                        ?>
                    
                    <tr> 
                            <td height="5" colspan="10"><B><U>Spouse / Emergency Contact Details</U></B></td>
                        </tr>
                         <tr > 
                            <td height="5"></td>
                        </tr>
                         <tr  > 
                            <td class="contenttab_internal_rows" width="100"><nobr><b>Name</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                               <input type="text" id="spouseName" name="spouseName" class="inputbox" value="<?php echo $studentDataArr[0]['spouseName']?>"   maxlength="100" />
                            </td>
                            <td class="contenttab_internal_rows" width="100"><nobr><b>Relation</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="spouseRelation" name="spouseRelation" value="<?php echo $studentDataArr[0]['spouseRelation']?>"  class="inputbox" maxlength="100"/></td>
                            <td class="contenttab_internal_rows" width="100"><nobr><b>Email</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="spouseEmail" name="spouseEmail" value="<?php echo $studentDataArr[0]['spouseEmail']?>"  class="inputbox" maxlength="100"/></td>                        
                        </tr>
                         <tr> 
                           <td class="contenttab_internal_rows"><nobr><b>Address1</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="spouseAddress1" name="spouseAddress1" value="<?php echo $studentDataArr[0]['spouseAddress1']?>" lass="inputbox" maxlength="255"/></td>
                            <td class="contenttab_internal_rows"><nobr><b>Address2</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="spouseAddress2" name="spouseAddress2" value="<?php echo $studentDataArr[0]['spouseAddress2'] ?>" lass="inputbox" maxlength="255"/>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Pincode</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="spousePincode" name="spousePincode" value="<?php echo $studentDataArr[0]['spousePinCode']?>"  class="inputbox" maxlength="10"/></td>
                         </tr>
                         <tr>
                             <td class="contenttab_internal_rows"><nobr><b>Country</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <select size="1" class="selectfield" name="spouseCountry" id="spouseCountry" onChange="autoPopulate(this.value,'states','Add','spouseStates','spouseCity');" <?php echo $disableClass?>>
                            <option value="" selected="selected">Select</option>
                            <?php
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getCountriesData($REQUEST_DATA['country']==''? $studentDataArr[0]['spouseCountryId'] : $REQUEST_DATA['country'] );
                            ?>
                            </select>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>State</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="spouseStates" id="spouseStates" onChange="autoPopulate(this.value,'city','Add','spouseStates','spouseCity');" <?php echo $disableClass?>>
                            <option value="">Select</option>
                            <?php
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getStatesData($studentDataArr[0]['spouseStateId'] ," WHERE countryId=".$studentDataArr[0]['spouseCountryId'] );
                            ?>
                            </select>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>City</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="spouseCity" id="spouseCity" <?php echo $disableClass?>>
                            <option value="">Select</option>
                            <?php
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getCityData($studentDataArr[0]['spouseCityId'] ," WHERE stateId=".$studentDataArr[0]['spouseStateId'] );
                            ?>
                            </select></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Contact No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="spousePhone" name="spousePhone"  value="<?php echo $studentDataArr[0]['spousePhone']?>"   class="inputbox" maxlength="20"/></td>
                        </tr>
                        
                        
                        <?php } ?>
                        <tr>
                        <td height="15"></td>
                        </tr>
                        
                        <tr>
                            <td class="contenttab_internal_rows" width="150"><nobr><b>Student Remarks</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><textarea type="text" id="studentRemarks" name="studentRemarks" class="inputbox1" rows="1" cols="28" <?php echo $inActiveClass?>/><?php echo $studentDataArr[0]['studentRemarks']?></textarea></td>
                                
                                    
                            <?php
                            global $sessionHandler;
                            if($optionalField == 0){
                            ?>
                            <td class="contenttab_internal_rows" width="315" valign="top"><nobr><b>Reference number/<br>name</b></nobr></td>
                            <td class="contenttab_internal_rows" valign="top"><b>:</b></td>
                            <td class="padding_top" valign="top"><input type="text" id="referenceName" name="referenceName" class="inputbox" maxlength="200" value="<?php echo $studentDataArr[0]['referenceName']?>"/></td>
                            <?php } ?>

                        </tr>
                        <?php
                                if($inActiveClass==''){
                        ?>
                        <tr>
                            <td  align="center" style="padding-right:5px" valign="bottom" colspan="9"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateAll(this.form);"/></td>
                        </tr>
                        <?php
                            }
                        ?>
                        </table>
          </div>              
     </div>             
</div>
<div class="dhtmlgoodies_aTab">
                        <table width="100%" border="0" cellspacing="5" cellpadding="5">
                        <tr style="display:none">
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Father Title</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td width="15%" class="padding_top"><select size="1" class="inputbox1" name="fatherTitle" id="fatherTitle" tabindex="1" <?php echo $disableClass?>>
                            <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getTitleData($studentDataArr[0]['fatherTitle']);
                            ?>
                            </select></td>
                            <td width="10%" class="contenttab_internal_rows"><nobr><b>Mother Title</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td width="25%" class="padding_top"><select size="1" class="inputbox1" name="motherTitle" id="motherTitle" tabindex="14" <?php echo $disableClass?>>
                            <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getTitleData($studentDataArr[0]['motherTitle']==''?$studentDataArr[0]['motherTitle'] : $studentDataArr[0]['motherTitle']);
                            ?>
                            </select></td>
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Guardian Title</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td width="15%" class="padding_top"><select size="1" class="inputbox1" name="guardianTitle" id="guardianTitle"  tabindex="27" <?php echo $disableClass?>>
                            <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getTitleData($studentDataArr[0]['guardianTitle']==''?$studentDataArr[0]['guardianTitle'] : $studentDataArr[0]['guardianTitle']);
                            ?>
                            </select></td>
                        </tr>
                        <tr>
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Father's Name</b><?php echo REQUIRED_FIELD?></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td width="15%" class="padding_top"><input type="text" name="fatherName" id="fatherName" class="inputbox" value="<?php echo $studentDataArr[0]['fatherName']?>"  tabindex="2" maxlength="100" <?php echo $inActiveClass?>/></td>
                            <td width="10%" class="contenttab_internal_rows"><nobr><b>Mother's Name</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td width="25%" class="padding_top"><input type="text" name="motherName" id="motherName" class="inputbox" value="<?php echo $studentDataArr[0]['motherName']?>"  tabindex="15" maxlength="100" <?php echo $inActiveClass?>/></td>
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Guardian's Name</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td width="15%" class="padding_top"><input type="text" name="guardianName" class="inputbox" value="<?php echo $studentDataArr[0]['guardianName']?>"  tabindex="28" maxlength="100" <?php echo $inActiveClass?>/></td>
                        </tr>

                        <tr>
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Occupation</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td width="15%" class="padding_top"><input type="text" name="fatherOccupation" class="inputbox" value="<?php echo $studentDataArr[0]['fatherOccupation']?>"  tabindex="3" maxlength="100" <?php echo $inActiveClass?>/></td>
                            <td width="10%" class="contenttab_internal_rows"><nobr><b>Occupation</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td width="25%" class="padding_top"><input type="text" name="motherOccupation" class="inputbox" value="<?php echo $studentDataArr[0]['motherOccupation']?>"  tabindex="16" maxlength="100" <?php echo $inActiveClass?>/></td>
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Occupation</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td width="15%" class="padding_top"><input type="text" name="guardianOccupation" class="inputbox" value="<?php echo $studentDataArr[0]['guardianOccupation']?>"  tabindex="29" maxlength="100" <?php echo $inActiveClass?>/></td>
                        </tr>
                        <tr>
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Mobile No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td width="15%" class="padding_top"><input type="text" name="fatherMobileNo" class="inputbox" value="<?php echo $studentDataArr[0]['fatherMobileNo']?>"  tabindex="4" maxlength="20" <?php echo $inActiveClass?>/></td>
                            <td width="10%" class="contenttab_internal_rows"><nobr><b>Mobile No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td width="25%" class="padding_top"><input type="text" name="motherMobileNo" class="inputbox" value="<?php echo $studentDataArr[0]['motherMobileNo']?>" tabindex="17" maxlength="20" <?php echo $inActiveClass?>/></td>
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Mobile No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td width="15%" class="padding_top"><input type="text" name="guardianMobileNo" class="inputbox" value="<?php echo $studentDataArr[0]['guardianMobileNo']?>"  tabindex="30" maxlength="20" <?php echo $inActiveClass?>/></td>
                        </tr>
                        <tr>
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Email</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td width="15%" class="padding_top"><input type="text" name="fatherEmail" id="fatherEmail" class="inputbox" value="<?php echo $studentDataArr[0]['fatherEmail']?>"  tabindex="5" maxlength="100" <?php echo $inActiveClass?>/></td>
                            <td width="10%" class="contenttab_internal_rows"><nobr><b>Email</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td width="25%" class="padding_top"><input type="text" name="motherEmail" id="motherEmail" class="inputbox" value="<?php echo $studentDataArr[0]['motherEmail']?>" tabindex="18" maxlength="100" <?php echo $inActiveClass?>/></td>
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Email</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td width="15%" class="padding_top"><input type="text" name="guardianEmail" id="guardianEmail" class="inputbox" value="<?php echo $studentDataArr[0]['guardianEmail']?>"  tabindex="31" maxlength="100" <?php echo $inActiveClass?>/></td>
                        </tr>
                        <tr>
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Address 1</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td width="15%" class="padding_top"><input type="text" name="fatherAddress1" class="inputbox" value="<?php echo $studentDataArr[0]['fatherAddress1']?>"  tabindex="6" maxlength="255" <?php echo $inActiveClass?>/></td>
                            <td width="10%" class="contenttab_internal_rows"><nobr><b>Address 1</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td width="25%" class="padding_top"><input type="text" name="motherAddress1" class="inputbox" value="<?php echo $studentDataArr[0]['motherAddress1']?>" tabindex="19" maxlength="255" <?php echo $inActiveClass?>/></td>
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Address 1</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td width="15%" class="padding_top"><input type="text" name="guardianAddress1" class="inputbox" value="<?php echo $studentDataArr[0]['guardianAddress1']?>"  tabindex="32" maxlength="255" <?php echo $inActiveClass?>/></td>
                        </tr>
                        <tr>
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Address 2</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td width="15%" class="padding_top"><input type="text" name="fatherAddress2" class="inputbox" value="<?php echo $studentDataArr[0]['fatherAddress2']?>"  tabindex="7" maxlength="255" <?php echo $inActiveClass?>/></td>
                            <td width="10%" class="contenttab_internal_rows"><nobr><b>Address 2</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td width="25%" class="padding_top"><input type="text" name="motherAddress2" class="inputbox" value="<?php echo $studentDataArr[0]['motherAddress2']?>" tabindex="20" maxlength="255" <?php echo $inActiveClass?>/></td>
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Address 2</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td width="15%" class="padding_top"><input type="text" name="guardianAddress2" class="inputbox" value="<?php echo $studentDataArr[0]['guardianAddress2']?>"  tabindex="33" maxlength="255" <?php echo $inActiveClass?>/></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Country</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="fatherCountry" id="fatherCountry" onChange="autoPopulate(this.value,'states','Add','fatherStates','fatherCity');"  tabindex="8" <?php echo $disableClass?>>
                            <option value="" selected="selected">Select</option>
                            <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getCountriesData($REQUEST_DATA['country']==''? $studentDataArr[0]['fatherCountryId'] : $REQUEST_DATA['country'] );
                            ?>
                            </select></td>
                            <td class="contenttab_internal_rows"><nobr><b>Country</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="motherCountry" id="motherCountry" onChange="autoPopulate(this.value,'states','Add','motherStates','motherCity');" tabindex="21" <?php echo $disableClass?>>
                            <option value="" selected="selected">Select</option>
                            <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getCountriesData($REQUEST_DATA['country']==''? $studentDataArr[0]['motherCountryId'] : $REQUEST_DATA['country'] );
                            ?>
                            </select></td>
                            <td class="contenttab_internal_rows"><nobr><b>Country</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="guardianCountry" id="guardianCountry" onChange="autoPopulate(this.value,'states','Add','guardianStates','guardianCity');"  tabindex="34" <?php echo $disableClass?>>
                            <option value="" selected="selected">Select</option>
                            <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getCountriesData($REQUEST_DATA['country']==''? $studentDataArr[0]['guardianCountryId'] : $REQUEST_DATA['country'] );
                            ?>
                            </select></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>State</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="fatherStates" id="fatherStates" onChange="autoPopulate(this.value,'city','Add','fatherStates','fatherCity');"  tabindex="9" <?php echo $disableClass?>>
                            <option value="">Select</option>
                            <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getStatesData($studentDataArr[0]['fatherStateId'] ," WHERE countryId=$studentDataArr[0]['fatherCountryId']" );
                            ?>
                            </select>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>State</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="motherStates" id="motherStates" onChange="autoPopulate(this.value,'city','Add','motherStates','motherCity');" tabindex="22" <?php echo $disableClass?>>
                            <option value="">Select</option>
                            <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getStatesData($studentDataArr[0]['motherStateId'] ," WHERE countryId=$studentDataArr[0]['motherCountryId']" );
                            ?>
                            </select></td>
                            <td class="contenttab_internal_rows"><nobr><b>State</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="guardianStates" id="guardianStates" onChange="autoPopulate(this.value,'city','Add','guardianStates','guardianCity');" tabindex="35" <?php echo $disableClass?>>
                            <option value="">Select</option>
                            <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getStatesData($studentDataArr[0]['guardianStateId'] ," WHERE countryId=$studentDataArr[0]['guardianCountryId']" );
                            ?>
                            </select></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>City</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="fatherCity" id="fatherCity"  tabindex="10" <?php echo $disableClass?>>
                           <option value="">Select</option>
                              <?php
                                  require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                  echo HtmlFunctions::getInstance()->getCityData($studentDataArr[0]['fatherCityId'] ," WHERE stateId=$studentDataArr[0]['fatherStateId']" );
                              ?>
                            </select></td>
                            <td class="contenttab_internal_rows"><nobr><b>City</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="motherCity" id="motherCity" tabindex="23" <?php echo $disableClass?>>
                           <option value="">Select</option>
                           <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getCityData($studentDataArr[0]['motherCityId'] ," WHERE stateId=$studentDataArr[0]['motherStateId']" );
                          ?>
                            </select></td>
                            <td class="contenttab_internal_rows"><nobr><b>City</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="guardianCity" id="guardianCity" tabindex="36" <?php echo $disableClass?>>
                           <option value="">Select</option>
                          <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getCityData($studentDataArr[0]['guardianCityId'] ," WHERE stateId=$studentDataArr[0]['guardianStateId']" );
                          ?>
                            </select></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Pin Code</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" name="fatherPinCode" class="inputbox" value="<?php echo $studentDataArr[0]['fatherPinCode']?>"  tabindex="11" maxlength="10" <?php echo $inActiveClass?>/></td>
                            <td class="contenttab_internal_rows"><nobr><b>Pin Code</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" name="motherPinCode" class="inputbox" value="<?php echo $studentDataArr[0]['motherPinCode']?>" tabindex="24" maxlength="10" <?php echo $inActiveClass?>/></td>
                            <td class="contenttab_internal_rows"><nobr><b>Pin Code</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" name="guardianPinCode" class="inputbox" value="<?php echo $studentDataArr[0]['guardianPinCode']?>" tabindex="37" maxlength="10" <?php echo $inActiveClass?>/></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Username</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" name="fatherUserName" id="fatherUserName" class="inputbox1" value="<?php echo $fatherUserDataArr[0]['userName']?>" <?php if($fatherUserDataArr[0]['userName']) echo "READONLY";?>  tabindex="12" maxlength="10" <?php echo $inActiveClass?>/></td>
                            <td class="contenttab_internal_rows"><nobr><b>Username</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" name="motherUserName" id="motherUserName" class="inputbox"value="<?php echo $motherUserDataArr[0]['userName']?>" <?php if($motherUserDataArr[0]['motherUserName']) echo "READONLY";?> tabindex="25"  maxlength="10" <?php echo $inActiveClass?>/></td>
                            <td class="contenttab_internal_rows"><nobr><b>Username</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" name="guardianUserName" id="guardianUserName" class="inputbox" value="<?php echo $guardianUserDataArr[0]['userName']?>" <?php if($guardianUserDataArr[0]['guardianUserName']) echo "READONLY";?> tabindex="38"  maxlength="10" <?php echo $inActiveClass?>/></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Password</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="password" name="fatherPassword" id="fatherPassword" class="inputbox" <?php if($fatherUserDataArr[0]['userName']) echo "value='1****1'";?>  tabindex="13"  maxlength="10" <?php echo $inActiveClass?>/> <span style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 9px; color: red;">(Maximum allowed length is 10 characters)</span></td>
                            <td class="contenttab_internal_rows"><nobr><b>Password</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="password" name="motherPassword" id="motherPassword" class="inputbox" <?php if($motherUserDataArr[0]['userName']) echo "value='1****1'";?> tabindex="26"  maxlength="10" <?php echo $inActiveClass?>/> <span style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 9px; color: red;">(Maximum allowed length is 10 characters)</span> </td>
                            <td class="contenttab_internal_rows"><nobr><b>Password</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="password" name="guardianPassword" id="guardianPassword" class="inputbox" <?php if($guardianUserDataArr[0]['userName']) echo "value='1****1'";?> tabindex="39"  maxlength="10" <?php echo $inActiveClass?>/> <span style="font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 9px; color: red;">(Maximum allowed length is 10 characters)</span> </td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Address Verified</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="checkbox" id="fatherVerified" name="fatherVerified" value="1" <?php if($studentDataArr[0]['fatherAddressVerified']) echo "CHECKED";?> <?php echo $disableClass?>/></td>
                            <td class="contenttab_internal_rows"><nobr><b>Address Verified</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="checkbox" id="motherVerified" name="motherVerified" value="1" <?php if($studentDataArr[0]['motherAddressVerified']) echo "CHECKED";?> <?php echo $disableClass?>/></td>
                            <td class="contenttab_internal_rows"><nobr><b>Address Verified</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="checkbox" id="guardianVerified" name="guardianVerified" value="1" <?php if($studentDataArr[0]['guardianAddressVerified']) echo "CHECKED";?> <?php echo $disableClass?>/></td>
                        </tr>
                        <?php
                            if($inActiveClass==''){
                        ?>
                        <tr>
                            <td  align="center" style="padding-right:5px" valign="bottom" colspan="9"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateAll(this.form);"/></td>
                        </tr>
                        <?php
                          }
                        ?>
                        </table>
</div>
<div class="dhtmlgoodies_aTab">
                        <table width="100%" border="0" cellspacing="5" cellpadding="5">
                        <tr>
                            <td>
                            <table width="35%" border="0" cellspacing="1" cellpadding="1" align="center">
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
                            <td><div id="results1"></div>
                            </td>
                        </tr>
                        </table>
</div>
<div class="dhtmlgoodies_aTab">
                        <table width="90%" border="0" cellspacing="5" cellpadding="5" align="center">
                        <tr>
                            <td width="10%" class="contenttab_internal_rows"><nobr><b>University</b></nobr></td>
                            <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                            <td width="20%" align="left"><?php echo $university;?> </td>
                            <td width="10%" class="contenttab_internal_rows"><nobr><b>Institute </b></nobr></td>
                            <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                            <td width="50%" class="padding_top"><?php echo $sessionHandler->getSessionVariable('InstituteName');?></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Degree</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo $degree;?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Branch</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo $branch;?></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Batch</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo $batch?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Current Study Period</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo $periodName?></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Institute Roll No</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo $studentDataArr[0]['rollNo']!='' ? $studentDataArr[0]['rollNo'] :"--";?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Institute Registration No</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo $studentDataArr[0]['regNo']!='' ? $studentDataArr[0]['regNo'] :"--";?></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>University Roll No</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo $studentDataArr[0]['universityRollNo']!='' ? $studentDataArr[0]['universityRollNo'] :"--";?></td>
                            <td class="contenttab_internal_rows"><nobr><b>University Registration No</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo $studentDataArr[0]['universityRegNo']!='' ? $studentDataArr[0]['universityRegNo'] :"--";?></td>
                        </tr>
                        <tr>
                            <td colspan="7">
                                <div id="scroll2" style="overflow:auto; width:825px; height:380px">
                                  <div id="courseResultDiv"></div>
                                </div>
                            </td>
                        </tr>
                        </table>
</div>
<div class="dhtmlgoodies_aTab" style="overflow:auto;">
                        <table width="80%" border="0" cellspacing="0" cellpadding="0" align='center'>
                        <tr>
                            <td width="10%" class="contenttab_internal_rows"><nobr><b>Quota</b></nobr></td>
                            <td class="contenttab_internal_rows" width="2%" ><b>:</b></td>
                            <td class="padding_top" width="40%" ><?php echo $quotaName;?></td>
                            <td rowspan="7" valign="top">
                            <!--Hostel and Room Information-->
                              <table border="0" cellpadding="0" cellspacing="0" height="100%" width="80%">
                                 <tr>
                                 
                                 <?php
                                global $sessionHandler;
                                if($optionalField == 0){
                                ?>
                                   <td colspan="3"  align="center" bgcolor="#F4F3EA"><nobr><b>Hostel Information</b></nobr></td>
                                </tr>
                                <tr>
                                   <td width="10%" class="contenttab_internal_rows"><nobr><b>Hostel Name</b></nobr></td>
                                   <td class="padding" width="1%"><b>:</b></td>
                                   <td class="padding_top"><?php
                                   if($studentDataArr[0]['hostelId']!=''){
                                    $hostelArr=CommonQueryManager::getInstance()->getHostelName('hostelId',' WHERE hostelId='.$studentDataArr[0]['hostelId']);
                                    echo $hostelArr[0]['hostelName']!=''?$hostelArr[0]['hostelName']:NOT_APPLICABLE_STRING ;
                                   }
                                   else{
                                       echo NOT_APPLICABLE_STRING;
                                   }

                                    ?></td>
                                </tr>
                                <tr>
                                  <td class="contenttab_internal_rows"><nobr><b>Hostel Room No.</b></nobr></td>
                                  <td class="padding" width="1%"><b>:</b></td>
                                  <td class="padding_top">
                                  <?php
                                  if($studentDataArr[0]['hostelRoomId']!=''){
                                    $hostelRoomArr=CommonQueryManager::getInstance()->getHostelRoom('hostelRoomId',' WHERE hostelRoomId='.$studentDataArr[0]['hostelRoomId']);
                                    echo $hostelRoomArr[0]['roomName']!=''?$hostelRoomArr[0]['roomName']:NOT_APPLICABLE_STRING ;
                                  }
                                  else{
                                   echo NOT_APPLICABLE_STRING;
                                  }

                                    ?></td>
                                </tr>
                                <tr>
                                 <td class="contenttab_internal_rows"><nobr><b>Room Type</b></nobr></td>
                                 <td class="padding" width="1%"><b>:</b></td>
                                 <td class="padding_top">
                                  <?php
                                     echo $studentRoomFacilitiesArr[0]['roomType']!=''?$studentRoomFacilitiesArr[0]['roomType']:NOT_APPLICABLE_STRING;
                                  ?></td>
                                </tr>
                                <tr>
                                <td class="contenttab_internal_rows"><nobr><b>Attached Bath</b></nobr></td>
                                <td class="padding" width="1%"><b>:</b></td>
                                <td class="padding_top">
                                <?php
                                    echo $studentRoomFacilitiesArr[0]['attachedBath']!=''?$studentRoomFacilitiesArr[0]['attachedBath']:NOT_APPLICABLE_STRING;
                                ?></td>
                                </tr>
                                <tr>
                                <td class="contenttab_internal_rows"><nobr><b>Air Conditioned</b></nobr></td>
                                <td class="padding" width="1%"><b>:</b></td>
                                <td class="padding_top">
                                <?php
                                    echo $studentRoomFacilitiesArr[0]['airConditioned']!=''?$studentRoomFacilitiesArr[0]['airConditioned'] :NOT_APPLICABLE_STRING;
                                ?></td>
                                </tr>
                                <tr>
                                 <td class="contenttab_internal_rows"><nobr><b>Internet Facility</b></nobr></td>
                                 <td class="padding" width="1%"><b>:</b></td>
                                 <td class="padding_top">
                                 <?php
                                   echo $studentRoomFacilitiesArr[0]['internetFacility']!=''?$studentRoomFacilitiesArr[0]['internetFacility']:NOT_APPLICABLE_STRING;
                                 ?></td>
                                </tr>
                                 <tr>
                                  <td class="contenttab_internal_rows"><nobr><b>Expected Checkout Date</b></nobr></td>
                                  <td class="padding" width="1%"><b>:</b></td>
                                  <td class="padding_top">
                                  <?php
                                  if($studentDataArr[0]['hostelId']!='' and $studentDataArr[0]['hostelRoomId']!=''){
                                     $studentId = $studentDataArr[0]['studentId'];
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
                                  }
                                  else{
                                   echo NOT_APPLICABLE_STRING;
                                  }

                                    ?></td>
                                    
                                    <?php } ?>
                                </tr>
                              </table>
                              <!--Hostel and Room Information-->
                            </td>

                        </tr>
                        <tr>

                            <td width="10%" class="contenttab_internal_rows"><nobr><b>I - Card Number</b></nobr></td>
                            <td class="contenttab_internal_rows" width="2%" ><b>:</b></td>
                            <td width="25%" class="padding_top"><input type="text" name="iCardNo"  id="iCardNo" class="inputbox" value="<?php echo $studentDataArr[0]['icardNumber']?>"  maxlength="10" <?php echo $inActiveClass?>/></td>
                        </tr>

                        <tr>
                        
                            <?php
                            global $sessionHandler;
                            if($optionalField == 0){
                            ?>
                            <td class="contenttab_internal_rows"><nobr><b>Mgmt. Category</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <input type="checkbox" id="managementCategory" name="managementCategory" value="1" <?php if($studentDataArr[0]['managementCategory']) echo "CHECKED";?> <?php echo $disableClass?>/></td>

                        </tr>
                        <tr>

                            <td class="contenttab_internal_rows"><nobr><b>Mgmt. Reference</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" name="managementReference" class="inputbox" value="<?php echo $studentDataArr[0]['managementReference']?>"  maxlength="10" <?php echo $inActiveClass?>/></td>
                        </tr>

                        <!--tr>
                            <td class="contenttab_internal_rows"><nobr><b>Hostel Name</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="studentHostel" id="studentHostel" onChange="autoPopulate(this.value,'hostel','Add','','studentRoom');" <?php echo $disableClass?>>
                            <option value="">Select Hostel</option>
                            <?php
                              require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getHostelName($studentDataArr[0]['hostelId']);
                            ?>
                            </select></td>
                            <td class="contenttab_internal_rows"><nobr><b>Hostel Room No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="studentRoom" id="studentRoom" <?php echo $disableClass?>>
                            <option value="">Select</option>
                            <?php
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                echo HtmlFunctions::getInstance()->getHostelRoomData($studentDataArr[0]['hostelRoomId'] ," WHERE hostelId='".$studentDataArr[0]['hostelId']."'");
                            ?>
                            </select></td>
                        </tr-->
                        <tr>

                            <td class="contenttab_internal_rows"><nobr><b>Transport Facility</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <?php
                                if($studentDataArr[0]['transportFacility']==1 && $studentDataArr[0]['busStopId']!='') {
                                  echo "Yes";
                                }
                                else {
                            ?>
                                 <select size="1" class="selectfield" name="transportFacility" id="transportFacility" <?php echo $disableClass?>>
                                    <option value="">Select</option>
                                    <option value="1" <?php if($studentDataArr[0]['transportFacility']==1) echo "SELECTED='SELECTED'";?>>Yes</option>
                                    <option value="0" <?php if(empty($studentDataArr[0]['transportFacility'])) echo "SELECTED='SELECTED'";?>>No</option>
                                 </select>
                             <?php
                                }
                              ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Bus Route No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <?php
                                require_once(MODEL_PATH."/CommonQueryManager.inc.php");
                                $foundArr1 = CommonQueryManager::getInstance()->getBusRoute('routeCode',"WHERE busRouteId= '".$studentDataArr[0]['busRouteId']."'");
                                echo parseOutput($foundArr1[0]['routeCode']);
                            ?>
                            <!--
                            <select size="1" class="selectfield" name="studentRoute" id="studentRoute" <?php echo $disableClass?>  onChange="autoPopulate(this.value,'busRoute','Add','','studentStop');">
                            <option value="">Select Route</option>
                                  //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                  //echo HtmlFunctions::getInstance()->getBusRouteName($studentDataArr[0]['busRouteId']);
                            </select>
                            -->
                            </td>
                        </tr>
                        <tr>

                            <td class="contenttab_internal_rows"><nobr><b>Bus Stop No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <?php
                                require_once(MODEL_PATH."/CommonQueryManager.inc.php");
                                $foundArr = CommonQueryManager::getInstance()->getBusStop('stopName',"WHERE busStopId= '".$studentDataArr[0]['busStopId']."'");
                                echo parseOutput($foundArr[0]['stopName']);
                            ?>
                            <!--
                            <select size="1" class="selectfield" name="studentStop" id="studentStop" <?php echo $disableClass?>>
                            <option value="">Select Bus Stop</option>
                              //require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              //echo HtmlFunctions::getInstance()->getBusStopName($studentDataArr[0]['busStopId']," WHERE busRouteId='".$studentDataArr[0]['busRouteId']."'");
                            </select>
                            -->

                            </td>
                        </tr>
                        <tr>

                            <td class="contenttab_internal_rows"><nobr><b>Hostel Facility</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <?php
                                if($studentDataArr[0]['hostelFacility']==1 && $studentDataArr[0]['hostelId']!='') {
                                  echo "Yes";
                                }
                                else {
                            ?>
                                 <select size="1" class="selectfield" name="hostelFacility" id="hostelFacility" <?php echo $disableClass?>>
                                    <option value="">Select</option>
                                    <option value="1" <?php if($studentDataArr[0]['hostelFacility']==1) echo "SELECTED='SELECTED'";?>>Yes</option>
                                    <option value="0" <?php if(empty($studentDataArr[0]['hostelFacility'])) echo "SELECTED='SELECTED'";?>>No</option>
                                 </select>
                             <?php
                                }
                              ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Ever Stayed in Hostel</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="everStayedInHostel" id="everStayedInHostel" onchange="getHostelStayed();" <?php echo $disableClass?>>
                            <option value="">Select</option>
                            <option value="1" <?php if($studentDataArr[0]['everHostelStayed']==1) echo "SELECTED='SELECTED'";?>>Yes</option>
                            <option value="0" <?php if($studentDataArr[0]['everHostelStayed']==0) echo "SELECTED='SELECTED'";?>>No</option>
                            </select></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>How Many Years</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="text" id="yearsInHostel" name="yearsInHostel" class="inputbox" maxlength="2" value="<?php if($studentDataArr[0]['everHostelStayed']==1) {
                                echo $studentDataArr[0]['years'];
                            }
                            else {?>"
                                    disabled="disabled"
                                "<?php }?>"/>
                            </td>
                        </tr>

                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Completed Graduation</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><select size="1" class="selectfield" name="completedGraduation" id="completedGraduation" onchange="getCompletedGraduation();" <?php echo $disableClass?>>
                            <option value="">Select</option>
                            <option value="1" <?php if($studentDataArr[0]['completedGraduation']==1) echo "SELECTED='SELECTED'";?>>Yes</option>
                            <option value="0" <?php if($studentDataArr[0]['completedGraduation']==0) echo "SELECTED='SELECTED'";?>>No</option>
                            </select></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Written Final Exam</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <?php if($studentDataArr[0]['completedGraduation'] == 1) { ?>
                                <select size="1" class="selectfield" name="writtenFinalExam" id="writtenFinalExam" disabled="disabled" <?php echo $disableClass?>>
                                    <option value="">Select</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            <?php } else { ?>
                            <select size="1" class="selectfield" name="writtenFinalExam" id="writtenFinalExam" <?php echo $disableClass?>>
                                <option value="">Select</option>
                                <option value="1" <?php if($studentDataArr[0]['writtenFinalExam']==1) echo "SELECTED='SELECTED'";?>>Yes</option>
                                <option value="0" <?php if($studentDataArr[0]['writtenFinalExam']==0) echo "SELECTED='SELECTED'";?>>No</option>
                            </select><?php } ?></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Result Due</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td><?php
                                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                if($studentDataArr[0]['writtenFinalExam'] == 1) {
                                    if($studentDataArr[0]['resultDue'] != '0000-00-00' AND $studentDataArr[0]['resultDue'] != '') {
                                        echo HtmlFunctions::getInstance()->datePicker('resultDue',$studentDataArr[0]['resultDue']);
                                    }
                                    else {
                                        echo HtmlFunctions::getInstance()->datePicker('resultDue','');
                                    }
                                }
                                else {
                                    echo HtmlFunctions::getInstance()->datePicker('resultDue','');
                                }
                            ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                        <td valign='top' colspan='9'>
			 
                            <table border="0" cellspacing="1" cellpadding="1" width='90%'>
                            <tr>
                                <td height="5"></td>
                            </tr>
                            <tr class='row1'>
                                <td valign="middle" height='25'><B>Class</B></td>
                                <td valign="middle"><B>Roll Number</B></td>
                                <td valign="middle"><B>Session</B></td>
                                <td valign="middle"><B>School/Institute/University Last Attended</B></td>
                                <td valign="middle"><B>Name of Board/University</B></td>
                                <td valign="middle"><B>Education Stream</B></td>
                                <td valign="middle" align='left'><B>Max.<br>Marks</B></td>
                                <td valign="middle" align='left'><B>Marks Obtained</B></td>
                                <td valign="middle" align='left'><B>%age</B></td>
				<td valign="middle" align='left'><B>Subject Detail</B></td>
                            </tr>
                            <?php
                            global $classResults;

                            if(isset($classResults) && is_array($classResults)) {

                                $count = count($classResults);
				                                $i=0;
                                foreach($classResults as $key=>$value) {
                                    $i++;
                                $disabled = '';
                                if($studentDataArr[0]['completedGraduation'] == 0 ) {
                                    if($i==3 OR $i==4) {
                                        $disabled = "disabled";
                                    }
                                }

                                echo "<tr class='row0'>
                                    <td valign='middle' nowrap>".$value."</td>
                                    <td valign='middle'><input type='text' $disabled id='rollNo".$key."' name='rollNo[]' class='inputbox1' maxlength='20' size='13' value='".$rollArr[$key]."'></td>
                                    <td valign='middle'><input type='text' $disabled id='session".$key."' name='session[]' class='inputbox1' maxlength='10' size='10' value='".$sessionArr[$key]."'></td>
                                    <td valign='middle'><input type='text' $disabled id='institute".$key."' name='institute[]' class='inputbox1' size='40' maxlength='250' value='".$instituteArr[$key]."'></td>
                                    <td valign='middle'><input type='text' $disabled id='board".$key."' name='board[]' class='inputbox' maxlength='250' value='".$boardArr[$key]."'></td>
                                    <td valign='middle'><input type='text' $disabled id='educationStream".$key."' name='educationStream[]' class='inputbox1' style='width:80px' maxlength='50' value='".$educationArr[$key]."'></td>
                                    <td valign='middle' align='left'><input type='text' $disabled id='maxMarks".$key."' name='maxMarks[]' class='inputbox1'  maxlength='6' size='8' onKeyup='calculatePercentage(".$key.")' value=".$maxMarksArr[$key]."></td>
                                    <td valign='middle' align='left'><input type='text' $disabled id='marks".$key."' name='marks[]' class='inputbox1' maxlength='6' size='8' onKeyup='calculatePercentage(".$key.")' value=".$marksArr[$key]."></td>

                                    <td valign='middle' align='left'><input type='text' $disabled id='percentage".$key."' name='percentage[]' class='inputbox1' maxlength='6' size='3' readonly value=".$perArr[$key]."><input type='hidden' id='previousClass".$key."' name='previousClass[]' value='".$key."'/></td>

<td valign='middle' align='left'><input type='hidden' id='previousClassId".$key."' name='previousClassId' value='".$key."'/>
	<img src='".IMG_HTTP_PATH."/add.gif' alt='Subject Details' onclick='editWindow(\"$key\",\"tableDiv\",\"divAcademic\",300,200); return false;'>
</td>

                                    </tr>";
                                }
                                echo "<input type='hidden' id='countRecord' name='countRecord' value='".$count."'/>";
                            }
                            ?>
                            </table>
			
                        </td>
                        </tr>
                        <?php
                            if($inActiveClass==''){
                        ?>
                        <tr>
                            <td  align="center" style="padding-right:5px" valign="bottom" colspan="6"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateAll(this.form);"/></td>
                        </tr>
                        <?php
                            }
                        ?>
                            <tr>
                                <td height="5"></td>
                            </tr> 			 
			                <tr>
                            <td colspan="6">
                            <?php
                              require_once(TEMPLATES_PATH. "/studentLastRegistration.php");    
                            ?>
                            </td>
                            </tr>
                        </table>
</div>
<div class="dhtmlgoodies_aTab">
                        <div id="scroll2" style="overflow:auto;HEIGHT:580px;">
                            <div id="timeTableResultDiv"></div>
                        </div>
</div>
<div class="dhtmlgoodies_aTab">
                            <table width="100%" border="0" cellspacing="1" cellpadding="1" >
                            <tr>
                               <td>
                                    <div id="gradeResultDiv12" style="overflow:auto; width:960px; height:540px"> 
                                        <div id="gradeResultDiv"></div>
                                    </div>
                               </td>
                            </tr>
                            <tr id = 'saveDiv1' style='display:none'>
                                <td colspan='1' align='right' valign="middle"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printMarksReport();return false;"/>&nbsp;<input type="image"  name="printMarksCSV" id='generateCSV' onClick="csvMarksReport();return false;" value="printMarksCSV" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" /></td>
                            </tr>
                            </table>
</div>
<div class="dhtmlgoodies_aTab">
        <div id="scroll2" >
            <table width="100%" border="0" cellspacing="1" cellpadding="1">
                    <tr class="row0">
                        <td>
                            <table border="0" cellspacing="1" cellpadding="0" align="center">
                            <tr>
                                <td valign="middle" align="left" class="contenttab_internal_rows" style="padding-right:5px;">
                                 <div id="consolidatedDiv" title="Consolidated View" style="text-decoration:underline;cursor:pointer;" onclick="toggleAttendanceDataFormat('<?php echo $REQUEST_DATA[id]?>',document.getElementById('studyPeriod').value);">
                                  <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/consolidated.gif" />
                                  </div>
                                </td>
                                <td valign="middle"><b>Show Attendance Upto </b></td>
                                <td class="contenttab_internal_rows" valign="middle"><b>:</b></td>
                                <td>    
                                <?php
                                   require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                   echo HtmlFunctions::getInstance()->datePicker('startDate2',date('Y-m-d'));
                                ?></td>
                                <td width="5"></td>
                                <td><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/showlist.gif" onClick="getAttendance(<?php echo $REQUEST_DATA['id']?>,document.getElementById('startDate2').value);return false;"/></td>
                            </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
						<td>
							<font color="red"><b><u>Please Note:</u>&nbsp;</b></font><br>
							<font color="red">1. Medical Leaves are ONLY applicable in the Consolidated View.</font><br/>
							<font color="red">2. Medical Leaves are counted in the Aggregate ONLY if (Total Attendance + Duty Leaves) lie between <?php echo $sessionHandler->getSessionVariable('MEDICAL_LEAVE_CALCULATION_LIMIT'); ?>% and <?php echo $sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');?>% </font>
						</td>
					</tr>
                    <tr>
                        <td style="padding-right:10px"><div style="overflow:auto;HEIGHT:510px" id="attendanceResultDiv"></div></td>
                    </tr>
                    <tr id = 'printDiv2' style='display:none'>
                        <td colspan='1' align='right' valign="middle">
                           <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printAttendanceReport();return false;"/>&nbsp;
                           <input type="image"  name="printAttendanceCSV" id='generateCSV' onClick="csvAttendanceReport();return false;" value="printAttendanceCSV" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" />
                        </td>
                    </tr>
                </table>
        </div>
</div>

<div class="dhtmlgoodies_aTab">
            <table width="100%" border="0" cellspacing="5" cellpadding="5">
            <tr>
                <td>
                     <div id="feesResultsDiv12" style="overflow:auto; width:960px; height:540px"> 
                            <div id="feesResultsDiv"></div>
                      </div>
                </td>
            </tr>
            </table>
</div>
<div class="dhtmlgoodies_aTab">
                <table width="100%" border="0" cellspacing="2" cellpadding="2">
                <tr>
                   <td>
                     <table border="0" cellspacing="0" cellpadding="0" align="right">
                        <tr>
                            <td><input type="text" id="searchbox" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" /></td>
                            <td width="5"></td>
                            <td>
                             <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/search.gif" align="absbottom" style="margin-right: 5px;" onClick="refreshResourceData(<?php echo $REQUEST_DATA['id']?>,document.addForm.studyPeriod.value);return false;"/>
                            </td>
                        </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td id='showResourceSearch' style='display:none' height="2px"></td>
                </tr>
                <tr>
                    <td>
                       <div id="resourceResultsDiv12" style="overflow:auto; width:960px; height:540px">
                          <div id="resourceResultsDiv"></div>
                        </div>
                    </td>
                </tr>
                <tr id='' >
                    <td colspan='1' align='right' valign="middle"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printResourceReport();return false;"/>&nbsp;<input type="image"  name="printResourceCSV" id='generateCSV' onClick="csvResourceReport();return false;" value="printResourceCSV" src="<?php echo IMG_HTTP_PATH;?>/excel.gif" /></td>
                </tr>
                </table>
</div>
<div class="dhtmlgoodies_aTab">
               <table width="100%" border="0" cellspacing="2" cellpadding="2">
               <tr>
                   <td>
                     <div id="finalResultsDiv12" style="overflow:auto; width:960px; height:540px">
                       <div id="finalResultsDiv"></div>
                      </div>
                   </td>
               </tr>
               <tr id = 'printDiv3' style='display:none'>
                <td colspan='1' align='right' valign="middle"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printFinalResultReport();return false;"/></td>
                </tr>
               </table>
</div>
<div class="dhtmlgoodies_aTab">
               <table width="100%" border="0" cellspacing="2" cellpadding="2">
               <tr>
                   <td>
                       <div id="offenceResultsDiv12" style="overflow:auto; width:960px; height:540px">
                         <div id="offenceResultsDiv"></div>
                       </div>
                   </td>
               </tr>
               </table>
</div>
<div class="dhtmlgoodies_aTab">
                           <table width="100%" border="0" cellspacing="2" cellpadding="2">
                            <tr>
                                <td class="contenttab_internal_rows"><nobr><b>Religion</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><input type="text" id="religion" name="religion" class="inputbox"   value="<?php echo $studentDataArr[0]['religion']?>" maxlength="50" <?php echo $inActiveClass?>/>
                                </td>
                                <td class="contenttab_internal_rows"><nobr><b>Hobbies</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><input type="text" id="hobbies" name="hobbies" class="inputbox" value="<?php echo $studentDataArr[0]['hobbies']?>" maxlength="60" <?php echo $inActiveClass?>/>
                                </td>
                            </tr>
                            <tr>
                                <td class="contenttab_internal_rows"><nobr><b>Lang. Read</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><input type="text" id="toRead" name="toRead" class="inputbox"   value="<?php echo $studentDataArr[0]['languageRead']?>" maxlength="30" <?php echo $inActiveClass?>/>
                                </td>
                                <td class="contenttab_internal_rows"><nobr><b>Lang. Write</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><input type="text" id="toWrite" name="toWrite" class="inputbox"   value="<?php echo $studentDataArr[0]['languageWrite']?>" maxlength="60" <?php echo $inActiveClass?>/>
                                </td>
                                <td class="contenttab_internal_rows"><nobr><b>Lang. Speak</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><input type="text" id="toSpeak" name="toSpeak" class="inputbox"   value="<?php echo $studentDataArr[0]['languageSpeak']?>" maxlength="60" <?php echo $inActiveClass?>/>
                                </td>
                            </tr>
                            <tr>
                                <td class="contenttab_internal_rows"><nobr><b>Education</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><select size="1" class="selectfield" name="education" id="education" onchange="getEducation();" <?php echo $disableClass?>>
                                    <option value="">Select</option>
                                    <option value="0" <?php if($studentDataArr[0]['education']==0) echo "SELECTED='SELECTED'";?>>Self-financed</option>
                                    <option value="1" <?php if($studentDataArr[0]['education']==1) echo "SELECTED='SELECTED'";?>>Educational Loan</option>
                                </select>
                                </td>
                                <td class="contenttab_internal_rows"><nobr><b>Bank Name & Address</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><input type="text" id="bankName" name="bankName" class="inputbox" maxlength="250" value="<?php if($studentDataArr[0]['education']==1) {
                                    echo $studentDataArr[0]['bankNameAddress'];
                                }
                                else {?>"
                                    disabled="disabled"
                                "<?php }?>"/></td>
                                <td class="contenttab_internal_rows"><nobr><b>Loan Amount</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><input type="text" id="loanAmount" name="loanAmount" class="inputbox" maxlength="7" value="<?php if($studentDataArr[0]['education']==1) {
                                    echo $studentDataArr[0]['loanAmount'];
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
                                    <option value="1" <?php if($studentAilmentDataArr[0]['ailment']==1) echo "SELECTED='SELECTED'";?>>Yes</option>
                                    <option value="0" <?php if($studentAilmentDataArr[0]['ailment']==0) echo "SELECTED='SELECTED'";?>>No</option>
                                </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="contenttab_internal_rows"><nobr><b>Nature of Ailment</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><input type="text" id="natureAilment" name="natureAilment" class="inputbox" maxlength="20" value="<?php if($studentAilmentDataArr[0]['ailment']==1) {
                                    echo $studentAilmentDataArr[0]['otherAilment'];
                                } else {?>"
                                    disabled = "disabled"
                                "<?php }?>"/>
                                </td>
                                <td class="contenttab_internal_rows"><nobr><b>Family Ailment</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top">
                                <?php if($studentAilmentDataArr[0]['ailment']==1) { ?>
                                    <select multiple size="3" class="selectfield" name="familyAilment[]" id="familyAilment" disabled="disabled" <?php echo $disableClass?>>
                                    <?php
                                      require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                      echo HtmlFunctions::getInstance()->getFamilyAilment($studentAilmentDataArr[0]['familyAilment']);
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
                                <td class="padding_top"><input type="text" id="otherAilment" name="otherAilment" class="inputbox" maxlength="20" value="<?php if($studentAilmentDataArr[0]['ailment']==1) {
                                    echo $studentAilmentDataArr[0]['familyOtherAilment'];
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
                                      echo HtmlFunctions::getInstance()->getCoachingCentersData($studentDataArr[0]['coachingName']);
                                    ?>
                                    </select>
                                </td>
                                <td class="contenttab_internal_rows"><nobr><b>Branch Manager Name</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><input type="text" id="coachingManager" name="coachingManager" class="inputbox" maxlength="50" value="<?php if($studentDataArr[0]['coachingName']!=0) {
                                    echo $studentDataArr[0]['coachingManagerName'];
                                } else {?>"
                                    disabled = "disabled"
                                "<?php }?>"/>
                                </td>
                                <td class="contenttab_internal_rows"><nobr><b>Address & Phone No.</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><input type="text" id="address" name="address" class="inputbox" maxlength="250" value="<?php if($studentDataArr[0]['coachingName']==1) {
                                    echo $studentDataArr[0]['coachingAddress'];
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
                                    <option value="1" <?php if($studentDataArr[0]['workExperience']==1) echo "SELECTED='SELECTED'";?>>Yes</option>
                                    <option value="0" <?php if($studentDataArr[0]['workExperience']==0) echo "SELECTED='SELECTED'";?>>No</option>
                                </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="contenttab_internal_rows"><nobr><b>Area/Department</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><input type="text" id="department" name="department" class="inputbox" maxlength="100" value="<?php if($studentDataArr[0]['workExperience']==1) {
                                    echo $studentDataArr[0]['departmentName'];
                                } else {?>"
                                    disabled = "disabled"
                                "<?php }?>"/>
                                </td>
                                <td class="contenttab_internal_rows"><nobr><b>Organization</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><input type="text" id="organization" name="organization" class="inputbox" maxlength="100" value="<?php if($studentDataArr[0]['workExperience']==1) {
                                    echo $studentDataArr[0]['organization'];
                                } else {?>"
                                    disabled="disabled"
                                "<?php }?>"/>
                                </td>
                                <td class="contenttab_internal_rows"><nobr><b>Place</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top"><input type="text" id="place" name="place" class="inputbox" maxlength="100" value="<?php if($studentDataArr[0]['workExperience']==1) {
                                    echo $studentDataArr[0]['place'];
                                } else {?>"
                                    disabled="disabled"
                                "<?php }?>"/>
                                </td>
                            </tr>

                            <tr>
                                <td height="5"></td>
                            </tr>

                            <?php
                                if($inActiveClass==''){
                            ?>
                            <tr>
                                <td  align="center" style="padding-right:5px" valign="bottom" colspan="9"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif"  onClick="return validateAll(this.form);"/></td>
                            </tr>
                            <?php
                                }
                            ?>
                           </table>
</div>
<div class="dhtmlgoodies_aTab" >
        <form name='messageFrm' id='messageFrm' onsubmit="return false;" method="post">
            <table width="100%"  border="0" cellspacing="1px" cellpadding="1px">
            <tr>
                <td class="contenttab_internal_rows" width="40%">
                    <table width="100%" nowrap="left" cellspacing="0px" cellpadding="0px">
                        <tr>
                            <td><nobr><strong>&nbsp;&nbsp;Message Medium&nbsp;:</strong></nobr></td>
                            <td class="contenttab_internal_rows"><nobr>
                                <select size="1" class="selectfield" name="MessageMedium1" id="MessageMedium1" style="width:100px" onchange="refreshMessageMedium2(<?php echo $_REQUEST['id']; ?>);return false;">
                                    <option value="All" name="All">All</option>
                                    <option value="SMS" name="SMS">SMS</option>
                                    <option value="Email" name="Email">Email</option>
                                    <option value="Dashboard" name="Dashboard">Dashboard</option>
                                </select>
                           </td>
                           <td class="contenttab_internal_rows" nowrap align="right"><strong>&nbsp;&nbsp;Sent By&nbsp;:</strong></td>
                            <td class="contenttab_internal_rows"><nobr>
                             <select size="1" name="roleType" id="roleType" class="htmlElement" onChange="refreshMessageMedium2(<?php echo $_REQUEST['id']; ?>);return false;">
                               <option value="0" selected="selected">All</option>
                                <?php
                                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                    echo HtmlFunctions::getInstance()->getRoleNameReceived();
                                ?>
                                </select></nobr>
                            </td>
                         </tr>       
                     </table>
                </td>
                <td class=""  width="60%"  align="right" ><nobr> 
                    <input type="text" id="messagebox1" name="messagebox1" class="inputbox" value="<?php echo $REQUEST_DATA['messagebox1'];?>" style="margin-bottom: 2px;" size="30" />
                    <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/search.gif" align="absbottom" style="margin-right: 5px;" onClick="refreshMessageMedium2(<?php echo $REQUEST_DATA['id']?>);return false;"/>
                </td>
            </tr>
            <tr>
                 <td id='showMessageSearch' style='display:none' height="2px"></td>
            </tr>
        </table>
        <br/><br />
        <table width="100%" border="0" cellspacing="2px" cellpadding="2px">
            <tr>
                <td>
                    <div id="messageCorrespondenceResultDiv12" style="overflow:auto; width:960px; height:520px">
                       <div id="messageCorrespondenceResultDiv"></div>
                    </div>
                </td>
            </tr>     
        </table>
        </form>    
        </div>
        
        <div class="dhtmlgoodies_aTab" style="vertical-align:top;">
            <div style="overflow:auto; width:100%; height:550px; vertical-align:top;">
              <table width="100%" border="0" cellspacing="5px" cellpadding="2px">
                <tr>
                   <td><div id="finalGradesDiv"></div></td>
                </tr>
              </table>
            </div>
        </div>
         <!--mentor comments-->
                 <div class="dhtmlgoodies_aTab" style="vertical-align:top;">
                    <div style="overflow:auto; width:100%; height:550px; vertical-align:top;">
                        <div id="mentorCommentsDiv"></div>
                    </div>
                 </div>   
        
</div>

<script type="text/javascript">
    initTabs('dhtmlgoodies_tabView1',
    Array('Personal Info','Parents Info','Sibling','Course','Administrative','Schedule','Marks','Attendance','Fees','Resource','FinalResult','Offense','Misc Info','Msg Correspondence','Grade Info','Mentor Comments'),0,1020,620,
    Array(false,false,false,false,false,false,false,false,false,false,false,false,false,false,false));
</script>


        </td>
        </tr>
        </table>



<?php floatingDiv_Start('divMessage','Brief Description','',''); ?>
<form name="MessageForm" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
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

<?php floatingDiv_Start('divDutyLeave','Duty Leaves','',''); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
    <tr>
        <td width="89%" style="padding-left:5px">
            <div id="scrolLeave1" style="overflow:auto; width:550px; height:300px; vertical-align:top;">
                <div id="div_dutyLeave" style="width:98%; vertical-align:top;"></div>
            </div>
        </td>
    </tr>
</table>
<?php floatingDiv_End(); ?>

<?php floatingDiv_Start('divMedicalLeave','Medical Leaves','',''); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td height="5px"></td></tr>
    <tr>
        <td width="89%" style="padding-left:5px">
            <div id="scrolLeave211" style="overflow:auto; width:550px; height:300px; vertical-align:top;">
                <div id="div_medicalLeave" style="width:98%; vertical-align:top;"></div>
            </div>
        </td>
    </tr>
</table>
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
<?php floatingDiv_End(); ?>





	<?php 
   global $sessionHandler;
   $roleId = $sessionHandler->getSessionVariable('RoleId');    
  
   if($roleId!=1) {         
    
    
     $studentManager = StudentManager::getInstance();

     $blockedTab = $studentManager->getBlockedTab("Student Details");
    
     
        for($i=0;$i<count($blockedTab);$i++){
        ?>
                         <script type="text/javascript">
            deleteTab("<?php echo $blockedTab[$i]['frameName1']; ?>");                       
            </script>
            <?php    
            }
                 }
             ?>
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
                             
                             if ($sessionHandler->getSessionVariable('SIBLING')==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_2').style.display='none'; </script>"; 
                             }
                             else  if($show==-1) {
                                $show=2;
                              }
                               
                              if ($sessionHandler->getSessionVariable('COURSE')==0)  {
                                echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_3').style.display='none'; </script>"; 
                              }
                              else  if($show==-1) {
                                $show=3;
                              }
                              
                              if ($sessionHandler->getSessionVariable('ADMINISTRATIVE')==0)  {
                                echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_4').style.display='none'; </script>"; 
                              }
                              else  if($show==-1) {
                                $show=4;
                              }
                              
                              if ($sessionHandler->getSessionVariable('SCHEDULE')==0)  {
                                echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_5').style.display='none'; </script>"; 
                              }    
                              else  if($show==-1) {
                                $show=5;
                              }
                              
                              if ($sessionHandler->getSessionVariable('MARKS')==0)  {
                                echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_6').style.display='none'; </script>"; 
                              } 
                              else  if($show==-1) {
                                $show=6;
                              }
                                 
                              if ($sessionHandler->getSessionVariable('ATTENDANCE')==0)  {
                                echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_7').style.display='none'; </script>"; 
                              }    
                              else  if($show==-1) {
                                $show=7;
                              }
                              
                              if ($sessionHandler->getSessionVariable('FEES')==0)  {
                                echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_8').style.display='none'; </script>"; 
                              }  
                              else  if($show==-1) {
                                $show=8;
                              }
                              
                              if ($sessionHandler->getSessionVariable('RESOURCE')==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_9').style.display='none'; </script>"; 
                             }  
                             else  if($show==-1) {
                                $show=9;
                             }
                              
                             if ($sessionHandler->getSessionVariable('FINAL_RESULT')==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_10').style.display='none'; </script>"; 
                             }  
                             else  if($show==-1) {
                                $show=10;
                             }
                              
                              if ($sessionHandler->getSessionVariable('OFFENSE')==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_11').style.display='none'; </script>"; 
                             } 
                             else  if($show==-1) {
                                $show=11;
                             }
                             
                             if ($sessionHandler->getSessionVariable('MISC_INFO')==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_12').style.display='none'; </script>"; 
                             } 
                             else  if($show==-1) {
                                $show=12;
                             }
                             
                             if ($sessionHandler->getSessionVariable('MESSAGE_CORRESPONDENCE')==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_13').style.display='none'; </script>"; 
                             } 
                             else  if($show==-1) {
                                $show=13;
                             }
                             
                             if ($sessionHandler->getSessionVariable('GRADE_INFO')==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_14').style.display='none'; </script>"; 
                             } 
                             else  if($show==-1) {
                                $show=14;
                             }
							$mentorComments=1;
							 if ($mentorComments==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_15').style.display='none'; </script>"; 
                             } 
                             else  if($show==-1) {
                                $show=15;
                             }
                             
                       ?>
                       <input type="hidden" name="showTabView" id="showTabView" value="<?php echo $show; ?>">
             
        </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
 </form>
 <?php
// $History: studentDetailContents.php $
//
//*****************  Version 31  *****************
//User: Parveen      Date: 1/28/10    Time: 3:14p
//Updated in $/LeapCC/Templates/Student
//Bus Route No., Bus Stop Read only
//
//*****************  Version 30  *****************
//User: Rajeev       Date: 09-10-24   Time: 2:02p
//Updated in $/LeapCC/Templates/Student
//fixed bug no 0001821,0001880,0001816,0001852,0001851,0001637,0001329
//
//*****************  Version 29  *****************
//User: Jaineesh     Date: 10/23/09   Time: 6:32p
//Updated in $/LeapCC/Templates/Student
//fixed bug nos. 0001871,0001869,0001853,0001873,0001820,0001809,0001808,
//0001805,0001806, 0001876, 0001879, 0001878
//
//*****************  Version 28  *****************
//User: Dipanjan     Date: 6/10/09    Time: 17:00
//Updated in $/LeapCC/Templates/Student
//Added Detailed(group wise) and Consolidated view(irrespective of groups
//of a subject) of attendance in admin section
//
//*****************  Version 27  *****************
//User: Rajeev       Date: 09-10-01   Time: 10:23a
//Updated in $/LeapCC/Templates/Student
//- Updated administrative tab with hostel details
//
//*****************  Version 26  *****************
//User: Rajeev       Date: 7/29/09    Time: 10:21a
//Updated in $/LeapCC/Templates/Student
//Updated with quartine student registration number increment
//
//*****************  Version 25  *****************
//User: Rajeev       Date: 7/20/09    Time: 4:02p
//Updated in $/LeapCC/Templates/Student
//Fixed bugs and enhancements 0000616-0000620
//
//*****************  Version 24  *****************
//User: Rajeev       Date: 7/16/09    Time: 10:53a
//Updated in $/LeapCC/Templates/Student
//Updated student detail module formatting when session is changed from
//top
//
//*****************  Version 23  *****************
//User: Rajeev       Date: 7/15/09    Time: 1:29p
//Updated in $/LeapCC/Templates/Student
//Updated code with transaction in admit student if there is an error in
//query
//
//*****************  Version 22  *****************
//User: Rajeev       Date: 7/13/09    Time: 1:12p
//Updated in $/LeapCC/Templates/Student
//changed Institute Reg No maxlength to 60
//
//*****************  Version 21  *****************
//User: Rajeev       Date: 7/13/09    Time: 12:25p
//Updated in $/LeapCC/Templates/Student
//Removed Last name validation check as per Sachin sir dated 13thjuly
//
//*****************  Version 20   *****************
//User: Rajeev       Date: 7/11/09    Time: 11:01a
//Updated in $/LeapCC/Templates/Student


//made enhancement to exchange max marks and marks obtained field and
//validations
//
//*****************  Version 19  *****************
//User: Rajeev       Date: 6/24/09    Time: 1:36p
//Updated in $/LeapCC/Templates/Student
//0000188: Find Student (Admin-CC) > Data is not displaying in correct
//order on �student list report print� window
//
//0000183: Find Student - Admin > Search is not working properly in IE
//browser
//
//*****************  Version 18  *****************
//User: Rajeev       Date: 6/23/09    Time: 1:16p
//Updated in $/LeapCC/Templates/Student
//updated with back button image
//
//*****************  Version 17  *****************
//User: Rajeev       Date: 6/22/09    Time: 4:34p
//Updated in $/LeapCC/Templates/Student
//0000162. Find Student-Admin > Remove �info� from each tab except
//�Personal and Parent Info� tab.
//
//*****************  Version 16  *****************
//User: Dipanjan     Date: 17/06/09   Time: 14:18
//Updated in $/LeapCC/Templates/Student
//Modifed look and feel as mailed by kabir sir.
//
//*****************  Version 15  *****************
//User: Dipanjan     Date: 16/06/09   Time: 18:25
//Updated in $/LeapCC/Templates/Student
//Added education stream text box in admininstrative info
//
//*****************  Version 14  *****************
//User: Rajeev       Date: 6/15/09    Time: 7:22p
//Updated in $/LeapCC/Templates/Student
//Enhanced "Admin Student" module as mailed by Puspender Sir.
//
//*****************  Version 13  *****************
//User: Rajeev       Date: 6/02/09    Time: 11:39a
//Updated in $/LeapCC/Templates/Student
//Fixed bugs  1104-1110  and enhanced with student previous academics
//
//*****************  Version 12  *****************
//User: Rajeev       Date: 5/28/09    Time: 4:10p
//Updated in $/LeapCC/Templates/Student
//Updated with student academic data in student tab
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 5/28/09    Time: 3:30p
//Updated in $/LeapCC/Templates/Student
//Added blood group, reference name, sports activity, student previous
//academic, in print report as well as find student tab
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 4/09/09    Time: 3:19p
//Updated in $/LeapCC/Templates/Student
//added print reports
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 3/24/09    Time: 7:01p
//Updated in $/LeapCC/Templates/Student
//Updated formatting
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 3/09/09    Time: 10:54a
//Updated in $/LeapCC/Templates/Student
//Increased the student contact info maxlength to 40
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 2/26/09    Time: 4:18p
//Updated in $/LeapCC/Templates/Student
//Implemented address verification
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 2/24/09    Time: 6:16p
//Updated in $/LeapCC/Templates/Student
//Updated student tab so that only tab based file is called
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 1/22/09    Time: 2:35p
//Updated in $/LeapCC/Templates/Student
//updated with left align
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 1/12/09    Time: 5:30p
//Updated in $/LeapCC/Templates/Student
//Updated with Required field, centralized message, left align
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 12/22/08   Time: 5:52p
//Updated in $/LeapCC/Templates/Student
//added Offense tab
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/10/08   Time: 10:22a
//Updated in $/LeapCC/Templates/Student
//Updated As per CC
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p

//Created in $/LeapCC/Templates/Student
//
//*****************  Version 33  *****************
//User: Rajeev       Date: 9/17/08    Time: 2:47p
//Updated in $/Leap/Source/Templates/Student
//updated with asterix on mandatory fields
//
//*****************  Version 32  *****************
//User: Rajeev       Date: 9/17/08    Time: 12:01p
//Updated in $/Leap/Source/Templates/Student
//updated back button with class
//
//*****************  Version 31  *****************
//User: Rajeev       Date: 9/17/08    Time: 10:48a
//Updated in $/Leap/Source/Templates/Student
//updated as respect to subject centric
//
//*****************  Version 30  *****************
//User: Rajeev       Date: 9/16/08    Time: 6:53p
//Updated in $/Leap/Source/Templates/Student
//updated cellsapcing in attendance
//
//*****************  Version 29  *****************
//User: Rajeev       Date: 9/16/08    Time: 4:55p
//Updated in $/Leap/Source/Templates/Student
//updated files according to subject centric
//
//*****************  Version 28  *****************
//User: Rajeev       Date: 9/13/08    Time: 12:09p
//Updated in $/Leap/Source/Templates/Student
//updated mobile no max length to 15
//
//*****************  Version 27  *****************
//User: Rajeev       Date: 9/08/08    Time: 5:13p
//Updated in $/Leap/Source/Templates/Student
//updated group query
//
//*****************  Version 25  *****************
//User: Rajeev       Date: 9/08/08    Time: 12:31p
//Updated in $/Leap/Source/Templates/Student
//updated the file with division by zero issue
//
//*****************  Version 24  *****************
//User: Rajeev       Date: 9/04/08    Time: 7:01p
//Updated in $/Leap/Source/Templates/Student
//updated maxlength on html controls
//
//*****************  Version 23  *****************
//User: Rajeev       Date: 9/03/08    Time: 3:10p
//Updated in $/Leap/Source/Templates/Student
//updated formatting and spacing
//
//*****************  Version 22  *****************
//User: Rajeev       Date: 9/01/08    Time: 12:08p
//Updated in $/Leap/Source/Templates/Student
//updated with default list of attendance details under attendance tab as
//said by Sachin sir
//
//*****************  Version 21  *****************
//User: Rajeev       Date: 8/28/08    Time: 11:54a
//Updated in $/Leap/Source/Templates/Student
//updated with class name on top of student detail instead of showing
//university, degree, branch, batch seprately
//
//*****************  Version 20  *****************
//User: Rajeev       Date: 8/28/08    Time: 11:34a
//Updated in $/Leap/Source/Templates/Student
//updated date format
//
//*****************  Version 19  *****************
//User: Rajeev       Date: 8/25/08    Time: 5:31p
//Updated in $/Leap/Source/Templates/Student
//updated student detail functions
//
//*****************  Version 17  *****************
//User: Rajeev       Date: 8/21/08    Time: 2:03p
//Updated in $/Leap/Source/Templates/Student
//updated formatting and print reports
//

//*****************  Version 16  *****************
//User: Rajeev       Date: 8/16/08    Time: 2:54p
//Updated in $/Leap/Source/Templates/Student
//updated file for print report
//
//*****************  Version 15  *****************
//User: Rajeev       Date: 8/14/08    Time: 7:28p
//Updated in $/Leap/Source/Templates/Student
//updated print functions
//
//*****************  Version 14  *****************
//User: Rajeev       Date: 8/07/08    Time: 3:04p
//Updated in $/Leap/Source/Templates/Student
//checked the formatting
//
//*****************  Version 13  *****************
//User: Rajeev       Date: 8/06/08    Time: 3:51p
//Updated in $/Leap/Source/Templates/Student
//updated query and formatting
//
//*****************  Version 12  *****************
//User: Rajeev       Date: 8/05/08    Time: 6:29p
//Updated in $/Leap/Source/Templates/Student
//remove all the demo issues
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 8/02/08    Time: 6:22p
//Updated in $/Leap/Source/Templates/Student
//updated time table format
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 8/01/08    Time: 4:03p
//Updated in $/Leap/Source/Templates/Student
//updated attendance function
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 7/31/08    Time: 4:32p
//Updated in $/Leap/Source/Templates/Student
//updated the format of file
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 7/24/08    Time: 6:38p
//Updated in $/Leap/Source/Templates/Student
//updated the validations
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





