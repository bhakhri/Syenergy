<?php
//-------------------------------------------------------
// Purpose: To design the layout for Student Information.
//
// Author : Parveen Sharma
// Created on : 10.12.2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
  $queryString =  $_SERVER['QUERY_STRING'];
  
  echo $queryString;
?>
 <?php
  require_once(BL_PATH.'/helpMessage.inc.php');
?>
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top">
             <?php require_once(TEMPLATES_PATH . "/breadCrumb.php");        ?> 
        </td>
        </tr>
        </table> 
 <form method="POST" name="addForm" id="addForm"  action="<?php echo HTTP_LIB_PATH;?>/Parent/fileUpload.php" fmethod="post" enctype="multipart/form-data" style="display:inline" onsubmit="return false;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>                                                                       
        <td valign="top">
            <table border="0"cellspacing="0" cellpadding="0" width="100%">
           <!-- <tr>
                <td height="10"></td>
            </tr> -->
            <tr>
               <!-- <td valign="top">Parent Activities&nbsp;&raquo;&nbsp;Student Info</td> -->
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0" height="625">
             <tr>
                <td class="contenttab_border" height="20">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="contenttab_row1"><span class="content_title">Student Detail:</span>
                            &nbsp;<B><U>Name:</U></B>&nbsp;<?php echo $studentDataArr[0]['firstName']." ".$studentDataArr[0]['lastName'];?>
                            &nbsp;&nbsp;<B><U>Class:</U></B>&nbsp;<?php echo $studentClassArr[0]['className'];?>
                            &nbsp;&nbsp;<B><U>Admission Date:</U></B>&nbsp;
                            <?php echo (UtilityManager::formatDate($studentDataArr[0]['dateOfAdmission']));?>&nbsp;&nbsp;<span class="content_title">Study Period:</span>
                            <select size="1" class="selectfield" name="studyPeriod" id="studyPeriod" style="width:100px" onchange="refreshStudentData('<?php echo $studentDataArr[0]['studentId']?>',this.value,tabNumber);"> 
                            <option value="0">All</option>
                            <?php
                             require_once(BL_PATH.'/HtmlFunctions.inc.php');
                              echo HtmlFunctions::getInstance()->getStudyPeriodName($studentDataArr[0]['studentId'],$studentDataArr[0]['classId']);    
                            ?>
                            </select>
                        </td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" ><div id="dhtmlgoodies_tabView1">
                 <div class="dhtmlgoodies_aTab" style="overflow:auto;HEIGHT:510px; vertical-align:top;">
                 <table width="100%" border="0" cellspacing="0" cellpadding="2px"  >
                <tr>
                    <td height="5"></td>
                </tr>
                <tr>    
                    <td class="contenttab_internal_rows"><nobr><b>First Name</b></nobr></td>
                    <td class="contenttab_internal_rows"><b>:</b></td>
                    <td class="padding_top" width='450'><?php echo parseOutput($studentDataArr[0]['firstName']) ?>
                    </td>
                    <td class="contenttab_internal_rows" width='150'><nobr><b>Last Name </b></nobr></td>
                    <td class="contenttab_internal_rows"><b>:</b></td>
                    <td class="padding_top" width='450'><?php echo parseOutput($studentDataArr[0]['lastName'])?>
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
                            <td class="contenttab_internal_rows"><nobr><b>Roll No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['rollNo'])?>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Institute Reg. No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['regNo'])?>
                            </td>
                        </tr>
                        <tr>    
                            <td class="contenttab_internal_rows"><nobr><b>University No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['universityRollNo'])?>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Univ. Reg. No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                                <?php echo parseOutput($studentDataArr[0]['universityRegNo']) ?>
                            </td>
                        </tr>
                        <tr>    
                            <td class="contenttab_internal_rows"><nobr><b>Date of Birth</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top" nowrap> 
                               <?php
                                    if($studentDataArr[0]['dateOfBirth']=="0000-00-00") {
                                      echo NOT_APPLICABLE_STRING;      
                                    }
                                    else {
                                      echo UtilityManager::formatDate($studentDataArr[0]['dateOfBirth']);
                                    } 
                               ?>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Gender</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                                <?php if($studentDataArr[0]['studentGender']=="M") {
                                         echo parseOutput("Male"); 
                                      }
                                      else   
                                      if($studentDataArr[0]['studentGender']=="F") {
                                        echo parseOutput("Female");
                                      }
                                      else {
                                        echo NOT_APPLICABLE_STRING;
                                      }
                                ?>
                            </td>
                        </tr>
                        <tr>    
                            <td class="contenttab_internal_rows"><nobr><b>Email</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['studentEmail'])?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Nationality</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php
                              require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
                              $results2 = CommonQueryManager::getInstance()->getCountries('countryName',"WHERE countryId=".parseInput($studentDataArr[0]['nationalityId'])); 
                              echo parseOutput($results2[0]['countryName']); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Contact No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['studentPhone'])?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Mobile No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['studentMobileNo'])?></td>
                        </tr>
                            <td class="contenttab_internal_rows"><nobr><b>Domicile</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <?php
                              require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
                              $results1 = CommonQueryManager::getInstance()->getStates('stateName',"WHERE stateId=".parseInput($studentDataArr[0]['domicileId'])); 
                              echo parseOutput($results1[0]['stateName']);
                             ?>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Category</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <?php
                                require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
                                $results3 = CommonQueryManager::getInstance()->getQuota('quotaName',"WHERE quotaId=".parseInput($studentDataArr[0]['quotaId']));
                                echo parseOutput($results3[0]['quotaName']);
                             ?>
                             </td>
                        </tr>
                        <tr>    
                            <td class="contenttab_internal_rows"><nobr><b>User Name</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentUserDataArr[0]['userName'])?> 
                            </td>
                            <?php
                            /*<td class="contenttab_internal_rows"><nobr><b>Password</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"> password </td>
                            */
                            ?>
                        </tr>
                        <tr>    
                            <td class="contenttab_internal_rows"><nobr><b>Exam</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <?php
                                echo parseOutput($results[$studentDataArr[0]['compExamBy']]);
                            ?>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Rank</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['compExamRank'])?></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Ent. Exam. Roll No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['compExamRollNo'])?></td>
                        </tr>
                        
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Fee Receipt No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="contenttab_internal_rows"><?php echo parseOutput($studentDataArr[0]['feeReceiptNo']) ?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Sports Activity</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td><?php echo parseOutput($studentDataArr[0]['studentSportsActivity'])?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Blood Group</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="contenttab_internal_rows">
                                <?php
                                  global $bloodResults;
                                  echo parseOutput($bloodResults[$studentDataArr[0]['studentBloodGroup']]);
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Student Status</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="contenttab_internal_rows"><?php if($studentDataArr[0]['studentStatus']) {
                                        echo "Active"; 
                                      }
                                      else {
                                        echo "Unactive";  
                                      }
                                ?>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Is LEET</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="contenttab_internal_rows"><?php if($studentDataArr[0]['isLeet']) echo "Yes"; else echo NOT_APPLICABLE_STRING; ?>
                            </td>
							<td class="contenttab_internal_rows"><nobr><b>Role</b></nobr></td>
							<td class="contenttab_internal_rows"><b>:</b></td>
							<td><?php echo parseOutput($studentDataArr[0]['role'])?>
							</td>
                        </tr>
                        <tr>
                            <td height="5"></td>
                        </tr>
						<tr>
							<!--<td class="contenttab_internal_rows"><nobr><b>Batch</b></nobr></td>
							<td class="contenttab_internal_rows"><b>:</b></td>
							<td class="padding_top"><input type="text" id="batch" name="batch" class="inputbox"  value="" maxlength="30" />
							</td>-->
							<td class="contenttab_internal_rows"><nobr><b>Current Organization</b></nobr></td>
							<td class="contenttab_internal_rows"><b>:</b></td>
							<td><?php echo parseOutput($studentDataArr[0]['currentOrg'])?>
							</td>
							<td class="contenttab_internal_rows"><nobr><b>Designation</b></nobr></td>
							<td class="contenttab_internal_rows"><b>:</b></td>
							<td><?php echo parseOutput($studentDataArr[0]['companyDesignation'])?>
							</td>
							</tr>
							<tr>
							<td class="contenttab_internal_rows"><nobr><b>Work Email</b></nobr></td>
							<td class="contenttab_internal_rows"><b>:</b></td>
							<td><?php echo parseOutput($studentDataArr[0]['workEmail'])?>
							</td>
							 <td class="contenttab_internal_rows"><nobr><b>Office Phone No</b></nobr></td>
							<td class="contenttab_internal_rows"><b>:</b></td>
							<td><?php echo parseOutput($studentDataArr[0]['officeContactNo'])?>
							</td>
                         <tr>
                            <td height="5" colspan="4"><B><U>Correspondence Address</U></B></td>
                        </tr>
                         <tr>
                            <td height="5"></td>
                        </tr>
                        <tr>    
                            <td class="contenttab_internal_rows" ><nobr><b>Address1</b></nobr></td>
                            <td class="contenttab_internal_rows" ><b>:</b></td>
                            <td class="padding_top"><?php echo $studentDataArr[0]['corrAddress1']?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Address2</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo $studentDataArr[0]['corrAddress2']?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Pin Code</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo $studentDataArr[0]['corrPinCode']?></td>
                        </tr>
                        <tr>    
                            <td class="contenttab_internal_rows"><nobr><b>Country</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"> 
                            <?php
                              require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
                              $results5 = CommonQueryManager::getInstance()->getCountries('countryName',"WHERE countryId=".parseInput($studentDataArr[0]['corrCountryId']));             
                              echo parseOutput($results5[0]['countryName']);
                            ?>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>State</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <?php
                              require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
                              $results6 = CommonQueryManager::getInstance()->getStates('stateName',"WHERE stateId=".parseInput($studentDataArr[0]['corrStateId']));              
                              echo parseOutput($results6[0]['stateName']);
                            ?>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>City</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <?php
                              require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
                              $results7 = CommonQueryManager::getInstance()->getCity('cityName',"WHERE cityId=".parseInput($studentDataArr[0]['corrCityId']));              
                              echo parseOutput($results7[0]['cityName']);
                            ?>
                            </td>
                        </tr>
                        <tr>    
                            <td class="contenttab_internal_rows"><nobr><b>Contact No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['corrPhone']); ?>
                            </td>
                           <?php 
                             /* <td class="contenttab_internal_rows"><nobr><b>Address Verified</b></nobr></td>
                                <td class="contenttab_internal_rows"><b>:</b></td>
                                <td class="padding_top">
                                    <?php if($studentDataArr[0]['correspondenceAddressVerified']) echo "Yes"; else "No"; ?>
                                </td> */ 
                           ?> 
                        </tr>
                         <tr>
                            <td height="5"></td>
                        </tr>
                         <tr>
                            <td height="5" colspan="4"><B><U>Permanent Address</U></B></td>
                        </tr>
                         <tr>
                            <td height="5"></td>
                        </tr>
                        <tr>    
                            <td class="contenttab_internal_rows"><nobr><b>Address1</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['permAddress1']) ?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Address2</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['permAddress2'])?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Pin Code</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['permPinCode']) ?></td>
                        </tr>
                        <tr>    
                            <td class="contenttab_internal_rows"><nobr><b>Country</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"> 
                            <?php
                              require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
                              $results8 = CommonQueryManager::getInstance()->getCountries('countryName',"WHERE countryId=".parseInput($studentDataArr[0]['permCountryId']));             
                              echo parseOutput($results8[0]['countryName']);
                            ?>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>State</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <?php
                              require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
                              $results9 = CommonQueryManager::getInstance()->getStates('stateName',"WHERE stateId=".parseInput($studentDataArr[0]['permStateId']));              
                              echo parseOutput($results9[0]['stateName']);
                            ?>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>City</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <?php
                              require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
                              $results10 = CommonQueryManager::getInstance()->getCity('cityName',"WHERE cityId=".parseInput($studentDataArr[0]['permCityId']));              




                              echo parseOutput($results10[0]['cityName']);
                            ?>
                            </td>
                        </tr>
                        <tr>    
                            <td class="contenttab_internal_rows"><nobr><b>Contact No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['permPhone']); ?></td>
                          <?php  
                           /* <td class="contenttab_internal_rows"><nobr><b>Address Verified</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><input type="checkbox" id="correspondeceVerified" name="correspondeceVerified" value="1" <?php if($studentDataArr[0]['correspondenceAddressVerified']) echo "CHECKED";?> <?php echo $disableClass?>/>
                            </td> */
                          ?>  
                        </tr>
						  <tr>
                            <td height="5"></td>
                        </tr>
						<tr> 
                            <td height="5" colspan="10"><B><U>Present Address</U></B></td>
                        </tr>
                         <tr > 
                            <td height="5"></td>
                        </tr>
                         <tr> 
                            <td class="contenttab_internal_rows"><nobr><b>Address1</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['presentAddress1']); ?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Address2</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['presentAddress2']); ?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Pincode</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['presentPinCode']); ?></td>
                        </tr>
                         <tr  > 
                           <td class="contenttab_internal_rows"><nobr><b>Country</b></nobr></td>
							<td class="contenttab_internal_rows"><b>:</b></td>
							<td class="padding_top">
							 <?php
                              require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
                              $results8 = CommonQueryManager::getInstance()->getCountries('countryName',"WHERE countryId=".parseInput($studentDataArr[0]['presentCountryId']));             
                              echo parseOutput($results8[0]['countryName']);
                            ?>
							</td>
							<td class="contenttab_internal_rows"><nobr><b>State</b></nobr></td>
							<td class="contenttab_internal_rows"><b>:</b></td>
							<td class="padding_top"> 
							<?php
                              require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
                              $results9 = CommonQueryManager::getInstance()->getStates('stateName',"WHERE stateId=".parseInput($studentDataArr[0]['presentStateId']));              
                              echo parseOutput($results9[0]['stateName']);
                            ?>
							</td>
							<td class="contenttab_internal_rows"><nobr><b>City</b></nobr></td>
							<td class="contenttab_internal_rows"><b>:</b></td>
							<td class="padding_top">
							<?php
                              require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
                              $results10 = CommonQueryManager::getInstance()->getCity('cityName',"WHERE cityId=".parseInput($studentDataArr[0]['presentCityId']));              
                              echo parseOutput($results10[0]['cityName']);
                            ?>
							</td>
                        </tr>
						  <tr>
                            <td height="5"></td>
                        </tr>
						<tr> 
                            <td height="5" colspan="10"><B><U>Spouse / Emergency Contact Details</U></B></td>
                        </tr>
                         <tr > 
                            <td height="5"></td>
                        </tr>
                         <tr > 
                            <td class="contenttab_internal_rows"><nobr><b>Name</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td><?php echo parseOutput($studentDataArr[0]['spouseName']); ?> </td>
                            <td class="contenttab_internal_rows"><nobr><b>Relationship</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td ><?php echo parseOutput($studentDataArr[0]['spouseRelation']); ?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Mobile Phone Number</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td><?php echo parseOutput($studentDataArr[0]['spousePhone']); ?> </td>
                        </tr>
                         <tr  > 
                            <td class="contenttab_internal_rows"><nobr><b>Email</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td> <?php echo parseOutput($studentDataArr[0]['spouseEmail']); ?> </td>
                        </tr>
                        <tr>
                            <td height="8px"></td>
                        </tr>
                        <tr>    
                            <td class="contenttab_internal_rows" width="150"><nobr><b>Student Remarks</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top" colspan="8"><?php echo parseOutput($studentDataArr[0]['studentRemarks']); ?></td>
                        </tr>
                         <tr>
                            <td class="contenttab_internal_rows" valign="top"><nobr><b>Reference No./Name</b></nobr></td>
                            <td class="contenttab_internal_rows" valign="top"><b>:</b></td>
                            <td class="padding_top" valign="top"><?php echo parseOutput($studentDataArr[0]['referenceName']); ?></td>
                        </tr>
                        </tr>
						  <tr>
                            <td height="5"></td>
                        </tr>
			<?php if(sizeof($getStudentRegistrationInfo)>0){ ?>
						<tr> 
                            <td height="5" colspan="10"><B><U>Details As Per Last Registration :</U></B></td>
                        </tr>
                         <tr > 
                            <td height="5"></td>
                        </tr>
                         <tr > 
                            <td class="contenttab_internal_rows"><nobr><b>Student Mobile</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($getStudentRegistrationInfo[0]['studentMobileNo']); ?> </td>
                            <td class="contenttab_internal_rows"><nobr><b>Address</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($getStudentRegistrationInfo[0]['permAddress1']); ?></td>
                            </tr>
                            <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Father Mobile No</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($getStudentRegistrationInfo[0]['fatherMobile']); ?> </td> 
                            <td class="contenttab_internal_rows"><nobr><b>Landline No</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"> <?php echo parseOutput($getStudentRegistrationInfo[0]['landlineNo']); ?> </td> 
     			</tr>
			<tr>  
                            <td class="contenttab_internal_rows" width="150"><nobr><b>Father Email</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($getStudentRegistrationInfo[0]['parentEmail']); ?></td>
                        </tr>
			<?php $bloodGroupArray=Array('1'=>'A+','2'=>'A-','3'=>'B+','4'=>'B-','5'=>'O+','6'=>'O-','7'=>'AB+','8'=>'AB-');
   				 for($i=1;$i<9;$i++){
					 if($getStudentRegistrationInfo[0]['bloodGroup']==$i){
     					    $bloodGroup=$bloodGroupArray[$i];
    					 }		
 				  } ?>
                         <tr>
                            <td class="contenttab_internal_rows" valign="top"><nobr><b>Blood Group</b></nobr></td>
                            <td class="contenttab_internal_rows" valign="top"><b>:</b></td>
                            <td class="padding_top" valign="top"><?php echo parseOutput($bloodGroup); ?></td>
                            <td class="contenttab_internal_rows" valign="top"><nobr><b>Allergies</b></nobr></td>
                            <td class="contenttab_internal_rows" valign="top"><b>:</b></td>
                            <td class="padding_top" valign="top"><?php echo parseOutput($getStudentRegistrationInfo[0]['allergy']); ?></td>
                        </tr>
                      <?php } ?>
                       </table>
                      </div>                                                              
                      <div class="dhtmlgoodies_aTab">
                      <table width="100%" border="0" cellspacing="5" cellpadding="5">
                        <tr>
                            <td width="90" class="contenttab_internal_rows" width="200"><nobr><b>Father's Name</b></nobr></td>
                            <td width="5" class="contenttab_internal_rows"><b>:</b></td>
                            <td width="150" class="padding_top">
                            <?php 
                                global $titleResults;
                                if($studentDataArr[0]['fatherName']) 
                                  echo $titleResults[$studentDataArr[0]['fatherTitle']]." ".$studentDataArr[0]['fatherName']; 
                                else 
                                  echo NOT_APPLICABLE_STRING;  
                            ?>
                            </td>
                            <td width="90"  class="contenttab_internal_rows" width="200"><nobr><b>Mother's Name</b></nobr></td>
                            <td width="5"  class="contenttab_internal_rows"><b>:</b></td>
                            <td width="150"  class="padding_top">
                                <?php 
                                    if($studentDataArr[0]['motherName']) 
                                      echo $titleResults[$studentDataArr[0]['motherTitle']]." ".$studentDataArr[0]['motherName'];
                                    else 
                                      echo NOT_APPLICABLE_STRING;  
                                ?>      
                            </td>
                                      
                            <td width="90"  class="contenttab_internal_rows" width="200"><nobr><b>Guardian's Name</b></nobr></td>
                            <td width="5"  class="contenttab_internal_rows"><b>:</b></td>
                            <td width="150"  class="padding_top">
                                <?php 
                                   if($studentDataArr[0]['guardianName']) 
                                     echo $titleResults[$studentDataArr[0]['guardianTitle']]." ".$studentDataArr[0]['guardianName'] ;
                                   else 
                                     echo NOT_APPLICABLE_STRING;  
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Occupation</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['fatherOccupation'])?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Occupation</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['motherOccupation'])?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Occupation</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['guardianOccupation'])?></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Mobile No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['fatherMobileNo'])?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Mobile No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['motherMobileNo'])?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Mobile No.</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['guardianMobileNo']) ?></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Email</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['fatherEmail']) ?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Email</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['motherEmail'])?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Email</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['guardianEmail'])?></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Address 1</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['fatherAddress1'])?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Address 1</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['motherAddress1']) ?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Address 1</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['guardianAddress1']) ?></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Address 2</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['fatherAddress2'])?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Address 2</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['motherAddress2']) ?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Address 2</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['guardianAddress2'])?></td>
                        </tr>                                                                                                  
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Country</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                             <?php
                              require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
                              $results11 = CommonQueryManager::getInstance()->getCountries('countryName',"WHERE countryId=".parseInput($studentDataArr[0]['fatherCountryId']));             
                              echo parseOutput($results11[0]['countryName']);
                             ?>
                             </td>
                            <td class="contenttab_internal_rows"><nobr><b>Country</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                             <?php
                              require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
                              $results12 = CommonQueryManager::getInstance()->getCountries('countryName',"WHERE countryId=".parseInput($studentDataArr[0]['motherCountryId']));             
                              echo parseOutput($results12[0]['countryName']);
                             ?>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Country</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">                             <?php
                              require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
                              $results13 = CommonQueryManager::getInstance()->getCountries('countryName',"WHERE countryId=".parseInput($studentDataArr[0]['guardianCountryId']));             
                              echo parseOutput($results13[0]['countryName']);
                             ?>
                             </td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>State</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"> 
                            <?php
                              require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
                              $results14 = CommonQueryManager::getInstance()->getStates('stateName',"WHERE stateId=".parseInput($studentDataArr[0]['fatherStateId']));              
                              echo parseOutput($results14[0]['stateName']);
                            ?>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>State</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"> 
                            <?php
                              require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
                              $results15 = CommonQueryManager::getInstance()->getStates('stateName',"WHERE stateId=".parseInput($studentDataArr[0]['motherStateId']));              
                              echo parseOutput($results15[0]['stateName']);
                            ?>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>State</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                             <?php
                              require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
                              $results = CommonQueryManager::getInstance()->getStates('stateName',"WHERE stateId=".parseInput($studentDataArr[0]['guardianStateId']));              
                              echo parseOutput($results[0]['stateName']);
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>City</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <?php
                              require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
                              $results = CommonQueryManager::getInstance()->getCity('cityName',"WHERE cityId=".parseInput($studentDataArr[0]['fatherCityId']));              
                              echo parseOutput($results[0]['cityName']);
                            ?>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>City</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <?php
                              require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
                              $foundArr = CommonQueryManager::getInstance()->getCity('cityName',"WHERE cityId=".parseInput($studentDataArr[0]['motherCityId']));              
                              echo parseOutput($foundArr[0]['cityName']);
                            ?></td>
                            <td class="contenttab_internal_rows"><nobr><b>City</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top">
                            <?php
                              require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
                              $foundArr = CommonQueryManager::getInstance()->getCity('cityName',"WHERE cityId=".parseInput($studentDataArr[0]['guardianCityId']));              
                              echo parseOutput($foundArr[0]['cityName']);
                            ?></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Pin Code</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['fatherPinCode']) ?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Pin Code</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['motherPinCode']) ?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Pin Code</b></nobr></td>
                            <td class="contenttab_internal_rows"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['guardianPinCode']) ?></td>
                        </tr>
                        </table>
                       </div>
                        <div class="dhtmlgoodies_aTab">
                        <table width="95%" border="0" cellspacing="5" cellpadding="5" align="center">
                        <tr>
                            <td width="10%" class="contenttab_internal_rows"><nobr><b>University </b></nobr></td>
                            <td width="1%"  class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td width="38%" class="padding_top"><?php echo $university;?> </td>
                            <td width="10%" class="contenttab_internal_rows"><nobr><b>Institute </b></nobr></td>
                            <td width="1%" class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td width="38%" class="padding_top"><?php echo $sessionHandler->getSessionVariable('InstituteName');?></td>
                        </tr>
                        <tr>
                            <td  class="contenttab_internal_rows"><nobr><b>Degree </b></nobr></td>
                            <td  class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td class="padding_top"><?php echo $degree;?></td>
                            <td  class="contenttab_internal_rows"><nobr><b>Branch </b></nobr></td>
                            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td  class="padding_top"><?php echo $branch;?></td>
                        </tr> 
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Batch </b></nobr></td>
                            <td  class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td  class="padding_top"><?php echo $batch?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Current Study Period </b></nobr></td>
                            <td  class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td  class="padding_top"><?php echo $periodName?></td>
                        </tr> 
                        <tr>
                            <td  class="contenttab_internal_rows"><nobr><b>Institute Roll No. </b></nobr></td>
                            <td  class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td  class="padding_top"><?php echo $studentDataArr[0]['rollNo']!='' ? $studentDataArr[0]['rollNo'] :"--";?></td>
                            <td  class="contenttab_internal_rows"><nobr><b>Institute Registration No. </b></nobr></td>
                            <td  class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td  class="padding_top"><?php echo $studentDataArr[0]['regNo']!='' ? $studentDataArr[0]['regNo'] :"--";?></td>
                        </tr> 
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>University Roll No. </b></nobr></td>
                            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td class="padding_top"><?php echo $studentDataArr[0]['universityRollNo']!='' ? $studentDataArr[0]['universityRollNo'] :"--";?></td>
                            <td class="contenttab_internal_rows"><nobr><b>University Registration No. </b></nobr></td>
                            <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
                            <td class="padding_top"><?php echo $studentDataArr[0]['universityRegNo']!='' ? $studentDataArr[0]['universityRegNo'] :"--";?></td> 
                        </tr> 
                        <tr>
                            <td valign="top" colspan="6" width="100%">
                                <div id="scroll2" style="overflow:auto; width:100%; height:390px; vertical-align:top;">
                                    <div id="courseResultDiv" style="width:98%; vertical-align:top;"></div>
                                </div>
                            </td>
                        </tr> 
                        </table>
                        </div>
                        
                    <div class="dhtmlgoodies_aTab">
                        <table width="100%" border="0" cellspacing="5px" cellpadding="5px">
                        <tr>
                            <td width="10%" class="contenttab_internal_rows"><nobr><b>Quota</b></nobr></td>
                            <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                            <td width="90%" class="padding_top"><?php echo $quotaName;?></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Date of Admission</b></nobr></td>
                            <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                            <td class="padding_top"><?php 
                            if($studentDataArr[0]['dateOfAdmission']=="0000-00-00") {
                              echo NOT_APPLICABLE_STRING;      
                            }
                            else {
                              echo UtilityManager::formatDate($studentDataArr[0]['dateOfAdmission']);
                            } 
                            ?></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>I - Card Number</b></nobr></td>
                            <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['icardNumber']);?> </td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Mgmt. Category</b></nobr></td>
                            <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                            <td class="padding_top">
                            <?php if($studentDataArr[0]['managementCategory']) 
                                    echo "Yes";
                                  else
                                    echo NOT_APPLICABLE_STRING;  
                             ?> </td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Mgmt. Reference</b></nobr></td>
                            <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($studentDataArr[0]['managementReference']); ?></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Hostel Name</b></nobr></td>
                            <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                            <td class="padding_top"><?php
                              require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
                              $foundArr = CommonQueryManager::getInstance()->getHostelName('hostelName',"WHERE hostelId=".parseInput($studentDataArr[0]['hostelId'])); 
                              echo parseOutput($foundArr[0]['hostelName']); 
                            ?></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Hostel Room No.</b></nobr></td>
                            <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                            <td class="padding_top"><?php
                              require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
                              $foundArr = CommonQueryManager::getInstance()->getHostelRoom('roomName',"WHERE hostelRoomId=".parseInput($studentDataArr[0]['hostelRoomId'])); 
                              echo parseOutput($foundArr[0]['roomName']); 
                            ?></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Bus Route No.</b></nobr></td>
                            <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                            <td class="padding_top"><?php
                              require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
                              $foundArr1 = CommonQueryManager::getInstance()->getBusRoute('routeCode',"WHERE busRouteId=".parseInput($studentDataArr[0]['busRouteId'])); 
                              echo parseOutput($foundArr1[0]['routeCode']); 
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Bus Stop No.</b></nobr></td>
                            <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                            <td class="padding_top"><?php
                              require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
                              $foundArr = CommonQueryManager::getInstance()->getBusStop('stopName',"WHERE busStopId=".parseInput($studentDataArr[0]['busStopId'])); 
                              echo parseOutput($foundArr[0]['stopName']); 
                            ?>
                            </td>
                        </tr>
                        <tr>
                        <td valign='top' colspan='8' align="center">
                            <table border="0" cellspacing="1" cellpadding="1" width='100%'>
                            <tr>
                                <td height="5"></td>
                            </tr>
                            <tr class='row1'>
                                <td valign="middle" height='25' class="padding_top"><B>Class</B></td>
                                <td valign="middle" class="padding_top"><B>Roll Number</B></td>
                                <td valign="middle" class="padding_top"><B>Session</B></td>
                                <td valign="middle" class="padding_top"><B>School/Institute/University Last Attended</B></td>
                                <td valign="middle" class="padding_top"><B>Name of Board/University</B></td>
                                <td valign="middle" class="padding_top"><B>Education Stream</B></td>
                                <td valign="middle" align='right' class="padding_top"><B>Marks Obtained</B></td>
                                <td valign="middle" align='right' class="padding_top"><B>Max. Marks</B></td>
                                <td valign="middle" align='right' class="padding_top"><B>%age</B></td>
                            </tr>
                            <?php
                            global $classResults;
                            if(isset($classResults) && is_array($classResults)) {
                                $count = count($classResults);
                                foreach($classResults as $key=>$value) {
                                    echo "<tr class='row0'>
                                        <td valign='middle' nowrap>".$value."</td>
                                        <td valign='middle'>".parseOutput($rollArr[$key])."</td>
                                        <td valign='middle'>".parseOutput($sessionArr[$key])."</td>
                                        <td valign='middle'>".parseOutput($instituteArr[$key])."</td>
                                        <td valign='middle'>".parseOutput($boardArr[$key])."</td>  
                                        <td valign='middle'>".parseOutput($educationArr[$key])."</td>
                                        <td valign='middle' align='right'>".parseOutput($marksArr[$key])."</td>
                                        <td valign='middle' align='right'>".parseOutput($maxMarksArr[$key])."</td>
                                        <td valign='middle' align='right'>".parseOutput($perArr[$key])."</td>
                                        </tr>";
                                    }
                                    echo "<input type='hidden' id='countRecord' name='countRecord' value='".$count."'/>" ;
                             }
                            ?> 
                       <?php if(sizeof($getStudentRegistrationInfo)>0){ ?>
                         <tr>
 			 <td colspan="2">
			 <table width="100%" border="0" cellspacing="5px" cellpadding="5px">
                         <tr > 
                            <td height="5"></td>
                        </tr>
			<tr> 
                             <td class="contenttab_internal_rows"><nobr><b><u>Details As Per Last Registration:</u></b></nobr></td>
                        </tr>	
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Hostel Name</b></nobr></td>
                            <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($getStudentRegistrationInfo[0]['hostelName']); ?></td>
			</tr>
			<tr>
                            <td class="contenttab_internal_rows"><nobr><b>Hostel Room No</b></nobr></td>
                            <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($getStudentRegistrationInfo[0]['roomNo']); ?></td>
                        </tr>
			<tr>
                            <td class="contenttab_internal_rows"><nobr><b>Bus Route No</b></nobr></td>
                            <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($getStudentRegistrationInfo[0]['routeNo']); ?></td>
 			</tr>
			<tr>
                            <td class="contenttab_internal_rows"><nobr><b>Pick Up</b></nobr></td>
                            <td class="contenttab_internal_rows" width="1%"><b>:</b></td>
                            <td class="padding_top"><?php echo parseOutput($getStudentRegistrationInfo[0]['pickUp']); ?></td>
			</tr>
                         </td>
			</tr>
			</table>
                        <?php } ?>
                            </table>
                        </td>
                        </tr>
                        </table>
                    </div>
                    <div class="dhtmlgoodies_aTab" style="overflow:auto;HEIGHT:510px; vertical-align:top;">
                            <div id="timeTableResultDiv"></div>  
                        </div>
                       <div class="dhtmlgoodies_aTab" style="overflow:auto;HEIGHT:510px; vertical-align:top;">
                            <table width="100%" border="0" cellspacing="1" cellpadding="1">
                            <tr>
                                <td valign="top">
                                   <div id="gradeResultDiv"></div>
                                </td>
                            </tr>
                            </table>
                        </div>   

                        
                        <div class="dhtmlgoodies_aTab">
                        <table width="100%" border="0" cellspacing="1" cellpadding="1">
                        <tr class="row0">
                            <td valign="top">
                            <table border="0" cellspacing="1" cellpadding="0" align="center">
                            <tr>
                                <td valign="middle" align="left" class="contenttab_internal_rows" style="padding-right:5px;">
                                 <div id="consolidatedDiv" title="Consolidated View" style="text-decoration:underline;cursor:pointer;" onclick="toggleAttendanceDataFormat(document.getElementById('startDate2').value); return false;">
                                  <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/consolidated.gif" />
                                  </div>
                                </td>
                                <td valign="middle"><b>Show Attendence Upto </b></td>
                                <td class="contenttab_internal_rows" valign="middle"><b>:</b></td>
                                <td>
                                <?php
                                   require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                   echo HtmlFunctions::getInstance()->datePicker('startDate2',date('Y-m-d'));
                                ?></td>
                                <td width="5"></td>
                                <td><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/showlist.gif" onClick="getAttendance(document.getElementById('startDate2').value); return false;"></td>
                            </tr>
                            </table>
                            <td>
                        </tr>
                        <tr>
		                <td class="contenttab_internal_rows1">
		                	<font color="red"><b><u>Please Note:</u>&nbsp;</b></font>
							<font color="red">Medical Leaves are only applicable in the Consolidated view.</font><br/>
		                </td>
                		</tr>
                        <tr>
                            <td valign="top" style="padding-right:10px">
                               <div id="scroll2" style="overflow:auto;HEIGHT:510px; vertical-align:top;">
                                  <div id="attendanceResultDiv"></div>
                               </div>
                            </td>
                        </tr>
                        <tr>
                             <td colspan='1' align='right'><div id = 'saveDiv3'></div></td>
                        </tr> 
                        </table>
                        </div>
                       <div class="dhtmlgoodies_aTab" style="overflow:auto;HEIGHT:510px; vertical-align:top;">
                        <table width="100%" border="0" cellspacing="5" cellpadding="5">
                        <tr>
                            <td valign="top"><div id="feesResultsDiv"></div></td>
                        </tr>
                        </table>
                        </div>
                       <div class="dhtmlgoodies_aTab">
                            <table width="100%" border="0" cellspacing="2" cellpadding="2">
                            <tr class="row0">
                                <td valign="top">
                                <table border="0" cellspacing="1" cellpadding="0" align="right">
                                <tr>
                                    <td valign="top"><input type="text" id="searchbox" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" onkeydown="return sendKeys('searchbox',event);" /></td>    
                                    <td valign="top" width="5"></td>
                                    <td valign="top">
                                        <input type="image" src="<?php echo IMG_HTTP_PATH;?>/search.gif"  onClick="refreshResourceData(<?php echo $studentDataArr[0]['studentId']?>,document.addForm.studyPeriod.value);document.getElementById('saveDiv3').style.display='';return false;"/>
                                    </td>
                                </tr>
                                </table>
                                <td>
                            </tr> 
                            <tr>
                                <td valign="top" ><div id="resourceResultsDiv" style="overflow:auto;HEIGHT:510px; vertical-align:top;"></div></td>
                            </tr>
                           </table>                 
                       </div>    
                      <div class="dhtmlgoodies_aTab" style="overflow:auto;HEIGHT:510px; vertical-align:top;">
                           <table width="100%" border="0" cellspacing="2" cellpadding="2">
                           <tr>
                               <td valign="top"><div id="finalResultsDiv"></div></td>
                           </tr>
                           </table>                 
                       </div>   
                       <div class="dhtmlgoodies_aTab" style="overflow:auto;HEIGHT:510px; vertical-align:top;">
                           <table width="100%" border="0" cellspacing="2" cellpadding="2">
                           <tr>
                               <td valign="top">
                                   <div id="offenceResultsDiv"></div>
                               </td>
                           </tr>
                           </table>                 
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
                            <div style="overflow:auto; width:965px; height:540px; vertical-align:top;">
                               <div id="attendanceRegister" style="width:100%; vertical-align:top;"></div>
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
//initTabs('dhtmlgoodies_tabView1',Array('Personal Info','Parents Info','Course Info','Administrative Info','Schedule Info','Marks Info','Attendance Info','Fees Info','Resource Info','Final Result','Grades Info'),0,980,550,Array(false,false,false,false,false,false,false,false,false,false,false));
initTabs('dhtmlgoodies_tabView1',Array('Personal Info.','Parents Info.','Subject Info.','Administrative Info.','Time Table','Marks Info.','Attendance Info.','Fees Info.','Resource Info.','Final Result','Offense/Achv.','Attendance Register','Fees'),0,980,550,Array(false,false,false,false,false,false,false,false,false,false,false,false,false));
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
                             
                             if ($sessionHandler->getSessionVariable('SCHEDULE')==0)  {
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
                               }
                               
                              if ($sessionHandler->getSessionVariable('RESOURCE')==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_8').style.display='none'; </script>"; 
                              }
                              else if($show==-1) {
                                 $show=8;
                               }
                              
                             if ($sessionHandler->getSessionVariable('FINAL_RESULT')==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_9').style.display='none'; </script>"; 
                             }
                            else  if($show==-1) {
                                 $show=9;
                               }
                               
                              if ($sessionHandler->getSessionVariable('OFFENSE')==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_10').style.display='none'; </script>"; 
                              }
                            else  if($show==-1) {
                                 $show=10;
                               }

				 if ($sessionHandler->getSessionVariable('FEES')==0)  {
                               echo "<script>document.getElementById('tabTabdhtmlgoodies_tabView1_12').style.display='none'; </script>"; 
                             }
                             else  if($show==-1) {
                                 $show=12;
                               }  
                              
                             echo "<script>showTab('dhtmlgoodies_tabView1',".$show.");</script>";             
                       ?>     

             </td>
          </tr>
             <tr>
                        <td height="5" colspan="6"></td>
                    </tr>
                     <tr>
                        <td  align="center" style="padding-right:5px">
                        <input type="hidden" name="classId" value="<?php echo $studentDataArr[0]['classId']?>">
                        <input type="hidden" name="studentUserId" value="<?php echo $studentDataArr[0]['userId']?>">
                        <input type="hidden" name="fatherUserId" value="<?php echo $studentDataArr[0]['fatherUserId']?>">
                        <input type="hidden" name="motherUserId" value="<?php echo $studentDataArr[0]['motherUserId']?>">
                        <input type="hidden" name="guardianUserId" value="<?php echo $studentDataArr[0]['guardianUserId']?>">
                        <input type="hidden" name="studentId" value="<?php echo $studentDataArr[0]['studentId']?>">
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
 
<?php 
// $History: studentDetailContents.php $
//
//*****************  Version 26  *****************
//User: Jaineesh     Date: 2/04/10    Time: 11:08a
//Updated in $/LeapCC/Templates/Parent
//changes in code to show final result tab in find student & parent 
//
//*****************  Version 25  *****************
//User: Dipanjan     Date: 14/12/09   Time: 15:51
//Updated in $/LeapCC/Templates/Parent
//Corrected "Attendance" display  problems in "Parent" login
//
//*****************  Version 24  *****************
//User: Parveen      Date: 9/24/09    Time: 10:57a
//Updated in $/LeapCC/Templates/Parent
//alignment & condition format updated
//
//*****************  Version 23  *****************
//User: Parveen      Date: 9/03/09    Time: 5:48p
//Updated in $/LeapCC/Templates/Parent
//condition & formating updated issue fix (1426, 1384, 1263, 1074)
//
//*****************  Version 22  *****************
//User: Parveen      Date: 9/03/09    Time: 10:14a
//Updated in $/LeapCC/Templates/Parent
//student academic stream condition updated
//
//*****************  Version 21  *****************
//User: Parveen      Date: 9/02/09    Time: 2:15p
//Updated in $/LeapCC/Templates/Parent
//attendance, course Info validation & format updated 
//
//*****************  Version 20  *****************
//User: Dipanjan     Date: 8/27/09    Time: 5:47p
//Updated in $/LeapCC/Templates/Parent
//Gurkeerat: fixed issue 1268
//
//*****************  Version 19  *****************
//User: Dipanjan     Date: 8/20/09    Time: 7:02p
//Updated in $/LeapCC/Templates/Parent
//Gurkeerat: resolved issue 1172
//
//*****************  Version 18  *****************
//User: Dipanjan     Date: 8/19/09    Time: 5:09p
//Updated in $/LeapCC/Templates/Parent
//Gurkeerat: resolved issue 387
//
//*****************  Version 17  *****************
//User: Parveen      Date: 8/14/09    Time: 12:55p
//Updated in $/LeapCC/Templates/Parent
//search condition, report formating updated
//
//*****************  Version 16  *****************
//User: Parveen      Date: 7/02/09    Time: 5:59p
//Updated in $/LeapCC/Templates/Parent
//format update, Spellings correct (tabs)
//
//*****************  Version 15  *****************
//User: Parveen      Date: 7/02/09    Time: 5:03p
//Updated in $/LeapCC/Templates/Parent
//formatting, conditions, validations updated
//
//*****************  Version 14  *****************
//User: Parveen      Date: 7/01/09    Time: 3:47p
//Updated in $/LeapCC/Templates/Parent
//alignment & formatting updated (issue resolved Build No. CC0030)
//
//*****************  Version 13  *****************
//User: Parveen      Date: 6/26/09    Time: 6:55p
//Updated in $/LeapCC/Templates/Parent
//tag name & button theme wise changes
//
//*****************  Version 12  *****************
//User: Parveen      Date: 6/17/09    Time: 5:45p
//Updated in $/LeapCC/Templates/Parent
//spelling format correct (father's Name added )
//
//*****************  Version 11  *****************
//User: Parveen      Date: 6/17/09    Time: 2:24p
//Updated in $/LeapCC/Templates/Parent
//validation, formatting, link tabs updated
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 17/06/09   Time: 14:18
//Updated in $/LeapCC/Templates/Parent
//Modifed look and feel as mailed by kabir sir.
//
//*****************  Version 9  *****************
//User: Parveen      Date: 12/24/08   Time: 1:45p
//Updated in $/LeapCC/Templates/Parent
//formatting settings
//
//*****************  Version 8  *****************
//User: Parveen      Date: 12/24/08   Time: 12:23p
//Updated in $/LeapCC/Templates/Parent
//formatting settings
//
//*****************  Version 7  *****************
//User: Parveen      Date: 12/23/08   Time: 2:41p
//Updated in $/LeapCC/Templates/Parent
//sorting formatting setting
//
//*****************  Version 6  *****************
//User: Parveen      Date: 12/17/08   Time: 5:05p
//Updated in $/LeapCC/Templates/Parent
//initial checkin
//
//*****************  Version 5  *****************
//User: Parveen      Date: 12/16/08   Time: 5:15p
//Updated in $/LeapCC/Templates/Parent
//attendance update
//
//*****************  Version 4  *****************
//User: Parveen      Date: 12/16/08   Time: 10:45a
//Updated in $/LeapCC/Templates/Parent
//Intial Checkin 
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/15/08   Time: 5:30p
//Updated in $/LeapCC/Templates/Parent
//update info
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/10/08   Time: 3:27p
//Updated in $/LeapCC/Templates/Parent
//code review
//

?>
 
    


