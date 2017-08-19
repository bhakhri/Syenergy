<?php
//This file is used as printing version for student profile.
//
// Author :Rajeev Aggarwal
// Created on : 14-08-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	global $sessionHandler;
	$optionalField = $sessionHandler->getSessionVariable('INSTITUTE_ADMIT_STUDENT_OPTIONAL_FIELD');

	require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

	$classId = trim($REQUEST_DATA['classId']);
    $studentId=trim($REQUEST_DATA['studentId']);

    if($studentId=='' or $classId==''){
        echo 'Required Parameters Missing';
        die;
    }

    //check for alumni class
    $alumnniArray=$studentManager->checkAlumniClass($classId);
    $isAlumni=0;
    if($alumnniArray[0]['isAlumni']==1){
        $isAlumni=1;
        //find old classes of this student
        $allClassesArray=$studentManager->getStudentAllClasses($studentId);
        if(count($allClassesArray)>0 and is_array($allClassesArray)){
            $classId=UtilityManager::makeCSList($allClassesArray,'classId');
        }
        else{
            echo 'No Class Information Found';
            die;
        }
    }

	$studentDataArr = $studentManager->getStudentInformationList($REQUEST_DATA['studentId']);

	require_once(MODEL_PATH . "/TimeTableManager.inc.php");
	$timeTableManager = TimeTableManager::getInstance();

	require_once($FE . "/Library/HtmlFunctions.inc.php");
	$htmlFunctionsManager = HtmlFunctions::getInstance();
	global $sessionHandler;
	$timetableFormat = $sessionHandler->getSessionVariable('TIMETABLE_FORMAT');
	//$orderBy =($timetableFormat == 1) ? " tt.daysOfWeek, length(p.periodNumber)+0,p.periodNumber" : " length(p.periodNumber)+0,p.periodNumber,tt.daysOfWeek";
    $orderBy =($timetableFormat == 1) ? " daysOfWeek, length(periodNumber)+0,periodNumber" : " length(periodNumber)+0,periodNumber,daysOfWeek";

	$studentRecordArray = $studentManager->getStudentTimeTable($REQUEST_DATA['studentId'],$classId,$classId,$orderBy);

	/* START: Student Marks*/
	$studentMarksArray = $studentManager->getStudentMarksCount($REQUEST_DATA['studentId'],$classId);
	$limit = "LIMIT 0,".$studentMarksArray[0]['totalRecords'];
	$studentSubjectArray = $studentManager->getStudentMarks($REQUEST_DATA['studentId'],$classId,' studyPeriod',$limit);
	/* END: Student Marks*/

	/* START: Student Fees*/
	$totalArray          = $studentManager->getStudentTotalFeesClass($REQUEST_DATA['studentId'],$classId);
	$limit = "LIMIT 0,".$studentMarksArray[0]['totalRecords'];
	$feesClassArr = $studentManager->getStudentFeesClass($REQUEST_DATA['studentId'],$classId,' feeReceiptId DESC',$limit);
	/* END: Student Fees*/

	/* function to fetch student attendance array*/
	require_once(MODEL_PATH."/CommonQueryManager.inc.php");
	$commonAttendanceArr = CommonQueryManager::getInstance();
	//$totalArray = $commonAttendanceArr->countStudentAttendance($REQUEST_DATA['studentId'],$classId,"");
	//$limit = "LIMIT 0,".count($totalArray);
	$studentAttendanceArray = $commonAttendanceArr->getStudentAttendance($REQUEST_DATA['studentId'],$classId,'',' AND su.hasAttendance = 1 ',' t.subjectId');
	/* function to fetch student attendance array*/

	/* function to fetch student section array*/
	$totalArray = $studentManager->getStudentGroupCount($REQUEST_DATA['studentId'],$classId);
    if(count($totalArray)>0)
	$limit = "LIMIT 0,".count($totalArray);
	$studentSectionArray = $studentManager->getStudentGroups($REQUEST_DATA['studentId'],$classId,'studyPeriod',$limit);
	/* function to fetch student section array*/


	/* function to fetch student resource array*/
	$studentResourceArray = $studentManager->getStudentCourseResourceList($REQUEST_DATA['studentId'],$classId);
	/* function to fetch student resource array*/

	/* function to fetch student result array*/
	//$totalArray          = $studentManager->getTotalStudentFinalResult($REQUEST_DATA['studentId'],$classId);
	//$totalRecords = count($totalArray);

    //$limit = "LIMIT 0,".$totalRecords;
	//$resultRecordArray = $studentManager->getStudentFinalResultList($REQUEST_DATA['studentId'],$classId,' a.subjectId',$limit);
    $resultRecordArray = $studentManager->getStudentFinalResultListAdv($REQUEST_DATA['studentId'],$classId,' periodName','');


	/* function to fetch student result array*/

	/* function to fetch student Offense*/
	$offenseRecordArray = $studentManager->getStudentOffenceList($REQUEST_DATA['studentId'],$classId,'off.offenseName','');

	/* function to fetch student Previous Academic*/
	$academicRecordArray = $studentManager->getStudentAcademicList( " WHERE sa.studentId = ".$REQUEST_DATA['studentId'],'previousClassId');

	/* function to fetch student Offense*/
	if($studentRecordArray[0]['timeTableLabelId'])
		$periodArray = $timeTableManager->getTimeTablePeriodList(' tt.timeTableLabelId = '.$studentRecordArray[0]['timeTableLabelId']);

	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
	$reportManager->setReportInformation("For ".$studentDataArr[0]['firstName'].' '.$studentDataArr[0]['lastName'].' of '.$studentDataArr[0]['className']." As On $formattedDate ");
	$reportManager->setReportHeading("Student Profile");
?>

	<table border="0" cellspacing="0" cellpadding="0" width="90%" align="center">
	<tr>
		<td align="left" width="25%" class=""><?php echo $reportManager->showHeader();?></td>
		<th align="center" width="50%" <?php echo $reportManager->getReportTitleStyle();?> valign="top">
			<table border="0" cellspacing="0" cellpadding="0">
			<tr>
				<th align="center" colspan="1" nowrap <?php echo $reportManager->getReportTitleStyle();?>><?php echo $reportManager->getInstituteName(); ?></th>
			</tr>
			<tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?> valign="bottom"><?php echo $reportManager->reportHeading; ?></th></tr>
			</table>
		</th>
		<td align="right" colspan="1" width="25%" valign="top">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right" width="50%">Date :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> nowrap="nowrap"><?php echo date("d-M-y");?></td>
				</tr>
				<tr>
					<td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right">Time :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> nowrap="nowrap"><?php echo date("h:i:s A"); ?></td>
				</tr>
			</table>
		</td>
	</tr>

	<tr><th colspan="3" <?php echo $reportManager->getReportInformationStyle(); ?>><?php echo $reportManager->getReportInformation(); ?></th></tr>
	</table><br><table border='0' cellspacing='0' class="reportTableBorder" width="90%" align="center">
	   <?php 
			// by me
	        if ($sessionHandler->getSessionVariable('PERSONAL_INFO')==1)  { 
		?>
		<tr>
			<td colspan='4' height="20" <?php echo $reportManager->getReportDataStyle()?>><B><U>PERSONAL DETAILS</U></B></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle() ?> width="17%" height="20"><nobr><b>Name: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle() ?> width="50%">
				<?php echo $studentDataArr[0]['firstName'].' '.$studentDataArr[0]['lastName'];?>
			</td>
			<td rowspan="10" align="left" colspan="2" valign="top">
						<?php if($studentDataArr[0]['studentPhoto']){

							echo "<img src='".STUDENT_PHOTO_PATH."/".$studentDataArr[0][studentPhoto]."' width='170' height='190'/>";
						}
						else
							echo "<img src='".IMG_HTTP_PATH."/notfound.jpg' width='170' height='190'/>";
						?>
						</td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Date Of Birth: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?> ><?php echo (UtilityManager::formatDate($studentDataArr[0]['dateOfBirth']))?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Roll No. : </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?> ><?php echo ( $studentDataArr[0]['rollNo'] != "" ) ? $studentDataArr[0]['rollNo'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Email: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?> ><?php echo ( $studentDataArr[0]['studentEmail'] != "" && $studentDataArr[0]['studentEmail'] != "NULL" ) ? $studentDataArr[0]['studentEmail'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Institute Reg No. : </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?> ><?php echo ( $studentDataArr[0]['regNo'] != "" && $studentDataArr[0]['regNo'] != "NULL") ? $studentDataArr[0]['regNo'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>University Roll No. : </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?> ><?php echo ( $studentDataArr[0]['universityRollNo'] != "" && $studentDataArr[0]['universityRollNo'] != "NULL" ) ? $studentDataArr[0]['universityRollNo'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
					<?php
                    global $sessionHandler;
                    if($optionalField == 0){
                    ?>
			<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Univ. Reg. No. : </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['universityRegNo'] != "" && $studentDataArr[0]['universityRegNo'] != "NULL" ) ? $studentDataArr[0]['universityRegNo'] : NOT_APPLICABLE_STRING;?></td>
		    </tr>

					<?php } ?>

		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Gender: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php if($studentDataArr[0]['studentGender']=="M") echo "Male"; else echo "Female";?>
			</td>
		</tr>

					<?php
                    global $sessionHandler;
                    if($optionalField == 1){
                    ?>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Current Organization: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo($studentDataArr[0]['currentOrg'] != ""  && $studentDataArr[0]['currentOrg'] != "NULL" ) ? $studentDataArr[0]['currentOrg'] : NOT_APPLICABLE_STRING;?></td>
			</td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Designation: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo($studentDataArr[0]['companyDesignation'] != ""  && $studentDataArr[0]['companyDesignation'] != "NULL" ) ? $studentDataArr[0]['companyDesignation'] : NOT_APPLICABLE_STRING;?></td>
			</td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Work Email: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo($studentDataArr[0]['workEmail'] != ""  && $studentDataArr[0]['workEmail'] != "NULL" ) ? $studentDataArr[0]['workEmail'] : NOT_APPLICABLE_STRING;?></td>
			</td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Office Phone No: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo($studentDataArr[0]['officeContactNo'] != ""  && $studentDataArr[0]['officeContactNo'] != "NULL" ) ? $studentDataArr[0]['officeContactNo'] : NOT_APPLICABLE_STRING;?></td>
			</td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Role: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo($studentDataArr[0]['role'] != ""  && $studentDataArr[0]['role'] != "NULL" ) ? $studentDataArr[0]['role'] : NOT_APPLICABLE_STRING;?></td>
			</td>
		</tr>

		<?php } ?>
		<tr>

			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Nationality: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo $studentDataArr[0]['nationalityName'];?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Domicile: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo (trim($studentDataArr[0]['stateName'])!="" && trim($studentDataArr[0]['stateName'])!="NULL") ? trim($studentDataArr[0]['stateName']) : NOT_APPLICABLE_STRING;?></td>

		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Contact No. : </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['studentPhone'] != "" && $studentDataArr[0]['studentPhone'] != "NULL") ? $studentDataArr[0]['studentPhone'] : NOT_APPLICABLE_STRING;?></td>
			<td <?php echo $reportManager->getReportDataStyle()?> width="17%" ><nobr><b>Mobile No. : </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?> width="30%"><?php echo ( $studentDataArr[0]['studentMobileNo'] != ""  && $studentDataArr[0]['studentMobileNo'] != "NULL") ? $studentDataArr[0]['studentMobileNo'] : NOT_APPLICABLE_STRING;?></td>
		</tr>

 					<?php
                    global $sessionHandler;
                    if($optionalField == 0){
                    ?>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Exam: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['compExamBy'] != ""   && $studentDataArr[0]['compExamBy'] != "NULL") ? $results[$studentDataArr[0]['compExamBy']] : NOT_APPLICABLE_STRING;?></td>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Ent. Exam Roll No. : </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['compExamRollNo'] != "" && $studentDataArr[0]['compExamRollNo'] != "NULL" ) ? $studentDataArr[0]['compExamRollNo'] : NOT_APPLICABLE_STRING;?></td>
		</tr>

		<?php  } ?>
		<tr>

 					<?php
                    global $sessionHandler;
                    if($optionalField == 0){
                    ?>
			<td <?php echo $reportManager->getReportDataStyle()?>><nobr><b>Rank: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['compExamRank'] != "" && $studentDataArr[0]['compExamRank'] != "NULL") ? $studentDataArr[0]['compExamRank'] : NOT_APPLICABLE_STRING;?></td>
			<?php } ?>
			<td <?php echo $reportManager->getReportDataStyle()?>><nobr><b>Category: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>>
             <?php
               if(trim($studentDataArr[0]['quotaId'])!=''){
                   $quotaNameArray=$studentManager->getStudentDetailedQuota(trim($studentDataArr[0]['quotaId']));
                   if($quotaNameArray[0]['quotaName']!=''){
                       echo $quotaNameArray[0]['quotaName'];
                   }
                   else{
                       echo NOT_APPLICABLE_STRING;
                   }
               }
               else{
                   echo NOT_APPLICABLE_STRING;
               }

             ?>
            </td>
		</tr>
				<?php
                 global $sessionHandler;
                 if($optionalField == 0){
                 ?>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?>><b>Is LEET:</b></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php if($studentDataArr[0]['isLeet']) echo "Yes"; else echo "No";?></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><b>Blood Group:</b></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php if($studentDataArr[0]['studentBloodGroup']) echo $bloodResults[$studentDataArr[0]['studentBloodGroup']]; else echo NOT_APPLICABLE_STRING;?></td>
		</tr>
					<?php } ?>
		<tr>
						<?php
                        global $sessionHandler;
                        if($optionalField == 0){
                        ?>
			<td <?php echo $reportManager->getReportDataStyle()?>><b>Sports Activity:</b></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php if($studentDataArr[0]['studentSportsActivity']) echo $studentDataArr[0]['studentSportsActivity']; else echo NOT_APPLICABLE_STRING;?></td>

			<?php } ?>
			<td <?php echo $reportManager->getReportDataStyle()?>><b>Fee Receipt No.:</b></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php if($studentDataArr[0]['feeReceiptNo']) echo $studentDataArr[0]['feeReceiptNo']; else echo NOT_APPLICABLE_STRING;?></td>

		</tr>
		<?php
                            global $sessionHandler;
                            if($optionalField == 0){
                            ?>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> colspan='2'><b>Reference number/ Reference name:</b>&nbsp;<?php if($studentDataArr[0]['referenceName']) echo $studentDataArr[0]['referenceName']; else echo NOT_APPLICABLE_STRING;?></td>


<?php }   


?>
		</tr>
		<tr>
			<td colspan='2' height="20" <?php echo $reportManager->getReportDataStyle()?>><B><U>CORRESPONDENCE ADDRESS</U></B></td>
			<td colspan='2' height="20" <?php echo $reportManager->getReportDataStyle()?>><B><U>PERMANENT ADDRESS</U></B></td>
		</tr>
	
			<tr>
			<td <?php echo $reportManager->getReportDataStyle() ?> height="20"><nobr><b>CorrespondingAddress: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['corrAddress1'] != "" && $studentDataArr[0]['corrAddress1'] != "NULL" ) ? $studentDataArr[0]['corrAddress1'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>CorrespondingAddress2: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['corrAddress2'] != "" && $studentDataArr[0]['corrAddress2'] != "NULL" ) ? $studentDataArr[0]['corrAddress2'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>CorrCity: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['corrCity'] != "" && $studentDataArr[0]['corrCity'] != "NULL" ) ? $studentDataArr[0]['corrCity'] : NOT_APPLICABLE_STRING;?></td>
		</tr>	
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>CorrCountry: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['corrCountry'] != "" && $studentDataArr[0]['corrCountry'] != "NULL" ) ? $studentDataArr[0]['corrCountry'] : NOT_APPLICABLE_STRING;   ?></td>
		</tr>		
        <tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>CorrPinCode: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['corrPinCode'] != "" && $studentDataArr[0]['corrPinCode'] != "NULL" ) ? $studentDataArr[0]['corrPinCode'] : NOT_APPLICABLE_STRING;?></td>
		</tr>	

		<!--	 
			 echo ( $studentDataArr[0]['corrAddress1'] != "" && $studentDataArr[0]['corrAddress1'] != "NULL" ) ? $studentDataArr[0]['corrAddress1']."<br>" : " ";
-->
			<!--echo ( $studentDataArr[0]['corrAddress2'] != "" && $studentDataArr[0]['corrAddress2'] != "NULL" ) ? " ".$studentDataArr[0]['corrAddress2']."<br>" : " ";

			echo ( $studentDataArr[0]['corrCity'] != "" && $studentDataArr[0]['corrCity'] != "NULL" ) ? " ".$studentDataArr[0]['corrCity'] : " ";

			echo ( $studentDataArr[0]['corrState'] != "" && $studentDataArr[0]['corrState'] != "NULL") ? " ".$studentDataArr[0]['corrState'] : " ";

			echo ( $studentDataArr[0]['corrCountry'] != "" &&  $studentDataArr[0]['corrCountry'] != "NULL") ? " ".$studentDataArr[0]['corrCountry'] : " ";

			echo ( $studentDataArr[0]['corrPinCode'] != "" &&  $studentDataArr[0]['corrPinCode'] != "NULL" ) ? " "."-".$studentDataArr[0]['corrPinCode'] : " ";

			--></td>
			<td <?php echo $reportManager->getReportDataStyle()?> colspan="2" valign="top" width="60%">
			<!--
		/*	echo ( $studentDataArr[0]['permAddress1'] != "" &&  $studentDataArr[0]['permAddress1'] != "NULL" ) ? $studentDataArr[0]['permAddress1']."<br>" : " ";

			echo ( $studentDataArr[0]['permAddress2'] != "" && $studentDataArr[0]['permAddress2'] != "NULL" ) ? " ".$studentDataArr[0]['permAddress2']."<br>" : " ";

			echo ( $studentDataArr[0]['permCity'] != "" &&  $studentDataArr[0]['permCity'] != "NULL" ) ? " ".$studentDataArr[0]['permCity'] : " ";

			echo ( $studentDataArr[0]['permState'] != "" &&  $studentDataArr[0]['permState'] != "NULL" ) ? " ".$studentDataArr[0]['permState'] : " ";

			echo ( $studentDataArr[0]['permCountry'] != "" && $studentDataArr[0]['permCountry'] != "NULL" ) ? " ".$studentDataArr[0]['permCountry'] : " ";

			echo ( $studentDataArr[0]['permPinCode'] != "" && $studentDataArr[0]['permPinCode'] != "NULL" ) ? " "."-".$studentDataArr[0]['permPinCode'] : " ";
			*/
			-->
			</td>
		</tr>
				 <?php

                 global $sessionHandler;
                 if($optionalField == 1){
                 ?>
		<tr>
			<td colspan='2' height="20" <?php echo $reportManager->getReportDataStyle()?>><B><U>PRESENT ADDRESS</U></B></td>
		</tr>
		<tr>
		<td colspan="2" <?php echo $reportManager->getReportDataStyle()?> height="20" valign="top" width="40%"><?php
			echo ( $studentDataArr[0]['presentAddress1'] != "" && $studentDataArr[0]['presentAddress1'] != "NULL" ) ? $studentDataArr[0]['presentAddress1']."<br>" : " ";
			echo ( $studentDataArr[0]['presentAddress2'] != "" && $studentDataArr[0]['corrAddress2'] != "NULL" ) ? " ".$studentDataArr[0]['presentAddress2']."<br>" : " ";
			echo ( $studentDataArr[0]['presentCity'] != "" && $studentDataArr[0]['presentCity'] != "NULL" ) ? " ".$studentDataArr[0]['presentCity'] : " ";
			echo ( $studentDataArr[0]['presentState'] != "" && $studentDataArr[0]['presentState'] != "NULL") ? " ".$studentDataArr[0]['presentState'] : " ";
			echo ( $studentDataArr[0]['presentCountry'] != "" &&  $studentDataArr[0]['presentCountry'] != "NULL") ? " ".$studentDataArr[0]['presentCountry'] : " ";
			echo ( $studentDataArr[0]['presentPinCode'] != "" &&  $studentDataArr[0]['presentPinCode'] != "NULL" ) ? " "."-".$studentDataArr[0]['presentPinCode'] : " ";
			?>  
			</td>
		</tr>

		<tr>
			<td colspan='2' height="20" <?php echo $reportManager->getReportDataStyle()?>><B><U>Spouse / Emergency Contact Details</U></B></td>
		</tr>
		<tr>
		<td  <?php echo $reportManager->getReportDataStyle()?>> <nobr><b>Name: </b></nobr></td>
		<td  <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['spouseName'] != "" && $studentDataArr[0]['spouseName'] != "NULL") ? $studentDataArr[0]['spouseName'] : NOT_APPLICABLE_STRING;?></td>
		<td <?php echo $reportManager->getReportDataStyle()?> ><nobr><b>Relation: </b></nobr></td>
		<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['spouseRelation'] != "" && $studentDataArr[0]['spouseRelation'] != "NULL") ? $studentDataArr[0]['spouseRelation'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
		<td <?php echo $reportManager->getReportDataStyle()?> width="17%" height="20"><nobr><b>Email: </b></nobr></td>
		<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['spouseEmail'] != "" && $studentDataArr[0]['spouseEmail'] != "NULL") ? $studentDataArr[0]['spouseEmail'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
		<td <?php echo $reportManager->getReportDataStyle()?> width="17%" height="20"><nobr><b>Spouse Address: </b></nobr></td>
		</tr>
		<tr>
		<td colspan="2" <?php echo $reportManager->getReportDataStyle()?> height="20" valign="top" width="40%"><?php
			echo ( $studentDataArr[0]['spouseAddress1'] != "" && $studentDataArr[0]['spouseAddress1'] != "NULL" ) ? $studentDataArr[0]['spouseAddress1']."<br>" : " ";

			echo ( $studentDataArr[0]['spouseAddress2'] != "" && $studentDataArr[0]['spouseAddress2'] != "NULL" ) ? " ".$studentDataArr[0]['spouseAddress2']."<br>" : " ";

			echo ( $studentDataArr[0]['spouseCity'] != "" && $studentDataArr[0]['spouseCity'] != "NULL" ) ? " ".$studentDataArr[0]['spouseCity'] : " ";

			echo ( $studentDataArr[0]['spouseState'] != "" && $studentDataArr[0]['spouseState'] != "NULL") ? " ".$studentDataArr[0]['spouseState'] : " ";

			echo ( $studentDataArr[0]['spouseCountry'] != "" &&  $studentDataArr[0]['spouseCountry'] != "NULL") ? " ".$studentDataArr[0]['spouseCountry'] : " ";

			echo ( $studentDataArr[0]['spousePinCode'] != "" &&  $studentDataArr[0]['spousePinCode'] != "NULL" ) ? " "."-".$studentDataArr[0]['spousePinCode'] : " ";

			?></td>
			</tr>
<!--<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Date Of Birth: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo (UtilityManager::formatDate($studentDataArr[0]['dateOfBirth']))?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Roll No. : </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['rollNo'] != "" ) ? $studentDataArr[0]['rollNo'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Email: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['studentEmail'] != "" && $studentDataArr[0]['studentEmail'] != "NULL" ) ? $studentDataArr[0]['studentEmail'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Institute Reg No. : </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['regNo'] != "" && $studentDataArr[0]['regNo'] != "NULL") ? $studentDataArr[0]['regNo'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>University Roll No. : </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['universityRollNo'] != "" && $studentDataArr[0]['universityRollNo'] != "NULL" ) ? $studentDataArr[0]['universityRollNo'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
-->

		<?php } ?>

		<tr>
			<td valign="top" height="5" colspan='4'></td>
		</tr>
        <?php }
         if ($sessionHandler->getSessionVariable('PARENTS_INFO')==1)  {         
			 ?>
		<tr>
			<td colspan='4' height="20" <?php echo $reportManager->getReportDataStyle()?>><B><U>PARENTS DETAILS</U></B></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20" colspan='4'><B><U>Father Details</U></B></td>
		</tr>
		    <tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Father Name: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo $titleResults[$studentDataArr[0]['fatherTitle']].' '.$studentDataArr[0]['fatherName'];?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Father Occupation : </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['fatherOccupation'] != "" && $studentDataArr[0]['fatherOccupation'] != "NULL" ) ? $studentDataArr[0]['fatherOccupation'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Mobile No: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['fatherMobileNo'] != "" && $studentDataArr[0]['fatherMobileNo'] != "NULL" ) ? $studentDataArr[0]['fatherMobileNo'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Email: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['fatherEmail'] != "" && $studentDataArr[0]['fatherEmail'] != "NULL") ? $studentDataArr[0]['fatherEmail'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Address1: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['fatherAddress1'] != "" && $studentDataArr[0]['fatherAddress1'] != "NULL" ) ? $studentDataArr[0]['fatherAddress1'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Address2: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['fatherAddress2'] != "" && $studentDataArr[0]['fatherAddress2'] != "NULL" ) ? $studentDataArr[0]['fatherAddress2'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>City: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['fatherCity'] != "" && $studentDataArr[0]['fatherCity'] != "NULL" ) ? $studentDataArr[0]['fatherCity'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>State: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['fatherState'] != "" && $studentDataArr[0]['fatherState'] != "NULL" ) ? $studentDataArr[0]['fatherState'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Country: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['fatherCountry'] != "" && $studentDataArr[0]['fatherCountry'] != "NULL" ) ? $studentDataArr[0]['fatherCountry'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>PinCode: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['fatherPinCode'] != "" && $studentDataArr[0]['fatherPinCode'] != "NULL" ) ? $studentDataArr[0]['fatherPinCode'] : NOT_APPLICABLE_STRING;?></td>
		</tr>        		
			<?php
		/*	echo $titleResults[$studentDataArr[0]['fatherTitle']].' '.$studentDataArr[0]['fatherName'];
			echo ( $studentDataArr[0]['fatherOccupation'] != "" ) ? " (".$studentDataArr[0]['fatherOccupation'].")"."<br>" : " ";
			echo ( $studentDataArr[0]['fatherMobileNo'] != "" &&  $studentDataArr[0]['fatherMobileNo'] != "NULL" ) ? " ".$studentDataArr[0]['fatherMobileNo']."<br>" : " ";
			echo ( $studentDataArr[0]['fatherEmail'] != "" && $studentDataArr[0]['fatherEmail'] != "NULL" ) ? " ".$studentDataArr[0]['fatherEmail'] : " ";
			echo ( $studentDataArr[0]['fatherAddress1'] != "" &&  $studentDataArr[0]['fatherAddress1'] != "NULL" ) ? "<br>".$studentDataArr[0]['fatherAddress1'] : " ";
			echo ( $studentDataArr[0]['fatherAddress2'] != "" &&  $studentDataArr[0]['fatherAddress2'] != "NULL" ) ? "<br>".$studentDataArr[0]['fatherAddress2'] : " ";
			echo ($studentDataArr[0]['fatherCity'] != "" && $studentDataArr[0]['fatherCity'] != "NULL") ? " <br>".$studentDataArr[0]['fatherCity'] : " ";
			echo ($studentDataArr[0]['fatherState'] != "" && $studentDataArr[0]['fatherState'] != "NULL") ? " ".$studentDataArr[0]['fatherState'] : " ";
			echo ($studentDataArr[0]['fatherCountry'] != "" && $studentDataArr[0]['fatherCountry'] != "NULL") ? " ".$studentDataArr[0]['fatherCountry']: " ";
			echo ($studentDataArr[0]['fatherPinCode'] != "" && $studentDataArr[0]['fatherPinCode'] != "NULL") ? " "."-".$studentDataArr[0]['fatherPinCode'] : " ";*/
			?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20" colspan='4'><B><U>Mother Details</U></B></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Mother Name: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['motherName'] != "" && $studentDataArr[0]['motherName'] != "NULL" ) ? $studentDataArr[0]['motherName'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Occupation: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['motherOccupation'] != "" && $studentDataArr[0]['motherOccupation'] != "NULL" ) ? $studentDataArr[0]['motherOccupation'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>MobileNo: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['motherMobileNo'] != "" && $studentDataArr[0]['motherMobileNo'] != "NULL" ) ? $studentDataArr[0]['motherMobileNo'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Email: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['motherEmail'] != "" && $studentDataArr[0]['motherEmail'] != "NULL" ) ? $studentDataArr[0]['motherEmail'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Address1: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['motherAddress1'] != "" && $studentDataArr[0]['motherAddress1'] != "NULL" ) ? $studentDataArr[0]['motherAddress1'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Address2: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['motherAddress2'] != "" && $studentDataArr[0]['motherAddress2'] != "NULL" ) ? $studentDataArr[0]['motherAddress2'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
	    <tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>City: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['motherCity'] != "" && $studentDataArr[0]['motherCity'] != "NULL" ) ? $studentDataArr[0]['motherCity'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>State: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['motherState'] != "" && $studentDataArr[0]['motherState'] != "NULL" ) ? $studentDataArr[0]['motherState'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Country: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['motherCountry'] != "" && $studentDataArr[0]['motherCountry'] != "NULL" ) ? $studentDataArr[0]['motherCountry'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>PinCode: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['motherPinCode'] != "" && $studentDataArr[0]['motherPinCode'] != "NULL" ) ? $studentDataArr[0]['motherPinCode'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<!--	
			echo 'Mrs. '.$studentDataArr[0]['motherName'];
			echo ( $studentDataArr[0]['motherOccupation'] != "" ) ? " (".$studentDataArr[0]['motherOccupation'].")"."<br>" : " ";
			echo ( $studentDataArr[0]['motherMobileNo'] != "" ) ? " ".$studentDataArr[0]['motherMobileNo']."<br>" : " ";
			echo ( $studentDataArr[0]['motherEmail'] != "" && $studentDataArr[0]['motherEmail'] != "NULL" ) ? " ".$studentDataArr[0]['motherEmail'] : " ";
			echo ( $studentDataArr[0]['motherAddress1'] != "" &&  $studentDataArr[0]['motherAddress1'] != "NULL" ) ? "<br>".$studentDataArr[0]['motherAddress1'] : " ";
			echo ( $studentDataArr[0]['motherAddress2'] != "" &&  $studentDataArr[0]['motherAddress2'] != "NULL" ) ? "<br>".$studentDataArr[0]['motherAddress2'] : " ";

			echo ($studentDataArr[0]['motherCity'] != "" && $studentDataArr[0]['motherCity'] != "NULL") ? " <br>".$studentDataArr[0]['motherCity'] : " ";
			echo ($studentDataArr[0]['motherState'] != "" && $studentDataArr[0]['motherState'] != "NULL") ? " ".$studentDataArr[0]['motherState'] : " ";
			echo ($studentDataArr[0]['motherCountry'] != "" && $studentDataArr[0]['motherCountry'] != "NULL") ? " ".$studentDataArr[0]['motherCountry']: " ";
			echo ($studentDataArr[0]['motherPinCode'] != "" && $studentDataArr[0]['motherPinCode'] != "NULL") ? " "."-".$studentDataArr[0]['motherPinCode'] : " ";
			</td>
		-->
		</tr>
		<?php
		 if($studentDataArr[0]['guardianName'])
		 {
		?>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20" colspan='4'><B><U>Guardian Details</U></B></td>
		</tr>
              <tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Gaurdian Name: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo $titleResults[$studentDataArr[0]['guardianTitle']].' '.$studentDataArr[0]['guardianName'];?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Gaurdian Occupation: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['guardianOccupation'] != "" && $studentDataArr[0]['guardianOccupation'] != "NULL" ) ? $studentDataArr[0]['guardianOccupation'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>MobileNo: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['guardianMobileNo'] != "" && $studentDataArr[0]['guardianMobileNo'] != "NULL" ) ? $studentDataArr[0]['guardianMobileNo'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Email: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['guardianEmail'] != "" && $studentDataArr[0]['guardianEmail'] != "NULL" ) ? $studentDataArr[0]['guardianEmail'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Address1: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['guardianAddress1'] != "" && $studentDataArr[0]['guardianAddress1'] != "NULL" ) ? $studentDataArr[0]['guardianAddress1'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Address2: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['guardianAddress2'] != "" && $studentDataArr[0]['guardianAddress2'] != "NULL" ) ? $studentDataArr[0]['motherName'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
			<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>City: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['guardianCity'] != "" && $studentDataArr[0]['guardianCity'] != "NULL" ) ? $studentDataArr[0]['guardianCity'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
			<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>State: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['guardianState'] != "" && $studentDataArr[0]['guardianState'] != "NULL" ) ? $studentDataArr[0]['guardianState'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
			<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Country: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['guardianCountry'] != "" && $studentDataArr[0]['guardianCountry'] != "NULL" ) ? $studentDataArr[0]['guardianCountry'] : NOT_APPLICABLE_STRING;?></td>
		</tr>
			<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>PinCode: </b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?> > <?php echo ( $studentDataArr[0]['guardianPinCode'] != "" && $studentDataArr[0]['guardianPinCode'] != "NULL" ) ? $studentDataArr[0]['guardianPinCode'] : NOT_APPLICABLE_STRING;?></td>
		</tr>		
		
		<!--	echo ( $studentDataArr[0]['guardianName'] != "" ) ? " ".$titleResults[$studentDataArr[0]['guardianTitle']].' '.$studentDataArr[0]['guardianName'] : " ";
			echo ( $studentDataArr[0]['guardianOccupation'] != "" ) ? " (".$studentDataArr[0]['guardianOccupation'].")"."<br>" : " ";
			echo ( $studentDataArr[0]['guardianMobileNo'] != "" && $studentDataArr[0]['guardianMobileNo'] != "NULL" ) ? " ".$studentDataArr[0]['guardianMobileNo']."<br>" : " ";
			echo ( $studentDataArr[0]['guardianEmail'] != "" && $studentDataArr[0]['guardianEmail'] != "NULL" ) ? " ".$studentDataArr[0]['guardianEmail'] : " ";
			echo ( $studentDataArr[0]['guardianAddress1'] != "" &&  $studentDataArr[0]['guardianAddress1'] != "NULL" ) ? "<br>".$studentDataArr[0]['guardianAddress1'] : " ";
			echo ( $studentDataArr[0]['guardianAddress2'] != "" &&  $studentDataArr[0]['guardianAddress2'] != "NULL") ? "<br>".$studentDataArr[0]['guardianAddress2'] : " ";
			echo ($studentDataArr[0]['guardianCity'] != "" && $studentDataArr[0]['guardianCity'] != "NULL") ? " <br>".$studentDataArr[0]['guardianCity'] : " ";
			echo ($studentDataArr[0]['guardianState'] != "" && $studentDataArr[0]['guardianState'] != "NULL") ? " ".$studentDataArr[0]['guardianState'] : " ";
			echo ($studentDataArr[0]['guardianCountry'] != "" && $studentDataArr[0]['guardianCountry'] != "NULL") ? " ".$studentDataArr[0]['guardianCountry']: " ";
			echo ($studentDataArr[0]['guardianPinCode'] != "" && $studentDataArr[0]['guardianPinCode'] != "NULL") ? " "."-".$studentDataArr[0]['guardianPinCode'] : " ";
			--></td>
		</tr>
		<?php
		 }  
		?>
		</table>

		<br class="page" />
         <?php }
          if ($sessionHandler->getSessionVariable('COURSE')==1)  { 
	  ?>
        <table border='0' cellspacing='0' class="reportTableBorder" width="90%" align="center">
        <tr>
            <td colspan='4' height="20" <?php echo $reportManager->getReportDataStyle()?>><B><U>COURSE DETAILS</U></B></td>
        </tr>
        <tr>
            <td valign="top" colspan="4">
            <table border='1' cellspacing='0' width="90%" class="reportTableBorder"  align="center">
                <tr <?php echo $reportManager->getReportDataStyle()?> bgcolor="#ECECEC">
                    <td width="2%"><b>#</b>
                    <td valign="middle" align="left" width="22%"><b>Group Name</b></td>
                    <td valign="middle" align="left" width="12%" ><b>Group Type</b></td>
                    <td valign="middle" align="left" width="22%" ><b>Group Type Code</b></td>
                    <td valign="middle" align="center" width="14%"><b>Study Period</b></td>
                </tr>
                <?php
                    $countSection = count($studentSectionArray);
                    if($countSection){
                        for($sec=0;$sec<$countSection;$sec++){
                            if($sec%2==0)
                                $bg = "class=row0";
                            else
                                $bg = "class=row1";
                            echo "<tr ".$bg.">
                                <td align='center' ".$reportManager->getReportDataStyle().">".($sec+1)."</td>
                                <td ".$reportManager->getReportDataStyle().">".$studentSectionArray[$sec]['groupName']."</td>
                                <td ".$reportManager->getReportDataStyle().">".$studentSectionArray[$sec]['groupTypeName']."</td>
                                <td ".$reportManager->getReportDataStyle().">".$studentSectionArray[$sec]['groupTypeCode']."</td>
                                <td ".$reportManager->getReportDataStyle().">".$studentSectionArray[$sec]['studyPeriod']."</td>
                            </tr>";
                        }
                    }
                    else{
                        echo "<tr><td colspan='5' align='center' ".$reportManager->getReportDataStyle().">No record found</td></tr>";
                    }
                ?>
                </table>
            </td>
        </tr>
        </table>

        <br class="page" />
        <?php  }
          if ($sessionHandler->getSessionVariable('ADMINISTRATIVE')==1)  {     ?>
          <table border='0' cellspacing='0' class="reportTableBorder" width="90%" align="center">
        <tr>
            <td colspan='4' height="20" <?php echo $reportManager->getReportDataStyle()?>><B><U>ADMINISTRATIVE DETAILS</U></B></td>
        </tr>
        <tr>
            <td valign="top" colspan="4">
            <table border='1' cellspacing='0' class="reportTableBorder" width="90%" align="center">
            <tr>
                <td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Date Of Admission: </b></nobr></td>
                <td <?php echo $reportManager->getReportDataStyle()?>><?php echo (UtilityManager::formatDate($studentDataArr[0]['dateOfAdmission']))?></td>
                <td <?php echo $reportManager->getReportDataStyle()?>><nobr><b>I - Card Number: </b></nobr></td>
                <td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['icardNumber'] != "" && $studentDataArr[0]['icardNumber'] != "NULL" ) ? $studentDataArr[0]['icardNumber'] : NOT_APPLICABLE_STRING;?></td>
            </tr>
            <tr>
                <td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Mgmt Category: </b></nobr></td>
                <td <?php echo $reportManager->getReportDataStyle()?>><?php  if($studentDataArr[0]['managementCategory']) echo "Yes"; else echo "No";?></td>
                <td <?php echo $reportManager->getReportDataStyle()?>><nobr><b>Mgmt Reference: </b></nobr></td>
                <td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['managementReference'] != "" && $studentDataArr[0]['managementReference'] != "NULL" ) ? $studentDataArr[0]['managementReference'] : NOT_APPLICABLE_STRING;?></td>
            </tr>
            <tr>
                <td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Hostel Name: </b></nobr></td>
                <td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['hostelName'] != "" && $studentDataArr[0]['hostelName'] != "NULL" ) ? $studentDataArr[0]['hostelName'] : NOT_APPLICABLE_STRING;?></td>
                <td <?php echo $reportManager->getReportDataStyle()?>><nobr><b>Hostel Room No.: </b></nobr></td>
                <td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['roomName'] != "" && $studentDataArr[0]['roomName'] != "NULL") ? $studentDataArr[0]['roomName'] : NOT_APPLICABLE_STRING;?></td>
            </tr>
            <tr>
                <td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Bus Route No.: </b></nobr></td>
                <td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['routeName'] != "" && $studentDataArr[0]['routeName'] != "NULL") ? $studentDataArr[0]['routeName'] : NOT_APPLICABLE_STRING;?></td>
                <td <?php echo $reportManager->getReportDataStyle()?>><nobr><b>Bus Stop No.: </b></nobr></td>
                <td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['stopName'] != "" && $studentDataArr[0]['stopName'] != "NULL") ? $studentDataArr[0]['stopName'] : NOT_APPLICABLE_STRING;?></td>
            </tr>
            </table>
            </td>
        </tr>
        </table>
		<table border='0' cellspacing='0' class="reportTableBorder" width="90%" align="center">

		<tr>
			<td colspan='4' height="20" <?php echo $reportManager->getReportDataStyle()?>><B><U>PREVIOUS ACADEMIC RECORD</U></B></td>
		</tr>
		<tr>
			<td valign="top" colspan="4">
			<table border='1' cellspacing='0' class="reportTableBorder" width="90%" align="center">

			<tr class='row1'>
				<td valign="middle" height='25' <?php echo $reportManager->getReportDataStyle()?>><B>Class</B></td>
				<td valign="middle" <?php echo $reportManager->getReportDataStyle()?>><B>Roll No.</B></td>
				<td valign="middle" <?php echo $reportManager->getReportDataStyle()?>><B>Session</B></td>
				<td valign="middle" <?php echo $reportManager->getReportDataStyle()?>><B>School/Institute/University Last Attended</B></td>
				<td valign="middle" <?php echo $reportManager->getReportDataStyle()?>><B>Name of Board/University</B></td>
				<td valign="middle" <?php echo $reportManager->getReportDataStyle()?> align='right'><B>Marks Obtained</B></td>
				<td valign="middle" <?php echo $reportManager->getReportDataStyle()?> align='right'><B>Max. Marks</B></td>
				<td valign="middle" <?php echo $reportManager->getReportDataStyle()?> align='right'><B>%age</B></td>
			</tr>
			<?php
			global $classResults;
			if(isset($academicRecordArray) && is_array($academicRecordArray) && count($academicRecordArray)>0) {
				$count = count($academicRecordArray);
				for($sec=0;$sec<$count;$sec++){

				 $prevRoll = ($academicRecordArray[$sec]['previousRollNo'] != "" && $academicRecordArray[$sec]['previousRollNo'] != "NULL") ? $academicRecordArray[$sec]['previousRollNo'] : NOT_APPLICABLE_STRING;
				  	//previousInstitute 	previousBoard 	previousMarks 	previousMaxMarks 	previousPercentage

				 $prevSession = ($academicRecordArray[$sec]['previousSession'] != "" && $academicRecordArray[$sec]['previousSession'] != "NULL") ? $academicRecordArray[$sec]['previousSession'] : NOT_APPLICABLE_STRING;

				 $previousInstitute = ($academicRecordArray[$sec]['previousInstitute'] != "" && $academicRecordArray[$sec]['previousInstitute'] != "NULL") ? $academicRecordArray[$sec]['previousInstitute'] : NOT_APPLICABLE_STRING;

				 $previousBoard = ($academicRecordArray[$sec]['previousBoard'] != "" && $academicRecordArray[$sec]['previousBoard'] != "NULL") ? $academicRecordArray[$sec]['previousBoard'] : NOT_APPLICABLE_STRING;

				 $previousMarks = ($academicRecordArray[$sec]['previousMarks'] != "" && $academicRecordArray[$sec]['previousMarks'] != "NULL") ? $academicRecordArray[$sec]['previousMarks'] : NOT_APPLICABLE_STRING;

				 $previousMaxMarks = ($academicRecordArray[$sec]['previousMaxMarks'] != "" && $academicRecordArray[$sec]['previousMaxMarks'] != "NULL") ? $academicRecordArray[$sec]['previousMaxMarks'] : NOT_APPLICABLE_STRING;

				 $previousPercentage = ($academicRecordArray[$sec]['previousPercentage'] != "" && $academicRecordArray[$sec]['previousPercentage'] != "NULL") ? $academicRecordArray[$sec]['previousPercentage'] : NOT_APPLICABLE_STRING;

				 echo "<tr class='row0'>
					<td valign='middle' ".$reportManager->getReportDataStyle().">". $classResults[$academicRecordArray[$sec][previousClassId]]."</td>
					<td valign='middle' ".$reportManager->getReportDataStyle().">".$prevRoll."</td>
					<td valign='middle' ".$reportManager->getReportDataStyle().">".$prevSession."</td>
					<td valign='middle' ".$reportManager->getReportDataStyle().">".$previousInstitute."</td>
					<td valign='middle' ".$reportManager->getReportDataStyle().">".$previousBoard."</td>
					<td valign='middle' ".$reportManager->getReportDataStyle()." align='right'>".$previousMarks."</td>
					<td valign='middle' ".$reportManager->getReportDataStyle()." align='right'>".$previousMaxMarks."</td>
					<td valign='middle' ".$reportManager->getReportDataStyle()." align='right'>".$previousPercentage."</td>
					</tr>";
				}
				echo "<input type='hidden' id='countRecord' name='countRecord' value='".$count."'/>";

			}
            else{
              echo '<tr><td colspan="8" align="center" '.$reportManager->getReportDataStyle().'>'.NO_DATA_FOUND.'</td></tr>';
            }
			?>
			</table>
			</td>
		</tr>
		</table>
        <br class="page" />
       <?php   }
          if ($sessionHandler->getSessionVariable('SCHEDULE')==1)  {         ?>
        <table border='0' cellspacing='0' class="reportTableBorder" width="90%" align="center">
        <tr>
            <td colspan='4' height="20" <?php echo $reportManager->getReportDataStyle()?>><B><U>SCHEDULE DETAILS</U></B></td>
        </tr>
        <tr>
            <td valign="top" colspan='4'>
            <?php
            $findTimeTable='';
            if($isAlumni==0){
                $recordCount = count($studentRecordArray);
                if($recordCount >0 && is_array($studentRecordArray)) {
                    if($timetableFormat=='1') {
                       echo  $htmlFunctionsManager->showTimeTablePeriodsColumns($studentRecordArray,$periodArray);
                       echo "<br>";
                   }
                   else
                   if($timetableFormat=='2') {
                     echo  $htmlFunctionsManager->showTimeTablePeriodsRows($studentRecordArray,$periodArray);
                     echo "<br>";
                   }
                   $findTimeTable='1';
                }
                else{
                    echo "<table border='1' cellspacing='0' width='90%' class='reportTableBorder'  align='center'><tr ".$reportManager->getReportDataStyle()."><td valign='middle'   align='center'>".TIME_TABLE_NOT_GENERATED."</td></tr></table>";
                }
            }
            else{
                // Period Slot Array
        $results = CommonQueryManager::getInstance()->getTimeTableLabel('');

        // Fetch All Classes
        $classFetchArray = CommonQueryManager::getInstance()->getStudyPeriodData($studentId);
        $classRecordCount = count($classFetchArray);
        $findTimeTable='';
        if($classRecordCount >0 && is_array($classFetchArray)) {
           for($k=0;$k<$classRecordCount;$k++) {
               $classId = $classFetchArray[$k]['classId'];
               $className = $classFetchArray[$k]['periodName'];
               if(isset($results) && is_array($results)) {
                   for($i=0; $i<count($results); $i++) {
                         //Get the time table date according to class selected
                         $timeTableLabelId = $results[$i]['timeTableLabelId'];
                         if($timeTableLabelId=='') {
                            $timeTableLabelId=0;
                         }

                        // Fetch Period Arrays
                        $periodCondition = " tt.timeTableLabelId = ".$timeTableLabelId;
                        $orderByList= " p.periodSlotId, LENGTH(p.periodNumber)+0,p.periodNumber";
                        $periodSlotArr = $timeTableManager->getTimeTablePeriodList($periodCondition,$orderByList,' DISTINCT p.periodSlotId');

                        //Get the time table date according to time table slot wise
                        for($ps=0; $ps < count($periodSlotArr); $ps++) {          // Period Slot Wise  --  Start --
                           $periodSlotId = $periodSlotArr[$ps]['periodSlotId'];

                           $periodCondition = " tt.timeTableLabelId = ".$timeTableLabelId." AND p.periodSlotId = ".$periodSlotId;
                           $orderByList= " p.periodSlotId, LENGTH(p.periodNumber)+0,p.periodNumber";
                           $periodArray = $timeTableManager->getTimeTablePeriodList($periodCondition,$orderByList);

                           $conditions  = " AND sg.studentId=".$studentId." AND cl.classId = $classId";
                           $conditions .= " AND tt.timeTableLabelId=".$timeTableLabelId;

                           $cond1 =  $conditions." AND p.periodSlotId = ".$periodSlotArr[$ps]['periodSlotId'];

                           $fieldName="DISTINCT timeTableType";
                           $orderFrom = " ORDER BY timeTableType";
                           $studentRecordArray = $timeTableManager->getStudentShowTimeTable($cond1,$orderFrom,'','',$fieldName);
                           $timeTableType=1;
                           if(count($studentRecordArray)>0) {
                               $timeTableType = $studentRecordArray[0]['timeTableType'];
                           }

                           if($timeTableType==1) {
                               $orderBy =($timetableFormat == 1) ? " ORDER BY periodSlotId, daysOfWeek, LENGTH(periodNumber)+0,periodNumber" : " ORDER BY periodSlotId, LENGTH(periodNumber)+0,periodNumber,daysOfWeek";
                           }
                           else
                           if($timeTableType==2) {
                              $orderBy = " ORDER BY periodSlotId, fromDate, LENGTH(periodNumber)+0,periodNumber";
                           }

                           if($timeTableType==2) {
                                // Date Format
                                $fieldName = " DISTINCT tt.fromDate";
                                $orderFrom = " ORDER BY fromDate";
                                $timeTableDateArray = $timeTableManager->getStudentShowTimeTable($cond1,$orderFrom,'','',$fieldName);
                           }

                           $teacherRecordArray = $timeTableManager->getStudentShowTimeTable($cond1,$orderBy);
                           $recordCount = count($teacherRecordArray);
                           if($recordCount >0 && is_array($teacherRecordArray)) {
                              $findTimeTable .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="reportTableBorder">
                               <tr>
                                  <td width="100%" class="contenttab_internal_rows1" valign="bottom" align="left"><nobr<b>'.$className.'</b></nobr></td>
                               </tr>
                              </table>';
                               if($timeTableType==1) {
                                    if($timetableFormat=='1') {
                                       $findTimeTable .= $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray);
                                       $findTimeTable .= "<br>";
                                    }
                                    else{
                                        if($timetableFormat=='2') {
                                            $findTimeTable .= $htmlFunctionsManager->showTimeTablePeriodsRows($teacherRecordArray,$periodArray);
                                            $findTimeTable .= "<br>";
                                        }
                                    }
                               }
                               else
                               if($timeTableType==2) {
                                   $findTimeTable .= $htmlFunctionsManager->showTimeTablePeriodsColumns($teacherRecordArray,$periodArray,'0',$timeTableType,$timeTableDateArray);
                                   $findTimeTable .= "<br>";
                               }
                           }
                        }  // Period Slot Wise  --  End --
                  }
              } // Period
           }
         }
   }
   if($findTimeTable=='') {
     //echo "<div align='center'>No record found</div></td></tr></table></div>";
   }
 //  echo $findTimeTable;
   ?>
            </td>
        </tr>
        </table>
        <br class="page" />

        <?php }

        if ($sessionHandler->getSessionVariable('MARKS')==1)  {    ?>
        <table border='0' cellspacing='0' class="reportTableBorder" width="90%" align="center">
        <tr>
            <td colspan='4' height="20" <?php echo $reportManager->getReportDataStyle()?>><B><U>MARKS DETAILS</U></B></td>
        </tr>
        <tr>
            <td valign="top" height="5" colspan='4'></td>
        </tr>
        <tr>
            <td valign="top" colspan='4'>
            <table border='1' cellspacing='0' width="90%" class="reportTableBorder"  align="center">
                <tr <?php echo $reportManager->getReportDataStyle()?> bgcolor="#ECECEC">
                    <td valign="middle" height="25" width="4%" align="left"><b>&nbsp;#</b></td>
                    <td valign="middle"><b>&nbsp;Subject</b></td>
                    <td valign="middle" width="20%">&nbsp;<b>Type</b></td>
                    <td valign="middle">&nbsp;<b>Date</b></td>
                    <td valign="middle">&nbsp;<b>Study Period</b></td>
                    <td valign="middle">&nbsp;<b>Teacher</b></td>
                    <td valign="middle" width="15%">&nbsp;<b>Test Name</b></td>
                    <td valign="middle" align="right">&nbsp;<b>Marks</b></td>
                    <td valign="middle" align="right">&nbsp;<b>Obtained</b></td>

                </tr>
                <?php 
                $recordCount = count($studentSubjectArray);
                $j=0;
                $k=0;
                if($recordCount >0 && is_array($studentSubjectArray) ) {
                    $subjectName = "";
                    for($i=0; $i<$recordCount; $i++ ) {

                        $bg = $bg =='row0' ? 'row1' : 'row0';

                        $subjectName1 = $studentSubjectArray[$i]['subjectName'].' ('.$studentSubjectArray[$i]['subjectCode'].')';



                    echo '<tr '.$reportManager->getReportDataStyle().'>
                        <td valign="top" '.$reportManager->getReportDataStyle().' align="center" >'.($i+1).'</td>
                        <td valign="top" '.$reportManager->getReportDataStyle().' >'.$studentSubjectArray[$i]['subjectName'].'</td>
                        <td valign="top" '.$reportManager->getReportDataStyle().'>'.$studentSubjectArray[$i]['testTypeName'].'</td>
                        <td valign="top" '.$reportManager->getReportDataStyle().'>'.$studentSubjectArray[$i]['testDate'].'</td>
                        <td valign="top" '.$reportManager->getReportDataStyle().'>'.$studentSubjectArray[$i]['studyPeriod'].'</td>
                        <td valign="top" '.$reportManager->getReportDataStyle().'>'.$studentSubjectArray[$i]['employeeName'].'</td>
                        <td valign="top" '.$reportManager->getReportDataStyle().'>'.$studentSubjectArray[$i]['testName'].'</td>
                        <td valign="top" '.$reportManager->getReportDataStyle().' align="right">'.$studentSubjectArray[$i]['totalMarks'].'</td>
                        <td valign="top" '.$reportManager->getReportDataStyle().' align="right">'.$studentSubjectArray[$i]['obtainedMarks'].'</td>

                        </tr>';

                    }
                }
                else {
                    echo '<tr><td colspan="9" align="center" '.$reportManager->getReportDataStyle().'>'.NO_DATA_FOUND.'</td></tr>';
                }
                 ?>
                </table>
            </td>
        </tr>
        </table>
        <br class="page" />
        <?php }

        if ($sessionHandler->getSessionVariable('ATTENDANCE')==1)  {    ?>
        <table border='0' cellspacing='0' class="reportTableBorder" width="90%" align="center">
        <tr>
            <td colspan='4' height="20" <?php echo $reportManager->getReportDataStyle()?>><B><U>ATTENDANCE DETAILS</U></B></td>
        </tr>
        <tr>
            <td valign="top" colspan='4'>
            <table border='1' cellspacing='0' width="90%" class="reportTableBorder"  align="center">
                <tr <?php echo $reportManager->getReportDataStyle()?> bgcolor="#ECECEC">
                    <td valign="middle" height="25" width="2%"><b>&nbsp;#</b></td>
                    <td valign="middle" width="25%"><b>&nbsp;Subject</b></td>
                    <td valign="middle"><nobr><b>&nbsp;Study Period</b></nobr></td>
                    <td valign="middle"><b>&nbsp;Group</b></td>
                    <td valign="middle"><b>&nbsp;Teacher</b></td>
                    <td valign="middle"><b>&nbsp;From</b></td>
                    <td valign="middle"><b>&nbsp;To</b></td>
                    <td valign="middle" align="right">&nbsp;<b>Delivered</b></td>
                    <td valign="middle" align="right">&nbsp;<b>Attended</b></td>
                    <td valign="middle" align="right"><nobr>&nbsp;<b>Duty Leave</b></nobr></td>
                    <td valign="middle" align="right">&nbsp;<b>%</b></td>
                </tr>

                <?php
                $recordCount = count($studentAttendanceArray);
                if($recordCount >0 && is_array($studentAttendanceArray) ) {

                    $subjectName = "";
                    for($i=0; $i<$recordCount; $i++ ) {


                        if ($studentAttendanceArray[$i]['studentName'] != '-1') {
                            $studentAttendanceArray[$i]['Percentage'] = "0.00";
                        }
                        else {
                            $studentAttendanceArray[$i]['Percentage'] = NOT_APPLICABLE_STRING;
                            $studentAttendanceArray[$i]['Percentage'] = "<span style='color:#0081D7'><strong>".$studentAttendanceArray[$i]['Percentage']."</strong></span>";
                        }

                        if($studentAttendanceArray[$i]['attended'] > 0 && $studentAttendanceArray[$i]['delivered'] > 0 ) {
                            if ($studentAttendanceArray[$i]['dutyLeave'] != '') {
                                $studentAttendanceArray[$i]['attended1'] = "".$studentAttendanceArray[$i]['attended'] + $studentAttendanceArray[$i]['dutyLeave']."";
                                $studentAttendanceArray[$i]['Percentage']="".number_format((($studentAttendanceArray[$i]['attended1'] /  $studentAttendanceArray[$i]['delivered'])*100),2,'.','')."";
                            }
                            else {
                                $studentAttendanceArray[$i]['Percentage']="".number_format((($studentAttendanceArray[$i]['attended'] /  $studentAttendanceArray[$i]['delivered'])*100),2,'.','')."";
                            }
                       }

                       if ($studentAttendanceArray[$i]['dutyLeave'] == 'null' || $studentAttendanceArray[$i]['dutyLeave'] == '') {
                           $studentAttendanceArray[$i]['dutyLeave'] = NOT_APPLICABLE_STRING;
                       }

                        echo '<tr '.$reportManager->getReportDataStyle().'>
                        <td valign="top" align="center" '.$reportManager->getReportDataStyle().' >'.($i+1).'</td>
                        <td valign="top" '.$reportManager->getReportDataStyle().'>'.$studentAttendanceArray[$i]['subject'].'</td>
                        <td valign="top" '.$reportManager->getReportDataStyle().'>'.$studentAttendanceArray[$i]['periodName'].'</td>
                        <td valign="top" '.$reportManager->getReportDataStyle().'>'.$studentAttendanceArray[$i]['groupName'].'</td>
                        <td valign="top" '.$reportManager->getReportDataStyle().'>'.$studentAttendanceArray[$i]['employeeName'].'</td>
                        <td valign="top" '.$reportManager->getReportDataStyle().'>'.$studentAttendanceArray[$i]['fromDate'].'</td>
                        <td valign="top" '.$reportManager->getReportDataStyle().'>'.$studentAttendanceArray[$i]['toDate'].'</td>
                        <td valign="top" align="right" '.$reportManager->getReportDataStyle().'>'.$studentAttendanceArray[$i]['delivered'].'</td>
                        <td valign="top" align="right" '.$reportManager->getReportDataStyle().'>'.$studentAttendanceArray[$i]['attended'].'</td>
                        <td valign="top" align="right" '.$reportManager->getReportDataStyle().'>'.$studentAttendanceArray[$i]['dutyLeave'].'</td>
                        <td valign="top" align="right" '.$reportManager->getReportDataStyle().'>'.$studentAttendanceArray[$i]['Percentage'].'</td>
                        </tr>';
                    }
                }
                else {
                    echo '<tr><td colspan="11" align="center" '.$reportManager->getReportDataStyle().'>'.NO_DATA_FOUND.'</td></tr>';
                }
                 ?>
                </table>
            </td>
        </tr>
        <tr>
            <td valign="top" height="5" colspan='4'></td>
        </tr>
        </table>
        <br class="page" />
        <?php   }

         if ($sessionHandler->getSessionVariable('FEES')==1)  {       ?>
		<table border='0' cellspacing='0' class="reportTableBorder" width="90%" align="center">
		<tr>
			<td colspan='4' height="20" <?php echo $reportManager->getReportDataStyle()?>><B><U>FEES DETAILS</U></B></td>
		</tr>
		<tr>
			<td valign="top" colspan='4'>
			<table border='1' cellspacing='0' width="90%" class="reportTableBorder"  align="center">
			<tr <?php echo $reportManager->getReportDataStyle()?> bgcolor="#ECECEC">
				<td valign="middle" height="25" class="searchhead_text"><b>#</b></td>
				<td valign="middle" height="25" class="searchhead_text"><b>Receipt No.</b></td>
				<td valign="middle" class="searchhead_text"><b>Date</b></td>
				<td valign="middle" class="searchhead_text" align="right"><b>Total(Rs)</b></td>
				<td valign="middle" class="searchhead_text" align="right"><b>Payable(Rs)</b></td>
				<td valign="middle" class="searchhead_text" align="right"><b>Paid(Rs)</b></td>
				<td valign="middle" class="searchhead_text" align="right"><b>Instrument</b></td>
				<td valign="middle" class="searchhead_text" align="right"><b>Status</b></td>

				<td valign="middle" class="searchhead_text" align="right"><b>Status&nbsp;&nbsp;</b></td>
			</tr>
			<?php
			$recordCount = count($feesClassArr);
			if($recordCount >0 && is_array($feesClassArr) ) {

				for($i=0; $i<$recordCount; $i++ ) {

				$bg = $bg =='row0' ? 'row1' : 'row0';

				echo '<tr class="'.$bg.'">
					<td valign="top"'.$reportManager->getReportDataStyle().'>'.($records+$i+1).'</td>
					<td '.$reportManager->getReportDataStyle().' valign="top">'.strip_slashes($feesClassArr[$i]['receiptNo']).'</td>
					<td '.$reportManager->getReportDataStyle().' valign="top">'.strip_slashes($feesClassArr[$i]['receiptDate']).'</td>
					<td '.$reportManager->getReportDataStyle().' valign="top" align="right"> '.number_format(strip_slashes($feesClassArr[$i]['totalFeePayable']),'2','.','').'</td>
					<td '.$reportManager->getReportDataStyle().' valign="top" align="right"> '.number_format(strip_slashes($feesClassArr[$i]['discountedFeePayable']),'2','.','').'</td>
					<td '.$reportManager->getReportDataStyle().' valign="top" align="right"> '.number_format(strip_slashes($feesClassArr[$i]['totalAmountPaid']),'2','.','').'</td>
					<td '.$reportManager->getReportDataStyle().' valign="top" align="center">'.$modeArr[strip_slashes($feesClassArr[$i]['paymentInstrument'])].'</td>
					<td '.$reportManager->getReportDataStyle().' valign="top" align="center">'.$receiptArr[strip_slashes($feesClassArr[$i]['receiptStatus'])].'</td>

					<td '.$reportManager->getReportDataStyle().' valign="top" align="center">'.$receiptPaymentArr[strip_slashes($feesClassArr[$i]['instrumentStatus'])].'</td>
					</tr>';
				}

			}
			else {
				echo '<tr><td colspan="9" align="center" '.$reportManager->getReportDataStyle().'>'.NO_DATA_FOUND.'</td></tr>';
			}
			 ?>
			</table>
			</td>
		</tr>
		</table>
		<br class="page" />

        <?php }
          if ($sessionHandler->getSessionVariable('RESOURCE')==1)  {   ?>
		<table border='0' cellspacing='0' class="reportTableBorder" width="90%" align="center">
		<tr>
			<td colspan='4' height="20" <?php echo $reportManager->getReportDataStyle()?>><B><U>RESOURCE DETAILS</U></B></td>
		</tr>
		<tr>
			<td valign="top" colspan='4'>
			<table border='1' cellspacing='0' width="90%" class="reportTableBorder"  align="center">
			<tr <?php echo $reportManager->getReportDataStyle()?> bgcolor="#ECECEC">
				<td valign="middle" height="25" class="searchhead_text"><b>#</b></td>
				<td valign="middle" height="25" class="searchhead_text"><b>Course</b></td>
				<td valign="middle" class="searchhead_text"><b>Description</b></td>
				<td valign="middle" class="searchhead_text" align="right"><b>Type</b></td>
				<td valign="middle" class="searchhead_text" align="right"><b>Date</b></td>
				<td valign="middle" class="searchhead_text" align="right"><b>Link</b></td>

				<td valign="middle" class="searchhead_text" align="right"><b>Creator</b></td>
			</tr>
			<?php
			$recordCount = count($studentResourceArray);
			if($recordCount >0 && is_array($studentResourceArray) ) {

				for($i=0; $i<$recordCount; $i++ ) {

				$bg = $bg =='row0' ? 'row1' : 'row0';

				echo '<tr class="'.$bg.'">
					<td valign="top"'.$reportManager->getReportDataStyle().'>'.($records+$i+1).'</td>
					<td '.$reportManager->getReportDataStyle().' valign="top">'.strip_slashes($studentResourceArray[$i]['subject']).'</td>
					<td '.$reportManager->getReportDataStyle().' valign="top">'.strip_slashes($studentResourceArray[$i]['description']).'</td>

					<td '.$reportManager->getReportDataStyle().' valign="top" align="right"> '.strip_slashes($studentResourceArray[$i]['resourceName']).'</td>
					<td '.$reportManager->getReportDataStyle().' valign="top" align="right"> '.strip_slashes($studentResourceArray[$i]['postedDate']).'</td>
					<td '.$reportManager->getReportDataStyle().' valign="top" align="center">'.strip_slashes($studentResourceArray[$i]['resourceUrl']).'</td>

					<td '.$reportManager->getReportDataStyle().' valign="top" align="center">'.strip_slashes($studentResourceArray[$i]['employeeName']).'</td>
					</tr>';
				}

			}
			else {
				echo '<tr><td colspan="9" align="center" '.$reportManager->getReportDataStyle().'>'.NO_DATA_FOUND.'</td></tr>';
			}
			 ?>
			</table>
			</td>
		</tr>
		</table>
        <?php
          }
           if ($sessionHandler->getSessionVariable('FINAL_RESULT')==1)  { ?>
		<table border='0' cellspacing='0' class="reportTableBorder" width="90%" align="center">
		<tr>
			<td valign="top" height="15" colspan='4'></td>
		</tr>
		<tr>
			<td colspan='4' height="20" <?php echo $reportManager->getReportDataStyle()?>><B><U>FINAL RESULT</U></B></td>
		</tr>
		<tr>
			<td valign="top" colspan='4'>
			<table border='1' cellspacing='0' width="90%" class="reportTableBorder"  align="center">
			<tr <?php echo $reportManager->getReportDataStyle()?> bgcolor="#ECECEC">
				<td valign="middle" height="25" class="searchhead_text"><b>#</b></td>
				<td valign="middle" height="25" class="searchhead_text"><b>Study Period</b></td>
				<td valign="middle" class="searchhead_text"><b>Course</b></td>
                <td valign="middle" class="searchhead_text" align="right"><b>Attendance</b></td>
				<td valign="middle" class="searchhead_text" align="right"><b>Pre Compre</b></td>
				<td valign="middle" class="searchhead_text" align="right"><b>Compre</b></td>
			</tr>
			<?php
			$recordCount = count($resultRecordArray);
			if($recordCount >0 && is_array($resultRecordArray) ) {

				for($i=0; $i<$recordCount; $i++ ) {

				$bg = $bg =='row0' ? 'row1' : 'row0';

				echo '<tr class="'.$bg.'">
					<td valign="top"'.$reportManager->getReportDataStyle().'>'.($records+$i+1).'</td>
					<td '.$reportManager->getReportDataStyle().' valign="top">'.strip_slashes($resultRecordArray[$i]['periodName']).'</td>
					<td '.$reportManager->getReportDataStyle().' valign="top">'.strip_slashes($resultRecordArray[$i]['subjectCode']).'</td>
                    <td '.$reportManager->getReportDataStyle().' valign="top" align="right"> '.strip_slashes($resultRecordArray[$i]['attendance']).'</td>
					<td '.$reportManager->getReportDataStyle().' valign="top" align="right"> '.strip_slashes($resultRecordArray[$i]['preComprehensive']).'</td>
					<td '.$reportManager->getReportDataStyle().' valign="top" align="right"> '.strip_slashes($resultRecordArray[$i]['Comprehensive']).'</td>
					</tr>';
				}

			}
			else {
				echo '<tr><td colspan="9" align="center" '.$reportManager->getReportDataStyle().'>'.NO_DATA_FOUND.'</td></tr>';
			}
			 ?>
			</table>
			</td>
		</tr>
		</table>
        <?php }
         if ($sessionHandler->getSessionVariable('OFFENSE')==1)  {  ?>
		<table border='0' cellspacing='0' class="reportTableBorder" width="90%" align="center">
		<tr>
			<td valign="top" height="15" colspan='4'></td>
		</tr>
		<tr>
			<td colspan='4' height="20" <?php echo $reportManager->getReportDataStyle()?>><B><U>STUDENT OFFENSE</U></B></td>
		</tr>
		<tr>
			<td valign="top" colspan='4'>
			<table border='1' cellspacing='0' width="90%" class="reportTableBorder"  align="center">
			<tr <?php echo $reportManager->getReportDataStyle()?> bgcolor="#ECECEC">
				<td valign="middle" height="25" class="searchhead_text"><b>#</b></td>
				<td valign="middle" class="searchhead_text"><b>Offence</b></td>
				<td valign="middle" class="searchhead_text" align="left"><b>Date</b></td>
				<td valign="middle" height="25" class="searchhead_text"><b>Study Period</b></td>
				<td valign="middle" class="searchhead_text" align="left" width="55%"><b>Remarks</b></td>
			</tr>
			<?php
			$recordCount = count($offenseRecordArray);
			if($recordCount >0 && is_array($offenseRecordArray) ) {

				for($i=0; $i<$recordCount; $i++ ) {

				$bg = $bg =='row0' ? 'row1' : 'row0';

				echo '<tr class="'.$bg.'">
					<td valign="top"'.$reportManager->getReportDataStyle().'>'.($records+$i+1).'</td>
					<td '.$reportManager->getReportDataStyle().' valign="top">'.strip_slashes($offenseRecordArray[$i]['offenseName']).'</td>
					<td '.$reportManager->getReportDataStyle().' valign="top">'.strip_slashes($offenseRecordArray[$i]['offenseDate']).'</td>
					<td '.$reportManager->getReportDataStyle().' valign="top" align="left"> '.strip_slashes($offenseRecordArray[$i]['periodName']).'</td>
					<td '.$reportManager->getReportDataStyle().' valign="top" align="left"> '.strip_slashes($offenseRecordArray[$i]['remarks']).'</td>
					</tr>';
				}

			}
			else {
				echo '<tr><td colspan="9" align="center" '.$reportManager->getReportDataStyle().'>'.NO_DATA_FOUND.'</td></tr>';
			}
			 ?>
			</table>
			</td>
		</tr>
		</table>
        <?php } ?>
		<table border='0' cellspacing='0' class="reportTableBorder" width="90%" align="center">
		<tr>
			<td valign="top" height="10" colspan='4'></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> width="150" height="20" colspan='4'><nobr><b>Student Remarks: </b></nobr><?php echo ( $studentDataArr[0]['studentRemarks'] != "" && $studentDataArr[0]['studentRemarks'] != "NULL") ? nl2br($studentDataArr[0]['studentRemarks']) : NOT_APPLICABLE_STRING;?></td>
		</tr>
		</table>
		<br>
		<table border='0' cellspacing='0' cellpadding='0' width="90%" align="center">
		<tr>
			<td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
		</tr>
		</table>
<?php
// $History: studentProfilePrint.php $
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 10-02-11   Time: 5:47p
//Updated in $/LeapCC/Templates/Student
//fixed 0002700
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 5/28/09    Time: 7:17p
//Updated in $/LeapCC/Templates/Student
//Changed Order by parameter for student academic display to be class
//wise
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 5/28/09    Time: 3:30p
//Updated in $/LeapCC/Templates/Student
//Added blood group, reference name, sports activity, student previous
//academic, in print report as well as find student tab
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 5/18/09    Time: 9:53a
//Updated in $/LeapCC/Templates/Student
//Updated Time table format as per the parameter set from Config Paramter
//"TIMETABLE_FORMAT"
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 12/22/08   Time: 5:52p
//Updated in $/LeapCC/Templates/Student
//added Offense tab
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/10/08   Time: 5:50p
//Updated in $/LeapCC/Templates/Student
//updated functionality as per CC
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Student
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 9/17/08    Time: 10:48a
//Updated in $/Leap/Source/Templates/Student
//updated as respect to subject centric
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 9/09/08    Time: 3:24p
//Updated in $/Leap/Source/Templates/Student
//updated student profile print module
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 9/03/08    Time: 3:10p
//Updated in $/Leap/Source/Templates/Student
//updated formatting and spacing
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 8/25/08    Time: 5:30p
//Updated in $/Leap/Source/Templates/Student
//updated student profile report
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 8/22/08    Time: 5:49p
//Created in $/Leap/Source/Templates/Student
//intial checkin
?>