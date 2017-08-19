<?php
//-------------------------------------------------------
// Purpose: To Display student marks list
//
// Author : Dipanjan Bhattacharjee
// Created on : (31.07.2008 )
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
                <td valign="top">Student Activities &nbsp;&raquo;&nbsp;Student Marks</td>
                 <td align="right" style="padding-right:10px">
                  <a href="searchStudent.php?class=<?php echo $REQUEST_DATA['class']?>&subject=<?php echo $REQUEST_DATA['subject']?>&group=<?php echo $REQUEST_DATA['group']?>&studentRollNo=<?php echo $REQUEST_DATA['studentRollNo']?>" ><U>Back</U></a>
				 </td>
            </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="590">
            <tr>
             <td valign="top" class="content" valign="top">

             <table width="100%" border="0" cellspacing="0" cellpadding="0" height="590" >
			 
             <tr>
                <td class="contenttab_border" height="20" valign="top">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                    <tr>
                        <td class="contenttab_row1"><span class="content_title">Student Detail:</span>
						&nbsp;<B><U>Name:</U></B>&nbsp;<?php echo parseOutput($studentDataArr[0]['firstName'])." ".parseOutput($studentDataArr[0]['lastName']);?>
						&nbsp;&nbsp;<B><U>University:</U></B>&nbsp;<?php echo parseOutput($studentCourseDataArr[0]['universityName']) ; ?>
						&nbsp;&nbsp;<B><U>Degree:</U></B>&nbsp;<?php echo parseOutput($studentCourseDataArr[0]['degreeName']); ?>
						&nbsp;&nbsp;<B><U>Branch:</U></B>&nbsp;<?php echo parseOutput($studentCourseDataArr[0]['branchName']) ; ?>
						&nbsp;&nbsp;<B><U>Batch:</U></B>&nbsp;<?php echo parseOutput($studentCourseDataArr[0]['batchName']); ?>
						&nbsp;&nbsp;<B><U>Admission Date:</U></B>&nbsp;<?php echo parseOutput((UtilityManager::formatDate($studentDataArr[0]['dateOfAdmission'])));?></td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" height="570">
                <!--Student Marks Part-->
                <table width="100%" border="0" cellspacing="5" cellpadding="5">
                    <tr>
                       <td valign="top">
                         <table width="100%" border="0" cellspacing="1" cellpadding="1">
                          <tr class="rowheading">
                                <td valign="middle" height="25" width="4%"><b>&nbsp;Sr No</b></td>
                                <td valign="middle" height="25" width="20%"><b>&nbsp;Subject</b></td>
                                <td valign="middle" width="20%">&nbsp;<b>Type</b></td>
                                <td valign="middle">&nbsp;<b>Total Marks</b></td>
                                <td valign="middle">&nbsp;<b>Marks Obtained</b></td>
                                <td valign="middle">&nbsp;<b>Percentage</b></td>
                          </tr>
                          <?php
                            $recordCount = count($studentSubjectArray);
                            if($recordCount >0 && is_array($studentSubjectArray) ) { 
                                 
                                for($i=0; $i<$recordCount; $i++ ) {
                                    
                                    $bg = $bg =='row0' ? 'row1' : 'row0';
                                    $studentMarksArray = $teacherManager->getStudentMarksClass($studentSubjectArray[$i]['subjectId'],$REQUEST_DATA['id']);
                                    //print_r($studentMarksArray);
                                    //echo "<br><br>";
                                echo '<tr class="'.$bg.'">
                                    <td valign="top" class="padding_top" >'.($records+$i+1).'</td>
                                    <td valign="top" class="padding_top" >'.$studentSubjectArray[$i]['subjectName'].'</td>
                                    <td valign="top" colspan="4">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">';
                                    $recordCount1 = count($studentMarksArray);
                                
                                if($recordCount1 >0 && is_array($studentMarksArray) ) { 
                                   for($j=0; $j<$recordCount1; $j++ ) {
                                    $percentage = number_format((($studentMarksArray[$j]['MarksObtained']/$studentMarksArray[$j]['TotalMarks'])*100),2,'.','');
                                    echo '<tr class="'.$bg.'">
                                    <td valign="top" class="padding_top" width="27%" >'.$studentMarksArray[$j]['type'].'</td>
                                    <td valign="top" class="padding_top" width="23%" >'.$studentMarksArray[$j]['TotalMarks'].'</td>
                                    <td valign="top" class="padding_top" width="30%" >'.$studentMarksArray[$j]['MarksObtained'].'</td> 
                                    <td valign="top" class="padding_top" >'.$percentage.'</td>';
                                    echo '</tr>';
                                }
                              }
                             else
                               echo '<tr class="'.$bg.'">
                                     
                                    <td valign="top" class="padding_top" width="27%" >--</td>
                                    <td valign="top" class="padding_top" width="23%" >--</td>
                                    <td valign="top" class="padding_top" width="30%" >--</td> 
                                    <td valign="top" class="padding_top" >--</td>';
                                    echo '</tr>';
                                    echo '</table>
                                    </td>
                                    </tr>';
                                }
                            }
                            else {
                                echo '<tr><td colspan="8" align="center">No record found</td></tr>';
                            }
                             ?> 
                             
                             

                            </table>
                            </td>
                        </tr>
                         
                        </table>
               <!--Student Marks Part(ends)-->                        
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
<?php 
// $History: studentDetailMarksContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher/StudentActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/01/08    Time: 12:11p
//Updated in $/Leap/Source/Templates/Teacher/StudentActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/31/08    Time: 7:27p
//Created in $/Leap/Source/Templates/Teacher/StudentActivity
?>
 
    


