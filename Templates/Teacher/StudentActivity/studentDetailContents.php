<?php
//-------------------------------------------------------
// Purpose: to design the layout for assign to subject.
//
// Author : Dipanjan Bhattacharjee
// Created on : (14.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
 
 
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top" class="title">
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td height="10"></td>
            </tr>
            <tr>
                <td valign="top">Student Info &nbsp;&raquo;&nbsp; Student Details</td>
                 <td align="right" style="padding-right:10px">
                   <INPUT type="image" alt="back" title="back" src="<?php echo IMG_HTTP_PATH?>/bigback.gif" alt="Back" border="0" onClick=goToURL("searchStudent.php?class=<?php echo $_REQUEST['class']?>&subject=<?php echo $_REQUEST['subject']?>&group=<?php echo $_REQUEST['group']?>&studentRollNo=<?php echo $_REQUEST['studentRollNo']?>&studentNameFilter=<?php echo $_REQUEST['studentNameFilter']?>&page=<?php echo $_REQUEST['page']?>&sortOrderBy=<?php echo $_REQUEST['sortOrderBy']?>&sortField=<?php echo $_REQUEST['sortField']?>") />
                  </a>
				 </td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="590">                              
            <tr>
             <td valign="top" class="content">

             <table width="100%" border="0" cellspacing="0" cellpadding="0" height="370">
			 
             <tr>
                <td class="contenttab_border" height="5px" >
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="contenttab_row1"><span class="content_title">Student Detail:</span>
						&nbsp;<B><U>Name:</U></B>&nbsp;<?php echo parseOutput($studentDataArr[0]['firstName'])." ".parseOutput($studentDataArr[0]['lastName']);?>
						&nbsp;&nbsp;<B><U>University:</U></B>&nbsp;<?php echo parseOutput($studentCourseDataArr[0]['universityAbbr']) ; ?>
						&nbsp;&nbsp;<B><U>Degree:</U></B>&nbsp;<?php echo parseOutput($studentCourseDataArr[0]['degreeAbbr']); ?>
						&nbsp;&nbsp;<B><U>Branch:</U></B>&nbsp;<?php echo parseOutput($studentCourseDataArr[0]['branchCode']) ; ?>
						&nbsp;&nbsp;<B><U>Batch:</U></B>&nbsp;<?php echo parseOutput($studentCourseDataArr[0]['batchYear']); ?>
						&nbsp;&nbsp;<B><U>Admission Date:</U></B>&nbsp;<?php echo parseOutput((UtilityManager::formatDate($studentDataArr[0]['dateOfAdmission'])));?>
                        &nbsp;&nbsp;<span class="content_title">Study Period:</span>
                        <select size="1" class="selectfield" name="studyPeriod" id="studyPeriod" style="width:100px" onchange="refreshStudentData(this.value,tabNumber);">
                        <option value="0">All</option>
                        <?php
                         require_once(BL_PATH.'/HtmlFunctions.inc.php');
                          if(trim(add_slashes($_REQUEST['id']))!=''){
                           echo HtmlFunctions::getInstance()->getStudyPeriodName(add_slashes(trim($_REQUEST['id'])),$studentDataArr[0]['classId'] );
                          }
                          else{
                              echo HtmlFunctions::getInstance()->getStudyPeriodName(-1,-1);
                          }
                        ?>
                        </select>
                       </td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" align="left" >
               <!--******************Tabs For Student Information***************-->
                  <div id="dhtmlgoodies_tabView1" >
                        <div class="dhtmlgoodies_aTab" style="overflow:auto">
                         
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                        <tr>
                            <td height="5"></td>
                        </tr>
                        
                        <tr>    
                          <td class="contenttab_internal_rows" width="10%"><nobr><b>First Name</b></nobr></td>
                          <td class="padding">:</td>
                          <td class="contenttab_internal_rows"><?php echo parseOutput($studentDataArr[0]['firstName']);?>
                          </td>
                            <td class="contenttab_internal_rows" width="12%"><nobr><b>Last Name</b></nobr></td>
                            <td class="padding">:</td>
                            <td class="contenttab_internal_rows"><?php echo parseOutput($studentDataArr[0]['lastName']);?>
                            </td>
                            <td colspan="2" align="center" valign="middle" rowspan="6">
                            <table border="0" width="320" height="200">
                            <tr>
                                <td width="130"></td>
                                <td class="field1_heading">
                                <?php if($studentDataArr[0]['studentPhoto']){ 
                                 echo "<img src='".STUDENT_PHOTO_PATH."/".$studentDataArr[0][studentPhoto]."' width='170' height='190' title='".$studentDataArr[0]['firstName']." ".$studentDataArr[0]['lastName']."' />";
                                 }
                             else{
                                 echo "<img src='".IMG_HTTP_PATH."/notfound.jpg' width='170' height='190'/>";
                             } 

                                ?>
                                 </td>
                            </tr>
                            </table>
                            </td>
                             
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Roll No</b></nobr></td>
                            <td class="padding">:</td>
                            <td class="contenttab_internal_rows"><?php echo parseOutput($studentDataArr[0]['rollNo']);?>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Institute Reg. No.</b></nobr></td>
                            <td class="padding">:</td>
                            <td class="contenttab_internal_rows"><?php echo parseOutput($studentDataArr[0]['regNo']); ?>
                            </td>
                        </tr>
                        <tr>    
                            
                            <td class="contenttab_internal_rows"><nobr><b>University No.</b></nobr></td>
                            <td class="padding">:</td>
                            <td class="contenttab_internal_rows"><?php echo parseOutput($studentDataArr[0]['universityRollNo']);?>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Univ Reg. No.</b></nobr></td>
                            <td class="padding">:</td>
                            <td class="contenttab_internal_rows"><?php echo parseOutput($studentDataArr[0]['universityRegNo']);?></td>
                        </tr>
                        <tr>    
                            <td class="contenttab_internal_rows"><nobr><b>Date Of Birth.</b></nobr></td>
                            <td class="padding">:</td>
                            <td class="contenttab_internal_rows">
                             <?php echo parseOutput((UtilityManager::formatDate($studentDataArr[0]['dateOfBirth'])));?>
                             </td>
                            <td class="contenttab_internal_rows"><nobr><b>Gender</b></nobr></td>
                            <td class="padding">:</td>
                            <td class="contenttab_internal_rows">
                            <?php 
                                 if($studentDataArr[0]['studentGender']=="M"){ 
                                  echo "Male"; 
                                 } 
                                 elseif($studentDataArr[0]['studentGender']=="F"){
                                    echo "Female";
                                 }
                                else{
                                 echo "---";
                                } 
                             ?>
                            </td>
                             
                            
                        </tr>
                        <tr>    
                            
                            <td class="contenttab_internal_rows"><nobr><b>Email</b></nobr></td>
                            <td class="padding">:</td>
                            <td class="contenttab_internal_rows"><?php echo parseOutput($studentDataArr[0]['studentEmail']);?>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Nationality</b></nobr></td>
                            <td class="padding">:</td>
                            <td class="contenttab_internal_rows">
                            <?php
                              require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
                              $results = CommonQueryManager::getInstance()->getCountries('countryName',"WHERE countryId=".parseInput($studentDataArr[0]['nationalityId'])); 
                            ?>
                            <?php echo parseOutput($results[0]['nationalityName']); ?>
                             </td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Contact No.</b></nobr></td>
                            <td class="padding">:</td>
                            <td class="contenttab_internal_rows"><?php echo parseOutput($studentDataArr[0]['studentPhone']); ?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Mobile No.</b></nobr></td>
                            <td class="padding">:</td>
                            <td class="contenttab_internal_rows"><?php echo parseOutput($studentDataArr[0]['studentMobileNo']); ?></td>
                        </tr>
          		  <tr>
          		      <td height="5px"></td></tr>
			  <?php if(sizeof($getStudentRegistrationInfo)>0){ ?>
          		  <tr>
			   <td class="contenttab_internal_rows"><nobr><b><u>Details As Per Last Registration :</u></b></nobr></td>
			 </tr>
          		  <tr>
          		      <td height="5px"></td></tr>
          		  <tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Student Mobile No</b></nobr></td>
                            <td class="padding">:</td>
                            <td class="contenttab_internal_rows"><?php echo parseOutput($getStudentRegistrationInfo[0]['studentMobileNo']); ?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Address</b></nobr></td>
                            <td class="padding">:</td>
                            <td class="contenttab_internal_rows"><?php echo parseOutput($getStudentRegistrationInfo[0]['permAddress1']); ?></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Father Mobile No</b></nobr></td>
                            <td class="padding">:</td>
                            <td class="contenttab_internal_rows"><?php echo parseOutput($getStudentRegistrationInfo[0]['fatherMobile']); ?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Landline No</b></nobr></td>
                            <td class="padding">:</td>
                            <td class="contenttab_internal_rows"><?php echo parseOutput($getStudentRegistrationInfo[0]['landlineNo']); ?></td>
			</tr>
			<tr>
                            <td class="contenttab_internal_rows"><nobr><b>Father Email</b></nobr></td>
                            <td class="padding">:</td>
                            <td class="contenttab_internal_rows"><?php echo parseOutput($getStudentRegistrationInfo[0]['parentEmail']); ?></td>
                        </tr>
				<?php $bloodGroupArray=Array('1'=>'A+','2'=>'A-','3'=>'B+','4'=>'B-','5'=>'O+','6'=>'O-','7'=>'AB+','8'=>'AB-');
   				 for($i=1;$i<9;$i++){
					 if($getStudentRegistrationInfo[0]['bloodGroup']==$i){
     					    $bloodGroup=$bloodGroupArray[$i];
    					 }		
 				  } ?>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Blood Group</b></nobr></td>
                            <td class="padding">:</td>
                            <td class="contenttab_internal_rows"><?php echo parseOutput($bloodGroup); ?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Allergies</b></nobr></td>
                            <td class="padding">:</td>
                            <td class="contenttab_internal_rows"><?php echo parseOutput($getStudentRegistrationInfo[0]['allergy']); ?></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Hostel Name</b></nobr></td>
                            <td class="padding">:</td>
                            <td class="contenttab_internal_rows"><?php echo parseOutput($getStudentRegistrationInfo[0]['hostelName']); ?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Hostel Room No</b></nobr></td>
                            <td class="padding">:</td>
                            <td class="contenttab_internal_rows"><?php echo parseOutput($getStudentRegistrationInfo[0]['roomNo']); ?></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Bus Route No</b></nobr></td>
                            <td class="padding">:</td>
                            <td class="contenttab_internal_rows"><?php echo parseOutput($getStudentRegistrationInfo[0]['routeNo']); ?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Pick Up</b></nobr></td>
                            <td class="padding">:</td>
                            <td class="contenttab_internal_rows"><?php echo parseOutput($getStudentRegistrationInfo[0]['pickUp']); ?></td>
                        </tr>
                        <?php } ?>
			
                        </table>
                         
                        </div>
                        <div class="dhtmlgoodies_aTab" style="overflow:auto">
                        <table width="100%" border="0" cellspacing="5" cellpadding="5">
                        <tr>
                            <td  class="contenttab_internal_rows" width="10%"><nobr><b>Father's Name</b></nobr></td>
                            <td class="padding" width="1%">:</td>
                            <td  class="contenttab_internal_rows" width="20%"><?php echo parseOutput($titleResults[$studentDataArr[0]['fatherTitle']]).'.'.parseOutput($studentDataArr[0]['fatherName']);  ?></td>
                            <td  class="contenttab_internal_rows" width="10%"><nobr><b>Mother's Name</b></nobr></td>
                            <td class="padding" width="1%">:</td>
                            <td  class="contenttab_internal_rows" width="20%"><?php echo parseOutput($titleResults[$studentDataArr[0]['motherTitle']]).'.'.$studentDataArr[0]['motherName'];  ?></td>
                            <td  class="contenttab_internal_rows" width="10%"><nobr><b>Guardian's Name</b></nobr></td>
                            <td class="padding" width="1%">:</td>
                            <td  class="contenttab_internal_rows" width="20%"><?php echo parseOutput($titleResults[$studentDataArr[0]['guardianTitle']]).'.'.parseOutput($studentDataArr[0]['guardianName']);  ?></td>
                        </tr>
                        <tr>
                            <td  class="contenttab_internal_rows"><nobr><b>Occupation</b></nobr></td>
                            <td class="padding">:</td>
                            <td  class="contenttab_internal_rows"><?php echo parseOutput($studentDataArr[0]['fatherOccupation']);  ?></td>  
                            <td  class="contenttab_internal_rows"><nobr><b>Occupation</b></nobr></td>
                            <td class="padding">:</td>
                            <td  class="contenttab_internal_rows"><?php echo parseOutput($studentDataArr[0]['motherOccupation']);  ?></td>  
                            <td  class="contenttab_internal_rows"><nobr><b>Occupation</b></nobr></td>
                            <td class="padding">:</td>
                            <td  class="contenttab_internal_rows"><?php echo parseOutput($studentDataArr[0]['guardianOccupation']);  ?></td>  
                        </tr>
                        <tr>
                            <td  class="contenttab_internal_rows"><nobr><b>Mobile No.</b></nobr></td>
                            <td class="padding">:</td>
                            <td  class="contenttab_internal_rows"><?php echo parseOutput($studentDataArr[0]['fatherMobileNo']);  ?></td>  
                            <td  class="contenttab_internal_rows"><nobr><b>Mobile No.</b></nobr></td>
                            <td class="padding">:</td>
                            <td  class="contenttab_internal_rows"><?php echo parseOutput($studentDataArr[0]['motherMobileNo']);  ?></td> 
                            <td  class="contenttab_internal_rows"><nobr><b>Mobile No.</b></nobr></td>
                            <td class="padding">:</td>
                            <td  class="contenttab_internal_rows"><?php echo parseOutput($studentDataArr[0]['guardianMobileNo']);  ?></td> 
                        </tr>
                        <tr>
                            <td  class="contenttab_internal_rows"><nobr><b>Email</b></nobr></td>
                            <td class="padding">:</td>
                            <td  class="contenttab_internal_rows"><?php echo parseOutput($studentDataArr[0]['fatherEmail']);  ?></td> 
                            <td  class="contenttab_internal_rows"><nobr><b>Email</b></nobr></td>
                            <td class="padding">:</td>
                            <td  class="contenttab_internal_rows"><?php echo parseOutput($studentDataArr[0]['motherEmail']);  ?></td>   
                            <td  class="contenttab_internal_rows"><nobr><b>Email</b></nobr></td>
                            <td class="padding">:</td>
                            <td  class="contenttab_internal_rows"><?php echo parseOutput($studentDataArr[0]['guardianEmail']);  ?></td>   
                        </tr>
                        <tr>
                            <td  class="contenttab_internal_rows"><nobr><b>Address 1</b></nobr></td>
                            <td class="padding">:</td>
                            <td  class="contenttab_internal_rows"><?php echo parseOutput($studentDataArr[0]['fatherAddress1']);  ?></td>   
                            <td  class="contenttab_internal_rows"><nobr><b>Address 1</b></nobr></td>
                            <td class="padding">:</td>
                            <td  class="contenttab_internal_rows"><?php echo parseOutput($studentDataArr[0]['motherAddress1']);  ?></td>         
                            <td  class="contenttab_internal_rows"><nobr><b>Address 1</b></nobr></td>
                            <td class="padding">:</td>
                            <td  class="contenttab_internal_rows"><?php echo parseOutput($studentDataArr[0]['guardianAddress1']);  ?></td>         
                        </tr>
                        <tr>
                            <td  class="contenttab_internal_rows"><nobr><b>Address 2</b></nobr></td>
                            <td class="padding">:</td>
                            <td  class="contenttab_internal_rows"><?php echo parseOutput($studentDataArr[0]['fatherAddress2']);  ?></td>         
                            <td  class="contenttab_internal_rows"><nobr><b>Address 2</b></nobr></td>
                            <td class="padding">:</td>
                            <td  class="contenttab_internal_rows"><?php echo parseOutput($studentDataArr[0]['motherAddress2']);  ?></td>         
                            <td  class="contenttab_internal_rows"><nobr><b>Address 2</b></nobr></td>
                            <td class="padding">:</td>
                            <td  class="contenttab_internal_rows"><?php echo parseOutput($studentDataArr[0]['guardianAddress2']);  ?></td> 
                        </tr>
                        </table>
                        </div>
                        <div class="dhtmlgoodies_aTab" style="overflow:auto">
                        <table width="100%" border="0" cellspacing="5" cellpadding="5">
                        <tr>
                            <td class="contenttab_internal_rows" width="7%"><nobr><b>University</b></nobr></td>
                            <td class="padding" width="1%">:</td>
                            <td class="contenttab_internal_rows"><?php echo parseOutput($studentCourseDataArr[0]['universityName']); ?></td>
                            <td class="contenttab_internal_rows" width="7%"><nobr><b>Institute</b></nobr></td>
                            <td class="padding" width="1%">:</td>
                            <td class="contenttab_internal_rows"><?php echo parseOutput($studentCourseDataArr[0]['instituteName']); ?></td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Degree</b></nobr></td>
                            <td class="padding" width="1%">:</td> 
                            <td class="contenttab_internal_rows"><?php echo parseOutput($studentCourseDataArr[0]['degreeName']); ?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Branch</b></nobr></td>
                            <td class="padding" width="1%">:</td> 
                            <td class="contenttab_internal_rows" ><?php echo parseOutput($studentCourseDataArr[0]['branchName']); ?></td>
                        </tr> 
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Batch</b></nobr></td>
                            <td class="padding" width="1%">:</td> 
                            <td class="contenttab_internal_rows" ><?php echo parseOutput($studentCourseDataArr[0]['batchName']); ?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Current Period</b></nobr></td>
                            <td class="padding" width="1%">:</td> 
                            <td class="contenttab_internal_rows" ><?php echo parseOutput($studentCourseDataArr[0]['periodName']); ?></td>
                        </tr> 
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Institute Roll No.</b></nobr></td>
                            <td class="padding" width="1%">:</td> 
                            <td class="contenttab_internal_rows" ><?php echo parseOutput($studentDataArr[0]['rollNo']); ?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Institute Registration No.</b></nobr></td>
                            <td class="padding" width="1%">:</td> 
                            <td class="contenttab_internal_rows" ><?php echo parseOutput($studentDataArr[0]['regNo']); ?></td>
                        </tr> 
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>University Roll No.</b></nobr></td>
                            <td class="padding" width="1%">:</td> 
                            <td class="contenttab_internal_rows" ><?php echo parseOutput($studentDataArr[0]['universityRollNo']);?></td>
                            <td class="contenttab_internal_rows"><nobr><b>University Registration No.</b></nobr></td>
                            <td class="padding" width="1%">:</td> 
                            <td class="contenttab_internal_rows" ><?php echo parseOutput($studentDataArr[0]['universityRegNo']); ?></td>
                        </tr>
                        <tr>
                         <td width="50%" colspan="6">
                         <!--Shows student groups -->
                          <div id="groupResultsDiv"></div>
                         <!--Shows student groups --> 
                         </td>
                        </tr> 
                        </table>
                        </div>
                        
                        <div class="dhtmlgoodies_aTab" style="overflow:auto">
                        <!--Shows student marks -->
                           <div id="marksResultsDiv"></div>
                        <!--Shows student groups -->   
                        </div>
                        
                        <div class="dhtmlgoodies_aTab" style="overflow:auto">
                        <table width="100%" border="0" cellspacing="1" cellpadding="1">
                        <tr class="row0">
                            <td valign="top" align="center">
                            <?php 
                             //get current date
                             //$thisDate=date('Y')."-".date('m')."-".date('d');
                            ?>
                            <table width="100%" border="0" cellspacing="1" cellpadding="1" align="left">
                            <tr>
                                <td width="5%" valign="middle" align="left" class="contenttab_internal_rows" style="padding-right:5px;">
                                 <div id="consolidatedDiv" title="Consolidated View" style="text-decoration:underline;cursor:pointer;" onclick="toggleAttendanceDataFormat(document.getElementById('studyPeriod').value);">
                                  <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH; ?>/consolidated.gif" />
                                  </div>
                                </td>
                                <td width="7%" class="contenttab_internal_rows"><b>From Date</b></td>
                                <td width="10%" valign="top" align="left" >
                                 <?php
                                       require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                       echo HtmlFunctions::getInstance()->datePicker('startDate',$thisDate);
                                 ?>
                               </td>
                                <td width="7%" class="contenttab_internal_rows"><b>To Date</b></td>
                                <td width="10%" valign="top" align="left">
                                  <?php
                                       require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                       echo HtmlFunctions::getInstance()->datePicker('endDate',$thisDate);
                                 ?>
                                </td>    
                                <td valign="top" align="left"><input type="image" src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="getAttendance(<?php echo $_REQUEST['id']?>,document.getElementById('startDate').value,document.getElementById('endDate').value);return false;"/></td>
                            </tr>
                            </table>
                            <td>
                        </tr>
                        <tr>
                            <td valign="top">
                             <!--Shows student attendance -->   
                              <div id="attendanceResultsDiv"></div>
                            <!--Shows student attendance -->    
                            </td>
                        </tr>
                        </table>
                        </div>
    
    <!--Uploade Resource Div-->                        
    <div class="dhtmlgoodies_aTab" style="overflow:auto">
     <?php
       $sessionHandler->setSessionVariable('rStudentId',trim($_REQUEST['id']));
     ?>
        <table width="100%" border="0" cellspacing="5" cellpadding="0">
                       <tr>
                        <td align="right">
                          <input type="text" name="searchbox" id="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" onkeydown="return sendKeysForResource('searchbox',event);" />
                          &nbsp;
                          <input type="image"  name="submit" src="<?php echo IMG_HTTP_PATH;?>/search.gif" title="Search"   style="margin-bottom: -5px;" onClick="refreshResourceData(document.getElementById('studyPeriod').value);"/>&nbsp;
                         </td>
                        </tr>
                        <tr>
                            <td valign="top">
                            <div id="resourceResultsDiv">
                           </div> 
                          </td>
                        </tr>
                      </table>                 
     </div>   
     
     <!--Offense/Achv Div-->   
     <div class="dhtmlgoodies_aTab">
       <table width="100%" border="0" cellspacing="2" cellpadding="2">
           <tr>
               <td>
                    <div id="offenceResultsDiv" style="overflow:auto;HEIGHT:510px"></div>
                </td>
           </tr>
        </table>                 
        <!--Start Notice  Div-->
        <?php floatingDiv_Start('divMessage','Brief Description '); ?>
        <form name="MessageForm" action="" method="post">  
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
            <tr>
                <td height="5px"></td></tr>
            <tr>
            <tr>    
                <td width="89%"><div id="message" style="overflow:auto; width:400px; height:200px" ></div></td>
            </tr>
        </table>
        </form> 
        <?php floatingDiv_End(); ?>
    </div>    
</div>

<script type="text/javascript">
initTabs('dhtmlgoodies_tabView1',Array('Personal Details','Parents Details','Course Details','Grades Details','Attendance Details','Resources','Offense/Achv'),0,980,300,Array(false,false,false,false,false,false,false));
</script>

<!--******************Tab Ends************************************-->    	    
             </td>
          </tr>

				
          </table>
  
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>
 
<?php 
// $History: studentDetailContents.php $
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 3/11/09    Time: 12:30
//Updated in $/LeapCC/Templates/Teacher/StudentActivity
//Fixed Query Error
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 8/10/09    Time: 16:23
//Updated in $/LeapCC/Templates/Teacher/StudentActivity
//Done bug fixing.
//Bug ids---
//00001712,
//00001504
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 7/10/09    Time: 16:41
//Updated in $/LeapCC/Templates/Teacher/StudentActivity
//Done bug fixing.
//Bug ids---
//00001726,
//00001714,
//00001713
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 7/10/09    Time: 15:52
//Updated in $/LeapCC/Templates/Teacher/StudentActivity
//Added Detailed(group wise) and Consolidated view(irrespective of groups
//of a subject) of attendance records in student tabs .Now user can
//choose whether to view complete or just consolidated attendance of a
//student.This is also done in print & export to excel version of these
//reports as applicable.
//
//*****************  Version 9  *****************
//User: Gurkeerat    Date: 9/11/09    Time: 5:36p
//Updated in $/LeapCC/Templates/Teacher/StudentActivity
//resolved issue 1506
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 24/08/09   Time: 11:54
//Updated in $/LeapCC/Templates/Teacher/StudentActivity
//Corrected look and feel of teacher module logins
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 17/08/09   Time: 15:51
//Updated in $/LeapCC/Templates/Teacher/StudentActivity
//Corrected look and feel issues
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 13/07/09   Time: 11:59
//Updated in $/LeapCC/Templates/Teacher/StudentActivity
//Added "Class" column in student display and corrected session changing
//problem
//
//*****************  Version 5  *****************
//User: Parveen      Date: 6/17/09    Time: 4:31p
//Updated in $/LeapCC/Templates/Teacher/StudentActivity
//comma updated (father's Name)
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 17/06/09   Time: 14:18
//Updated in $/LeapCC/Templates/Teacher/StudentActivity
//Modifed look and feel as mailed by kabir sir.
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/30/09    Time: 7:15p
//Updated in $/LeapCC/Templates/Teacher/StudentActivity
//condition & formatting update
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/05/08   Time: 1:38p
//Updated in $/LeapCC/Templates/Teacher/StudentActivity
//Corrected Student Tabs
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher/StudentActivity
//
//*****************  Version 16  *****************
//User: Dipanjan     Date: 9/29/08    Time: 12:22p
//Updated in $/Leap/Source/Templates/Teacher/StudentActivity
//
//*****************  Version 15  *****************
//User: Dipanjan     Date: 9/20/08    Time: 7:13p
//Updated in $/Leap/Source/Templates/Teacher/StudentActivity
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 9/20/08    Time: 3:56p
//Updated in $/Leap/Source/Templates/Teacher/StudentActivity
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 9/19/08    Time: 2:54p
//Updated in $/Leap/Source/Templates/Teacher/StudentActivity
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 9/16/08    Time: 1:40p
//Updated in $/Leap/Source/Templates/Teacher/StudentActivity
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 9/01/08    Time: 5:36p
//Updated in $/Leap/Source/Templates/Teacher/StudentActivity
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 9/01/08    Time: 11:34a
//Updated in $/Leap/Source/Templates/Teacher/StudentActivity
//Corrected the problem of header row of tables(which displays the list )
//which is taking much
//vertical space in IE.
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 8/26/08    Time: 6:41p
//Updated in $/Leap/Source/Templates/Teacher/StudentActivity
//Removed HTML error by readjusting <form> tags
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/22/08    Time: 5:36p
//Updated in $/Leap/Source/Templates/Teacher/StudentActivity
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/22/08    Time: 3:40p
//Updated in $/Leap/Source/Templates/Teacher/StudentActivity
//Corrected image name
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/07/08    Time: 10:49a
//Updated in $/Leap/Source/Templates/Teacher/StudentActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/06/08    Time: 7:27p
//Updated in $/Leap/Source/Templates/Teacher/StudentActivity
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/31/08    Time: 7:27p
//Updated in $/Leap/Source/Templates/Teacher/StudentActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/26/08    Time: 10:37a
//Updated in $/Leap/Source/Templates/Teacher/StudentActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/15/08    Time: 5:37p
//Updated in $/Leap/Source/Templates/Teacher/StudentActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/14/08    Time: 7:20p
//Created in $/Leap/Source/Templates/Teacher/StudentActivity
//Initial Checkin
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
