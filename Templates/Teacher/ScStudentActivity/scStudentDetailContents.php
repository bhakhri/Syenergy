<?php
//-------------------------------------------------------
// Purpose: to design the layout for assign to subject.
//
// Author : Dipanjan Bhattacharjee
// Created on : (14.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
                  <a href="scSearchStudent.php?classes=<?php echo $REQUEST_DATA['classes']?>&subject=<?php echo $REQUEST_DATA['subject']?>&section=<?php echo $REQUEST_DATA['section']?>&studentRollNo=<?php echo $REQUEST_DATA['studentRollNo']?>&studentNameFilter=<?php echo $REQUEST_DATA['studentNameFilter']?>&page=<?php echo $REQUEST_DATA['page']?>&sortOrderBy=<?php echo $REQUEST_DATA['sortOrderBy']?>&sortField=<?php echo $REQUEST_DATA['sortField']?>"><?php echo "<img src='".IMG_HTTP_PATH."/bigback.gif' alt=\"Back\" border=\"0\" />";?> </a>
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
                        <select size="1" class="selectfield" name="studyPeriod" id="studyPeriod" style="width:100px" onchange="refreshStudentData(this.value);">
                        <option value="0">All</option>
                        <?php
                         require_once(BL_PATH.'/HtmlFunctions.inc.php');
                         echo HtmlFunctions::getInstance()->getStudyPeriodName(add_slashes($REQUEST_DATA['id']),$studentDataArr[0]['classId'] );
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
                            <td class="contenttab_internal_rows"><nobr><b>First Name: </b></nobr></td>
                          <td class="padding"><?php echo parseOutput($studentDataArr[0]['firstName']);?>
                          </td>
                            <td class="contenttab_internal_rows"><nobr><b>Last Name: </b></nobr></td>
                            <td class="padding"><?php echo parseOutput($studentDataArr[0]['lastName']);?>
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
                            <td class="contenttab_internal_rows"><nobr><b>Roll No: </b></nobr></td>
                            <td class="padding"><?php echo parseOutput($studentDataArr[0]['rollNo']);?>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Institute Reg No: </b></nobr></td>
                            <td class="padding"><?php echo parseOutput($studentDataArr[0]['regNo']); ?>
                            </td>
                        </tr>
                        <tr>    
                            
                            <td class="contenttab_internal_rows"><nobr><b>University No: </b></nobr></td>
                            <td class="padding"><?php echo parseOutput($studentDataArr[0]['universityRollNo']);?>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Univ Reg No: </b></nobr></td>
                            <td class="padding"><?php echo parseOutput($studentDataArr[0]['universityRegNo']);?></td>
                        </tr>
                        <tr>    
                            <td class="contenttab_internal_rows"><nobr><b>Date Of Birth: </b></nobr></td>
                            <td class="padding">
                             <?php echo parseOutput((UtilityManager::formatDate($studentDataArr[0]['dateOfBirth'])));?>
                             </td>
                            <td class="contenttab_internal_rows"><nobr><b>Gender: </b></nobr></td>
                            <td class="padding">
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
                            
                            <td class="contenttab_internal_rows"><nobr><b>Email: </b></nobr></td>
                            <td class="padding"><?php echo parseOutput($studentDataArr[0]['studentEmail']);?>
                            </td>
                            <td class="contenttab_internal_rows"><nobr><b>Nationality: </b></nobr></td>
                            <td class="padding">
                            <?php
                              require_once(MODEL_PATH."/CommonQueryManager.inc.php"); 
                              $results = CommonQueryManager::getInstance()->getCountries('countryName',"WHERE countryId=".parseInput($studentDataArr[0]['nationalityId'])); 
                            ?>
                            <?php echo parseOutput($results[0]['countryName']); ?>
                             </td>
                        </tr>
                        <tr>
                            <td class="contenttab_internal_rows"><nobr><b>Contact No: </b></nobr></td>
                            <td class="padding"><?php echo parseOutput($studentDataArr[0]['studentPhone']); ?></td>
                            <td class="contenttab_internal_rows"><nobr><b>Mobile No: </b></nobr></td>
                            <td class="padding"><?php echo parseOutput($studentDataArr[0]['studentMobileNo']); ?></td>
                        </tr>
                        </table>
                         
                        </div>

                        <div class="dhtmlgoodies_aTab" style="overflow:auto" >
                        <table width="100%" border="0" cellspacing="5" cellpadding="5">
                        <tr>
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Father Name: </b></nobr></td>
                            <td width="15%" class="padding"><?php echo parseOutput($studentDataArr[0]['fatherName']);  ?></td>
                            <td width="10%" class="contenttab_internal_rows"><nobr><b>Mother Name: </b></nobr></td>
                            <td width="25%" class="padding"><?php echo $studentDataArr[0]['motherName'];  ?></td>
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Guardian Name: </b></nobr></td>
                            <td width="15%" class="padding"><?php echo parseOutput($studentDataArr[0]['guardianName']);  ?></td>
                        </tr>
                        <tr>
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Father Title: </b></nobr></td>
                            <td width="15%" class="padding"><?php echo parseOutput($studentDataArr[0]['fatherTitle']);  ?></td>     
                            <td width="10%" class="contenttab_internal_rows"><nobr><b>Mother Title: </b></nobr></td>
                            <td width="25%" class="padding"><?php echo parseOutput($studentDataArr[0]['motherTitle']);  ?></td>  
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Guardian Title: </b></nobr></td>
                            <td width="15%" class="padding"><?php echo parseOutput($studentDataArr[0]['guardianTitle']);  ?></td>  
                        </tr>
                        <tr>
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Occupation: </b></nobr></td>
                            <td width="15%" class="padding"><?php echo parseOutput($studentDataArr[0]['fatherOccupation']);  ?></td>  
                            <td width="10%" class="contenttab_internal_rows"><nobr><b>Occupation: </b></nobr></td>
                            <td width="25%" class="padding"><?php echo parseOutput($studentDataArr[0]['motherOccupation']);  ?></td>  
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Occupation: </b></nobr></td>
                            <td width="15%" class="padding"><?php echo parseOutput($studentDataArr[0]['guardianOccupation']);  ?></td>  
                        </tr>
                        <tr>
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Mobile No: </b></nobr></td>
                            <td width="15%" class="padding"><?php echo parseOutput($studentDataArr[0]['fatherMobileNo']);  ?></td>  
                            <td width="10%" class="contenttab_internal_rows"><nobr><b>Mobile No: </b></nobr></td>
                            <td width="25%" class="padding"><?php echo parseOutput($studentDataArr[0]['motherMobileNo']);  ?></td> 
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Mobile No: </b></nobr></td>
                            <td width="15%" class="padding"><?php echo parseOutput($studentDataArr[0]['guardianMobileNo']);  ?></td> 
                        </tr>
                        <tr>
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Email: </b></nobr></td>
                            <td width="15%" class="padding"><?php echo parseOutput($studentDataArr[0]['fatherEmail']);  ?></td> 
                            <td width="10%" class="contenttab_internal_rows"><nobr><b>Email: </b></nobr></td>
                            <td width="25%" class="padding"><?php echo parseOutput($studentDataArr[0]['motherEmail']);  ?></td>   
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Email: </b></nobr></td>
                            <td width="15%" class="padding"><?php echo parseOutput($studentDataArr[0]['guardianEmail']);  ?></td>   
                        </tr>
                        <tr>
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Address 1: </b></nobr></td>
                            <td width="15%" class="padding"><?php echo parseOutput($studentDataArr[0]['fatherAddress1']);  ?></td>   
                            <td width="10%" class="contenttab_internal_rows"><nobr><b>Address 1: </b></nobr></td>
                            <td width="25%" class="padding"><?php echo parseOutput($studentDataArr[0]['motherAddress1']);  ?></td>         
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Address 1: </b></nobr></td>
                            <td width="15%" class="padding"><?php echo parseOutput($studentDataArr[0]['guardianAddress1']);  ?></td>         
                        </tr>
                        <tr>
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Address 2: </b></nobr></td>
                            <td width="15%" class="padding"><?php echo parseOutput($studentDataArr[0]['fatherAddress2']);  ?></td>         
                            <td width="10%" class="contenttab_internal_rows"><nobr><b>Address 2: </b></nobr></td>
                            <td width="25%" class="padding"><?php echo parseOutput($studentDataArr[0]['motherAddress2']);  ?></td>         
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Address 2: </b></nobr></td>
                            <td width="15%" class="padding"><?php echo parseOutput($studentDataArr[0]['guardianAddress2']);  ?></td> 
                        </tr>
                        </table>
                        </div>
                        <div class="dhtmlgoodies_aTab" style="overflow:auto">
                        <table width="100%" border="0" cellspacing="5" cellpadding="5">
                        <tr>
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>University: </b></nobr></td>
                            <td width="15%" class="padding"><?php echo parseOutput($studentCourseDataArr[0]['universityName']); ?></td>
                            <td width="10%" class="contenttab_internal_rows"><nobr><b>Institute: </b></nobr></td>
                            <td width="25%" class="padding"><?php echo parseOutput($studentCourseDataArr[0]['instituteName']); ?></td>
                        </tr>
                        <tr>
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Degree: </b></nobr></td>
                            <td width="15%" class="padding"><?php echo parseOutput($studentCourseDataArr[0]['degreeName']); ?></td>
                            <td width="10%" class="contenttab_internal_rows"><nobr><b>Branch: </b></nobr></td>
                            <td width="25%" class="padding"><?php echo parseOutput($studentCourseDataArr[0]['branchName']); ?></td>
                        </tr> 
                        <tr>
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Batch: </b></nobr></td>
                            <td width="15%" class="padding"><?php echo parseOutput($studentCourseDataArr[0]['batchName']); ?></td>
                            <td width="10%" class="contenttab_internal_rows"><nobr><b>Current Period: </b></nobr></td>
                            <td width="25%" class="padding"><?php echo parseOutput($studentCourseDataArr[0]['periodName']); ?></td>
                        </tr> 
                        <tr>
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Institute Roll No: </b></nobr></td>
                            <td width="15%" class="padding"><?php echo parseOutput($studentDataArr[0]['rollNo']); ?></td>
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>Institute Registration No: </b></nobr></td>
                            <td width="15%" class="padding"><?php echo parseOutput($studentDataArr[0]['regNo']); ?></td>
                        </tr> 
                        <tr>
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>University Roll: </b></nobr></td>
                            <td width="15%" class="padding"><?php echo parseOutput($studentDataArr[0]['universityRollNo']);?></td>
                            <td width="15%" class="contenttab_internal_rows"><nobr><b>University Registration No: </b></nobr></td>
                            <td width="15%" class="padding"><?php echo parseOutput($studentDataArr[0]['universityRegNo']); ?></td>
                           
                        </tr> 
                        <tr>
                            <tr>
                             <td valign="top" colspan="4">
                             <!--Contains "div" for section details-->
                             <div id="courseResultDiv">
                             </div>
                             </td>
                        </tr> 
                        </tr> 
                        </table>
                        </div>
                        <div class="dhtmlgoodies_aTab" style="overflow:auto">
                         <table width="100%" border="0" cellspacing="5" cellpadding="5">
                         
                        <tr>
                            <td valign="top">
                            <div id="marksResultsDiv">
                            <!--
                            <table width="100%" border="0" cellspacing="0" cellpadding="1">
                            
                            <tr class="rowheading">
                                <td valign="middle" height="25" width="2%" style="padding-left:15px"><b>#</b></td>
                                <td valign="middle" height="25" width="15%"><b>&nbsp;Subject</b></td>
                                <td valign="middle" width="15%">&nbsp;<b>Test Type</b></td>
                                <td valign="middle" width="15%" align="center"><b>Test Date</b></td>
                                <td valign="middle" width="15%" align="center"><b>Study Period</b></td>
                                <td valign="middle" width="15%" align="center"><b>Teacher Name</b></td>
                                <td valign="middle" width="12%" align="center"><b>Test Name</b></td>
                                <td valign="middle" width="10%" align="right" >&nbsp;<b><nobr>Max. Marks</nobr></b></td>
                                <td valign="middle" width="10%" align="right" >&nbsp;<b><nobr>Marks Scored</nobr></b></td>
                                <td valign="middle" width="10%" align="right" >&nbsp;<b>Percentage</b></td>
                                 
                            </tr>
                             
                              
                            <?php
                            /*
                            $recordCount = count($studentSubjectArray);
                            $j=0;
                            $k=0;
                            if($recordCount >0 && is_array($studentSubjectArray) ) { 
                                 $subjectName = "";     
                                for($i=0; $i<$recordCount; $i++ ) {
                                    
                                    $bg = $bg =='row0' ? 'row1' : 'row0';
                                    $marksObtained="0.00";
                                    if ($studentSubjectArray[$i]['Obtained'] >0 && $studentSubjectArray[$i]['totalMarks'] >0){
                                        $marksObtained =  ($studentSubjectArray[$i]['Obtained']/$studentSubjectArray[$i]['totalMarks'])*100;
                                        $marksObtained = number_format($marksObtained, 2, '.', ' ');
                                    }

                                    $subjectName1 = $studentSubjectArray[$i]['subjectName'].' ('.$studentSubjectArray[$i]['subjectCode'].')';

                                    if($subjectName == $subjectName1)
                                    {
                                        $subjectName1 = "";
                                        $k++;
                                        $j="";
                                    }
                                   else{
                                      $j=$i-$k+1;
                                    }
                                    if ($studentSubjectArray[$i]['Obtained']=='Not MOC'){
                                        $marksObtained="-";
                                    }
                                    if ($studentSubjectArray[$i]['Obtained']=='A'){
                                        $marksObtained="-";
                                    }
                                echo '<tr class="'.$bg.'">
                                    <td valign="top" class="padding_top" style="padding-left:15px">'.$j.'</td>
                                    <td valign="top" class="padding_top" >&nbsp;'.$subjectName1.'</td>
                                    <td valign="top" class="padding_top">'.$studentSubjectArray[$i]['examType'].' ('.$studentSubjectArray[$i]['testTypeName'].')</td>
                                    <td valign="top" class="padding_top" align="center">'.UtilityManager::formatDate($studentSubjectArray[$i]['testDate']).'</td>
                                    <td valign="top" class="padding_top" align="center">'.$studentSubjectArray[$i]['periodName'].'</td>
                                    <td valign="top" class="padding_top" align="center">'.$studentSubjectArray[$i]['employeeName'].'</td>
                                    <td valign="top" class="padding_top" align="center">'.$studentSubjectArray[$i]['testName'].'</td>
                                    <td valign="top" class="padding_top" align="right" >'.$studentSubjectArray[$i]['totalMarks'].'</td>
                                    <td valign="top" class="padding_top" align="right" >'.$studentSubjectArray[$i]['Obtained'].'</td> 
                                    <td valign="top" class="padding_top" align="right" >'.$marksObtained.'</td> 
                                     
                                    </tr>';
                                    if($subjectName1 != "")
                                        $subjectName = $subjectName1;
                                    
                                }?>


                            <?php
                            }
                            else {
                                echo '<tr><td colspan="8" align="center">No record found</td></tr>';
                            }
                            */
                            ?> 
                             

                            </table>
                            -->
                           </div> 
                            </td>
                        </tr>
                         
                        </table>
                        </div>
                        
                        <div class="dhtmlgoodies_aTab" style="overflow:auto">
                        <table width="100%" border="0" cellspacing="1" cellpadding="1">
                        <tr class="row0">
                            <td valign="top" align="left">
                            <?php 
                             //get current date
                             //$thisDate=date('Y')."-".date('m')."-".date('d');
                            ?>
                            <table width="100%" border="0" cellspacing="1" cellpadding="1" align="left">
                            <tr>
                                <td width="7%" class="contenttab_internal_rows"><b>From Date</b></td>
                                <td width="20%" valign="top" align="left" >
                                 <?php
                                       require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                       echo HtmlFunctions::getInstance()->datePicker('startDate',$thisDate);
                                 ?>
                               </td>
                                <td width="7%" class="contenttab_internal_rows"><b>To Date</b></td>
                                <td width="20%" valign="top" align="left">
                                  <?php
                                       require_once(BL_PATH.'/HtmlFunctions.inc.php');
                                       echo HtmlFunctions::getInstance()->datePicker('endDate',$thisDate);
                                 ?>
                                </td>    
                                <td valign="top" align="left"><img src="<?php echo IMG_HTTP_PATH;?>/showlist.gif" onClick="getAttendance(<?php echo $REQUEST_DATA['id']?>,document.getElementById('startDate').value,document.getElementById('endDate').value);return false;"/></td>
                            </tr>
                            </table>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top">
                            <div id="attendanceResultsDiv">
                            <!--
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                               <tr class="rowheading">
                                <td width="2%"    style=padding-left:3px;padding-right:10px"><b>#</b></td>
                                <td width="15%"   align="left" style="padding-left:5px;"><strong>Subject</strong></td>
                                <td width="10%"   align="left"><strong>Study Period </strong></td>
                                <td width="10%"   align="left"><strong>Section </strong></td>
                                <td width="18%"   align="left"><strong>Teacher Name</strong></td>
                                <td width="10%"   align="center"><strong>From </strong></td>
                                <td width="10%"   align="center"><strong>To</strong></td>
                                <td width="8%"    align="right" ><strong>Delivered</strong>
                                <td width="10%"   align="right" ><strong>Attended</strong></td>
                                <td width="20%"   align="right" ><strong>Percentage</strong></td>
                               </tr>
                               <?php
                               /*  
                                $recordCount = count($studentAttArray);
                                if($recordCount >0 && is_array($studentAttArray) ) { 
                                   //  $j = $records;
                                   for($i=0; $i<$recordCount; $i++ ) {
                                    $bg = $bg =='row0' ? 'row1' : 'row0';
                                    $percentage = "0.00";
                                    if($studentAttArray[$i]['delivered'])
                                    $percentage = number_format((($studentAttArray[$i]['attended']/$studentAttArray[$i]['delivered'])*100),2,'.','');    

                                    echo '<tr class="'.$bg.'">
                                    <td valign="top" class="padding_top" style="padding-left:15px">'.($records+$i+1).'</td>
                                    <td class="padding_top" valign="top" style="padding-left:5px;" >'.strip_slashes($studentAttArray[$i]['subject']).'</td>
                                    <td class="padding_top" valign="top" align="left">'.strip_slashes($studentAttArray[$i]['periodName']).'</td>
                                    <td class="padding_top" valign="top" align="left">'.strip_slashes($studentAttArray[$i]['sectionName']).'</td>
                                    <td class="padding_top" valign="top" align="left">'.strip_slashes($studentAttArray[$i]['employeeName']).'</td>
                                    <td class="padding_top" valign="top" align="center">'.strip_slashes($studentAttArray[$i]['fromDate']).'</td>
                                    <td class="padding_top" valign="top" align="center">'.strip_slashes($studentAttArray[$i]['toDate']).'</td>
                                    <td class="padding_top" valign="top" align="right">'.strip_slashes($studentAttArray[$i]['delivered']).'</td>
                                    <td class="padding_top" valign="top" align="right">'.strip_slashes($studentAttArray[$i]['attended']).'</td>
                                    <td class="padding_top" valign="top" align="right">'.$percentage.'</td>
                                    </tr>';
                                  }
                   
                                  if($totalEventsArray[0]['totalRecords']>RECORDS_PER_PAGE) {
                                  $bg = $bg =='row0' ? 'row1' : 'row0';
                                  require_once(BL_PATH . "/Paging.php");
                                  $paging = Paging::getInstance(RECORDS_PER_PAGE,$totalEventsArray[0]['totalRecords']);
                                  echo '<tr><td colspan="10" align="right">'.$paging->ajaxPrintLinks().'&nbsp;</td></tr>';                   
                                 }
                               ?>
                                                                                        
                        <?php }
                    else {
                       echo '<tr><td colspan="10" align="center">No record found</td></tr>';
                    }
                    */
                   ?> 
         
                </table>
                -->
               </div> <!-- end of (div id="attendanceResultsDiv") -->
              </td>
             </tr>
            </table>
          </div>                    

    <div class="dhtmlgoodies_aTab" style="overflow:auto">
     <?php
       $sessionHandler->setSessionVariable('rStudentId',trim($REQUEST_DATA['id']));
     ?>
        <table width="100%" border="0" cellspacing="5" cellpadding="0">
                       <tr>
                        <td align="right">
                          <input type="text" name="searchbox" id="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                          &nbsp;
                          <input type="image"  name="submit" src="<?php echo IMG_HTTP_PATH;?>/search.gif" title="Search"   style="margin-bottom: -5px;" onClick="refreshResourceData(document.getElementById('studyPeriod').value);"/>&nbsp;
                         </td>
                        </tr>
                        <tr>
                            <td valign="top">
                            <div id="resourceResultsDiv">
                            <!--
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr class="rowheading">
                                <td valign="middle" width="2%">&nbsp;<b>#</b></td>
                                <td valign="middle" width="10%"><b>&nbsp;Course</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-up.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=DESC&sortField='+sortField)" /></td>
                                <td valign="middle" width="15%">&nbsp;<b>Description</b></td>
                                <td valign="middle" width="10%" align="left">&nbsp;<b>Type</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=resourceName')" /></td>
                                <td valign="middle" width="8%" align="center"><b>Date</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=postedDate')" /></td>
                                <td valign="middle" width="8%" align="left">&nbsp;<b>Link</b></td>
                                <td valign="middle" width="5%" align="left">&nbsp;<b>Attachment</b></td>
                                <td valign="middle" width="10%" align="left" >&nbsp;<b>Creator</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=employeeName')" /></td>
                            </tr>
                          <?php
                          /*
                            $recordCount = count($resourceRecordArray);
                            if($recordCount >0 && is_array($resourceRecordArray) ) { 
                     
                             for($i=0; $i<$recordCount; $i++ ) {
                        
                                $bg = $bg =='row0' ? 'row1' : 'row0';
                    
                                //for file downloading
                                $fileStr='<img src="'.IMG_HTTP_PATH.'/download.gif" name="'.strip_slashes($resourceRecordArray[$i]['attachmentFile']).'" onclick="download(this.name);" title="Download File" />';    
                                //for url clicking
                                $urlStr='<a href="'.strip_slashes($resourceRecordArray[$i]['resourceUrl']).'" target="_blank">'.trim_output(strip_slashes($resourceRecordArray[$i]['resourceUrl']),40).'</a>';
                    
                                echo '<tr class="'.$bg.'">
                                <td valign="top" class="padding_top" >&nbsp;'.($records+$i+1).'</td>
                                <td class="padding_top" valign="top">&nbsp;'.strip_slashes($resourceRecordArray[$i]['subject']).'</td>
                                <td class="padding_top" valign="top">&nbsp;'.strip_slashes(trim_output($resourceRecordArray[$i]['description'],100)).'</td>
                                <td class="padding_top" valign="top">&nbsp;'.strip_slashes($resourceRecordArray[$i]['resourceName']).'</td>
                                <td class="padding_top" valign="top" align="center">'.strip_slashes($resourceRecordArray[$i]['postedDate']).'</td>
                                <td class="padding_top" valign="top">&nbsp;'.(strip_slashes($resourceRecordArray[$i]['resourceUrl'])==-1 ? '' : $urlStr).'</td>
                                <td class="padding_top" valign="top" align="center">'.(strip_slashes($resourceRecordArray[$i]['attachmentFile'])==-1 ? '' :$fileStr).'</td>
                                <td class="padding_top" valign="top">&nbsp;'.strip_slashes($resourceRecordArray[$i]['employeeName']).'</td>
                                </tr>';
                             }
                              if($resourceRecordTotalArray[0]['totalRecords']>RECORDS_PER_PAGE) {
                                  $bg = $bg =='row0' ? 'row1' : 'row0';
                                  require_once(BL_PATH . "/Paging.php");
                                  $paging = Paging::getInstance(RECORDS_PER_PAGE,$resourceRecordTotalArray[0]['totalRecords']);
                                  echo '<tr><td colspan="8" align="right">'.$paging->ajaxPrintLinks().'&nbsp;</td></tr>';                   
                              }
                            } 
                            else {
                                echo '<tr><td colspan="8" align="center">No record found</td></tr>';
                            }
                           */ 
                            ?> 
                             

                            </table>
                            -->
                           </div> 
                          </td>
                        </tr>
                      </table>                 
                   </div>

              </div>

<script type="text/javascript">
initTabs('dhtmlgoodies_tabView1',Array('Personal Details','Parents Details','Course Details','Grades Details','Attendance Details','Resource Details'),0,980,300,Array(false,false,false,false,false,false));
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
// $History: scStudentDetailContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher/ScStudentActivity
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 11/12/08   Time: 11:58a
//Updated in $/Leap/Source/Templates/Teacher/ScStudentActivity
//Added Fully Ajax Enabled Student Tabs in Teacher Module
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 11/11/08   Time: 10:43a
//Updated in $/Leap/Source/Templates/Teacher/ScStudentActivity
//Added resource sorting and paging in student tab view
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 11/05/08   Time: 4:37p
//Updated in $/Leap/Source/Templates/Teacher/ScStudentActivity
//Added Resourc eDetails view and download capability in student tab
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 10/20/08   Time: 6:13p
//Updated in $/Leap/Source/Templates/Teacher/ScStudentActivity
//Mofified attendance and grades showing
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 9/29/08    Time: 12:22p
//Updated in $/Leap/Source/Templates/Teacher/ScStudentActivity
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 9/22/08    Time: 4:36p
//Updated in $/Leap/Source/Templates/Teacher/ScStudentActivity
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 9/20/08    Time: 7:13p
//Updated in $/Leap/Source/Templates/Teacher/ScStudentActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 9/19/08    Time: 3:24p
//Updated in $/Leap/Source/Templates/Teacher/ScStudentActivity
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/16/08    Time: 1:40p
//Updated in $/Leap/Source/Templates/Teacher/ScStudentActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/15/08    Time: 4:35p
//Updated in $/Leap/Source/Templates/Teacher/ScStudentActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/10/08    Time: 6:37p
//Created in $/Leap/Source/Templates/Teacher/ScStudentActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/09/08    Time: 5:19p
//Created in $/Leap/Source/Templates/Teacher/StudentActivity
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
 
    


