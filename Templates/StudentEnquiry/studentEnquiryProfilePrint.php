<?php 
//This file is used as printing version for Student Enquiry Profile Print
//
// Author :Parveen Sharma
// Created on : 29-05-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
	
    global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");

	require_once(MODEL_PATH . "/StudentEnquiryManager.inc.php");
    $studentManager = StudentEnquiryManager::getInstance();
    
    define('MODULE','AddStudentEnquiry');
    define('ACCESS','view');
    //UtilityManager::ifNotLoggedIn();


	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();
	
	$studentDataArr = $studentManager->getStudentInformationList($REQUEST_DATA['studentId']);
    
	$formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);
	$reportManager->setReportInformation("For ".$studentDataArr[0]['studentName'].' of '.$studentDataArr[0]['className']." As On $formattedDate ");
	$reportManager->setReportHeading("Student Enquiry Profile");
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
					<td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right" width="50%">Date :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo date("d-M-y");?></td>
				</tr>
				<tr>
					<td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right">Time :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo date("h:i:s A");?></td>
				</tr>
			</table>
		</td>
	</tr>
	
	<tr><th colspan="3" <?php echo $reportManager->getReportInformationStyle(); ?>><?php echo $reportManager->getReportInformation(); ?></th></tr>
	</table> <br>
		<table border='0' cellspacing='0' class="reportTableBorder" width="90%" align="center">
		<tr>
			<td colspan='2' height="20" <?php echo $reportManager->getReportDataStyle()?>><B><U>PERSONAL DETAILS</U></B></td>
            <td colspan='3' align="right" height="20" <?php echo $reportManager->getReportDataStyle()?>>
                <B>Enquiry Date:</B>
                 <?php echo ($studentDataArr[0]['enquiryDate'] != "0000-00-00" ) ? (UtilityManager::formatDate($studentDataArr[0]['enquiryDate'])) : NOT_APPLICABLE_STRING; ?>&nbsp;&nbsp;
            </td>
		</tr>
		<tr>    
            <td <?php echo $reportManager->getReportDataStyle()?> width="17%" height="20"><nobr><b>Name</b></nobr></td>
            <td <?php echo $reportManager->getReportDataStyle()?> width="1%" ><nobr><b>:</b></nobr></td>
            <td <?php echo $reportManager->getReportDataStyle()?> width="50%"><?php echo $studentDataArr[0]['studentName']?></td>
            <td rowspan="7" align="right" colspan="2" valign="bottom" >
                <?php if($studentDataArr[0]['studentPhoto']){ 
                    
                    echo "<img src='".STUDENT_PHOTO_PATH."/".$studentDataArr[0][studentPhoto]."' width='135' height='135'/>";
                }
                else
                    echo "<img src='".IMG_HTTP_PATH."/notfound.jpg' border='0' width='135' height='135'/>";
                ?>
            </td>
        </tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Date of Birth</b></nobr></td>
            <td <?php echo $reportManager->getReportDataStyle()?> width="1%" ><nobr><b>:</b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo (UtilityManager::formatDate($studentDataArr[0]['dateOfBirth']))?></td>
		</tr>
		<tr>	
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Email</b></nobr></td>
            <td <?php echo $reportManager->getReportDataStyle()?> width="1%" ><nobr><b>:</b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo ( $studentDataArr[0]['studentEmail'] != "" && $studentDataArr[0]['studentEmail'] != "NULL" ) ? $studentDataArr[0]['studentEmail'] : "-- ";?></td>
		</tr>
		<tr>	
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Gender</b></nobr></td>
            <td <?php echo $reportManager->getReportDataStyle()?> width="1%" ><nobr><b>:</b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php if($studentDataArr[0]['studentGender']=="M") echo "Male"; else echo "Female";?>
			</td>
		</tr>
		<tr>	
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Nationality</b></nobr></td>
            <td <?php echo $reportManager->getReportDataStyle()?> width="1%" ><nobr><b>:</b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo $studentDataArr[0]['nationalityName'];?></td>
		</tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Domicile</b></nobr></td>
            <td <?php echo $reportManager->getReportDataStyle()?> width="1%" ><nobr><b>:</b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>><?php echo $studentDataArr[0]['domicile'];?></td>
		</tr>
        <tr>
            <td <?php echo $reportManager->getReportDataStyle()?>><nobr><b>Category</b></nobr></td>
            <td <?php echo $reportManager->getReportDataStyle()?> width="1%" ><nobr><b>:</b></nobr></td>
            <td <?php echo $reportManager->getReportDataStyle()?>><?php echo $studentDataArr[0]['quotaName'];?></td>
        </tr>
		<tr>
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Contact No.</b></nobr></td>
            <td <?php echo $reportManager->getReportDataStyle()?> width="1%" ><nobr><b>:</b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>  colspan='3'>
               <table border='0' cellspacing='0' class="reportTableBorder" width="100%" align="left">
                 <tr>
                    <td <?php echo $reportManager->getReportDataStyle()?> width="25%">
                      <nobr><?php echo ( $studentDataArr[0]['studentPhone'] != "" && $studentDataArr[0]['studentPhone'] != "NULL") ? $studentDataArr[0]['studentPhone'] : "-- ";?></nobr>
                    </td> 
                   <td <?php echo $reportManager->getReportDataStyle()?> width="8%" ><nobr><b>Mobile No</b></nobr></td>
                   <td <?php echo $reportManager->getReportDataStyle()?> width="1%" ><nobr><b>:</b></nobr></td>
                   <td <?php echo $reportManager->getReportDataStyle()?> width="20%"><nobr><?php echo ( $studentDataArr[0]['studentMobileNo'] != ""  && $studentDataArr[0]['studentMobileNo'] != "NULL") ? $studentDataArr[0]['studentMobileNo'] : "-- ";?></nobr></td>
                   <td <?php echo $reportManager->getReportDataStyle()?> width="8%"><nobr><b>&nbsp;</b></nobr></td>
                   <td <?php echo $reportManager->getReportDataStyle()?> width="1%" ><nobr><b>&nbsp;</b></nobr></td>
                   <td <?php echo $reportManager->getReportDataStyle()?> width="21%">&nbsp;</td>
                 </tr>  
               </table>  
            </td>
		</tr>
		<tr>	
			<td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Comp. Exam. By</b></nobr></td>
            <td <?php echo $reportManager->getReportDataStyle()?> width="1%" ><nobr><b>:</b></nobr></td>
			<td <?php echo $reportManager->getReportDataStyle()?>  colspan='3'>
               <table border='0' cellspacing='0' class="reportTableBorder" width="100%" align="left">
                 <tr>
                    <td <?php echo $reportManager->getReportDataStyle()?> width="25%">
                      <nobr><?php echo ( $studentDataArr[0]['compExamBy'] != "" && $studentDataArr[0]['compExamBy'] != "NULL") ? $results[$studentDataArr[0]['compExamBy']] : "-- ";?></nobr>
                   </td> 
                   <td <?php echo $reportManager->getReportDataStyle()?> width="11%"><nobr><b>Roll No.</b></nobr></td> 
                   <td <?php echo $reportManager->getReportDataStyle()?> width="1%" ><nobr><b>:</b></nobr></td>
                   <td <?php echo $reportManager->getReportDataStyle()?> width="20%"><nobr><?php echo ( $studentDataArr[0]['compExamRollNo'] != "" && $studentDataArr[0]['compExamRank'] != "NULL") ? $studentDataArr[0]['compExamRollNo'] : "-- ";?></nobr></td>
                   <td <?php echo $reportManager->getReportDataStyle()?> width="8%"><nobr><b>Rank</b></nobr></td>
                   <td <?php echo $reportManager->getReportDataStyle()?> width="1%" ><nobr><b>:</b></nobr></td>
                   <td <?php echo $reportManager->getReportDataStyle()?> width="21%"><nobr><?php echo ( $studentDataArr[0]['compExamRank'] != "" && $studentDataArr[0]['compExamRank'] != "NULL") ? $studentDataArr[0]['compExamRank'] : "-- ";?></nobr></td>
                 </tr>  
               </table> 
            </td>   
		</tr>
		<tr>
			<td colspan='6' height="20" <?php echo $reportManager->getReportDataStyle()?>><B><U>CORRESPONDENCE ADDRESS</U></B></td>
		</tr>
		<tr>	
			<td colspan="6" <?php echo $reportManager->getReportDataStyle()?> height="20" valign="top" width="40%"><?php 
			echo ( $studentDataArr[0]['corrAddress1'] != "" && $studentDataArr[0]['corrAddress1'] != "NULL" ) ? $studentDataArr[0]['corrAddress1']."<br>" : " "; 

			echo ( $studentDataArr[0]['corrAddress2'] != "" && $studentDataArr[0]['corrAddress2'] != "NULL" ) ? " ".$studentDataArr[0]['corrAddress2']."<br>" : " ";
			
			echo ( $studentDataArr[0]['corrCity'] != "" && $studentDataArr[0]['corrCity'] != "NULL" ) ? " ".$studentDataArr[0]['corrCity'] : " ";

			echo ( $studentDataArr[0]['corrState'] != "" && $studentDataArr[0]['corrState'] != "NULL") ? " ".$studentDataArr[0]['corrState'] : " ";

			echo ( $studentDataArr[0]['corrCountry'] != "" &&  $studentDataArr[0]['corrCountry'] != "NULL") ? " ".$studentDataArr[0]['corrCountry'] : " ";

			echo ( $studentDataArr[0]['corrPinCode'] != "" &&  $studentDataArr[0]['corrPinCode'] != "NULL" ) ? " "."-".$studentDataArr[0]['corrPinCode'] : " ";

			?></td>
		</tr>
		<tr>
			<td valign="top" height="5" colspan='4'></td>
		</tr> 
		<tr>
			<td colspan='6' height="20" <?php echo $reportManager->getReportDataStyle()?>><B><U>PARENTS DETAILS</U></B></td>
		</tr>
		<tr>
            <td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Father's Name</b></nobr></td>
            <td <?php echo $reportManager->getReportDataStyle()?> width="1%" ><nobr><b>:</b></nobr></td>
            <td colspan='4' <?php echo $reportManager->getReportDataStyle()?>><?php 
            echo ( $studentDataArr[0]['fatherName'] != "" &&  $studentDataArr[0]['fatherName'] != "NULL" ) ? " ".$studentDataArr[0]['fatherName'] : NOT_APPLICABLE_STRING;
            ?></td>
        </tr>
		<tr>
            <td <?php echo $reportManager->getReportDataStyle()?> height="20"><nobr><b>Mother's Name</b></nobr></td>
            <td <?php echo $reportManager->getReportDataStyle()?> width="1%" ><nobr><b>:</b></nobr></td>
            <td colspan='4' <?php echo $reportManager->getReportDataStyle()?>><?php 
            echo ( $studentDataArr[0]['motherName'] != "" &&  $studentDataArr[0]['motherName'] != "NULL" ) ? " ".$studentDataArr[0]['motherName'] : NOT_APPLICABLE_STRING;
            ?></td>
        </tr>
        <tr>
            <td valign="top" height="5" colspan='4'></td>
        </tr> 
        <tr>
            <td colspan='6' height="20" <?php echo $reportManager->getReportDataStyle()?>><B><U>VISITOR DETAILS</U></B></td>
        </tr>
        <tr>
           <td <?php echo $reportManager->getReportDataStyle()?>  colspan='6'>
               <table border='0' cellspacing='0' class="reportTableBorder" width="100%" align="left">
                 <tr>
                   <td <?php echo $reportManager->getReportDataStyle()?> width="17%">
                      <nobr><b>Purpose of visit</b></nobr>
                   </td> 
                   <td <?php echo $reportManager->getReportDataStyle()?> width="1%" ><nobr><b>:</b></nobr></td>
                   <td <?php echo $reportManager->getReportDataStyle()?>>
                    <?php echo ($studentDataArr[0]['visitPurpose'] != "" &&  $studentDataArr[0]['visitPurpose'] != "NULL" ) ? " ".$studentDataArr[0]['visitPurpose'] : NOT_APPLICABLE_STRING; ?>
                   </td>
                </tr>   
                <tr>
                   <td <?php echo $reportManager->getReportDataStyle()?> width="17%">
                       <nobr><b>Visitor Name</b></nobr>
                   </td>
                   <td <?php echo $reportManager->getReportDataStyle()?> width="1%" ><nobr><b>:</b></nobr></td>
                   <td <?php echo $reportManager->getReportDataStyle()?>>
                    <?php echo ($studentDataArr[0]['visitorName'] != "" &&  $studentDataArr[0]['visitorName'] != "NULL" ) ? " ".$studentDataArr[0]['visitorName'] : NOT_APPLICABLE_STRING; ?>
                   </td> 
                </tr>   
                <tr>
                   <td <?php echo $reportManager->getReportDataStyle()?> width="17%">
                       <nobr><b>Source from</b></nobr>
                   </td>
                   <td <?php echo $reportManager->getReportDataStyle()?> width="1%" ><nobr><b>:</b></nobr></td>
                   <td <?php echo $reportManager->getReportDataStyle()?>>
                    <?php 
                        $visitSource = '';
                        if($studentDataArr[0]['visitSource']!='') {
                            global $visitorSource; 
                            $result1 = explode('~',$studentDataArr[0]['visitSource']);
                            for($i=0;$i<count($result1);$i++) {
                               $id = $result1[$i];
                               if($visitorSource[$id]!='') {
                                 if($visitSource == '') {
                                   $visitSource = $visitorSource[$id];  
                                 }
                                 else {
                                   $visitSource .= ", ".$visitorSource[$id];
                                 }  
                               }
                            }
                            if(trim($studentDataArr[0]['paperName'])!='') {
                                if($visitSource == '') {
                                   $visitSource = $studentDataArr[0]['paperName']; 
                                }    
                                else {
                                   $visitSource .= ", ".$studentDataArr[0]['paperName'];  
                                }
                            }
                        }
                        echo ($visitSource != "" &&  $visitSource != "NULL" ) ? " ".$visitSource: NOT_APPLICABLE_STRING; 
                    ?>
                   </td> 
                </tr>   
              </table> 
            </td>   
        </tr>
		</table>
		
		<table border='0' cellspacing='0' class="reportTableBorder" width="90%" align="center">
		<tr>
			<td valign="top" height="10" colspan='6'></td>
		</tr>
		<tr>	
			<td <?php echo $reportManager->getReportDataStyle()?> width="17%" height="20" ><nobr><b>Student Remarks<Br></b></nobr></td>
            <td <?php echo $reportManager->getReportDataStyle()?> width="1%" ><nobr><b>:</b></nobr></td>
            <td <?php echo $reportManager->getReportDataStyle()?> height="20" colspan="3"><nobr><?php echo ( $studentDataArr[0]['studentRemarks'] != "" && $studentDataArr[0]['studentRemarks'] != "NULL") ? nl2br($studentDataArr[0]['studentRemarks']) : "-- ";?></td>
		</tr>
		</table>
		<br>
		<table border='0' cellspacing='0' cellpadding='0' width="90%" align="center">
		<tr>
			<td valign='' align="left" colspan="<?php echo count($reportManager->tableHeadArray)?>" <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
		</tr>
		</table>
<?php 
// $History: studentEnquiryProfilePrint.php $
//
//*****************  Version 8  *****************
//User: Parveen      Date: 3/05/10    Time: 4:58p
//Updated in $/LeapCC/Templates/StudentEnquiry
//validation & condition format updated 
//
//*****************  Version 7  *****************
//User: Parveen      Date: 6/03/09    Time: 10:36a
//Updated in $/LeapCC/Templates/StudentEnquiry
//set rights permission 
//
//*****************  Version 6  *****************
//User: Parveen      Date: 6/01/09    Time: 11:45a
//Updated in $/LeapCC/Templates/StudentEnquiry
//validation & format update
//
//*****************  Version 5  *****************
//User: Parveen      Date: 5/30/09    Time: 6:26p
//Updated in $/LeapCC/Templates/StudentEnquiry
//validation checks & spelling correct 
//
//*****************  Version 4  *****************
//User: Administrator Date: 30/05/09   Time: 17:57
//Updated in $/LeapCC/Templates/StudentEnquiry
//Corrected bugs
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/30/09    Time: 12:14p
//Updated in $/LeapCC/Templates/StudentEnquiry
//enquryDate added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/30/09    Time: 11:27a
//Updated in $/LeapCC/Templates/StudentEnquiry
//formating & conditions update
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/29/09    Time: 6:26p
//Created in $/LeapCC/Templates/StudentEnquiry
//initial checkin
//

?>